<?php

use common\models\Kids;
use common\models\Medicals;
use common\models\Menus;
use common\models\Municipality;
use common\models\TypeLager;
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

<div class="users-report-form container"><h2 align="center">Отчет по регистрациям (ПС "Оценка эффективности
        оздоровления")</h2>
    <?php
    $form = ActiveForm::begin();
    $federal_district = FederalDistrict::find()->all();
    $federal_district_item = ArrayHelper::map($federal_district, 'id', 'name');

    //$region = Region::find()->where(['district_id' => '1'])->all();
    //$region_item = ArrayHelper::map($region, 'id', 'name');

    //$municipality = Municipality::find()->where(['region_id' => '1'])->all();
    //$municipality_item = ArrayHelper::map($municipality, 'id', 'name');


    $type_lager = TypeLager::find()->all();
    $type_lager_item = ArrayHelper::map($type_lager, 'id', 'name');

    /* $physical_evolution_null = array('0' => 'Все');
     $physical_evolution_items = ArrayHelper::merge($physical_evolution_null, $physical_evolution_items);
     */

    //exit;
    //не пришли с контроллера

    if (empty($district_for_district) && empty($region_for_district) && empty($municipality_for_region))
    {
        $district_for_district = 0;
        $region_for_district = 0;
        $municipality_for_region = 0;
    }

    if (empty($type_lager_key))
    {
        $type_lager_key = 0;
    }

    $two_column = [
        'options' => ['class' => 'row mt-3'],
        'labelOptions' => ['class' => 'col-4 col-form-label font-weight-bold']
    ];

    $federal_district_item['0'] = 'Все';
    $region_item['0'] = 'Все';
    $municipality_item['0'] = 'Все';
    $type_lager_item['0'] = 'Все';
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
    echo $form
        ->field($model, 'type_lager_id', $two_column)
        ->dropDownList($type_lager_item,
            [
                'options' => [$type_lager_key => ['Selected' => true]],
                'class' => 'form-control col-8'
            ]);
    ?>
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
        <tr>
            <th class="text-center" rowspan="2" colspan="1">№</th>
            <th class="text-center" rowspan="2" colspan="1">Федеральный округ</th>
            <th class="text-center" rowspan="2" colspan="1">Субъект Федерации</th>
            <th class="text-center" rowspan="2" colspan="1">Муниципальное образование</th>
            <th class="text-center" rowspan="2" colspan="1">Наименование учреждения</th>
            <th class="text-center" rowspan="2" colspan="1">Тип организации</th>
            <th class="text-center" rowspan="2" colspan="1">Тип лагеря</th>
            <th class="text-center" rowspan="2" colspan="1">Дата регистрации</th>
            <th class="text-center" rowspan="2" colspan="1">Последний вход в систему</th>
            <!--<th class="text-center" rowspan="1" colspan="2">Настройки программы</th>-->
            <!--<th class="text-center" rowspan="2" colspan="1">Внесено меню всего</th>-->
            <th class="text-center" rowspan="1" colspan="4">1 смена</th>
            <th class="text-center" rowspan="1" colspan="4">2 смена</th>
            <th class="text-center" rowspan="1" colspan="4">3 смена</th>
            <th class="text-center" rowspan="1" colspan="4">4 смена</th>
            <th class="text-center" rowspan="1" colspan="4">5 смена</th>
            <th class="text-center" rowspan="1" colspan="4">6 смена</th>
            <th class="text-center" rowspan="1" colspan="3">Все смены</th>
        </tr>
        <tr>
            <!--
            <th class="text-center" rowspan="1" colspan="1">Питания</th>
            <th class="text-center" rowspan="1" colspan="1">Мед. обслуживание</th>
            -->
            <?php
            for ($i = 0; $i < 6; $i++)
            {
                echo '<th class="text-center" rowspan="1" colspan="1">Дата начала смены</th>';
                echo '<th class="text-center" rowspan="1" colspan="1">Внесено детей</th>';
                echo '<th class="text-center" rowspan="1" colspan="1">Заполнен 1 медосмотр</th>';
                echo '<th class="text-center" rowspan="1" colspan="1">Заполнен 2 медосмотр</th>';
            }
            ?>
            <th class="text-center" rowspan="1" colspan="1">Внесено детей</th>
            <th class="text-center" rowspan="1" colspan="1">Заполнен 1 медосмотр</th>
            <th class="text-center" rowspan="1" colspan="1">Заполнен 2 медосмотр</th>
            <!--
            <th class="text-center" rowspan="1" colspan="1">Телефон организации</th>
            <th class="text-center" rowspan="1" colspan="1">Телефон сотрудника</th>
            -->
        </tr>
        </thead>
        <tbody>
        <?php
        $array_org = array();
        $array_org[] = array();
        $i = 0;
        $j = 0;
        $k = 0;


        /*
         if ($region_for_district == 0) {
             $organizationWhere = [
                 //'organization_id' => Yii::$app->user->identity->organization_id,
                 'federal_district_id' => $district->id,
             ];
         }
         else {

         }

         */
        $type_lager_key = ($type_lager_key == '0' ? ('') : $type_lager_key);
        $municipality_for_region = ($municipality_for_region == '0' ? ('') : $municipality_for_region);
        $cur_district = 0;
        $prev_district = 0;

        $menus_all_count = [];
        $menu_all_count = [];
        $kids_all_count = [];
        $kids_all_med1_count = [];
        $kids_all_med2_count = [];
        $kids_all_count_all = 0;
        $kids_all_med1_count_all = 0;
        $kids_all_med2_count_all = 0;
        //print_r($type_lager_key);
        foreach ($districts as $district)
        {
            //echo "$region_for_district";
            $cur_district = $district->id;
            if ($region_for_district == 0)
            {
                $regions = Region::find()
                    ->select('id')
                    ->where(['district_id' => $district->id])
                    ->andWhere(['not in', 'id', [1, 2, 3, 4, 5, 7, 8, 9, 10, 11, 12, 13, 14, 15, 17, 18, 19, 20, 21, 22, 23, 24, 25, 27, 28, 30, 31, 32, 33, 34, 35, 36, 37, 38, 39, 40, 41, 42, 43, 46, 47, 51, 52, 53, 55, 56, 57, 58, 59, 60, 62, 63, 64, 65, 66, 67, 71, 72, 73, 74, 75, 77, 78, 79, 80, 81, 82, 83, 85]])
                    ->all();//получили все регионы по всем округам
            }
            else
            {
                $regions = Region::find()
                    ->select('id')
                    ->where(['district_id' => $district->id, 'id' => $region_for_district])
                    ->all();//получили регион
            }
            //$regions);
            if ($regions)
            {
                $count_organizations_all = 0;
                $prev_region = 0;
                $cur_region = 0;

                $menus_district_count = [];
                $menu_district_count = [];
                $kids_district_count = [];
                $kids_district_med1_count = [];
                $kids_district_med2_count = [];
                $kids_district_count_all = 0;
                $kids_district_med1_count_all = 0;
                $kids_district_med2_count_all = 0;
                foreach ($regions as $region)//цикл по регионам
                {
                    $cur_region = $region->id;
                    //убраны лагеря
                    //город Новосибирск 10
                    //Приморский край - Спасский муниципальный район - ДОЛ Горный 156
                    //Приморский край - Спасский муниципальный район - ДОЛ Горный 156
                    //Иркутская область - город Иркутск - ОГКУ СО "ЦПД Ленинского района г. Иркутска" 28
                    //Иркутская область - Шелеховский муниципальный район - Областное государственное казённое учреждение социального обслуживания" Центр помощи детям, оставшимся без попечения родителей, г.Шелехова" 143
                    //Иркутская область - Ангарское городское МО - ДЛО Звездный
                    $organizations = Organization::find()
                        ->where(['region_id' => $region->id])
                        ->andWhere(['type_org' => 1])
                        ->andWhere(['not in', 'id', [10, 156, 143, 30]])
                        ->andFilterWhere(['type_lager_id' => $type_lager_key])
                        ->andFilterWhere(['municipality_id' => $municipality_for_region])
                        ->all();
                    $count_organizations = Organization::find()
                        ->where(['region_id' => $region->id])
                        ->andWhere(['type_org' => 1])
                        ->andWhere(['not in', 'id', [10, 156, 143, 134, 30]])
                        ->andFilterWhere(['type_lager_id' => $type_lager_key])
                        ->andFilterWhere(['municipality_id' => $municipality_for_region])
                        ->count();
                    $count_organizations_all += $count_organizations;
                    $menus_region_count = [];
                    $menu_region_count = [];
                    $kids_region_count = [];
                    $kids_region_med1_count = [];
                    $kids_region_med2_count = [];
                    $kids_region_count_all = 0;
                    $kids_region_med1_count_all = 0;
                    $kids_region_med2_count_all = 0;
                    //$type_user = \common\models\AuthAssignment::find()->select(['item_name'])->where(['user_id' => $user_id]);
                    foreach ($organizations as $organization)
                    {
                        //$user_id = User::find()->select(['id'])->where(['organization_id' => $organization->id]);
                        //print_r($user_id);
                        //$type_user = \common\models\AuthAssignment::find()->select(['item_name'])->where(['user_id' => $user_id]);
                        $array_org[$i][0] = $organization->federal_district_id;
                        $array_org[$j][0] = $organization->federal_district_id;
                        $array_org[$k][0] = $organization->federal_district_id;
                        $array_org[$i][1] = $organization->region_id;
                        $array_org[$j][1] = $organization->region_id;
                        $array_org[$i][3] = $organization->short_title;
                        $array_org[$i][2] = Municipality::findOne($organization->municipality_id)->name;
                        $array_org[$i][4] = $organization->get_type_org($organization->type_org);
                        $array_org[$i][5] = $organization->get_type_lager($organization->type_lager_id);
                        $array_org[$i][6] = $organization->created_at;
                        //$array_org[$i][7] = $organization->organizator_food;
                        //$array_org[$i][8] = $organization->medic_service_programm;

                        $table = '<tr>';
                        $table .= '<td>' . ($i + 1) . '</td>';
                        $table .= '<td>' . $model->get_district($array_org[$i][0]) . '</td>';
                        $table .= '<td>' . $model->get_region($array_org[$i][1]) . '</td>';
                        $table .= '<td class="text-center">' . $array_org[$i][2] . '</td>';
                        $table .= '<td class="text-center">' . (isset($array_org[$i][3]) ? $array_org[$i][3] : '-') . '</td>';
                        $table .= '<td class="text-center">' . (isset($array_org[$i][4]) ? $array_org[$i][4] : '-') . '</td>';
                        $table .= '<td class="text-center">' . (isset($array_org[$i][5]) ? $array_org[$i][5] : '-') . '</td>';
                        $table .= '<td class="text-center">' . (isset($array_org[$i][6]) ? $array_org[$i][6] : '-') . '</td>';
                        //$table .= '<td class="text-center">' . ($array_org[$i][7] == '0' ? 'Краткий' : 'Полный') . '</td>';
                        //$table .= '<td class="text-center">' . ($array_org[$i][8] == '0' ? 'Полный' : 'Краткий') . '</td>';


                        $user_id_from_organization = User::find()
                            ->where(['organization_id' => $organization->id])->one();
                        $user_autorization_statistic = \common\models\UserAutorizationStatistic::find()
                            ->where(['user_id' => $user_id_from_organization->id])->one();
                        $table .= '<td class="text-center">' . $user_autorization_statistic->created_at . '</td>';

                        $menus_organization_count[1] = Menus::find()
                            ->Select(['organization_id'])
                            ->where(['organization_id' => $organization->id, 'status_archive' => '0'])
                            ->count();
                        $menus_region_count[1] += $menus_organization_count[1];
                        $menu_region_count[1] += Menus::find()
                            ->Select(['organization_id'])
                            ->where(['organization_id' => $organization->id, 'status_archive' => '0'])
                            ->groupBy('organization_id')
                            ->count();
                        //$table .= '<td class="text-center">' . $menus_organization_count[1] . '</td>';
                        $kids_organization_count = [];
                        $kids_organization_med1_count = [];
                        $kids_organization_med2_count = [];
                        for ($j = 1; $j < 7; $j++)
                        {
                            $fact_inf_camp_smena = \common\models\FactInfCamp::find()
                                ->where(['organization_id' => $organization->id, 'year' => 2021, 'change' => $j])->one();
                            if ($fact_inf_camp_smena->start_date == '')
                            {
                                $fact_inf_camp_smena_j[$j] = 'Фактическая информация не внесена';
                            }
                            else
                            {
                                $fact_inf_camp_smena_j[$j] = $fact_inf_camp_smena->start_date;
                            }
                            //print_r($fact_inf_camp_smena_j[$j]);print_r('<br>');
                            $kids_organization_count[$j] = Kids::find()
                                ->Select(['organization_id'])
                                ->where(['organization_id' => $organization->id, 'change_camp' => $j, 'year' => 2021])
                                ->count();
                            $kids_region_count[$j] += $kids_organization_count[$j];

                            $lkid_id = Kids::find()
                                ->Select(['id'])
                                ->where(['organization_id' => $organization->id, 'change_camp' => $j, 'year' => 2021])->all();
                            $ids = [];
                            foreach ($lkid_id as $h)
                            {
                                $ids[] = $h->id;
                            }
                            //print_r($ids);
                            //exit;
                            $kids_organization_med1_count[$j] = Medicals::find()
                                ->Select(['id'])
                                ->where(['kids_id' => $ids, 'number_med' => 0])
                                ->count();
                            $kids_organization_med2_count[$j] = Medicals::find()
                                ->Select(['id'])
                                ->where(['kids_id' => $ids, 'number_med' => 1])
                                ->count();
                            $kids_region_med1_count[$j] += $kids_organization_med1_count[$j];
                            $kids_region_med2_count[$j] += $kids_organization_med2_count[$j];
                        }

                        $user_phone = User::find()
                            ->Select(['phone'])
                            ->where(['organization_id' => $organization->id])
                            ->one();
                        $table .= '<td colspan="1" class="text-center">' . $fact_inf_camp_smena_j[1] . '</td>';

                        $table .= '<td colspan="1" class="text-center">' . $kids_organization_count[1] . '</td>';
                        $table .= '<td colspan="1" class="text-center">' . $kids_organization_med1_count[1] . '</td>';
                        $table .= '<td colspan="1" class="text-center">' . $kids_organization_med2_count[1] . '</td>';
//
                        $table .= '<td colspan="1" class="text-center">' . $fact_inf_camp_smena_j[2] . '</td>';
                        $table .= '<td colspan="1" class="text-center">' . $kids_organization_count[2] . '</td>';
                        $table .= '<td colspan="1" class="text-center">' . $kids_organization_med1_count[2] . '</td>';
                        $table .= '<td colspan="1" class="text-center">' . $kids_organization_med2_count[2] . '</td>';

                        $table .= '<td colspan="1" class="text-center">' . $fact_inf_camp_smena_j[3] . '</td>';
                        $table .= '<td colspan="1" class="text-center">' . $kids_organization_count[3] . '</td>';
                        $table .= '<td colspan="1" class="text-center">' . $kids_organization_med1_count[3] . '</td>';
                        $table .= '<td colspan="1" class="text-center">' . $kids_organization_med2_count[3] . '</td>';

                        $table .= '<td colspan="1" class="text-center">' . $fact_inf_camp_smena_j[4] . '</td>';
                        $table .= '<td colspan="1" class="text-center">' . $kids_organization_count[4] . '</td>';
                        $table .= '<td colspan="1" class="text-center">' . $kids_organization_med1_count[4] . '</td>';
                        $table .= '<td colspan="1" class="text-center">' . $kids_organization_med2_count[4] . '</td>';

                        $table .= '<td colspan="1" class="text-center">' . $fact_inf_camp_smena_j[5] . '</td>';
                        $table .= '<td colspan="1" class="text-center">' . $kids_organization_count[5] . '</td>';
                        $table .= '<td colspan="1" class="text-center">' . $kids_organization_med1_count[5] . '</td>';
                        $table .= '<td colspan="1" class="text-center">' . $kids_organization_med2_count[5] . '</td>';

                        $table .= '<td colspan="1" class="text-center">' . $fact_inf_camp_smena_j[6] . '</td>';
                        $table .= '<td colspan="1" class="text-center">' . $kids_organization_count[6] . '</td>';
                        $table .= '<td colspan="1" class="text-center">' . $kids_organization_med1_count[6] . '</td>';
                        $table .= '<td colspan="1" class="text-center">' . $kids_organization_med2_count[6] . '</td>';

                        $kids_organization_count_all = 0;
                        for ($p = 0; $p < count($kids_organization_count); $p++)
                        {
                            $kids_organization_count_all += $kids_organization_count[$p];
                        }
                        $table .= '<td colspan="1" class="text-center">' . $kids_organization_count_all . '</td>';
                        $kids_organization_med1_count_all = 0;
                        for ($p = 0; $p < count($kids_organization_med1_count); $p++)
                        {
                            $kids_organization_med1_count_all += $kids_organization_med1_count[$p];
                        }
                        $table .= '<td colspan="1" class="text-center">' . $kids_organization_med1_count_all . '</td>';
                        $kids_organization_med2_count_all = 0;
                        for ($p = 0; $p < count($kids_organization_med2_count); $p++)
                        {
                            $kids_organization_med2_count_all += $kids_organization_med2_count[$p];
                        }
                        $table .= '<td colspan="1" class="text-center">' . $kids_organization_med2_count_all . '</td>';
                        $kids_region_count_all += $kids_organization_count_all;
                        $kids_region_med1_count_all += $kids_organization_med1_count_all;
                        $kids_region_med2_count_all += $kids_organization_med2_count_all;
                        $table .= '</tr>';
                        echo $table;
                        $i++;
                    }
                    //итоги по региону
                    for ($j = 1; $j < 7; $j++)
                    {
                        $menus_district_count[$j] += $menus_region_count[$j];
                        $menu_district_count[$j] += $menu_region_count[$j];
                        $kids_district_count[$j] += $kids_region_count[$j];
                        $kids_district_med1_count [$j] += $kids_region_med1_count[$j];
                        $kids_district_med2_count [$j] += $kids_region_med2_count[$j];
                    }


                    if ($count_organizations)
                    {
                        $table = '<tr class="main-color-3">';
                        $table .= '<td></td>';
                        $table .= '<td>' . $model->get_district($district->id) . '</td>';
                        $table .= '<td>' . $model->get_region($region) . '</td>';
                        $table .= '<td class="text-center align-middle">' . $count_organizations . '</td>';
                        $table .= '<td colspan="5" class="text-center align-middle"></td>';
                        $table .= '<td colspan="1" class="text-center align-middle"></td>';
                        $table .= '<td class="text-center align-middle">' . $kids_region_count[1] . '</td>';
                        $table .= '<td class="text-center align-middle">' . $kids_region_med1_count[1] . '</td>';
                        $table .= '<td class="text-center align-middle">' . $kids_region_med2_count[1] . '</td>';
                        $table .= '<td colspan="1" class="text-center align-middle"></td>';
                        $table .= '<td class="text-center align-middle">' . $kids_region_count[2] . '</td>';
                        $table .= '<td class="text-center align-middle">' . $kids_region_med1_count[2] . '</td>';
                        $table .= '<td class="text-center align-middle">' . $kids_region_med2_count[2] . '</td>';
                        $table .= '<td colspan="1" class="text-center align-middle"></td>';
                        $table .= '<td class="text-center align-middle">' . $kids_region_count[3] . '</td>';
                        $table .= '<td class="text-center align-middle">' . $kids_region_med1_count[3] . '</td>';
                        $table .= '<td class="text-center align-middle">' . $kids_region_med2_count[3] . '</td>';
                        $table .= '<td colspan="1" class="text-center align-middle"></td>';
                        $table .= '<td class="text-center align-middle">' . $kids_region_count[4] . '</td>';
                        $table .= '<td class="text-center align-middle">' . $kids_region_med1_count[4] . '</td>';
                        $table .= '<td class="text-center align-middle">' . $kids_region_med2_count[4] . '</td>';
                        $table .= '<td colspan="1" class="text-center align-middle"></td>';
                        $table .= '<td class="text-center align-middle">' . $kids_region_count[5] . '</td>';
                        $table .= '<td class="text-center align-middle">' . $kids_region_med1_count[5] . '</td>';
                        $table .= '<td class="text-center align-middle">' . $kids_region_med2_count[5] . '</td>';
                        $table .= '<td colspan="1" class="text-center align-middle"></td>';
                        $table .= '<td class="text-center align-middle">' . $kids_region_count[6] . '</td>';
                        $table .= '<td class="text-center align-middle">' . $kids_region_med1_count[6] . '</td>';
                        $table .= '<td class="text-center align-middle">' . $kids_region_med2_count[6] . '</td>';

                        $table .= '<td class="text-center align-middle">' . $kids_region_count_all . '</td>';
                        $table .= '<td class="text-center align-middle">' . $kids_region_med1_count_all . '</td>';
                        $table .= '<td class="text-center align-middle">' . $kids_region_med2_count_all . '</td>';

                        $table .= '</tr>';
                    }
                    else
                    {
                        $table = '<tr class="main-color-3">';
                        $table .= '<td></td>';
                        $table .= '<td>' . $model->get_district($district->id) . '</td>';
                        $table .= '<td>' . $model->get_region($region) . '</td>';
                        $table .= '<td colspan="33" class="text-center align-middle">Организации не зарегистрированы</td>';
                        $table .= '</tr>';
                    }
                    echo $table;
                    $kids_district_count_all += $kids_region_count_all;
                    $kids_district_med1_count_all += $kids_region_med1_count_all;
                    $kids_district_med2_count_all += $kids_region_med2_count_all;
                }
                //итоги по округу
                for ($j = 1; $j < 7; $j++)
                {
                    $menus_all_count[$j] += $menus_district_count[$j];
                    $menu_all_count[$j] += $menu_district_count[$j];
                    $kids_all_count[$j] += $kids_district_count[$j];
                    $kids_all_med1_count[$j] += $kids_district_med1_count[$j];
                    $kids_all_med2_count[$j] += $kids_district_med2_count[$j];
                }
                //if ($prev_district != $cur_district) {
                $table = '<tr class="main-color">';
                $table .= '<td></td>';
                $table .= '<td colspan="2">' . $model->get_district($district->id) . '</td>';
                $table .= '<td class="text-center align-middle">' . $count_organizations_all . '</td>';
                $table .= '<td colspan="5" class="text-center align-middle"></td>';
                $table .= '<td colspan="1" class="text-center align-middle"></td>';
                $table .= '<td class="text-center align-middle">' . $kids_district_count[1] . '</td>';
                $table .= '<td class="text-center align-middle">' . $kids_district_med1_count[1] . '</td>';
                $table .= '<td class="text-center align-middle">' . $kids_district_med2_count[1] . '</td>';
                $table .= '<td colspan="1" class="text-center align-middle"></td>';
                $table .= '<td class="text-center align-middle">' . $kids_district_count[2] . '</td>';
                $table .= '<td class="text-center align-middle">' . $kids_district_med1_count[2] . '</td>';
                $table .= '<td class="text-center align-middle">' . $kids_district_med2_count[2] . '</td>';
                $table .= '<td colspan="1" class="text-center align-middle"></td>';
                $table .= '<td class="text-center align-middle">' . $kids_district_count[3] . '</td>';
                $table .= '<td class="text-center align-middle">' . $kids_district_med1_count[3] . '</td>';
                $table .= '<td class="text-center align-middle">' . $kids_district_med2_count[3] . '</td>';
                $table .= '<td colspan="1" class="text-center align-middle"></td>';
                $table .= '<td class="text-center align-middle">' . $kids_district_count[4] . '</td>';
                $table .= '<td class="text-center align-middle">' . $kids_district_med1_count[4] . '</td>';
                $table .= '<td class="text-center align-middle">' . $kids_district_med2_count[4] . '</td>';
                $table .= '<td colspan="1" class="text-center align-middle"></td>';
                $table .= '<td class="text-center align-middle">' . $kids_district_count[5] . '</td>';
                $table .= '<td class="text-center align-middle">' . $kids_district_med1_count[5] . '</td>';
                $table .= '<td class="text-center align-middle">' . $kids_district_med2_count[5] . '</td>';
                $table .= '<td colspan="1" class="text-center align-middle"></td>';
                $table .= '<td class="text-center align-middle">' . $kids_district_count[6] . '</td>';
                $table .= '<td class="text-center align-middle">' . $kids_district_med1_count[6] . '</td>';
                $table .= '<td class="text-center align-middle">' . $kids_district_med2_count[6] . '</td>';
                $table .= '<td class="text-center align-middle">' . $kids_district_count_all . '</td>';
                $table .= '<td class="text-center align-middle">' . $kids_district_med1_count_all . '</td>';
                $table .= '<td class="text-center align-middle">' . $kids_district_med2_count_all . '</td>';
                //$table .= '</tr>';
                //}
                //print_r('prev_district: ' . $prev_district .' ------------- ');
                //print_r('cur_district: ' . $cur_district .'<br>');
                echo $table;
                $kids_all_count_all += $kids_district_count_all;
                $kids_all_med1_count_all += $kids_district_med1_count_all;
                $kids_all_med2_count_all += $kids_district_med2_count_all;
            }

        }
        $table = '<tr class="main-color-2">';
        $table .= '<td></td>';
        $table .= '<td colspan="2">Итого</td>';
        $table .= '<td class="text-center align-middle">' . (($i == 0) ? 0 : count($array_org)) . '</td>';
        $table .= '<td colspan="6" class="text-center align-middle"></td>';
        $table .= '<td class="text-center align-middle">' . $kids_all_count[1] . '</td>';
        $table .= '<td class="text-center align-middle">' . $kids_all_med1_count[1] . '</td>';
        $table .= '<td class="text-center align-middle">' . $kids_all_med2_count[1] . '</td>';
        $table .= '<td colspan="1" class="text-center align-middle"></td>';
        $table .= '<td class="text-center align-middle">' . $kids_all_count[2] . '</td>';
        $table .= '<td class="text-center align-middle">' . $kids_all_med1_count[2] . '</td>';
        $table .= '<td class="text-center align-middle">' . $kids_all_med2_count[2] . '</td>';
        $table .= '<td colspan="1" class="text-center align-middle"></td>';
        $table .= '<td class="text-center align-middle">' . $kids_all_count[3] . '</td>';
        $table .= '<td class="text-center align-middle">' . $kids_all_med1_count[3] . '</td>';
        $table .= '<td class="text-center align-middle">' . $kids_all_med2_count[3] . '</td>';
        $table .= '<td colspan="1" class="text-center align-middle"></td>';
        $table .= '<td class="text-center align-middle">' . $kids_all_count[4] . '</td>';
        $table .= '<td class="text-center align-middle">' . $kids_all_med1_count[4] . '</td>';
        $table .= '<td class="text-center align-middle">' . $kids_all_med2_count[4] . '</td>';
        $table .= '<td colspan="1" class="text-center align-middle"></td>';
        $table .= '<td class="text-center align-middle">' . $kids_all_count[5] . '</td>';
        $table .= '<td class="text-center align-middle">' . $kids_all_med1_count[5] . '</td>';
        $table .= '<td class="text-center align-middle">' . $kids_all_med2_count[5] . '</td>';
        $table .= '<td colspan="1" class="text-center align-middle"></td>';
        $table .= '<td class="text-center align-middle">' . $kids_all_count[6] . '</td>';
        $table .= '<td class="text-center align-middle">' . $kids_all_med1_count[6] . '</td>';
        $table .= '<td class="text-center align-middle">' . $kids_all_med2_count[6] . '</td>';
        $table .= '<td class="text-center align-middle">' . $kids_all_count_all . '</td>';
        $table .= '<td class="text-center align-middle">' . $kids_all_med1_count_all . '</td>';
        $table .= '<td class="text-center align-middle">' . $kids_all_med2_count_all . '</td>';
        $table .= '</tr>';
        echo $table;
        ?>

        </tbody>
    </table>
    <?
}
?>
<?
//print_r('$district_for_district: ' . $district_for_district . '<br>');
//print_r('$region_for_district: ' . $region_for_district . '<br>');
//print_r('$municipality_for_region: ' . $municipality_for_region . '<br>');
//print_r('$type_lager_key: ' . $type_lager_key . '<br>');

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




