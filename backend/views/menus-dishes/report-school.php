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

$this->title = 'Мониторинг 7-11 лет';
$this->params['breadcrumbs'][] = $this->title;
$organization_id = Yii::$app->user->identity->organization_id;
$region_id = Organization::findOne($organization_id)->region_id;
$my_mun = Organization::findOne($organization_id)->municipality_id;
$model_menus_dishes = new MenusDishes();
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

$organization_id = Yii::$app->user->identity->organization_id;
$region_id = Organization::findOne($organization_id)->region_id;

if(Yii::$app->user->can('admin') || Yii::$app->user->can('minobr') || Yii::$app->user->can('rospotrebnadzor_nutrition')){
    $municipalities = \common\models\Municipality::find()->where(['region_id' => $region_id])->all();
    $municipality_items = ArrayHelper::map($municipalities, 'id', 'name');
}
elseif(Yii::$app->user->can('subject_minobr')){
    $municipalities = \common\models\Municipality::find()->where(['id' => Organization::findOne($organization_id)->municipality_id])->all();
    $municipality_items = ArrayHelper::map($municipalities, 'id', 'name');
}



if(!empty($post)){

    $organizations = Organization::find()->where(['municipality_id' => $post['municipality_id'], 'type_org' => 3])->andWhere(['!=', 'id', 7])->orderBy(['short_title'=> SORT_ASC,'title'=> SORT_ASC])->all();
    $mun = \common\models\Municipality::findOne($post['municipality_id'])->name;
    //$region_id = \common\models\Municipality::findOne($post['menu_id'])->region_id;
    //$region = \common\models\Region::findOne($region_id)->name;


    $normativ = [];
    $nutrition_koeff_z = \common\models\NutritionProcent::find()->where(['type_org' => 3, 'nutrition_id' => 1])->one()->procent/100;
    $nutrition_koeff_o = \common\models\NutritionProcent::find()->where(['type_org' => 3, 'nutrition_id' => 3])->one()->procent/100;
    foreach ($vitamins_mas as $key => $vitamin_m){
        $normativ[1][$key] = \common\models\NormativVitaminDay::find()->where(['name' => $key, 'age_info_id' => 6])->one()->value*$nutrition_koeff_z;
        $normativ[3][$key] = \common\models\NormativVitaminDay::find()->where(['name' => $key, 'age_info_id' => 6])->one()->value*$nutrition_koeff_o;
    }
    foreach ($salt_sahar_yield_mas as $key => $salt_sahar_yield_m){
        $normativ[1][$key] = \common\models\NormativVitaminDayNew::find()->where(['name' => $key, 'age_info_id' => 6, 'nutrition_id' => 1])->one()->value;
        $normativ[3][$key] = \common\models\NormativVitaminDayNew::find()->where(['name' => $key, 'age_info_id' => 6, 'nutrition_id' => 3])->one()->value;
    }


    if($post['city_id'] != 0){
        $organizations = Organization::find()->where(['type_org' => 3, 'city_id' => $post['city_id']])->all();
    }


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

<?php ActiveForm::end(); ?>




<?if(!empty($post)){?>
    <table class="table table-bordered table-sm">
        <thead>
        <tr>
            <td rowspan="3">№</td>
            <td rowspan="3">Муниципальное образование</td>
            <td rowspan="3">Наименование общеобразовательной организации</td>
            <td rowspan="3">Количество школ внесших информацию</td>
            <td rowspan="1" colspan="2">в т.ч.</td>
            <td rowspan="2" colspan="3">Количество школьников 1-4 кл. обучающихся очно</td>
            <td rowspan="2" colspan="2">Продолжительность перемен для питания детей (в мин.)</td>
            <td rowspan="3" >Количество школ в которых занижена продолжительность перемен (менее 20 минут)</td>
            <td rowspan="2" colspan="7">Количество ШКОЛ имеющих школьников 1-4 кл. с заболеваниями требующими индивидуального подхода в организации питания</td>
            <td rowspan="2" colspan="7">Количество ШКОЛЬНИКОВ 1-4 кл., имеющих заболевания, требующие индивидуального подхода в организации питания</td>
            <td rowspan="1" colspan="4">Количество</td>
            <td rowspan="3">Количество оборудованных посадочных мест</td>
            <td rowspan="3">Площадь на 1 посадочное место</td>
            <td rowspan="3">Максимальное количество одномоментно питающихся детей</td>
            <td rowspan="3">фактическая площадь на одно посадочное место</td>
            <td rowspan="3">Имеется дефицит площади столовой</td>
            <td rowspan="3">Количество школ в которых количество питающихся превышает количество посадочных мест</td>
            <td rowspan="3">Количество функционирующих умывальников уст. перед входом в столовую</td>
            <td rowspan="3">Количество детей на один умывальник (д.б. не менее 1 на 20 дет)</td>
            <td rowspan="3">Имеется дефицит умывальников</td>

            <td rowspan="2" colspan="8">Количество ШКОЛ внесших меню (ЗАВТРАКОВ)</td>
            <td rowspan="2" colspan="8">Количество внесенных меню (ЗАВТРАКОВ)</td>



            <td colspan="13">Средние показатели, характеризующие завтраки</td>
            <td rowspan="2" colspan="6">количество ШКОЛ, предусматривающих выдачу в завтрак</td>
            <td rowspan="2" colspan="6">количество дней в меню, предусматривающих выдачу в завтрак</td>
            <td rowspan="3">Количество школ с дефицитом калорийности блюд(ЗАВТРАК)</td>
            <td rowspan="2" colspan="8">Количество ШКОЛ внесших меню (ОБЕДОВ)</td>
            <td rowspan="2" colspan="8">Количество внесенных меню (ОБЕДОВ)</td>
            <td colspan="13">Средние показатели, характеризующие обеды</td>
            <td rowspan="2" colspan="6">количество ШКОЛ, предусматривающих выдачу в Обед</td>
            <td rowspan="2" colspan="6">количество дней в меню, предусматривающих выдачу в Обед</td>
            <td rowspan="3">Количество школ с дефицитом калорийности блюд(ОБЕД)</td>
            <td colspan="38">количество школьников, получающих бесплатное питание, и охват  в %</td>



            <td class="text-center align-middle" colspan="7">ЗАВТРАКИ</td>
            <td class="text-center align-middle" colspan="7">ОБЕДЫ</td>
            <td class="text-center align-middle" colspan="7">ИНЫЕ ПРИЕМЫ ПИЩИ</td>
        </tr>
        <tr>
            <td rowspan="2">шк.1-4 кл. обуч только в первую смену</td>
            <td rowspan="2">шк.1-4 кл. обуч в две смены</td>

            <td colspan="2">школ, в которых есть дети непитающиеся в столовой, удельный вес их от всех школьников 1-4 классов</td>
            <td colspan="2">детей непитающихся в столовой, удельный вес их от всех школьников 1-4 классов</td>
            <!--ЗАВТРАК-->
            <td >суммарная масса блюд (г.)</td>
            <td>калорийность (ккал)</td>
            <td colspan="4">Содержание витаминов</td>
            <td colspan="5">Содержание минеральных веществ</td>
            <td colspan="2">Содержание в среднем за прием пищи</td>



            <!--ОБЕД-->
            <td >суммарная масса блюд (г.)</td>
            <td >калорийность (ккал)</td>
            <td colspan="4">Содержание витаминов</td>
            <td colspan="5">Содержание минеральных веществ</td>
            <td colspan="2">Содержание в среднем за прием пищи</td>


            <!--            озватпит-->
            <td colspan="2">школьников 1-4 кл. (всего)</td>
            <td colspan="2">школьников 1-4 кл. получающих бесплатные горячие завтраки</td>
            <td colspan="2">школьников 1-4 кл. получающих бесплатные обеды</td>
            <td colspan="2">школьников 1-4 кл. получающих бесплатные завтраки и обеды</td>
            <td colspan="5" class="text-center">СД</td>
            <td colspan="5" class="text-center">целиакия</td>
            <td colspan="5" class="text-center">Муковисцидоз</td>
            <td colspan="5" class="text-center">Фенилкетонурия</td>
            <td colspan="5" class="text-center">ОВЗ</td>
            <td colspan="5" class="text-center">ПА</td>






            <td rowspan="2">Количество мероприятий родительского контроля</td>
            <td colspan="3">Количество баллов</td>
            <td colspan="3">Процент несъеденной пищи</td>

            <td rowspan="2">Количество мероприятий родительского контроля</td>
            <td colspan="3">Количество баллов</td>
            <td colspan="3">Процент несъеденной пищи</td>

            <td rowspan="2">Количество мероприятий родительского контроля</td>
            <td colspan="3">Количество баллов</td>
            <td colspan="3">Процент несъеденной пищи</td>
        </tr>
        <tr>
            <td>всего</td>
            <td>в I-ю смену</td>
            <td>во II-ю</td>
            <td>мин</td>
            <td>макс</td>

            <td>СД</td>
            <td>Цел</td>
            <td>Мук</td>
            <td>ФКУ</td>
            <td>овз</td>
            <td>ПА</td>
            <td class="text-center" style="min-width: 90px">ПА и ОВЗ</td>

            <td>СД</td>
            <td>Цел</td>
            <td>Мук</td>
            <td>ФКУ</td>
            <td>овз</td>
            <td>ПА</td>
            <td class="text-center" style="min-width: 90px">ПА и ОВЗ</td>




            <td>Всего</td>
            <td>%</td>
            <td>Всего</td>
            <td>%</td>


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



            <td>ср</td>

            <td>ср</td>

            <td>С(мг)</td>
            <td>В1(мг)</td>
            <td>В2(мг)</td>
            <td>А (р.э.)</td>

            <td>кальций (мг)</td>
<!--            <td>фосфор (мг)</td>-->
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

            <td>колбасных изделий</td>
            <td>кондитерских изделий</td>
            <td>фруктов</td>
            <td>ягод</td>
            <td>меда</td>
            <td>овощей</td>

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



            <!--ОБЕД-->
            <td>ср</td>

            <td>ср</td>

            <td>С(мг)</td>
            <td>В1(мг)</td>
            <td>В2(мг)</td>
            <td>А (р.э.)</td>

            <td>кальций (мг)</td>
<!--            <td>фосфор (мг)</td>-->
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

            <td>колбасных изделий</td>
            <td>кондитерских изделий</td>
            <td>фруктов</td>
            <td>ягод</td>
            <td>меда</td>
            <td>овощей</td>




            <!--треб/пит-->
            <td>всего</td>
            <td>%</td>
            <td>всего</td>
            <td>%</td>
            <td>всего</td>
            <td>%</td>
            <td>всего</td>
            <td>%</td>

            <td>кол-во шк</td>
            <td>кол-во дет</td>
            <td>кол-во шк внес меню</td>
            <td>кол-во пит дет</td>
            <td>кол-во не пит дет</td>

            <td>кол-во шк</td>
            <td>кол-во дет</td>
            <td>кол-во шк внес меню</td>
            <td>кол-во пит дет</td>
            <td>кол-во не пит дет</td>


            <td>кол-во шк</td>
            <td>кол-во дет</td>
            <td>кол-во шк внес меню</td>
            <td>кол-во пит дет</td>
            <td>кол-во не пит дет</td>

            <td>кол-во шк</td>
            <td>кол-во дет</td>
            <td>кол-во шк внес меню</td>
            <td>кол-во пит дет</td>
            <td>кол-во не пит дет</td>

            <td>кол-во шк</td>
            <td>кол-во дет</td>
            <td>кол-во шк внес меню</td>
            <td>кол-во пит дет</td>
            <td>кол-во не пит дет</td>

            <td>кол-во шк</td>
            <td>кол-во дет</td>
            <td>кол-во шк внес меню</td>
            <td>кол-во пит дет</td>
            <td>кол-во не пит дет</td>


            <td>мин</td>
            <td>ср</td>
            <td>макс</td>
            <td>мин</td>
            <td>ср</td>
            <td>макс</td>

            <td>мин</td>
            <td>ср</td>
            <td>макс</td>
            <td>мин</td>
            <td>ср</td>
            <td>макс</td>

            <td>мин</td>
            <td>ср</td>
            <td>макс</td>
            <td>мин</td>
            <td>ср</td>
            <td>макс</td>

        </tr>

        </thead>
        <tbody>
        <?$itog_string = []; $itog_string['ohvat_sahar_school'] = 0;  $itog_string['ohvat_sahar_student'] = 0; $itog_string['shcolnik_sahar_menu'] = 0; $itog_string['ohvat_sahar_pit'] = 0; $itog_string['ohvat_sahar_nepit'] = 0;//ИТОГИ?>
        <?$itog_string['ohvat_cialic_school'] = 0;  $itog_string['ohvat_cialic_student'] = 0; $itog_string['shcolnik_cialic_menu'] = 0; $itog_string['ohvat_cialic_pit'] = 0; $itog_string['ohvat_cialic_nepit'] = 0;//ИТОГИ?>
        <?$itog_string['ohvat_mukovis_school'] = 0;  $itog_string['ohvat_mukovis_student'] = 0; $itog_string['shcolnik_mukovis_menu'] = 0; $itog_string['ohvat_mukovis_pit'] = 0; $itog_string['ohvat_mukovis_nepit'] = 0;//ИТОГИ?>
        <?$itog_string['ohvat_fenilketon_school'] = 0;  $itog_string['ohvat_fenilketon_student'] = 0; $itog_string['shcolnik_fenilketon_menu'] = 0; $itog_string['ohvat_fenilketon_pit'] = 0; $itog_string['ohvat_fenilketon_nepit'] = 0;//ИТОГИ?>
        <?$itog_string['ohvat_ovz_school'] = 0;  $itog_string['ohvat_ovz_student'] = 0; $itog_string['shcolnik_ovz_menu'] = 0; $itog_string['ohvat_ovz_pit'] = 0; $itog_string['ohvat_ovz_nepit'] = 0;//ИТОГИ?>
        <?$itog_string['ohvat_allergy_school'] = 0;  $itog_string['ohvat_allergy_student'] = 0; $itog_string['shcolnik_allergy_menu'] = 0; $itog_string['ohvat_allergy_pit'] = 0; $itog_string['ohvat_allergy_nepit'] = 0;//ИТОГИ?>
        <? $count = 0; $sred = []; foreach ($organizations as $organization) { $count++;?>
            <? $character_stolovaya = \common\models\CharactersStolovaya::find()->where(['organization_id' => $organization->id])->one();?>
            <? $school_break_min = SchoolBreak::find()->where(['organization_id' => $organization->id])->min('duration');?>
            <? $school_break_max = SchoolBreak::find()->where(['organization_id' => $organization->id])->max('duration');?>
            <? $school_break_count = SchoolBreak::find()->where(['organization_id' => $organization->id])->count();if($school_break_count != 0){$school_break_sred = round(SchoolBreak::find()->where(['organization_id' => $organization->id])->sum('duration')/$school_break_count,1);}else{$school_break_sred = 0;}?>
            <? $information_education = \common\models\InformationEducation::find()->where(['organization_id' => $organization->id])->one();?>
            <? $cs_mas = []; $students_class = \common\models\StudentsClass::find()->where(['organization_id' => $organization->id, 'class_number' => [1,2,3,4]])->all();?>
            <?$number = [];
            $vt_smena = $information_education->quantity14 - $information_education->quantity14_first;?>



            <?$students_class_mas = ArrayHelper::map($students_class, 'id', 'id');

            $cs_mas['sahar'] = \common\models\Students::find()->where(['students_class_id' => $students_class_mas, 'form_study' => 1, 'otkaz_pitaniya' => 1, 'dis_sahar' => 1, 'dis_ovz' => 0, 'dis_cialic' => 0, 'dis_mukovis' => 0, 'dis_fenilketon' => 0, 'al_moloko' => 0, 'al_yico' => 0, 'al_fish' => 0, 'al_chocolad' => 0, 'al_orehi' => 0, 'al_citrus' => 0, 'al_med' => 0, 'al_pshenica' => 0, 'al_arahis' => 0])->count();
            $cs_mas['ovz'] = \common\models\Students::find()->where(['students_class_id' => $students_class_mas, 'form_study' => 1, 'otkaz_pitaniya' => 1, 'dis_sahar' => 0, 'dis_ovz' => 1, 'dis_cialic' => 0, 'dis_mukovis' => 0, 'dis_fenilketon' => 0, 'al_moloko' => 0, 'al_yico' => 0, 'al_fish' => 0, 'al_chocolad' => 0, 'al_orehi' => 0, 'al_citrus' => 0, 'al_med' => 0, 'al_pshenica' => 0, 'al_arahis' => 0])->count();
            //$cs_mas['sahar_ovz'] = \common\models\Students::find()->where(['students_class_id' => $students_class_mas, 'form_study' => 1, 'otkaz_pitaniya' => 1, 'dis_sahar' => 1, 'dis_ovz' => 1, 'dis_cialic' => 0, 'dis_mukovis' => 0, 'dis_fenilketon' => 0, 'al_moloko' => 0, 'al_yico' => 0, 'al_fish' => 0, 'al_chocolad' => 0, 'al_orehi' => 0, 'al_citrus' => 0, 'al_med' => 0, 'al_pshenica' => 0, 'al_arahis' => 0])->count();
            $cs_mas['cialic'] = \common\models\Students::find()->where(['students_class_id' => $students_class_mas, 'form_study' => 1, 'otkaz_pitaniya' => 1, 'dis_sahar' => 0, 'dis_ovz' => 0, 'dis_cialic' => 1, 'dis_mukovis' => 0, 'dis_fenilketon' => 0, 'al_moloko' => 0, 'al_yico' => 0, 'al_fish' => 0, 'al_chocolad' => 0, 'al_orehi' => 0, 'al_citrus' => 0, 'al_med' => 0, 'al_pshenica' => 0, 'al_arahis' => 0])->count();
            $cs_mas['mukovis'] = \common\models\Students::find()->where(['students_class_id' => $students_class_mas, 'form_study' => 1, 'otkaz_pitaniya' => 1, 'dis_sahar' => 0, 'dis_ovz' => 0, 'dis_cialic' => 0, 'dis_mukovis' => 1, 'dis_fenilketon' => 0, 'al_moloko' => 0, 'al_yico' => 0, 'al_fish' => 0, 'al_chocolad' => 0, 'al_orehi' => 0, 'al_citrus' => 0, 'al_med' => 0, 'al_pshenica' => 0, 'al_arahis' => 0])->count();

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
            $cs_mas['ovz_allergy'] = \common\models\Students::find()->where(['students_class_id' => $students_class_mas, 'form_study' => 1, 'otkaz_pitaniya' => 1, 'dis_sahar' => 0, 'dis_ovz' => 1, 'dis_cialic' => 0, 'dis_mukovis' => 0, 'dis_fenilketon' => 0, 'al_moloko' => 1])
                ->orWhere(['students_class_id' => $students_class_mas, 'form_study' => 1, 'otkaz_pitaniya' => 1, 'dis_sahar' => 0, 'dis_ovz' => 1, 'dis_cialic' => 0, 'dis_mukovis' => 0, 'dis_fenilketon' => 0, 'al_yico' => 1])
                ->orWhere(['students_class_id' => $students_class_mas, 'form_study' => 1, 'otkaz_pitaniya' => 1, 'dis_sahar' => 0, 'dis_ovz' => 1, 'dis_cialic' => 0, 'dis_mukovis' => 0, 'dis_fenilketon' => 0, 'al_fish' => 1])
                ->orWhere(['students_class_id' => $students_class_mas, 'form_study' => 1, 'otkaz_pitaniya' => 1, 'dis_sahar' => 0, 'dis_ovz' => 1, 'dis_cialic' => 0, 'dis_mukovis' => 0, 'dis_fenilketon' => 0, 'al_chocolad' => 1])
                ->orWhere(['students_class_id' => $students_class_mas, 'form_study' => 1, 'otkaz_pitaniya' => 1, 'dis_sahar' => 0, 'dis_ovz' => 1, 'dis_cialic' => 0, 'dis_mukovis' => 0, 'dis_fenilketon' => 0, 'al_orehi' => 1])
                ->orWhere(['students_class_id' => $students_class_mas, 'form_study' => 1, 'otkaz_pitaniya' => 1, 'dis_sahar' => 0, 'dis_ovz' => 1, 'dis_cialic' => 0, 'dis_mukovis' => 0, 'dis_fenilketon' => 0, 'al_citrus' => 1])
                ->orWhere(['students_class_id' => $students_class_mas, 'form_study' => 1, 'otkaz_pitaniya' => 1, 'dis_sahar' => 0, 'dis_ovz' => 1, 'dis_cialic' => 0, 'dis_mukovis' => 0, 'dis_fenilketon' => 0, 'al_med' => 1])
                ->orWhere(['students_class_id' => $students_class_mas, 'form_study' => 1, 'otkaz_pitaniya' => 1, 'dis_sahar' => 0, 'dis_ovz' => 1, 'dis_cialic' => 0, 'dis_mukovis' => 0, 'dis_fenilketon' => 0, 'al_pshenica' => 1])
                ->orWhere(['students_class_id' => $students_class_mas, 'form_study' => 1, 'otkaz_pitaniya' => 1, 'dis_sahar' => 0, 'dis_ovz' => 1, 'dis_cialic' => 0, 'dis_mukovis' => 0, 'dis_fenilketon' => 0, 'al_arahis' => 1])
                ->count();
            //SELECT *  FROM `students` WHERE `dis_sahar` = 1 && (`al_moloko`=1 || `al_yico`=1 || `al_fish`=1 || `al_chocolad`=1 || `al_orehi`=1 || `al_citrus`=1 || `al_med`=1 || `al_pshenica`=1 || `al_arahis`=1 || `al_inoe`=1) ORDER BY `students_class_id`  DESC
            $cs_mas['fenilketon'] = \common\models\Students::find()->where(['students_class_id' => $students_class_mas, 'form_study' => 1, 'otkaz_pitaniya' => 1, 'dis_sahar' => 0, 'dis_ovz' => 0, 'dis_cialic' => 0, 'dis_mukovis' => 0, 'dis_fenilketon' => 1, 'al_moloko' => 0, 'al_yico' => 0, 'al_fish' => 0, 'al_chocolad' => 0, 'al_orehi' => 0, 'al_citrus' => 0, 'al_med' => 0, 'al_pshenica' => 0, 'al_arahis' => 0])->count();
            $cs_mas['students_1smena'] = \common\models\Students::find()->where(['students_class_id' => ArrayHelper::map(\common\models\StudentsClass::find()->where(['organization_id' => $organization->id, 'class_number' => [1,2,3,4], 'smena' => 1])->all(), 'id', 'id'), 'form_study' => 1])->count();
            $cs_mas['students_2smena'] = \common\models\Students::find()->where(['students_class_id' => ArrayHelper::map(\common\models\StudentsClass::find()->where(['organization_id' => $organization->id, 'class_number' => [1,2,3,4], 'smena' => 2])->all(), 'id', 'id'), 'form_study' => 1])->count();
            $cs_mas['students_all_smena'] = $cs_mas['students_1smena']+ $cs_mas['students_2smena'];

            $cs_mas['students_ochno_i_zaochno'] = \common\models\Students::find()->where(['students_class_id' => ArrayHelper::map(\common\models\StudentsClass::find()->where(['organization_id' => $organization->id, 'class_number' => [1,2,3,4]])->all(), 'id', 'id')])->count();
            $cs_mas['otkaz'] = \common\models\Students::find()->where(['students_class_id' => ArrayHelper::map(\common\models\StudentsClass::find()->where(['organization_id' => $organization->id, 'class_number' => [1,2,3,4]])->all(), 'id', 'id'), 'otkaz_pitaniya' => 0])->count();

            //для расчета max числа питающихся в перемену:
            $cs_mas['students_1smena_mas'] = ArrayHelper::map(\common\models\Students::find()->where(['students_class_id' => ArrayHelper::map(\common\models\StudentsClass::find()->where(['organization_id' => $organization->id, 'class_number' => [1,2,3,4], 'smena' => 1])->all(), 'id', 'id')])->all(), 'id', 'id');
            $cs_mas['students_2smena_mas'] = ArrayHelper::map(\common\models\Students::find()->where(['students_class_id' => ArrayHelper::map(\common\models\StudentsClass::find()->where(['organization_id' => $organization->id, 'class_number' => [1,2,3,4], 'smena' => 2])->all(), 'id', 'id')])->all(), 'id', 'id');
            $peremen_mas = [1=>1,2=>2,3=>3,4=>4,5=>5,6=>6];$pit = 0;
            foreach ($peremen_mas as $peremena){
                $pit = \common\models\StudentsNutrition::find()->where(['students_id' => $cs_mas['students_1smena_mas'], 'peremena' => $peremena])->count();
                if($pit > $cs_mas['max_pit']){
                    $cs_mas['max_pit'] = $pit;
                }
                $pit = \common\models\StudentsNutrition::find()->where(['students_id' => $cs_mas['students_2smena_mas'], 'peremena' => $peremena])->count();
                if($pit > $cs_mas['max_pit']){
                    $cs_mas['max_pit'] = $pit;
                }
            }

            //охват
            $classes_mas = ArrayHelper::map(\common\models\StudentsClass::find()->where(['organization_id' => $organization->id, 'class_number' => [1,2,3,4]])->all(), 'id', 'id');
            $students_mas = ArrayHelper::map(\common\models\Students::find()->where(['students_class_id' => $classes_mas])->all(), 'id', 'id');
            $cs_mas['ohvat_vsego'] = $cs_mas['students_ochno_i_zaochno'] - $cs_mas['otkaz'];

            if($cs_mas['students_ochno_i_zaochno'] != 0)
            {
                $cs_mas['ohvat_%_vsego'] = round(($cs_mas['students_ochno_i_zaochno'] - $cs_mas['otkaz'])/$cs_mas['students_ochno_i_zaochno']*100, 2);
                //находим одновременно завтракающих и обедающих
                $array1 = ArrayHelper::map(\common\models\StudentsNutrition::find()->where(['students_id' => $students_mas, 'nutrition_id' => 1])->all(), 'students_id', 'students_id');
                $array2 = ArrayHelper::map(\common\models\StudentsNutrition::find()->where(['students_id' => $students_mas, 'nutrition_id' => 3])->all(), 'students_id', 'students_id');
                $cs_mas['ohvat_zavtrak_obed'] = count(array_intersect($array1, $array2));
                $cs_mas['ohvat_%_zavtrak_obed'] = round($cs_mas['ohvat_zavtrak_obed']/$cs_mas['ohvat_vsego']*100, 2);
                //потом отнимаем их от завтракающих и от обедающих
                $cs_mas['ohvat_zavtrak'] = \common\models\StudentsNutrition::find()->where(['students_id' => $students_mas, 'nutrition_id' => 1])->count() - $cs_mas['ohvat_zavtrak_obed'];
                $cs_mas['ohvat_%_zavtrak'] = round($cs_mas['ohvat_zavtrak']/$cs_mas['ohvat_vsego']*100, 2);
                $cs_mas['ohvat_obed'] = \common\models\StudentsNutrition::find()->where(['students_id' => $students_mas, 'nutrition_id' => 3])->count() - $cs_mas['ohvat_zavtrak_obed'];
                $cs_mas['ohvat_%_obed'] = round($cs_mas['ohvat_obed']/$cs_mas['ohvat_vsego']*100, 2);

                //ohvat_osoben
                //sahar
                $cs_mas['ohvat_sahar_student'] = \common\models\Students::find()->where(['students_class_id' => $students_class_mas, 'dis_sahar' => 1, 'dis_ovz' => 0, 'dis_cialic' => 0, 'dis_mukovis' => 0, 'dis_fenilketon' => 0, 'al_moloko' => 0, 'al_yico' => 0, 'al_fish' => 0, 'al_chocolad' => 0, 'al_orehi' => 0, 'al_citrus' => 0, 'al_med' => 0, 'al_pshenica' => 0, 'al_arahis' => 0])->count();
                if ($cs_mas['ohvat_sahar_student'] > 0)
                {
                    $cs_mas['ohvat_sahar_school'] = 1;
                }
                else
                {
                    $cs_mas['ohvat_sahar_school'] = 0;
                }
                $cs_mas['ohvat_sahar_pit'] = $cs_mas['sahar'];
                $cs_mas['ohvat_sahar_nepit'] = $cs_mas['ohvat_sahar_student'] - $cs_mas['sahar'];

                //cialic
                $cs_mas['ohvat_cialic_student'] = \common\models\Students::find()->where(['students_class_id' => $students_class_mas, 'dis_sahar' => 0, 'dis_ovz' => 0, 'dis_cialic' => 1, 'dis_mukovis' => 0, 'dis_fenilketon' => 0, 'al_moloko' => 0, 'al_yico' => 0, 'al_fish' => 0, 'al_chocolad' => 0, 'al_orehi' => 0, 'al_citrus' => 0, 'al_med' => 0, 'al_pshenica' => 0, 'al_arahis' => 0])->count();
                if ($cs_mas['ohvat_cialic_student'] > 0)
                {
                    $cs_mas['ohvat_cialic_school'] = 1;
                }
                else
                {
                    $cs_mas['ohvat_cialic_school'] = 0;
                }
                $cs_mas['ohvat_cialic_pit'] = $cs_mas['cialic'];
                $cs_mas['ohvat_cialic_nepit'] = $cs_mas['ohvat_cialic_student'] - $cs_mas['cialic'];

                //mukovis
                $cs_mas['ohvat_mukovis_student'] = \common\models\Students::find()->where(['students_class_id' => $students_class_mas, 'dis_sahar' => 0, 'dis_ovz' => 0, 'dis_cialic' => 0, 'dis_mukovis' => 1, 'dis_fenilketon' => 0, 'al_moloko' => 0, 'al_yico' => 0, 'al_fish' => 0, 'al_chocolad' => 0, 'al_orehi' => 0, 'al_citrus' => 0, 'al_med' => 0, 'al_pshenica' => 0, 'al_arahis' => 0])->count();
                if ($cs_mas['ohvat_mukovis_student'] > 0)
                {
                    $cs_mas['ohvat_mukovis_school'] = 1;
                }
                else
                {
                    $cs_mas['ohvat_mukovis_school'] = 0;
                }
                $cs_mas['ohvat_mukovis_pit'] = $cs_mas['mukovis'];
                $cs_mas['ohvat_mukovis_nepit'] = $cs_mas['ohvat_mukovis_student'] - $cs_mas['mukovis'];

                //fenilketon
                $cs_mas['ohvat_fenilketon_student'] = \common\models\Students::find()->where(['students_class_id' => $students_class_mas, 'dis_sahar' => 0, 'dis_ovz' => 0, 'dis_cialic' => 0, 'dis_mukovis' => 0, 'dis_fenilketon' => 1, 'al_moloko' => 0, 'al_yico' => 0, 'al_fish' => 0, 'al_chocolad' => 0, 'al_orehi' => 0, 'al_citrus' => 0, 'al_med' => 0, 'al_pshenica' => 0, 'al_arahis' => 0])->count();
                if ($cs_mas['ohvat_fenilketon_student'] > 0)
                {
                    $cs_mas['ohvat_fenilketon_school'] = 1;
                }
                else
                {
                    $cs_mas['ohvat_fenilketon_school'] = 0;
                }
                $cs_mas['ohvat_fenilketon_pit'] = $cs_mas['fenilketon'];
                $cs_mas['ohvat_fenilketon_nepit'] = $cs_mas['ohvat_fenilketon_student'] - $cs_mas['fenilketon'];

                //ovz
                $cs_mas['ohvat_ovz_student'] = \common\models\Students::find()->where(['students_class_id' => $students_class_mas, 'dis_sahar' => 0, 'dis_ovz' => 1, 'dis_cialic' => 0, 'dis_mukovis' => 0, 'dis_fenilketon' => 0, 'al_moloko' => 0, 'al_yico' => 0, 'al_fish' => 0, 'al_chocolad' => 0, 'al_orehi' => 0, 'al_citrus' => 0, 'al_med' => 0, 'al_pshenica' => 0, 'al_arahis' => 0])->count();
                if ($cs_mas['ohvat_ovz_student'] > 0)
                {
                    $cs_mas['ohvat_ovz_school'] = 1;
                }
                else
                {
                    $cs_mas['ohvat_ovz_school'] = 0;
                }
                $cs_mas['ohvat_ovz_pit'] = $cs_mas['ovz'];
                $cs_mas['ohvat_ovz_nepit'] = $cs_mas['ohvat_ovz_student'] - $cs_mas['ovz'];

                //allergy
                $cs_mas['ohvat_allergy_student'] = \common\models\Students::find()->where(['students_class_id' => $students_class_mas, 'dis_sahar' => 0, 'dis_ovz' => 0, 'dis_cialic' => 0, 'dis_mukovis' => 0, 'dis_fenilketon' => 0, 'al_moloko' => 1])
                    ->orWhere(['students_class_id' => $students_class_mas, 'dis_sahar' => 0, 'dis_ovz' => 0, 'dis_cialic' => 0, 'dis_mukovis' => 0, 'dis_fenilketon' => 0, 'al_yico' => 1])
                    ->orWhere(['students_class_id' => $students_class_mas, 'dis_sahar' => 0, 'dis_ovz' => 0, 'dis_cialic' => 0, 'dis_mukovis' => 0, 'dis_fenilketon' => 0, 'al_fish' => 1])
                    ->orWhere(['students_class_id' => $students_class_mas, 'dis_sahar' => 0, 'dis_ovz' => 0, 'dis_cialic' => 0, 'dis_mukovis' => 0, 'dis_fenilketon' => 0, 'al_chocolad' => 1])
                    ->orWhere(['students_class_id' => $students_class_mas, 'dis_sahar' => 0, 'dis_ovz' => 0, 'dis_cialic' => 0, 'dis_mukovis' => 0, 'dis_fenilketon' => 0, 'al_orehi' => 1])
                    ->orWhere(['students_class_id' => $students_class_mas, 'dis_sahar' => 0, 'dis_ovz' => 0, 'dis_cialic' => 0, 'dis_mukovis' => 0, 'dis_fenilketon' => 0, 'al_citrus' => 1])
                    ->orWhere(['students_class_id' => $students_class_mas, 'dis_sahar' => 0, 'dis_ovz' => 0, 'dis_cialic' => 0, 'dis_mukovis' => 0, 'dis_fenilketon' => 0, 'al_med' => 1])
                    ->orWhere(['students_class_id' => $students_class_mas, 'dis_sahar' => 0, 'dis_ovz' => 0, 'dis_cialic' => 0, 'dis_mukovis' => 0, 'dis_fenilketon' => 0, 'al_pshenica' => 1])
                    ->orWhere(['students_class_id' => $students_class_mas, 'dis_sahar' => 0, 'dis_ovz' => 0, 'dis_cialic' => 0, 'dis_mukovis' => 0, 'dis_fenilketon' => 0, 'al_arahis' => 1])
                    ->count();
                if ($cs_mas['ohvat_allergy_student'] > 0)
                {
                    $cs_mas['ohvat_allergy_school'] = 1;
                }
                else
                {
                    $cs_mas['ohvat_allergy_school'] = 0;
                }
                $cs_mas['ohvat_allergy_pit'] = $cs_mas['allergy'];
                $cs_mas['ohvat_allergy_nepit'] = $cs_mas['ohvat_allergy_student'] - $cs_mas['allergy'];
            }


            if(!empty($organization->short_title) && !empty($character_stolovaya) && !empty($students_class) && !empty($information_education)){  $itog_string['school_vnes_info'] = $itog_string['school_vnes_info'] + 1;  $cs_mas['school_vnes_info'] = 1;}else{  $itog_string['school_vnes_info'] = $itog_string['school_vnes_info'] + 0; $cs_mas['school_vnes_info'] = 0;;}


            $menu_mas = [];
            $menus = \common\models\Menus::find()->where(['organization_id' => $organization->id, 'age_info_id' => [6, 9], 'status_archive' => 0 ])->andWhere(['>=', 'date_end', strtotime("now")])->all();
            foreach($menus as $menu){
                if(MenusDishes::find()->where(['menu_id' => $menu->id, 'nutrition_id' => 1])->count() > 0){
                    $menu_mas['zavtrak'][$menu->feeders_characters_id] = $menu_mas['zavtrak'][$menu->feeders_characters_id] + 1;
                    $menu_mas['zavtrak']['itog'] = $menu_mas['zavtrak']['itog'] + 1;
                }
                if(MenusDishes::find()->where(['menu_id' => $menu->id, 'nutrition_id' => 3])->count() > 0){
                    $menu_mas['obed'][$menu->feeders_characters_id] = $menu_mas['obded'][$menu->feeders_characters_id] + 1;
                    $menu_mas['obed']['itog'] = $menu_mas['obed']['itog'] + 1;
                }
            }
            $menu = \common\models\Menus::find()->where(['organization_id' => $organization->id, 'age_info_id' => [6, 9]])->andWhere(['>=', 'date_end', strtotime("now")])->count();
            ?>

            <tr>
                <td class="text-center align-middle"><?=$count?></td>
                <td class="text-center align-middle"><?=$mun?></td>
                <td class="align-middle"><?echo (empty($organization->short_title)) ? $organization->title : $organization->short_title;?></td>
                <td class="align-middle"><?=$cs_mas['school_vnes_info'];?></td>
                <?if($cs_mas['students_all_smena'] > 0){?>
                    <td class="text-center align-middle"><? if($cs_mas['students_1smena']>0 && $cs_mas['students_2smena'] == 0){$v=1;}else{$v=0;} echo $v; $itog_string['students_1smena_count_sch'] = $itog_string['students_1smena_count_sch'] +$v;?></td>
                    <td class="text-center align-middle"><? if($cs_mas['students_1smena']>0 && $cs_mas['students_2smena'] > 0){$v=1;}else{$v=0;} echo $v; $itog_string['students_all_smena_count_sch'] = $itog_string['students_all_smena_count_sch'] +$v;?></td>
                    <td class="text-center align-middle"><?$itog_string['students_all_smena'] = $itog_string['students_all_smena'] + $cs_mas['students_all_smena']; echo $cs_mas['students_all_smena'];?></td>
                    <td class="text-center align-middle"><?$itog_string['students_1smena'] = $itog_string['students_1smena'] + $cs_mas['students_1smena']; echo $cs_mas['students_1smena'];?></td>
                    <td class="text-center align-middle"><?$itog_string['students_2smena'] = $itog_string['students_2smena'] + $cs_mas['students_2smena']; echo $cs_mas['students_2smena'];?></td>
                <?}?>
                <?if($cs_mas['students_all_smena'] == 0){?>
                    <td class="text-center align-middle bg-secondary">нд</td>
                    <td class="text-center align-middle bg-secondary">нд</td>
                    <td class="text-center align-middle bg-secondary">нд</td>
                    <td class="text-center align-middle bg-secondary">нд</td>
                    <td class="text-center align-middle bg-secondary">нд</td>
                <?}?>
                <?if($school_break_count == 0){?>
                    <td class="text-center align-middle bg-secondary">нд</td>
                    <td class="text-center align-middle bg-secondary">нд</td>
                <?}else{?>
                    <td class="text-center align-middle"><?= $school_break_min; ?></td>
                    <td class="text-center align-middle"><?= $school_break_max; ?></td>
                <?}?>
                <?if(empty($school_break_min)){?>
                    <td class="text-center align-middle bg-secondary">нд</td>
                <?}elseif($school_break_min >= 20){?>
                    <td class="text-center align-middle">0</td>
                <?}else{?>
                    <td class="text-center align-middle"><?=1;$itog_string['peremena_niz'] = $itog_string['peremena_niz'] + 1;?></td>
                <?}?>
                <td class="text-center align-middle"><? if (empty($cs_mas['sahar'])){echo '-';}else{$itog_string['school_sahar'] = $itog_string['school_sahar'] + 1; echo 1;}?></td>
                <td class="text-center align-middle"><? if (empty($cs_mas['cialic'])){echo '-';}else{$itog_string['school_cialic'] = $itog_string['school_cialic'] + 1; echo 1;}?></td>
                <td class="text-center align-middle"><? if (empty($cs_mas['mukovis'])){echo '-';}else{$itog_string['school_mukovis'] = $itog_string['school_mukovis'] + 1; echo 1;}?></td>
                <td class="text-center align-middle"><? if (empty($cs_mas['fenilketon'])){echo '-';}else{$itog_string['school_fenilketon'] = $itog_string['school_fenilketon'] + 1; echo 1;}?></td>
                <td class="text-center align-middle"><? if (empty($cs_mas['ovz'])){echo '-';}else{$itog_string['school_ovz'] = $itog_string['school_ovz'] + 1; echo 1;}?></td>
                <td class="text-center align-middle"><? if (empty($cs_mas['allergy'])){echo '-';}else{$itog_string['school_allergy'] = $itog_string['school_allergy'] + 1; echo 1;}?></td>
                <td class="text-center align-middle"><? if (empty($cs_mas['ovz_allergy'])){echo '-';}else{$itog_string['school_ovz_allergy'] = $itog_string['school_ovz_allergy'] + 1; echo 1;}?></td>

                <td class="text-center align-middle"><? if (empty($cs_mas['sahar'])){echo '-';}else{$itog_string['student_sahar'] = $itog_string['student_sahar'] + $cs_mas['sahar']; echo $cs_mas['sahar'];}?></td>
                <td class="text-center align-middle"><? if (empty($cs_mas['cialic'])){echo '-';}else{$itog_string['student_cialic'] = $itog_string['student_cialic'] + $cs_mas['cialic']; echo $cs_mas['cialic'];}?></td>
                <td class="text-center align-middle"><? if (empty($cs_mas['mukovis'])){echo '-';}else{$itog_string['student_mukovis'] = $itog_string['student_mukovis'] + $cs_mas['mukovis']; echo $cs_mas['mukovis'];}?></td>
                <td class="text-center align-middle"><? if (empty($cs_mas['fenilketon'])){echo '-';}else{$itog_string['student_fenilketon'] = $itog_string['student_fenilketon'] + $cs_mas['fenilketon']; echo $cs_mas['fenilketon'];}?></td>
                <td class="text-center align-middle"><? if (empty($cs_mas['ovz'])){echo '-';}else{$itog_string['student_ovz'] = $itog_string['student_ovz'] + $cs_mas['ovz']; echo $cs_mas['ovz'];}?></td>
                <td class="text-center align-middle"><? if (empty($cs_mas['allergy'])){echo '-';}else{$itog_string['student_allergy'] = $itog_string['student_allergy'] + $cs_mas['allergy']; echo $cs_mas['allergy'];}?></td>
                <td class="text-center align-middle"><? if (empty($cs_mas['ovz_allergy'])){echo '-';}else{$itog_string['student_ovz_allergy'] = $itog_string['student_ovz_allergy'] + $cs_mas['ovz_allergy']; echo $cs_mas['ovz_allergy'];}?></td>

                <?if(empty($students_class_mas)){?>
                    <td class="text-center align-middle bg-secondary">нд</td>
                    <td class="text-center align-middle bg-secondary">нд</td>
                    <td class="text-center align-middle bg-secondary">нд</td>
                    <td class="text-center align-middle bg-secondary">нд</td>
                <?}else{?>
                    <td class="text-center align-middle"><?if ($cs_mas['otkaz'] > 0){ $itog_string['school_otkaz'] = $itog_string['school_otkaz'] + 1; echo 1;}else{ echo 0;$itog_string['school_otkaz_count'] = $itog_string['school_otkaz_count'] + 1;}?></td>
                    <td class="text-center align-middle"><?if ($cs_mas['otkaz'] > 0){ $itog_string['school_otkaz_procent'] = $itog_string['school_otkaz_procent'] + 100; echo 100;}else{ $itog_string['school_otkaz_procent'] = $itog_string['school_otkaz_procent'] + 0; echo 0;}?></td>
                    <td class="text-center align-middle"><?= $cs_mas['otkaz'];$itog_string['student_otkaz'] = $itog_string['student_otkaz'] + $cs_mas['otkaz'];?></td>
                    <td class="text-center align-middle"><?if ($cs_mas['students_ochno_i_zaochno'] == 0){ echo 'нд';}else{ echo round($cs_mas['otkaz']/$cs_mas['students_ochno_i_zaochno']*100, 1);}?></td>
                <?}?>



                <?if(empty($character_stolovaya->spot)){?>
                    <td class="text-center align-middle bg-secondary">нд</td>
                    <td class="text-center align-middle bg-secondary">нд</td>
                <?}else{?>
                    <td class="text-center align-middle"><?=$character_stolovaya->spot; $itog_string['kolichestvo_posad'] = $itog_string['kolichestvo_posad'] + $character_stolovaya->spot;?></td>
                    <td class="text-center align-middle"><?=round($character_stolovaya->square/$character_stolovaya->spot, 1);$itog_string['ploshad_posad'] = $itog_string['ploshad_posad'] + round($character_stolovaya->square/$character_stolovaya->spot, 1);$itog_string['ploshad_posad_count'] = $itog_string['ploshad_posad_count'] + 1;?></td>
                <?}?>
                <?if($cs_mas['max_pit'] == 0){?>
                    <td class="text-center align-middle bg-secondary">нд</td>
                <?}else{?>
                    <td class="text-center align-middle"><?=$cs_mas['max_pit'];if($cs_mas['max_pit'] > $itog_string['max_pit']){$itog_string['max_pit'] = $cs_mas['max_pit'];}?></td>
                <?}?>
                <?if($cs_mas['max_pit'] == 0 || empty($character_stolovaya->spot)){?>
                    <td class="text-center align-middle bg-secondary">нд</td>
                <?}else{?>
                    <td class="text-center align-middle"><?$fact = round(round($character_stolovaya->square/$character_stolovaya->spot, 1) * $character_stolovaya->spot / $cs_mas['max_pit'],2); echo $fact; $itog_string['fact_ploshad'] = $itog_string['fact_ploshad'] + $fact;$itog_string['fact_ploshad_count'] = $itog_string['fact_ploshad_count'] + 1;?></td>
                <?}?>

                <?if($cs_mas['max_pit'] == 0 || empty($character_stolovaya->spot)){?>
                    <td class="text-center align-middle bg-secondary">нд</td>
                <?}elseif(round(round($character_stolovaya->square/$character_stolovaya->spot, 1) * $character_stolovaya->spot / $cs_mas['max_pit'],2) < 0.7){?>
                    <td class="text-center align-middle"><?=1; $itog_string['deficit_ploshad'] = $itog_string['deficit_ploshad'] + 1;?></td>
                <?}else{?>
                    <td class="text-center align-middle">0</td>
                <?}?>

                <?if($cs_mas['max_pit'] == 0 || empty($character_stolovaya->spot)){?>
                    <td class="text-center align-middle bg-secondary">нд</td>
                <?}elseif($cs_mas['max_pit'] > $character_stolovaya->spot){?>
                    <td class="text-center align-middle"><?=1; $itog_string['deficit_previsaet'] = $itog_string['deficit_previsaet'] + 1;?></td>
                <?}else{?>
                    <td class="text-center align-middle">0</td>
                <?}?>

                <?if(empty($character_stolovaya->count_washing)){?>
                    <td class="text-center align-middle bg-secondary">нд</td>
                    <td class="text-center align-middle bg-secondary">нд</td>
                    <td class="text-center align-middle bg-secondary">нд</td>
                <?}else{?>
                    <td class="text-center align-middle"><?=$character_stolovaya->count_washing;?></td>
                    <td class="text-center align-middle <?if(round($cs_mas['max_pit']/$character_stolovaya->count_washing, 0)> 20){echo "bg-danger";}?>"><?=round($cs_mas['max_pit']/$character_stolovaya->count_washing, 0);?></td>
                    <td class="text-center align-middle <?if(round($cs_mas['max_pit']/$character_stolovaya->count_washing, 0)> 20){echo "bg-danger";}?>"><?if(round($cs_mas['max_pit']/$character_stolovaya->count_washing, 0) > 20){echo 1;$itog_string['deficit_umivalnik'] = $itog_string['deficit_umivalnik'] + 1;}else{ echo 0;}?></td>
                <?}?>

                <td class="text-center align-middle"><?if (empty($menu_mas['zavtrak']['itog'])){ echo 0; }else{ echo 1;$itog_string['vnes_zavtrak_itog'] = $itog_string['vnes_zavtrak_itog'] + 1;}?></td>
                <td class="text-center align-middle"><?if (empty($menu_mas['zavtrak'][3])){ echo 0; }else{ echo 1;$itog_string['vnes_zavtrak_3'] = $itog_string['vnes_zavtrak_3'] + 1;}?></td>
                <td class="text-center align-middle <?if($cs_mas['sahar'] > 0 && empty($menu_mas['zavtrak'][5]) && empty($menu_mas['obed'][5])){echo "bg-danger";}?>"><?if (empty($menu_mas['zavtrak'][5])){ echo 0; }else{ echo 1;$itog_string['vnes_zavtrak_5'] = $itog_string['vnes_zavtrak_5'] + 1;}?></td>
                <td class="text-center align-middle <?if($cs_mas['cialic'] > 0 && empty($menu_mas['zavtrak'][6]) && empty($menu_mas['obed'][6])){echo "bg-danger";}?>"><?if (empty($menu_mas['zavtrak'][6])){ echo 0; }else{ echo 1;$itog_string['vnes_zavtrak_6'] = $itog_string['vnes_zavtrak_6'] + 1;}?></td>
                <td class="text-center align-middle <?if($cs_mas['mukovis'] > 0 && empty($menu_mas['zavtrak'][8]) && empty($menu_mas['obed'][8])){echo "bg-danger";}?>"><?if (empty($menu_mas['zavtrak'][8])){ echo 0; }else{ echo 1;$itog_string['vnes_zavtrak_8'] = $itog_string['vnes_zavtrak_8'] + 1;}?></td>
                <td class="text-center align-middle <?if($cs_mas['fenilketon'] > 0 && empty($menu_mas['zavtrak'][7]) && empty($menu_mas['obed'][7])){echo "bg-danger";}?>"><?if (empty($menu_mas['zavtrak'][7])){ echo 0; }else{ echo 1;$itog_string['vnes_zavtrak_7'] = $itog_string['vnes_zavtrak_7'] + 1;}?></td>
                <td class="text-center align-middle <?if($cs_mas['ovz'] > 0 && empty($menu_mas['zavtrak'][4]) && empty($menu_mas['obed'][4])){echo "bg-danger";}?>"><?if (empty($menu_mas['zavtrak'][4])){ echo 0; }else{ echo 1;$itog_string['vnes_zavtrak_4'] = $itog_string['vnes_zavtrak_4'] + 1;}?></td>
                <td class="text-center align-middle <?if($cs_mas['allergy'] > 0 && empty($menu_mas['zavtrak'][10]) && empty($menu_mas['obed'][10])){echo "bg-danger";}?> "><?if (empty($menu_mas['zavtrak'][10])){ echo 0; }else{ echo 1;$itog_string['vnes_zavtrak_10'] = $itog_string['vnes_zavtrak_10'] + 1;}?></td>

                <td class="text-center align-middle"><?if (empty($menu_mas['zavtrak']['itog'])){ echo 0; }else{ echo $menu_mas['zavtrak']['itog'];$itog_string['vnes_zavtrak_itog_count'] = $itog_string['vnes_zavtrak_itog_count'] + $menu_mas['zavtrak']['itog'];}?></td>
                <td class="text-center align-middle"><?if (empty($menu_mas['zavtrak'][3])){ echo 0; }else{ echo $menu_mas['zavtrak'][3];$itog_string['vnes_zavtrak_3_count'] = $itog_string['vnes_zavtrak_3_count'] + $menu_mas['zavtrak'][3];}?></td>
                <td class="text-center align-middle <?if($cs_mas['sahar'] > 0 && empty($menu_mas['zavtrak'][5]) && empty($menu_mas['obed'][5])){echo "bg-danger";}?>"><?if (empty($menu_mas['zavtrak'][5])){ echo 0; }else{ echo $menu_mas['zavtrak'][5];$itog_string['vnes_zavtrak_5_count'] = $itog_string['vnes_zavtrak_5_count'] + $menu_mas['zavtrak'][5];}?></td>
                <td class="text-center align-middle <?if($cs_mas['cialic'] > 0 && empty($menu_mas['zavtrak'][6]) && empty($menu_mas['obed'][6])){echo "bg-danger";}?>"><?if (empty($menu_mas['zavtrak'][6])){ echo 0; }else{ echo $menu_mas['zavtrak'][6];$itog_string['vnes_zavtrak_6_count'] = $itog_string['vnes_zavtrak_6_count'] + $menu_mas['zavtrak'][6];}?></td>
                <td class="text-center align-middle <?if($cs_mas['mukovis'] > 0 && empty($menu_mas['zavtrak'][8]) && empty($menu_mas['obed'][8])){echo "bg-danger";}?>"><?if (empty($menu_mas['zavtrak'][8])){ echo 0; }else{ echo $menu_mas['zavtrak'][8];$itog_string['vnes_zavtrak_8_count'] = $itog_string['vnes_zavtrak_8_count'] + $menu_mas['zavtrak'][8];}?></td>
                <td class="text-center align-middle <?if($cs_mas['fenilketon'] > 0 && empty($menu_mas['zavtrak'][7]) && empty($menu_mas['obed'][7])){echo "bg-danger";}?>"><?if (empty($menu_mas['zavtrak'][7])){ echo 0; }else{ echo $menu_mas['zavtrak'][7];$itog_string['vnes_zavtrak_7_count'] = $itog_string['vnes_zavtrak_7_count'] + $menu_mas['zavtrak'][7];}?></td>
                <td class="text-center align-middle <?if($cs_mas['ovz'] > 0 && empty($menu_mas['zavtrak'][4]) && empty($menu_mas['obed'][4])){echo "bg-danger";}?>"><?if (empty($menu_mas['zavtrak'][4])){ echo 0; }else{ echo $menu_mas['zavtrak'][4];$itog_string['vnes_zavtrak_4_count'] = $itog_string['vnes_zavtrak_4_count'] + $menu_mas['zavtrak'][4];}?></td>
                <td class="text-center align-middle <?if($cs_mas['allergy'] > 0 && empty($menu_mas['zavtrak'][10]) && empty($menu_mas['obed'][10])){echo "bg-danger";}?>"><?if (empty($menu_mas['zavtrak'][10])){ echo 0; }else{ echo $menu_mas['zavtrak'][10];$itog_string['vnes_zavtrak_10_count'] = $itog_string['vnes_zavtrak_10_count'] + $menu_mas['zavtrak'][10];}?></td>



                <?if($menu > 0){
                    if(!empty($control_zavtrak)){
                        unset($control_zavtrak);
                    }
                    if(!empty($control_obed)){
                        unset($control_obed);
                    }

                    $menu_zavtrak = $model_menus_dishes->get_menu_information($organization->id, 1);
                    $menu_obed = $model_menus_dishes->get_menu_information($organization->id, 3);
                    if($menu_zavtrak['yield'] == 0 || $menu_zavtrak== "null"){?>
                        <td colspan="26" class="text-center align-middle text-danger"><!--Меню внесено некорректно, или находится на стадии внесения-->Меню не внесено</td>
                    <?}else{?>
                        <td class="text-center align-middle"><?= round($menu_zavtrak['yield'],1); $itog_string['menu_zavtrak_yield'] = $itog_string['menu_zavtrak_yield'] + round($menu_zavtrak['yield'],1);$itog_string['menu_zavtrak_count'] = $itog_string['menu_zavtrak_count'] + 1;?></td>
                        <td class="text-center align-middle <?if(round($menu_zavtrak['kkal'],1) < $normativ[1]['kkal']){echo "bg-danger";}?>"><?= round($menu_zavtrak['kkal'],1);$itog_string['menu_zavtrak_kkal'] = $itog_string['menu_zavtrak_kkal'] + round($menu_zavtrak['kkal'],1);?></td>
                        <td class="text-center align-middle <?if(round($menu_zavtrak['vitamin_c'],1) < $normativ[1]['vitamin_c']){echo "bg-danger";}?>"><?= round($menu_zavtrak['vitamin_c'],1);$itog_string['menu_zavtrak_vitamin_c'] = $itog_string['menu_zavtrak_vitamin_c'] + round($menu_zavtrak['vitamin_c'],1);?></td>
                        <td class="text-center align-middle <?if(round($menu_zavtrak['vitamin_b1'],1)< $normativ[1]['vitamin_b1']){echo "bg-danger";}?>"><?= round($menu_zavtrak['vitamin_b1'],1);$itog_string['menu_zavtrak_vitamin_b1'] = $itog_string['menu_zavtrak_vitamin_b1'] + round($menu_zavtrak['vitamin_b1'],1);?></td>
                        <td class="text-center align-middle <?if(round($menu_zavtrak['vitamin_b2'],1) < $normativ[1]['vitamin_b2']){echo "bg-danger";}?>"><?= round($menu_zavtrak['vitamin_b2'],1);$itog_string['menu_zavtrak_vitamin_b2'] = $itog_string['menu_zavtrak_vitamin_b2'] + round($menu_zavtrak['vitamin_b2'],1);?></td>
                        <td class="text-center align-middle <?if(round($menu_zavtrak['vitamin_a'],1) < $normativ[1]['vitamin_a']){echo "bg-danger";}?>"><?= round($menu_zavtrak['vitamin_a'],1);$itog_string['menu_zavtrak_vitamin_a'] = $itog_string['menu_zavtrak_vitamin_a'] + round($menu_zavtrak['vitamin_a'],1);?></td>
                        <td class="text-center align-middle <?if(round($menu_zavtrak['ca'],1) < $normativ[1]['ca']){echo "bg-danger";}?>"><?= round($menu_zavtrak['ca'],1);$itog_string['menu_zavtrak_ca'] = $itog_string['menu_zavtrak_ca'] + round($menu_zavtrak['ca'],1);?></td>
<!--                        <td class="text-center align-middle --><?//if(round($menu_zavtrak['p'],1) < $normativ[1]['p']){echo "bg-danger";}?><!--">--><?//= round($menu_zavtrak['p'],1);$itog_string['menu_zavtrak_p'] = $itog_string['menu_zavtrak_p'] + round($menu_zavtrak['p'],1);?><!--</td>-->
                        <td class="text-center align-middle <?if(round($menu_zavtrak['mg'],1) < $normativ[1]['mg']){echo "bg-danger";}?>"><?= round($menu_zavtrak['mg'],1);$itog_string['menu_zavtrak_mg'] = $itog_string['menu_zavtrak_mg'] + round($menu_zavtrak['mg'],1);?></td>
                        <td class="text-center align-middle <?if(round($menu_zavtrak['fe'],1) < $normativ[1]['fe']){echo "bg-danger";}?>"><?= round($menu_zavtrak['fe'],1);$itog_string['menu_zavtrak_fe'] = $itog_string['menu_zavtrak_fe'] + round($menu_zavtrak['fe'],1);?></td>
                        <td class="text-center align-middle <?if(round($menu_zavtrak['i'],1) < $normativ[1]['i']){echo "bg-danger";}?>"><?= round($menu_zavtrak['i'],1);$itog_string['menu_zavtrak_i'] = $itog_string['menu_zavtrak_i'] + round($menu_zavtrak['i'],1);?></td>
                        <td class="text-center align-middle <?if(round($menu_zavtrak['se'],1) < $normativ[1]['se']){echo "bg-danger";}?>"><?= round($menu_zavtrak['se'],1);$itog_string['menu_zavtrak_se'] = $itog_string['menu_zavtrak_se'] + round($menu_zavtrak['se'],1);?></td>
                        <td class="text-center align-middle <?if(round($menu_zavtrak['salt'],1) > $normativ[1]['salt']){echo "bg-danger";}?>"><?= round($menu_zavtrak['salt'],1);$itog_string['menu_zavtrak_salt'] = $itog_string['menu_zavtrak_salt'] + round($menu_zavtrak['salt'],1);?></td>
                        <td class="text-center align-middle <?if(round($menu_zavtrak['sahar'],1) > $normativ[1]['sahar']){echo "bg-danger";}?>"><?= round($menu_zavtrak['sahar'],1);$itog_string['menu_zavtrak_sahar'] = $itog_string['menu_zavtrak_sahar'] + round($menu_zavtrak['sahar'],1);?></td>

                        <td class="text-center align-middle <?if(round($menu_zavtrak['kolbasa'],0)>0){echo "bg-danger";}?>"><?if ( round($menu_zavtrak['kolbasa'],0)>0){echo 1;$itog_string['menu_zavtrak_kolbasa'] = $itog_string['menu_zavtrak_kolbasa']+1;}else{echo 0;}?></td>
                        <td class="text-center align-middle <?if(round($menu_zavtrak['konditer'],0)>0){echo "bg-danger";}?>"><?if ( round($menu_zavtrak['konditer'],0)>0){echo 1;$itog_string['menu_zavtrak_konditer'] = $itog_string['menu_zavtrak_konditer']+1;}else{echo 0;}?></td>
                        <td class="text-center align-middle"><?if ( round($menu_zavtrak['frukti'] ,0)>0){echo 1;$itog_string['menu_zavtrak_frukti'] = $itog_string['menu_zavtrak_frukti']+1;}else{echo 0;}?></td>
                        <td class="text-center align-middle"><?if ( round($menu_zavtrak['yagoda'] ,0)>0){echo 1;$itog_string['menu_zavtrak_yagoda'] = $itog_string['menu_zavtrak_yagoda']+1;}else{echo 0;}?></td>
                        <td class="text-center align-middle"><?if ( round($menu_zavtrak['med'] ,0) >0){echo 1;$itog_string['menu_zavtrak_med'] = $itog_string['menu_zavtrak_med']+1;}else{echo 0;}?></td>
                        <td class="text-center align-middle"><?if ( round($menu_zavtrak['ovoshi'] ,0)>0){echo 1;$itog_string['menu_zavtrak_ovoshi'] = $itog_string['menu_zavtrak_ovoshi']+1;}else{echo 0;}?></td>

                        <td class="text-center align-middle <?if(round($menu_zavtrak['kolbasa'],0)>0){echo "bg-danger";}?>"><?= round($menu_zavtrak['kolbasa'],0);$itog_string['menu_zavtrak_kolbasa_count'] = $itog_string['menu_zavtrak_kolbasa_count']+round($menu_zavtrak['kolbasa'],0);?></td>
                        <td class="text-center align-middle <?if(round($menu_zavtrak['konditer'],0)>0){echo "bg-danger";}?>"><?=round($menu_zavtrak['konditer'],0);$itog_string['menu_zavtrak_konditer_count'] = $itog_string['menu_zavtrak_konditer_count']+round($menu_zavtrak['konditer'],0);?></td>
                        <td class="text-center align-middle"><?= round($menu_zavtrak['frukti'] ,0);$itog_string['menu_zavtrak_frukti_count'] = $itog_string['menu_zavtrak_frukti_count']+round($menu_zavtrak['frukti'] ,0);?></td>
                        <td class="text-center align-middle"><?= round($menu_zavtrak['yagoda'] ,0);$itog_string['menu_zavtrak_yagoda_count'] = $itog_string['menu_zavtrak_yagoda_count']+round($menu_zavtrak['yagoda'] ,0);?></td>
                        <td class="text-center align-middle"><?= round($menu_zavtrak['med'] ,0);$itog_string['menu_zavtrak_med_count'] = $itog_string['menu_zavtrak_med_count']+round($menu_zavtrak['med'] ,0);?></td>
                        <td class="text-center align-middle"><?= round($menu_zavtrak['ovoshi'] ,0);$itog_string['menu_zavtrak_ovoshi_count'] = $itog_string['menu_zavtrak_ovoshi_count']+round($menu_zavtrak['ovoshi'] ,0); ?></td>
                        <td class="text-center align-middle <?if(round($menu_zavtrak['kkal'],1) < $normativ[1]['kkal']){echo "bg-danger";}?>"><?if(round($menu_zavtrak['kkal'],1) < $normativ[1]['kkal']){echo 1; $itog_string['deficit_blud_zavtrak'] = $itog_string['deficit_blud_zavtrak']+1;}else{ echo 0;}?></td>

                    <?}?>
                <?}else{?>
                    <td colspan="26" class="text-center align-middle text-danger">Меню не внесено</td>
                <?}?>

                <td class="text-center align-middle"><?if (empty($menu_mas['obed']['itog'])){ echo 0; }else{ echo 1;$itog_string['vnes_obed_itog'] = $itog_string['vnes_obed_itog'] + 1;}?></td>
                <td class="text-center align-middle"><?if (empty($menu_mas['obed'][3])){ echo 0; }else{ echo 1;$itog_string['vnes_obed_3'] = $itog_string['vnes_obed_3'] + 1;}?></td>
                <td class="text-center align-middle <?if($cs_mas['sahar'] > 0 && empty($menu_mas['zavtrak'][5]) && empty($menu_mas['obed'][5])){echo "bg-danger";}?>"><?if (empty($menu_mas['obed'][5])){ echo 0; }else{ echo 1;$itog_string['vnes_obed_5'] = $itog_string['vnes_obed_5'] + 1;}?></td>
                <td class="text-center align-middle <?if($cs_mas['cialic'] > 0 && empty($menu_mas['zavtrak'][6]) && empty($menu_mas['obed'][6])){echo "bg-danger";}?>"><?if (empty($menu_mas['obed'][6])){ echo 0; }else{ echo 1;$itog_string['vnes_obed_6'] = $itog_string['vnes_obed_6'] + 1;}?></td>
                <td class="text-center align-middle <?if($cs_mas['mukovis'] > 0 && empty($menu_mas['zavtrak'][8]) && empty($menu_mas['obed'][8])){echo "bg-danger";}?>"><?if (empty($menu_mas['obed'][8])){ echo 0; }else{ echo 1;$itog_string['vnes_obed_8'] = $itog_string['vnes_obed_8'] + 1;}?></td>
                <td class="text-center align-middle <?if($cs_mas['fenilketon'] > 0 && empty($menu_mas['zavtrak'][7]) && empty($menu_mas['obed'][7])){echo "bg-danger";}?>"><?if (empty($menu_mas['obed'][7])){ echo 0; }else{ echo 1;$itog_string['vnes_obed_7'] = $itog_string['vnes_obed_7'] + 1;}?></td>
                <td class="text-center align-middle <?if($cs_mas['ovz'] > 0 && empty($menu_mas['zavtrak'][4]) && empty($menu_mas['obed'][4])){echo "bg-danger";}?>"><?if (empty($menu_mas['obed'][4])){ echo 0; }else{ echo 1;$itog_string['vnes_obed_4'] = $itog_string['vnes_obed_4'] + 1;}?></td>
                <td class="text-center align-middle <?if($cs_mas['allergy'] > 0 && empty($menu_mas['zavtrak'][10]) && empty($menu_mas['obed'][10])){echo "bg-danger";}?>"><?if (empty($menu_mas['obed'][10])){ echo 0; }else{ echo 1;$itog_string['vnes_obed_10'] = $itog_string['vnes_obed_10'] + 1;}?></td>

                <td class="text-center align-middle"><?if (empty($menu_mas['obed']['itog'])){ echo 0; }else{ echo $menu_mas['obed']['itog'];$itog_string['vnes_obed_itog_count'] = $itog_string['vnes_obed_itog_count'] + $menu_mas['obed']['itog'];}?></td>
                <td class="text-center align-middle"><?if (empty($menu_mas['obed'][3])){ echo 0; }else{ echo $menu_mas['obed'][3];$itog_string['vnes_obed_3_count'] = $itog_string['vnes_obed_3_count'] + $menu_mas['obed'][3];}?></td>
                <td class="text-center align-middle <?if($cs_mas['sahar'] > 0 && empty($menu_mas['zavtrak'][5]) && empty($menu_mas['obed'][5])){echo "bg-danger";}?>"><?if (empty($menu_mas['obed'][5])){ echo 0; }else{ echo $menu_mas['obed'][5];$itog_string['vnes_obed_5_count'] = $itog_string['vnes_obed_5_count'] + $menu_mas['obed'][5];}?></td>
                <td class="text-center align-middle <?if($cs_mas['cialic'] > 0 && empty($menu_mas['zavtrak'][6]) && empty($menu_mas['obed'][6])){echo "bg-danger";}?>"><?if (empty($menu_mas['obed'][6])){ echo 0; }else{ echo $menu_mas['obed'][6];$itog_string['vnes_obed_6_count'] = $itog_string['vnes_obed_6_count'] + $menu_mas['obed'][6];}?></td>
                <td class="text-center align-middle <?if($cs_mas['mukovis'] > 0 && empty($menu_mas['zavtrak'][8]) && empty($menu_mas['obed'][8])){echo "bg-danger";}?>"><?if (empty($menu_mas['obed'][8])){ echo 0; }else{ echo $menu_mas['obed'][8];$itog_string['vnes_obed_8_count'] = $itog_string['vnes_obed_8_count'] + $menu_mas['obed'][8];}?></td>
                <td class="text-center align-middle <?if($cs_mas['fenilketon'] > 0 && empty($menu_mas['zavtrak'][7]) && empty($menu_mas['obed'][7])){echo "bg-danger";}?>"><?if (empty($menu_mas['obed'][7])){ echo 0; }else{ echo $menu_mas['obed'][7];$itog_string['vnes_obed_7_count'] = $itog_string['vnes_obed_7_count'] + $menu_mas['obed'][7];}?></td>
                <td class="text-center align-middle <?if($cs_mas['ovz'] > 0 && empty($menu_mas['zavtrak'][4]) && empty($menu_mas['obed'][4])){echo "bg-danger";}?>"><?if (empty($menu_mas['obed'][4])){ echo 0; }else{ echo $menu_mas['obed'][4];$itog_string['vnes_obed_4_count'] = $itog_string['vnes_obed_4_count'] + $menu_mas['obed'][4];}?></td>
                <td class="text-center align-middle <?if($cs_mas['allergy'] > 0 && empty($menu_mas['zavtrak'][10]) && empty($menu_mas['obed'][10])){echo "bg-danger";}?> "><?if (empty($menu_mas['obed'][10])){ echo 0; }else{ echo $menu_mas['obed'][10];$itog_string['vnes_obed_10_count'] = $itog_string['vnes_obed_10_count'] + $menu_mas['obed'][10];}?></td>
                <?if($menu > 0){
                    if($menu_obed['yield'] == 0 || $menu_obed== "null"){?>
                        <td colspan="26" class="text-center align-middle text-danger"><!--Меню внесено некорректно, или находится на стадии внесения-->Меню не внесено</td>
                    <?}else{?>
                        <td class="text-center align-middle"><?= round($menu_obed['yield'],1); $itog_string['menu_obed_yield'] = $itog_string['menu_obed_yield'] + round($menu_obed['yield'],1);$itog_string['menu_obed_count'] = $itog_string['menu_obed_count'] + 1;?></td>
                        <td class="text-center align-middle <?if(round($menu_obed['kkal'],1) < $normativ[3]['kkal']){echo "bg-danger";}?>"><?= round($menu_obed['kkal'],1);$itog_string['menu_obed_kkal'] = $itog_string['menu_obed_kkal'] + round($menu_obed['kkal'],1);?></td>
                        <td class="text-center align-middle <?if(round($menu_obed['vitamin_c'],1) < $normativ[3]['vitamin_c']){echo "bg-danger";}?>"><?= round($menu_obed['vitamin_c'],1);$itog_string['menu_obed_vitamin_c'] = $itog_string['menu_obed_vitamin_c'] + round($menu_obed['vitamin_c'],1);?></td>
                        <td class="text-center align-middle <?if(round($menu_obed['vitamin_b1'],1)< $normativ[3]['vitamin_b1']){echo "bg-danger";}?>"><?= round($menu_obed['vitamin_b1'],1);$itog_string['menu_obed_vitamin_b1'] = $itog_string['menu_obed_vitamin_b1'] + round($menu_obed['vitamin_b1'],1);?></td>
                        <td class="text-center align-middle <?if(round($menu_obed['vitamin_b2'],1) < $normativ[3]['vitamin_b2']){echo "bg-danger";}?>"><?= round($menu_obed['vitamin_b2'],1);$itog_string['menu_obed_vitamin_b2'] = $itog_string['menu_obed_vitamin_b2'] + round($menu_obed['vitamin_b2'],1);?></td>
                        <td class="text-center align-middle <?if(round($menu_obed['vitamin_a'],1) < $normativ[3]['vitamin_a']){echo "bg-danger";}?>"><?= round($menu_obed['vitamin_a'],1);$itog_string['menu_obed_vitamin_a'] = $itog_string['menu_obed_vitamin_a'] + round($menu_obed['vitamin_a'],1);?></td>
                        <td class="text-center align-middle <?if(round($menu_obed['ca'],1) < $normativ[3]['ca']){echo "bg-danger";}?>"><?= round($menu_obed['ca'],1);$itog_string['menu_obed_ca'] = $itog_string['menu_obed_ca'] + round($menu_obed['ca'],1);?></td>
<!--                        <td class="text-center align-middle --><?//if(round($menu_obed['p'],1) < $normativ[3]['p']){echo "bg-danger";}?><!--">--><?//= round($menu_obed['p'],1);$itog_string['menu_obed_p'] = $itog_string['menu_obed_p'] + round($menu_obed['p'],1);?><!--</td>-->
                        <td class="text-center align-middle <?if(round($menu_obed['mg'],1) < $normativ[3]['mg']){echo "bg-danger";}?>"><?= round($menu_obed['mg'],1);$itog_string['menu_obed_mg'] = $itog_string['menu_obed_mg'] + round($menu_obed['mg'],1);?></td>
                        <td class="text-center align-middle <?if(round($menu_obed['fe'],1) < $normativ[3]['fe']){echo "bg-danger";}?>"><?= round($menu_obed['fe'],1);$itog_string['menu_obed_fe'] = $itog_string['menu_obed_fe'] + round($menu_obed['fe'],1);?></td>
                        <td class="text-center align-middle <?if(round($menu_obed['i'],1) < $normativ[3]['i']){echo "bg-danger";}?>"><?= round($menu_obed['i'],1);$itog_string['menu_obed_i'] = $itog_string['menu_obed_i'] + round($menu_obed['i'],1);?></td>
                        <td class="text-center align-middle <?if(round($menu_obed['se'],1) < $normativ[3]['se']){echo "bg-danger";}?>"><?= round($menu_obed['se'],1);$itog_string['menu_obed_se'] = $itog_string['menu_obed_se'] + round($menu_obed['se'],1);?></td>
                        <td class="text-center align-middle <?if(round($menu_obed['salt'],1) > $normativ[3]['salt']){echo "bg-danger";}?>"><?= round($menu_obed['salt'],1);$itog_string['menu_obed_salt'] = $itog_string['menu_obed_salt'] + round($menu_obed['salt'],1);?></td>
                        <td class="text-center align-middle <?if(round($menu_obed['sahar'],1) > $normativ[3]['sahar']){echo "bg-danger";}?>"><?= round($menu_obed['sahar'],1);$itog_string['menu_obed_sahar'] = $itog_string['menu_obed_sahar'] + round($menu_obed['sahar'],1);?></td>



                        <td class="text-center align-middle <?if(round($menu_obed['kolbasa'],0)>0){echo "bg-danger";}?>"><?if ( round($menu_obed['kolbasa'],0)>0){echo 1;$itog_string['menu_obed_kolbasa'] = $itog_string['menu_obed_kolbasa']+1;}else{echo 0;}?></td>
                        <td class="text-center align-middle <?if(round($menu_obed['konditer'],0)>0){echo "bg-danger";}?>"><?if ( round($menu_obed['konditer'],0)>0){echo 1;$itog_string['menu_obed_konditer'] = $itog_string['menu_obed_konditer']+1;}else{echo 0;}?></td>
                        <td class="text-center align-middle"><?if ( round($menu_obed['frukti'] ,0)>0){echo 1;$itog_string['menu_obed_frukti'] = $itog_string['menu_obed_frukti']+1;}else{echo 0;}?></td>
                        <td class="text-center align-middle"><?if ( round($menu_obed['yagoda'] ,0)>0){echo 1;$itog_string['menu_obed_yagoda'] = $itog_string['menu_obed_yagoda']+1;}else{echo 0;}?></td>
                        <td class="text-center align-middle"><?if ( round($menu_obed['med'] ,0) >0){echo 1;$itog_string['menu_obed_med'] = $itog_string['menu_obed_med']+1;}else{echo 0;}?></td>
                        <td class="text-center align-middle"><?if ( round($menu_obed['ovoshi'] ,0)>0){echo 1;$itog_string['menu_obed_ovoshi'] = $itog_string['menu_obed_ovoshi']+1;}else{echo 0;}?></td>

                        <td class="text-center align-middle <?if(round($menu_obed['kolbasa'],0)>0){echo "bg-danger";}?>"><?= round($menu_obed['kolbasa'],0);$itog_string['menu_obed_kolbasa_count'] = $itog_string['menu_obed_kolbasa_count']+round($menu_obed['kolbasa'],0);?></td>
                        <td class="text-center align-middle <?if(round($menu_obed['konditer'],0)>0){echo "bg-danger";}?>"><?=round($menu_obed['konditer'],0);$itog_string['menu_obed_konditer_count'] = $itog_string['menu_obed_konditer_count']+round($menu_obed['konditer'],0);?></td>
                        <td class="text-center align-middle"><?= round($menu_obed['frukti'] ,0);$itog_string['menu_obed_frukti_count'] = $itog_string['menu_obed_frukti_count']+round($menu_obed['frukti'] ,0);?></td>
                        <td class="text-center align-middle"><?= round($menu_obed['yagoda'] ,0);$itog_string['menu_obed_yagoda_count'] = $itog_string['menu_obed_yagoda_count']+round($menu_obed['yagoda'] ,0);?></td>
                        <td class="text-center align-middle"><?= round($menu_obed['med'] ,0);$itog_string['menu_obed_med_count'] = $itog_string['menu_obed_med_count']+round($menu_obed['med'] ,0);?></td>
                        <td class="text-center align-middle"><?= round($menu_obed['ovoshi'] ,0);$itog_string['menu_obed_ovoshi_count'] = $itog_string['menu_obed_ovoshi_count']+round($menu_obed['ovoshi'] ,0); ?></td>
                        <td class="text-center align-middle <?if(round($menu_obed['kkal'],1) < $normativ[3]['kkal']){echo "bg-danger";}?>"><?if(round($menu_obed['kkal'],1) < $normativ[3]){echo 1; $itog_string['deficit_blud_obed'] = $itog_string['deficit_blud_obed']+1;}else{ echo 0;}?></td>
                    <?}?>
                <?}?>
                <?if($menu == 0){?>
                    <td colspan="26" class="text-center align-middle text-danger">Меню не внесено</td>
                <?}?>


                <?if($cs_mas['students_ochno_i_zaochno'] == 0){?>
                    <td class="text-center align-middle bg-secondary">нд</td>
                    <td class="text-center align-middle bg-secondary">нд</td>
                    <td class="text-center align-middle bg-secondary">нд</td>
                    <td class="text-center align-middle bg-secondary">нд</td>
                    <td class="text-center align-middle bg-secondary">нд</td>
                    <td class="text-center align-middle bg-secondary">нд</td>
                    <td class="text-center align-middle bg-secondary">нд</td>
                    <td class="text-center align-middle bg-secondary">нд</td>
                    <td class="text-center align-middle bg-secondary">нд</td>
                    <td class="text-center align-middle bg-secondary">нд</td>
                    <td class="text-center align-middle bg-secondary">нд</td>
                    <td class="text-center align-middle bg-secondary">нд</td>
                    <td class="text-center align-middle bg-secondary">нд</td>
                    <td class="text-center align-middle bg-secondary">нд</td>
                    <td class="text-center align-middle bg-secondary">нд</td>
                    <td class="text-center align-middle bg-secondary">нд</td>
                    <td class="text-center align-middle bg-secondary">нд</td>
                    <td class="text-center align-middle bg-secondary">нд</td>

                    <td class="text-center align-middle bg-secondary">нд</td>
                    <td class="text-center align-middle bg-secondary">нд</td>
                    <td class="text-center align-middle bg-secondary">нд</td>

                    <td class="text-center align-middle bg-secondary">нд</td>
                    <td class="text-center align-middle bg-secondary">нд</td>
                    <td class="text-center align-middle bg-secondary">нд</td>
                    <td class="text-center align-middle bg-secondary">нд</td>
                    <td class="text-center align-middle bg-secondary">нд</td>
                    <td class="text-center align-middle bg-secondary">нд</td>
                    <td class="text-center align-middle bg-secondary">нд</td>
                    <td class="text-center align-middle bg-secondary">нд</td>
                    <td class="text-center align-middle bg-secondary">нд</td>
                    <td class="text-center align-middle bg-secondary">нд</td>
                    <td class="text-center align-middle bg-secondary">нд</td>
                    <td class="text-center align-middle bg-secondary">нд</td>
                    <td class="text-center align-middle bg-secondary">нд</td>
                    <td class="text-center align-middle bg-secondary">нд</td>
                    <td class="text-center align-middle bg-secondary">нд</td>
                    <td class="text-center align-middle bg-secondary">нд</td>
                    <td class="text-center align-middle bg-secondary">нд</td>
                <?}else{?>
                    <td class="text-center align-middle"><?=$cs_mas['ohvat_vsego'];$itog_string['ohvat_vsego'] = $itog_string['ohvat_vsego'] + $cs_mas['ohvat_vsego'];$itog_string['ohvat_vsego_count']++;?></td>
                    <td class="text-center align-middle"><?=$cs_mas['ohvat_%_vsego'];$itog_string['ohvat_%_vsego'] = $itog_string['ohvat_%_vsego']+$cs_mas['ohvat_%_vsego'];?></td>

                    <td class="text-center align-middle"><?=$cs_mas['ohvat_zavtrak'];$itog_string['ohvat_zavtrak'] = $itog_string['ohvat_zavtrak']+$cs_mas['ohvat_zavtrak'];?></td>
                    <td class="text-center align-middle"><?=$cs_mas['ohvat_%_zavtrak'];?></td>

                    <td class="text-center align-middle"><?=$cs_mas['ohvat_obed'];$itog_string['ohvat_obed'] = $itog_string['ohvat_obed']+$cs_mas['ohvat_obed'];?></td>
                    <td class="text-center align-middle"><?=$cs_mas['ohvat_%_obed'];?></td>

                    <td class="text-center align-middle"><?=$cs_mas['ohvat_zavtrak_obed'];$itog_string['ohvat_zavtrak_obed'] = $itog_string['ohvat_zavtrak_obed']+$cs_mas['ohvat_zavtrak_obed'];?></td>
                    <td class="text-center align-middle"><?=$cs_mas['ohvat_%_zavtrak_obed'];?></td>

                    <!--треб/пит-->
                    <td class="text-center align-middle"><?=$cs_mas['ohvat_sahar_school'];$itog_string['ohvat_sahar_school'] = $itog_string['ohvat_sahar_school'] + $cs_mas['ohvat_sahar_school'];?></td>
                    <td class="text-center align-middle"><?=$cs_mas['ohvat_sahar_student'];$itog_string['ohvat_sahar_student'] = $itog_string['ohvat_sahar_student'] + $cs_mas['ohvat_sahar_student'];?></td>
                    <td class="text-center align-middle"><?if (empty($menu_mas['zavtrak'][5]) && empty($menu_mas['obed'][5])){ echo 0; }else{ echo 1; $itog_string['shcolnik_sahar_menu'] = $itog_string['shcolnik_sahar_menu']+1;}?></td>
                    <td class="text-center align-middle"><?=$cs_mas['ohvat_sahar_pit'];$itog_string['ohvat_sahar_pit'] = $itog_string['ohvat_sahar_pit'] + $cs_mas['ohvat_sahar_pit'];?></td>
                    <td class="text-center align-middle"><?=$cs_mas['ohvat_sahar_nepit'];$itog_string['ohvat_sahar_nepit'] = $itog_string['ohvat_sahar_nepit'] + $cs_mas['ohvat_sahar_nepit'];?></td>

                    <td class="text-center align-middle"><?=$cs_mas['ohvat_cialic_school'];$itog_string['ohvat_cialic_school'] = $itog_string['ohvat_cialic_school'] + $cs_mas['ohvat_cialic_school'];?></td>
                    <td class="text-center align-middle"><?=$cs_mas['ohvat_cialic_student'];$itog_string['ohvat_cialic_student'] = $itog_string['ohvat_cialic_student'] + $cs_mas['ohvat_cialic_student'];?></td>
                    <td class="text-center align-middle"><?if (empty($menu_mas['zavtrak'][6]) && empty($menu_mas['obed'][6])){ echo 0; }else{ echo 1; $itog_string['shcolnik_cialic_menu'] = $itog_string['shcolnik_cialic_menu']+1;}?></td>
                    <td class="text-center align-middle"><?=$cs_mas['ohvat_cialic_pit'];$itog_string['ohvat_cialic_pit'] = $itog_string['ohvat_cialic_pit'] + $cs_mas['ohvat_cialic_pit'];?></td>
                    <td class="text-center align-middle"><?=$cs_mas['ohvat_cialic_nepit'];$itog_string['ohvat_cialic_nepit'] = $itog_string['ohvat_cialic_nepit'] + $cs_mas['ohvat_cialic_nepit'];?></td>


                    <td class="text-center align-middle"><?=$cs_mas['ohvat_mukovis_school'];$itog_string['ohvat_mukovis_school'] = $itog_string['ohvat_mukovis_school'] + $cs_mas['ohvat_mukovis_school'];?></td>
                    <td class="text-center align-middle"><?=$cs_mas['ohvat_mukovis_student'];$itog_string['ohvat_mukovis_student'] = $itog_string['ohvat_mukovis_student'] + $cs_mas['ohvat_mukovis_student'];?></td>
                    <td class="text-center align-middle"><?if (empty($menu_mas['zavtrak'][8]) && empty($menu_mas['obed'][8])){ echo 0; }else{ echo 1; $itog_string['shcolnik_mukovis_menu'] = $itog_string['shcolnik_mukovis_menu']+1;}?></td>
                    <td class="text-center align-middle"><?=$cs_mas['ohvat_mukovis_pit'];$itog_string['ohvat_mukovis_pit'] = $itog_string['ohvat_mukovis_pit']+$cs_mas['ohvat_mukovis_pit'];?></td>
                    <td class="text-center align-middle"><?=$cs_mas['ohvat_mukovis_nepit'];$itog_string['ohvat_mukovis_nepit'] = $itog_string['ohvat_mukovis_nepit'] + $cs_mas['ohvat_mukovis_nepit'];?></td>

                    <td class="text-center align-middle"><?=$cs_mas['ohvat_fenilketon_school'];$itog_string['ohvat_fenilketon_school'] = $itog_string['ohvat_fenilketon_school'] + $cs_mas['ohvat_fenilketon_school'];?></td>
                    <td class="text-center align-middle"><?=$cs_mas['ohvat_fenilketon_student'];$itog_string['ohvat_fenilketon_student'] = $itog_string['ohvat_fenilketon_student'] + $cs_mas['ohvat_fenilketon_student'];?></td>
                    <td class="text-center align-middle"><?if (empty($menu_mas['zavtrak'][7]) && empty($menu_mas['obed'][7])){ echo 0; }else{ echo 1; $itog_string['shcolnik_fenilketon_menu'] = $itog_string['shcolnik_fenilketon_menu']+1;}?></td>
                    <td class="text-center align-middle"><?=$cs_mas['ohvat_fenilketon_pit'];$itog_string['ohvat_fenilketon_pit'] = $itog_string['ohvat_fenilketon_pit']+$cs_mas['ohvat_fenilketon_pit'];?></td>
                    <td class="text-center align-middle"><?=$cs_mas['ohvat_fenilketon_nepit'];$itog_string['ohvat_fenilketon_nepit'] = $itog_string['ohvat_fenilketon_nepit'] + $cs_mas['ohvat_fenilketon_nepit'];?></td>

                    <td class="text-center align-middle"><?=$cs_mas['ohvat_ovz_school'];$itog_string['ohvat_ovz_school'] = $itog_string['ohvat_ovz_school'] + $cs_mas['ohvat_ovz_school'];?></td>
                    <td class="text-center align-middle"><?=$cs_mas['ohvat_ovz_student'];$itog_string['ohvat_ovz_student'] = $itog_string['ohvat_ovz_student'] + $cs_mas['ohvat_ovz_student'];?></td>
                    <td class="text-center align-middle"><?if (empty($menu_mas['zavtrak'][4]) && empty($menu_mas['obed'][4])){ echo 0; }else{ echo 1; $itog_string['shcolnik_ovz_menu'] = $itog_string['shcolnik_ovz_menu']+1;}?></td>
                    <td class="text-center align-middle"><?=$cs_mas['ohvat_ovz_pit'];$itog_string['ohvat_ovz_pit'] = $itog_string['ohvat_ovz_pit']+$cs_mas['ohvat_ovz_pit'];?></td>
                    <td class="text-center align-middle"><?=$cs_mas['ohvat_ovz_nepit'];$itog_string['ohvat_ovz_nepit'] = $itog_string['ohvat_ovz_nepit'] + $cs_mas['ohvat_ovz_nepit'];?></td>

                    <td class="text-center align-middle"><?=$cs_mas['ohvat_allergy_school'];$itog_string['ohvat_allergy_school'] = $itog_string['ohvat_allergy_school'] + $cs_mas['ohvat_allergy_school'];?></td>
                    <td class="text-center align-middle"><?=$cs_mas['ohvat_allergy_student'];$itog_string['ohvat_allergy_student'] = $itog_string['ohvat_allergy_student'] + $cs_mas['ohvat_allergy_student'];?></td>
                    <td class="text-center align-middle"><?if (empty($menu_mas['zavtrak'][10]) && empty($menu_mas['obed'][10])){ echo 0; }else{ echo 1; $itog_string['shcolnik_allergy_menu'] = $itog_string['shcolnik_allergy_menu']+1;}?></td>
                    <td class="text-center align-middle"><?=$cs_mas['ohvat_allergy_pit'];$itog_string['ohvat_allergy_pit'] = $itog_string['ohvat_allergy_pit']+$cs_mas['ohvat_allergy_pit'];?></td>
                    <td class="text-center align-middle"><?=$cs_mas['ohvat_allergy_nepit'];$itog_string['ohvat_allergy_nepit'] = $itog_string['ohvat_allergy_nepit'] + $cs_mas['ohvat_allergy_nepit'];?></td>

                <?}?>
                <?

                $control_zavtrak = $model_menus_dishes->get_control_information($organization->id, 1);
                $control_obed = $model_menus_dishes->get_control_information($organization->id, 3);
                $control_inoe = $model_menus_dishes->get_control_information($organization->id, 'inoe');
                //print_r($control_zavtrak);
                ?>
                <?if($control_zavtrak == 'null' || empty($control_zavtrak) || $control_zavtrak['vnutr'] == 0){?>
                    <td colspan="7" class="text-center align-middle <?if($control_zavtrak == 'null' || $control_obed == 'null' || $control_inoe == 'null'){ echo 'bg-danger';}?>">Контроль в завтрак не проводился</td>
                <?}else{
                    $itog_string['zavtrak_control_count'] ++;?>
                    <td class="text-center align-middle"><?=$control_zavtrak['vnutr']; $itog_string['zavtrak_vnutr'] = $itog_string['zavtrak_vnutr'] + $control_zavtrak['vnutr']; ?></td>
                    <td class="text-center align-middle"><?=round($control_zavtrak['min_ball'],1); if(empty($itog_string['zavtrak_min_ball'])){$itog_string['zavtrak_min_ball'] = round($control_zavtrak['min_ball'],1);}elseif ($itog_string['zavtrak_min_ball'] > round($control_zavtrak['min_ball'],1)){$itog_string['zavtrak_min_ball'] = round($control_zavtrak['min_ball'],1);}?></td>
                    <td class="text-center align-middle"><?=round($control_zavtrak['sred_ball'],1); $itog_string['zavtrak_sred_ball'] = $itog_string['zavtrak_sred_ball'] + round($control_zavtrak['sred_ball'],1);?></td>
                    <td class="text-center align-middle"><?=round($control_zavtrak['max_ball'],1); if(empty($itog_string['zavtrak_max_ball'])){$itog_string['zavtrak_max_ball'] = round($control_zavtrak['max_ball'],1);}elseif ($itog_string['zavtrak_max_ball'] < round($control_zavtrak['max_ball'],1)){$itog_string['zavtrak_max_ball'] = round($control_zavtrak['max_ball'],1);}?></td>
                    <td class="text-center align-middle"><?=round($control_zavtrak['min_procent'],1);if(empty($itog_string['zavtrak_min_procent'])){$itog_string['zavtrak_min_procent'] = round($control_zavtrak['min_procent'],1);}elseif ($itog_string['zavtrak_min_procent'] > round($control_zavtrak['min_procent'],1)){$itog_string['zavtrak_min_procent'] = round($control_zavtrak['min_procent'],1);}?></td>
                    <td class="text-center align-middle <?if(round($control_zavtrak['sred_procent'],1) >= 30){echo "bg-danger";}?>"><?=round($control_zavtrak['sred_procent'],1); $itog_string['zavtrak_sred_procent'] = $itog_string['zavtrak_sred_procent'] +round($control_zavtrak['sred_procent'],1);?></td>
                    <td class="text-center align-middle <?if(round($control_zavtrak['max_procent'],1) >= 30){echo "bg-danger";}?>"><?=round($control_zavtrak['max_procent'],1);if(empty($itog_string['zavtrak_max_procent'])){$itog_string['zavtrak_max_procent'] = round($control_zavtrak['max_procent'],1);}elseif ($itog_string['zavtrak_max_procent'] < round($control_zavtrak['max_procent'],1)){$itog_string['zavtrak_max_procent'] = round($control_zavtrak['max_procent'],1);}?></td>
                <?}?>

                <?if($control_obed == 'null' || empty($control_obed) || $control_obed['vnutr'] == 0){?>
                    <td colspan="7" class="text-center align-middle <?if($control_zavtrak == 'null' || $control_obed == 'null' || $control_inoe == 'null'){ echo 'bg-danger';}?>">Контроль в обед не проводился</td>
                <?}else{
                    $itog_string['obed_control_count'] ++;?>
                    <td class="text-center align-middle"><?=$control_obed['vnutr']; $itog_string['obed_vnutr'] = $itog_string['obed_vnutr'] + $control_obed['vnutr'];?></td>
                    <td class="text-center align-middle"><?=round($control_obed['min_ball'],1); if(empty($itog_string['obed_min_ball'])){$itog_string['obed_min_ball'] = round($control_obed['min_ball'],1);}elseif ($itog_string['obed_min_ball'] > round($control_obed['min_ball'],1)){$itog_string['obed_min_ball'] = round($control_obed['min_ball'],1);}?></td>
                    <td class="text-center align-middle"><?=round($control_obed['sred_ball'],1); $itog_string['obed_sred_ball'] = $itog_string['obed_sred_ball'] +round($control_obed['sred_ball'],1);?></td>
                    <td class="text-center align-middle"><?=round($control_obed['max_ball'],1); if(empty($itog_string['obed_max_ball'])){$itog_string['obed_max_ball'] = round($control_obed['max_ball'],1);}elseif ($itog_string['obed_max_ball'] < round($control_obed['min_ball'],1)){$itog_string['obed_max_ball'] = round($control_obed['max_ball'],1);}?></td>
                    <td class="text-center align-middle"><?=round($control_obed['min_procent'],1); if(empty($itog_string['obed_min_procent'])){$itog_string['obed_min_procent'] = round($control_obed['min_procent'],1);}elseif ($itog_string['obed_min_procent'] > round($control_obed['min_procent'],1)){$itog_string['obed_min_procent'] = round($control_obed['min_procent'],1);}?></td>
                    <td class="text-center align-middle <?if(round($control_obed['sred_procent'],1) >= 30){echo "bg-danger";}?>"><?=round($control_obed['sred_procent'],1); $itog_string['obed_sred_procent'] = $itog_string['obed_sred_procent'] +round($control_obed['sred_procent'],1);?></td>
                    <td class="text-center align-middle <?if(round($control_obed['max_procent'],1) >= 30){echo "bg-danger";}?>"><?=round($control_obed['max_procent'],1); if(empty($itog_string['obed_max_procent'])){$itog_string['obed_max_procent'] = round($control_obed['max_procent'],1);}elseif ($itog_string['obed_max_procent'] < round($control_obed['max_procent'],1)){$itog_string['obed_max_procent'] = round($control_obed['max_procent'],1);}?></td>
                <?}?>



                <?if($control_inoe == 'null' || empty($control_inoe) || $control_inoe['vnutr'] == 0){?>
                    <td colspan="7" class="text-center align-middle <?if($control_zavtrak == 'null' || $control_obed == 'null' || $control_inoe == 'null'){ echo 'bg-danger';}?>">Контроль не проводился</td>
                <?}else{
                    $itog_string['inoe_control_count'] ++;?>
                    <td class="text-center align-middle"><?=$control_inoe['vnutr']; $itog_string['inoe_vnutr'] = $itog_string['inoe_vnutr'] + $control_inoe['vnutr'];?></td>
                    <td class="text-center align-middle"><?=round($control_inoe['min_ball'],1); if(empty($itog_string['inoe_min_ball'])){$itog_string['inoe_min_ball'] = round($control_inoe['min_ball'],1);}elseif ($itog_string['inoe_min_ball'] > round($control_inoe['min_ball'],1)){$itog_string['inoe_min_ball'] = round($control_inoe['min_ball'],1);}?></td>
                    <td class="text-center align-middle"><?=round($control_inoe['sred_ball'],1); $itog_string['inoe_sred_ball'] = $itog_string['inoe_sred_ball'] +round($control_inoe['sred_ball'],1);?></td>
                    <td class="text-center align-middle"><?=round($control_inoe['max_ball'],1); if(empty($itog_string['inoe_max_ball'])){$itog_string['inoe_max_ball'] = round($control_inoe['max_ball'],1);}elseif ($itog_string['inoe_max_ball'] < round($control_inoe['min_ball'],1)){$itog_string['inoe_max_ball'] = round($control_inoe['max_ball'],1);}?></td>
                    <td class="text-center align-middle"><?=round($control_inoe['min_procent'],1); if(empty($itog_string['inoe_min_procent'])){$itog_string['inoe_min_procent'] = round($control_inoe['min_procent'],1);}elseif ($itog_string['inoe_min_procent'] > round($control_inoe['min_procent'],1)){$itog_string['inoe_min_procent'] = round($control_inoe['min_procent'],1);}?></td>
                    <td class="text-center align-middle <?if(round($control_inoe['sred_procent'],1) >= 30){echo "bg-danger";}?>"><?=round($control_inoe['sred_procent'],1); $itog_string['inoe_sred_procent'] = $itog_string['inoe_sred_procent'] +round($control_inoe['sred_procent'],1);?></td>
                    <td class="text-center align-middle <?if(round($control_inoe['max_procent'],1) >= 30){echo "bg-danger";}?>"><?=round($control_inoe['max_procent'],1); if(empty($itog_string['inoe_max_procent'])){$itog_string['inoe_max_procent'] = round($control_inoe['max_procent'],1);}elseif ($itog_string['inoe_max_procent'] < round($control_inoe['max_procent'],1)){$itog_string['inoe_max_procent'] = round($control_inoe['max_procent'],1);}?></td>
                <?}?>



            </tr>
        <?}?>
        <tr class="table-danger">
            <td class="" colspan="3">Итого <?=$mun;?><?if($post['city_id'] != 0){echo ' ('.\common\models\City::findOne($post['city_id'])->name.')';}?>:</td>
            <td class=""><?=$itog_string['school_vnes_info'];?></td>
            <td class="text-center align-middle"><?=$itog_string['students_1smena_count_sch'];?></td>
            <td class="text-center align-middle"><?=$itog_string['students_all_smena_count_sch'];?></td>
            <td class="text-center align-middle"><?=$itog_string['students_all_smena'];?></td>
            <td class="text-center align-middle"><?=$itog_string['students_1smena'];?></td>
            <td class="text-center align-middle"><?=$itog_string['students_2smena'];?></td>
            <td class="text-center align-middle">-</td>
            <td class="text-center align-middle">-</td>
            <td class="text-center align-middle"><?=$itog_string['peremena_niz']?></td></td>
            <td class="text-center align-middle"><?echo(empty($itog_string['school_sahar']))? 0:$itog_string['school_sahar'] ;?></td>
            <td class="text-center align-middle"><?echo(empty($itog_string['school_cialic']))? 0:$itog_string['school_cialic'] ;?></td>
            <td class="text-center align-middle"><?echo(empty($itog_string['school_mukovis']))? 0:$itog_string['school_mukovis'] ;?></td>
            <td class="text-center align-middle"><?echo(empty($itog_string['school_fenilketon']))? 0:$itog_string['school_fenilketon'] ;?></td>
            <td class="text-center align-middle"><?echo(empty($itog_string['school_ovz']))? 0:$itog_string['school_ovz'] ;?></td>
            <td class="text-center align-middle"><?echo(empty($itog_string['school_allergy']))? 0:$itog_string['school_allergy'] ;?></td>
            <td class="text-center align-middle"><?echo(empty($itog_string['school_ovz_allergy']))? 0:$itog_string['school_ovz_allergy'] ;?></td>

            <td class="text-center align-middle"><?echo(empty($itog_string['student_sahar']))? 0:$itog_string['student_sahar'] ;?></td>
            <td class="text-center align-middle"><?echo(empty($itog_string['student_cialic']))? 0:$itog_string['student_cialic'] ;?></td>
            <td class="text-center align-middle"><?echo(empty($itog_string['student_mukovis']))? 0:$itog_string['student_mukovis'] ;?></td>
            <td class="text-center align-middle"><?echo(empty($itog_string['student_fenilketon']))? 0:$itog_string['student_fenilketon'] ;?></td>
            <td class="text-center align-middle"><?echo(empty($itog_string['student_ovz']))? 0:$itog_string['student_ovz'] ;?></td>
            <td class="text-center align-middle"><?echo(empty($itog_string['student_allergy']))? 0:$itog_string['student_allergy'] ;?></td>
            <td class="text-center align-middle"><?echo(empty($itog_string['student_ovz_allergy']))? 0:$itog_string['student_ovz_allergy'] ;?></td>

            <td class="text-center align-middle"><?=$itog_string['school_otkaz']?></td>
            <td class="text-center align-middle"><?echo(empty($itog_string['school_otkaz_count']))? 'нд':round($itog_string['school_otkaz_procent']/$itog_string['school_otkaz_count'],1);?></td>
            <td class="text-center align-middle"><?=$itog_string['student_otkaz']?></td>
            <td class="text-center align-middle"><?($itog_string['students_all_smena']!= 0) ? round($itog_string['student_otkaz']/$itog_string['students_all_smena']*100,2): 0;?></td>
            <td class="text-center align-middle"><?=$itog_string['kolichestvo_posad']?></td>
            <td class="text-center align-middle"><?echo(empty($itog_string['ploshad_posad_count']))? 'нд': round($itog_string['ploshad_posad']/$itog_string['ploshad_posad_count'], 1)?></td>
            <td class="text-center align-middle"><?=$itog_string['max_pit']?></td>
            <td class="text-center align-middle"><?echo(empty($itog_string['fact_ploshad_count']))? 'нд': round($itog_string['fact_ploshad']/$itog_string['fact_ploshad_count'], 1)?></td>
            <td class="text-center align-middle"><?echo(empty($itog_string['deficit_ploshad']))? 0: $itog_string['deficit_ploshad']?></td>
            <td class="text-center align-middle"><?echo(empty($itog_string['deficit_previsaet']))? 0: $itog_string['deficit_previsaet']?></td>
            <td class="text-center align-middle">-</td>
            <td class="text-center align-middle">-</td>
            <td class="text-center align-middle"><?echo(empty($itog_string['deficit_umivalnik']))? 0: $itog_string['deficit_umivalnik']?></td>

            <td class="text-center align-middle"><?echo(empty($itog_string['vnes_zavtrak_itog']))? 0:$itog_string['vnes_zavtrak_itog']?></td>
            <td class="text-center align-middle"><?echo(empty($itog_string['vnes_zavtrak_3']))? 0:$itog_string['vnes_zavtrak_3']?></td>
            <td class="text-center align-middle"><?echo(empty($itog_string['vnes_zavtrak_5']))? 0:$itog_string['vnes_zavtrak_5']?></td>
            <td class="text-center align-middle"><?echo(empty($itog_string['vnes_zavtrak_6']))? 0:$itog_string['vnes_zavtrak_6']?></td>
            <td class="text-center align-middle"><?echo(empty($itog_string['vnes_zavtrak_8']))? 0:$itog_string['vnes_zavtrak_8']?></td>
            <td class="text-center align-middle"><?echo(empty($itog_string['vnes_zavtrak_7']))? 0:$itog_string['vnes_zavtrak_7']?></td>
            <td class="text-center align-middle"><?echo(empty($itog_string['vnes_zavtrak_4']))? 0:$itog_string['vnes_zavtrak_4']?></td>
            <td class="text-center align-middle"><?echo(empty($itog_string['vnes_zavtrak_10']))? 0:$itog_string['vnes_zavtrak_10']?></td>
            <td class="text-center align-middle"><?echo(empty($itog_string['vnes_zavtrak_itog_count']))? 0:$itog_string['vnes_zavtrak_itog_count']?></td>
            <td class="text-center align-middle"><?echo(empty($itog_string['vnes_zavtrak_3_count']))? 0:$itog_string['vnes_zavtrak_3_count']?></td>
            <td class="text-center align-middle"><?echo(empty($itog_string['vnes_zavtrak_5_count']))? 0:$itog_string['vnes_zavtrak_5_count']?></td>
            <td class="text-center align-middle"><?echo(empty($itog_string['vnes_zavtrak_6_count']))? 0:$itog_string['vnes_zavtrak_6_count']?></td>
            <td class="text-center align-middle"><?echo(empty($itog_string['vnes_zavtrak_8_count']))? 0:$itog_string['vnes_zavtrak_8_count']?></td>
            <td class="text-center align-middle"><?echo(empty($itog_string['vnes_zavtrak_7_count']))? 0:$itog_string['vnes_zavtrak_7_count']?></td>
            <td class="text-center align-middle"><?echo(empty($itog_string['vnes_zavtrak_4_count']))? 0:$itog_string['vnes_zavtrak_4_count']?></td>
            <td class="text-center align-middle"><?echo(empty($itog_string['vnes_zavtrak_10_count']))? 0:$itog_string['vnes_zavtrak_10_count']?></td>
            <?if($itog_string['menu_zavtrak_count'] == 0 || empty($itog_string['menu_zavtrak_count'])){?>
                <td class="text-center align-middle">нд</td>
                <td class="text-center align-middle">нд</td>
                <td class="text-center align-middle">нд</td>
                <td class="text-center align-middle">нд</td>
                <td class="text-center align-middle">нд</td>
                <td class="text-center align-middle">нд</td>
                <td class="text-center align-middle">нд</td>
                <td class="text-center align-middle">нд</td>
                <td class="text-center align-middle">нд</td>
                <td class="text-center align-middle">нд</td>
                <td class="text-center align-middle">нд</td>
                <td class="text-center align-middle">нд</td>
                <td class="text-center align-middle">нд</td>
<!--                <td class="text-center align-middle">нд</td>-->
            <?}else{?>
                <td class="text-center align-middle"><?=round($itog_string['menu_zavtrak_yield']/$itog_string['menu_zavtrak_count'],1)?></td>
                <td class="text-center align-middle"><?=round($itog_string['menu_zavtrak_kkal']/$itog_string['menu_zavtrak_count'],1)?></td>
                <td class="text-center align-middle"><?=round($itog_string['menu_zavtrak_vitamin_c']/$itog_string['menu_zavtrak_count'],1)?></td>
                <td class="text-center align-middle"><?=round($itog_string['menu_zavtrak_vitamin_b1']/$itog_string['menu_zavtrak_count'],1)?></td>
                <td class="text-center align-middle"><?=round($itog_string['menu_zavtrak_vitamin_b2']/$itog_string['menu_zavtrak_count'],1)?></td>
                <td class="text-center align-middle"><?=round($itog_string['menu_zavtrak_vitamin_a']/$itog_string['menu_zavtrak_count'],1)?></td>
                <td class="text-center align-middle"><?=round($itog_string['menu_zavtrak_ca']/$itog_string['menu_zavtrak_count'],1)?></td>
<!--                <td class="text-center align-middle">--><?//=round($itog_string['menu_zavtrak_p']/$itog_string['menu_zavtrak_count'],1)?><!--</td>-->
                <td class="text-center align-middle"><?=round($itog_string['menu_zavtrak_mg']/$itog_string['menu_zavtrak_count'],1)?></td>
                <td class="text-center align-middle"><?=round($itog_string['menu_zavtrak_fe']/$itog_string['menu_zavtrak_count'],1)?></td>
                <td class="text-center align-middle"><?=round($itog_string['menu_zavtrak_i']/$itog_string['menu_zavtrak_count'],1)?></td>
                <td class="text-center align-middle"><?=round($itog_string['menu_zavtrak_se']/$itog_string['menu_zavtrak_count'],1)?></td>
                <td class="text-center align-middle"><?=round($itog_string['menu_zavtrak_salt']/$itog_string['menu_zavtrak_count'],1)?></td>
                <td class="text-center align-middle"><?=round($itog_string['menu_zavtrak_sahar']/$itog_string['menu_zavtrak_count'],1)?></td>
            <?}?>
            <td class="text-center align-middle"><?echo(empty($itog_string['menu_zavtrak_kolbasa']))? 0:$itog_string['menu_zavtrak_kolbasa'];?></td>
            <td class="text-center align-middle"><?echo(empty($itog_string['menu_zavtrak_konditer']))? 0:$itog_string['menu_zavtrak_konditer'];?></td>
            <td class="text-center align-middle"><?echo(empty($itog_string['menu_zavtrak_frukti']))? 0:$itog_string['menu_zavtrak_frukti'];?></td>
            <td class="text-center align-middle"><?echo(empty($itog_string['menu_zavtrak_yagoda']))? 0:$itog_string['menu_zavtrak_yagoda'];?></td>
            <td class="text-center align-middle"><?echo(empty($itog_string['menu_zavtrak_med']))? 0:$itog_string['menu_zavtrak_med'];?></td>
            <td class="text-center align-middle"><?echo(empty($itog_string['menu_zavtrak_ovoshi']))? 0:$itog_string['menu_zavtrak_ovoshi'];?></td>

            <?if($itog_string['menu_zavtrak_count'] == 0 || empty($itog_string['menu_zavtrak_count'])){?>
                <td class="text-center align-middle">нд</td>
                <td class="text-center align-middle">нд</td>
                <td class="text-center align-middle">нд</td>
                <td class="text-center align-middle">нд</td>
                <td class="text-center align-middle">нд</td>
                <td class="text-center align-middle">нд</td>
            <?}else{?>
                <td class="text-center align-middle"><?=round($itog_string['menu_zavtrak_kolbasa_count']/$itog_string['menu_zavtrak_count'], 1)?></td>
                <td class="text-center align-middle"><?=round($itog_string['menu_zavtrak_konditer_count']/$itog_string['menu_zavtrak_count'], 1)?></td>
                <td class="text-center align-middle"><?=round($itog_string['menu_zavtrak_frukti_count']/$itog_string['menu_zavtrak_count'], 1)?></td>
                <td class="text-center align-middle"><?=round($itog_string['menu_zavtrak_yagoda_count']/$itog_string['menu_zavtrak_count'], 1)?></td>
                <td class="text-center align-middle"><?=round($itog_string['menu_zavtrak_med_count']/$itog_string['menu_zavtrak_count'], 1)?></td>
                <td class="text-center align-middle"><?=round($itog_string['menu_zavtrak_ovoshi_count']/$itog_string['menu_zavtrak_count'], 1)?></td>
            <?}?>

            <td class="text-center align-middle"><?=(empty($itog_string['deficit_blud_zavtrak']))? 0 : $itog_string['deficit_blud_zavtrak']?></td>
            <td class="text-center align-middle"><?echo(empty($itog_string['vnes_obed_itog']))? 0:$itog_string['vnes_obed_itog']?></td>
            <td class="text-center align-middle"><?echo(empty($itog_string['vnes_obed_3']))? 0:$itog_string['vnes_obed_3']?></td>
            <td class="text-center align-middle"><?echo(empty($itog_string['vnes_obed_5']))? 0:$itog_string['vnes_obed_5']?></td>
            <td class="text-center align-middle"><?echo(empty($itog_string['vnes_obed_6']))? 0:$itog_string['vnes_obed_6']?></td>
            <td class="text-center align-middle"><?echo(empty($itog_string['vnes_obed_8']))? 0:$itog_string['vnes_obed_8']?></td>
            <td class="text-center align-middle"><?echo(empty($itog_string['vnes_obed_7']))? 0:$itog_string['vnes_obed_7']?></td>
            <td class="text-center align-middle"><?echo(empty($itog_string['vnes_obed_4']))? 0:$itog_string['vnes_obed_4']?></td>
            <td class="text-center align-middle"><?echo(empty($itog_string['vnes_obed_10']))? 0:$itog_string['vnes_obed_10']?></td>
            <td class="text-center align-middle"><?echo(empty($itog_string['vnes_obed_itog_count']))? 0:$itog_string['vnes_obed_itog_count']?></td>
            <td class="text-center align-middle"><?echo(empty($itog_string['vnes_obed_3_count']))? 0:$itog_string['vnes_obed_3_count']?></td>
            <td class="text-center align-middle"><?echo(empty($itog_string['vnes_obed_5_count']))? 0:$itog_string['vnes_obed_5_count']?></td>
            <td class="text-center align-middle"><?echo(empty($itog_string['vnes_obed_6_count']))? 0:$itog_string['vnes_obed_6_count']?></td>
            <td class="text-center align-middle"><?echo(empty($itog_string['vnes_obed_8_count']))? 0:$itog_string['vnes_obed_8_count']?></td>
            <td class="text-center align-middle"><?echo(empty($itog_string['vnes_obed_7_count']))? 0:$itog_string['vnes_obed_7_count']?></td>
            <td class="text-center align-middle"><?echo(empty($itog_string['vnes_obed_4_count']))? 0:$itog_string['vnes_obed_4_count']?></td>
            <td class="text-center align-middle"><?echo(empty($itog_string['vnes_obed_10_count']))? 0:$itog_string['vnes_obed_10_count']?></td>
            <?if($itog_string['menu_obed_count'] == 0 || empty($itog_string['menu_obed_count'])){?>
                <td class="text-center align-middle">нд</td>
                <td class="text-center align-middle">нд</td>
                <td class="text-center align-middle">нд</td>
                <td class="text-center align-middle">нд</td>
                <td class="text-center align-middle">нд</td>
                <td class="text-center align-middle">нд</td>
                <td class="text-center align-middle">нд</td>
                <td class="text-center align-middle">нд</td>
                <td class="text-center align-middle">нд</td>
                <td class="text-center align-middle">нд</td>
                <td class="text-center align-middle">нд</td>
                <td class="text-center align-middle">нд</td>
                <td class="text-center align-middle">нд</td>
<!--                <td class="text-center align-middle">нд</td>-->
            <?}else{?>
                <td class="text-center align-middle"><?=round($itog_string['menu_obed_yield']/$itog_string['menu_obed_count'],1)?></td>
                <td class="text-center align-middle"><?=round($itog_string['menu_obed_kkal']/$itog_string['menu_obed_count'],1)?></td>
                <td class="text-center align-middle"><?=round($itog_string['menu_obed_vitamin_c']/$itog_string['menu_obed_count'],1)?></td>
                <td class="text-center align-middle"><?=round($itog_string['menu_obed_vitamin_b1']/$itog_string['menu_obed_count'],1)?></td>
                <td class="text-center align-middle"><?=round($itog_string['menu_obed_vitamin_b2']/$itog_string['menu_obed_count'],1)?></td>
                <td class="text-center align-middle"><?=round($itog_string['menu_obed_vitamin_a']/$itog_string['menu_obed_count'],1)?></td>
                <td class="text-center align-middle"><?=round($itog_string['menu_obed_ca']/$itog_string['menu_obed_count'],1)?></td>
<!--                <td class="text-center align-middle">--><?//=round($itog_string['menu_obed_p']/$itog_string['menu_obed_count'],1)?><!--</td>-->
                <td class="text-center align-middle"><?=round($itog_string['menu_obed_mg']/$itog_string['menu_obed_count'],1)?></td>
                <td class="text-center align-middle"><?=round($itog_string['menu_obed_fe']/$itog_string['menu_obed_count'],1)?></td>
                <td class="text-center align-middle"><?=round($itog_string['menu_obed_i']/$itog_string['menu_obed_count'],1)?></td>
                <td class="text-center align-middle"><?=round($itog_string['menu_obed_se']/$itog_string['menu_obed_count'],1)?></td>
                <td class="text-center align-middle"><?=round($itog_string['menu_obed_salt']/$itog_string['menu_obed_count'],1)?></td>
                <td class="text-center align-middle"><?=round($itog_string['menu_obed_sahar']/$itog_string['menu_obed_count'],1)?></td>
            <?}?>


            <td class="text-center align-middle"><?echo(empty($itog_string['menu_obed_kolbasa']))? 0:$itog_string['menu_obed_kolbasa'];?></td>
            <td class="text-center align-middle"><?echo(empty($itog_string['menu_obed_konditer']))? 0:$itog_string['menu_obed_konditer'];?></td>
            <td class="text-center align-middle"><?echo(empty($itog_string['menu_obed_frukti']))? 0:$itog_string['menu_obed_frukti'];?></td>
            <td class="text-center align-middle"><?echo(empty($itog_string['menu_obed_yagoda']))? 0:$itog_string['menu_obed_yagoda'];?></td>
            <td class="text-center align-middle"><?echo(empty($itog_string['menu_obed_med']))? 0:$itog_string['menu_obed_med'];?></td>
            <td class="text-center align-middle"><?echo(empty($itog_string['menu_obed_ovoshi']))? 0:$itog_string['menu_obed_ovoshi'];?></td>
            <?if($itog_string['menu_obed_count'] == 0){?>
                <td class="text-center align-middle">0</td>
                <td class="text-center align-middle">0</td>
                <td class="text-center align-middle">0</td>
                <td class="text-center align-middle">0</td>
                <td class="text-center align-middle">0</td>
                <td class="text-center align-middle">0</td>
                <td class="text-center align-middle">0</td>
            <?}else{?>
                <td class="text-center align-middle"><?=round($itog_string['menu_obed_kolbasa_count']/$itog_string['menu_obed_count'],1)?></td>
                <td class="text-center align-middle"><?=round($itog_string['menu_obed_konditer_count']/$itog_string['menu_obed_count'],1)?></td>
                <td class="text-center align-middle"><?=round($itog_string['menu_obed_frukti_count']/$itog_string['menu_obed_count'],1)?></td>
                <td class="text-center align-middle"><?=round($itog_string['menu_obed_yagoda_count']/$itog_string['menu_obed_count'],1)?></td>
                <td class="text-center align-middle"><?=round($itog_string['menu_obed_med_count']/$itog_string['menu_obed_count'],1)?></td>
                <td class="text-center align-middle"><?=round($itog_string['menu_obed_ovoshi_count']/$itog_string['menu_obed_count'],1)?></td>
                <td class="text-center align-middle"><?=(empty($itog_string['deficit_blud_obed']))? 0 : $itog_string['deficit_blud_obed']?></td>
            <?}?>
            <td class="text-center align-middle"><?=$itog_string['ohvat_vsego']?></td>
            <td class="text-center align-middle"><?=(!empty($itog_string['ohvat_vsego_count']))?round($itog_string['ohvat_%_vsego']/$itog_string['ohvat_vsego_count'], 2):0?></td>
            <td class="text-center align-middle"><?=$itog_string['ohvat_zavtrak']?></td>
            <td class="text-center align-middle"><?=(!empty($itog_string['ohvat_vsego']))?round($itog_string['ohvat_zavtrak']/$itog_string['ohvat_vsego']*100, 2):0?></td>
            <td class="text-center align-middle"><?=$itog_string['ohvat_obed']?></td>
            <td class="text-center align-middle"><?=(!empty($itog_string['ohvat_vsego']))?round($itog_string['ohvat_obed']/$itog_string['ohvat_vsego']*100, 2):0?></td>
            <td class="text-center align-middle"><?=$itog_string['ohvat_zavtrak_obed']?></td>
            <td class="text-center align-middle"><?=(!empty($itog_string['ohvat_vsego']))?round($itog_string['ohvat_zavtrak_obed']/$itog_string['ohvat_vsego']*100, 2):0?></td>

            <td class="text-center align-middle"><?=$itog_string['ohvat_sahar_school'];?></td>
            <td class="text-center align-middle"><?=$itog_string['ohvat_sahar_student']?></td>
            <td class="text-center align-middle"><?=$itog_string['shcolnik_sahar_menu']?></td>
            <td class="text-center align-middle"><?=$itog_string['ohvat_sahar_pit']?></td>
            <td class="text-center align-middle"><?=$itog_string['ohvat_sahar_nepit']?></td>

            <td class="text-center align-middle"><?=$itog_string['ohvat_cialic_school'] ;?></td>
            <td class="text-center align-middle"><?=$itog_string['ohvat_cialic_student']?></td>
            <td class="text-center align-middle"><?=$itog_string['shcolnik_cialic_menu']?></td>
            <td class="text-center align-middle"><?=$itog_string['ohvat_cialic_pit']?></td>
            <td class="text-center align-middle"><?=$itog_string['ohvat_cialic_nepit']?></td>

            <td class="text-center align-middle"><?=$itog_string['ohvat_mukovis_school'];?></td>
            <td class="text-center align-middle"><?=$itog_string['ohvat_mukovis_student']?></td>
            <td class="text-center align-middle"><?=$itog_string['shcolnik_mukovis_menu']?></td>
            <td class="text-center align-middle"><?=$itog_string['ohvat_mukovis_pit']?></td>
            <td class="text-center align-middle"><?=$itog_string['ohvat_mukovis_nepit']?></td>

            <td class="text-center align-middle"><?=$itog_string['ohvat_fenilketon_school'] ;?></td>
            <td class="text-center align-middle"><?=$itog_string['ohvat_fenilketon_student']?></td>
            <td class="text-center align-middle"><?=$itog_string['shcolnik_fenilketon_menu']?></td>
            <td class="text-center align-middle"><?=$itog_string['ohvat_fenilketon_pit']?></td>
            <td class="text-center align-middle"><?=$itog_string['ohvat_fenilketon_nepit']?></td>

            <td class="text-center align-middle"><?=$itog_string['ohvat_ovz_school'] ;?></td>
            <td class="text-center align-middle"><?=$itog_string['ohvat_ovz_student']?></td>
            <td class="text-center align-middle"><?=$itog_string['shcolnik_ovz_menu']?></td>
            <td class="text-center align-middle"><?=$itog_string['ohvat_ovz_pit']?></td>
            <td class="text-center align-middle"><?=$itog_string['ohvat_ovz_nepit']?></td>

            <td class="text-center align-middle"><?=$itog_string['ohvat_allergy_school'] ;?></td>
            <td class="text-center align-middle"><?=$itog_string['ohvat_allergy_student']?></td>
            <td class="text-center align-middle"><?=$itog_string['shcolnik_allergy_menu']?></td>
            <td class="text-center align-middle"><?=$itog_string['ohvat_allergy_pit']?></td>
            <td class="text-center align-middle"><?=$itog_string['ohvat_allergy_nepit']?></td>


            <td class="text-center align-middle"><?=$itog_string['zavtrak_vnutr']?></td>
            <td class="text-center align-middle"><?=$itog_string['zavtrak_min_ball']?></td>
            <td class="text-center align-middle"><?if(!empty($itog_string['zavtrak_control_count'])){ echo round($itog_string['zavtrak_sred_ball']/$itog_string['zavtrak_control_count'],1);}?></td>
            <td class="text-center align-middle"><?=$itog_string['zavtrak_max_ball']?></td>
            <td class="text-center align-middle"><?=$itog_string['zavtrak_min_procent']?></td>
            <td class="text-center align-middle"><?if(!empty($itog_string['zavtrak_control_count'])){ echo round($itog_string['zavtrak_sred_procent']/$itog_string['zavtrak_control_count'],1);}?></td>
            <td class="text-center align-middle"><?=$itog_string['zavtrak_max_procent']?></td>

            <td class="text-center align-middle"><?=$itog_string['obed_vnutr']?></td>
            <td class="text-center align-middle"><?=$itog_string['obed_min_ball']?></td>
            <td class="text-center align-middle"><?if(!empty($itog_string['obed_control_count'])){ echo round($itog_string['obed_sred_ball']/$itog_string['obed_control_count'],1);}?></td>
            <td class="text-center align-middle"><?=$itog_string['obed_max_ball']?></td>
            <td class="text-center align-middle"><?=$itog_string['obed_min_procent']?></td>
            <td class="text-center align-middle"><?if(!empty($itog_string['obed_control_count'])){ echo round($itog_string['obed_sred_procent']/$itog_string['obed_control_count'],1);}?></td>
            <td class="text-center align-middle"><?=$itog_string['obed_max_procent']?></td>

            <td class="text-center align-middle"><?=$itog_string['inoe_vnutr']?></td>
            <td class="text-center align-middle"><?=$itog_string['inoe_min_ball']?></td>
            <td class="text-center align-middle"><?if(!empty($itog_string['inoe_control_count'])){ echo round($itog_string['inoe_sred_ball']/$itog_string['inoe_control_count'],1);}?></td>
            <td class="text-center align-middle"><?=$itog_string['inoe_max_ball']?></td>
            <td class="text-center align-middle"><?=$itog_string['inoe_min_procent']?></td>
            <td class="text-center align-middle"><?if(!empty($itog_string['inoe_control_count'])){ echo round($itog_string['inoe_sred_procent']/$itog_string['inoe_control_count'],1);}?></td>
            <td class="text-center align-middle"><?=$itog_string['inoe_max_procent']?></td>


        </tr>




        <!--        <tr class="table-success">-->
        <!--            <td class="" colspan="3">Нормативы:</td>-->
        <!--            <td class="">-</td>-->
        <!--            <td class="text-center align-middle">-</td>-->
        <!--            <td class="text-center align-middle">-</td>-->
        <!--            <td class="text-center align-middle">-</td>-->
        <!--            <td class="text-center align-middle">-</td>-->
        <!--            <td class="text-center align-middle">-</td>-->
        <!--            <td class="text-center align-middle">-</td>-->
        <!--            <td class="text-center align-middle">-</td>-->
        <!--            <td class="text-center align-middle">-</td>-->
        <!--            <td class="text-center align-middle">-</td>-->
        <!--            <td class="text-center align-middle">-</td>-->
        <!--            <td class="text-center align-middle">-</td>-->
        <!--            <td class="text-center align-middle">-</td>-->
        <!--            <td class="text-center align-middle"></td>-->
        <!--            <td class="text-center align-middle"></td>-->
        <!--            <td class="text-center align-middle"></td>-->
        <!--            <td class="text-center align-middle"></td>-->
        <!--            <td class="text-center align-middle">-</td>-->
        <!--            <td class="text-center align-middle">-</td>-->
        <!--            <td class="text-center align-middle">-</td>-->
        <!--            <td class="text-center align-middle">-</td>-->
        <!--            <td class="text-center align-middle">-</td>-->
        <!--            <td class="text-center align-middle">-</td>-->
        <!--            <td class="text-center align-middle">-</td>-->
        <!--            <td class="text-center align-middle">-</td>-->
        <!--            <td class="text-center align-middle">-</td>-->
        <!--            <td class="text-center align-middle">-</td>-->
        <!--            <td class="text-center align-middle">-</td>-->
        <!--            <td class="text-center align-middle">-</td>-->
        <!--            <td class="text-center align-middle">-</td>-->
        <!--            <td class="text-center align-middle">-</td>-->
        <!--            <td class="text-center align-middle">-</td>-->
        <!--            <td class="text-center align-middle"><20</td>-->
        <!--            <td class="text-center align-middle">0</td>-->
        <!---->
        <!--            <td class="text-center align-middle">-</td>-->
        <!--            <td class="text-center align-middle">-</td>-->
        <!--            <td class="text-center align-middle">-</td>-->
        <!--            <td class="text-center align-middle">-</td>-->
        <!--            <td class="text-center align-middle">-</td>-->
        <!--            <td class="text-center align-middle">-</td>-->
        <!--            <td class="text-center align-middle">-</td>-->
        <!--            <td class="text-center align-middle">-</td>-->
        <!--            <td class="text-center align-middle">-</td>-->
        <!--            <td class="text-center align-middle">-</td>-->
        <!--            <td class="text-center align-middle">-</td>-->
        <!--            <td class="text-center align-middle">-</td>-->
        <!--            <td class="text-center align-middle">-</td>-->
        <!--            <td class="text-center align-middle">-</td>-->
        <!--            <td class="text-center align-middle">-</td>-->
        <!--            <td class="text-center align-middle">-</td>-->
        <!---->
        <!--                <td class="text-center align-middle">-</td>-->
        <!--                <td class="text-center align-middle">>400</td>-->
        <!--                <td class="text-center align-middle">>--><?//=$normativ['vitamin_c'] *0.2?><!--</td>-->
        <!--                <td class="text-center align-middle">>--><?//=$normativ['vitamin_b1']*0.2?><!--</td>-->
        <!--                <td class="text-center align-middle">>--><?//=$normativ['vitamin_b2']*0.2?><!--</td>-->
        <!--                <td class="text-center align-middle">>--><?//=$normativ['vitamin_a'] *0.2?><!--</td>-->
        <!--                <td class="text-center align-middle">>--><?//=$normativ['ca']*0.2?><!--</td>-->
        <!--                <td class="text-center align-middle">>--><?//=$normativ['p'] *0.2?><!--</td>-->
        <!--                <td class="text-center align-middle">>--><?//=$normativ['mg']*0.2?><!--</td>-->
        <!--                <td class="text-center align-middle">>--><?//=$normativ['fe'] *0.2?><!--</td>-->
        <!--                <td class="text-center align-middle">>--><?//=$normativ['i'] *0.2?><!--</td>-->
        <!--                <td class="text-center align-middle">>--><?//=$normativ['se']*0.2?><!--</td>-->
        <!--                <td class="text-center align-middle"><1.25</td>-->
        <!--                <td class="text-center align-middle"><10</td>-->
        <!---->
        <!--            <td class="text-center align-middle">-</td>-->
        <!--            <td class="text-center align-middle">-</td>-->
        <!--            <td class="text-center align-middle">-</td>-->
        <!--            <td class="text-center align-middle">-</td>-->
        <!--            <td class="text-center align-middle">-</td>-->
        <!--            <td class="text-center align-middle">-</td>-->
        <!---->
        <!--            <td class="text-center align-middle">0</td>-->
        <!--            <td class="text-center align-middle">0</td>-->
        <!--            <td class="text-center align-middle">>0</td>-->
        <!--            <td class="text-center align-middle">>0</td>-->
        <!--            <td class="text-center align-middle">>0</td>-->
        <!--            <td class="text-center align-middle">>0</td>-->
        <!--            <td class="text-center align-middle">0</td>-->
        <!--            <td class="text-center align-middle"></td>-->
        <!--            <td class="text-center align-middle"></td>-->
        <!--            <td class="text-center align-middle"></td>-->
        <!--            <td class="text-center align-middle"></td>-->
        <!--            <td class="text-center align-middle"></td>-->
        <!--            <td class="text-center align-middle"></td>-->
        <!--            <td class="text-center align-middle"></td>-->
        <!--            <td class="text-center align-middle"></td>-->
        <!--            <td class="text-center align-middle"></td>-->
        <!--            <td class="text-center align-middle"></td>-->
        <!--            <td class="text-center align-middle"></td>-->
        <!--            <td class="text-center align-middle"></td>-->
        <!--            <td class="text-center align-middle"></td>-->
        <!--            <td class="text-center align-middle"></td>-->
        <!--            <td class="text-center align-middle"></td>-->
        <!--            <td class="text-center align-middle"></td>-->
        <!--                <td class="text-center align-middle">-</td>-->
        <!--                <td class="text-center align-middle">>550</td>-->
        <!--                <td class="text-center align-middle">>--><?//=$normativ['vitamin_c'] *0.3?><!--</td>-->
        <!--                <td class="text-center align-middle">>--><?//=$normativ['vitamin_b1']*0.3?><!--</td>-->
        <!--                <td class="text-center align-middle">>--><?//=$normativ['vitamin_b2']*0.3?><!--</td>-->
        <!--                <td class="text-center align-middle">>--><?//=$normativ['vitamin_a'] *0.3?><!--</td>-->
        <!--                <td class="text-center align-middle">>--><?//=$normativ['ca']*0.3?><!--</td>-->
        <!--                <td class="text-center align-middle">>--><?//=$normativ['p'] *0.3?><!--</td>-->
        <!--                <td class="text-center align-middle">>--><?//=$normativ['mg']*0.3?><!--</td>-->
        <!--                <td class="text-center align-middle">>--><?//=$normativ['fe'] *0.3?><!--</td>-->
        <!--                <td class="text-center align-middle">>--><?//=$normativ['i'] *0.3?><!--</td>-->
        <!--                <td class="text-center align-middle">>--><?//=$normativ['se']*0.3?><!--</td>-->
        <!--                <td class="text-center align-middle"><1.5</td>-->
        <!--                <td class="text-center align-middle"><10</td>-->
        <!---->
        <!---->
        <!--            <td class="text-center align-middle">-</td>-->
        <!--            <td class="text-center align-middle">-</td>-->
        <!--            <td class="text-center align-middle">-</td>-->
        <!--            <td class="text-center align-middle">-</td>-->
        <!--            <td class="text-center align-middle">-</td>-->
        <!--            <td class="text-center align-middle">-</td>-->
        <!---->
        <!--            <td class="text-center align-middle">0</td>-->
        <!--            <td class="text-center align-middle">0</td>-->
        <!--            <td class="text-center align-middle">>0</td>-->
        <!--            <td class="text-center align-middle">>0</td>-->
        <!--            <td class="text-center align-middle">>0</td>-->
        <!--            <td class="text-center align-middle">>0</td>-->
        <!--            <td class="text-center align-middle">0</td>-->
        <!--            <td class="text-center align-middle"></td>-->
        <!---->
        <!--            <td class="text-center align-middle">-</td>-->
        <!--            <td class="text-center align-middle">-</td>-->
        <!--            <td class="text-center align-middle">-</td>-->
        <!--            <td class="text-center align-middle">-</td>-->
        <!--            <td class="text-center align-middle">-</td>-->
        <!--            <td class="text-center align-middle">-</td>-->
        <!---->
        <!--            <td class="text-center align-middle">-</td>-->
        <!--            <td class="text-center align-middle">-</td>-->
        <!--            <td class="text-center align-middle">-</td>-->
        <!--            <td class="text-center align-middle">-</td>-->
        <!--            <td class="text-center align-middle">-</td>-->
        <!--            <td class="text-center align-middle">-</td>-->
        <!---->
        <!--            <td class="text-center align-middle">-</td>-->
        <!--            <td class="text-center align-middle">-</td>-->
        <!--            <td class="text-center align-middle">-</td>-->
        <!--            <td class="text-center align-middle">-</td>-->
        <!--            <td class="text-center align-middle">-</td>-->
        <!--            <td class="text-center align-middle">-</td>-->
        <!---->
        <!--            <td class="text-center align-middle">-</td>-->
        <!--            <td class="text-center align-middle">-</td>-->
        <!--            <td class="text-center align-middle">-</td>-->
        <!--            <td class="text-center align-middle">-</td>-->
        <!--            <td class="text-center align-middle">-</td>-->
        <!--            <td class="text-center align-middle">-</td>-->
        <!---->
        <!--            <td class="text-center align-middle">-</td>-->
        <!--            <td class="text-center align-middle">-</td>-->
        <!--            <td class="text-center align-middle">-</td>-->
        <!--            <td class="text-center align-middle">-</td>-->
        <!--            <td class="text-center align-middle">-</td>-->
        <!--            <td class="text-center align-middle">-</td>-->
        <!---->
        <!--            <td class="text-center align-middle">-</td>-->
        <!--            <td class="text-center align-middle">-</td>-->
        <!--            <td class="text-center align-middle">-</td>-->
        <!--            <td class="text-center align-middle">-</td>-->
        <!--            <td class="text-center align-middle">-</td>-->
        <!--            <td class="text-center align-middle">-</td>-->
        <!---->
        <!--            <td class="text-center align-middle">-</td>-->
        <!--            <td class="text-center align-middle">-</td>-->
        <!--            <td class="text-center align-middle">-</td>-->
        <!--            <td class="text-center align-middle">-</td>-->
        <!--            <td class="text-center align-middle">40</td>-->
        <!--            <td class="text-center align-middle"><35%</td>-->
        <!--            <td class="text-center align-middle"><35%</td>-->
        <!--            <td class="text-center align-middle"><35%</td>-->
        <!---->
        <!--            <td class="text-center align-middle">-</td>-->
        <!--            <td class="text-center align-middle">-</td>-->
        <!--            <td class="text-center align-middle">-</td>-->
        <!--            <td class="text-center align-middle">40</td>-->
        <!--            <td class="text-center align-middle"><35%</td>-->
        <!--            <td class="text-center align-middle"><35%</td>-->
        <!--            <td class="text-center align-middle"><35%</td>-->
        <!--        </tr>-->


        </tbody>
    </table><br><br><br>
<?}?>


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
