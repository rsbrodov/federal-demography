<?php

namespace backend\modules\dropdown\controllers;

use common\models\Municipality;
use common\models\Organization;
use common\models\Region;
use Yii;
use yii\web\Controller;

/**
 * Default controller for the `dropdown` module
 */
class DropDownController extends Controller
{
    /*Подставляет организации города в выпадающий список*/
    public function actionOrgcity($id){
        $cities = Organization::find()->where(['city_id' => $id])->all();
        $return = '<option value="0">Все организации района</option>';
        if(!empty($cities)){
            foreach ($cities as $key => $city) {
                $return .= '<option value="'.$city->id.'">'.$city->short_title.'</option>';
            }
        }
        return $return;
    }



    /*Подставляет организации в выпадающий список*/
//    public function actionOrganizationlist($id){
////        if($id == 0){
////            if (Yii::$app->user->can('rospotrebnadzor_camp')){
////                $groups = Organization::find()->where(['region_id' => $region_id,'type_org' => 3])->orderby(['title' => SORT_ASC])->all();
////            }else{
////                $groups = Organization::find()->where(['region_id' => $region_id,'type_org' => 4])->orderby(['title' => SORT_ASC])->all();
////            }
////        }else{
//            if (Yii::$app->user->can('rospotrebnadzor_camp')){
//                $groups = Organization::find()->where(['municipality_id'=>$id, 'type_org' => 4])->orderby(['title' => SORT_ASC])->all();
//            }else{
//                $groups = Organization::find()->where(['municipality_id'=>$id, 'type_org' => 3])->orderby(['title' => SORT_ASC])->all();
//            }
//        //}
//        $return= '<option value="0">Все организации11...</option>';
//        if(!empty($groups)){
//            foreach ($groups as $key => $group) {
//                $return .= '<option value="'.$group->id.'">'.$group->title.'</option>';
//            }
//        }
//        return $return;
//    }



    /*public function actionOrglist2($id){
        //Если Вы организатор питания
        $return= '';
        $groups = Organization::find()->where(['municipality_id'=>$id, 'type_org' => 1])->orderby(['title' => SORT_ASC])->all();
        $return .=  '<option value=" ">Выберите организацию...</option>';

        if(!empty($groups)){
            foreach ($groups as $key => $group) {
                $return .=  '<option value="'.$group->id.'">'.$group->title.'</option>';
            }
        }
        return $return;
    }*/

    /*Подставляет муниципальные образования в выпадающий список*/
    public function actionMunicipalityList($id){
        $groups = Municipality::find()->where(['region_id'=>$id])->orderby(['name' => SORT_ASC])->all();
        $return = '<option value=" ">Выберите муниципальное образование...</option>';
        if(!empty($groups)){
            foreach ($groups as $key => $group) {
                $return .= '<option value="'.$group->id.'">'.$group->name.'</option>';
            }
        }
        return $return;
    }

    /*Подставляет регионы в выпадающий список ЛАГЕРЯ ВСЕ РЕГИОНЫ*/
    //public function actionSubjectslist($id){
    public function actionRegionList($id){
        $groups = Region::find()->where(['district_id'=>$id])->orderby(['name' => SORT_ASC])->all();
        $return = '<option value=" ">Выберите регион...</option>';
        if(!empty($groups)){
            foreach ($groups as $key => $group) {
                $return .= '<option value="'.$group->id.'">'.$group->name.'</option>';
            }
        }
        return $return;
    }
    //Выпадающий список в разделе заявок на сотрудничество между операторами и школами и лагерями
    public function actionOrganizationListApplications($id){
        $json = array();
        //Если Вы организатор питания
        if(Organization::findOne(Yii::$app->user->identity->organization_id)->type_org == 4){
            $groups = Organization::find()->where(['municipality_id'=>$id, 'type_org' => [3, 1]])->orderby(['title' => SORT_ASC])->all();
            $json .= '<option value=" ">Выберите организацию...</option>';
        }
        //Если Вы представитель школы
        if(Organization::findOne(Yii::$app->user->identity->organization_id)->type_org == 3 || Organization::findOne(Yii::$app->user->identity->organization_id)->type_org == 1){
            $groups = Organization::find()->where(['municipality_id'=>$id, 'type_org' => 4])->orderby(['title' => SORT_ASC])->all();
            $json .= '<option value=" ">Выберите организатора питания...</option>';
        }
        if(!empty($groups)){
            foreach ($groups as $key => $group) {
                if($group->type_org == 1){
                    $json .= '<option value="'.$group->id.'">'.$group->title.'(Лагерь)</option>';
                }else{
                    $json .= '<option value="'.$group->id.'">'.$group->title.'</option>';

                }
            }
        }
        return $json;
    }
    //Список всех школ по мун райну с выборкой всех организаций
    public function actionOrganizationListAll($id){
        $return= '';
        $groups = Organization::find()->where(['municipality_id'=>$id, 'type_org' => 3])->orderby(['title' => SORT_ASC])->all();
        $return .= '<option value="0">Все организации...</option>';


        if(!empty($groups)){
            foreach ($groups as $key => $group) {
                $return .= '<option value="'.$group->id.'">'.$group->title.'</option>';
            }
        }
        return $return;
    }

    /*Для роспотреба лагерей*/
    public function actionOrglist($id){
        $organization_id = Yii::$app->user->identity->organization_id;
        $region_id = Organization::findOne($organization_id)->region_id;

        if($id == 0){
            if (Yii::$app->user->can('rospotrebnadzor_camp')){
                $groups = Organization::find()->where(['region_id' => $region_id,'type_org' => 3])->orderby(['title' => SORT_ASC])->all();
            }else{
                $groups = Organization::find()->where(['region_id' => $region_id,'type_org' => 4])->orderby(['title' => SORT_ASC])->all();
            }

            echo '<option value="0">Все организации...</option>';
        }else{
            if (Yii::$app->user->can('rospotrebnadzor_camp')){
                $groups = Organization::find()->where(['region_id' => $region_id, 'municipality_id'=>$id, 'type_org' => 4])->orderby(['title' => SORT_ASC])->all();
            }else{
                $groups = Organization::find()->where(['region_id' => $region_id, 'municipality_id'=>$id, 'type_org' => 3])->orderby(['title' => SORT_ASC])->all();
            }
            echo '<option value="0">Все организации...</option>';
        }

        if(!empty($groups)){
            foreach ($groups as $key => $group) {
                echo '<option value="'.$group->id.'">'.$group->title.'</option>';
            }
        }
    }
}
