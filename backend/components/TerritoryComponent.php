<?php
namespace app\components;

use common\models\Organization;
use Yii;
use yii\base\Component;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class TerritoryComponent extends Component{
    public function init(){
        parent::init();
    }

    public function municipalities($region_id=null, $mas=true, $zero = true){
        if($region_id!=null) {
            $municipalities = \common\models\Municipality::find()->where(['region_id' => $region_id])->all();
        }else{
            $municipalities = \common\models\Municipality::find()->where(['region_id' => Organization::findOne(Yii::$app->user->identity->organization_id)->region_id])->all();
        }

        if($mas == false){
            return $municipalities;
        }else{
            $municipality_items = ArrayHelper::map($municipalities, 'id', 'name');
            if($zero == true){
                $municipality_null = array(0 => 'ПО ВСЕМУ РЕГИОНУ...');
                $municipality_items = ArrayHelper::merge($municipality_null, $municipality_items);
            }
            return $municipality_items;
        }

    }

    public function my_municipality($mun_id=null, $mas=true){
        if($mun_id!=null) {
            $municipalities = \common\models\Municipality::find()->where(['id' => $mun_id])->all();
        }else{
            $municipalities = \common\models\Municipality::find()->where(['id' => Organization::findOne(Yii::$app->user->identity->organization_id)->municipality_id])->all();
        }

        if($mas == false){
            return $municipalities;
        }else{
            $municipality_items = ArrayHelper::map($municipalities, 'id', 'name');
            return $municipality_items;
        }
    }

    public function my_municipality_id(){
        $municipality = \common\models\Municipality::findOne(Organization::findOne(Yii::$app->user->identity->organization_id)->municipality_id)->id;
        return $municipality;
    }


    public function organizations($municipality_id=null, $type_org=3, $mas=true, $zero = true){
        if($municipality_id!=null) {
            $organizations = Organization::find()->where(['type_org' => $type_org, 'municipality_id' => $municipality_id])->all();
        }else{
            $organizations = Organization::find()->where(['type_org' => $type_org, 'municipality_id' => Organization::findOne(Yii::$app->user->identity->organization_id)->municipality_id])->all();
        }

        if($mas == false){
            return $organizations;
        }else{
            $organizations_items = ArrayHelper::map($organizations, 'id', 'title');
            if($zero == true){
                $organization_null = array(0 => 'Все организации ...');
                $organizations_items = ArrayHelper::merge($organization_null, $organizations_items);
            }
            return $organizations_items;
        }
    }

}
?>