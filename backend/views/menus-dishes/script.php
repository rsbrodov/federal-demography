<?php

use common\models\CharactersStolovaya;
use common\models\Organization;
use common\models\SchoolBreak;
use yii\bootstrap4\Html;
use yii\grid\GridView;
use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Button;
use common\models\Menus;
use common\models\Days;
use common\models\MenusDays;
use common\models\MenusDishes;
use common\models\MenusNutrition;
use common\models\NutritionInfo;
use yii\helpers\ArrayHelper;


/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Скрипт меню';
$this->params['breadcrumbs'][] = $this->title;

$vitamins_mas['kkal'] = 'Калорийность, ккал.';
$vitamins_mas['protein'] = 'Количество белков (г)';
$vitamins_mas['fat'] = 'Количество жиров (г)';
$vitamins_mas['carbohydrates'] = 'Количество углеводов (г)';
$vitamins_mas['vitamin_c'] = 'Витамин С, мг';
$vitamins_mas['vitamin_b1'] = 'Витамин В1, мг';
$vitamins_mas['vitamin_b2'] = 'Витамин В2, мг';
$vitamins_mas['vitamin_a'] = 'Витамин А, мкг рэ';
$vitamins_mas['ca'] = 'Кальций, мг';
$vitamins_mas['mg'] = 'Магний, мг';
$vitamins_mas['fe'] = 'Железо, мг ';
$vitamins_mas['p'] = 'Калий, мг ';
$vitamins_mas['i'] = 'Йод, мкг ';
$vitamins_mas['se'] = 'Селен, мкг ';

$salt_sahar_yield_mas['salt'] = 'salt';
$salt_sahar_yield_mas['sahar'] = 'sahar';
$salt_sahar_yield_mas['yield'] = 'yield';

$feeders_characters = \common\models\FeedersCharacters::find()->all();

$organization_id = Yii::$app->user->identity->organization_id;
$region_id = Organization::findOne($organization_id)->region_id;

    //$municipalities = \common\models\Municipality::find()->where(['region_id' => $region_id])->all();
   //$municipalities = \common\models\Municipality::find()->where(['id' => [221,222,223,224,225,226,227,228,229,230,231,232,233]])->all();//баганский
   //$municipalities = \common\models\Municipality::find()->where(['id' => [234,235,236,237,238,239,240,241,242,243,244,245,246,247]])->all();//баганский
   $municipalities = \common\models\Municipality::find()->where(['id' => [248,249,250,251,252,253,254,255]])->all();//баганский
   //$municipalities = \common\models\Municipality::find()->where(['id' => [248]])->all();//баганский
    //$municipality_items = ArrayHelper::map($municipalities, 'id', 'name');

$normativ = [];
    $nutrition_koeff_z = \common\models\NutritionProcent::find()->where(['type_org' => 3, 'nutrition_id' => 1])->one()->procent/100;
    $nutrition_koeff_o = \common\models\NutritionProcent::find()->where(['type_org' => 3, 'nutrition_id' => 3])->one()->procent/100;



$normativ = [];
foreach ($vitamins_mas as $key => $vitamin_m){
    $normativ[1][$key] = \common\models\NormativVitaminDay::find()->where(['name' => $key, 'age_info_id' => 6])->one()->value*$nutrition_koeff_z;
    $normativ[3][$key] = \common\models\NormativVitaminDay::find()->where(['name' => $key, 'age_info_id' => 6])->one()->value*$nutrition_koeff_o;
}
foreach ($salt_sahar_yield_mas as $key => $salt_sahar_yield_m){
    $normativ[1][$key] = \common\models\NormativVitaminDayNew::find()->where(['name' => $key, 'age_info_id' => 6, 'nutrition_id' => 1])->one()->value;
    $normativ[3][$key] = \common\models\NormativVitaminDayNew::find()->where(['name' => $key, 'age_info_id' => 6, 'nutrition_id' => 3])->one()->value;
}
?>
<style>
    th, td {
        border: 1px solid black!important;
        color: black;

    }
    thead, th {
        background-color: #ede8b9;
        font-size: 15px;
    }
