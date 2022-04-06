<?php

use common\models\BasicInformation;
use common\models\CharactersStolovaya;
use common\models\CharactersStudy;
use common\models\ExpensesFood;
use common\models\InformationEducation;
use common\models\Kids;
use common\models\Menus;
use common\models\Municipality;
use common\models\NutritionApplications;
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

<div class="users-nutrition-report-form container"><h2 align="center">ПС "Питание" - отчет по уровням</h2>
    <?php
    $SPACE_LENGTH = 1;
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
<?php
if (!empty($districts)) //нажали кнопку показать
{
    echo '<table class="table table-bordered table-sm table-responsive">';
    echo '<thead>';
    echo '<tr class="main-color-7">';
    echo '<th class="text-center" rowspan="'. ($type_org_key == 2? 1:2).'">№</th>';
    //if($region_for_district === '0'){
    //    echo '<th class="text-center" rowspan="2" colspan="1">Территория</th>';
    //    echo '<th class="text-center" rowspan="2" colspan="1">Муниципальное образование</th>';
    //}
    echo '<th class="text-center"  rowspan="'. ($type_org_key == 2? 1:2).'">Наименование учреждения</th>';
    //echo '<th class="text-center" rowspan="2" colspan="1">Тип организации</th>';
    echo '<th class="text-center" rowspan="'. ($type_org_key == 2? 1:2).'">Дата регистрации</th>';
    if ($type_org_key != 2){ // если не мунобр
        echo '<th class="text-center" rowspan="2">'.($type_org_key == 3?'Организатор питания':'Прикреплено образовательное учреждение').'</th>';
        echo '<th class="text-center" rowspan="2">Внесено меню (1-4 класс)</th>';
        echo '<th class="text-center" rowspan="1" colspan="7">В том числе</th>';
        echo '<th class="text-center" rowspan="1" colspan="' . ($type_org_key == 4 ? 1 : 6) . '">Внесена информация</th>';
        echo '</tr>';
        echo '<tr class="main-color-7">';
        echo '<th class="text-center" >б/о</th>';
        echo '<th class="text-center" >ОВЗ</th>';
        echo '<th class="text-center" >СД</th>';
        echo '<th class="text-center" >Целиакия</th>';
        echo '<th class="text-center" >ФКУ</th>';
        echo '<th class="text-center" >Муковисцидоз</th>';
        //echo '<th class="text-center" rowspan="1" colspan="1">Метаболический синдром</th>';
        echo '<th class="text-center" >ПА</th>';
        echo '<th class="text-center" >Общ. инф.</th>';
        if ($type_org_key == 3) //общеобразовательная
        {
            echo '<th class="text-center">о столовой</th>';
            echo '<th class="text-center">о хар-ке обучающихся</th>';
            echo '<th class="text-center">о произв. помещ.</th>';
            echo '<th class="text-center">о кол-ве обучающихся</th>';
            echo '<th class="text-center">о сменах и переменах</th>';
        }
    }
    else {
        echo '<th class="text-center">Информация о финансировании</th>';
    }
    echo '</tr></thead><tbody>';
    $array_org = array();
    $array_org[] = array();
    $i = 0;
    $j = 0;
    $k = 0;
    $m = 0;
    $type_org_key = ($type_org_key == '0' ? ('') : $type_org_key);
    $municipality_for_region = ($municipality_for_region == '0' ? ('') : $municipality_for_region);
    $cur_district = 0;
    $prev_district = 0;
    //print_r($type_org_key);
    $organizations_all_count = 0;
    $menus_all_count = 0;
    $menus_wf_all_count = 0;
    $menus_lim_pos_all_count = 0;
    $menus_diabetes_all_count = 0;
    $menus_celiac_all_count = 0;
    $menus_phenyl_all_count = 0;
    $menus_cys_fibr_all_count = 0;
    //$menus_metabolic_all_count = 0;
    $menus_food_allergy_all_count = 0;
    $menu_all_count = 0;
    //$kids_all_count = 0;
    //$kid_all_count = 0;
    $information_inc_all100 = 0;
    $school_break_information_inc_all100 = 0;
    $education_information_inc_all100 = 0;
    $basic_information_inc_all100 = 0;
    $study_information_inc_all100 = 0;
    $stolovaya_information_inc_all100 = 0;
    $expenses_food_information_all = 0;
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
            $menus_wf_district_count = 0;
            $menus_lim_pos_district_count = 0;
            $menus_diabetes_district_count = 0;
            $menus_celiac_district_count = 0;
            $menus_phenyl_district_count = 0;
            $menus_cys_fibr_district_count = 0;
            $menus_metabolic_district_count = 0;
            $menus_food_allergy_district_count = 0;
            $menu_district_count = 0;
            $kids_district_count = 0;
            $kid_district_count = 0;
            $information_inc_federal100 = 0;
            $school_break_information_inc_federal100 = 0;
            $education_information_inc_federal100 = 0;
            $basic_information_inc_federal100 = 0;
            $study_information_inc_federal100 = 0;
            $stolovaya_information_inc_federal100 = 0;
            $organizations_district_count = 0;
            $expenses_food_information_district = 0;
            foreach ($regions as $region)//цикл по регионам
            {
                $cur_region = $region->id;
                //$subquery = User::find()->select(['id'])->where(['status' => 10])->all();
                if ($municipality_for_region == 0) {
                    $municipalitys = Municipality::find()->select(['id'])->where(['region_id' => $region->id])->orderBy('name ASC')->all();
                }
                else {
                    $municipalitys = Municipality::find()->select(['id'])->where(['region_id' => $region->id, 'id' => $municipality_for_region])->orderBy('name ASC')->all();
                    //print_r($municipalitys);
                }
                $menus_region_count = 0;
                $menus_wf_region_count = 0;
                $menus_lim_pos_region_count = 0;
                $menus_diabetes_region_count = 0;
                $menus_celiac_region_count = 0;
                $menus_phenyl_region_count = 0;
                $menus_cys_fibr_region_count = 0;
                $menus_metabolic_region_count = 0;
                $menus_food_allergy_region_count = 0;
                $menu_region_count = 0;
                $kids_region_count = 0;
                $kid_region_count = 0;
                $information_inc_region100 = 0;
                $school_break_information_inc_region100 = 0;
                $education_information_inc_region100 = 0;
                $basic_information_inc_region100 = 0;
                $study_information_inc_region100 = 0;
                $stolovaya_information_inc_region100 = 0;
                $organizations_region_count = 0;
                $expenses_food_information_region = 0;
                foreach ($municipalitys as $municipality) {
                    $menus_municipality_count = 0;
                    $menu_municipality_count = 0;
                    //$kids_municipality_count = 0;
                    //$kid_municipality_count = 0;
                    $information_inc_municipality100 = 0;
                    $school_break_information_inc_municipality100 = 0;
                    $education_information_inc_municipality100 = 0;
                    $basic_information_inc_municipality100 = 0;
                    $study_information_inc_municipality100 = 0;
                    $stolovaya_information_inc_municipality100 = 0;

                    //print_r($municipality->id);
                    $bad_org_id = [305];
                    $organizations = Organization::find()
                        ->leftJoin('municipality as mun', 'organization.municipality_id = mun.id')
                        ->where(['organization.region_id' => $region->id])
                        ->andWhere(['not in', 'organization.type_org', $unset_organization])
                        ->andWhere(['not in', 'organization.id', $bad_org_id])
                        ->andFilterWhere(['organization.type_org' => $type_org_key])
                        ->andFilterWhere(['organization.municipality_id' => $municipality->id])
                        ->orderBy('mun.name ASC')
                        //->andWhere(['in', 'id', $subquery])
                        ->all();
                    $count_organizations = Organization::find()
                        ->leftJoin('municipality as mun', 'organization.municipality_id = mun.id')
                        ->where(['organization.region_id' => $region->id])
                        ->andWhere(['not in', 'organization.type_org', $unset_organization])
                        ->andWhere(['not in', 'organization.id', $bad_org_id])
                        ->andFilterWhere(['organization.type_org' => $type_org_key])
                        ->andFilterWhere(['organization.municipality_id' => $municipality->id])
                        //->andWhere(['in', 'id', $subquery])
                        ->count();
                    //print_r($organizations);
                    $count_organizations_all += $count_organizations;
                    //$type_user = \common\models\AuthAssignment::find()->select(['item_name'])->where(['user_id' => $user_id]);
                    $menus_municipality_count = 0;
                    $menu_municipality_count = 0;
                    $kids_municipality_count = 0;
                    $kid_municipality_count = 0;
                    $organizations_municipality_count = 0;
                    $menus_wf_municipality_count = 0;
                    $menus_lim_pos_municipality_count = 0;
                    $menus_diabetes_municipality_count = 0;
                    $menus_celiac_municipality_count = 0;
                    $menus_phenyl_municipality_count = 0;
                    $menus_cys_fibr_municipality_count = 0;
                    $menus_metabolic_municipality_count = 0;
                    $menus_food_allergy_municipality_count = 0;
                    $expenses_food_information_municipality = 0;
                    $number_of_count = 0;
                    foreach ($organizations as $organization) {
                        ++$number_of_count;
                        //$user_id = User::find()->select(['id'])->where(['organization_id' => $organization->id]);
                        //print_r($user_id);
                        //$type_user = \common\models\AuthAssignment::find()->select(['item_name'])->where(['user_id' => $user_id]);
                        $array_org[$i][0] = $organization->federal_district_id;
                        $array_org[$j][0] = $organization->federal_district_id;
                        $array_org[$k][0] = $organization->federal_district_id;
                        $array_org[$m][0] = $organization->federal_district_id;
                        $array_org[$i][1] = $organization->region_id;
                        $array_org[$j][1] = $organization->region_id;
                        $array_org[$m][1] = $organization->region_id;
                        $array_org[$m][2] = Municipality::findOne($organization->municipality_id)->name;
                        $array_org[$i][3] = $organization->short_title;
                        $array_org[$i][2] = Municipality::findOne($organization->municipality_id)->name;
                        $array_org[$i][4] = $organization->get_type_org($organization->type_org);
                        $array_org[$i][7] = $organization->organizator_food;
                        $array_org[$i][8] = $organization->medic_service_programm;

                        //$table .= '<td>' . $model->get_district($array_org[$i][0]) . '</td>';
                        //if ($region_for_district === '0') {
                            //$table .= '<td colspan="1">' . $model->get_region($array_org[$i][1]) . '</td>';
                        //    $table .= '<td class="text-center">' . $array_org[$i][2] . '</td>';
                        //}

                        //$table .= '<td class="text-center">' . (isset($array_org[$i][4]) ? $array_org[$i][4] : '-') . '</td>';
                        //$user = User::find()->Select('created_at')->where(['organization_id' => $organization->id])->all();

                        if ($type_org_key !== '2') { //Смотрим организатора питания: (если не мунобр)
                            $table = '<tr>';
                            $table .= '<td>' . ($number_of_count) . '</td>';
                            $table .= '<td class="text-center">' . (isset($array_org[$i][3]) ? $array_org[$i][3] : '-') . '</td>'; // Название
                            $date_created_at = new DateTime($organization->created_at);
                            $table .= '<td class="text-center">' . $date_created_at->format('d.m.Y') . '</td>';
                            $nutr_apps = NutritionApplications::find()
                                ->select('sender_org_id, reciever_org_id')
                                ->where(['and', 'status=1', ['or', 'reciever_org_id' => $organization->id, 'sender_org_id' => $organization->id]])
                                ->andWhere(['type_org_id' => 3])// только школы, оргпит не учитываются
                                ->asArray()
                                ->all();
                            if ($type_org_key == 3) { // если общеобразовательная организация
                                $check_str_nutr_app = 0;
                                foreach ($nutr_apps as $nutr_app) {
                                    if ($nutr_app['sender_org_id'] == $organization->id) {
                                        $table .= '<td class="text-center">' .
                                            Organization::find()
                                                ->select('short_title')
                                                ->where(['id' => $nutr_app['reciever_org_id']])->scalar()
                                            . '</td>';
                                        $check_str_nutr_app = 1;
                                    }
                                    elseif ($nutr_app['reciever_org_id'] == $organization->id) {
                                        $table .= '<td class="text-center">' .
                                            Organization::find()
                                                ->select('short_title')
                                                ->where(['id' => $nutr_app['sender_org_id']])->scalar()
                                            . '</td>';
                                        $check_str_nutr_app = 1;
                                    }
                                    /*else {
                                        $table .= '<td class="text-center">Отсутствует</td>';
                                    }*/
                                }
                                if ($check_str_nutr_app == 0) {
                                    $table .= '<td class="text-center">Нет информации</td>';
                                }
                            }
                            if ($type_org_key == 4) { // если организатор питания
                                $table .= '<td class="text-center">';
                                $check_str_nutr_app = 0;
                                $app_orgs = '';
                                foreach ($nutr_apps as $nutr_app) {
                                    if ($nutr_app['sender_org_id'] == $organization->id) {
                                        $app_orgs .= Organization::find()
                                                ->select('short_title')
                                                ->where(['id' => $nutr_app['reciever_org_id']])->scalar() . ',</br>';
                                        $check_str_nutr_app = 1;
                                    }
                                    elseif ($nutr_app['reciever_org_id'] == $organization->id) {
                                        $app_orgs .=
                                            Organization::find()
                                                ->select('short_title')
                                                ->where(['id' => $nutr_app['sender_org_id']])->scalar()
                                            . ',</br>';
                                        $check_str_nutr_app = 1;
                                    }
                                }
                                $table .= substr($app_orgs, 0, -6);
                                if ($check_str_nutr_app == 0) {
                                    $table .= 'Нет информации';
                                }
                                $table .= '</td>';
                            }
                            //Перестаем смотреть организатора питания.
                            //Считаем меню:
                            $menus_organization_count = Menus::find()
                                ->Select(['organization_id'])
                                ->where(['organization_id' => $organization->id, 'status_archive' => '0'])
                                ->andWhere(['or', 'age_info_id' => 6, 'age_info_id' => 9])
                                ->andWhere(['!=', 'feeders_characters_id', 9])
                                ->count();
                            $menus_municipality_count += $menus_organization_count;
                            $menu_municipality_count += Menus::find()
                                ->Select(['organization_id'])
                                ->where(['organization_id' => $organization->id, 'status_archive' => '0'])
                                ->andWhere(['or', 'age_info_id' => 6, 'age_info_id' => 9])
                                ->andWhere(['!=', 'feeders_characters_id', 9])
                                ->groupBy('organization_id')
                                ->count();
                            $table .= '<td class="text-center">' . $menus_organization_count . '</td>';
                            $menus_wf_organization_count = Menus::find()->Select(['organization_id'])->where(['organization_id' => $organization->id, 'status_archive' => '0', 'feeders_characters_id' => 3])->andWhere(['or', 'age_info_id' => 6, 'age_info_id' => 9])->count();
                            $menus_lim_pos_organization_count = Menus::find()->Select(['organization_id'])->where(['organization_id' => $organization->id, 'status_archive' => '0', 'feeders_characters_id' => 4])->andWhere(['or', 'age_info_id' => 6, 'age_info_id' => 9])->count();
                            $menus_diabetes_organization_count = Menus::find()->Select(['organization_id'])->where(['organization_id' => $organization->id, 'status_archive' => '0', 'feeders_characters_id' => 5])->andWhere(['or', 'age_info_id' => 6, 'age_info_id' => 9])->count();
                            $menus_celiac_organization_count = Menus::find()->Select(['organization_id'])->where(['organization_id' => $organization->id, 'status_archive' => '0', 'feeders_characters_id' => 6])->andWhere(['or', 'age_info_id' => 6, 'age_info_id' => 9])->count();
                            $menus_phenyl_organization_count = Menus::find()->Select(['organization_id'])->where(['organization_id' => $organization->id, 'status_archive' => '0', 'feeders_characters_id' => 7])->andWhere(['or', 'age_info_id' => 6, 'age_info_id' => 9])->count();
                            $menus_cys_fibr_organization_count = Menus::find()->Select(['organization_id'])->where(['organization_id' => $organization->id, 'status_archive' => '0', 'feeders_characters_id' => 8])->andWhere(['or', 'age_info_id' => 6, 'age_info_id' => 9])->count();
                            //$menus_metabolic_organization_count = Menus::find()->Select(['organization_id'])->where(['organization_id' => $organization->id, 'status_archive' => '0', 'feeders_characters_id' => 9 ])->$this->count();
                            $menus_food_allergy_organization_count = Menus::find()->Select(['organization_id'])->where(['organization_id' => $organization->id, 'status_archive' => '0', 'feeders_characters_id' => 10])->andWhere(['or', 'age_info_id' => 6, 'age_info_id' => 9])->count();
                            $table .= '<td class="text-center">' . $menus_wf_organization_count . '</td>';
                            $table .= '<td class="text-center">' . $menus_lim_pos_organization_count . '</td>';
                            $table .= '<td class="text-center">' . $menus_diabetes_organization_count . '</td>';
                            $table .= '<td class="text-center">' . $menus_celiac_organization_count . '</td>';
                            $table .= '<td class="text-center">' . $menus_phenyl_organization_count . '</td>';
                            $table .= '<td class="text-center">' . $menus_cys_fibr_organization_count . '</td>';
                            //$table .= '<td class="text-center">' . $menus_metabolic_organization_count . '</td>';
                            $table .= '<td class="text-center">' . $menus_food_allergy_organization_count . '</td>';
                            $menus_wf_municipality_count += $menus_wf_organization_count;
                            $menus_lim_pos_municipality_count += $menus_lim_pos_organization_count;
                            $menus_diabetes_municipality_count += $menus_diabetes_organization_count;
                            $menus_celiac_municipality_count += $menus_celiac_organization_count;
                            $menus_phenyl_municipality_count += $menus_phenyl_organization_count;
                            $menus_cys_fibr_municipality_count += $menus_cys_fibr_organization_count;
                            //$menus_metabolic_municipality_count += $menus_metabolic_organization_count;
                            $menus_food_allergy_municipality_count += $menus_food_allergy_organization_count;
                            $basic_information = 0;
                            $information_items = Organization::findOne(['id' => $organization->id]);
                            $stolovaya_information_items = CharactersStolovaya::find()->where(['organization_id' => $organization->id])->count() > 0;
                            $study_information_items = CharactersStudy::find()->where(['organization_id' => $organization->id])->count() > 0;
                            $basic_information_items = BasicInformation::find()->where(['organization_id' => $organization->id])->count() > 0;
                            $education_information_items = InformationEducation::find()->where(['organization_id' => $organization->id])->count() > 0;
                            $school_break_information_items = SchoolBreak::find()->where(['organization_id' => $organization->id])->count() > 0;
                            if (!empty($information_items->address) && !empty($information_items->email) && !empty($information_items->phone) && !empty($information_items->name_dir)) {
                                $information_inc_organization = 'Да';
                                $information_inc_municipality100++;
                                $information_inc_region100++;
                                $information_inc_federal100++;
                                $information_inc_all100++;
                            }
                            else {
                                $information_inc_organization = 'Нет';
                            }
                            if ($stolovaya_information_items) {
                                $stolovaya_information_inc_organization = 'Да';
                                $stolovaya_information_inc_municipality100++;
                                $stolovaya_information_inc_region100++;
                                $stolovaya_information_inc_federal100++;
                                $stolovaya_information_inc_all100++;
                            }
                            else {
                                $stolovaya_information_inc_organization = 'Нет';
                            }
                            if ($study_information_items) {
                                $study_information_inc_organization = 'Да';
                                $study_information_inc_municipality100++;
                                $study_information_inc_region100++;
                                $study_information_inc_federal100++;
                                $study_information_inc_all100++;
                            }
                            else {
                                $study_information_inc_organization = 'Нет';
                            }
                            if ($basic_information_items) {
                                $basic_information_inc_organization = 'Да';
                                $basic_information_inc_municipality100++;
                                $basic_information_inc_region100++;
                                $basic_information_inc_federal100++;
                                $basic_information_inc_all100++;
                            }
                            else {
                                $basic_information_inc_organization = 'Нет';
                            }
                            if ($education_information_items) {
                                $education_information_inc_organization = 'Да';
                                $education_information_inc_municipality100++;
                                $education_information_inc_region100++;
                                $education_information_inc_federal100++;
                                $education_information_inc_all100++;
                            }
                            else {
                                $education_information_inc_organization = 'Нет';
                            }
                            if ($school_break_information_items) {
                                $school_break_information_inc_organization = 'Да';
                                $school_break_information_inc_municipality100++;
                                $school_break_information_inc_region100++;
                                $school_break_information_inc_federal100++;
                                $school_break_information_inc_all100++;
                            }
                            else {
                                $school_break_information_inc_organization = 'Нет';
                            }
                            $table .= '<td class="text-center">' . $information_inc_organization . '</td>';
                            if ($type_org_key == 3) {//если общеобразовательная организация
                                $table .= '<td class="text-center">' . $stolovaya_information_inc_organization . '</td>';
                                $table .= '<td class="text-center">' . $study_information_inc_organization . '</td>';
                                $table .= '<td class="text-center">' . $basic_information_inc_organization . '</td>';
                                $table .= '<td class="text-center">' . $education_information_inc_organization . '</td>';
                                $table .= '<td class="text-center">' . $school_break_information_inc_organization . '</td>';
                            }
                            $table .= '</tr>';
                            echo $table;
                            $organizations_municipality_count++;
                        }
                        if ($type_org_key === '2' && $number_of_count == 1){
                            $table = '<tr>';
                            //$table .= '<td>' . ($number_of_count) . '</td>';
                            $table .= '<td class="text-center">' . $array_org[$i][2] . '</td>';
                            $table .= '<td class="text-center">' . (isset($array_org[$i][3]) ? $array_org[$i][3] : '-') . '</td>'; // Название
                            $date_created_at = new DateTime($organization->created_at);
                            $table .= '<td class="text-center">' . $date_created_at->format('d.m.Y') . '</td>';
                            $orgs = Organization::find()->where(['municipality_id' => $organization->municipality_id])->all();
                            $org_mas = [];
                            foreach ($orgs as $org) {
                                $org_mas[] = $org->id;
                            }
                            $expenses_food_information = ExpensesFood::find()->where(['organization_id' => $org_mas])->count();
                            // print_r($expenses_food_information);
                            $table .= '<td class="text-center">' . $expenses_food_information . '</td>';
                            $table .= '</tr>';
                            echo $table;
                            $organizations_municipality_count++;
                            $expenses_food_information_municipality += $expenses_food_information;
                        }


                        $i++;


                    }
                    //итоги по муниципальному
                    if ($organizations_municipality_count and $type_org_key != 2) {
                        $table = '<tr class="main-color-8">';
                        $table .= '<td colspan="3">' . $array_org[$m][2] . '</td>';
                        if($type_org_key != 2) {
                            $table .= '<td class="text-center">' . $organizations_municipality_count . '</td>';
                        }
                        else {
                            $table .= '<td class="text-center"></td>';
                        }
                        //$table .= '<td colspan="' . ($type_org_key == 3 ? $SPACE_LENGTH : $SPACE_LENGTH) . '" class="text-center align-middle"></td>';
                        if ($type_org_key != 2) {
                            $table .= '<td class="text-center align-middle">' . $menus_municipality_count . '</td>';
                            $table .= '<td class="text-center align-middle">' . $menus_wf_municipality_count . '</td>';
                            $table .= '<td class="text-center align-middle">' . $menus_lim_pos_municipality_count . '</td>';
                            $table .= '<td class="text-center align-middle">' . $menus_diabetes_municipality_count . '</td>';
                            $table .= '<td class="text-center align-middle">' . $menus_celiac_municipality_count . '</td>';
                            $table .= '<td class="text-center align-middle">' . $menus_phenyl_municipality_count . '</td>';
                            $table .= '<td class="text-center align-middle">' . $menus_cys_fibr_municipality_count . '</td>';
                            //$table .= '<td class="text-center align-middle">' . $menus_metabolic_municipality_count . '</td>';
                            $table .= '<td class="text-center align-middle">' . $menus_food_allergy_municipality_count . '</td>';
                            //$table .= $type_org_key == 3 ? '<td colspan="1" class="text-center align-middle">' . $kids_municipality_count . '</td>' : '';
                            $table .= '<td class="text-center align-middle">' . $information_inc_municipality100 . '</td>';
                            if ($type_org_key == 3) {
                                $table .= '<td class="text-center align-middle">' . $stolovaya_information_inc_municipality100 . '</td>';
                                $table .= '<td class="text-center align-middle">' . $study_information_inc_municipality100 . '</td>';
                                $table .= '<td class="text-center align-middle">' . $basic_information_inc_municipality100 . '</td>';
                                $table .= '<td class="text-center align-middle">' . $education_information_inc_municipality100 . '</td>';
                                $table .= '<td class="text-center align-middle">' . $school_break_information_inc_municipality100 . '</td>';
                            }
                        }
                        $table .= '</tr>';
                        echo $table;
                    }
                    else {
                        $table = '<tr class="main-color-3">';
                        $table .= '<td>' . $model->get_municipality($array_org[$m][2]) . '</td>';
                        $table .= '<td colspan="4" class="text-center align-middle">Не найдено организаций в муниципальном образовании</td>';
                        $table .= '</tr>';
                        $table = '';
                    }
                    $m++;
                    $organizations_region_count += $organizations_municipality_count;
                    $menus_region_count += $menus_municipality_count;
                    $menus_wf_region_count += $menus_wf_municipality_count;
                    $menus_lim_pos_region_count += $menus_lim_pos_municipality_count;
                    $menus_diabetes_region_count += $menus_diabetes_municipality_count;
                    $menus_celiac_region_count += $menus_celiac_municipality_count;
                    $menus_phenyl_region_count += $menus_phenyl_municipality_count;
                    $menus_cys_fibr_region_count += $menus_cys_fibr_municipality_count;
                    //$menus_metabolic_region_count += $menus_metabolic_municipality_count;
                    $menus_food_allergy_region_count += $menus_food_allergy_municipality_count;
                    $menu_region_count += $menu_municipality_count;
                    $expenses_food_information_region += $expenses_food_information_municipality;
                }
                //итоги по региону
                $organizations_district_count += $organizations_region_count;
                $menus_district_count += $menus_region_count;
                $menus_wf_district_count += $menus_wf_region_count;
                $menus_lim_pos_district_count += $menus_lim_pos_region_count;
                $menus_diabetes_district_count += $menus_diabetes_region_count;
                $menus_celiac_district_count += $menus_celiac_region_count;
                $menus_phenyl_district_count += $menus_phenyl_region_count;
                $menus_cys_fibr_district_count += $menus_cys_fibr_region_count;
                //$menus_metabolic_district_count += $menus_metabolic_region_count;
                $menus_food_allergy_district_count += $menus_food_allergy_region_count;
                $menu_district_count += $menu_region_count;
                $expenses_food_information_district += $expenses_food_information_region;
                if ($municipality_for_region == 0) {
                    if ($organizations_region_count) {
                        $table = '<tr class="main-color-9">';
                        //$table .= '<td>' . $model->get_district($district->id) . '</td>';
                        $table .= '<td colspan="'.($type_org_key == 2?1:3).'">' . $model->get_region($region) . '</td>';
                        //$table .= '<td colspan="1" class="text-center align-middle">Количество организаций в СФ:</td>';
                        $table .= '<td class="text-center align-middle" colspan="'.($type_org_key == 2?2:1).'">' . $organizations_region_count . '</td>';
                        //$table .= '<td colspan="' . ($type_org_key == 3 ? $SPACE_LENGTH : $SPACE_LENGTH) . '" class="text-center align-middle"></td>';
                        if ($type_org_key != 2) {
                            $table .= '<td class="text-center align-middle">' . $menus_region_count . '</td>';
                            $table .= '<td class="text-center align-middle">' . $menus_wf_region_count . '</td>';
                            $table .= '<td class="text-center align-middle">' . $menus_lim_pos_region_count . '</td>';
                            $table .= '<td class="text-center align-middle">' . $menus_diabetes_region_count . '</td>';
                            $table .= '<td class="text-center align-middle">' . $menus_celiac_region_count . '</td>';
                            $table .= '<td class="text-center align-middle">' . $menus_phenyl_region_count . '</td>';
                            $table .= '<td class="text-center align-middle">' . $menus_cys_fibr_region_count . '</td>';
                            //$table .= '<td colspan="1" class="text-center align-middle">' . $menus_metabolic_region_count . '</td>';
                            $table .= '<td class="text-center align-middle">' . $menus_food_allergy_region_count . '</td>';
                            //$table .= $type_org_key == 3 ? '<td colspan="1" class="text-center align-middle">' . $kids_region_count . '</td>' : '';
                            $table .= '<td class="text-center align-middle">' . $information_inc_region100 . '</td>';
                            if ($type_org_key == 3) {
                                $table .= '<td class="text-center align-middle">' . $stolovaya_information_inc_region100 . '</td>';
                                $table .= '<td class="text-center align-middle">' . $study_information_inc_region100 . '</td>';
                                $table .= '<td class="text-center align-middle">' . $basic_information_inc_region100 . '</td>';
                                $table .= '<td class="text-center align-middle">' . $education_information_inc_region100 . '</td>';
                                $table .= '<td class="text-center align-middle">' . $school_break_information_inc_region100 . '</td>';
                            }
                        }
                        else {
                            $table .= '<td class="text-center align-middle">' . $expenses_food_information_region. '</td>';
                        }
                        $table .= '</tr>';
                    }
                    else {
                        $table = '<tr class="main-color-3">';
                        $table .= '<td>' . $model->get_district($district->id) . '</td>';
                        $table .= '<td>' . $model->get_region($region) . '</td>';
                        $table .= '<td colspan="4" class="text-center align-middle">Не найдено организаций в регионе</td>';
                        $table .= '</tr>';
                        $table = '';
                    }
                    echo $table;
                }

            }
            //итоги по округу
            $organizations_all_count += $organizations_district_count;
            $menus_all_count += $menus_district_count;
            $menus_wf_all_count += $menus_wf_district_count;
            $menus_lim_pos_all_count += $menus_lim_pos_district_count;
            $menus_diabetes_all_count += $menus_diabetes_district_count;
            $menus_celiac_all_count += $menus_celiac_district_count;
            $menus_phenyl_all_count += $menus_phenyl_district_count;
            $menus_cys_fibr_all_count += $menus_cys_fibr_district_count;
            //$menus_metabolic_all_count += $menus_metabolic_district_count;
            $menus_food_allergy_all_count += $menus_food_allergy_district_count;
            $menu_all_count += $menu_district_count;
            $expenses_food_information_all += $expenses_food_information_district;
            //$kids_all_count += $kids_district_count;
            //$kid_all_count += $kid_district_count;
            if ($region_for_district == 0) {
                $table = '<tr class="main-color-5">';
                //$table .= '<td colspan="1"></td>';
                $table .= '<td colspan="'.($type_org_key == 2?1:3).'">' . $model->get_district($district->id) . '</td>';
                //$table .= '<td colspan="1" class="text-center align-middle">Количество организаций в ФО:</td>';
                $table .= '<td class="text-center align-middle" colspan="'.($type_org_key == 2?2:1).'">' . $organizations_district_count . '</td>';
                //$table .= '<td colspan="' . ($type_org_key == 3 ? $SPACE_LENGTH : $SPACE_LENGTH) . '" class="text-center align-middle"></td>';
                if ($type_org_key != 2) {
                    $table .= '<td class="text-center align-middle">' . $menus_district_count . '</td>';
                    $table .= '<td class="text-center align-middle">' . $menus_wf_district_count . '</td>';
                    $table .= '<td class="text-center align-middle">' . $menus_lim_pos_district_count . '</td>';
                    $table .= '<td class="text-center align-middle">' . $menus_diabetes_district_count . '</td>';
                    $table .= '<td class="text-center align-middle">' . $menus_celiac_district_count . '</td>';
                    $table .= '<td class="text-center align-middle">' . $menus_phenyl_district_count . '</td>';
                    $table .= '<td class="text-center align-middle">' . $menus_cys_fibr_district_count . '</td>';
                    //$table .= '<td colspan="1" class="text-center align-middle">' . $menus_metabolic_district_count . '</td>';
                    $table .= '<td class="text-center align-middle">' . $menus_food_allergy_district_count . '</td>';
                    //$table .= $type_org_key == 3 ? '<td colspan="1" class="text-center align-middle">' . $kids_district_count . '</td>' : '';
                    $table .= '<td class="text-center align-middle">' . $information_inc_federal100 . '</td>';
                    if ($type_org_key == 3) {
                        $table .= '<td class="text-center align-middle">' . $stolovaya_information_inc_federal100 . '</td>';
                        $table .= '<td class="text-center align-middle">' . $study_information_inc_federal100 . '</td>';
                        $table .= '<td class="text-center align-middle">' . $basic_information_inc_federal100 . '</td>';
                        $table .= '<td class="text-center align-middle">' . $education_information_inc_federal100 . '</td>';
                        $table .= '<td class="text-center align-middle">' . $school_break_information_inc_federal100 . '</td>';
                    }
                }
                else {
                    $table .= '<td class="text-center align-middle">' . $expenses_food_information_district . '</td>';
                }
                $table .= '</tr>';

                //}
                //('prev_district: ' . $prev_district . ' ------------- ');
                //print_r('cur_district: ' . $cur_district . '<br>');

                echo $table;
            }
        }
        else {
            //нули по округам
            $table = '<tr class="main-color">';
            $table .= '<td colspan="2">' . $model->get_district($array_org[$k][0]) . '</td>';
            $table .= '<td colspan="4" class="text-center align-middle">0</td>';
            $table .= '</tr>';
            echo $table;
        }
    }
    if ($district_for_district == 0) {
        $table = '<tr class="main-color-7">';
        $table .= '<td colspan="'.($type_org_key == 2?1:3).'">Итого организаций: </td>';
        $table .= '<td class="text-center align-middle" colspan="'.($type_org_key == 2?2:1).'">' . $organizations_all_count . '</td>';
        //$table .= '<td colspan="' . ($type_org_key == 3 ? $SPACE_LENGTH : $SPACE_LENGTH) . '" class="text-center align-middle"></td>';
        if ($type_org_key != 2) {
            $table .= '<td class="text-center align-middle">' . $menus_all_count . '</td>';
            $table .= '<td class="text-center align-middle">' . $menus_wf_all_count . '</td>';
            $table .= '<td class="text-center align-middle">' . $menus_lim_pos_all_count . '</td>';
            $table .= '<td class="text-center align-middle">' . $menus_diabetes_all_count . '</td>';
            $table .= '<td class="text-center align-middle">' . $menus_celiac_all_count . '</td>';
            $table .= '<td class="text-center align-middle">' . $menus_phenyl_all_count . '</td>';
            $table .= '<td class="text-center align-middle">' . $menus_cys_fibr_all_count . '</td>';
            //$table .= '<td colspan="1" class="text-center align-middle">' . $menus_metabolic_all_count . '</td>';
            $table .= '<td class="text-center align-middle">' . $menus_food_allergy_all_count . '</td>';
            //$table .= $type_org_key == 3 ? '<td colspan="1" class="text-center align-middle">' . $kids_all_count . '</td>' : '';
            $table .= '<td class="text-center align-middle">' . $information_inc_all100 . '</td>';
            if ($type_org_key == 3) {
                $table .= '<td class="text-center align-middle">' . $stolovaya_information_inc_all100 . '</td>';
                $table .= '<td class="text-center align-middle">' . $study_information_inc_all100 . '</td>';
                $table .= '<td class="text-center align-middle">' . $basic_information_inc_all100 . '</td>';
                $table .= '<td class="text-center align-middle">' . $education_information_inc_all100 . '</td>';
                $table .= '<td class="text-center align-middle">' . $school_break_information_inc_all100 . '</td>';
            }
        }
        else {
            $table .= '<td class="text-center align-middle">' . $expenses_food_information_all . '</td>';
        }
        $table .= '</tr>';
        echo $table;
    }
    echo '</tbody>';
    echo '</table>';
}

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




