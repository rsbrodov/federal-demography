<?php

use common\models\CharactersStolovaya;
use common\models\NutritionApplications;
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

$this->title = 'Характеристика детей и имеющихся меню';
$this->params['breadcrumbs'][] = $this->title;

$organization_id = Yii::$app->user->identity->organization_id;
$region_id = Organization::findOne($organization_id)->region_id;
$my_mun = Organization::findOne($organization_id)->municipality_id;
if(Yii::$app->user->can('rospotrebnadzor_nutrition') || Yii::$app->user->can('minobr')){
    $municipalities = \common\models\Municipality::find()->where(['region_id' => $region_id])->all();
    $municipality_null = array(0 => 'Все муниципальные округа ...');
    $municipality_items = ArrayHelper::map($municipalities, 'id', 'name');
    $municipality_items = ArrayHelper::merge($municipality_null, $municipality_items);
}else{
    $municipalities = \common\models\Municipality::find()->where(['id' => $my_mun])->all();
    $municipality_items = ArrayHelper::map($municipalities, 'id', 'name');
}



if(!empty($post)){
    $organizations = Organization::find()->where(['municipality_id' => $post['municipality_id'], 'type_org' => 3])->andWhere(['!=', 'id', 7])->orderBy(['short_title'=> SORT_ASC,'title'=> SORT_ASC])->all();

    if (Yii::$app->user->can('food_director'))
    {

        $ids = [];
        $nutrition_aplications = NutritionApplications::find()->where(['sender_org_id' => Yii::$app->user->identity->organization_id, 'status' => 1, 'type_org_id' => 3])->orWhere(['reciever_org_id' => Yii::$app->user->identity->organization_id, 'status' => 1, 'type_org_id' => 3])->all();
        foreach ($nutrition_aplications as $n_aplication)
        {
            if ($n_aplication->sender_org_id != Yii::$app->user->identity->organization_id)
            {
                $ids[] = $n_aplication->sender_org_id;
            }
            if ($n_aplication->reciever_org_id != Yii::$app->user->identity->organization_id)
            {
                $ids[] = $n_aplication->reciever_org_id;
            }
        }


        $organization_id = Yii::$app->user->identity->organization_id;
        $org = Organization::findOne($organization_id);
        $organizations = Organization::find()->where(['id' => $ids])->andWhere(['!=', 'id', 7])->all();

    }


    $mun = \common\models\Municipality::findOne($post['municipality_id'])->name;
}

?>
<style>
    th, td {
        border: 1px solid black!important;
        color: black;
        font-size: 15px;

    }
    th {
        background-color: #ede8b9;
        font-size: 15px;
    }
    thead, th {
        background-color: #ede8b9;
        font-size: 15px;
    }
</style>



<?php $form = ActiveForm::begin([]); ?>
<div class="container mb-30">
    <h1 class="text-center"><?= Html::encode($this->title) ?></h1>
    <div class="row">
        <?$my_municipality = \common\models\Municipality::findOne($my_mun);?>
        <? if (Yii::$app->user->can('subject_minobr') && $my_municipality->city_status == 1) { ?>
            <?$cities_null = array(0 => 'Все районы...');
            $cities = \common\models\City::find()->where(['municipality_id' => $my_municipality->id])->all();
            $cities_items = ArrayHelper::map($cities, 'id', 'name');
            $cities_items = ArrayHelper::merge($cities_null, $cities_items);?>
            <div class="col-6">
                <?= $form->field($model, 'municipality_id')->dropDownList($municipality_items, [
                    'class' => 'form-control', 'options' => [$post['municipality_id'] => ['Selected' => true]],
                ])->label('Муниципальный округ'); ?>
            </div>
            <div class="col-6">
                <?= $form->field($model, 'city_id')->dropDownList($cities_items, [
                    'class' => 'form-control', 'options' => [$post['city_id'] => ['Selected' => true]],
                ])->label('Городской район'); ?>
            </div>
        <?}else{?>
            <div class="col-12 col-md-12">
                <?= $form->field($model, 'municipality_id')->dropDownList($municipality_items, [
                    'class' => 'form-control', 'options' => [$post['municipality_id'] => ['Selected' => true]],
                ])->label('Муниципальный округ'); ?>
            </div>
        <?}?>
    </div>
</div>
<div class="row">
    <div class="form-group" style="margin: 0 auto">
        <?= Html::submitButton('Посмотреть', ['class' => 'btn main-button-3 beforeload']) ?>
        <button class="btn main-button-3 load" type="button" disabled style="display: none">
            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
            Посмотреть...
        </button>
    </div>
</div>
<p class="text-center"><b>Для крупных городов и муниципальных образований формирование отчета может занимать некоторое время</b></p>

<?php ActiveForm::end(); ?>




