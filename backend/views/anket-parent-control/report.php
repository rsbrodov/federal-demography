<?php

use common\models\AnketParentControl;
use common\models\NutritionApplications;
use common\models\Region;
use yii\bootstrap4\Html;
use yii\grid\GridView;
use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Button;
use common\models\Menus;
use common\models\Days;
use common\models\MenusDays;
use common\models\MenusDishes;
use common\models\MenusNutrition;
use common\models\Organization;
use yii\helpers\ArrayHelper;


/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Отчет по контрольным мероприятиям';
$this->params['breadcrumbs'][] = $this->title;
$correct_mas_for_mounth = ['09','10','11','12','01','02','03','04','05','06','07','08'];
$yes_no_items = [
    '1' => "Родительский",
    '2' => "Внутренний",
    '3' => "Родительский и внутренний",
];

$items = [1,2,3,4,5,6,7,8,9,10,11,13,14];
$organization_id = Yii::$app->user->identity->organization_id;
$region_id = Organization::findOne($organization_id)->region_id;
$municipality_one = \common\models\Municipality::find()->where(['region_id' => $region_id])->one()->id;

if (Yii::$app->user->can('minobr') || Yii::$app->user->can('rospotrebnadzor_nutrition'))
{
    $municipalities = \common\models\Municipality::find()->where(['region_id' => $region_id])->all();
    $municipality_items = ArrayHelper::map($municipalities, 'id', 'name');
    $municipality_null = array(0 => 'ПО ВСЕМУ РЕГИОНУ...');
    $municipality_items = ArrayHelper::merge($municipality_null, $municipality_items);

    $organization = Organization::find()->where(['type_org' => 3, 'municipality_id' => $municipality_one])->all();
    $organization_null = array(0 => 'Все организации ...');
    $organization_items = ArrayHelper::map($organization, 'id', 'title');
    $organization_items = ArrayHelper::merge($organization_null, $organization_items);
}
if (Yii::$app->user->can('subject_minobr'))
{
    $municipalities = \common\models\Municipality::find()->where(['id' => Organization::findOne($organization_id)->municipality_id])->all();
    $municipality_items = ArrayHelper::map($municipalities, 'id', 'name');

    $organization = Organization::find()->where(['type_org' => 3, 'municipality_id' => Organization::findOne($organization_id)->municipality_id])->all();
    $organization_null = array(0 => 'Все организации ...');
    $organization_items = ArrayHelper::map($organization, 'id', 'title');
    $organization_items = ArrayHelper::merge($organization_null, $organization_items);
}

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
    $organization = Organization::find()->where(['id' => $ids])->andWhere(['!=', 'id', 7])->all();

    $organization_null = array(0 => 'Все организации ...');
    $organization_items = ArrayHelper::map($organization, 'id', 'title');
    $organization_items = ArrayHelper::merge($organization_null, $organization_items);
}

if (!empty($post))
{

    $params_date_start = $post['date_start'];
    $params_date_end = ['class' => 'form-control', 'options' => [$post['date_end'] => ['Selected' => true]]];
    $params_field = ['class' => 'form-control', 'options' => [$post['field'] => ['Selected' => true]]];
    $params_org = ['class' => 'form-control', 'options' => [$post['field2'] => ['Selected' => true]]];



    $organization = Organization::find()->where(['type_org' => 3, 'municipality_id' => $post['field2']])->all();
    $organization_null = array(0 => 'Все организации ...');
    $organization_items = ArrayHelper::map($organization, 'id', 'title');
    $organization_items = ArrayHelper::merge($organization_null, $organization_items);
}
else{
    $params_field = ['class' => 'form-control', 'options' => [1 => ['Selected' => true]]];
    $params_date_start = '01.09.2021';
}
//print_r($organizations);exit;
?>
<style>
    th, td {
        border: 1px solid black!important;
        color: black;

    }
    th {
        background-color: #ede8b9;
        font-size: 13px;
    }
</style>
<h1 class="text-center"><?= Html::encode($this->title) ?></h1>
<!--<p class="text-danger text-center" style="font-size: 25px;"><b>ОТЧЕТ НА ТЕХНИЧЕСКОЙ ДОРАБОТКЕ ДО 11:40 по Новосибирскому времени. В указанный период времени могут быть ошибки и неверные данные!</b></p>-->


<?php $form = ActiveForm::begin([]); ?>
<div class="container mb-5 mt-5">
    <? if (Yii::$app->user->can('minobr') || Yii::$app->user->can('rospotrebnadzor_nutrition') || Yii::$app->user->can('subject_minobr') || Yii::$app->user->can('food_director')) { ?>
        <div class="row">
            <? if (!Yii::$app->user->can('food_director')) { ?>
                <div class="col-md-6">
                    <?= $form->field($model, 'field2')->dropDownList($municipality_items, [
                        'class' => 'form-control', 'options' => [$post['field2'] => ['Selected' => true]],
                        'onchange' => '
              $.get("../menus/orglist?id="+$(this).val(), function(data){
                $("select#dateform-field3").html(data);
              });'
                    ])->label('Муниципальный округ'); ?>
                </div>
            <?}?>
            <div class="<? if (!Yii::$app->user->can('food_director')) { echo 'col-md-6';}else{echo 'col-md-12';}?>">
                <?= $form->field($model, 'field3')->dropDownList($organization_items, [
                    'class' => 'form-control', 'options' => [$post['field3'] => ['Selected' => true]],
                ])->label('Организация'); ?>
            </div>
        </div>
    <?}?>
    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'date_start')->textInput(['class'=>'datepicker-here form-control', 'autocomplete' => 'off', 'value' => $params_date_start])->label('Начало периода'); ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'date_end')->textInput(['class'=>'datepicker-here form-control', 'autocomplete' => 'off', 'value' => $post['date_end']])->label('Конец периода'); ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'field')->dropDownList($yes_no_items, $params_field)->label('Вид контроля'); ?>
        </div>
    </div>
    <p class="text-center text-danger"><b>Не обязательно указывать дату начала или окончания периода. Если оставить поле(я) пустым, то будут отображены все данные без ограничений по дате.</b></p>
    <div class="row">
        <div class="form-group" style="margin: 0 auto">
            <?= Html::submitButton('Посмотреть', ['name' => 'identificator', 'value' => 'view', 'class' => 'btn main-button-3 beforeload mt-3']) ?>
            <button class="btn main-button-3 load mt-3" type="button" disabled style="display: none">
                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                Посмотреть...
            </button>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
