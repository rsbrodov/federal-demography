<?php

namespace backend\modules\menus\controllers;

use common\models\Days;
use common\models\MenuForm;
use common\models\Menus;
use common\models\MenusDays;
use common\models\MenusDishes;
use common\models\MenuSearch;
use common\models\MenusNutrition;
use common\models\MenusOrgRpnMinobr;
use common\models\MenusSend;
use common\models\MenusVariativity;
use common\models\NutritionInfo;
use common\models\Organization;
use common\models\Variativity;
use Yii;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\web\Controller;
use yii\web\NotFoundHttpException;


class MenusController extends Controller
{
    public function actionScriptReplace()
    {
        $count = 0;
        // $menus = Menus::find()->where(['>', 'food_director', 0])->count();//798 отправленых оргпитами меню в организации.
        $menus_orgpit_sends = Menus::find()->where(['>', 'food_director', 0])->all();//798 отправленых оргпитами меню в организации.
        foreach($menus_orgpit_sends as $menus_orgpit_send){
            $menus_org_pit = Menus::find()->where(['organization_id' => $menus_orgpit_send->food_director])->all();
            $check_in = 0;
            foreach ($menus_org_pit as $menu_org_pit){
                if($check_in == 0 && $menus_orgpit_send->name == $menu_org_pit->name){
                    $count++;$check_in++;
                    //print_r($count.'<br>');
                    $menus_send = new MenusSend();
                    $menus_send->sender_org_id = $menus_orgpit_send->food_director;
                    $menus_send->reciever_org_id = $menus_orgpit_send->organization_id;
                    $menus_send->reciever_type_org = 3;
                    $menus_send->sender_menu_id = $menu_org_pit->id;
                    $menus_send->reciever_menu_id = $menus_orgpit_send->id;
                    $menus_send->created_at = $menus_orgpit_send->created_at;
                    $menus_send->save();
                }
            }
        }
        exit;

    }
}