<?if(!empty($post)){
    if($post['municipality_id'] != 0){
        $municipalities = \common\models\Municipality::find()->where(['id' => $post['municipality_id']])->all();
    }
    else{
        $municipalities = \common\models\Municipality::find()->where(['region_id' => Organization::findOne(Yii::$app->user->identity->organization_id)->region_id])->all();
    }
    ?>
    <div class="ml-3 mt-5 mb-3">
        <p><b>СД</b> - Сахарный диабет, <b>Цел</b> - Целиакия, <b>Мук</b> - Муковисцидоз, <b>ФКУ</b> - Фенилкетонурия, <b>ПА</b> - Пищевая аллергия, <b>ОВЗ</b> - Ограниченные возможности здоровья</p>
    </div>
    <table id="table_character" class="table table-sm fixtable table2excel_with_colors" style="width: 1200px">
        <thead>
        <tr>
            <td rowspan="2">№</td>
            <td rowspan="2">Муниципальное образование</td>
            <td rowspan="2">Наименование общеобразовательной организации</td>
            <td rowspan="1" colspan="6" style="width: 300px">Количество ШКОЛЬНИКОВ 1-4 кл., имеющих заболевания, требующие индивидуального подхода в организации питания</td>
            <td rowspan="1" colspan="8">Количество внесенных меню (ЗАВТРАКОВ)</td>
            <td rowspan="1" colspan="8">Количество внесенных меню (ОБЕДОВ)</td>
            <!--            <td rowspan="1" colspan="8">Cколько меню нужно внести</td>-->
            <!--            <td rowspan="1" colspan="6" style="width: 300px">Количество ШКОЛЬНИКОВ 1-4 кл. имеющих заболевания, для которых не разработано меню</td>-->
            <td rowspan="2" colspan="1" style="width: 300px">Внесены данные по детям:</td>
            <td rowspan="2" colspan="1" style="width: 300px">Не внесены данные по детям:</td>
        </tr>
        <tr>
            <td>СД</td>
            <td>Цел</td>
            <td>Мук</td>
            <td>ФКУ</td>
            <td>овз</td>
            <td>ПА</td>

            <td>Всего</td>
            <td>Осн</td>
            <td>СД</td>
            <td>Цел</td>
            <td>Мук</td>
            <td>ФКУ</td>
            <td>овз</td>
            <td>ПА</td>


            <td>Всего</td>
            <td>Осн</td>
            <td>СД</td>
            <td>Цел</td>
            <td>Мук</td>
            <td>ФКУ</td>
            <td>овз</td>
            <td>ПА</td>



            <!--            <td>Всего</td>-->
            <!--            <td>Осн</td>-->
            <!--            <td>СД</td>-->
            <!--            <td>Цел</td>-->
            <!--            <td>Мук</td>-->
            <!--            <td>ФКУ</td>-->
            <!--            <td>овз</td>-->
            <!--            <td>ПА</td>-->


            <!--            <td>СД</td>-->
            <!--            <td>Цел</td>-->
            <!--            <td>Мук</td>-->
            <!--            <td>ФКУ</td>-->
            <!--            <td>овз</td>-->
            <!--            <td>ПА</td>-->


        </tr>

        </thead>
        <tbody>
        <?$super_itog_string =[];foreach ($municipalities as $mun){
            if(!Yii::$app->user->can('food_director')){
                $organizations = Organization::find()->where(['municipality_id' => $mun->id, 'type_org' => 3])->andWhere(['!=', 'id', 7])->orderBy(['short_title'=> SORT_ASC,'title'=> SORT_ASC])->all();
            }
            if($post['city_id'] != 0){
                $organizations = Organization::find()->where(['type_org' => 3, 'city_id' => $post['city_id']])->all();
            }
            ?>
            <? $itog_string = []; $count = 0; $sred = []; foreach ($organizations as $organization) { $count++;?>
<!--                Если мы смотри в разарезе конкретного района, то данные будут считатьться тут а если по региону то срану всех детей целиком по району-->

                    <? $cs_mas = [];
                    $students_class = \common\models\StudentsClass::find()->where(['organization_id' => $organization->id, 'class_number' => [1, 2, 3, 4]])->all(); ?>
                    <? $students_class_mas = ArrayHelper::map($students_class, 'id', 'id');
                    $vnesli_detey = \common\models\Students::find()->where(['students_class_id' => $students_class_mas])->count();?>
                <?if($post['municipality_id'] != 0) {
                    $cs_mas['sahar'] = \common\models\Students::find()->where(['students_class_id' => $students_class_mas, 'form_study' => 1, 'otkaz_pitaniya' => 1, 'dis_sahar' => 1, /*'dis_ovz' => 0, 'dis_cialic' => 0, 'dis_mukovis' => 0, 'dis_fenilketon' => 0, 'al_moloko' => 0, 'al_yico' => 0, 'al_fish' => 0, 'al_chocolad' => 0, 'al_orehi' => 0, 'al_citrus' => 0, 'al_med' => 0, 'al_pshenica' => 0, 'al_arahis' => 0*/])->count();
                    $cs_mas['ovz'] = \common\models\Students::find()->where(['students_class_id' => $students_class_mas, 'form_study' => 1, 'otkaz_pitaniya' => 1, /*'dis_sahar' => 0,*/ 'dis_ovz' => 1, /*'dis_cialic' => 0, 'dis_mukovis' => 0, 'dis_fenilketon' => 0, 'al_moloko' => 0, 'al_yico' => 0, 'al_fish' => 0, 'al_chocolad' => 0, 'al_orehi' => 0, 'al_citrus' => 0, 'al_med' => 0, 'al_pshenica' => 0, 'al_arahis' => 0*/])->count();
                    $cs_mas['cialic'] = \common\models\Students::find()->where(['students_class_id' => $students_class_mas, 'form_study' => 1, 'otkaz_pitaniya' => 1, /*'dis_sahar' => 0, 'dis_ovz' => 0, */ 'dis_cialic' => 1, /*'dis_mukovis' => 0, 'dis_fenilketon' => 0, 'al_moloko' => 0, 'al_yico' => 0, 'al_fish' => 0, 'al_chocolad' => 0, 'al_orehi' => 0, 'al_citrus' => 0, 'al_med' => 0, 'al_pshenica' => 0, 'al_arahis' => 0*/])->count();

                    $cs_mas['mukovis'] = \common\models\Students::find()->where(['students_class_id' => $students_class_mas, 'form_study' => 1, 'otkaz_pitaniya' => 1, 'dis_sahar' => 0, 'dis_ovz' => 0, 'dis_cialic' => 0, 'dis_mukovis' => 1, 'dis_fenilketon' => 0, 'al_moloko' => 0, 'al_yico' => 0, 'al_fish' => 0, 'al_chocolad' => 0, 'al_orehi' => 0, 'al_citrus' => 0, 'al_med' => 0, 'al_pshenica' => 0, 'al_arahis' => 0])->count();
                    //print_r(990);exit;
                    $cs_mas['allergy'] = \common\models\Students::find()->where(['students_class_id' => $students_class_mas, 'form_study' => 1, 'otkaz_pitaniya' => 1, 'dis_sahar' => 0, 'dis_ovz' => 0, 'dis_cialic' => 0, 'dis_mukovis' => 0, 'dis_fenilketon' => 0, 'al_moloko' => 1])
                        ->orWhere(['students_class_id' => $students_class_mas, 'form_study' => 1, 'otkaz_pitaniya' => 1, 'dis_sahar' => 0, 'dis_ovz' => 0, 'dis_cialic' => 0, 'dis_mukovis' => 0, 'dis_fenilketon' => 0, 'al_yico' => 1])
                        ->orWhere(['students_class_id' => $students_class_mas, 'form_study' => 1, 'otkaz_pitaniya' => 1, 'dis_sahar' => 0, 'dis_ovz' => 0, 'dis_cialic' => 0, 'dis_mukovis' => 0, 'dis_fenilketon' => 0, 'al_fish' => 1])
                        ->orWhere(['students_class_id' => $students_class_mas, 'form_study' => 1, 'otkaz_pitaniya' => 1, 'dis_sahar' => 0, 'dis_ovz' => 0, 'dis_cialic' => 0, 'dis_mukovis' => 0, 'dis_fenilketon' => 0, 'al_chocolad' => 1])
                        ->orWhere(['students_class_id' => $students_class_mas, 'form_study' => 1, 'otkaz_pitaniya' => 1, 'dis_sahar' => 0, 'dis_ovz' => 0, 'dis_cialic' => 0, 'dis_mukovis' => 0, 'dis_fenilketon' => 0, 'al_orehi' => 1])
                        ->orWhere(['students_class_id' => $students_class_mas, 'form_study' => 1, 'otkaz_pitaniya' => 1, 'dis_sahar' => 0, 'dis_ovz' => 0, 'dis_cialic' => 0, 'dis_mukovis' => 0, 'dis_fenilketon' => 0, 'al_citrus' => 1])
                        ->orWhere(['students_class_id' => $students_class_mas, 'form_study' => 1, 'otkaz_pitaniya' => 1, 'dis_sahar' => 0, 'dis_ovz' => 0, 'dis_cialic' => 0, 'dis_mukovis' => 0, 'dis_fenilketon' => 0, 'al_med' => 1])
                        ->orWhere(['students_class_id' => $students_class_mas, 'form_study' => 1, 'otkaz_pitaniya' => 1, 'dis_sahar' => 0, 'dis_ovz' => 0, 'dis_cialic' => 0, 'dis_mukovis' => 0, 'dis_fenilketon' => 0, 'al_pshenica' => 1])
                        ->orWhere(['students_class_id' => $students_class_mas, 'form_study' => 1, 'otkaz_pitaniya' => 1, 'dis_sahar' => 0, 'dis_ovz' => 0, 'dis_cialic' => 0, 'dis_mukovis' => 0, 'dis_fenilketon' => 0, 'al_arahis' => 1])
                        ->count();
                    $cs_mas['fenilketon'] = \common\models\Students::find()->where(['students_class_id' => $students_class_mas, 'form_study' => 1, 'otkaz_pitaniya' => 1, 'dis_sahar' => 0, 'dis_ovz' => 0, 'dis_cialic' => 0, 'dis_mukovis' => 0, 'dis_fenilketon' => 1, 'al_moloko' => 0, 'al_yico' => 0, 'al_fish' => 0, 'al_chocolad' => 0, 'al_orehi' => 0, 'al_citrus' => 0, 'al_med' => 0, 'al_pshenica' => 0, 'al_arahis' => 0])->count();

                }?><?
                $menu_mas = [];
                $menus = \common\models\Menus::find()->where(['organization_id' => $organization->id, 'age_info_id' => 6, 'status_archive' => 0])->andWhere(['>=', 'date_end', strtotime("now")])->all();
                foreach($menus as $menu){
                    if(MenusDishes::find()->where(['menu_id' => $menu->id, 'nutrition_id' => 1])->count() > 0){
                        $menu_mas['zavtrak'][$menu->feeders_characters_id] = $menu_mas['zavtrak'][$menu->feeders_characters_id] + 1;
                        $menu_mas['zavtrak']['itog'] = $menu_mas['zavtrak']['itog'] + 1;
                    }
                    if(MenusDishes::find()->where(['menu_id' => $menu->id, 'nutrition_id' => 3])->count() > 0){
                        $menu_mas['obed'][$menu->feeders_characters_id] = $menu_mas['obed'][$menu->feeders_characters_id] + 1;
                        $menu_mas['obed']['itog'] = $menu_mas['obed']['itog'] + 1;
                    }
                }
                $menu = \common\models\Menus::find()->where(['organization_id' => $organization->id, 'feeders_characters_id' => 3, 'age_info_id' => 6, 'status_archive' => 0])->andWhere(['>=', 'date_end', strtotime("now")])->count();
                ?>

                <?if($post['municipality_id'] != 0) { ?>
                <? $itog_string['student_sahar'] = $itog_string['student_sahar'] + $cs_mas['sahar']; ?>
                <? $itog_string['student_cialic'] = $itog_string['student_cialic'] + $cs_mas['cialic'];?>
                <? $itog_string['student_mukovis'] = $itog_string['student_mukovis'] + $cs_mas['mukovis']; ?>
                <? $itog_string['student_fenilketon'] = $itog_string['student_fenilketon'] + $cs_mas['fenilketon'];?>
                <? $itog_string['student_ovz'] = $itog_string['student_ovz'] + $cs_mas['ovz']; ?>
                <? $itog_string['student_allergy'] = $itog_string['student_allergy'] + $cs_mas['allergy']; ?>
                <?}?>

                <? $itog_string['vnes_zavtrak_itog_count'] = $itog_string['vnes_zavtrak_itog_count'] + $menu_mas['zavtrak']['itog'];?>
                <? $itog_string['vnes_zavtrak_3_count'] = $itog_string['vnes_zavtrak_3_count'] + $menu_mas['zavtrak'][3];?>
                <?$itog_string['vnes_zavtrak_5_count'] = $itog_string['vnes_zavtrak_5_count'] + $menu_mas['zavtrak'][5];?>
                <?$itog_string['vnes_zavtrak_6_count'] = $itog_string['vnes_zavtrak_6_count'] + $menu_mas['zavtrak'][6];?>
                <?$itog_string['vnes_zavtrak_8_count'] = $itog_string['vnes_zavtrak_8_count'] + $menu_mas['zavtrak'][8];?>
                <?$itog_string['vnes_zavtrak_7_count'] = $itog_string['vnes_zavtrak_7_count'] + $menu_mas['zavtrak'][7];?>
                <?$itog_string['vnes_zavtrak_4_count'] = $itog_string['vnes_zavtrak_4_count'] + $menu_mas['zavtrak'][4];?>
                <?$itog_string['vnes_zavtrak_10_count'] = $itog_string['vnes_zavtrak_10_count'] + $menu_mas['zavtrak'][10];?>

                <?$itog_string['vnes_obed_itog_count'] = $itog_string['vnes_obed_itog_count'] + $menu_mas['obed']['itog'];?>
                <?$itog_string['vnes_obed_3_count'] = $itog_string['vnes_obed_3_count'] + $menu_mas['obed'][3];?>
                <?$itog_string['vnes_obed_5_count'] = $itog_string['vnes_obed_5_count'] + $menu_mas['obed'][5];?>
                <?$itog_string['vnes_obed_6_count'] = $itog_string['vnes_obed_6_count'] + $menu_mas['obed'][6];?>
                <?$itog_string['vnes_obed_8_count'] = $itog_string['vnes_obed_8_count'] + $menu_mas['obed'][8];?>
                <?$itog_string['vnes_obed_7_count'] = $itog_string['vnes_obed_7_count'] + $menu_mas['obed'][7];?>
                <?$itog_string['vnes_obed_4_count'] = $itog_string['vnes_obed_4_count'] + $menu_mas['obed'][4];?>
                <?$itog_string['vnes_obed_10_count'] = $itog_string['vnes_obed_10_count'] + $menu_mas['obed'][10];?>

                <?if($vnesli_detey > 0){ $itog_string['vnesli_detey']++;}?>
                <?if($vnesli_detey == 0){ $itog_string['nevnesli_detey']++;}?>



                <?if($post['municipality_id'] != 0){?>
                    <tr>
                        <td class="text-center align-middle"><?=$count?></td>
                        <td class="align-middle" style="width: 200px"><?=$mun->name?></td>
                        <td class="align-middle" style="width: 200px"><?echo (empty($organization->short_title)) ? $organization->title : $organization->short_title;?></td>
                        <td class="text-center align-middle <?if(empty($cs_mas['sahar'])){echo 'bg-secondary';}?>"><? if (empty($cs_mas['sahar'])){echo '-';}else{echo $cs_mas['sahar'];}?></td>
                        <td class="text-center align-middle <?if(empty($cs_mas['cialic'])){echo 'bg-secondary';}?>"><? if (empty($cs_mas['cialic'])){echo '-';}else{echo $cs_mas['cialic'];}?></td>
                        <td class="text-center align-middle <?if(empty($cs_mas['mukovis'])){echo 'bg-secondary';}?>"><? if (empty($cs_mas['mukovis'])){echo '-';}else{echo $cs_mas['mukovis'];}?></td>
                        <td class="text-center align-middle <?if(empty($cs_mas['fenilketon'])){echo 'bg-secondary';}?>"><? if (empty($cs_mas['fenilketon'])){echo '-';}else{echo $cs_mas['fenilketon'];}?></td>
                        <td class="text-center align-middle <?if(empty($cs_mas['ovz'])){echo 'bg-secondary';}?>"><? if (empty($cs_mas['ovz'])){echo '-';}else{echo $cs_mas['ovz'];}?></td>
                        <td class="text-center align-middle <?if(empty($cs_mas['allergy'])){echo 'bg-secondary';}?>"><? if (empty($cs_mas['allergy'])){echo '-';}else{echo $cs_mas['allergy'];}?></td>

                        <td class="text-center align-middle <?if (empty($menu_mas['zavtrak']['itog']) && empty($menu_mas['obed']['itog']) ){ echo 'bg-danger'; }?>"><?if (empty($menu_mas['zavtrak']['itog'])){ echo 0; }else{ echo $menu_mas['zavtrak']['itog'];}?></td>
                        <td class="text-center align-middle <?if (empty($menu_mas['zavtrak'][3]) && empty($menu_mas['obed'][3]) && \common\models\Students::find()->where(['students_class_id' => $students_class_mas, 'form_study' => 1, 'otkaz_pitaniya' => 1, 'dis_sahar' => 0, 'dis_ovz' => 0, 'dis_cialic' => 0, 'dis_mukovis' => 0, 'dis_fenilketon' => 0, 'al_moloko' => 0, 'al_yico' => 0, 'al_fish' => 0, 'al_chocolad' => 0, 'al_orehi' => 0, 'al_citrus' => 0, 'al_med' => 0, 'al_pshenica' => 0, 'al_arahis' => 0])->count() > 0){ echo 'bg-danger'; }?> "><?if (empty($menu_mas['zavtrak'][3])){ echo 0; }else{ echo $menu_mas['zavtrak'][3];}?></td>
                        <td class="text-center align-middle <?if($cs_mas['sahar'] > 0 && empty($menu_mas['zavtrak'][5]) && empty($menu_mas['obed'][5])){echo "bg-danger";}?>"><?if (empty($menu_mas['zavtrak'][5])){ echo 0; }else{ echo $menu_mas['zavtrak'][5];}?></td>
                        <td class="text-center align-middle <?if($cs_mas['cialic'] > 0 && empty($menu_mas['zavtrak'][6]) && empty($menu_mas['obed'][6])){echo "bg-danger";}?>"><?if (empty($menu_mas['zavtrak'][6])){ echo 0; }else{ echo $menu_mas['zavtrak'][6];}?></td>
                        <td class="text-center align-middle <?if($cs_mas['mukovis'] > 0 && empty($menu_mas['zavtrak'][8]) && empty($menu_mas['obed'][8])){echo "bg-danger";}?>"><?if (empty($menu_mas['zavtrak'][8])){ echo 0; }else{ echo $menu_mas['zavtrak'][8];}?></td>
                        <td class="text-center align-middle <?if($cs_mas['fenilketon'] > 0 && empty($menu_mas['zavtrak'][7]) && empty($menu_mas['obed'][7])){echo "bg-danger";}?>"><?if (empty($menu_mas['zavtrak'][7])){ echo 0; }else{ echo $menu_mas['zavtrak'][7];}?></td>
                        <td class="text-center align-middle <?if($cs_mas['ovz'] > 0 && empty($menu_mas['zavtrak'][4]) && empty($menu_mas['obed'][4])){echo "bg-danger";}?>"><?if (empty($menu_mas['zavtrak'][4])){ echo 0; }else{ echo $menu_mas['zavtrak'][4];}?></td>
                        <td class="text-center align-middle <?if($cs_mas['allergy'] > 0 && empty($menu_mas['zavtrak'][10]) && empty($menu_mas['obed'][10])){echo "bg-danger";}?>"><?if (empty($menu_mas['zavtrak'][10])){ echo 0; }else{ echo $menu_mas['zavtrak'][10];}?></td>

                        <td class="text-center align-middle <?if (empty($menu_mas['zavtrak']['itog']) && empty($menu_mas['obed']['itog'])){ echo 'bg-danger'; }?>"><?if (empty($menu_mas['obed']['itog'])){ echo 0; }else{ echo $menu_mas['obed']['itog'];}?></td>
                        <td class="text-center align-middle <?if (empty($menu_mas['zavtrak'][3]) && empty($menu_mas['obed'][3]) && \common\models\Students::find()->where(['students_class_id' => $students_class_mas, 'form_study' => 1, 'otkaz_pitaniya' => 1, 'dis_sahar' => 0, 'dis_ovz' => 0, 'dis_cialic' => 0, 'dis_mukovis' => 0, 'dis_fenilketon' => 0, 'al_moloko' => 0, 'al_yico' => 0, 'al_fish' => 0, 'al_chocolad' => 0, 'al_orehi' => 0, 'al_citrus' => 0, 'al_med' => 0, 'al_pshenica' => 0, 'al_arahis' => 0])->count() > 0){ echo 'bg-danger'; }?> "><?if (empty($menu_mas['obed'][3])){ echo 0; }else{ echo $menu_mas['obed'][3];}?></td>
                        <td class="text-center align-middle <?if($cs_mas['sahar'] > 0 && empty($menu_mas['zavtrak'][5]) && empty($menu_mas['obed'][5])){echo "bg-danger";}?>"><?if (empty($menu_mas['obed'][5])){ echo 0; }else{ echo $menu_mas['obed'][5];}?></td>
                        <td class="text-center align-middle <?if($cs_mas['cialic'] > 0 && empty($menu_mas['zavtrak'][6]) && empty($menu_mas['obed'][6])){echo "bg-danger";}?>"><?if (empty($menu_mas['obed'][6])){ echo 0; }else{ echo $menu_mas['obed'][6];}?></td>
                        <td class="text-center align-middle <?if($cs_mas['mukovis'] > 0 && empty($menu_mas['zavtrak'][8]) && empty($menu_mas['obed'][8])){echo "bg-danger";}?>"><?if (empty($menu_mas['obed'][8])){ echo 0; }else{ echo $menu_mas['obed'][8];}?></td>
                        <td class="text-center align-middle <?if($cs_mas['fenilketon'] > 0 && empty($menu_mas['zavtrak'][7]) && empty($menu_mas['obed'][7])){echo "bg-danger";}?>"><?if (empty($menu_mas['obed'][7])){ echo 0; }else{ echo $menu_mas['obed'][7];}?></td>
                        <td class="text-center align-middle <?if($cs_mas['ovz'] > 0 && empty($menu_mas['zavtrak'][4]) && empty($menu_mas['obed'][4])){echo "bg-danger";}?>"><?if (empty($menu_mas['obed'][4])){ echo 0; }else{ echo $menu_mas['obed'][4];}?></td>
                        <td class="text-center align-middle <?if($cs_mas['allergy'] > 0 && empty($menu_mas['zavtrak'][10]) && empty($menu_mas['obed'][10])){echo "bg-danger";}?>"><?if (empty($menu_mas['obed'][10])){ echo 0; }else{ echo $menu_mas['obed'][10];}?></td>



                        <!--                    <td class="text-center align-middle --><?//if(!(empty($menu_mas['zavtrak']['itog']) && empty($menu_mas['obed']['itog']))){echo "bg-secondary";}?><!--">--><?//if(empty($menu_mas['zavtrak']['itog']) && empty($menu_mas['obed']['itog'])){ echo 1;$itog_string['itog_menu_nado'] = $itog_string['itog_menu_nado'] + 1;}else{echo 0;}?><!--</td>-->
                        <!--                    <td class="text-center align-middle --><?//if(!(empty($menu_mas['zavtrak'][3]) && empty($menu_mas['obed'][3]))){echo "bg-secondary";}?><!--">--><?//if(empty($menu_mas['zavtrak'][3]) && empty($menu_mas['obed'][3])){ echo 1;$itog_string['osn_menu_nado'] = $itog_string['osn_menu_nado'] + 1;}else{echo 0;}?><!--</td>-->
                        <!--                    <td class="text-center align-middle --><?//if(!($cs_mas['sahar'] > 0 && empty($menu_mas['zavtrak'][5]) && empty($menu_mas['obed'][5]))){echo "bg-secondary";}?><!--">--><?//if($cs_mas['sahar'] > 0 && empty($menu_mas['zavtrak'][5]) && empty($menu_mas['obed'][5])){ echo 1;$itog_string['sahar_menu_nado'] = $itog_string['sahar_menu_nado'] + 1;}else{echo 0;}?><!--</td>-->
                        <!--                    <td class="text-center align-middle --><?//if(!($cs_mas['cialic'] > 0 && empty($menu_mas['zavtrak'][6]) && empty($menu_mas['obed'][6]))){echo "bg-secondary";}?><!--">--><?//if($cs_mas['cialic'] > 0 && empty($menu_mas['zavtrak'][6]) && empty($menu_mas['obed'][6])){ echo 1;$itog_string['cialic_menu_nado'] = $itog_string['cialic_menu_nado'] + 1;}else{echo 0;}?><!--</td>-->
                        <!--                    <td class="text-center align-middle --><?//if(!($cs_mas['mukovis'] > 0 && empty($menu_mas['zavtrak'][8]) && empty($menu_mas['obed'][8]))){echo "bg-secondary";}?><!--">--><?//if($cs_mas['mukovis'] > 0 && empty($menu_mas['zavtrak'][8]) && empty($menu_mas['obed'][8])){ echo 1;$itog_string['mukovis_menu_nado'] = $itog_string['mukovis_menu_nado'] + 1;}else{echo 0;}?><!--</td>-->
                        <!--                    <td class="text-center align-middle --><?//if(!($cs_mas['fenilketon'] > 0 && empty($menu_mas['zavtrak'][7]) && empty($menu_mas['obed'][7]))){echo "bg-secondary";}?><!--">--><?//if($cs_mas['fenilketon'] > 0 && empty($menu_mas['zavtrak'][7]) && empty($menu_mas['obed'][7])){ echo 1;$itog_string['fenilketon_menu_nado'] = $itog_string['fenilketon_menu_nado'] + 1;}else{echo 0;}?><!--</td>-->
                        <!--                    <td class="text-center align-middle --><?//if(!($cs_mas['ovz'] > 0 && empty($menu_mas['zavtrak'][4]) && empty($menu_mas['obed'][4]))){echo "bg-secondary";}?><!--">--><?//if($cs_mas['ovz'] > 0 && empty($menu_mas['zavtrak'][4]) && empty($menu_mas['obed'][4])){ echo 1;$itog_string['ovz_menu_nado'] = $itog_string['ovz_menu_nado'] + 1;}else{echo 0;}?><!--</td>-->
                        <!--                    <td class="text-center align-middle --><?//if(!($cs_mas['allergy'] > 0 && empty($menu_mas['zavtrak'][10]) && empty($menu_mas['obed'][10]))){echo "bg-secondary";}?><!--">--><?//if($cs_mas['allergy'] > 0 && empty($menu_mas['zavtrak'][10]) && empty($menu_mas['obed'][10])){ echo 1;$itog_string['allergy_menu_nado'] = $itog_string['allergy_menu_nado'] + 1;}else{echo 0;}?><!--</td>-->


                        <!--                    <td class="text-center align-middle --><?//if(!($cs_mas['sahar'] > 0 && empty($menu_mas['zavtrak'][5]) && empty($menu_mas['obed'][5]))){echo "bg-secondary";}?><!--">--><?//if($cs_mas['sahar'] > 0 && empty($menu_mas['zavtrak'][5]) && empty($menu_mas['obed'][5])){ echo $cs_mas['sahar'];$itog_string['sahar_nepit'] = $itog_string['sahar_nepit'] + $cs_mas['sahar'];}else{echo 0;}?><!--</td>-->
                        <!--                    <td class="text-center align-middle --><?//if(!($cs_mas['cialic'] > 0 && empty($menu_mas['zavtrak'][6]) && empty($menu_mas['obed'][6]))){echo "bg-secondary";}?><!--">--><?//if($cs_mas['cialic'] > 0 && empty($menu_mas['zavtrak'][6]) && empty($menu_mas['obed'][6])){ echo $cs_mas['cialic'];$itog_string['cialic_nepit'] = $itog_string['cialic_nepit'] + $cs_mas['cialic'];}else{echo 0;}?><!--</td>-->
                        <!--                    <td class="text-center align-middle --><?//if(!($cs_mas['mukovis'] > 0 && empty($menu_mas['zavtrak'][8]) && empty($menu_mas['obed'][8]))){echo "bg-secondary";}?><!--">--><?//if($cs_mas['mukovis'] > 0 && empty($menu_mas['zavtrak'][8]) && empty($menu_mas['obed'][8])){ echo $cs_mas['mukovis'];$itog_string['mukovis_nepit'] = $itog_string['mukovis_nepit'] + $cs_mas['mukovis'];}else{echo 0;}?><!--</td>-->
                        <!--                    <td class="text-center align-middle --><?//if(!($cs_mas['fenilketon'] > 0 && empty($menu_mas['zavtrak'][7]) && empty($menu_mas['obed'][7]))){echo "bg-secondary";}?><!--">--><?//if($cs_mas['fenilketon'] > 0 && empty($menu_mas['zavtrak'][7]) && empty($menu_mas['obed'][7])){ echo $cs_mas['fenilketon'];$itog_string['fenilketon_nepit'] = $itog_string['fenilketon_nepit'] + $cs_mas['fenilketon'];}else{echo 0;}?><!--</td>-->
                        <!--                    <td class="text-center align-middle --><?//if(!($cs_mas['ovz'] > 0 && empty($menu_mas['zavtrak'][4]) && empty($menu_mas['obed'][4]))){echo "bg-secondary";}?><!--">--><?//if($cs_mas['ovz'] > 0 && empty($menu_mas['zavtrak'][4]) && empty($menu_mas['obed'][4])){ echo $cs_mas['ovz'];$itog_string['ovz_nepit'] = $itog_string['ovz_nepit'] + $cs_mas['ovz'];}else{echo 0;}?><!--</td>-->
                        <!--                    <td class="text-center align-middle --><?//if(!($cs_mas['allergy'] > 0 && empty($menu_mas['zavtrak'][10]) && empty($menu_mas['obed'][10]))){echo "bg-secondary";}?><!--">--><?//if($cs_mas['allergy'] > 0 && empty($menu_mas['zavtrak'][10]) && empty($menu_mas['obed'][10])){ echo $cs_mas['allergy'];$itog_string['allergy_nepit'] = $itog_string['allergy_nepit'] + $cs_mas['allergy'];}else{echo 0;}?><!--</td>-->




                        <td class="text-center align-middle <?if($vnesli_detey > 0){echo "bg-success";}?>"><?if($vnesli_detey > 0){ echo 1; }else{echo 0;}?></td>
                        <td class="text-center align-middle <?if($vnesli_detey == 0){echo "bg-danger";}?>"><?if($vnesli_detey == 0){ echo 1;}else{echo 0;}?></td>
                    </tr>
                <?}?>

            <?}?>



            <?if($post['municipality_id'] == 0) { ?>
                <? $cs_mas = [];$organization_mas = ArrayHelper::map( Organization::find()->where(['municipality_id' => $mun->id, 'type_org' => 3])->andWhere(['!=', 'id', 7])->orderBy(['short_title'=> SORT_ASC,'title'=> SORT_ASC])->all(), 'id', 'id');
                $students_class = \common\models\StudentsClass::find()->where(['organization_id' => $organization_mas, 'class_number' => [1, 2, 3, 4]])->all(); ?>
                <? $students_class_mas = ArrayHelper::map($students_class, 'id', 'id');
                $vnesli_detey = \common\models\Students::find()->where(['students_class_id' => $students_class_mas])->count();
                $cs_mas['sahar'] = \common\models\Students::find()->where(['students_class_id' => $students_class_mas, 'form_study' => 1, 'otkaz_pitaniya' => 1, 'dis_sahar' => 1, /*'dis_ovz' => 0, 'dis_cialic' => 0, 'dis_mukovis' => 0, 'dis_fenilketon' => 0, 'al_moloko' => 0, 'al_yico' => 0, 'al_fish' => 0, 'al_chocolad' => 0, 'al_orehi' => 0, 'al_citrus' => 0, 'al_med' => 0, 'al_pshenica' => 0, 'al_arahis' => 0*/])->count();
                $cs_mas['ovz'] = \common\models\Students::find()->where(['students_class_id' => $students_class_mas, 'form_study' => 1, 'otkaz_pitaniya' => 1, /*'dis_sahar' => 0,*/ 'dis_ovz' => 1, /*'dis_cialic' => 0, 'dis_mukovis' => 0, 'dis_fenilketon' => 0, 'al_moloko' => 0, 'al_yico' => 0, 'al_fish' => 0, 'al_chocolad' => 0, 'al_orehi' => 0, 'al_citrus' => 0, 'al_med' => 0, 'al_pshenica' => 0, 'al_arahis' => 0*/])->count();
                $cs_mas['cialic'] = \common\models\Students::find()->where(['students_class_id' => $students_class_mas, 'form_study' => 1, 'otkaz_pitaniya' => 1, /*'dis_sahar' => 0, 'dis_ovz' => 0, */ 'dis_cialic' => 1, /*'dis_mukovis' => 0, 'dis_fenilketon' => 0, 'al_moloko' => 0, 'al_yico' => 0, 'al_fish' => 0, 'al_chocolad' => 0, 'al_orehi' => 0, 'al_citrus' => 0, 'al_med' => 0, 'al_pshenica' => 0, 'al_arahis' => 0*/])->count();

                $cs_mas['mukovis'] = \common\models\Students::find()->where(['students_class_id' => $students_class_mas, 'form_study' => 1, 'otkaz_pitaniya' => 1, 'dis_sahar' => 0, 'dis_ovz' => 0, 'dis_cialic' => 0, 'dis_mukovis' => 1, 'dis_fenilketon' => 0, 'al_moloko' => 0, 'al_yico' => 0, 'al_fish' => 0, 'al_chocolad' => 0, 'al_orehi' => 0, 'al_citrus' => 0, 'al_med' => 0, 'al_pshenica' => 0, 'al_arahis' => 0])->count();
                //print_r(990);exit;
                $cs_mas['allergy'] = \common\models\Students::find()->where(['students_class_id' => $students_class_mas, 'form_study' => 1, 'otkaz_pitaniya' => 1, 'dis_sahar' => 0, 'dis_ovz' => 0, 'dis_cialic' => 0, 'dis_mukovis' => 0, 'dis_fenilketon' => 0, 'al_moloko' => 1])
                    ->orWhere(['students_class_id' => $students_class_mas, 'form_study' => 1, 'otkaz_pitaniya' => 1, 'dis_sahar' => 0, 'dis_ovz' => 0, 'dis_cialic' => 0, 'dis_mukovis' => 0, 'dis_fenilketon' => 0, 'al_yico' => 1])
                    ->orWhere(['students_class_id' => $students_class_mas, 'form_study' => 1, 'otkaz_pitaniya' => 1, 'dis_sahar' => 0, 'dis_ovz' => 0, 'dis_cialic' => 0, 'dis_mukovis' => 0, 'dis_fenilketon' => 0, 'al_fish' => 1])
                    ->orWhere(['students_class_id' => $students_class_mas, 'form_study' => 1, 'otkaz_pitaniya' => 1, 'dis_sahar' => 0, 'dis_ovz' => 0, 'dis_cialic' => 0, 'dis_mukovis' => 0, 'dis_fenilketon' => 0, 'al_chocolad' => 1])
                    ->orWhere(['students_class_id' => $students_class_mas, 'form_study' => 1, 'otkaz_pitaniya' => 1, 'dis_sahar' => 0, 'dis_ovz' => 0, 'dis_cialic' => 0, 'dis_mukovis' => 0, 'dis_fenilketon' => 0, 'al_orehi' => 1])
                    ->orWhere(['students_class_id' => $students_class_mas, 'form_study' => 1, 'otkaz_pitaniya' => 1, 'dis_sahar' => 0, 'dis_ovz' => 0, 'dis_cialic' => 0, 'dis_mukovis' => 0, 'dis_fenilketon' => 0, 'al_citrus' => 1])
                    ->orWhere(['students_class_id' => $students_class_mas, 'form_study' => 1, 'otkaz_pitaniya' => 1, 'dis_sahar' => 0, 'dis_ovz' => 0, 'dis_cialic' => 0, 'dis_mukovis' => 0, 'dis_fenilketon' => 0, 'al_med' => 1])
                    ->orWhere(['students_class_id' => $students_class_mas, 'form_study' => 1, 'otkaz_pitaniya' => 1, 'dis_sahar' => 0, 'dis_ovz' => 0, 'dis_cialic' => 0, 'dis_mukovis' => 0, 'dis_fenilketon' => 0, 'al_pshenica' => 1])
                    ->orWhere(['students_class_id' => $students_class_mas, 'form_study' => 1, 'otkaz_pitaniya' => 1, 'dis_sahar' => 0, 'dis_ovz' => 0, 'dis_cialic' => 0, 'dis_mukovis' => 0, 'dis_fenilketon' => 0, 'al_arahis' => 1])
                    ->count();
                $cs_mas['fenilketon'] = \common\models\Students::find()->where(['students_class_id' => $students_class_mas, 'form_study' => 1, 'otkaz_pitaniya' => 1, 'dis_sahar' => 0, 'dis_ovz' => 0, 'dis_cialic' => 0, 'dis_mukovis' => 0, 'dis_fenilketon' => 1, 'al_moloko' => 0, 'al_yico' => 0, 'al_fish' => 0, 'al_chocolad' => 0, 'al_orehi' => 0, 'al_citrus' => 0, 'al_med' => 0, 'al_pshenica' => 0, 'al_arahis' => 0])->count();
              ?>
                
                    <? $itog_string['student_sahar'] = $itog_string['student_sahar'] + $cs_mas['sahar']; ?>
                    <? $itog_string['student_cialic'] = $itog_string['student_cialic'] + $cs_mas['cialic'];?>
                    <? $itog_string['student_mukovis'] = $itog_string['student_mukovis'] + $cs_mas['mukovis']; ?>
                    <? $itog_string['student_fenilketon'] = $itog_string['student_fenilketon'] + $cs_mas['fenilketon'];?>
                    <? $itog_string['student_ovz'] = $itog_string['student_ovz'] + $cs_mas['ovz']; ?>
                    <? $itog_string['student_allergy'] = $itog_string['student_allergy'] + $cs_mas['allergy']; ?>
                    <? $itog_string['vnes_zavtrak_itog_count'] = $itog_string['vnes_zavtrak_itog_count'] + $menu_mas['zavtrak']['itog'];?>
                    <? $itog_string['vnes_zavtrak_3_count'] = $itog_string['vnes_zavtrak_3_count'] + $menu_mas['zavtrak'][3];?>

                
            <?}?>


            <tr class="<?if($post['municipality_id'] != 0){echo 'table-danger';}?>">
                <td class="" colspan="3"><?if($post['municipality_id'] != 0){?>Итого<?}?> <?=$mun->name;?><?if($post['city_id'] != 0){echo ' ('.\common\models\City::findOne($post['city_id'])->name.')';}?>:</td>
                <td class="text-center align-middle"><?echo(empty($itog_string['student_sahar']))? 0:$itog_string['student_sahar'] ;?></td>
                <td class="text-center align-middle"><?echo(empty($itog_string['student_cialic']))? 0:$itog_string['student_cialic'] ;?></td>
                <td class="text-center align-middle"><?echo(empty($itog_string['student_mukovis']))? 0:$itog_string['student_mukovis'] ;?></td>
                <td class="text-center align-middle"><?echo(empty($itog_string['student_fenilketon']))? 0:$itog_string['student_fenilketon'] ;?></td>
                <td class="text-center align-middle"><?echo(empty($itog_string['student_ovz']))? 0:$itog_string['student_ovz'] ;?></td>
                <td class="text-center align-middle"><?echo(empty($itog_string['student_allergy']))? 0:$itog_string['student_allergy'] ;?></td>

                <td class="text-center align-middle"><?echo(empty($itog_string['vnes_zavtrak_itog_count']))? 0:$itog_string['vnes_zavtrak_itog_count']?></td>
                <td class="text-center align-middle"><?echo(empty($itog_string['vnes_zavtrak_3_count']))? 0:$itog_string['vnes_zavtrak_3_count']?></td>
                <td class="text-center align-middle"><?echo(empty($itog_string['vnes_zavtrak_5_count']))? 0:$itog_string['vnes_zavtrak_5_count']?></td>
                <td class="text-center align-middle"><?echo(empty($itog_string['vnes_zavtrak_6_count']))? 0:$itog_string['vnes_zavtrak_6_count']?></td>
                <td class="text-center align-middle"><?echo(empty($itog_string['vnes_zavtrak_8_count']))? 0:$itog_string['vnes_zavtrak_8_count']?></td>
                <td class="text-center align-middle"><?echo(empty($itog_string['vnes_zavtrak_7_count']))? 0:$itog_string['vnes_zavtrak_7_count']?></td>
                <td class="text-center align-middle"><?echo(empty($itog_string['vnes_zavtrak_4_count']))? 0:$itog_string['vnes_zavtrak_4_count']?></td>
                <td class="text-center align-middle"><?echo(empty($itog_string['vnes_zavtrak_10_count']))? 0:$itog_string['vnes_zavtrak_10_count']?></td>

                <td class="text-center align-middle"><?echo(empty($itog_string['vnes_obed_itog_count']))? 0:$itog_string['vnes_obed_itog_count']?></td>
                <td class="text-center align-middle"><?echo(empty($itog_string['vnes_obed_3_count']))? 0:$itog_string['vnes_obed_3_count']?></td>
                <td class="text-center align-middle"><?echo(empty($itog_string['vnes_obed_5_count']))? 0:$itog_string['vnes_obed_5_count']?></td>
                <td class="text-center align-middle"><?echo(empty($itog_string['vnes_obed_6_count']))? 0:$itog_string['vnes_obed_6_count']?></td>
                <td class="text-center align-middle"><?echo(empty($itog_string['vnes_obed_8_count']))? 0:$itog_string['vnes_obed_8_count']?></td>
                <td class="text-center align-middle"><?echo(empty($itog_string['vnes_obed_7_count']))? 0:$itog_string['vnes_obed_7_count']?></td>
                <td class="text-center align-middle"><?echo(empty($itog_string['vnes_obed_4_count']))? 0:$itog_string['vnes_obed_4_count']?></td>
                <td class="text-center align-middle"><?echo(empty($itog_string['vnes_obed_10_count']))? 0:$itog_string['vnes_obed_10_count']?></td>


                <!--                <td class="text-center align-middle">--><?//= $itog_string['osn_menu_nado']+$itog_string['sahar_menu_nado']+$itog_string['cialic_menu_nado']+$itog_string['mukovis_menu_nado']+$itog_string['fenilketon_menu_nado']+$itog_string['ovz_menu_nado']+$itog_string['allergy_menu_nado'];?><!--</td>-->
                <!--                <td class="text-center align-middle">--><?//echo(empty($itog_string['osn_menu_nado']))? 0:$itog_string['osn_menu_nado'] ;?><!--</td>-->
                <!--                <td class="text-center align-middle">--><?//echo(empty($itog_string['sahar_menu_nado']))? 0:$itog_string['sahar_menu_nado'] ;?><!--</td>-->
                <!--                <td class="text-center align-middle">--><?//echo(empty($itog_string['cialic_menu_nado']))? 0:$itog_string['cialic_menu_nado'] ;?><!--</td>-->
                <!--                <td class="text-center align-middle">--><?//echo(empty($itog_string['mukovis_menu_nado']))? 0:$itog_string['mukovis_menu_nado'] ;?><!--</td>-->
                <!--                <td class="text-center align-middle">--><?//echo(empty($itog_string['fenilketon_menu_nado']))? 0:$itog_string['fenilketon_menu_nado'] ;?><!--</td>-->
                <!--                <td class="text-center align-middle">--><?//echo(empty($itog_string['ovz_menu_nado']))? 0:$itog_string['ovz_menu_nado'] ;?><!--</td>-->
                <!--                <td class="text-center align-middle">--><?//echo(empty($itog_string['allergy_menu_nado']))? 0:$itog_string['allergy_menu_nado'];?><!--</td>-->

                <!--                <td class="text-center align-middle">--><?//echo(empty($itog_string['sahar_nepit']))? 0:$itog_string['sahar_nepit'] ;?><!--</td>-->
                <!--                <td class="text-center align-middle">--><?//echo(empty($itog_string['cialic_nepit']))? 0:$itog_string['cialic_nepit'] ;?><!--</td>-->
                <!--                <td class="text-center align-middle">--><?//echo(empty($itog_string['mukovis_nepit']))? 0:$itog_string['mukovis_nepit'] ;?><!--</td>-->
                <!--                <td class="text-center align-middle">--><?//echo(empty($itog_string['fenilketon_nepit']))? 0:$itog_string['fenilketon_nepit'] ;?><!--</td>-->
                <!--                <td class="text-center align-middle">--><?//echo(empty($itog_string['ovz_nepit']))? 0:$itog_string['ovz_nepit'] ;?><!--</td>-->
                <!--                <td class="text-center align-middle">--><?//echo(empty($itog_string['allergy_nepit']))? 0:$itog_string['allergy_nepit'];?><!--</td>-->


                <td class="text-center align-middle"><?echo $itog_string['vnesli_detey'];?></td>
                <td class="text-center align-middle"><?echo $itog_string['nevnesli_detey'];?></td>
            </tr>



            <?$super_itog_string['student_sahar'] = $itog_string['student_sahar'] + $super_itog_string['student_sahar']; ?>
            <?$super_itog_string['student_cialic'] = $itog_string['student_cialic'] + $super_itog_string['student_cialic'];?>
            <?$super_itog_string['student_mukovis'] = $itog_string['student_mukovis'] + $super_itog_string['student_mukovis']; ?>
            <?$super_itog_string['student_fenilketon'] = $itog_string['student_fenilketon'] + $super_itog_string['student_fenilketon'];?>
            <?$super_itog_string['student_ovz'] = $itog_string['student_ovz'] + $super_itog_string['student_ovz']; ?>
            <?$super_itog_string['student_allergy'] = $itog_string['student_allergy'] + $super_itog_string['student_allergy']; ?>
            <?$super_itog_string['vnes_zavtrak_itog_count'] = $itog_string['vnes_zavtrak_itog_count'] + $super_itog_string['vnes_zavtrak_itog_count'];?>
            <?$super_itog_string['vnes_zavtrak_3_count'] = $itog_string['vnes_zavtrak_3_count'] + $super_itog_string['vnes_zavtrak_3_count'];?>

            <?$super_itog_string['vnes_zavtrak_5_count'] = $itog_string['vnes_zavtrak_5_count'] + $super_itog_string['vnes_zavtrak_5_count'];?>
            <?$super_itog_string['vnes_zavtrak_6_count'] = $itog_string['vnes_zavtrak_6_count'] + $super_itog_string['vnes_zavtrak_6_count'];?>
            <?$super_itog_string['vnes_zavtrak_8_count'] = $itog_string['vnes_zavtrak_8_count'] + $super_itog_string['vnes_zavtrak_8_count'];?>
            <?$super_itog_string['vnes_zavtrak_7_count'] = $itog_string['vnes_zavtrak_7_count'] + $super_itog_string['vnes_zavtrak_7_count'];?>
            <?$super_itog_string['vnes_zavtrak_4_count'] = $itog_string['vnes_zavtrak_4_count'] + $super_itog_string['vnes_zavtrak_4_count'];?>
            <?$super_itog_string['vnes_zavtrak_10_count'] = $itog_string['vnes_zavtrak_10_count'] + $super_itog_string['vnes_zavtrak_10_count'];?>

            <?$super_itog_string['vnes_obed_itog_count'] = $itog_string['vnes_obed_itog_count'] + $super_itog_string['vnes_obed_itog_count'];?>
            <?$super_itog_string['vnes_obed_3_count'] = $itog_string['vnes_obed_3_count'] + $super_itog_string['vnes_obed_3_count'];?>
            <?$super_itog_string['vnes_obed_5_count'] = $itog_string['vnes_obed_5_count'] + $super_itog_string['vnes_obed_5_count'];?>
            <?$super_itog_string['vnes_obed_6_count'] = $itog_string['vnes_obed_6_count'] + $super_itog_string['vnes_obed_6_count'];?>
            <?$super_itog_string['vnes_obed_8_count'] = $itog_string['vnes_obed_8_count'] + $super_itog_string['vnes_obed_8_count'];?>
            <?$super_itog_string['vnes_obed_7_count'] = $itog_string['vnes_obed_7_count'] + $super_itog_string['vnes_obed_7_count'];?>
            <?$super_itog_string['vnes_obed_4_count'] = $itog_string['vnes_obed_4_count'] + $super_itog_string['vnes_obed_4_count'];?>
            <?$super_itog_string['vnes_obed_10_count'] = $itog_string['vnes_obed_10_count'] + $super_itog_string['vnes_obed_10_count'];?>
            <?$super_itog_string['vnesli_detey'] =  $super_itog_string['vnesli_detey'] + $itog_string['vnesli_detey'];?>
            <?$super_itog_string['nevnesli_detey'] = $super_itog_string['nevnesli_detey'] + $itog_string['nevnesli_detey'];?>


        <?}?>
        <?if($post['municipality_id'] == 0){?>
        <tr class="table-primary">
            <td class="" colspan="3"><?if($post['municipality_id'] != 0){?>Итого<?}?> <?=\common\models\Region::findOne($region_id)->name;?>:</td>
            <td class="text-center align-middle"><?echo(empty($super_itog_string['student_sahar']))? 0:$super_itog_string['student_sahar'] ;?></td>
            <td class="text-center align-middle"><?echo(empty($super_itog_string['student_cialic']))? 0:$super_itog_string['student_cialic'] ;?></td>
            <td class="text-center align-middle"><?echo(empty($super_itog_string['student_mukovis']))? 0:$super_itog_string['student_mukovis'] ;?></td>
            <td class="text-center align-middle"><?echo(empty($super_itog_string['student_fenilketon']))? 0:$super_itog_string['student_fenilketon'] ;?></td>
            <td class="text-center align-middle"><?echo(empty($super_itog_string['student_ovz']))? 0:$super_itog_string['student_ovz'] ;?></td>
            <td class="text-center align-middle"><?echo(empty($super_itog_string['student_allergy']))? 0:$super_itog_string['student_allergy'] ;?></td>

            <td class="text-center align-middle"><?echo(empty($super_itog_string['vnes_zavtrak_itog_count']))? 0:$super_itog_string['vnes_zavtrak_itog_count']?></td>
            <td class="text-center align-middle"><?echo(empty($super_itog_string['vnes_zavtrak_3_count']))? 0:$super_itog_string['vnes_zavtrak_3_count']?></td>
            <td class="text-center align-middle"><?echo(empty($super_itog_string['vnes_zavtrak_5_count']))? 0:$super_itog_string['vnes_zavtrak_5_count']?></td>
            <td class="text-center align-middle"><?echo(empty($super_itog_string['vnes_zavtrak_6_count']))? 0:$super_itog_string['vnes_zavtrak_6_count']?></td>
            <td class="text-center align-middle"><?echo(empty($super_itog_string['vnes_zavtrak_8_count']))? 0:$super_itog_string['vnes_zavtrak_8_count']?></td>
            <td class="text-center align-middle"><?echo(empty($super_itog_string['vnes_zavtrak_7_count']))? 0:$super_itog_string['vnes_zavtrak_7_count']?></td>
            <td class="text-center align-middle"><?echo(empty($super_itog_string['vnes_zavtrak_4_count']))? 0:$super_itog_string['vnes_zavtrak_4_count']?></td>
            <td class="text-center align-middle"><?echo(empty($super_itog_string['vnes_zavtrak_10_count']))? 0:$super_itog_string['vnes_zavtrak_10_count']?></td>

            <td class="text-center align-middle"><?echo(empty($super_itog_string['vnes_obed_itog_count']))? 0:$super_itog_string['vnes_obed_itog_count']?></td>
            <td class="text-center align-middle"><?echo(empty($super_itog_string['vnes_obed_3_count']))? 0:$super_itog_string['vnes_obed_3_count']?></td>
            <td class="text-center align-middle"><?echo(empty($super_itog_string['vnes_obed_5_count']))? 0:$super_itog_string['vnes_obed_5_count']?></td>
            <td class="text-center align-middle"><?echo(empty($super_itog_string['vnes_obed_6_count']))? 0:$super_itog_string['vnes_obed_6_count']?></td>
            <td class="text-center align-middle"><?echo(empty($super_itog_string['vnes_obed_8_count']))? 0:$super_itog_string['vnes_obed_8_count']?></td>
            <td class="text-center align-middle"><?echo(empty($super_itog_string['vnes_obed_7_count']))? 0:$super_itog_string['vnes_obed_7_count']?></td>
            <td class="text-center align-middle"><?echo(empty($super_itog_string['vnes_obed_4_count']))? 0:$super_itog_string['vnes_obed_4_count']?></td>
            <td class="text-center align-middle"><?echo(empty($super_itog_string['vnes_obed_10_count']))? 0:$super_itog_string['vnes_obed_10_count']?></td>
            <td class="text-center align-middle"><?echo $super_itog_string['vnesli_detey'];?></td>
            <td class="text-center align-middle"><?echo $super_itog_string['nevnesli_detey'];?></td>
            <?}?>


        </tbody>
    </table>
    <div class="text-center mt-3 mb-3">
        <button id="pechat_character" class="btn btn-success">
            <span class="glyphicon glyphicon-download"></span> Скачать детальный отчет в Excel
        </button>
    </div>
<?}?>