<div>
    <? if ($post){ $itog=[];

        if($post['field'] == 3){
            $field[] = 1;
            $field[] = 2;
        }else{
            $field[] = $post['field'];
        }


        if($post['field2'] == 0 && isset($post['field2'])){
            $report_municipalities = \common\models\Municipality::find()->where(['region_id' => $region_id])->all();
        }
        elseif ($post['field2'] > 0){
            $report_municipalities = \common\models\Municipality::find()->where(['id' => $post['field2']])->all();
        }
        else{
            $post['field2'] = 111111;
            $report_municipalities = \common\models\Municipality::find()->where(['id' => $municipality_one])->all();
        }
        ?>



        <p><b>Максимальное количество баллов по тесту, которое можно набрать:26</b></p>
        <p><b>Максимальное количество баллов по пищеблоку, которое можно набрать:12</b></p>
        <?if ($post['field2'] > 0){?>
            <p class="text-center text-primary">Наведите курсором на Вопрос 1(2,3,4,5,6 и т.д), чтобы узнать формулировку </p>
            <table id="table_control" class="table_th0 table-hover table2excel_with_colors" style="width: 100%;">
            <thead>
            <tr>
                <th class="text-center align-middle" rowspan="2" style="width: 20px">№/ID</th>
                <th class="text-center align-middle" rowspan="2" style="width: 40px">Мун. район</th>
                <th class="text-center align-middle" rowspan="2" style="width: 40px">Организация</th>
                <th class="text-center align-middle" rowspan="2" style="width: 40px">Дата</th>
                <th class="text-center align-middle" rowspan="2" style="width: 70px">День по циклу</th>
                <th class="text-center align-middle" rowspan="2" style="width: 70px">Перемена</th>
                <th class="text-center align-middle" rowspan="2" style="width: 70px">Количество питающихся детей</th>
                <th class="text-center align-middle" rowspan="2" style="width: 70px">Процент несъеденной пищи</th>
                <th class="text-center align-middle" rowspan="2" style="width: 70px">Количество баллов по тесту</th>
                <th class="text-center align-middle" rowspan="2" style="width: 70px">Количество баллов по пищеблоку</th>
                <th class="text-center align-middle" rowspan="2" style="width: 70px">Количество баллов за весь контроль</th>
                <th class="text-center align-middle" colspan="2" style="width: 70px" data-toggle="tooltip" data-placement="top" title="Соответствует ли фактическое меню, объемы порций двухнедельному меню, утвержденному руководителем образовательной организации?">Вопрос 1</th>
                <th class="text-center align-middle" colspan="2" style="width: 70px" data-toggle="tooltip" data-placement="top" title="Организовано ли питание детей, требующих индивидуального подхода в организации питания с учетом имеющихся нарушений здоровья (сахарный диабет, целиакия, пищевая аллергия)">Вопрос 2</th>
                <th class="text-center align-middle" colspan="2" style="width: 70px" data-toggle="tooltip" data-placement="top" title="Все ли дети с сахарным диабетом, пищевой аллергией, ОВЗ, фенилкетонурией, целиакией, муковисцидозом питаются в столовой?">Вопрос 3</th>
                <th class="text-center align-middle" colspan="2" style="width: 70px" data-toggle="tooltip" data-placement="top" title="Все ли дети моют руки перед едой?">Вопрос 4</th>
                <th class="text-center align-middle" colspan="2" style="width: 70px" data-toggle="tooltip" data-placement="top" title="Созданы ли условия для мытья и дезинфекции рук?">Вопрос 5</th>
                <th class="text-center align-middle" colspan="2" style="width: 70px" data-toggle="tooltip" data-placement="top" title="Все ли дети едят сидя?">Вопрос 6</th>
                <th class="text-center align-middle" colspan="2" style="width: 70px" data-toggle="tooltip" data-placement="top" title="Все ли дети успевают поесть за перемену (хватает ли времени для приема пищи)?">Вопрос 7</th>
                <th class="text-center align-middle" colspan="2" style="width: 70px" data-toggle="tooltip" data-placement="top" title="Есть ли замечания по чистоте посуды?">Вопрос 8</th>
                <th class="text-center align-middle" colspan="2" style="width: 70px" data-toggle="tooltip" data-placement="top" title="Есть ли замечания по чистоте столов?">Вопрос 9</th>
                <th class="text-center align-middle" colspan="2" style="width: 70px" data-toggle="tooltip" data-placement="top" title="Есть ли замечания к сервировке столов?">Вопрос 10</th>
                <th class="text-center align-middle" colspan="2" style="width: 70px" data-toggle="tooltip" data-placement="top" title="Теплые ли блюда выдаются детям?">Вопрос 11</th>
<!--                <th class="text-center align-middle" colspan="2" style="width: 70px" data-toggle="tooltip" data-placement="top" title="Участвуют ли дети в накрывании на столы?">Вопрос 12</th>-->
                <th class="text-center align-middle" colspan="2" style="width: 70px" data-toggle="tooltip" data-placement="top" title="Лица, накрывающие на столы, работают в специальной одежде (халат, головной убор)?">Вопрос 12</th>
                <th class="text-center align-middle" colspan="2" style="width: 70px" data-toggle="tooltip" data-placement="top" title="Организовано ли наряду с основным питанием дополнительное питание (возможность самостоятельного приобретения блюд через линию раздачи или буфет)?">Вопрос 13</th>
            </tr>
            <tr>
                <th class="text-center align-middle" style="width: 70px">Ответ</th>
                <th class="text-center align-middle" style="width: 70px">Баллы</th>
                <th class="text-center align-middle" style="width: 70px">Ответ</th>
                <th class="text-center align-middle" style="width: 70px">Баллы</th>
                <th class="text-center align-middle" style="width: 70px">Ответ</th>
                <th class="text-center align-middle" style="width: 70px">Баллы</th>
                <th class="text-center align-middle" style="width: 70px">Ответ</th>
                <th class="text-center align-middle" style="width: 70px">Баллы</th>
                <th class="text-center align-middle" style="width: 70px">Ответ</th>
                <th class="text-center align-middle" style="width: 70px">Баллы</th>
                <th class="text-center align-middle" style="width: 70px">Ответ</th>
                <th class="text-center align-middle" style="width: 70px">Баллы</th>
                <th class="text-center align-middle" style="width: 70px">Ответ</th>
                <th class="text-center align-middle" style="width: 70px">Баллы</th>
                <th class="text-center align-middle" style="width: 70px">Ответ</th>
                <th class="text-center align-middle" style="width: 70px">Баллы</th>

                <th class="text-center align-middle" style="width: 70px">Ответ</th>
                <th class="text-center align-middle" style="width: 70px">Баллы</th>
                <th class="text-center align-middle" style="width: 70px">Ответ</th>
                <th class="text-center align-middle" style="width: 70px">Баллы</th>
                <th class="text-center align-middle" style="width: 70px">Ответ</th>
                <th class="text-center align-middle" style="width: 70px">Баллы</th>
<!--                <th class="text-center align-middle" style="width: 70px">Ответ</th>-->
<!--                <th class="text-center align-middle" style="width: 70px">Баллы</th>-->
                <th class="text-center align-middle" style="width: 70px">Ответ</th>
                <th class="text-center align-middle" style="width: 70px">Баллы</th>
                <th class="text-center align-middle" style="width: 70px">Ответ</th>
                <th class="text-center align-middle" style="width: 70px">Баллы</th>
            </tr>

            </thead>
            <tbody>
        <?}?>

        <?$bad_organization_count = []; $super_count = 0;$smena_peremens = []; $smena_peremens_procent = [];  $days_count = []; $days_procent = []; $days_max_procent = []; $peremens_max_procent = [];

        foreach($report_municipalities as $r_municipality){ $count_org_procent = [];?>
            <?if (Yii::$app->user->can('minobr') || Yii::$app->user->can('rospotrebnadzor_nutrition') || Yii::$app->user->can('subject_minobr') || Yii::$app->user->can('food_director')){
                if($post['field3'] == 0){
                    if(!Yii::$app->user->can('food_director')){
                        $organizations = Organization::find()->where(['type_org' => 3, 'municipality_id' => $r_municipality->id])->andWhere(['!=', 'id', 7])->all();
                    }else{
                        $organizations = Organization::find()->where(['type_org' => 3, 'id' => $ids])->andWhere(['!=', 'id', 7])->all();
                    }
                }
                else{
                    $organizations = Organization::find()->where(['id' => $post['field3']])->andWhere(['!=', 'id', 7])->all();
                }
            }else{
                $organizations = Organization::find()->where(['id' => Yii::$app->user->identity->organization_id])->andWhere(['!=', 'id', 7])->all();

            }
            ?>
            <?$count_org = 0;$bad_organization = []; $itog[$r_municipality->id]['count_organization'] = 0;foreach($organizations as $organization){ ?>
                <?/*if($post['field'] == 3){
                        $post['field'] = "[1,2]";
                    }*/
                if(empty($post['date_start']) && !empty($post['date_end'])){
                    $models = AnketParentControl::find()->where(['organization_id' => $organization->id, 'status'=>$field])->andWhere(['<=',  'date', strtotime($post['date_end'])])->orderBy(['date'=> SORT_ASC])->all();
                }
                elseif(!empty($post['date_start']) && empty($post['date_end'])){
                    $models = AnketParentControl::find()->where(['organization_id' => $organization->id, 'status'=>$field])->andWhere(['>=', 'date', strtotime($post['date_start'])])->orderBy(['date'=> SORT_ASC])->all();
                }
                elseif(empty($post['date_start']) && empty($post['date_end'])){
                    $models = AnketParentControl::find()->where(['organization_id' => $organization->id, 'status'=>$field])->orderBy(['date'=> SORT_ASC])->all();
                }
                else{
                    $models = AnketParentControl::find()->where(['organization_id' => $organization->id, 'status'=>$field])->andWhere(['>=', 'date', strtotime($post['date_start'])])->andWhere(['<=',  'date', strtotime($post['date_end'])])->orderBy(['date'=> SORT_ASC])->all();
                }

                ?>
                <? if(!empty($models)){$count_org++; $super_count++;} $count = 0; $result_on_organization = []; foreach ($models as $model) { $count++; $procent = $model->procent;?>

                    <?$date_items[] = date('d.m.Y',$model->date)?>
                    <?$procent_items[] = $procent;?>



                    <!--Выносим логику по расчету баллов из ячеек в буферную часть и вместо 14 строчек будет одна через форич-->
                    <?$question_value[1] = $model->question1;$question_value[2] = $model->question2;$question_value[3] = $model->question3;$question_value[4] = $model->question4;$question_value[5] = $model->question5;$question_value[6] = $model->question6;$question_value[7] = $model->question7;$question_value[8] = $model->question8;$question_value[9] = $model->question9;$question_value[10] = $model->question10;$question_value[11] = $model->question11;/*$question_value[12] = $model->question12;*/$question_value[13] = $model->question13;$question_value[14] = $model->question14;?>
                    <?foreach($items as $i){

                        $current_ball[$i] = $model->yes_no($i, $question_value[$i], 'ball');//по текущему контролю
                        $result_on_organization[$i] = $result_on_organization[$i] + $current_ball[$i];//по организации
                        if($current_ball[$i] == 2){$itog[$r_municipality->id][$i] = $itog[$r_municipality->id][$i] + 1;}//по итоговой строке муниципального района
                    }
                    $itog[$r_municipality->id]['count'] = $itog[$r_municipality->id]['count'] + 1;
                    $smena_peremens[$model->smena.'_'.$model->peremena] = $smena_peremens[$model->smena.'_'.$model->peremena] + 1;

                    $day_key = date('w',$model->date); if($day_key == 0) $day_key = 7;
                    $days_count[$day_key] = $days_count[$day_key] + 1;
                    $days_procent[$day_key] = $days_procent[$day_key] + $procent;

                    /*сборка массива для графика по максимальным процентам в днях*/
                    if($procent > $days_max_procent[$day_key]){
                        $days_max_procent[$day_key] = $procent;
                    }

                    /*сборка массива для графика по максимальным процентам в переменах*/
                    if($procent > $peremens_max_procent[$model->smena.'_'.$model->peremena]){
                        $peremens_max_procent[$model->smena.'_'.$model->peremena] = $procent;
                    }

                    if($procent > $itog[$organization->municipality_id]['max_procent']){
                        $itog[$organization->municipality_id]['max_procent'] = $procent;
                    }



                    $result_on_organization['count'] = $result_on_organization['count'] + $model->count;
                    $result_on_organization['procent'] = $result_on_organization['procent'] + $procent;
                    $smena_peremens_procent[$model->smena.'_'.$model->peremena] = $smena_peremens_procent[$model->smena.'_'.$model->peremena] + $procent;
                    $test = $model->test; $result_on_organization['test'] = $result_on_organization['test'] + $test;
                    $test_food = $model->test_food; $result_on_organization['test_food'] = $result_on_organization['test_food'] + $test_food;

                    ?>
                    <?if ($post['field2'] > 0){?>
                        <tr>
                            <td class="text-center align-middle"><?= $count.'/'.$model->id;?></td>
                            <td class="text-center align-middle" style="font-size: 13px;"><?=\common\models\Municipality::findOne($organization->municipality_id)->name;?></td>
                            <td class="align-middle" style="font-size: 13px;"><?echo (empty($organization->short_title)) ? $organization->title : $organization->short_title;?></td>
                            <td class="text-center align-middle"><?= date('d.m.Y',$model->date) ?></td>
                            <td class="text-center align-middle"><? $date = date('w',$model->date); if($date == 0) echo 7; else echo $date; ?></td>
                            <td class="text-center align-middle"><?=$model->peremena ?></td>
                            <td class="text-center align-middle"><?= $model->count;?></td>
                            <td class="text-center align-middle"><?  echo number_format($procent, 1, ',', '')?></td>
                            <td class="text-center align-middle"><?=number_format($test, 1, ',', '');?></td>
                            <td class="text-center align-middle"><?=number_format($test_food, 1, ',', '');?></td>
                            <td class="text-center align-middle"><? echo $test + $test_food;?></td>
                            <td class="text-center align-middle"><?= $model->yes_no(1, $model->question1, 'answer');?></td>
                            <td class="text-center align-middle"><?=$current_ball[1];?></td>
                            <td class="text-center align-middle"><?= $model->yes_no(2, $model->question2, 'answer');?></td>
                            <td class="text-center align-middle"><?=$current_ball[2];?></td>
                            <td class="text-center align-middle"><?= $model->yes_no(3, $model->question3, 'answer');?></td>
                            <td class="text-center align-middle"><?=$current_ball[3];?></td>
                            <td class="text-center align-middle"><?= $model->yes_no(4, $model->question4, 'answer');?></td>
                            <td class="text-center align-middle"><?=$current_ball[4];?></td>

                            <td class="text-center align-middle"><?= $model->yes_no(5, $model->question5, 'answer');?></td>
                            <td class="text-center align-middle"><?=$current_ball[5];?></td>
                            <td class="text-center align-middle"><?= $model->yes_no(6, $model->question6, 'answer');?></td>
                            <td class="text-center align-middle"><?=$current_ball[6];?></td>
                            <td class="text-center align-middle"><?= $model->yes_no(7, $model->question7, 'answer');?></td>
                            <td class="text-center align-middle"><?=$current_ball[7];?></td>
                            <td class="text-center align-middle"><?= $model->yes_no(8, $model->question8, 'answer');?></td>
                            <td class="text-center align-middle"><?=$current_ball[8];?></td>

                            <td class="text-center align-middle"><?= $model->yes_no(9, $model->question9, 'answer');?></td>
                            <td class="text-center align-middle"><?=$current_ball[9];?></td>
                            <td class="text-center align-middle"><?= $model->yes_no(10, $model->question10, 'answer');?></td>
                            <td class="text-center align-middle"><?=$current_ball[10];?></td>
                            <td class="text-center align-middle"><?= $model->yes_no(11, $model->question11, 'answer');?></td>
                            <td class="text-center align-middle"><?=$current_ball[11];?></td>
<!--                            <td class="text-center align-middle">--><?//= $model->yes_no(12, $model->question12, 'answer');?><!--</td>-->
<!--                            <td class="text-center align-middle">--><?//=$current_ball[12];?><!--</td>-->

                            <td class="text-center align-middle"><?= $model->yes_no(13, $model->question13, 'answer');?></td>
                            <td class="text-center align-middle"><?=$current_ball[13];?></td>
                            <td class="text-center align-middle"><?= $model->yes_no(14, $model->question14, 'answer');?></td>
                            <td class="text-center align-middle"><?=$current_ball[14];?></td>
                        </tr>
                    <?}?>
                <?}?>
                <?if($count > 0){ $itog[$r_municipality->id]['count_organization'] = $itog[$r_municipality->id]['count_organization'] +1;?>

                    <?$count_org_count[] = $count;
                    $count_org_procent[] = round($result_on_organization['procent']/$count, 1);
                    $count_org_title[] = (empty($organization->short_title)) ? $organization->title : $organization->short_title;
                    $itog[$r_municipality->id]['test'] = $itog[$r_municipality->id]['test'] + round($result_on_organization['test']/$count, 1);
                    $itog[$r_municipality->id]['procent'] = $itog[$r_municipality->id]['procent'] + round($result_on_organization['procent']/$count, 1);
                    $itog[$r_municipality->id]['test_food'] = $itog[$r_municipality->id]['test_food'] + round($result_on_organization['test_food']/$count, 1);

                    ?>
                    <?if ($post['field2'] > 0){?>
                        <tr class="table-danger">
                            <td class="" colspan="7">Среднее значение за период по <?echo (empty($organization->short_title)) ? $organization->title : $organization->short_title;?>:</td>
                            <td class="text-center align-middle"><?= number_format($result_on_organization['procent']/$count, 1, ',', '')?></td>
                            <td class="text-center align-middle"><?= number_format($result_on_organization['test']/$count, 1, ',', ''); ?></td>
                            <td class="text-center align-middle"><?= number_format($result_on_organization['test_food']/$count, 1, ',', '');?></td>
                            <td class="text-center align-middle"><?= number_format(($result_on_organization['test_food']/$count + $result_on_organization['test']/$count), 1, ',', '');?></td>
                            <td class="text-center align-middle" colspan="2"><?= round($result_on_organization['1']/$count, 1);?></td>
                            <td class="text-center align-middle" colspan="2"><?= round($result_on_organization['2']/$count, 1);?></td>
                            <td class="text-center align-middle" colspan="2"><?= round($result_on_organization['3']/$count, 1);?></td>
                            <td class="text-center align-middle" colspan="2"><?= round($result_on_organization['4']/$count, 1);?></td>
                            <td class="text-center align-middle" colspan="2"><?= round($result_on_organization['5']/$count, 1);?></td>
                            <td class="text-center align-middle" colspan="2"><?= round($result_on_organization['6']/$count, 1);?></td>
                            <td class="text-center align-middle" colspan="2"><?= round($result_on_organization['7']/$count, 1);?></td>
                            <td class="text-center align-middle" colspan="2"><?= round($result_on_organization['8']/$count, 1);?></td>
                            <td class="text-center align-middle" colspan="2"><?= round($result_on_organization['9']/$count, 1);?></td>
                            <td class="text-center align-middle" colspan="2"><?= round($result_on_organization['10']/$count, 1);?></td>
                            <td class="text-center align-middle" colspan="2"><?= round($result_on_organization['11']/$count, 1);?></td>
<!--                            <td class="text-center align-middle" colspan="2">--><?//= round($result_on_organization['12']/$count, 1);?><!--</td>-->
                            <td class="text-center align-middle" colspan="2"><?= round($result_on_organization['13']/$count, 1);?></td>
                            <td class="text-center align-middle" colspan="2"><?= round($result_on_organization['14']/$count, 1);?></td>
                        </tr>
                    <?}?>
                <?}else{?>
                    <?$bad_organization[$organization->id] = (empty($organization->short_title)) ? $organization->title : $organization->short_title;
                      $itog[$r_municipality->id]['count_neok'] = $itog[$r_municipality->id]['count_neok'] + 1;
                    ?>
                <?}?>
            <?}

            /*if($count_org_procent){
                $itog[$r_municipality->id]['max_procent'] = max($count_org_procent);
            }*/
            ?>
        <?}?>

        </tbody>
        </table>
        <?if ($post['field2'] > 0){?>
            <div class="text-center mt-3 mb-3">
                <button id="pechat_control" class="btn btn-success">
                    <span class="glyphicon glyphicon-download"></span> Скачать детальный отчет в Excel
                </button>
            </div>
        <?}?>
        <hr><br><br>
        <? if ((Yii::$app->user->can('minobr') || Yii::$app->user->can('rospotrebnadzor_nutrition') || Yii::$app->user->can('subject_minobr')) && $post['field3'] == 0) { ?>

            <p class="text-center"><b>ИТОГ ПО МУНИЦИПАЛЬНЫМ РАЙОНАМ </b></p>
            <table id="tableId" class="table_th0 table-hover table2excel_with_colors" style="width: 100%;">
                <thead>
                <tr>
                    <th class="text-center align-middle" rowspan="2" style="width: 50px">Мун. район</th>
                    <th class="text-center align-middle" rowspan="2" style="width: 40px">Количество мероприятий</th>
                    <th class="text-center align-middle" rowspan="2" style="width: 40px">Количество организаций внесших контроли</th>
                    <th class="text-center align-middle" rowspan="2" style="width: 40px">Количество организаций не внесших контроли</th>
                    <th class="text-center align-middle" rowspan="2" style="width: 40px">Процент несъеденной пищи</th>
                    <th class="text-center align-middle" rowspan="2" style="width: 40px">Максимальный процент несъеденной пищи</th>
                    <th class="text-center align-middle" rowspan="2" style="width: 40px">Количество баллов по тесту</th>
                    <th class="text-center align-middle" rowspan="2" style="width: 40px">Количество баллов по пищеблоку</th>
                    <th class="text-center align-middle" rowspan="2" style="width: 40px">Количество баллов за весь контроль</th>
                    <th class="text-center align-middle" rowspan="1" colspan="13">Положительных ответов</th>
                </tr>
                <tr>
                    <th class="text-center align-middle" colspan="1" style="width: 70px" data-toggle="tooltip" data-placement="top" title="Соответствует ли фактическое меню, объемы порций двухнедельному меню, утвержденному руководителем образовательной организации?">Вопрос 1</th>
                    <th class="text-center align-middle" colspan="1" style="width: 70px" data-toggle="tooltip" data-placement="top" title="Организовано ли питание детей, требующих индивидуального подхода в организации питания с учетом имеющихся нарушений здоровья (сахарный диабет, целиакия, пищевая аллергия)">Вопрос 2</th>
                    <th class="text-center align-middle" colspan="1" style="width: 70px" data-toggle="tooltip" data-placement="top" title="Все ли дети с сахарным диабетом, пищевой аллергией, ОВЗ, фенилкетонурией, целиакией, муковисцидозом питаются в столовой?">Вопрос 3</th>
                    <th class="text-center align-middle" colspan="1" style="width: 70px" data-toggle="tooltip" data-placement="top" title="Все ли дети моют руки перед едой?">Вопрос 4</th>
                    <th class="text-center align-middle" colspan="1" style="width: 70px" data-toggle="tooltip" data-placement="top" title="Созданы ли условия для мытья и дезинфекции рук?">Вопрос 5</th>
                    <th class="text-center align-middle" colspan="1" style="width: 70px" data-toggle="tooltip" data-placement="top" title="Все ли дети едят сидя?">Вопрос 6</th>
                    <th class="text-center align-middle" colspan="1" style="width: 70px" data-toggle="tooltip" data-placement="top" title="Все ли дети успевают поесть за перемену (хватает ли времени для приема пищи)?">Вопрос 7</th>
                    <th class="text-center align-middle" colspan="1" style="width: 70px" data-toggle="tooltip" data-placement="top" title="Есть ли замечания по чистоте посуды?">Вопрос 8</th>
                    <th class="text-center align-middle" colspan="1" style="width: 70px" data-toggle="tooltip" data-placement="top" title="Есть ли замечания по чистоте столов?">Вопрос 9</th>
                    <th class="text-center align-middle" colspan="1" style="width: 70px" data-toggle="tooltip" data-placement="top" title="Есть ли замечания к сервировке столов?">Вопрос 10</th>
                    <th class="text-center align-middle" colspan="1" style="width: 70px" data-toggle="tooltip" data-placement="top" title="Теплые ли блюда выдаются детям?">Вопрос 11</th>
<!--                    <th class="text-center align-middle" colspan="1" style="width: 70px" data-toggle="tooltip" data-placement="top" title="Участвуют ли дети в накрывании на столы?">Вопрос 12</th>-->
                    <th class="text-center align-middle" colspan="1" style="width: 70px" data-toggle="tooltip" data-placement="top" title="Лица, накрывающие на столы, работают в специальной одежде (халат, головной убор)?">Вопрос 12</th>
                    <th class="text-center align-middle" colspan="1" style="width: 70px" data-toggle="tooltip" data-placement="top" title="Организовано ли наряду с основным питанием дополнительное питание (возможность самостоятельного приобретения блюд через линию раздачи или буфет)?">Вопрос 13</th>
                </tr>

                </thead>
                <tbody>
                <?$super_itog = []; $mun_mas_graph = []; foreach($report_municipalities as $r_municipality){
                    $mun_mas_graph[] = $r_municipality->name;
                    $mun_mas_count[] = $itog[$r_municipality->id]['count']?>
                    <tr class="table-warning">
                        <?if($itog[$r_municipality->id]['count_organization'] > 0){?>

                            <?
                            foreach($items as $i){
                                $super_itog[$i] = $super_itog[$i] + $itog[$r_municipality->id][$i];//баллы
                            }
                            $super_itog['count'] = $super_itog['count'] + $itog[$r_municipality->id]['count'];
                            $super_itog['procent'] = $super_itog['procent'] + round($itog[$r_municipality->id]['procent']/$itog[$r_municipality->id]['count_organization'],1);
                            if($super_itog['max_procent'] < $itog[$r_municipality->id]['max_procent']){
                                $super_itog['max_procent'] = $itog[$r_municipality->id]['max_procent'];
                            }
                            $super_itog['test'] = $super_itog['test'] + round($itog[$r_municipality->id]['test']/$itog[$r_municipality->id]['count_organization'],1);
                            $super_itog['test_food'] = $super_itog['test_food'] + round($itog[$r_municipality->id]['test_food']/$itog[$r_municipality->id]['count_organization'],1);
                            $super_itog['count_municipality'] = $super_itog['count_municipality']+1;


                            $super_itog['count_ok'] = $super_itog['count_ok']+Organization::find()->where(['municipality_id' => $r_municipality->id, 'type_org' => 3])->count() - $itog[$r_municipality->id]['count_neok'];
                            $super_itog['count_neok'] = $super_itog['count_neok'] + $itog[$r_municipality->id]['count_neok'];

                            //$count_ok = Organization::find()->where(['municipality_id' => $r_municipality->id, 'type_org' => 3])->count() - count($bad_organization);$super_itog['count_ok'] = $super_itog['count_ok'] + $count_ok;
                            //$count_neok = count($bad_organization); $super_itog['count_neok'] = $super_itog['count_neok'] + $count_neok;
                            ?>




                            <td class="" colspan="1"><?=$r_municipality->name;?></td>
                            <td class="text-center align-middle"><?= $itog[$r_municipality->id]['count']?></td>
                            <td class="text-center align-middle"><?= Organization::find()->where(['municipality_id' => $r_municipality->id, 'type_org' => 3])->count() - $itog[$r_municipality->id]['count_neok']?></td>
                            <td class="text-center align-middle"><?= $itog[$r_municipality->id]['count_neok']?></td>
                            <td class="text-center align-middle"><?=  number_format($itog[$r_municipality->id]['procent']/$itog[$r_municipality->id]['count_organization'], 1, ',', '')?></td>
                            <td class="text-center align-middle"><?=  number_format($itog[$r_municipality->id]['max_procent'], 1, ',', '')?></td>
                            <td class="text-center align-middle"><?= number_format($itog[$r_municipality->id]['test']/$itog[$r_municipality->id]['count_organization'], 1, ',', '')?></td>
                            <td class="text-center align-middle"><?= number_format($itog[$r_municipality->id]['test_food']/$itog[$r_municipality->id]['count_organization'], 1, ',', '')?></td>
                            <td class="text-center align-middle"><?= number_format(($itog[$r_municipality->id]['test']/$itog[$r_municipality->id]['count_organization'] + $itog[$r_municipality->id]['test_food']/$itog[$r_municipality->id]['count_organization']), 1, ',', '')?></td>
                            <td class="text-center align-middle"><?= $itog[$r_municipality->id]['1']?></td>
                            <td class="text-center align-middle"><?= $itog[$r_municipality->id]['2']?></td>
                            <td class="text-center align-middle"><?= $itog[$r_municipality->id]['3']?></td>
                            <td class="text-center align-middle"><?= $itog[$r_municipality->id]['4']?></td>
                            <td class="text-center align-middle"><?= $itog[$r_municipality->id]['5']?></td>
                            <td class="text-center align-middle"><?= $itog[$r_municipality->id]['6']?></td>
                            <td class="text-center align-middle"><?= $itog[$r_municipality->id]['7']?></td>
                            <td class="text-center align-middle"><?= $itog[$r_municipality->id]['8']?></td>
                            <td class="text-center align-middle"><?= $itog[$r_municipality->id]['9']?></td>
                            <td class="text-center align-middle"><?= $itog[$r_municipality->id]['10']?></td>
                            <td class="text-center align-middle"><?= $itog[$r_municipality->id]['11']?></td>
<!--                            <td class="text-center align-middle">--><?//= $itog[$r_municipality->id]['12']?><!--</td>-->
                            <td class="text-center align-middle"><?= $itog[$r_municipality->id]['13']?></td>
                            <td class="text-center align-middle"><?= $itog[$r_municipality->id]['14']?></td>
                        <?}else{?>
                            <td class="" colspan="1"><?=$r_municipality->name;?></td>
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
                <?if($post['field2'] == 0 && isset($post['field2'])){?>
                    <tr class="table-info">
                        <td class="" colspan="1">ИТОГО <?=Region::findOne(\common\models\Municipality::findOne($r_municipality->id)->region_id)->name;?></td>
                        <td class="text-center align-middle"><?=  $super_itog['count']?></td>
                        <td class="text-center align-middle"><?=  $super_itog['count_ok']?></td>
                        <td class="text-center align-middle"><?=  $super_itog['count_neok']?></td>
                        <td class="text-center align-middle"><?=  number_format($super_itog['procent']/$super_itog['count_municipality'], 1, ',', '')?></td>
                        <td class="text-center align-middle"><?=  number_format($super_itog['max_procent'], 1, ',', '')?></td>
                        <td class="text-center align-middle"><?= number_format($super_itog['test']/$super_itog['count_municipality'], 1, ',', '')?></td>
                        <td class="text-center align-middle"><?= number_format($super_itog['test_food']/$super_itog['count_municipality'], 1, ',', '')?></td>
                        <td class="text-center align-middle"><?= number_format(($super_itog['test']/$super_itog['count_municipality'] + $super_itog['test_food']/$super_itog['count_municipality']), 1, ',', '')?></td>
                        <td class="text-center align-middle"><?= $super_itog['1']?></td>
                        <td class="text-center align-middle"><?= $super_itog['2']?></td>
                        <td class="text-center align-middle"><?= $super_itog['3']?></td>
                        <td class="text-center align-middle"><?= $super_itog['4']?></td>
                        <td class="text-center align-middle"><?= $super_itog['5']?></td>
                        <td class="text-center align-middle"><?= $super_itog['6']?></td>
                        <td class="text-center align-middle"><?= $super_itog['7']?></td>
                        <td class="text-center align-middle"><?= $super_itog['8']?></td>
                        <td class="text-center align-middle"><?= $super_itog['9']?></td>
                        <td class="text-center align-middle"><?= $super_itog['10']?></td>
                        <td class="text-center align-middle"><?= $super_itog['11']?></td>
<!--                        <td class="text-center align-middle">--><?//= $super_itog['12']?><!--</td>-->
                        <td class="text-center align-middle"><?= $super_itog['13']?></td>
                        <td class="text-center align-middle"><?= $super_itog['14']?></td>
                    </tr>
                <?}?>

                </tbody>
            </table>
            <div class="text-center mt-3">
                <button id="pechat_itog_mun" class="btn btn-success">
                    <span class="glyphicon glyphicon-download"></span> Скачать отчет по контрольным мероприятиям в Excel
                </button>
            </div>
        <?}?>


        <?$anket_parent_control_model = new AnketParentControl();?>
        <?if(empty($post['date_start'])){
            $post['date_start'] = 0;
        }?>
        <?if(empty($post['date_end'])){
            $post['date_end'] = 0;
        }?>

        <br>


        <?if($count_org == 0){?>
            <p class="text-center text-danger">Нет данных</p>
        <?}?>
<!--ЕСЛИ Я ДИРЕКТОР ИЛИ ВЫБРАНА ОДНА ШКОЛА-->
        <?if($count_org == 1){?>
            <div class="container">
                <div class="block" style="width: 100%!important;">
                    <canvas id="myChart" width="600" height="170"></canvas>
                </div>
            </div>
            <br>



            <?if(Yii::$app->user->can('school_director') || Yii::$app->user->can('foodworker') || Yii::$app->user->can('internat_director')){//одна орг?>
                <?$post['field3'] = Yii::$app->user->identity->organization_id;
                $post['field2'] = Organization::findOne(Yii::$app->user->identity->organization_id)->municipality_id;
            }?>
            <?$data_mun = $anket_parent_control_model->get_info_municipality2($post['field2'], $post['field'], $post['date_start'], $post['date_end'], 'municipality');?>
            <?//$data_mun = $anket_parent_control_model->get_info_municipality2(48, $post['field'], $post['date_start'], $post['date_end'], 'region');?>
            <?$data_org = $anket_parent_control_model->get_info_municipality2($post['field3'], $post['field'], $post['date_start'], $post['date_end'], 'organization');?>

            <?$bar_chart_school_label =\common\models\Organization::findOne($post['field3'])->title;?>
            <?$bar_chart_municipality_label =\common\models\Municipality::findOne($post['field2'])->name;?>
            <?foreach($correct_mas_for_mounth as $correct){?>
                <?if(!empty($data_org[$correct])){?>
                    <?$bar_chart_grouped_label[] =\common\models\Months::find()->where(['id_php' => $correct])->one()->name;?>
                    <?$bar_chart_procent_org_procent[] = $data_org[$correct]['procent'];?>
                    <?$bar_chart_procent_mun_procent[] = $data_mun[$correct]['procent'];?>
                    <?$bar_chart_procent_org_count[] = $data_org[$correct]['count'];?>
                    <?$bar_chart_procent_mun_count[] = $data_mun[$correct]['count'];?>
                    <?$bar_chart_procent_org_ball[] = $data_org[$correct]['ball'];?>
                    <?$bar_chart_procent_mun_ball[] = $data_mun[$correct]['ball'];?>
                <?}?>
            <?}?>
            <div class="container">
                <div class="block" style="width: 100%!important;">
                    <canvas id="bar-chart-procent" width="600" height="170"></canvas>
                </div>
            </div>
            <br>

            <div class="container">
                <div class="block" style="width: 100%!important;">
                    <canvas id="bar-chart-count" width="600" height="170"></canvas>

                </div>
            </div>

            <br>
            <div class="container">
                <div class="block" style="width: 100%!important;">
                    <canvas id="bar-chart-ball" width="600" height="170"></canvas>

                </div>
            </div>
        <?}?>



        <!--Количество по районам итоги-->
        <?if($post['field3'] == 0 && $post['field2'] == 0 && (Yii::$app->user->can('minobr') || !Yii::$app->user->can('subject_minobr'))){?>

            <div style="width: 80%">
                <p class="text-center"><b>Количество мероприятий в разрезе каждого муниципального района</b></p>
                <div class="block" style="width: 100%!important;">
                    <canvas id="organization_count_mun" width="700" height="220"></canvas>
                </div>
            </div>

        <?}?>

        <?if($post['field3'] == 0 && $post['field2'] > 0 && (!Yii::$app->user->can('school_director') && !Yii::$app->user->can('internat_director') && !Yii::$app->user->can('foodworker'))){?>

            <div style="width: 80%">
                <p class="text-center"><b>Количество проведенных мероприятий в разрезе каждой организации за указанный период времени</b></p>
                <div class="block" style="width: 100%!important;">
                    <canvas id="organization_count" width="700" height="220"></canvas>
                </div>
            </div>
            <hr>
            <div style="width: 80%">
                <p class="text-center"><b>Среднее значение несъеденной пищи по каждой организации в(%)</b></p>
                <div class="block" style="width: 100%!important;">
                    <canvas id="organization_sred_dinam" width="700" height="220"></canvas>
                </div>
            </div>
        <?}?>
<!--        ЕСЛИ ДЕПАРТАМЕНТ УПРАВЛЕНИЕ ВЫБРАЛО все школы РАЙОНА-->
        <?if(!Yii::$app->user->can('school_director') && !Yii::$app->user->can('foodworker') && !Yii::$app->user->can('internat_director') && $post['field3'] == 0){?>
            <?/*для графика средний процент по переменам*/foreach ($smena_peremens as $key => $smena_peremen){
                $key = explode('_',$key);
                $peremens_for_graphic[] = $key[0].' смена '.$key[1].'перемена';
                $count_for_graphic[] = $smena_peremen;
            }?>

            <?/*для графика по дням количество*/foreach ($days_count as $key => $day_count){
                $days_for_graphic[] = Days::findOne($key)->name;
                $days_count_for_graphic[] = $day_count;
            }?>
            <?/*для графика максимальный процент по дням*/foreach ($days_max_procent as $key => $day_max_procent){
                $days_for_graphic_max[] = Days::findOne($key)->name;
                $days_count_max_for_graphic[] = $day_max_procent;
                //$days_max_procent_table[] = $day_max_procent;
            }?>

            <?/*для графика максимальный процент по переменам*/foreach ($peremens_max_procent as $key => $peremen_max_procent){
                $key = explode('_',$key);
                $peremens_for_graphic_max[] = $key[0].' смена '.$key[1].' перемена';
                $peremens_count_max_for_graphic[] = $peremen_max_procent;
                //$days_max_procent_table[$key] = $peremen_max_procent;
            }?>
            <?//print_r($peremens_for_graphic);?>



            <?if($post['field2'] > 0){?>
                <?/*для двойных графиков муниципальный и регион*/
                 $data_mun = $anket_parent_control_model->get_info_municipality2($post['field2'], $post['field'], $post['date_start'], $post['date_end'], 'municipality');?>
                <?$data_reg = $anket_parent_control_model->get_info_municipality2($region_id, $post['field'], $post['date_start'], $post['date_end'], 'region');?>
                <?//print_r($data_mun);?>
                <?$bar_chart_municipality_label =\common\models\Municipality::findOne($post['field2'])->name;?>
                <?$bar_chart_region_label =\common\models\Region::findOne($region_id)->name;?>
                <?foreach($correct_mas_for_mounth as $correct){?>
                    <?if(!empty($data_mun[$correct])){?>
                        <?$bar_chart_grouped_label[] =\common\models\Months::find()->where(['id_php' => $correct])->one()->name;?>
                        <?$bar_chart_procent_mun_procent[] =$data_mun[$correct]['procent'];?>
                        <?$bar_chart_procent_reg_procent[] =$data_reg[$correct]['procent'];?>
                        <?$bar_chart_procent_mun_count[] =$data_mun[$correct]['count'];?>
                        <?$bar_chart_procent_reg_count[] =$data_reg[$correct]['count'];?>
                        <?$bar_chart_procent_mun_ball[] =$data_mun[$correct]['ball'];?>
                        <?$bar_chart_procent_reg_ball[] =$data_reg[$correct]['ball'];?>
                    <?}?>
                <?}?>
            <?}else{?>
                <?/*для двойных графиков муниципальный и регион*/
                 $data_reg = $anket_parent_control_model->get_info_municipality2($region_id, $post['field'], $post['date_start'], $post['date_end'], 'region');?>
                <?$bar_chart_region_label =\common\models\Region::findOne($region_id)->name;?>
                <?foreach($correct_mas_for_mounth as $correct){?>
                    <?if(!empty($data_reg[$correct])){?>
                        <?$bar_chart_grouped_label[] =\common\models\Months::find()->where(['id_php' => $correct])->one()->name;?>
                        <?$bar_chart_procent_reg_procent[] =$data_reg[$correct]['procent'];?>
                        <?$bar_chart_procent_reg_count[] =$data_reg[$correct]['count'];?>
                        <?$bar_chart_procent_reg_ball[] =$data_reg[$correct]['ball'];?>
                    <?}?>
                <?}?>
            <?}?>
                <div class="container">
                    <div class="block" style="width: 100%!important;">
                        <canvas id="bar-chart-mun-procent" width="600" height="170"></canvas>

                    </div>
                </div>
                <br>

                <div class="container">
                    <div class="block" style="width: 100%!important;">
                        <canvas id="bar-chart-mun-count" width="600" height="170"></canvas>

                    </div>
                </div>

                <br>
                <div class="container">
                    <div class="block" style="width: 100%!important;">
                        <canvas id="bar-chart-mun-ball" width="600" height="170"></canvas>

                    </div>
                </div>



            <div style="width: 60%">
                <p class="text-center"><b>Количество проведенных мероприятий в разрезе смены и перемены</b></p>
                <div class="block" style="width: 100%!important;">
                    <canvas id="peremens_count" width="700" height="220"></canvas>
                </div>
            </div>


            <?/*для графика по дням проценты*/foreach ($days_procent as $key => $day_procent){

                $days_procent_for_graphic[] = round($day_procent/$days_count[$key],1);
            }?>


            <?foreach ($smena_peremens_procent as $key => $procent){

                $procent_for_graphic[] = round($procent/$smena_peremens[$key],1);
            }?>
            <?//print_r($peremens_for_graphic);?>
            <hr>
            <div style="width: 60%">
                <p class="text-center"><b>Среднее значение несъеденной пищи в разрезе смены и перемены(%)</b></p>
                <div class="block" style="width: 100%!important;">
                    <canvas id="peremens_procent" width="700" height="220"></canvas>
                </div>
            </div>

            <hr>
            <div style="width: 60%">
                <p class="text-center"><b>Максимальное значение несъеденной пищи в разрезе смены и перемены(%)</b></p>
                <div class="block" style="width: 100%!important;">
                    <canvas id="peremens_procent_max" width="700" height="220"></canvas>
                </div>
            </div>


            <hr>
            <div style="width: 60%">
                <p class="text-center"><b>Количество мероприятий по дням недели</b></p>
                <div class="block" style="width: 100%!important;">
                    <canvas id="days_count" width="700" height="220"></canvas>
                </div>
            </div>


            <hr>
            <div style="width: 60%">
                <p class="text-center"><b>Cреднее значение несъеденной пищи по дням недели(%)</b></p>
                <div class="block" style="width: 100%!important;">
                    <canvas id="days_procent" width="700" height="220"></canvas>
                </div>
            </div>

            <hr>
            <div style="width: 60%">
                <p class="text-center"><b>Максимальное значение несъеденной пищи по дням недели(%)</b></p>
                <div class="block" style="width: 100%!important;">
                    <canvas id="days_procent_max" width="700" height="220"></canvas>
                </div>
            </div>
            <hr>
            <div class="text-center">
                <button class="btn btn-danger content_toggle text-center btn-lg">Посмотреть данные из графиков в табличном виде</button>
            </div>

            <!--Графики рисуем ввиде таблиц-->
            <div class="content_block mb-3 mt-3" style="display: none;">
                <div class="mb-3 mt-3" style="display: flex;justify-content: space-around">
                    <table class="table_th0 table-hover" style="width: 45%;">
                        <thead>
                        <tr>
                            <th class="text-center align-middle" rowspan="2" style="width: 20px">Смена/перемена</th>
                            <th class="text-center align-middle" rowspan="2" style="width: 40px">Количество мероприятий</th>
                            <th class="text-center align-middle" rowspan="2" style="width: 40px">Процент несъеденной пищи</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?foreach($peremens_for_graphic as $key => $label){?>
                            <tr>
                                <td class="text-center align-middle"><?= $label?></td>
                                <td class="text-center align-middle"><?= $count_for_graphic[$key]?></td>
                                <td class="text-center align-middle"><?= $procent_for_graphic[$key]?></td>
                            </tr>
                        <?}?>
                        </tbody>
                    </table>

                    <table class="table_th0 table-hover" style="width: 45%;">
                        <thead>
                        <tr>
                            <th class="text-center align-middle" rowspan="2" style="width: 20px">День недели</th>
                            <th class="text-center align-middle" rowspan="2" style="width: 40px">Количество мероприятий</th>
                            <th class="text-center align-middle" rowspan="2" style="width: 40px">Процент несъеденной пищи</th>
<!--                            <th class="text-center align-middle" rowspan="2" style="width: 40px">Максимальный процент несъеденной пищи</th>-->
                        </tr>
                        </thead>
                        <tbody>
                        <?foreach($days_for_graphic as $key => $label){?>
                            <tr>
                                <td class="text-center align-middle"><?= $label?></td>
                                <td class="text-center align-middle"><?= $days_count_for_graphic[$key]?></td>
                                <td class="text-center align-middle"><?= $days_procent_for_graphic[$key]?></td>
<!--                                <td class="text-center align-middle">--><?//= $days_max_procent_table[$key]?><!--</td>-->
                            </tr>
                        <?}?>
                        </tbody>
                    </table>
                </div>
            </div>


            <?//}?>
        <?}?>


        <?//print_r($days_max_procent);print_r($days_for_graphic);?>
        <?if($post['field3'] == 0 && $post['field2'] > 0 && !Yii::$app->user->can('school_director') && !Yii::$app->user->can('internat_director') && !Yii::$app->user->can('foodworker')){?>
            <?if(count($bad_organization) > 0){?><br><br><hr>
                <div style="width: 80%">
                <p class="text-center"><b>ОРГАНИЗАЦИИ, которые не внесли данные по контрольным мероприятиям за указанный период(<?=count($bad_organization)?>):</b></p>
                <?$bad_org_count = 0;foreach ($bad_organization as $b_org){$bad_org_count++;?>
                    <p class="text-danger"><?=$bad_org_count.') '.$b_org?></p>
                <?}?>
            <?}else{?>
                <p class="text-success text-center" style="font-size: 25px;"><b>Все организации внесли данные 1 и более раз за указаный период</b></p>
            <?}?>
            </div>
            <hr>
        <?}?>
        <br><br>
        <div style="font-size: 13px;">
            <h1 >Список вопросов</h1>
            <p><b>Вопрос 1:</b> Соответствует ли фактическое меню, объемы порций двухнедельному меню, утвержденному руководителем образовательной организации?</p>
            <p><b>Вопрос 2:</b> Организовано ли питание детей, требующих индивидуального подхода в организации питания с учетом имеющихся нарушений здоровья (сахарный диабет, целиакия, пищевая аллергия)</p>
            <p><b>Вопрос 3:</b> Все ли дети с сахарным диабетом, пищевой аллергией, ОВЗ, фенилкетонурией, целиакией, муковисцидозом питаются в столовой?</p>
            <p><b>Вопрос 4:</b> Все ли дети моют руки перед едой?</p>
            <p><b>Вопрос 5:</b> Созданы ли условия для мытья и дезинфекции рук?</p>
            <p><b>Вопрос 6:</b> Все ли дети едят сидя?</p>
            <p><b>Вопрос 7:</b> Все ли дети успевают поесть за перемену (хватает ли времени для приема пищи)?</p>
            <p><b>Вопрос 8:</b> Есть ли замечания по чистоте посуды?</p>
            <p><b>Вопрос 9:</b> Есть ли замечания по чистоте столов?</p>
            <p><b>Вопрос 10:</b> Есть ли замечания к сервировке столов?</p>
            <p><b>Вопрос 11:</b> Теплые ли блюда выдаются детям?</p>
<!--            <p><b>Вопрос 12:</b> Участвуют ли дети в накрывании на столы?</p>-->
            <p><b>Вопрос 12:</b> Лица, накрывающие на столы, работают в специальной одежде (халат, головной убор)?</p>
            <p><b>Вопрос 13:</b> Организовано ли наряду с основным питанием дополнительное питание (возможность самостоятельного приобретения блюд через линию раздачи или буфет)?</p>
                    <p class="text-center">https://demography.site/anket-parent-control/parent-outside-link?id=<?=Organization::findOne(Yii::$app->user->identity->organization_id)->anket_parent_control_link; ?></p>

        </div>

    <? } ?>




    <?if(Yii::$app->user->can('school_director') || Yii::$app->user->can('foodworker') || !Yii::$app->user->can('internat_director')){//одна орг?>
        <script>
            var ctx = document.getElementById('myChart').getContext('2d');
            var myChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: <?php echo json_encode($date_items);?>,
                    datasets: [{
                        label: 'Динамика несъеденной пищи(%)',
                        data: <?php echo json_encode($procent_items);?>,
                        backgroundColor: [
                            'rgba(24, 40, 184, 0.08)',
                        ],
                        borderColor: [
                            'rgba(24, 40, 184, 1)',
                        ],
                    }]
                },
                options: {
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }]
                    }
                }
            });