</style>
    <table class="table table-bordered table-sm">
        <thead>
        <tr>
            <td rowspan="3">№</td>
            <td rowspan="3">Район</td>
            <td rowspan="3">Организация</td>
            <td rowspan="3">Меню</td>
            <td rowspan="3">Характеристика меню</td>
            <td rowspan="3">Возрастная группа</td>
            <td rowspan="3">Кол-во организаций вн. меню</td>
            <td rowspan="3">Кол-во организаций зарегистрир.</td>
            <td colspan="18">ЗАВТРАКИ</td>

            <td rowspan="2" colspan="6">количество дней в меню, предусматривающих выдачу в завтрак</td>
            <td rowspan="3">Количество меню с дефицитом калорийности блюд(ЗАВТРАК)</td>
            <td colspan="18">ОБЕДЫ</td>

            <td rowspan="2" colspan="6">количество дней в меню, предусматривающих выдачу в Обед</td>
            <td rowspan="3">Количество меню с дефицитом калорийности блюд(ОБЕД)</td>
        </tr>
        <tr>
            <!--ЗАВТРАК-->
            <td rowspan="2">Количество меню</td>
            <td >суммарная масса блюд (г.)</td>
            <td >Белки (г.)</td>
            <td >Жиры (г.)</td>
            <td >Углеводы (г.)</td>
            <td>калорийность (ккал)</td>
            <td colspan="4">Содержание витаминов</td>
            <td colspan="6">Содержание минеральных веществ</td>
            <td colspan="2">Содержание в среднем за прием пищи</td>

            <!--ОБЕД-->
            <td rowspan="2">Количество меню</td>
            <td >суммарная масса блюд (г.)</td>
            <td >Белки (г.)</td>
            <td >Жиры (г.)</td>
            <td >Углеводы (г.)</td>
            <td >калорийность (ккал)</td>
            <td colspan="4">Содержание витаминов</td>
            <td colspan="6">Содержание минеральных веществ</td>
            <td colspan="2">Содержание в среднем за прием пищи</td>
        </tr>
        <tr>
            <td>ср</td>
            <td>ср</td>
            <td>ср</td>
            <td>ср</td>
            <td>ср</td>
            <td>С(мг)</td>
            <td>В1(мг)</td>
            <td>В2(мг)</td>
            <td>А (р.э.)</td>
            <td>кальций (мг)</td>
            <td>фосфор (мг)</td>
            <td>магний (мг)</td>
            <td>железо (мг)</td>
            <td>йод (мг)</td>
            <td>селен (мг)</td>
            <td>соли (г)</td>
            <td>сахара (г)</td>
            <td>колбасных изделий</td>
            <td>кондитерских изделий</td>
            <td>фруктов</td>
            <td>ягод</td>
            <td>меда</td>
            <td>овощей</td>


            <!--ОБЕД-->
            <td>ср</td>
            <td>ср</td>
            <td>ср</td>
            <td>ср</td>
            <td>ср</td>
            <td>С(мг)</td>
            <td>В1(мг)</td>
            <td>В2(мг)</td>
            <td>А (р.э.)</td>
            <td>кальций (мг)</td>
            <td>фосфор (мг)</td>
            <td>магний (мг)</td>
            <td>железо (мг)</td>
            <td>йод (мг)</td>
            <td>селен (мг)</td>
            <td>соли (г)</td>
            <td>сахара (г)</td>
            <td>колбасных изделий</td>
            <td>кондитерских изделий</td>
            <td>фруктов</td>
            <td>ягод</td>
            <td>меда</td>
            <td>овощей</td>
        </tr>


        </thead>
        <tbody>

        <tr class="table-success text-center">
            <td colspan="9">Норматив 7-11 лет</td>
            <td><?=$normativ[1]['yield']?></td>
            <td><?=$normativ[1]['protein']?></td>
            <td><?=$normativ[1]['fat']?></td>
            <td><?=$normativ[1]['carbohydrates']?></td>
            <td><?=$normativ[1]['kkal']?></td>
            <td><?=$normativ[1]['vitamin_c']?></td>
            <td><?=$normativ[1]['vitamin_b1']?></td>
            <td><?=$normativ[1]['vitamin_b2']?></td>
            <td><?=$normativ[1]['vitamin_a']?></td>
            <td><?=$normativ[1]['ca']?></td>
            <td><?=$normativ[1]['p']?></td>
            <td><?=$normativ[1]['mg']?></td>
            <td><?=$normativ[1]['fe']?></td>
            <td><?=$normativ[1]['i']?></td>
            <td><?=$normativ[1]['se']?></td>
            <td><?=$normativ[1]['salt']?></td>
            <td><?=$normativ[1]['sahar']?></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td><?=$normativ[3]['yield']?></td>
            <td><?=$normativ[3]['protein']?></td>
            <td><?=$normativ[3]['fat']?></td>
            <td><?=$normativ[3]['carbohydrates']?></td>
            <td><?=$normativ[3]['kkal']?></td>
            <td><?=$normativ[3]['vitamin_c']?></td>
            <td><?=$normativ[3]['vitamin_b1']?></td>
            <td><?=$normativ[3]['vitamin_b2']?></td>
            <td><?=$normativ[3]['vitamin_a']?></td>
            <td><?=$normativ[3]['ca']?></td>
            <td><?=$normativ[3]['p']?></td>
            <td><?=$normativ[3]['mg']?></td>
            <td><?=$normativ[3]['fe']?></td>
            <td><?=$normativ[3]['i']?></td>
            <td><?=$normativ[3]['se']?></td>
            <td><?=$normativ[3]['salt']?></td>
            <td><?=$normativ[3]['sahar']?></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>

        <?$model = new MenusDishes(); foreach ($municipalities as $mun_item){ $organizations = Organization::find()->where(['municipality_id' => $mun_item->id, 'type_org' => 3])->andWhere(['!=','id', 7])->all();?>
        <? $count = 0; $count_org = []; $sred = []; $itog_string = []; $type_string = []; foreach ($organizations as $organization) { ?>
           <? $menus = \common\models\Menus::find()->where(['organization_id' => $organization->id, 'age_info_id' => [6, 9], 'cycle' => [2,3,4], 'status_archive' => 0])->andWhere(['>=', 'date_end', strtotime(date('d.m.y'))])->all();
               foreach ($menus as $menu){?>
                   <?$menu_zavtrak = $model->get_menu_information_one($menu->id, 1);
                   $menu_obed = $model->get_menu_information_one($menu->id, 3);
                   if($menu_zavtrak != "null" || $menu_obed != "null"){
                       if(!array_key_exists($organization->id, $count_org)){
                           $count_org[$organization->id] = $organization->id;
                       }

                       $normativ = [];
                       foreach ($vitamins_mas as $key => $vitamin_m){
                           $normativ[1][$key] = \common\models\NormativVitaminDay::find()->where(['name' => $key, 'age_info_id' => $menu->age_info_id])->one()->value*$nutrition_koeff_z;
                           $normativ[3][$key] = \common\models\NormativVitaminDay::find()->where(['name' => $key, 'age_info_id' => $menu->age_info_id])->one()->value*$nutrition_koeff_o;
                       }
                       foreach ($salt_sahar_yield_mas as $key => $salt_sahar_yield_m){
                           $normativ[1][$key] = \common\models\NormativVitaminDayNew::find()->where(['name' => $key, 'age_info_id' => $menu->age_info_id, 'nutrition_id' => 1])->one()->value;
                           $normativ[3][$key] = \common\models\NormativVitaminDayNew::find()->where(['name' => $key, 'age_info_id' => $menu->age_info_id, 'nutrition_id' => 3])->one()->value;
                       }
                       ?>
                       <tr>
                       <td class="text-center align-middle"><?$count++;echo $count;?></td>
                       <td class="text-center align-middle"><?=$mun_item->name?></td>
                       <td class="text-center align-middle"><?=empty($organization->title) ?  $organization->short_title:$organization->title?></td>
                       <td class="text-center align-middle"><?=$menu->name?></td>
                       <td class="text-center align-middle"><?=\common\models\FeedersCharacters::findOne($menu->feeders_characters_id)->name?></td>
                       <td class="text-center align-middle"><?=\common\models\AgeInfo::findOne($menu->age_info_id)->name?></td>
                       <td class="text-center align-middle table-secondary">-</td>
                       <td class="text-center align-middle table-secondary">-</td>
                       <?if($menu_zavtrak!= "null"){?>
                           <?$type_string[$menu->feeders_characters_id]['menu_zavtrak_yield'] = $type_string[$menu->feeders_characters_id]['menu_zavtrak_yield'] + round($menu_zavtrak['yield'],1);
                           $type_string[$menu->feeders_characters_id]['menu_zavtrak_count'] = $type_string[$menu->feeders_characters_id]['menu_zavtrak_count'] + 1;
                           $type_string[$menu->feeders_characters_id]['menu_zavtrak_protein'] = $type_string[$menu->feeders_characters_id]['menu_zavtrak_protein'] + round($menu_zavtrak['protein'],1);
                           $type_string[$menu->feeders_characters_id]['menu_zavtrak_fat'] = $type_string[$menu->feeders_characters_id]['menu_zavtrak_fat'] + round($menu_zavtrak['fat'],1);
                           $type_string[$menu->feeders_characters_id]['menu_zavtrak_carbohydrates'] = $type_string[$menu->feeders_characters_id]['menu_zavtrak_carbohydrates'] + round($menu_zavtrak['carbohydrates'],1);
                           $type_string[$menu->feeders_characters_id]['menu_zavtrak_kkal'] = $type_string[$menu->feeders_characters_id]['menu_zavtrak_kkal'] + round($menu_zavtrak['kkal'],1);
                           $type_string[$menu->feeders_characters_id]['menu_zavtrak_vitamin_c'] = $type_string[$menu->feeders_characters_id]['menu_zavtrak_vitamin_c'] + round($menu_zavtrak['vitamin_c'],1);
                           $type_string[$menu->feeders_characters_id]['menu_zavtrak_vitamin_b1'] = $type_string[$menu->feeders_characters_id]['menu_zavtrak_vitamin_b1'] + round($menu_zavtrak['vitamin_b1'],1);
                           $type_string[$menu->feeders_characters_id]['menu_zavtrak_vitamin_b2'] = $type_string[$menu->feeders_characters_id]['menu_zavtrak_vitamin_b2'] + round($menu_zavtrak['vitamin_b2'],1);
                           $type_string[$menu->feeders_characters_id]['menu_zavtrak_vitamin_a'] = $type_string[$menu->feeders_characters_id]['menu_zavtrak_vitamin_a'] + round($menu_zavtrak['vitamin_a'],1);
                           $type_string[$menu->feeders_characters_id]['menu_zavtrak_ca'] = $type_string[$menu->feeders_characters_id]['menu_zavtrak_ca'] + round($menu_zavtrak['ca'],1);
                           $type_string[$menu->feeders_characters_id]['menu_zavtrak_p'] = $type_string[$menu->feeders_characters_id]['menu_zavtrak_p'] + round($menu_zavtrak['p'],1);
                           $type_string[$menu->feeders_characters_id]['menu_zavtrak_mg'] = $type_string[$menu->feeders_characters_id]['menu_zavtrak_mg'] + round($menu_zavtrak['mg'],1);
                           $type_string[$menu->feeders_characters_id]['menu_zavtrak_fe'] = $type_string[$menu->feeders_characters_id]['menu_zavtrak_fe'] + round($menu_zavtrak['fe'],1);
                           $type_string[$menu->feeders_characters_id]['menu_zavtrak_i'] = $type_string[$menu->feeders_characters_id]['menu_zavtrak_i'] + round($menu_zavtrak['i'],1);
                           $type_string[$menu->feeders_characters_id]['menu_zavtrak_se'] = $type_string[$menu->feeders_characters_id]['menu_zavtrak_se'] + round($menu_zavtrak['se'],1);
                           $type_string[$menu->feeders_characters_id]['menu_zavtrak_salt'] = $type_string[$menu->feeders_characters_id]['menu_zavtrak_salt'] + round($menu_zavtrak['salt'],1);
                           $type_string[$menu->feeders_characters_id]['menu_zavtrak_sahar'] = $type_string[$menu->feeders_characters_id]['menu_zavtrak_sahar'] + round($menu_zavtrak['sahar'],1);

                           $type_string[$menu->feeders_characters_id]['menu_zavtrak_kolbasa_count'] = $type_string[$menu->feeders_characters_id]['menu_zavtrak_kolbasa_count'] + round($menu_zavtrak['kolbasa'],1);
                           $type_string[$menu->feeders_characters_id]['menu_zavtrak_konditer_count'] = $type_string[$menu->feeders_characters_id]['menu_zavtrak_konditer_count'] + round($menu_zavtrak['konditer'],1);
                           $type_string[$menu->feeders_characters_id]['menu_zavtrak_frukti_count'] = $type_string[$menu->feeders_characters_id]['menu_zavtrak_frukti_count'] + round($menu_zavtrak['frukti'],1);
                           $type_string[$menu->feeders_characters_id]['menu_zavtrak_yagoda_count'] = $type_string[$menu->feeders_characters_id]['menu_zavtrak_yagoda_count'] + round($menu_zavtrak['yagoda'],1);
                           $type_string[$menu->feeders_characters_id]['menu_zavtrak_med_count'] = $type_string[$menu->feeders_characters_id]['menu_zavtrak_med_count'] + round($menu_zavtrak['med'],1);
                           $type_string[$menu->feeders_characters_id]['menu_zavtrak_ovoshi_count'] = $type_string[$menu->feeders_characters_id]['menu_zavtrak_ovoshi_count'] + round($menu_zavtrak['ovoshi'],1);
                           if(round($menu_zavtrak['kkal'],1) < $normativ[1]['kkal']){
                               $type_string[$menu->feeders_characters_id]['deficit_blud_zavtrak'] = $type_string[$menu->feeders_characters_id]['deficit_blud_zavtrak']+1;;
                           }


                           ?>
                           <td class="text-center align-middle table-secondary">-</td>
                           <td class="text-center align-middle <?if(round($menu_zavtrak['yield'],1) < $normativ[1]['yield']){echo "bg-danger";}?>"><?= round($menu_zavtrak['yield'],1); $itog_string['menu_zavtrak_yield'] = $itog_string['menu_zavtrak_yield'] + round($menu_zavtrak['yield'],1);$itog_string['menu_zavtrak_count'] = $itog_string['menu_zavtrak_count'] + 1;?></td>
                           <td class="text-center align-middle <?if(round($menu_zavtrak['protein'],1) < $normativ[1]['protein']){echo "bg-danger";}?>"><?= round($menu_zavtrak['protein'],1);$itog_string['menu_zavtrak_protein'] = $itog_string['menu_zavtrak_protein'] + round($menu_zavtrak['protein'],1);?></td>
                           <td class="text-center align-middle <?if(round($menu_zavtrak['fat'],1) < $normativ[1]['fat']){echo "bg-danger";}?>"><?= round($menu_zavtrak['fat'],1);$itog_string['menu_zavtrak_fat'] = $itog_string['menu_zavtrak_fat'] + round($menu_zavtrak['fat'],1);?></td>
                           <td class="text-center align-middle <?if(round($menu_zavtrak['carbohydrates'],1) < $normativ[1]['carbohydrates']){echo "bg-danger";}?>"><?= round($menu_zavtrak['carbohydrates'],1);$itog_string['menu_zavtrak_carbohydrates'] = $itog_string['menu_zavtrak_carbohydrates'] + round($menu_zavtrak['carbohydrates'],1);?></td>
                           <td class="text-center align-middle <?if(round($menu_zavtrak['kkal'],1) < $normativ[1]['kkal']){echo "bg-danger";}?>"><?= round($menu_zavtrak['kkal'],1);$itog_string['menu_zavtrak_kkal'] = $itog_string['menu_zavtrak_kkal'] + round($menu_zavtrak['kkal'],1);?></td>
                           <td class="text-center align-middle <?if(round($menu_zavtrak['vitamin_c'],1) < $normativ[1]['vitamin_c']){echo "bg-danger";}?>"><?= round($menu_zavtrak['vitamin_c'],1);$itog_string['menu_zavtrak_vitamin_c'] = $itog_string['menu_zavtrak_vitamin_c'] + round($menu_zavtrak['vitamin_c'],1);?></td>
                           <td class="text-center align-middle <?if(round($menu_zavtrak['vitamin_b1'],1)< $normativ[1]['vitamin_b1']){echo "bg-danger";}?>"><?= round($menu_zavtrak['vitamin_b1'],1);$itog_string['menu_zavtrak_vitamin_b1'] = $itog_string['menu_zavtrak_vitamin_b1'] + round($menu_zavtrak['vitamin_b1'],1);?></td>
                           <td class="text-center align-middle <?if(round($menu_zavtrak['vitamin_b2'],1) < $normativ[1]['vitamin_b2']){echo "bg-danger";}?>"><?= round($menu_zavtrak['vitamin_b2'],1);$itog_string['menu_zavtrak_vitamin_b2'] = $itog_string['menu_zavtrak_vitamin_b2'] + round($menu_zavtrak['vitamin_b2'],1);?></td>
                           <td class="text-center align-middle <?if(round($menu_zavtrak['vitamin_a'],1) < $normativ[1]['vitamin_a']){echo "bg-danger";}?>"><?= round($menu_zavtrak['vitamin_a'],1);$itog_string['menu_zavtrak_vitamin_a'] = $itog_string['menu_zavtrak_vitamin_a'] + round($menu_zavtrak['vitamin_a'],1);?></td>
                           <td class="text-center align-middle <?if(round($menu_zavtrak['ca'],1) < $normativ[1]['ca']){echo "bg-danger";}?>"><?= round($menu_zavtrak['ca'],1);$itog_string['menu_zavtrak_ca'] = $itog_string['menu_zavtrak_ca'] + round($menu_zavtrak['ca'],1);?></td>
                           <td class="text-center align-middle <?if(round($menu_zavtrak['p'],1) < $normativ[1]['p']){echo "bg-danger";}?>"><?= round($menu_zavtrak['p'],1);$itog_string['menu_zavtrak_p'] = $itog_string['menu_zavtrak_p'] + round($menu_zavtrak['p'],1);?></td>
                           <td class="text-center align-middle <?if(round($menu_zavtrak['mg'],1) < $normativ[1]['mg']){echo "bg-danger";}?>"><?= round($menu_zavtrak['mg'],1);$itog_string['menu_zavtrak_mg'] = $itog_string['menu_zavtrak_mg'] + round($menu_zavtrak['mg'],1);?></td>
                           <td class="text-center align-middle <?if(round($menu_zavtrak['fe'],1) < $normativ[1]['fe']){echo "bg-danger";}?>"><?= round($menu_zavtrak['fe'],1);$itog_string['menu_zavtrak_fe'] = $itog_string['menu_zavtrak_fe'] + round($menu_zavtrak['fe'],1);?></td>
                           <td class="text-center align-middle <?if(round($menu_zavtrak['i'],1) < $normativ[1]['i']){echo "bg-danger";}?>"><?= round($menu_zavtrak['i'],1);$itog_string['menu_zavtrak_i'] = $itog_string['menu_zavtrak_i'] + round($menu_zavtrak['i'],1);?></td>
                           <td class="text-center align-middle <?if(round($menu_zavtrak['se'],1) < $normativ[1]['se']){echo "bg-danger";}?>"><?= round($menu_zavtrak['se'],1);$itog_string['menu_zavtrak_se'] = $itog_string['menu_zavtrak_se'] + round($menu_zavtrak['se'],1);?></td>
                           <td class="text-center align-middle <?if(round($menu_zavtrak['salt'],1) > $normativ[1]['salt']){echo "bg-danger";}?>"><?= round($menu_zavtrak['salt'],1);$itog_string['menu_zavtrak_salt'] = $itog_string['menu_zavtrak_salt'] + round($menu_zavtrak['salt'],1);?></td>
                           <td class="text-center align-middle <?if(round($menu_zavtrak['sahar'],1) > $normativ[1]['sahar']){echo "bg-danger";}?>"><?= round($menu_zavtrak['sahar'],1);$itog_string['menu_zavtrak_sahar'] = $itog_string['menu_zavtrak_sahar'] + round($menu_zavtrak['sahar'],1);?></td>

                           <td class="text-center align-middle <?if(round($menu_zavtrak['kolbasa'],0)>0){echo "bg-warning";}?>"><?= round($menu_zavtrak['kolbasa'],0);$itog_string['menu_zavtrak_kolbasa_count'] = $itog_string['menu_zavtrak_kolbasa_count']+round($menu_zavtrak['kolbasa'],0);?></td>
                           <td class="text-center align-middle <?if(round($menu_zavtrak['konditer'],0)>0){echo "bg-warning";}?>"><?=round($menu_zavtrak['konditer'],0);$itog_string['menu_zavtrak_konditer_count'] = $itog_string['menu_zavtrak_konditer_count']+round($menu_zavtrak['konditer'],0);?></td>
                           <td class="text-center align-middle"><?= round($menu_zavtrak['frukti'] ,0);$itog_string['menu_zavtrak_frukti_count'] = $itog_string['menu_zavtrak_frukti_count']+round($menu_zavtrak['frukti'] ,0);?></td>
                           <td class="text-center align-middle"><?= round($menu_zavtrak['yagoda'] ,0);$itog_string['menu_zavtrak_yagoda_count'] = $itog_string['menu_zavtrak_yagoda_count']+round($menu_zavtrak['yagoda'] ,0);?></td>
                           <td class="text-center align-middle"><?= round($menu_zavtrak['med'] ,0);$itog_string['menu_zavtrak_med_count'] = $itog_string['menu_zavtrak_med_count']+round($menu_zavtrak['med'] ,0);?></td>
                           <td class="text-center align-middle"><?= round($menu_zavtrak['ovoshi'] ,0);$itog_string['menu_zavtrak_ovoshi_count'] = $itog_string['menu_zavtrak_ovoshi_count']+round($menu_zavtrak['ovoshi'] ,0); ?></td>
                           <td class="text-center align-middle <?if(round($menu_zavtrak['kkal'],1) < $normativ[1]['kkal']){echo "bg-danger";}?>"><?if(round($menu_zavtrak['kkal'],1) < $normativ[1]['kkal']){echo 1; $itog_string['deficit_blud_zavtrak'] = $itog_string['deficit_blud_zavtrak']+1;}else{ echo 0;}?></td>
                       <?}else{?>
                           <td class="text-center align-middle table-secondary"></td>
                           <td class="text-center align-middle"></td>
                           <td class="text-center align-middle"></td>
                           <td class="text-center align-middle"></td>
                           <td class="text-center align-middle"></td>
                           <td class="text-center align-middle"></td>
                           <td class="text-center align-middle"></td>
                           <td class="text-center align-middle"></td>
                           <td class="text-center align-middle"></td>
                           <td class="text-center align-middle"></td>
                           <td class="text-center align-middle"></td>
                           <td class="text-center align-middle"></td>
                           <td class="text-center align-middle"></td>
                           <td class="text-center align-middle"></td>
                           <td class="text-center align-middle"></td>
                           <td class="text-center align-middle"></td>
                           <td class="text-center align-middle"></td>
                           <td class="text-center align-middle"></td>
                           <td class="text-center align-middle"></td>
                           <td class="text-center align-middle"></td>
                           <td class="text-center align-middle"></td>
                           <td class="text-center align-middle"></td>
                           <td class="text-center align-middle"></td>
                           <td class="text-center align-middle"></td>
                           <td class="text-center align-middle"></td>
                       <?}?>
                       <?if($menu_obed != "null"){?>

                           <?$type_string[$menu->feeders_characters_id]['menu_obed_yield'] = $type_string[$menu->feeders_characters_id]['menu_obed_yield'] + round($menu_obed['yield'],1);
                           $type_string[$menu->feeders_characters_id]['menu_obed_count'] = $type_string[$menu->feeders_characters_id]['menu_obed_count'] + 1;
                           $type_string[$menu->feeders_characters_id]['menu_obed_protein'] = $type_string[$menu->feeders_characters_id]['menu_obed_protein'] + round($menu_obed['protein'],1);
                           $type_string[$menu->feeders_characters_id]['menu_obed_fat'] = $type_string[$menu->feeders_characters_id]['menu_obed_fat'] + round($menu_obed['fat'],1);
                           $type_string[$menu->feeders_characters_id]['menu_obed_carbohydrates'] = $type_string[$menu->feeders_characters_id]['menu_obed_carbohydrates'] + round($menu_obed['carbohydrates'],1);
                           $type_string[$menu->feeders_characters_id]['menu_obed_kkal'] = $type_string[$menu->feeders_characters_id]['menu_obed_kkal'] + round($menu_obed['kkal'],1);
                           $type_string[$menu->feeders_characters_id]['menu_obed_vitamin_c'] = $type_string[$menu->feeders_characters_id]['menu_obed_vitamin_c'] + round($menu_obed['vitamin_c'],1);
                           $type_string[$menu->feeders_characters_id]['menu_obed_vitamin_b1'] = $type_string[$menu->feeders_characters_id]['menu_obed_vitamin_b1'] + round($menu_obed['vitamin_b1'],1);
                           $type_string[$menu->feeders_characters_id]['menu_obed_vitamin_b2'] = $type_string[$menu->feeders_characters_id]['menu_obed_vitamin_b2'] + round($menu_obed['vitamin_b2'],1);
                           $type_string[$menu->feeders_characters_id]['menu_obed_vitamin_a'] = $type_string[$menu->feeders_characters_id]['menu_obed_vitamin_a'] + round($menu_obed['vitamin_a'],1);
                           $type_string[$menu->feeders_characters_id]['menu_obed_ca'] = $type_string[$menu->feeders_characters_id]['menu_obed_ca'] + round($menu_obed['ca'],1);
                           $type_string[$menu->feeders_characters_id]['menu_obed_p'] = $type_string[$menu->feeders_characters_id]['menu_obed_p'] + round($menu_obed['p'],1);
                           $type_string[$menu->feeders_characters_id]['menu_obed_mg'] = $type_string[$menu->feeders_characters_id]['menu_obed_mg'] + round($menu_obed['mg'],1);
                           $type_string[$menu->feeders_characters_id]['menu_obed_fe'] = $type_string[$menu->feeders_characters_id]['menu_obed_fe'] + round($menu_obed['fe'],1);
                           $type_string[$menu->feeders_characters_id]['menu_obed_i'] = $type_string[$menu->feeders_characters_id]['menu_obed_i'] + round($menu_obed['i'],1);
                           $type_string[$menu->feeders_characters_id]['menu_obed_se'] = $type_string[$menu->feeders_characters_id]['menu_obed_se'] + round($menu_obed['se'],1);
                           $type_string[$menu->feeders_characters_id]['menu_obed_salt'] = $type_string[$menu->feeders_characters_id]['menu_obed_salt'] + round($menu_obed['salt'],1);
                           $type_string[$menu->feeders_characters_id]['menu_obed_sahar'] = $type_string[$menu->feeders_characters_id]['menu_obed_sahar'] + round($menu_obed['sahar'],1);

                           $type_string[$menu->feeders_characters_id]['menu_obed_kolbasa_count'] = $type_string[$menu->feeders_characters_id]['menu_obed_kolbasa_count'] + round($menu_obed['kolbasa'],1);
                           $type_string[$menu->feeders_characters_id]['menu_obed_konditer_count'] = $type_string[$menu->feeders_characters_id]['menu_obed_konditer_count'] + round($menu_obed['konditer'],1);
                           $type_string[$menu->feeders_characters_id]['menu_obed_frukti_count'] = $type_string[$menu->feeders_characters_id]['menu_obed_frukti_count'] + round($menu_obed['frukti'],1);
                           $type_string[$menu->feeders_characters_id]['menu_obed_yagoda_count'] = $type_string[$menu->feeders_characters_id]['menu_obed_yagoda_count'] + round($menu_obed['yagoda'],1);
                           $type_string[$menu->feeders_characters_id]['menu_obed_med_count'] = $type_string[$menu->feeders_characters_id]['menu_obed_med_count'] + round($menu_obed['med'],1);
                           $type_string[$menu->feeders_characters_id]['menu_obed_ovoshi_count'] = $type_string[$menu->feeders_characters_id]['menu_obed_ovoshi_count'] + round($menu_obed['ovoshi'],1);
                           if(round($menu_obed['kkal'],1) < $normativ[3]['kkal']){
                               $type_string[$menu->feeders_characters_id]['deficit_blud_obed'] = $type_string[$menu->feeders_characters_id]['deficit_blud_obed']+1;;
                           }?>


                           <td class="text-center align-middle table-secondary">-</td>
                            <td class="text-center align-middle <?if(round($menu_obed['yield'],1) < $normativ[3]['yield']){echo "bg-danger";}?>"><?= round($menu_obed['yield'],1); $itog_string['menu_obed_yield'] = $itog_string['menu_obed_yield'] + round($menu_obed['yield'],1);$itog_string['menu_obed_count'] = $itog_string['menu_obed_count'] + 1;?></td>
                            <td class="text-center align-middle <?if(round($menu_obed['protein'],1) < $normativ[3]['protein']){echo "bg-danger";}?>"><?= round($menu_obed['protein'],1);$itog_string['menu_obed_protein'] = $itog_string['menu_obed_protein'] + round($menu_obed['protein'],1);?></td>
                            <td class="text-center align-middle <?if(round($menu_obed['fat'],1) < $normativ[3]['fat']){echo "bg-danger";}?>"><?= round($menu_obed['fat'],1);$itog_string['menu_obed_fat'] = $itog_string['menu_obed_fat'] + round($menu_obed['fat'],1);?></td>
                            <td class="text-center align-middle <?if(round($menu_obed['carbohydrates'],3) < $normativ[3]['carbohydrates']){echo "bg-danger";}?>"><?= round($menu_obed['carbohydrates'],1);$itog_string['menu_obed_carbohydrates'] = $itog_string['menu_obed_carbohydrates'] + round($menu_obed['carbohydrates'],1);?></td>
                            <td class="text-center align-middle <?if(round($menu_obed['kkal'],1) < $normativ[3]['kkal']){echo "bg-danger";}?>"><?= round($menu_obed['kkal'],1);$itog_string['menu_obed_kkal'] = $itog_string['menu_obed_kkal'] + round($menu_obed['kkal'],1);?></td>
                            <td class="text-center align-middle <?if(round($menu_obed['vitamin_c'],1) < $normativ[3]['vitamin_c']){echo "bg-danger";}?>"><?= round($menu_obed['vitamin_c'],1);$itog_string['menu_obed_vitamin_c'] = $itog_string['menu_obed_vitamin_c'] + round($menu_obed['vitamin_c'],1);?></td>
                            <td class="text-center align-middle <?if(round($menu_obed['vitamin_b1'],1)< $normativ[3]['vitamin_b1']){echo "bg-danger";}?>"><?= round($menu_obed['vitamin_b1'],1);$itog_string['menu_obed_vitamin_b1'] = $itog_string['menu_obed_vitamin_b1'] + round($menu_obed['vitamin_b1'],1);?></td>
                            <td class="text-center align-middle <?if(round($menu_obed['vitamin_b2'],1) < $normativ[3]['vitamin_b2']){echo "bg-danger";}?>"><?= round($menu_obed['vitamin_b2'],1);$itog_string['menu_obed_vitamin_b2'] = $itog_string['menu_obed_vitamin_b2'] + round($menu_obed['vitamin_b2'],1);?></td>
                            <td class="text-center align-middle <?if(round($menu_obed['vitamin_a'],1) < $normativ[3]['vitamin_a']){echo "bg-danger";}?>"><?= round($menu_obed['vitamin_a'],1);$itog_string['menu_obed_vitamin_a'] = $itog_string['menu_obed_vitamin_a'] + round($menu_obed['vitamin_a'],1);?></td>
                            <td class="text-center align-middle <?if(round($menu_obed['ca'],1) < $normativ[3]['ca']){echo "bg-danger";}?>"><?= round($menu_obed['ca'],1);$itog_string['menu_obed_ca'] = $itog_string['menu_obed_ca'] + round($menu_obed['ca'],1);?></td>
                            <td class="text-center align-middle <?if(round($menu_obed['p'],1) < $normativ[3]['p']){echo "bg-danger";}?>"><?= round($menu_obed['p'],1);$itog_string['menu_obed_p'] = $itog_string['menu_obed_p'] + round($menu_obed['p'],1);?></td>
                            <td class="text-center align-middle <?if(round($menu_obed['mg'],1) < $normativ[3]['mg']){echo "bg-danger";}?>"><?= round($menu_obed['mg'],1);$itog_string['menu_obed_mg'] = $itog_string['menu_obed_mg'] + round($menu_obed['mg'],1);?></td>
                            <td class="text-center align-middle <?if(round($menu_obed['fe'],1) < $normativ[3]['fe']){echo "bg-danger";}?>"><?= round($menu_obed['fe'],1);$itog_string['menu_obed_fe'] = $itog_string['menu_obed_fe'] + round($menu_obed['fe'],1);?></td>
                            <td class="text-center align-middle <?if(round($menu_obed['i'],1) < $normativ[3]['i']){echo "bg-danger";}?>"><?= round($menu_obed['i'],1);$itog_string['menu_obed_i'] = $itog_string['menu_obed_i'] + round($menu_obed['i'],1);?></td>
                            <td class="text-center align-middle <?if(round($menu_obed['se'],1) < $normativ[3]['se']){echo "bg-danger";}?>"><?= round($menu_obed['se'],1);$itog_string['menu_obed_se'] = $itog_string['menu_obed_se'] + round($menu_obed['se'],1);?></td>
                            <td class="text-center align-middle <?if(round($menu_obed['salt'],1) > $normativ[3]['salt']){echo "bg-danger";}?>"><?= round($menu_obed['salt'],1);$itog_string['menu_obed_salt'] = $itog_string['menu_obed_salt'] + round($menu_obed['salt'],1);?></td>
                            <td class="text-center align-middle <?if(round($menu_obed['sahar'],1) > $normativ[3]['sahar']){echo "bg-danger";}?>"><?= round($menu_obed['sahar'],1);$itog_string['menu_obed_sahar'] = $itog_string['menu_obed_sahar'] + round($menu_obed['sahar'],1);?></td>

                            <td class="text-center align-middle <?if(round($menu_obed['kolbasa'],0)>0){echo "bg-warning";}?>"><?= round($menu_obed['kolbasa'],0);$itog_string['menu_obed_kolbasa_count'] = $itog_string['menu_obed_kolbasa_count']+round($menu_obed['kolbasa'],0);?></td>
                            <td class="text-center align-middle <?if(round($menu_obed['konditer'],0)>0){echo "bg-warning";}?>"><?=round($menu_obed['konditer'],0);$itog_string['menu_obed_konditer_count'] = $itog_string['menu_obed_konditer_count']+round($menu_obed['konditer'],0);?></td>
                            <td class="text-center align-middle"><?= round($menu_obed['frukti'] ,0);$itog_string['menu_obed_frukti_count'] = $itog_string['menu_obed_frukti_count']+round($menu_obed['frukti'] ,0);?></td>
                            <td class="text-center align-middle"><?= round($menu_obed['yagoda'] ,0);$itog_string['menu_obed_yagoda_count'] = $itog_string['menu_obed_yagoda_count']+round($menu_obed['yagoda'] ,0);?></td>
                            <td class="text-center align-middle"><?= round($menu_obed['med'] ,0);$itog_string['menu_obed_med_count'] = $itog_string['menu_obed_med_count']+round($menu_obed['med'] ,0);?></td>
                            <td class="text-center align-middle"><?= round($menu_obed['ovoshi'] ,0);$itog_string['menu_obed_ovoshi_count'] = $itog_string['menu_obed_ovoshi_count']+round($menu_obed['ovoshi'] ,0); ?></td>
                            <td class="text-center align-middle <?if(round($menu_obed['kkal'],1) < $normativ[3]['kkal']){echo "bg-danger";}?>"><?if(round($menu_obed['kkal'],1) < $normativ[3]['kkal']){echo 1; $itog_string['deficit_blud_obed'] = $itog_string['deficit_blud_obed']+1;}else{ echo 0;}?></td>
                       <?}else{?>
                           <td class="text-center align-middle table-secondary"></td>
                           <td class="text-center align-middle"></td>
                           <td class="text-center align-middle"></td>
                           <td class="text-center align-middle"></td>
                           <td class="text-center align-middle"></td>
                           <td class="text-center align-middle"></td>
                           <td class="text-center align-middle"></td>
                           <td class="text-center align-middle"></td>
                           <td class="text-center align-middle"></td>
                           <td class="text-center align-middle"></td>
                           <td class="text-center align-middle"></td>
                           <td class="text-center align-middle"></td>
                           <td class="text-center align-middle"></td>
                           <td class="text-center align-middle"></td>
                           <td class="text-center align-middle"></td>
                           <td class="text-center align-middle"></td>
                           <td class="text-center align-middle"></td>
                           <td class="text-center align-middle"></td>
                           <td class="text-center align-middle"></td>
                           <td class="text-center align-middle"></td>
                           <td class="text-center align-middle"></td>
                           <td class="text-center align-middle"></td>
                           <td class="text-center align-middle"></td>
                           <td class="text-center align-middle"></td>
                           <td class="text-center align-middle"></td>
                       <?}?>
                   </tr>
                   <?}?>
               <?}
           }?>
            <?foreach($feeders_characters as $feeders_character){?>

                <tr class="table-warning">
                    <td class="" colspan="8"><?=$feeders_character->name?>:</td>
                    <?if($type_string[$feeders_character->id]['menu_zavtrak_count'] == 0 || empty($type_string[$feeders_character->id]['menu_zavtrak_count'])){?>
                        <td class="text-center align-middle"></td>
                        <td class="text-center align-middle"></td>
                        <td class="text-center align-middle"></td>
                        <td class="text-center align-middle"></td>
                        <td class="text-center align-middle"></td>
                        <td class="text-center align-middle"></td>
                        <td class="text-center align-middle"></td>
                        <td class="text-center align-middle"></td>
                        <td class="text-center align-middle"></td>
                        <td class="text-center align-middle"></td>
                        <td class="text-center align-middle"></td>
                        <td class="text-center align-middle"></td>
                        <td class="text-center align-middle"></td>
                        <td class="text-center align-middle"></td>
                        <td class="text-center align-middle"></td>
                        <td class="text-center align-middle"></td>
                        <td class="text-center align-middle"></td>
                        <td class="text-center align-middle"></td>
                        <td class="text-center align-middle"></td>
                        <td class="text-center align-middle"></td>
                        <td class="text-center align-middle"></td>
                        <td class="text-center align-middle"></td>
                        <td class="text-center align-middle"></td>
                        <td class="text-center align-middle"></td>
                        <td class="text-center align-middle"></td>
                    <?}else{?>
                        <td class="text-center align-middle"><?=$type_string[$feeders_character->id]['menu_zavtrak_count']?></td>
                        <td class="text-center align-middle"><?=round($type_string[$feeders_character->id]['menu_zavtrak_yield']/$type_string[$feeders_character->id]['menu_zavtrak_count'],1)?></td>
                        <td class="text-center align-middle"><?=round($type_string[$feeders_character->id]['menu_zavtrak_protein']/$type_string[$feeders_character->id]['menu_zavtrak_count'],1)?></td>
                        <td class="text-center align-middle"><?=round($type_string[$feeders_character->id]['menu_zavtrak_fat']/$type_string[$feeders_character->id]['menu_zavtrak_count'],1)?></td>
                        <td class="text-center align-middle"><?=round($type_string[$feeders_character->id]['menu_zavtrak_carbohydrates']/$type_string[$feeders_character->id]['menu_zavtrak_count'],1)?></td>
                        <td class="text-center align-middle"><?=round($type_string[$feeders_character->id]['menu_zavtrak_kkal']/$type_string[$feeders_character->id]['menu_zavtrak_count'],1)?></td>
                        <td class="text-center align-middle"><?=round($type_string[$feeders_character->id]['menu_zavtrak_vitamin_c']/$type_string[$feeders_character->id]['menu_zavtrak_count'],1)?></td>
                        <td class="text-center align-middle"><?=round($type_string[$feeders_character->id]['menu_zavtrak_vitamin_b1']/$type_string[$feeders_character->id]['menu_zavtrak_count'],1)?></td>
                        <td class="text-center align-middle"><?=round($type_string[$feeders_character->id]['menu_zavtrak_vitamin_b2']/$type_string[$feeders_character->id]['menu_zavtrak_count'],1)?></td>
                        <td class="text-center align-middle"><?=round($type_string[$feeders_character->id]['menu_zavtrak_vitamin_a']/$type_string[$feeders_character->id]['menu_zavtrak_count'],1)?></td>
                        <td class="text-center align-middle"><?=round($type_string[$feeders_character->id]['menu_zavtrak_ca']/$type_string[$feeders_character->id]['menu_zavtrak_count'],1)?></td>
                        <td class="text-center align-middle"><?=round($type_string[$feeders_character->id]['menu_zavtrak_p']/$type_string[$feeders_character->id]['menu_zavtrak_count'],1)?></td>
                        <td class="text-center align-middle"><?=round($type_string[$feeders_character->id]['menu_zavtrak_mg']/$type_string[$feeders_character->id]['menu_zavtrak_count'],1)?></td>
                        <td class="text-center align-middle"><?=round($type_string[$feeders_character->id]['menu_zavtrak_fe']/$type_string[$feeders_character->id]['menu_zavtrak_count'],1)?></td>
                        <td class="text-center align-middle"><?=round($type_string[$feeders_character->id]['menu_zavtrak_i']/$type_string[$feeders_character->id]['menu_zavtrak_count'],1)?></td>
                        <td class="text-center align-middle"><?=round($type_string[$feeders_character->id]['menu_zavtrak_se']/$type_string[$feeders_character->id]['menu_zavtrak_count'],1)?></td>
                        <td class="text-center align-middle"><?=round($type_string[$feeders_character->id]['menu_zavtrak_salt']/$type_string[$feeders_character->id]['menu_zavtrak_count'],1)?></td>
                        <td class="text-center align-middle"><?=round($type_string[$feeders_character->id]['menu_zavtrak_sahar']/$type_string[$feeders_character->id]['menu_zavtrak_count'],1)?></td>
                        <td class="text-center align-middle"><?=round($type_string[$feeders_character->id]['menu_zavtrak_kolbasa_count']/$type_string[$feeders_character->id]['menu_zavtrak_count'], 1)?></td>
                        <td class="text-center align-middle"><?=round($type_string[$feeders_character->id]['menu_zavtrak_konditer_count']/$type_string[$feeders_character->id]['menu_zavtrak_count'], 1)?></td>
                        <td class="text-center align-middle"><?=round($type_string[$feeders_character->id]['menu_zavtrak_frukti_count']/$type_string[$feeders_character->id]['menu_zavtrak_count'], 1)?></td>
                        <td class="text-center align-middle"><?=round($type_string[$feeders_character->id]['menu_zavtrak_yagoda_count']/$type_string[$feeders_character->id]['menu_zavtrak_count'], 1)?></td>
                        <td class="text-center align-middle"><?=round($type_string[$feeders_character->id]['menu_zavtrak_med_count']/$type_string[$feeders_character->id]['menu_zavtrak_count'], 1)?></td>
                        <td class="text-center align-middle"><?=round($type_string[$feeders_character->id]['menu_zavtrak_ovoshi_count']/$type_string[$feeders_character->id]['menu_zavtrak_count'], 1)?></td>
                        <td class="text-center align-middle"><?=(empty($type_string[$feeders_character->id]['deficit_blud_zavtrak']))? 0 : $type_string[$feeders_character->id]['deficit_blud_zavtrak']?></td>
                    <?}?>
                    <?if($type_string[$feeders_character->id]['menu_obed_count'] == 0 || empty($type_string[$feeders_character->id]['menu_obed_count'])){?>
                        <td class="text-center align-middle"></td>
                        <td class="text-center align-middle"></td>
                        <td class="text-center align-middle"></td>
                        <td class="text-center align-middle"></td>
                        <td class="text-center align-middle"></td>
                        <td class="text-center align-middle"></td>
                        <td class="text-center align-middle"></td>
                        <td class="text-center align-middle"></td>
                        <td class="text-center align-middle"></td>
                        <td class="text-center align-middle"></td>
                        <td class="text-center align-middle"></td>
                        <td class="text-center align-middle"></td>
                        <td class="text-center align-middle"></td>
                        <td class="text-center align-middle"></td>
                        <td class="text-center align-middle"></td>
                        <td class="text-center align-middle"></td>
                        <td class="text-center align-middle"></td>
                        <td class="text-center align-middle"></td>
                        <td class="text-center align-middle"></td>
                        <td class="text-center align-middle"></td>
                        <td class="text-center align-middle"></td>
                        <td class="text-center align-middle"></td>
                        <td class="text-center align-middle"></td>
                        <td class="text-center align-middle"></td>
                        <td class="text-center align-middle"></td>
                    <?}else{?>
                        <td class="text-center align-middle"><?=$type_string[$feeders_character->id]['menu_obed_count']?></td>
                        <td class="text-center align-middle"><?=round($type_string[$feeders_character->id]['menu_obed_yield']/$type_string[$feeders_character->id]['menu_obed_count'],1)?></td>
                        <td class="text-center align-middle"><?=round($type_string[$feeders_character->id]['menu_obed_protein']/$type_string[$feeders_character->id]['menu_obed_count'],1)?></td>
                        <td class="text-center align-middle"><?=round($type_string[$feeders_character->id]['menu_obed_fat']/$type_string[$feeders_character->id]['menu_obed_count'],1)?></td>
                        <td class="text-center align-middle"><?=round($type_string[$feeders_character->id]['menu_obed_carbohydrates']/$type_string[$feeders_character->id]['menu_obed_count'],1)?></td>
                        <td class="text-center align-middle"><?=round($type_string[$feeders_character->id]['menu_obed_kkal']/$type_string[$feeders_character->id]['menu_obed_count'],1)?></td>
                        <td class="text-center align-middle"><?=round($type_string[$feeders_character->id]['menu_obed_vitamin_c']/$type_string[$feeders_character->id]['menu_obed_count'],1)?></td>
                        <td class="text-center align-middle"><?=round($type_string[$feeders_character->id]['menu_obed_vitamin_b1']/$type_string[$feeders_character->id]['menu_obed_count'],1)?></td>
                        <td class="text-center align-middle"><?=round($type_string[$feeders_character->id]['menu_obed_vitamin_b2']/$type_string[$feeders_character->id]['menu_obed_count'],1)?></td>
                        <td class="text-center align-middle"><?=round($type_string[$feeders_character->id]['menu_obed_vitamin_a']/$type_string[$feeders_character->id]['menu_obed_count'],1)?></td>
                        <td class="text-center align-middle"><?=round($type_string[$feeders_character->id]['menu_obed_ca']/$type_string[$feeders_character->id]['menu_obed_count'],1)?></td>
                        <td class="text-center align-middle"><?=round($type_string[$feeders_character->id]['menu_obed_p']/$type_string[$feeders_character->id]['menu_obed_count'],1)?></td>
                        <td class="text-center align-middle"><?=round($type_string[$feeders_character->id]['menu_obed_mg']/$type_string[$feeders_character->id]['menu_obed_count'],1)?></td>
                        <td class="text-center align-middle"><?=round($type_string[$feeders_character->id]['menu_obed_fe']/$type_string[$feeders_character->id]['menu_obed_count'],1)?></td>
                        <td class="text-center align-middle"><?=round($type_string[$feeders_character->id]['menu_obed_i']/$type_string[$feeders_character->id]['menu_obed_count'],1)?></td>
                        <td class="text-center align-middle"><?=round($type_string[$feeders_character->id]['menu_obed_se']/$type_string[$feeders_character->id]['menu_obed_count'],1)?></td>
                        <td class="text-center align-middle"><?=round($type_string[$feeders_character->id]['menu_obed_salt']/$type_string[$feeders_character->id]['menu_obed_count'],1)?></td>
                        <td class="text-center align-middle"><?=round($type_string[$feeders_character->id]['menu_obed_sahar']/$type_string[$feeders_character->id]['menu_obed_count'],1)?></td>
                        <td class="text-center align-middle"><?=round($type_string[$feeders_character->id]['menu_obed_kolbasa_count']/$type_string[$feeders_character->id]['menu_obed_count'],1)?></td>
                        <td class="text-center align-middle"><?=round($type_string[$feeders_character->id]['menu_obed_konditer_count']/$type_string[$feeders_character->id]['menu_obed_count'],1)?></td>
                        <td class="text-center align-middle"><?=round($type_string[$feeders_character->id]['menu_obed_frukti_count']/$type_string[$feeders_character->id]['menu_obed_count'],1)?></td>
                        <td class="text-center align-middle"><?=round($type_string[$feeders_character->id]['menu_obed_yagoda_count']/$type_string[$feeders_character->id]['menu_obed_count'],1)?></td>
                        <td class="text-center align-middle"><?=round($type_string[$feeders_character->id]['menu_obed_med_count']/$type_string[$feeders_character->id]['menu_obed_count'],1)?></td>
                        <td class="text-center align-middle"><?=round($type_string[$feeders_character->id]['menu_obed_ovoshi_count']/$type_string[$feeders_character->id]['menu_obed_count'],1)?></td>
                        <td class="text-center align-middle"><?=(empty($type_string[$feeders_character->id]['deficit_blud_obed']))? 0 : $type_string[$feeders_character->id]['deficit_blud_obed']?></td>

                    <?}?>
                </tr>



            <?}?>
        <tr class="table-danger">
            <td class="" colspan="6">Итого <?=$mun_item->name?>:</td>
            <td class=""><?=count($count_org);?></td>
            <td class=""><?=Organization::find()->where(['type_org' => 3, 'municipality_id' => $mun_item->id])->count();?></td>

           <?if($itog_string['menu_zavtrak_count'] == 0 || empty($itog_string['menu_zavtrak_count'])){?>
                <td class="text-center align-middle"></td>
                <td class="text-center align-middle"></td>
                <td class="text-center align-middle"></td>
                <td class="text-center align-middle"></td>
                <td class="text-center align-middle"></td>
                <td class="text-center align-middle"></td>
                <td class="text-center align-middle"></td>
                <td class="text-center align-middle"></td>
                <td class="text-center align-middle"></td>
                <td class="text-center align-middle"></td>
                <td class="text-center align-middle"></td>
                <td class="text-center align-middle"></td>
                <td class="text-center align-middle"></td>
                <td class="text-center align-middle"></td>
                <td class="text-center align-middle"></td>
                <td class="text-center align-middle"></td>
                <td class="text-center align-middle"></td>
                <td class="text-center align-middle"></td>
                <td class="text-center align-middle"></td>
                <td class="text-center align-middle"></td>
                <td class="text-center align-middle"></td>
                <td class="text-center align-middle"></td>
                <td class="text-center align-middle"></td>
                <td class="text-center align-middle"></td>
                <td class="text-center align-middle"></td>
           <?}else{?>

               <td class="text-center align-middle"><?=$itog_string['menu_zavtrak_count']?></td>
               <td class="text-center align-middle"><?=round($itog_string['menu_zavtrak_yield']/$itog_string['menu_zavtrak_count'],1)?></td>
               <td class="text-center align-middle"><?=round($itog_string['menu_zavtrak_protein']/$itog_string['menu_zavtrak_count'],1)?></td>
               <td class="text-center align-middle"><?=round($itog_string['menu_zavtrak_fat']/$itog_string['menu_zavtrak_count'],1)?></td>
               <td class="text-center align-middle"><?=round($itog_string['menu_zavtrak_carbohydrates']/$itog_string['menu_zavtrak_count'],1)?></td>
               <td class="text-center align-middle"><?=round($itog_string['menu_zavtrak_kkal']/$itog_string['menu_zavtrak_count'],1)?></td>
               <td class="text-center align-middle"><?=round($itog_string['menu_zavtrak_vitamin_c']/$itog_string['menu_zavtrak_count'],1)?></td>
               <td class="text-center align-middle"><?=round($itog_string['menu_zavtrak_vitamin_b1']/$itog_string['menu_zavtrak_count'],1)?></td>
               <td class="text-center align-middle"><?=round($itog_string['menu_zavtrak_vitamin_b2']/$itog_string['menu_zavtrak_count'],1)?></td>
               <td class="text-center align-middle"><?=round($itog_string['menu_zavtrak_vitamin_a']/$itog_string['menu_zavtrak_count'],1)?></td>
               <td class="text-center align-middle"><?=round($itog_string['menu_zavtrak_ca']/$itog_string['menu_zavtrak_count'],1)?></td>
               <td class="text-center align-middle"><?=round($itog_string['menu_zavtrak_p']/$itog_string['menu_zavtrak_count'],1)?></td>
               <td class="text-center align-middle"><?=round($itog_string['menu_zavtrak_mg']/$itog_string['menu_zavtrak_count'],1)?></td>
               <td class="text-center align-middle"><?=round($itog_string['menu_zavtrak_fe']/$itog_string['menu_zavtrak_count'],1)?></td>
               <td class="text-center align-middle"><?=round($itog_string['menu_zavtrak_i']/$itog_string['menu_zavtrak_count'],1)?></td>
               <td class="text-center align-middle"><?=round($itog_string['menu_zavtrak_se']/$itog_string['menu_zavtrak_count'],1)?></td>
               <td class="text-center align-middle"><?=round($itog_string['menu_zavtrak_salt']/$itog_string['menu_zavtrak_count'],1)?></td>
               <td class="text-center align-middle"><?=round($itog_string['menu_zavtrak_sahar']/$itog_string['menu_zavtrak_count'],1)?></td>
               <td class="text-center align-middle"><?=round($itog_string['menu_zavtrak_kolbasa_count']/$itog_string['menu_zavtrak_count'], 1)?></td>
               <td class="text-center align-middle"><?=round($itog_string['menu_zavtrak_konditer_count']/$itog_string['menu_zavtrak_count'], 1)?></td>
               <td class="text-center align-middle"><?=round($itog_string['menu_zavtrak_frukti_count']/$itog_string['menu_zavtrak_count'], 1)?></td>
               <td class="text-center align-middle"><?=round($itog_string['menu_zavtrak_yagoda_count']/$itog_string['menu_zavtrak_count'], 1)?></td>
               <td class="text-center align-middle"><?=round($itog_string['menu_zavtrak_med_count']/$itog_string['menu_zavtrak_count'], 1)?></td>
               <td class="text-center align-middle"><?=round($itog_string['menu_zavtrak_ovoshi_count']/$itog_string['menu_zavtrak_count'], 1)?></td>
               <td class="text-center align-middle"><?=(empty($itog_string['deficit_blud_zavtrak']))? 0 : $itog_string['deficit_blud_zavtrak']?></td>
           <?}?>
           <?if($itog_string['menu_obed_count'] == 0 || empty($itog_string['menu_obed_count'])){?>
               <td class="text-center align-middle"></td>
               <td class="text-center align-middle"></td>
               <td class="text-center align-middle"></td>
               <td class="text-center align-middle"></td>
               <td class="text-center align-middle"></td>
               <td class="text-center align-middle"></td>
               <td class="text-center align-middle"></td>
               <td class="text-center align-middle"></td>
               <td class="text-center align-middle"></td>
               <td class="text-center align-middle"></td>
               <td class="text-center align-middle"></td>
               <td class="text-center align-middle"></td>
               <td class="text-center align-middle"></td>
               <td class="text-center align-middle"></td>
               <td class="text-center align-middle"></td>
               <td class="text-center align-middle"></td>
               <td class="text-center align-middle"></td>
               <td class="text-center align-middle"></td>
               <td class="text-center align-middle"></td>
               <td class="text-center align-middle"></td>
               <td class="text-center align-middle"></td>
               <td class="text-center align-middle"></td>
               <td class="text-center align-middle"></td>
               <td class="text-center align-middle"></td>
               <td class="text-center align-middle"></td>
           <?}else{?>
               <td class="text-center align-middle"><?=$itog_string['menu_obed_count']?></td>
               <td class="text-center align-middle"><?=round($itog_string['menu_obed_yield']/$itog_string['menu_obed_count'],1)?></td>
               <td class="text-center align-middle"><?=round($itog_string['menu_obed_protein']/$itog_string['menu_obed_count'],1)?></td>
               <td class="text-center align-middle"><?=round($itog_string['menu_obed_fat']/$itog_string['menu_obed_count'],1)?></td>
               <td class="text-center align-middle"><?=round($itog_string['menu_obed_carbohydrates']/$itog_string['menu_obed_count'],1)?></td>
               <td class="text-center align-middle"><?=round($itog_string['menu_obed_kkal']/$itog_string['menu_obed_count'],1)?></td>
               <td class="text-center align-middle"><?=round($itog_string['menu_obed_vitamin_c']/$itog_string['menu_obed_count'],1)?></td>
               <td class="text-center align-middle"><?=round($itog_string['menu_obed_vitamin_b1']/$itog_string['menu_obed_count'],1)?></td>
               <td class="text-center align-middle"><?=round($itog_string['menu_obed_vitamin_b2']/$itog_string['menu_obed_count'],1)?></td>
               <td class="text-center align-middle"><?=round($itog_string['menu_obed_vitamin_a']/$itog_string['menu_obed_count'],1)?></td>
               <td class="text-center align-middle"><?=round($itog_string['menu_obed_ca']/$itog_string['menu_obed_count'],1)?></td>
               <td class="text-center align-middle"><?=round($itog_string['menu_obed_p']/$itog_string['menu_obed_count'],1)?></td>
               <td class="text-center align-middle"><?=round($itog_string['menu_obed_mg']/$itog_string['menu_obed_count'],1)?></td>
               <td class="text-center align-middle"><?=round($itog_string['menu_obed_fe']/$itog_string['menu_obed_count'],1)?></td>
               <td class="text-center align-middle"><?=round($itog_string['menu_obed_i']/$itog_string['menu_obed_count'],1)?></td>
               <td class="text-center align-middle"><?=round($itog_string['menu_obed_se']/$itog_string['menu_obed_count'],1)?></td>
               <td class="text-center align-middle"><?=round($itog_string['menu_obed_salt']/$itog_string['menu_obed_count'],1)?></td>
               <td class="text-center align-middle"><?=round($itog_string['menu_obed_sahar']/$itog_string['menu_obed_count'],1)?></td>
               <td class="text-center align-middle"><?=round($itog_string['menu_obed_kolbasa_count']/$itog_string['menu_obed_count'],1)?></td>
               <td class="text-center align-middle"><?=round($itog_string['menu_obed_konditer_count']/$itog_string['menu_obed_count'],1)?></td>
               <td class="text-center align-middle"><?=round($itog_string['menu_obed_frukti_count']/$itog_string['menu_obed_count'],1)?></td>
               <td class="text-center align-middle"><?=round($itog_string['menu_obed_yagoda_count']/$itog_string['menu_obed_count'],1)?></td>
               <td class="text-center align-middle"><?=round($itog_string['menu_obed_med_count']/$itog_string['menu_obed_count'],1)?></td>
               <td class="text-center align-middle"><?=round($itog_string['menu_obed_ovoshi_count']/$itog_string['menu_obed_count'],1)?></td>
               <td class="text-center align-middle"><?=(empty($itog_string['deficit_blud_obed']))? 0 : $itog_string['deficit_blud_obed']?></td>

            <?}?>
        </tr>
        <?}?>
        </tbody>
    </table>
<br><br><br><br>



<?
//print_r($data);
$script = <<< JS



$( ".beforeload" ).click(function() {
  $(".beforeload").css('display','none');
  $(".load").css('display','block');

});


JS;

$this->registerJs($script, yii\web\View::POS_READY);
?>
