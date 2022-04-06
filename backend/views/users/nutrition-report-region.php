<?php

use common\models\Kids;
use common\models\Menus;
use common\models\Municipality;
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

<div class="users-nutrition-report-region-form container"><h2 align="center">Отчет по регистрациям (ПС "Питание") -
        регионы</h2>
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
    if (empty($type_org_key)) {
        $type_org_key = 0;
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
    $unset_organization = [1, 6];
    for ($i = 0; $i < count($unset_organization); $i++) {
        unset($type_org_item[$unset_organization[$i]]);
    }
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
    echo $form
        ->field($model, 'type_org', $two_column)
        ->dropDownList($type_org_item,
            [
                'options' => [$type_org_key => ['Selected' => true]],
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
        <tr class="main-color-7">
            <th class="text-center" rowspan="1" colspan="1">Территория</th>
            <th class="text-center" rowspan="1" colspan="1">Количество организаций всего</th>
            <th class="text-center" rowspan="1" colspan="1">Количество организаций, внесших меню</th>
            <th class="text-center" rowspan="1" colspan="1">Внесено меню всего</th>
            <th class="text-center" rowspan="1" colspan="1">Количество организаций, внесших детей</th>
            <th class="text-center" rowspan="1" colspan="1">Внесено детей всего</th>
            <th class="text-center" rowspan="1" colspan="1">Заполнена общая информация полностью</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $array_org = array();
        $array_org[] = array();
        $i = 0;
        $j = 0;
        $k = 0;
        $type_org_key = ($type_org_key == '0' ? ('') : $type_org_key);
        $municipality_for_region = ($municipality_for_region == '0' ? ('') : $municipality_for_region);
        $cur_district = 0;
        $prev_district = 0;
        //print_r($type_org_key);
        $menus_all_count = 0;
        $menu_all_count = 0;
        $kids_all_count = 0;
        $kid_all_count = 0;
        $basic_inc_all100 = 0;
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
                $menus_district_count = 0;
                $menu_district_count = 0;
                $kids_district_count = 0;
                $kid_district_count = 0;
                $basic_inc_federal100 = 0;
                foreach ($regions as $region)//цикл по регионам
                {
                    $cur_region = $region->id;
                    //$subquery = User::find()->select(['id'])->where(['status' => 10])->count();
                    //print_r($subquery.'<br>');
                    //exit;
                    $organizations = Organization::find()
                        ->where(['region_id' => $region->id])
                        ->andWhere(['not in', 'type_org', $unset_organization])
                        ->andFilterWhere(['type_org' => $type_org_key])
                        ->andFilterWhere(['municipality_id' => $municipality_for_region])
                        //->andWhere(['in', 'id', $subquery])
                        ->all();
                    $count_organizations = Organization::find()
                        ->where(['region_id' => $region->id])
                        ->andWhere(['not in', 'type_org', $unset_organization])
                        ->andFilterWhere(['type_org' => $type_org_key])
                        ->andFilterWhere(['municipality_id' => $municipality_for_region])
                        //->andWhere(['in', 'id', $subquery])
                        ->count();
                    $count_organizations_all += $count_organizations;
                    //$type_user = \common\models\AuthAssignment::find()->select(['item_name'])->where(['user_id' => $user_id]);
                    $menus_region_count = 0;
                    $menu_region_count = 0;
                    $kids_region_count = 0;
                    $kid_region_count = 0;
                    $basic_inc_region100 = 0;
                    foreach ($organizations as $organization) {
                        //$user_id = User::find()->select(['id'])->where(['organization_id' => $organization->id]);
                        //print_r($user_id);
                        //$type_user = \common\models\AuthAssignment::find()->select(['item_name'])->where(['user_id' => $user_id]);
                        $array_org[$i][0] = $organization->federal_district_id;
                        $array_org[$j][0] = $organization->federal_district_id;
                        $array_org[$k][0] = $organization->federal_district_id;
                        $array_org[$i][1] = $organization->region_id;
                        $array_org[$j][1] = $organization->region_id;
                        $array_org[$i][3] = $organization->title;
                        $array_org[$i][2] = Municipality::findOne($organization->municipality_id)->name;
                        $array_org[$i][4] = $organization->get_type_org($organization->type_org);
                        $array_org[$i][7] = $organization->organizator_food;
                        $array_org[$i][8] = $organization->medic_service_programm;
                        $table .= '<td>' . ($i + 1) . '</td>';
                        $menus_organization_count = Menus::find()
                            ->Select(['organization_id'])
                            ->where(['organization_id' => $organization->id, 'status_archive' => '0'])
                            ->count();
                        $menus_region_count += $menus_organization_count;
                        $menu_region_count += Menus::find()
                            ->Select(['organization_id'])
                            ->where(['organization_id' => $organization->id, 'status_archive' => '0'])
                            ->groupBy('organization_id')
                            ->count();
                        $kids_organization_count = Kids::find()
                            ->Select(['organization_id'])
                            ->where(['organization_id' => $organization->id])
                            ->count();
                        $kids_region_count += $kids_organization_count;
                        $kid_region_count += Kids::find()
                            ->Select(['organization_id'])
                            ->where(['organization_id' => $organization->id])
                            ->groupBy('organization_id')
                            ->count();
                        $basic_information = 0;
                        $basic_information_items = Organization::find()
                            ->Select(['naseleni_punkt', 'name_dir', 'short_title', 'address', 'phone', 'email', 'inn'])
                            ->where(['id' => $organization->id])
                            ->all();
                        $basic_inc_organization = 0;
                        foreach ($basic_information_items as $item) {
                            if (isset($item['naseleni_punkt']) && $item['naseleni_punkt'] !== '') {
                                $basic_inc_organization++;
                            }
                            if (isset($item['name_dir']) && $item['name_dir'] !== '') {
                                $basic_inc_organization++;
                            }
                            if (isset($item['short_title']) && $item['short_title'] !== '') {
                                $basic_inc_organization++;
                            }
                            if (isset($item['address']) && $item['address'] !== '') {
                                $basic_inc_organization++;
                            }
                            if (isset($item['phone']) && $item['phone'] !== '') {
                                $basic_inc_organization++;
                            }
                            if (isset($item['email']) && $item['email'] !== '') {
                                $basic_inc_organization++;
                            }
                            if (isset($item['inn']) && $item['inn'] !== '') {
                                $basic_inc_organization++;
                            }
                        }
                        if ($basic_inc_organization == 7) {
                            $basic_inc_region100++;
                            $basic_inc_federal100++;
                            $basic_inc_all100++;
                        }
                        //$table .= '<td colspan="3" class="text-center">' . number_format((100/7*$basic_inc), 0) . '%</td>';
                        $i++;
                    }
                    //итоги по региону
                    $menus_district_count += $menus_region_count;
                    $menu_district_count += $menu_region_count;
                    $kids_district_count += $kids_region_count;
                    $kid_district_count += $kid_region_count;
                    if ($count_organizations) {
                        $table = '<tr class="">';
                        $table .= '<td>' . $model->get_region($region) . '</td>';
                        $table .= '<td colspan="1" class="text-center align-middle">' . $count_organizations . '</td>';
                        $table .= '<td colspan="1" class="text-center align-middle">' . $menu_region_count . '</td>';
                        $table .= '<td colspan="1" class="text-center align-middle">' . $menus_region_count . '</td>';
                        $table .= '<td colspan="1" class="text-center align-middle">' . $kid_region_count . '</td>';
                        $table .= '<td colspan="1" class="text-center align-middle">' . $kids_region_count . '</td>';
                        $table .= '<td colspan="1" class="text-center align-middle">' . $basic_inc_region100 . '</td>';
                        $table .= '</tr>';
                    }
                    else {
                        $table = '<tr class="main-color-4">';
                        $table .= '<td>' . $model->get_region($region) . '</td>';
                        $table .= '<td colspan="6" class="text-center align-middle">Не найдено организаций в регионе</td>';
                        $table .= '</tr>';
                        $table = '';
                    }
                    echo $table;
                }
                $menus_all_count += $menus_district_count;
                $menu_all_count += $menu_district_count;
                $kids_all_count += $kids_district_count;
                $kid_all_count += $kid_district_count;
                //итоги по округу
                //if ($prev_district != $cur_district) {
                $table = '<tr class="main-color-5">';
                $table .= '<td colspan="1">' . $model->get_district($district->id) . '</td>';
                $table .= '<td colspan="1" class="text-center align-middle">' . $count_organizations_all . '</td>';
                $table .= '<td colspan="1" class="text-center align-middle">' . $menu_district_count . '</td>';
                $table .= '<td colspan="1" class="text-center align-middle">' . $menus_district_count . '</td>';
                $table .= '<td colspan="1" class="text-center align-middle">' . $kid_district_count . '</td>';
                $table .= '<td colspan="1" class="text-center align-middle">' . $kids_district_count . '</td>';
                $table .= '<td colspan="1" class="text-center align-middle">' . $basic_inc_federal100 . '</td>';
                $table .= '</tr>';
                //}
                //('prev_district: ' . $prev_district . ' ------------- ');
                //print_r('cur_district: ' . $cur_district . '<br>');
                echo $table;
            }
            else {
                //нули по округам
                $table = '<tr class="main-color-4">';
                $table .= '<td colspan="1">' . $model->get_district($array_org[$k][0]) . '</td>';
                $table .= '<td colspan="7" class="text-center align-middle">0</td>';
                $table .= '</tr>';
                echo $table;
            }
        }
        $table = '<tr class="main-color-7">';
        $table .= '<td colspan="1">Итого организаций: </td>';
        $table .= '<td colspan="1" class="text-center align-middle">' . (($i == 0) ? 0 : count($array_org)) . '</td>';
        $table .= '<td colspan="1" class="text-center align-middle">' . $menu_all_count . '</td>';
        $table .= '<td colspan="1" class="text-center align-middle">' . $menus_all_count . '</td>';
        $table .= '<td colspan="1" class="text-center align-middle">' . $kid_all_count . '</td>';
        $table .= '<td colspan="1" class="text-center align-middle">' . $kids_all_count . '</td>';
        $table .= '<td colspan="1" class="text-center align-middle">' . $basic_inc_all100 . '</td>';
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