//ГРАФИК ДВОЙНОЙ
            var ctx = document.getElementById('bar-chart-procent').getContext('2d');
            var myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: <?php echo json_encode($bar_chart_grouped_label);?>,
                    datasets: [
                        {
                            label: <?php echo json_encode($bar_chart_school_label);?>,//"Школа",
                            backgroundColor: "#3e95cd",
                            data: <?php echo json_encode($bar_chart_procent_org_procent);?>,
                        }, {
                            label: <?php echo json_encode($bar_chart_municipality_label);?>,//"Муниципальное образование",
                            backgroundColor: "#8e5ea2",
                            data: <?php echo json_encode($bar_chart_procent_mun_procent);?>,
                        }
                    ]
                },
                options: {
                    title: {
                        display: true,
                        text: 'Средний процент несъеденной пищи образовательного учреждения в сравнении с муниципальным образованием'
                    },
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }]
                    }
                }
            });





            var ctx = document.getElementById('bar-chart-count').getContext('2d');
            var myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: <?php echo json_encode($bar_chart_grouped_label);?>,
                    datasets: [
                        {
                            label: <?php echo json_encode($bar_chart_school_label);?>,//"Текущая школа",
                            backgroundColor: "#3e95cd",
                            data: <?php echo json_encode($bar_chart_procent_org_count);?>,
                        }, {
                            label: <?php echo json_encode($bar_chart_municipality_label);?>,//"Муниципальное образование",
                            backgroundColor: "#8e5ea2",
                            data: <?php echo json_encode($bar_chart_procent_mun_count);?>,
                        }
                    ]
                },
                options: {
                    title: {
                        display: true,
                        text: 'Среднее количество проведенных мероприятий образовательного учреждения в сравнении с муниципальным образованием'
                    },
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }]
                    }
                }
            });



            var ctx = document.getElementById('bar-chart-ball').getContext('2d');
            var myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: <?php echo json_encode($bar_chart_grouped_label);?>,
                    datasets: [
                        {
                            label: <?php echo json_encode($bar_chart_school_label);?>,//"Текущая школа",
                            backgroundColor: "#3e95cd",
                            data: <?php echo json_encode($bar_chart_procent_org_ball);?>,
                        }, {
                            label: <?php echo json_encode($bar_chart_municipality_label);?>,//"Муниципальное образование",
                            backgroundColor: "#8e5ea2",
                            data: <?php echo json_encode($bar_chart_procent_mun_ball);?>,
                        }
                    ]
                },
                options: {
                    title: {
                        display: true,
                        text: 'Среднее количество баллов, набранных по контрольным мероприятиям образовательным учреждением в сравнении с муниципальным образованием'
                    },
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }]
                    }
                }
            });

        </script>
    <?}?>








    <?if($post['field3'] == 0 && $post['field2'] != 0){//все орг?>

        <script>
            //двойные графики если мун выбрал все школы по муну
            var ctx = document.getElementById('bar-chart-mun-procent').getContext('2d');
            var myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: <?php echo json_encode($bar_chart_grouped_label);?>,
                    datasets: [
                        {
                            label: <?php echo json_encode($bar_chart_municipality_label);?>,//"муниципальное",
                            backgroundColor: "#3e95cd",
                            data: <?php echo json_encode($bar_chart_procent_mun_procent);?>,
                        }, {
                            label: <?php echo json_encode($bar_chart_region_label);?>,//"регион",
                            backgroundColor: "#8e5ea2",
                            data: <?php echo json_encode($bar_chart_procent_reg_procent);?>,
                        }
                    ]
                },
                options: {
                    title: {
                        display: true,
                        text: 'Средний процент несъеденной пищи муниципального образования в сравнении с регионом'
                    },
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }]
                    }
                }
            });





            var ctx = document.getElementById('bar-chart-mun-count').getContext('2d');
            var myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: <?php echo json_encode($bar_chart_grouped_label);?>,
                    datasets: [
                        {
                            label: <?php echo json_encode($bar_chart_municipality_label);?>,//"муниципальное",
                            backgroundColor: "#3e95cd",
                            data: <?php echo json_encode($bar_chart_procent_mun_count);?>,
                        }, {
                            label: <?php echo json_encode($bar_chart_region_label);?>,//"регион",
                            backgroundColor: "#8e5ea2",
                            data: <?php echo json_encode($bar_chart_procent_reg_count);?>,
                        }
                    ]
                },
                options: {
                    title: {
                        display: true,
                        text: 'Среднее количество проведенных мероприятий в муниципальном образовании на одну школу в сравнении с регионом'
                    },
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }]
                    }
                }
            });



            var ctx = document.getElementById('bar-chart-mun-ball').getContext('2d');
            var myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: <?php echo json_encode($bar_chart_grouped_label);?>,
                    datasets: [
                        {
                            label: <?php echo json_encode($bar_chart_municipality_label);?>,//"муниципальное",
                            backgroundColor: "#3e95cd",
                            data: <?php echo json_encode($bar_chart_procent_mun_ball);?>,
                        }, {
                            label: <?php echo json_encode($bar_chart_region_label);?>,//"регион",
                            backgroundColor: "#8e5ea2",
                            data: <?php echo json_encode($bar_chart_procent_reg_ball);?>,
                        }
                    ]
                },
                options: {
                    title: {
                        display: true,
                        text: 'Среднее количество баллов, набранных по контрольным мероприятиям в муниципальном образовании на одну организацию в сравнении с регионом'
                    },
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }]
                    }
                }
            });





            var ctx = document.getElementById('organization_sred_dinam').getContext('2d');
            var myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: <?php echo json_encode($count_org_title);?>,
                    datasets: [{
                        label: 'Динамика несъеденной пищи(%)',
                        data: <?php echo json_encode($count_org_procent);?>,
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                        ],
                        borderColor: [
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)',
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)',
                        ],
                    }]
                },
                options: {
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }]
                    }
                }
            });


            var ctx = document.getElementById('organization_count').getContext('2d');
            var myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: <?php echo json_encode($count_org_title);?>,
                    datasets: [{
                        label: 'Количество контрольных мероприятий',
                        data: <?php echo json_encode($count_org_count);?>,
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                        ],
                        borderColor: [
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)',
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)',
                        ],
                    }]
                },
                options: {
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }]
                    }
                }
            });


        </script>
    <?}?>

    <?if($post['field3'] == 0 || $post['field2'] == 0){//все орг и все районы ?>
        <script>
            var ctx = document.getElementById('peremens_count').getContext('2d');
            var myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: <?php echo json_encode($peremens_for_graphic);?>,
                    datasets: [{
                        label: 'Количество проведенных мероприятий в разрезе смены и перемены',
                        data: <?php echo json_encode($count_for_graphic);?>,
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                        ],
                        borderColor: [
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)',
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)',
                        ],
                    }]
                },
                options: {
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }]
                    }
                }
            });





            var ctx = document.getElementById('peremens_procent').getContext('2d');
            var myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: <?php echo json_encode($peremens_for_graphic);?>,
                    datasets: [{
                        label: 'Среднее значение несъеденной пищи в разрезе смены и перемены(%)',
                        data: <?php echo json_encode($procent_for_graphic);?>,
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                        ],
                        borderColor: [
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)',
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)',
                        ],
                    }]
                },
                options: {
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }]
                    }
                }
            });



            var ctx = document.getElementById('peremens_procent_max').getContext('2d');
            var myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: <?php echo json_encode($peremens_for_graphic_max);?>,
                    datasets: [{
                        label: 'Максимальное значение несъеденной пищи в разрезе смены и перемены(%)',
                        data: <?php echo json_encode($peremens_count_max_for_graphic);?>,
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                        ],
                        borderColor: [
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)',
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)',
                        ],
                    }]
                },
                options: {
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }]
                    }
                }
            });


            var ctx = document.getElementById('days_procent').getContext('2d');
            var myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: <?php echo json_encode($days_for_graphic);?>,
                    datasets: [{
                        label: 'Cреднее значение несъеденной пищи по дням недели(%)',
                        data: <?php echo json_encode($days_procent_for_graphic);?>,
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                        ],
                        borderColor: [
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)',
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)',
                        ],
                    }]
                },
                options: {
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }]
                    }
                }
            });


            var ctx = document.getElementById('days_procent_max').getContext('2d');
            var myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: <?php echo json_encode($days_for_graphic_max);?>,
                    datasets: [{
                        label: 'Максимальное значение несъеденной пищи по дням недели(%)',
                        data: <?php echo json_encode($days_count_max_for_graphic);?>,
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                        ],
                        borderColor: [
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)',
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)',
                        ],
                    }]
                },
                options: {
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }]
                    }
                }
            });



            var ctx = document.getElementById('days_count').getContext('2d');
            var myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: <?php echo json_encode($days_for_graphic);?>,
                    datasets: [{
                        label: 'Количество мероприятий по дням недели',
                        data: <?php echo json_encode($days_count_for_graphic);?>,
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                        ],
                        borderColor: [
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)',
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)',
                        ],
                    }]
                },
                options: {
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }]
                    }
                }
            });
        </script>
    <?}?>



    <?if($post['field3'] == 0 && $post['field2'] == 0){//все орг и все районы ?>
        <script>
            var ctx = document.getElementById('organization_count_mun').getContext('2d');
            var myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: <?php echo json_encode($mun_mas_graph);?>,
                    datasets: [{
                        label: 'Количество контрольных мероприятий по муниципальным районам',
                        data: <?php echo json_encode($mun_mas_count);?>,
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                        ],
                        borderColor: [
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)',
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)',
                        ],
                    }]
                },
                options: {
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }]
                    }
                }
            });




            //3 графика по месяцам если выбраны
            var ctx = document.getElementById('bar-chart-mun-procent').getContext('2d');
            var myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: <?php echo json_encode($bar_chart_grouped_label);?>,
                    datasets: [
                        {
                            label: <?php echo json_encode($bar_chart_region_label);?>,//"регион",
                            backgroundColor: "#8e5ea2",
                            data: <?php echo json_encode($bar_chart_procent_reg_procent);?>,
                        }
                    ]
                },
                options: {
                    title: {
                        display: true,
                        text: 'Средний процент несъеденной пищи по региону'
                    },
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }]
                    }
                }
            });





            var ctx = document.getElementById('bar-chart-mun-count').getContext('2d');
            var myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: <?php echo json_encode($bar_chart_grouped_label);?>,
                    datasets: [
                        {
                            label: <?php echo json_encode($bar_chart_region_label);?>,//"регион",
                            backgroundColor: "#8e5ea2",
                            data: <?php echo json_encode($bar_chart_procent_reg_count);?>,
                        }
                    ]
                },
                options: {
                    title: {
                        display: true,
                        text: 'Среднее количество проведенных мероприятий на одну школу в регионе'
                    },
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }]
                    }
                }
            });



            var ctx = document.getElementById('bar-chart-mun-ball').getContext('2d');
            var myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: <?php echo json_encode($bar_chart_grouped_label);?>,
                    datasets: [
                        {
                            label: <?php echo json_encode($bar_chart_region_label);?>,//"регион",
                            backgroundColor: "#8e5ea2",
                            data: <?php echo json_encode($bar_chart_procent_reg_ball);?>,
                        }
                    ]
                },
                options: {
                    title: {
                        display: true,
                        text: 'Среднее количество баллов, набранных по контрольным мероприятиям на одну организацию в регионе'
                    },
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }]
                    }
                }
            });






        </script>
    <?}?>

    <?

    $script = <<< JS
