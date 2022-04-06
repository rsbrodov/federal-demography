<?php

use common\models\BasicInformation;
use common\models\CharactersStolovaya;
use common\models\CharactersStudy;
use common\models\ExpensesFood;
use common\models\InformationEducation;
use common\models\Kids;
use common\models\Menus;
use common\models\Municipality;
use common\models\SchoolBreak;
use common\models\TypeLager;
use common\models\TypeOrganization;
use common\models\User;
use common\models\FederalDistrict;
use common\models\Region;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;
use common\models\Organization;

/* @var $this yii\web\View */
/* @var $model common\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="users-nutrition-report-region-form container"><h2 align="center">ПС "Питание" -
        отчет общий (Общеобразовательные организации/Организаторы питания/МО управления образования</h2>
    <?php
    $form = ActiveForm::begin();
    $federal_district = FederalDistrict::find()->all();
    $federal_district_item = ArrayHelper::map($federal_district, 'id', 'name');
    $type_org = TypeOrganization::find()->all();
    $type_org_item = ArrayHelper::map($type_org, 'id', 'name');
    //не пришли с контроллера
    if (empty($district_for_district) && empty($region_for_district) && empty($municipality_for_region)) {
        $district_for_district = 0;
        $region_for_district = 0;
        $municipality_for_region = 0;
    }

    $two_column = [
        'options' => ['class' => 'row mt-3'],
        'labelOptions' => ['class' => 'col-4 col-form-label font-weight-bold']
    ];
    $federal_district_item['0'] = 'Все';
    $region_item['0'] = 'Все';
    $municipality_item['0'] = 'Все';
    $type_org_item['0'] = 'Все';

    //удаляем лишнее из выборки:
    $unset_organization = [1, 6, 5, 7, 8, 9, 0];
    for ($i = 0; $i < count($unset_organization); $i++) {
        unset($type_org_item[$unset_organization[$i]]);
    }
    //sort($type_org_item);
    if (Yii::$app->user->can('rospotrebnadzor_nutrition')) {
        $flat_feet_items_info = Organization::find()->select(['federal_district_id', 'region_id'])->where(['id' => Yii::$app->user->identity->organization_id])->one();
        $federal_district = FederalDistrict::find()->where(['id' => $flat_feet_items_info['federal_district_id']])->all();
        $region = Region::find()->where(['id' => $flat_feet_items_info['region_id']])->all();
        $federal_district_item = ArrayHelper::map($federal_district, 'id', 'name');
        $region_item = ArrayHelper::map($region, 'id', 'name');
    }
    //рисуем форму:
    echo $form
        ->field($model, 'federal_district_id', $two_column)
        ->dropDownList($federal_district_item,
            [
                'options' => [$district_for_district => ['Selected' => true]],
                'class' => 'form-control col-8'
            ]);
    echo $form
        ->field($model, 'region_id', $two_column)
        ->dropDownList($region_item,
            [
                'options' => [$region_for_district => ['Selected' => true]],
                'class' => 'form-control col-8'
            ]);
    echo $form
        ->field($model, 'municipality_id', $two_column)
        ->dropDownList($municipality_item,
            [
                'options' => [$municipality_for_region => ['Selected' => true]],
                'class' => 'form-control col-8'
            ]);
    /*echo $form
        ->field($model, 'type_org', $two_column)
        ->dropDownList($type_org_item,
            [
                'options' => [$type_org_key => ['Selected' => true]],
                'class' => 'form-control col-8'
            ]);
    */ ?>
    <div class="form-group row">
        <?= Html::submitButton('Показать', ['class' => 'btn btn-success form-control col-12 mt-3']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>

<? if (!empty($districts)) //нажали кнопку показать
{
    ?>
    <table class="table table-bordered table-sm table-responsive">
        <thead>
        <tr class="main-color-7">
            <th class="text-center" rowspan="4" colspan="1">№</th>
            <th class="text-center" rowspan="4" colspan="1">Территория</th>
            <th class="text-center" rowspan="1" colspan="16">Первый уровень - школы</th>
            <th class="text-center" rowspan="1" colspan="16">Второй уровень - операторы питания</th>
            <th class="text-center" rowspan="1" colspan="2">Третий уровень - МО управления образованием</th>
        </tr>
        <tr class="main-color-7">
            <th class="text-center" rowspan="3" colspan="1">Регистрация</th>
            <th class="text-center" rowspan="3" colspan="1">Количество организаций, внесших меню</th>
            <th class="text-center" rowspan="1" colspan="8">Внесено меню</th>
            <th class="text-center" rowspan="1" colspan="6">Полностью заполнена информация</th>
            <th class="text-center" rowspan="3" colspan="1">Регистрация</th>
            <th class="text-center" rowspan="3" colspan="1">Количество организаций, внесших меню</th>
            <th class="text-center" rowspan="1" colspan="8">Внесено меню</th>
            <th class="text-center" rowspan="1" colspan="6">Полностью заполнена информация</th>
            <th class="text-center" rowspan="3" colspan="1">Регистрация</th>
            <th class="text-center" rowspan="3" colspan="1">Заполнена информация о источниках финансирования</th>
        </tr>
            <tr class="main-color-7">
            <th class="text-center" rowspan="2" colspan="1">всего</th>
            <th class="text-center" rowspan="1" colspan="7">в том числе</th>
            <th class="text-center" rowspan="2" colspan="1">общая</th>
            <th class="text-center" rowspan="2" colspan="1">о столовой</th>'
            <th class="text-center" rowspan="2" colspan="1">о хар-ке обучающихся</th>
            <th class="text-center" rowspan="2" colspan="1">о произв. помещ.</th>'
            <th class="text-center" rowspan="2" colspan="1">о кол-ве обучающихся</th>
            <th class="text-center" rowspan="2" colspan="1">о сменах и переменах</th>
            <th class="text-center" rowspan="2" colspan="1">всего</th>
            <th class="text-center" rowspan="1" colspan="7">в том числе</th>
            <th class="text-center" rowspan="2" colspan="1">общая</th>
            <th class="text-center" rowspan="2" colspan="1">о столовой</th>'
            <th class="text-center" rowspan="2" colspan="1">о хар-ке обучающихся</th>
            <th class="text-center" rowspan="2" colspan="1">о произв. помещ.</th>'
            <th class="text-center" rowspan="2" colspan="1">о кол-ве обучающихся</th>
            <th class="text-center" rowspan="2" colspan="1">о сменах и переменах</th>
        </tr>
        <tr class="main-color-7">
            <th class="text-center" rowspan="1" colspan="1">б/о</th>
            <th class="text-center" rowspan="1" colspan="1">ОВЗ</th>
            <th class="text-center" rowspan="1" colspan="1">СД</th>
            <th class="text-center" rowspan="1" colspan="1">Целиакия</th>
            <th class="text-center" rowspan="1" colspan="1">ФКУ</th>
            <th class="text-center" rowspan="1" colspan="1">Муковисцидоз</th>
            <th class="text-center" rowspan="1" colspan="1">ПА</th>
            <th class="text-center" rowspan="1" colspan="1">б/о</th>
            <th class="text-center" rowspan="1" colspan="1">ОВЗ</th>
            <th class="text-center" rowspan="1" colspan="1">СД</th>
            <th class="text-center" rowspan="1" colspan="1">Целиакия</th>
            <th class="text-center" rowspan="1" colspan="1">ФКУ</th>
            <th class="text-center" rowspan="1" colspan="1">Муковисцидоз</th>
            <th class="text-center" rowspan="1" colspan="1">ПА</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $array_org = array();
        $array_org[] = array();
        $i = 0;
        $j = 0;
        $k = 0;
        $m = 0;
        $municipality_for_region = ($municipality_for_region == '0' ? ('') : $municipality_for_region);
        $cur_district = 0;
        $prev_district = 0;
        //print_r($type_org_key);
        $menus_1lvl_all_count = 0;
        $menus_1lvl_wf_all_count = 0;
        $menus_1lvl_lim_pos_all_count = 0;
        $menus_1lvl_diabetes_all_count = 0;
        $menus_1lvl_celiac_all_count = 0;
        $menus_1lvl_phenyl_all_count = 0;
        $menus_1lvl_cys_fibr_all_count = 0;
        //$menus_1lvl_metabolic_all_count = 0;
        $menus_1lvl_food_allergy_all_count = 0;
        $menu_1lvl_all_count = 0;
        $menus_2lvl_all_count = 0;
        $menus_2lvl_wf_all_count = 0;
        $menus_2lvl_lim_pos_all_count = 0;
        $menus_2lvl_diabetes_all_count = 0;
        $menus_2lvl_celiac_all_count = 0;
        $menus_2lvl_phenyl_all_count = 0;
        $menus_2lvl_cys_fibr_all_count = 0;
        //$menus_2lvl_metabolic_all_count = 0;
        $menus_2lvl_food_allergy_all_count = 0;
        $menu_2lvl_all_count = 0;
        //$kids_1lvl_all_count = 0;
        //$kid_1lvl_all_count = 0;
        $information_1lvl_inc_all100 = 0;
        $school_break_1lvl_information_inc_all100 = 0;
        $education_1lvl_information_inc_all100 = 0;
        $basic_1lvl_information_inc_all100 = 0;
        $study_1lvl_information_inc_all100 = 0;
        $stolovaya_1lvl_information_inc_all100 = 0;

        $information_2lvl_inc_all100 = 0;
        $school_break_2lvl_information_inc_all100 = 0;
        $education_2lvl_information_inc_all100 = 0;
        $basic_2lvl_information_inc_all100 = 0;
        $study_2lvl_information_inc_all100 = 0;
        $stolovaya_2lvl_information_inc_all100 = 0;

        $organizations_1lvl_all_count = 0;
        $organizations_2lvl_all_count = 0;
        $organizations_3lvl_all_count = 0;
        $expenses_food_all_information = 0;
        foreach ($districts as $district) {
            $cur_district = $district->id;
            if ($region_for_district == 0) {
                $regions = Region::find()
                    ->select('id')
                    ->where(['district_id' => $district->id])
                    ->all();//получили все регионы по всем округам
            }
            else {
                $regions = Region::find()
                    ->select('id')
                    ->where(['district_id' => $district->id, 'id' => $region_for_district])
                    ->all();//получили регион
            }
            if ($regions) {
                $count_organizations_all = 0;
                $prev_region = 0;
                $cur_region = 0;
                         $menus_1lvl_district_count = 0;
                      $menus_1lvl_wf_district_count = 0;
                 $menus_1lvl_lim_pos_district_count = 0;
                $menus_1lvl_diabetes_district_count = 0;
                  $menus_1lvl_celiac_district_count = 0;
                  $menus_1lvl_phenyl_district_count = 0;
                $menus_1lvl_cys_fibr_district_count = 0;
                //$menus_1lvl_metabolic_district_count = 0;
                $menus_1lvl_food_allergy_district_count = 0;
                $menu_1lvl_district_count = 0;
                         $menus_2lvl_district_count = 0;
                      $menus_2lvl_wf_district_count = 0;
                 $menus_2lvl_lim_pos_district_count = 0;
                $menus_2lvl_diabetes_district_count = 0;
                  $menus_2lvl_celiac_district_count = 0;
                  $menus_2lvl_phenyl_district_count = 0;
                $menus_2lvl_cys_fibr_district_count = 0;
                //$menus_2lvl_metabolic_district_count = 0;
                $menus_2lvl_food_allergy_district_count = 0;
                $menu_2lvl_district_count = 0;
                //$kids_1lvl_district_count = 0;
                //$kid_1lvl_district_count = 0;
                             $information_1lvl_inc_district100 = 0;
                $school_break_1lvl_information_inc_district100 = 0;
                   $education_1lvl_information_inc_district100 = 0;
                       $basic_1lvl_information_inc_district100 = 0;
                       $study_1lvl_information_inc_district100 = 0;
                   $stolovaya_1lvl_information_inc_district100 = 0;

                             $information_2lvl_inc_district100 = 0;
                $school_break_2lvl_information_inc_district100 = 0;
                   $education_2lvl_information_inc_district100 = 0;
                       $basic_2lvl_information_inc_district100 = 0;
                       $study_2lvl_information_inc_district100 = 0;
                   $stolovaya_2lvl_information_inc_district100 = 0;

                $organizations_1lvl_district_count = 0;
                $organizations_2lvl_district_count = 0;
                $organizations_3lvl_district_count = 0;
                $expenses_food_district_information = 0;
                foreach ($regions as $region)//цикл по регионам
                {
                    $number_of_municipality = 0;
                    $cur_region = $region->id;
                    //$subquery = User::find()->select(['id'])->where(['status' => 10])->count();
                    if ($municipality_for_region == 0) {
                        $municipalitys = Municipality::find()->select(['id'])->where(['region_id' => $region->id])->orderBy('name ASC')->all();
                    }
                    else {
                        $municipalitys = Municipality::find()->select(['id'])->where(['region_id' => $region->id, 'id' => $municipality_for_region])->orderBy('name ASC')->all();
                        //print_r($municipalitys);
                    }
                    $menus_1lvl_region_count = 0;
                    $menus_1lvl_wf_region_count = 0;
                    $menus_1lvl_lim_pos_region_count = 0;
                    $menus_1lvl_diabetes_region_count = 0;
                    $menus_1lvl_celiac_region_count = 0;
                    $menus_1lvl_phenyl_region_count = 0;
                    $menus_1lvl_cys_fibr_region_count = 0;
                    $menus_1lvl_food_allergy_region_count = 0;
                    $menu_1lvl_region_count = 0;
                    $menus_2lvl_region_count = 0;
                    $menus_2lvl_wf_region_count = 0;
                    $menus_2lvl_lim_pos_region_count = 0;
                    $menus_2lvl_diabetes_region_count = 0;
                    $menus_2lvl_celiac_region_count = 0;
                    $menus_2lvl_phenyl_region_count = 0;
                    $menus_2lvl_cys_fibr_region_count = 0;
                    $menus_2lvl_food_allergy_region_count = 0;
                    $menu_2lvl_region_count = 0;

                    $information_1lvl_inc_region100 = 0;
                    $school_break_1lvl_information_inc_region100 = 0;
                    $education_1lvl_information_inc_region100 = 0;
                    $basic_1lvl_information_inc_region100 = 0;
                    $study_1lvl_information_inc_region100 = 0;
                    $stolovaya_1lvl_information_inc_region100 = 0;

                    $information_2lvl_inc_region100 = 0;
                    $school_break_2lvl_information_inc_region100 = 0;
                    $education_2lvl_information_inc_region100 = 0;
                    $basic_2lvl_information_inc_region100 = 0;
                    $study_2lvl_information_inc_region100 = 0;
                    $stolovaya_2lvl_information_inc_region100 = 0;

                    $organizations_1lvl_region_count = 0;
                    $organizations_2lvl_region_count = 0;
                    $organizations_3lvl_region_count = 0;
                    $expenses_food_region_information = 0;
                    foreach ($municipalitys as $municipality) {
                        $menus_1lvl_municipality_count = 0;
                        $menus_1lvl_municipality_count = 0;
                        $menus_1lvl_wf_municipality_count = 0;
                        $menus_1lvl_lim_pos_municipality_count = 0;
                        $menus_1lvl_diabetes_municipality_count = 0;
                        $menus_1lvl_celiac_municipality_count = 0;
                        $menus_1lvl_phenyl_municipality_count = 0;
                        $menus_1lvl_cys_fibr_municipality_count = 0;
                        //$menus_1lvl_metabolic_municipality_count = 0;
                        $menus_1lvl_food_allergy_municipality_count = 0;
                        $menu_1lvl_municipality_count = 0;
                        $menus_2lvl_municipality_count = 0;
                        $menus_2lvl_municipality_count = 0;
                        $menus_2lvl_wf_municipality_count = 0;
                        $menus_2lvl_lim_pos_municipality_count = 0;
                        $menus_2lvl_diabetes_municipality_count = 0;
                        $menus_2lvl_celiac_municipality_count = 0;
                        $menus_2lvl_phenyl_municipality_count = 0;
                        $menus_2lvl_cys_fibr_municipality_count = 0;
                        //$menus_2lvl_metabolic_municipality_count = 0;
                        $menus_2lvl_food_allergy_municipality_count = 0;
                        $menu_2lvl_municipality_count = 0;
                        //$kids_1lvl_municipality_count = 0;
                        //$kid_1lvl_municipality_count = 0;
                        $information_1lvl_inc_municipality100 = 0;
                        $school_break_1lvl_information_inc_municipality100 = 0;
                        $education_1lvl_information_inc_municipality100 = 0;
                        $basic_1lvl_information_inc_municipality100 = 0;
                        $study_1lvl_information_inc_municipality100 = 0;
                        $stolovaya_1lvl_information_inc_municipality100 = 0;

                        $information_2lvl_inc_municipality100 = 0;
                        $school_break_2lvl_information_inc_municipality100 = 0;
                        $education_2lvl_information_inc_municipality100 = 0;
                        $basic_2lvl_information_inc_municipality100 = 0;
                        $study_2lvl_information_inc_municipality100 = 0;
                        $stolovaya_2lvl_information_inc_municipality100 = 0;
                        $expenses_food_municipality_information = 0;
                        //print_r($municipality->id);
                        $bad_org_id = [305];
                        $organizations = Organization::find()
                            ->leftJoin('municipality as mun', 'organization.municipality_id = mun.id')
                            ->where(['organization.region_id' => $region->id])
                            ->andWhere(['not in', 'organization.type_org', $unset_organization])
                            ->andWhere(['not in', 'organization.id', $bad_org_id])
                            ->andFilterWhere(['organization.municipality_id' => $municipality->id])
                            ->orderBy('mun.name ASC')
                            //->andWhere(['in', 'id', $subquery])
                            ->all();
                        $count_organizations = Organization::find()
                            ->leftJoin('municipality as mun', 'organization.municipality_id = mun.id')
                            ->where(['organization.region_id' => $region->id])
                            ->andWhere(['not in', 'organization.type_org', $unset_organization])
                            ->andWhere(['not in', 'organization.id', $bad_org_id])
                            ->andFilterWhere(['organization.municipality_id' => $municipality->id])
                            //->andWhere(['in', 'id', $subquery])
                            ->count();
                        //print_r($organizations);
                        $count_organizations_all += $count_organizations;
                        //$type_user = \common\models\AuthAssignment::find()->select(['item_name'])->where(['user_id' => $user_id]);
                        $organizations_1lvl_municipality_count = 0;
                        $organizations_2lvl_municipality_count = 0;
                        $organizations_3lvl_municipality_count = 0;

                        foreach ($organizations as $organization) {
                            $expenses_food_information = 0;
                            //$array_org[$i][0] = $organization->federal_district_id;
                            //$array_org[$j][0] = $organization->federal_district_id;
                            //$array_org[$k][0] = $organization->federal_district_id;
                            //$array_org[$m][0] = $organization->federal_district_id;
                            //$array_org[$i][1] = Region::findOne($organization->region_id)->name;
                            //$array_org[$j][1] = Region::findOne($organization->region_id)->name;
                            //$array_org[$m][1] = Region::findOne($organization->region_id)->name;
                            $array_org[$m][2] = Municipality::findOne($organization->municipality_id)->name;
                            $array_org[$i][3] = $organization->short_title;
                            $array_org[$i][2] = Municipality::findOne($organization->municipality_id)->name;
                            $array_org[$i][4] = $organization->get_type_org($organization->type_org);
                            $array_org[$i][7] = $organization->organizator_food;
                            $array_org[$i][8] = $organization->medic_service_programm;

                            $basic_information = 0;
                            //$basic_information_items = Organization::find()->Select(['naseleni_punkt', 'name_dir', 'short_title', 'address', 'phone', 'email', 'inn'])->where(['id' => $organization->id])->all();
                            $information_items = Organization::findOne(['id' => $organization->id]);
                            $stolovaya_information_items = CharactersStolovaya::find()->where(['organization_id' => $organization->id])->count() > 0;
                            $study_information_items = CharactersStudy::find()->where(['organization_id' => $organization->id])->count() > 0;
                            $basic_information_items = BasicInformation::find()->where(['organization_id' => $organization->id])->count() > 0;
                            $education_information_items = InformationEducation::find()->where(['organization_id' => $organization->id])->count() > 0;
                            $school_break_information_items = SchoolBreak::find()->where(['organization_id' => $organization->id])->count() > 0;
                            if ($array_org[$i][4] == 'Уровень 1 - Общеобразовательная организация') {

                                if (!empty($information_items->address) && !empty($information_items->email) && !empty($information_items->phone) && !empty($information_items->name_dir)) {
                                    $information_1lvl_inc_municipality100++;
                                    $information_1lvl_inc_region100++;
                                    $information_1lvl_inc_district100++;
                                    $information_1lvl_inc_all100++;
                                }
                                if ($stolovaya_information_items) {
                                    $stolovaya_1lvl_information_inc_municipality100++;
                                    $stolovaya_1lvl_information_inc_region100++;
                                    $stolovaya_1lvl_information_inc_district100++;
                                    $stolovaya_1lvl_information_inc_all100++;
                                }
                                if ($study_information_items) {
                                    $study_1lvl_information_inc_municipality100++;
                                    $study_1lvl_information_inc_region100++;
                                    $study_1lvl_information_inc_district100++;
                                    $study_1lvl_information_inc_all100++;
                                }
                                if ($basic_information_items) {

                                    $basic_1lvl_information_inc_municipality100++;
                                    $basic_1lvl_information_inc_region100++;
                                    $basic_1lvl_information_inc_district100++;
                                    $basic_1lvl_information_inc_all100++;
                                }
                                if ($education_information_items) {
                                    $education_1lvl_information_inc_municipality100++;
                                    $education_1lvl_information_inc_region100++;
                                    $education_1lvl_information_inc_district100++;
                                    $education_1lvl_information_inc_all100++;
                                }
                                if ($school_break_information_items) {
                                    $school_break_1lvl_information_inc_municipality100++;
                                    $school_break_1lvl_information_inc_region100++;
                                    $school_break_1lvl_information_inc_district100++;
                                    $school_break_1lvl_information_inc_all100++;
                                }
                                $organizations_1lvl_municipality_count++;
                                $menus_1lvl_organization_count = Menus::find()
                                    ->Select(['organization_id'])
                                    ->where(['organization_id' => $organization->id, 'status_archive' => '0'])
                                    ->andWhere(['or', 'age_info_id' => 6, 'age_info_id' => 9])
                                    ->andWhere(['!=', 'feeders_characters_id', 9])
                                    ->count();
                                $menus_1lvl_municipality_count += $menus_1lvl_organization_count;
                                $menu_1lvl_municipality_count += Menus::find()->Select(['organization_id'])->where(['organization_id' => $organization->id, 'status_archive' => '0'])
                                    ->andWhere(['or', 'age_info_id' => 6, 'age_info_id' => 9])
                                    ->andWhere(['!=', 'feeders_characters_id', 9])
                                    ->groupBy('organization_id')
                                    ->count();
                                          $menus_1lvl_wf_organization_count = Menus::find()->Select(['organization_id'])->where(['organization_id' => $organization->id, 'status_archive' => '0', 'feeders_characters_id' => 3])->andWhere(['or', 'age_info_id' => 6, 'age_info_id' => 9])->count();
                                     $menus_1lvl_lim_pos_organization_count = Menus::find()->Select(['organization_id'])->where(['organization_id' => $organization->id, 'status_archive' => '0', 'feeders_characters_id' => 4])->andWhere(['or', 'age_info_id' => 6, 'age_info_id' => 9])->count();
                                    $menus_1lvl_diabetes_organization_count = Menus::find()->Select(['organization_id'])->where(['organization_id' => $organization->id, 'status_archive' => '0', 'feeders_characters_id' => 5])->andWhere(['or', 'age_info_id' => 6, 'age_info_id' => 9])->count();
                                      $menus_1lvl_celiac_organization_count = Menus::find()->Select(['organization_id'])->where(['organization_id' => $organization->id, 'status_archive' => '0', 'feeders_characters_id' => 6])->andWhere(['or', 'age_info_id' => 6, 'age_info_id' => 9])->count();
                                      $menus_1lvl_phenyl_organization_count = Menus::find()->Select(['organization_id'])->where(['organization_id' => $organization->id, 'status_archive' => '0', 'feeders_characters_id' => 7])->andWhere(['or', 'age_info_id' => 6, 'age_info_id' => 9])->count();
                                    $menus_1lvl_cys_fibr_organization_count = Menus::find()->Select(['organization_id'])->where(['organization_id' => $organization->id, 'status_archive' => '0', 'feeders_characters_id' => 8])->andWhere(['or', 'age_info_id' => 6, 'age_info_id' => 9])->count();
                                //$menus_metabolic_organization_count = Menus::find()->Select(['organization_id'])->where(['organization_id' => $organization->id, 'status_archive' => '0', 'age_info_id' => 6, 'feeders_characters_id' => 9])->count();
                                $menus_1lvl_food_allergy_organization_count = Menus::find()->Select(['organization_id'])->where(['organization_id' => $organization->id, 'status_archive' => '0', 'feeders_characters_id' => 10])->andWhere(['or', 'age_info_id' => 6, 'age_info_id' => 9])->count();

                                $menus_1lvl_wf_municipality_count += $menus_1lvl_wf_organization_count;
                                $menus_1lvl_lim_pos_municipality_count += $menus_1lvl_lim_pos_organization_count;
                                $menus_1lvl_diabetes_municipality_count += $menus_1lvl_diabetes_organization_count;
                                $menus_1lvl_celiac_municipality_count += $menus_1lvl_celiac_organization_count;
                                $menus_1lvl_phenyl_municipality_count += $menus_1lvl_phenyl_organization_count;
                                $menus_1lvl_cys_fibr_municipality_count += $menus_1lvl_cys_fibr_organization_count;
                                //$menus_metabolic_municipality_count += $menus_metabolic_organization_count;
                                $menus_1lvl_food_allergy_municipality_count += $menus_1lvl_food_allergy_organization_count;
                            }
                            elseif ($array_org[$i][4] == 'Уровень 2 - Организатор питания') {
                                if (!empty($information_items->address) && !empty($information_items->email) && !empty($information_items->phone) && !empty($information_items->name_dir)) {
                                    $information_2lvl_inc_municipality100++;
                                    $information_2lvl_inc_region100++;
                                    $information_2lvl_inc_district100++;
                                    $information_2lvl_inc_all100++;
                                }
                                if ($stolovaya_information_items) {
                                    $stolovaya_2lvl_information_inc_municipality100++;
                                    $stolovaya_2lvl_information_inc_region100++;
                                    $stolovaya_2lvl_information_inc_district100++;
                                    $stolovaya_2lvl_information_inc_all100++;
                                }
                                if ($study_information_items) {
                                    $study_2lvl_information_inc_municipality100++;
                                    $study_2lvl_information_inc_region100++;
                                    $study_2lvl_information_inc_district100++;
                                    $study_2lvl_information_inc_all100++;
                                }
                                if ($basic_information_items) {

                                    $basic_2lvl_information_inc_municipality100++;
                                    $basic_2lvl_information_inc_region100++;
                                    $basic_2lvl_information_inc_district100++;
                                    $basic_2lvl_information_inc_all100++;
                                }
                                if ($education_information_items) {
                                    $education_2lvl_information_inc_municipality100++;
                                    $education_2lvl_information_inc_region100++;
                                    $education_2lvl_information_inc_district100++;
                                    $education_2lvl_information_inc_all100++;
                                }
                                if ($school_break_information_items) {
                                    $school_break_2lvl_information_inc_municipality100++;
                                    $school_break_2lvl_information_inc_region100++;
                                    $school_break_2lvl_information_inc_district100++;
                                    $school_break_2lvl_information_inc_all100++;
                                }
                                $organizations_2lvl_municipality_count++;
                                $menus_2lvl_organization_count = Menus::find()
                                    ->Select(['organization_id'])
                                    ->where(['organization_id' => $organization->id, 'status_archive' => '0'])
                                    ->andWhere(['or', 'age_info_id' => 6, 'age_info_id' => 9])
                                    ->andWhere(['!=', 'feeders_characters_id', 9])
                                    ->count();
                                $menus_2lvl_municipality_count += $menus_2lvl_organization_count;
                                $menu_2lvl_municipality_count += Menus::find()
                                    ->Select(['organization_id'])
                                    ->where(['organization_id' => $organization->id, 'status_archive' => '0'])
                                    ->andWhere(['or', 'age_info_id' => 6, 'age_info_id' => 9])
                                    ->andWhere(['!=', 'feeders_characters_id', 9])
                                    ->groupBy('organization_id')
                                    ->count();
                                      $menus_2lvl_wf_organization_count = Menus::find()->Select(['organization_id'])->where(['organization_id' => $organization->id, 'status_archive' => '0', 'feeders_characters_id' => 3])->andWhere(['or', 'age_info_id' => 6, 'age_info_id' => 9])->count();
                                 $menus_2lvl_lim_pos_organization_count = Menus::find()->Select(['organization_id'])->where(['organization_id' => $organization->id, 'status_archive' => '0', 'feeders_characters_id' => 4])->andWhere(['or', 'age_info_id' => 6, 'age_info_id' => 9])->count();
                                $menus_2lvl_diabetes_organization_count = Menus::find()->Select(['organization_id'])->where(['organization_id' => $organization->id, 'status_archive' => '0', 'feeders_characters_id' => 5])->andWhere(['or', 'age_info_id' => 6, 'age_info_id' => 9])->count();
                                  $menus_2lvl_celiac_organization_count = Menus::find()->Select(['organization_id'])->where(['organization_id' => $organization->id, 'status_archive' => '0', 'feeders_characters_id' => 6])->andWhere(['or', 'age_info_id' => 6, 'age_info_id' => 9])->count();
                                  $menus_2lvl_phenyl_organization_count = Menus::find()->Select(['organization_id'])->where(['organization_id' => $organization->id, 'status_archive' => '0', 'feeders_characters_id' => 7])->andWhere(['or', 'age_info_id' => 6, 'age_info_id' => 9])->count();
                                $menus_2lvl_cys_fibr_organization_count = Menus::find()->Select(['organization_id'])->where(['organization_id' => $organization->id, 'status_archive' => '0', 'feeders_characters_id' => 8])->andWhere(['or', 'age_info_id' => 6, 'age_info_id' => 9])->count();
                                //$menus_metabolic_organization_count = Menus::find()->Select(['organization_id'])->where(['organization_id' => $organization->id, 'status_archive' => '0', 'age_info_id' => 6, 'feeders_characters_id' => 9])->count();
                                $menus_2lvl_food_allergy_organization_count = Menus::find()->Select(['organization_id'])->where(['organization_id' => $organization->id, 'status_archive' => '0', 'feeders_characters_id' => 10])->andWhere(['or', 'age_info_id' => 6, 'age_info_id' => 9])->count();

                                $menus_2lvl_wf_municipality_count += $menus_2lvl_wf_organization_count;
                                $menus_2lvl_lim_pos_municipality_count += $menus_2lvl_lim_pos_organization_count;
                                $menus_2lvl_diabetes_municipality_count += $menus_2lvl_diabetes_organization_count;
                                $menus_2lvl_celiac_municipality_count += $menus_2lvl_celiac_organization_count;
                                $menus_2lvl_phenyl_municipality_count += $menus_2lvl_phenyl_organization_count;
                                $menus_2lvl_cys_fibr_municipality_count += $menus_2lvl_cys_fibr_organization_count;
                                //$menus_metabolic_municipality_count += $menus_metabolic_organization_count;
                                $menus_2lvl_food_allergy_municipality_count += $menus_2lvl_food_allergy_organization_count;
                            }
                            elseif ($array_org[$i][4] == 'Уровень 3 - Муниципальный орган управления образования') {
                                $organizations_3lvl_municipality_count++;

                                $orgs = Organization::find()->where(['municipality_id' => $organization->municipality_id])->all();
                                $org_mas = [];
                                foreach ($orgs as $org){
                                    $org_mas[] = $org->id;
                                }
                                $expenses_food_information = ExpensesFood::find()->where(['organization_id' => $org_mas])->count();
                            }
                            $expenses_food_municipality_information += $expenses_food_information;

                        }
                        //итоги по муниципальному
                        if ($organizations_1lvl_municipality_count or $organizations_2lvl_municipality_count or $organizations_3lvl_municipality_count) {
                            $table = '<tr>';
                            $table .= '<td>' .  ($number_of_municipality+1) . '</td>';
                            $table .= '<td colspan="1">' . $array_org[$m][2].'</td>';
                            $table .= '<td class="text-center align-middle">' . $organizations_1lvl_municipality_count . '</td>';
                            $table .= '<td colspan="1" class="text-center align-middle">' . $menu_1lvl_municipality_count . '</td>';
                            $table .= '<td colspan="1" class="text-center align-middle">' . $menus_1lvl_municipality_count . '</td>';
                            $table .= '<td colspan="1" class="text-center align-middle">' . $menus_1lvl_wf_municipality_count . '</td>';
                            $table .= '<td colspan="1" class="text-center align-middle">' . $menus_1lvl_lim_pos_municipality_count . '</td>';
                            $table .= '<td colspan="1" class="text-center align-middle">' . $menus_1lvl_diabetes_municipality_count . '</td>';
                            $table .= '<td colspan="1" class="text-center align-middle">' . $menus_1lvl_celiac_municipality_count . '</td>';
                            $table .= '<td colspan="1" class="text-center align-middle">' . $menus_1lvl_phenyl_municipality_count . '</td>';
                            $table .= '<td colspan="1" class="text-center align-middle">' . $menus_1lvl_cys_fibr_municipality_count . '</td>';
                            $table .= '<td colspan="1" class="text-center align-middle">' . $menus_1lvl_food_allergy_municipality_count . '</td>';
                            $table .= '<td colspan="1" class="text-center align-middle">' . $information_1lvl_inc_municipality100 . '</td>';
                            $table .= '<td colspan="1" class="text-center align-middle">' . $stolovaya_1lvl_information_inc_municipality100 . '</td>';
                            $table .= '<td colspan="1" class="text-center align-middle">' . $study_1lvl_information_inc_municipality100 . '</td>';
                            $table .= '<td colspan="1" class="text-center align-middle">' . $basic_1lvl_information_inc_municipality100 . '</td>';
                            $table .= '<td colspan="1" class="text-center align-middle">' . $education_1lvl_information_inc_municipality100 . '</td>';
                            $table .= '<td colspan="1" class="text-center align-middle">' . $school_break_1lvl_information_inc_municipality100 . '</td>';

                            $table .= '<td class="text-center align-middle">' . $organizations_2lvl_municipality_count . '</td>';
                            $table .= '<td colspan="1" class="text-center align-middle">' .  $menu_2lvl_municipality_count                   . '</td>';
                            $table .= '<td colspan="1" class="text-center align-middle">' . $menus_2lvl_municipality_count                  . '</td>';
                            $table .= '<td colspan="1" class="text-center align-middle">' . $menus_2lvl_wf_municipality_count                  . '</td>';
                            $table .= '<td colspan="1" class="text-center align-middle">' . $menus_2lvl_lim_pos_municipality_count          . '</td>';
                            $table .= '<td colspan="1" class="text-center align-middle">' . $menus_2lvl_diabetes_municipality_count         . '</td>';
                            $table .= '<td colspan="1" class="text-center align-middle">' . $menus_2lvl_celiac_municipality_count           . '</td>';
                            $table .= '<td colspan="1" class="text-center align-middle">' . $menus_2lvl_phenyl_municipality_count            . '</td>';
                            $table .= '<td colspan="1" class="text-center align-middle">' . $menus_2lvl_cys_fibr_municipality_count         . '</td>';
                            $table .= '<td colspan="1" class="text-center align-middle">' . $menus_2lvl_food_allergy_municipality_count         . '</td>';
                            $table .= '<td colspan="1" class="text-center align-middle">' . $information_2lvl_inc_municipality100 . '</td>';
                            $table .= '<td colspan="1" class="text-center align-middle">' . $stolovaya_2lvl_information_inc_municipality100 . '</td>';
                            $table .= '<td colspan="1" class="text-center align-middle">' . $study_2lvl_information_inc_municipality100 . '</td>';
                            $table .= '<td colspan="1" class="text-center align-middle">' . $basic_2lvl_information_inc_municipality100 . '</td>';
                            $table .= '<td colspan="1" class="text-center align-middle">' . $education_2lvl_information_inc_municipality100 . '</td>';
                            $table .= '<td colspan="1" class="text-center align-middle">' . $school_break_2lvl_information_inc_municipality100 . '</td>';

                            $table .= '<td class="text-center align-middle">' . $organizations_3lvl_municipality_count . '</td>';
                            $table .= '<td colspan="1" class="text-center align-middle">'.$expenses_food_municipality_information.'</td>';
                            $table .= '</tr>';
                            $number_of_municipality++;
                        }
                        else {
                            $table = '<tr class="main-color-9">';
                            $table .= '<td>' . ($number_of_municipality+1) . '</td>';
                            $table .= '<td colspan="2" class="text-center align-middle">' . $array_org[$m][2] . '</td>';
                            $table .= '<td colspan="34" class="text-center align-middle">Не найдено организаций в муниципальном образовании</td>';
                            $table .= '</tr>';
                            $table = '';
                        }
                        echo $table;
                        $organizations_1lvl_region_count += $organizations_1lvl_municipality_count;
                        $organizations_2lvl_region_count += $organizations_2lvl_municipality_count;
                        $organizations_3lvl_region_count += $organizations_3lvl_municipality_count;
                        $menus_1lvl_region_count += $menus_1lvl_municipality_count;
                                  $menus_1lvl_wf_region_count+=          $menus_1lvl_wf_municipality_count          ;
                             $menus_1lvl_lim_pos_region_count+=     $menus_1lvl_lim_pos_municipality_count     ;
                            $menus_1lvl_diabetes_region_count+=    $menus_1lvl_diabetes_municipality_count    ;
                              $menus_1lvl_celiac_region_count+=      $menus_1lvl_celiac_municipality_count      ;
                              $menus_1lvl_phenyl_region_count+=      $menus_1lvl_phenyl_municipality_count      ;
                            $menus_1lvl_cys_fibr_region_count+=    $menus_1lvl_cys_fibr_municipality_count    ;
                        $menus_1lvl_food_allergy_region_count+=$menus_1lvl_food_allergy_municipality_count;
                        $menu_1lvl_region_count += $menu_1lvl_municipality_count;
                        $menus_2lvl_region_count += $menus_2lvl_municipality_count;
                        $menus_2lvl_region_count+=             $menus_2lvl_municipality_count   ;
                        $menus_2lvl_wf_region_count+=          $menus_2lvl_wf_municipality_count          ;
                        $menus_2lvl_lim_pos_region_count+=     $menus_2lvl_lim_pos_municipality_count     ;
                        $menus_2lvl_diabetes_region_count+=    $menus_2lvl_diabetes_municipality_count    ;
                        $menus_2lvl_celiac_region_count+=      $menus_2lvl_celiac_municipality_count      ;
                        $menus_2lvl_phenyl_region_count+=      $menus_2lvl_phenyl_municipality_count      ;
                        $menus_2lvl_cys_fibr_region_count+=    $menus_2lvl_cys_fibr_municipality_count    ;
                        $menus_2lvl_food_allergy_region_count+=$menus_2lvl_food_allergy_municipality_count;
                        $menu_2lvl_region_count += $menu_2lvl_municipality_count;
                        $expenses_food_region_information += $expenses_food_municipality_information;
//                        $kids_1lvl_region_count += $kids_1lvl_municipality_count;
//                        $kid_1lvl_region_count += $kid_1lvl_municipality_count;
                        //$m++;
                    }

                    //итоги по региону
                    if ($organizations_1lvl_region_count or $organizations_2lvl_region_count or $organizations_3lvl_region_count) {
                        $table = '<tr class="main-color-9">';
                        $table .= '<td colspan="2" class="text-center align-middle">' . $model->get_region($region) . '</td>';
                        $table .= '<td colspan="1" class="text-center align-middle">' . $organizations_1lvl_region_count . '</td>';
                        $table .= '<td colspan="1" class="text-center align-middle">' .  $menu_1lvl_region_count . '</td>';
                        $table .= '<td colspan="1" class="text-center align-middle">' . $menus_1lvl_region_count . '</td>';
                        $table .= '<td colspan="1" class="text-center align-middle">' . $menus_1lvl_wf_region_count . '</td>';
                        $table .= '<td colspan="1" class="text-center align-middle">' . $menus_1lvl_lim_pos_region_count . '</td>';
                        $table .= '<td colspan="1" class="text-center align-middle">' . $menus_1lvl_diabetes_region_count . '</td>';
                        $table .= '<td colspan="1" class="text-center align-middle">' . $menus_1lvl_celiac_region_count . '</td>';
                        $table .= '<td colspan="1" class="text-center align-middle">' . $menus_1lvl_phenyl_region_count . '</td>';
                        $table .= '<td colspan="1" class="text-center align-middle">' . $menus_1lvl_cys_fibr_region_count . '</td>';
                        $table .= '<td colspan="1" class="text-center align-middle">' . $menus_1lvl_food_allergy_region_count . '</td>';
                        $table .= '<td colspan="1" class="text-center align-middle">' . $information_1lvl_inc_region100 . '</td>';
                        $table .= '<td colspan="1" class="text-center align-middle">' . $stolovaya_1lvl_information_inc_region100 . '</td>';
                        $table .= '<td colspan="1" class="text-center align-middle">' . $study_1lvl_information_inc_region100 . '</td>';
                        $table .= '<td colspan="1" class="text-center align-middle">' . $basic_1lvl_information_inc_region100 . '</td>';
                        $table .= '<td colspan="1" class="text-center align-middle">' . $education_1lvl_information_inc_region100 . '</td>';
                        $table .= '<td colspan="1" class="text-center align-middle">' . $school_break_1lvl_information_inc_region100 . '</td>';

                        $table .= '<td class="text-center align-middle">' . $organizations_2lvl_region_count . '</td>';
                        $table .= '<td colspan="1" class="text-center align-middle">' .  $menu_2lvl_region_count                   . '</td>';
                        $table .= '<td colspan="1" class="text-center align-middle">' . $menus_2lvl_region_count                  . '</td>';
                        $table .= '<td colspan="1" class="text-center align-middle">' . $menus_2lvl_wf_region_count                  . '</td>';
                        $table .= '<td colspan="1" class="text-center align-middle">' . $menus_2lvl_lim_pos_region_count          . '</td>';
                        $table .= '<td colspan="1" class="text-center align-middle">' . $menus_2lvl_diabetes_region_count         . '</td>';
                        $table .= '<td colspan="1" class="text-center align-middle">' . $menus_2lvl_celiac_region_count           . '</td>';
                        $table .= '<td colspan="1" class="text-center align-middle">' . $menus_2lvl_phenyl_region_count            . '</td>';
                        $table .= '<td colspan="1" class="text-center align-middle">' . $menus_2lvl_cys_fibr_region_count         . '</td>';
                        $table .= '<td colspan="1" class="text-center align-middle">' . $menus_2lvl_food_allergy_region_count         . '</td>';
                        $table .= '<td colspan="1" class="text-center align-middle">' . $information_2lvl_inc_region100 . '</td>';
                        $table .= '<td colspan="1" class="text-center align-middle">' . $stolovaya_2lvl_information_inc_region100 . '</td>';
                        $table .= '<td colspan="1" class="text-center align-middle">' . $study_2lvl_information_inc_region100 . '</td>';
                        $table .= '<td colspan="1" class="text-center align-middle">' . $basic_2lvl_information_inc_region100 . '</td>';
                        $table .= '<td colspan="1" class="text-center align-middle">' . $education_2lvl_information_inc_region100 . '</td>';
                        $table .= '<td colspan="1" class="text-center align-middle">' . $school_break_2lvl_information_inc_region100 . '</td>';

                        $table .= '<td class="text-center align-middle">' . $organizations_3lvl_region_count . '</td>';
                        $table .= '<td colspan="1" class="text-center align-middle">'.$expenses_food_region_information.'</td>';
                        $table .= '</tr>';
                    }
                    else {
                        $table = '<tr class="main-color-9">';
                        $table .= '<td colspan="2" class="text-center align-middle">' . $model->get_region($region) . '</td>';
                        $table .= '<td colspan="34" class="text-center align-middle">Не найдено организаций в регионе</td>';
                        $table .= '</tr>';
                        //$table = '';
                    }
                    echo $table;
                    $organizations_1lvl_district_count += $organizations_1lvl_region_count;
                    $organizations_2lvl_district_count += $organizations_2lvl_region_count;
                    $organizations_3lvl_district_count += $organizations_3lvl_region_count;
                    $menus_1lvl_district_count += $menus_1lvl_region_count;
                    $menus_1lvl_wf_district_count+=          $menus_1lvl_wf_region_count          ;
                    $menus_1lvl_lim_pos_district_count+=     $menus_1lvl_lim_pos_region_count     ;
                    $menus_1lvl_diabetes_district_count+=    $menus_1lvl_diabetes_region_count    ;
                    $menus_1lvl_celiac_district_count+=      $menus_1lvl_celiac_region_count      ;
                    $menus_1lvl_phenyl_district_count+=      $menus_1lvl_phenyl_region_count      ;
                    $menus_1lvl_cys_fibr_district_count+=    $menus_1lvl_cys_fibr_region_count    ;
                    $menus_1lvl_food_allergy_district_count+=$menus_1lvl_food_allergy_region_count;
                    $menu_1lvl_district_count += $menu_1lvl_region_count;
                    $menus_2lvl_district_count += $menus_2lvl_region_count;
                    $menus_2lvl_district_count+=             $menus_2lvl_region_count   ;
                    $menus_2lvl_wf_district_count+=          $menus_2lvl_wf_region_count          ;
                    $menus_2lvl_lim_pos_district_count+=     $menus_2lvl_lim_pos_region_count     ;
                    $menus_2lvl_diabetes_district_count+=    $menus_2lvl_diabetes_region_count    ;
                    $menus_2lvl_celiac_district_count+=      $menus_2lvl_celiac_region_count      ;
                    $menus_2lvl_phenyl_district_count+=      $menus_2lvl_phenyl_region_count      ;
                    $menus_2lvl_cys_fibr_district_count+=    $menus_2lvl_cys_fibr_region_count    ;
                    $menus_2lvl_food_allergy_district_count+=$menus_2lvl_food_allergy_region_count;
                    $menu_2lvl_district_count += $menu_2lvl_region_count;
                    $expenses_food_district_information += $expenses_food_region_information;
                }
                //итоги по округу
                //if ($prev_district != $cur_district) {
                $organizations_1lvl_all_count += $organizations_1lvl_district_count;
                $organizations_2lvl_all_count += $organizations_2lvl_district_count;
                $organizations_3lvl_all_count += $organizations_3lvl_district_count;
                $menus_1lvl_all_count+=             $menus_1lvl_district_count   ;
                $menus_1lvl_wf_all_count+=          $menus_1lvl_wf_district_count          ;
                $menus_1lvl_lim_pos_all_count+=     $menus_1lvl_lim_pos_district_count     ;
                $menus_1lvl_diabetes_all_count+=    $menus_1lvl_diabetes_district_count    ;
                $menus_1lvl_celiac_all_count+=      $menus_1lvl_celiac_district_count      ;
                $menus_1lvl_phenyl_all_count+=      $menus_1lvl_phenyl_district_count      ;
                $menus_1lvl_cys_fibr_all_count+=    $menus_1lvl_cys_fibr_district_count    ;
                $menus_1lvl_food_allergy_all_count+=$menus_1lvl_food_allergy_district_count;
                $menu_1lvl_all_count += $menu_1lvl_district_count;
                $menus_2lvl_all_count += $menus_2lvl_district_count;
                $menus_2lvl_all_count+=             $menus_2lvl_district_count   ;
                $menus_2lvl_wf_all_count+=          $menus_2lvl_wf_district_count          ;
                $menus_2lvl_lim_pos_all_count+=     $menus_2lvl_lim_pos_district_count     ;
                $menus_2lvl_diabetes_all_count+=    $menus_2lvl_diabetes_district_count    ;
                $menus_2lvl_celiac_all_count+=      $menus_2lvl_celiac_district_count      ;
                $menus_2lvl_phenyl_all_count+=      $menus_2lvl_phenyl_district_count      ;
                $menus_2lvl_cys_fibr_all_count+=    $menus_2lvl_cys_fibr_district_count    ;
                $menus_2lvl_food_allergy_all_count+=$menus_2lvl_food_allergy_district_count;
                $menu_2lvl_all_count += $menu_2lvl_district_count;
                $expenses_food_all_information += $expenses_food_district_information;
                if ($region_for_district == 0) {
                    $table = '<tr class="main-color-5">';
                    $table .= '<td colspan="2">' . $model->get_district($district->id) . '</td>';
                    $table .= '<td colspan="1" class="text-center align-middle">' . $organizations_1lvl_district_count . '</td>';
                    $table .= '<td colspan="1" class="text-center align-middle">' . $menu_1lvl_district_count . '</td>';
                    $table .= '<td colspan="1" class="text-center align-middle">' . $menus_1lvl_district_count . '</td>';
                    $table .= '<td colspan="1" class="text-center align-middle">' . $menus_1lvl_wf_district_count . '</td>';
                    $table .= '<td colspan="1" class="text-center align-middle">' . $menus_1lvl_lim_pos_district_count . '</td>';
                    $table .= '<td colspan="1" class="text-center align-middle">' . $menus_1lvl_diabetes_district_count . '</td>';
                    $table .= '<td colspan="1" class="text-center align-middle">' . $menus_1lvl_celiac_district_count . '</td>';
                    $table .= '<td colspan="1" class="text-center align-middle">' . $menus_1lvl_phenyl_district_count . '</td>';
                    $table .= '<td colspan="1" class="text-center align-middle">' . $menus_1lvl_cys_fibr_district_count . '</td>';
                    $table .= '<td colspan="1" class="text-center align-middle">' . $menus_1lvl_food_allergy_district_count . '</td>';
                    $table .= '<td colspan="1" class="text-center align-middle">' . $information_1lvl_inc_district100 . '</td>';
                    $table .= '<td colspan="1" class="text-center align-middle">' . $stolovaya_1lvl_information_inc_district100 . '</td>';
                    $table .= '<td colspan="1" class="text-center align-middle">' . $study_1lvl_information_inc_district100 . '</td>';
                    $table .= '<td colspan="1" class="text-center align-middle">' . $basic_1lvl_information_inc_district100 . '</td>';
                    $table .= '<td colspan="1" class="text-center align-middle">' . $education_1lvl_information_inc_district100 . '</td>';
                    $table .= '<td colspan="1" class="text-center align-middle">' . $school_break_1lvl_information_inc_district100 . '</td>';

                    $table .= '<td class="text-center align-middle">' . $organizations_2lvl_district_count . '</td>';
                    $table .= '<td colspan="1" class="text-center align-middle">' . $menu_2lvl_district_count . '</td>';
                    $table .= '<td colspan="1" class="text-center align-middle">' . $menus_2lvl_district_count . '</td>';
                    $table .= '<td colspan="1" class="text-center align-middle">' . $menus_2lvl_wf_district_count . '</td>';
                    $table .= '<td colspan="1" class="text-center align-middle">' . $menus_2lvl_lim_pos_district_count . '</td>';
                    $table .= '<td colspan="1" class="text-center align-middle">' . $menus_2lvl_diabetes_district_count . '</td>';
                    $table .= '<td colspan="1" class="text-center align-middle">' . $menus_2lvl_celiac_district_count . '</td>';
                    $table .= '<td colspan="1" class="text-center align-middle">' . $menus_2lvl_phenyl_district_count . '</td>';
                    $table .= '<td colspan="1" class="text-center align-middle">' . $menus_2lvl_cys_fibr_district_count . '</td>';
                    $table .= '<td colspan="1" class="text-center align-middle">' . $menus_2lvl_food_allergy_district_count . '</td>';
                    $table .= '<td colspan="1" class="text-center align-middle">' . $information_2lvl_inc_district100 . '</td>';
                    $table .= '<td colspan="1" class="text-center align-middle">' . $stolovaya_2lvl_information_inc_district100 . '</td>';
                    $table .= '<td colspan="1" class="text-center align-middle">' . $study_2lvl_information_inc_district100 . '</td>';
                    $table .= '<td colspan="1" class="text-center align-middle">' . $basic_2lvl_information_inc_district100 . '</td>';
                    $table .= '<td colspan="1" class="text-center align-middle">' . $education_2lvl_information_inc_district100 . '</td>';
                    $table .= '<td colspan="1" class="text-center align-middle">' . $school_break_2lvl_information_inc_district100 . '</td>';

                    $table .= '<td colspan="1" class="text-center align-middle">' . $organizations_3lvl_district_count . '</td>';
                    $table .= '<td colspan="1" class="text-center align-middle">'.$expenses_food_district_information.'</td>';
                    $table .= '</tr>';
                    echo $table;
                }
            }
            else {
                //нули по округам
                $table = '<tr class="main-color">';
                $table .= '<td colspan="1">' . $model->get_district($array_org[$k][0]) . '</td>';
                $table .= '<td colspan="34" class="text-center align-middle">0</td>';
                $table .= '</tr>';
                echo $table;
            }
        }
        if ($district_for_district == 0) {
            $table = '<tr class="main-color-7">';
            $table .= '<td colspan="2">Итого организаций: </td>';
            $table .= '<td colspan="1" class="text-center align-middle">' . $organizations_1lvl_all_count . '</td>';
            $table .= '<td colspan="1" class="text-center align-middle">' . $menu_1lvl_all_count . '</td>';
            $table .= '<td colspan="1" class="text-center align-middle">' . $menus_1lvl_all_count . '</td>';
            $table .= '<td colspan="1" class="text-center align-middle">' . $menus_1lvl_wf_all_count . '</td>';
            $table .= '<td colspan="1" class="text-center align-middle">' . $menus_1lvl_lim_pos_all_count . '</td>';
            $table .= '<td colspan="1" class="text-center align-middle">' . $menus_1lvl_diabetes_all_count . '</td>';
            $table .= '<td colspan="1" class="text-center align-middle">' . $menus_1lvl_celiac_all_count . '</td>';
            $table .= '<td colspan="1" class="text-center align-middle">' . $menus_1lvl_phenyl_all_count . '</td>';
            $table .= '<td colspan="1" class="text-center align-middle">' . $menus_1lvl_cys_fibr_all_count . '</td>';
            $table .= '<td colspan="1" class="text-center align-middle">' . $menus_1lvl_food_allergy_all_count . '</td>';
            $table .= '<td colspan="1" class="text-center align-middle">' . $information_1lvl_inc_all100 . '</td>';
            $table .= '<td colspan="1" class="text-center align-middle">' . $stolovaya_1lvl_information_inc_all100 . '</td>';
            $table .= '<td colspan="1" class="text-center align-middle">' . $study_1lvl_information_inc_all100 . '</td>';
            $table .= '<td colspan="1" class="text-center align-middle">' . $basic_1lvl_information_inc_all100 . '</td>';
            $table .= '<td colspan="1" class="text-center align-middle">' . $education_1lvl_information_inc_all100 . '</td>';
            $table .= '<td colspan="1" class="text-center align-middle">' . $school_break_1lvl_information_inc_all100 . '</td>';

            $table .= '<td class="text-center align-middle">' . $organizations_2lvl_all_count . '</td>';
            $table .= '<td colspan="1" class="text-center align-middle">' . $menu_2lvl_all_count . '</td>';
            $table .= '<td colspan="1" class="text-center align-middle">' . $menus_2lvl_all_count . '</td>';
            $table .= '<td colspan="1" class="text-center align-middle">' . $menus_2lvl_wf_all_count . '</td>';
            $table .= '<td colspan="1" class="text-center align-middle">' . $menus_2lvl_lim_pos_all_count . '</td>';
            $table .= '<td colspan="1" class="text-center align-middle">' . $menus_2lvl_diabetes_all_count . '</td>';
            $table .= '<td colspan="1" class="text-center align-middle">' . $menus_2lvl_celiac_all_count . '</td>';
            $table .= '<td colspan="1" class="text-center align-middle">' . $menus_2lvl_phenyl_all_count . '</td>';
            $table .= '<td colspan="1" class="text-center align-middle">' . $menus_2lvl_cys_fibr_all_count . '</td>';
            $table .= '<td colspan="1" class="text-center align-middle">' . $menus_2lvl_food_allergy_all_count . '</td>';
            $table .= '<td colspan="1" class="text-center align-middle">' . $information_2lvl_inc_all100 . '</td>';
            $table .= '<td colspan="1" class="text-center align-middle">' . $stolovaya_2lvl_information_inc_all100 . '</td>';
            $table .= '<td colspan="1" class="text-center align-middle">' . $study_2lvl_information_inc_all100 . '</td>';
            $table .= '<td colspan="1" class="text-center align-middle">' . $basic_2lvl_information_inc_all100 . '</td>';
            $table .= '<td colspan="1" class="text-center align-middle">' . $education_2lvl_information_inc_all100 . '</td>';
            $table .= '<td colspan="1" class="text-center align-middle">' . $school_break_2lvl_information_inc_all100 . '</td>';

            $table .= '<td colspan="1" class="text-center align-middle">' . $organizations_3lvl_all_count . '</td>';
            $table .= '<td colspan="1" class="text-center align-middle">'.$expenses_food_all_information.'</td>';
            $table .= '</tr>';
            echo $table;
        }
        ?>

        </tbody>
    </table>
    <?
}
?>
<?
$script = <<< JS
$('#organization-federal_district_id').change(function() {
    var value = $('#organization-federal_district_id option:selected').val();
    $.ajax({
         url: "../organizations/search",
              type: "GET",      // тип запроса
              data: { // действия
                  'id': value
              },
              // Данные пришли
              success: function( data ) {
                    $("#organization-region_id").empty();
                    $("#organization-region_id").append(data);
                    $("#organization-municipality_id").empty();
                    $('#organization-municipality_id').append('<option value="0">Все</option>');
              },
              error: function(err) {
                 console.log(err);
              }
         })
});
$('#organization-region_id').change(function() {
    var value1 = $('#organization-region_id option:selected').val();
    $.ajax({
         url: "../organizations/search-municipality",
              type: "GET",      // тип запроса
              data: { // действия
                  'id': value1
              },
              // Данные пришли
              success: function( data1 ) {
                  $("#organization-municipality_id").empty();
                  $("#organization-municipality_id").append(data1);
              },
              error: function(err) {
                 console.log(err);
              }
         })
});
JS;

$this->registerJs($script, yii\web\View::POS_READY);
?>