<?
//print_r($data);
$script = <<< JS



$( ".beforeload" ).click(function() {
  $(".beforeload").css('display','none');
  $(".load").css('display','block');
  
});



function FixTable(table) {
	var inst = this;
	this.table  = table;
 
	$('thead > tr > td',$(this.table)).each(function(index) {
		var div_fixed = $('<div/>').addClass('fixtable-fixed');
		var div_relat = $('<div/>').addClass('fixtable-relative');
		div_fixed.html($(this).html());
		div_relat.html($(this).html());
		$(this).html('').append(div_fixed).append(div_relat);
		$(div_fixed).hide();
	});
	
 
	this.StyleColumns();
	this.FixColumns();
 
	$(window).scroll(function(){
		inst.FixColumns()
	}).resize(function(){
		inst.StyleColumns()
	});
}
 
FixTable.prototype.StyleColumns = function() {
	var inst = this;
	$('tr > th', $(this.table)).each(function(){
		var div_relat = $('div.fixtable-relative', $(this));
		var th = $(div_relat).parent('th');
		$('div.fixtable-fixed', $(this)).css({
			'width': $(th).outerWidth(true) - parseInt($(th).css('border-left-width')) + 'px',
			'height': $(th).outerHeight(true) + 'px',
			'left': $(div_relat).offset().left - parseInt($(th).css('padding-left')) + 'px',
			'padding-top': $(div_relat).offset().top - $(inst.table).offset().top + 'px',
			'padding-left': $(th).css('padding-left'),
			'padding-right': $(th).css('padding-right')
		});
	});
}
 
FixTable.prototype.FixColumns = function() {
	var inst = this;
	var show = false;
	var s_top = $(window).scrollTop();
	var h_top = $(inst.table).offset().top;
 
	if (s_top < (h_top + $(inst.table).height() - $(inst.table).find('.fixtable-fixed').outerHeight()) && s_top > h_top) {
		show = true;
	}
 
	$('tr > th > div.fixtable-fixed', $(this.table)).each(function(){
		show ? $(this).show() : $(this).hide()
	});
}
 
$(document).ready(function(){
	$('.fixtable').each(function() {
		new FixTable(this);
	});
});



$("#pechat_character").click(function () {
    var table = $('#table_character');
    if (table && table.length) {
        var preserveColors = (table.hasClass('table2excel_with_colors') ? true : false);
        $(table).table2excel({
            exclude: ".noExl",
            name: "Excel Document Name",
            filename: "Скачать отчет в Excel.xls",
            fileext: ".xls",
            exclude_img: true,
            exclude_links: true,
            exclude_inputs: true,
            preserveColors: preserveColors
        });
    }
});

JS;

$this->registerJs($script, yii\web\View::POS_READY);
?>