$(function () {
  $('[data-toggle="tooltip"]').tooltip()
})
//$('#menus-parent_id').attr('disabled', 'true');
$( ".beforeload" ).click(function() {
  $(".beforeload").css('display','none');
  $(".load").css('display','block');
  
});

$(document).ready(function(){
	$('.content_toggle').click(function(){
		$('.content_block').slideToggle(300);      
		return false;
	});
});

/*$( ".beforeload" ).click(function() {
  $('.beforeload').append('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');
});*/

$("#pechat_itog_mun").click(function () {
    var table = $('#tableId');
    if (table && table.length) {
        var preserveColors = (table.hasClass('table2excel_with_colors') ? true : false);
        $(table).table2excel({
            exclude: ".noExl",
            name: "Excel Document Name",
            filename: "Отчет по контрольным мероприятиям(итог).xls",
            fileext: ".xls",
            exclude_img: true,
            exclude_links: true,
            exclude_inputs: true,
            preserveColors: preserveColors
        });
    }
});
$("#pechat_control").click(function () {
    var table = $('#table_control');
    if (table && table.length) {
        var preserveColors = (table.hasClass('table2excel_with_colors') ? true : false);
        $(table).table2excel({
            exclude: ".noExl",
            name: "Excel Document Name",
            filename: "Отчет по контрольным мероприятиям(итог).xls",
            fileext: ".xls",
            exclude_img: true,
            exclude_links: true,
            exclude_inputs: true,
            preserveColors: preserveColors
        });
    }
});



function FixTable(table) {
	var inst = this;
	this.table  = table;
 
	$('thead > tr > th',$(this.table)).each(function(index) {
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
JS;

    $this->registerJs($script, yii\web\View::POS_READY);
    ?>
