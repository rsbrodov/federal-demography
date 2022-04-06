<?php

use common\models\AnketParentControl;
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

$this->title = 'Отчет по контролю';
$this->params['breadcrumbs'][] = $this->title;

$yes_no_items = [
    '1' => "Родительский",
    '2' => "Внутренний",
    '3' => "Общественный",
];

$organization_id = Yii::$app->user->identity->organization_id;
$region_id = Organization::findOne($organization_id)->region_id;
$municipalities = \common\models\Municipality::find()->where(['region_id' => $region_id])->all();
$municipality_one = \common\models\Municipality::find()->where(['region_id' => $region_id])->one()->id;
$municipality_items = ArrayHelper::map($municipalities, 'id', 'name');
$municipality_null = array(0 => 'ПО ВСЕМУ РЕГИОНУ...');
$municipality_items = ArrayHelper::merge($municipality_null, $municipality_items);
if (Yii::$app->user->can('minobr') || Yii::$app->user->can('rospotrebnadzor_nutrition') || Yii::$app->user->can('subject_minobr'))
{


    $organization = Organization::find()->where(['type_org' => 3, 'municipality_id' => $municipality_one])->all();

    $organization_null = array(0 => 'Все организации ...');
    $organization_items = ArrayHelper::map($organization, 'id', 'title');
    $organization_items = ArrayHelper::merge($organization_null, $organization_items);
}
if (!empty($post))
{
    $params_date_start = ['class' => 'form-control', 'options' => [$post['date_start'] => ['Selected' => true]]];
    $params_date_end = ['class' => 'form-control', 'options' => [$post['date_end'] => ['Selected' => true]]];
    $params_field = ['class' => 'form-control', 'options' => [$post['field'] => ['Selected' => true]]];

    $organization = Organization::find()->where(['type_org' => 3, 'municipality_id' => $post['field2']])->all();
    $organization_null = array(0 => 'Все организации ...');
    $organization_items = ArrayHelper::map($organization, 'id', 'title');
    $organization_items = ArrayHelper::merge($organization_null, $organization_items);
}
else{
    $params_field = ['class' => 'form-control', 'options' => [1 => ['Selected' => true]]];
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
    <? if (Yii::$app->user->can('minobr') || Yii::$app->user->can('rospotrebnadzor_nutrition') || Yii::$app->user->can('subject_minobr')) { ?>
        <div class="row">
            <div class="col-md-6">
                <?= $form->field($model, 'field2')->dropDownList($municipality_items, [
                    'class' => 'form-control text-center', 'options' => [$post['field2'] => ['Selected' => true]],
                    'onchange' => '
              $.get("../menus/orglist?id="+$(this).val(), function(data){
                $("select#dateform-field3").html(data);
              });'
                ])->label('Муниципальный округ'); ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'field3')->dropDownList($organization_items, [
                    'class' => 'form-control text-center', 'options' => [$post['field3'] => ['Selected' => true]],
                ])->label('Организация'); ?>
            </div>
        </div>
    <?}?>
    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'date_start')->textInput(['class'=>'datepicker-here form-control', 'autocomplete' => 'off', 'value' => $post['date_start']])->label('Начало периода'); ?>
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





            <p class="text-center text-primary">Наведите курсором на Вопрос 1(2,3,4,5,6 и т.д), чтобы узнать формулировку </p>
            <table class="table_th0 table-hover" style="width: 100%;">
            <thead>
            <tr>
                <th class="text-center align-middle" rowspan="2" style="width: 20px">№/ID</th>
                <th class="text-center align-middle" rowspan="2" style="width: 40px">Мун. район</th>
                <th class="text-center align-middle" rowspan="2" style="width: 40px">Организация</th>
                <th class="text-center align-middle" rowspan="2" style="width: 40px">Количество контролей</th>
                <th class="text-center align-middle" rowspan="2" style="width: 40px">Количество организаций</th>

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
                <th class="text-center align-middle" colspan="2" style="width: 70px" data-toggle="tooltip" data-placement="top" title="Участвуют ли дети в накрывании на столы?">Вопрос 12</th>
                <th class="text-center align-middle" colspan="2" style="width: 70px" data-toggle="tooltip" data-placement="top" title="Лица, накрывающие на столы, работают в специальной одежде (халат, головной убор)?">Вопрос 13</th>
                <th class="text-center align-middle" colspan="2" style="width: 70px" data-toggle="tooltip" data-placement="top" title="Организовано ли наряду с основным питанием дополнительное питание (возможность самостоятельного приобретения блюд через линию раздачи или буфет)?">Вопрос 14</th>
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
                <th class="text-center align-middle" style="width: 70px">Ответ</th>
                <th class="text-center align-middle" style="width: 70px">Баллы</th>
                <th class="text-center align-middle" style="width: 70px">Ответ</th>
                <th class="text-center align-middle" style="width: 70px">Баллы</th>
                <th class="text-center align-middle" style="width: 70px">Ответ</th>
                <th class="text-center align-middle" style="width: 70px">Баллы</th>
            </tr>

            </thead>
            <tbody>


        <?  $super_count = 0;foreach($report_municipalities as $r_municipality){ $count_org_procent = [];?>
            <?if (Yii::$app->user->can('minobr') || Yii::$app->user->can('rospotrebnadzor_nutrition') || Yii::$app->user->can('subject_minobr')){
                if($post['field3'] == 0){
                    $organizations = Organization::find()->where(['type_org' => 3, 'municipality_id' => $r_municipality->id])->andWhere(['!=', 'id', 7])->all();
                }
                else{
                    $organizations = Organization::find()->where(['id' => $post['field3']])->andWhere(['!=', 'id', 7])->all();
                }
            }else{
                $organizations = Organization::find()->where(['id' => Yii::$app->user->identity->organization_id])->andWhere(['!=', 'id', 7])->all();

            }
            ?>
            <?$count_org = 0;$bad_organization = []; $itog[$r_municipality->id]['count_organization'] = 0;$smena_peremens = []; $smena_peremens_procent = []; foreach($organizations as $organization){ ?>
                <?
                if(empty($post['date_start']) && !empty($post['date_end'])){
                    $models = AnketParentControl::find()->where(['organization_id' => $organization->id, 'status'=>$post['field']])->andWhere(['<=',  'date', strtotime($post['date_end'])])->orderBy(['date'=> SORT_ASC])->all();
                }
                elseif(!empty($post['date_start']) && empty($post['date_end'])){
                    $models = AnketParentControl::find()->where(['organization_id' => $organization->id, 'status'=>$post['field']])->andWhere(['>=', 'date', strtotime($post['date_start'])])->orderBy(['date'=> SORT_ASC])->all();
                }
                elseif(empty($post['date_start']) && empty($post['date_end'])){
                    $models = AnketParentControl::find()->where(['organization_id' => $organization->id, 'status'=>$post['field']])->orderBy(['date'=> SORT_ASC])->all();
                }
                else{
                    $models = AnketParentControl::find()->where(['organization_id' => $organization->id, 'status'=>$post['field']])->andWhere(['>=', 'date', strtotime($post['date_start'])])->andWhere(['<=',  'date', strtotime($post['date_end'])])->orderBy(['date'=> SORT_ASC])->all();
                }

                ?>
                <? if(!empty($models)){$count_org++; } $count = 0; $result_on_organization = []; foreach ($models as $model) { $super_count++; $procent = $model->get_result_food($model->id, 'procent');?>

                    <?if($procent > 50){?>
                    <?$date_items[] = date('d.m.Y',$model->date)?>
                    <?$procent_items[] = $procent;?>

                    <!--Выносим логику по расчету баллов из ячеек в буферную часть и вместо 14 строчек будет одна через форич-->
                    <?$question_value[1] = $model->question1;$question_value[2] = $model->question2;$question_value[3] = $model->question3;$question_value[4] = $model->question4;$question_value[5] = $model->question5;$question_value[6] = $model->question6;$question_value[7] = $model->question7;$question_value[8] = $model->question8;$question_value[9] = $model->question9;$question_value[10] = $model->question10;$question_value[11] = $model->question11;$question_value[12] = $model->question12;$question_value[13] = $model->question13;$question_value[14] = $model->question14;?>
                    <?for($i=1;$i<=14;$i++){

                        $current_ball[$i] = $model->yes_no($i, $question_value[$i], 'ball');//по текущему контролю
                        $result_on_organization[$i] = $result_on_organization[$i] + $current_ball[$i];//по организации
                        if($current_ball[$i] == 2){$itog[$r_municipality->id][$i] = $itog[$r_municipality->id][$i] + 1;}//по итоговой строке муниципального района
                    }
                    $itog[$r_municipality->id]['count'] = $itog[$r_municipality->id]['count'] + 1;
                    $smena_peremens[$model->smena.'_'.$model->peremena] = $smena_peremens[$model->smena.'_'.$model->peremena] + 1;
                    $result_on_organization['count'] = $result_on_organization['count'] + $model->count;
                    $result_on_organization['procent'] = $result_on_organization['procent'] + $procent;
                    $smena_peremens_procent[$model->smena.'_'.$model->peremena] = $smena_peremens_procent[$model->smena.'_'.$model->peremena] + $procent;
                    $test = $model->get_result_test($model->id); $result_on_organization['test'] = $result_on_organization['test'] + $test;
                    $test_food = $model->get_result_food($model->id, 'ball'); $result_on_organization['test_food'] = $result_on_organization['test_food'] + $test_food;

                    ?>

                        <tr>
                            <td class="text-center align-middle"><?= $count.'/'.$model->id;?></td>
                            <td class="text-center align-middle" style="font-size: 13px;"><?=\common\models\Municipality::findOne($organization->municipality_id)->name;?></td>
                            <td class="align-middle" style="font-size: 13px;"><?echo (empty($organization->short_title)) ? $organization->title : $organization->short_title;?></td>
                            <td class="align-middle" style="font-size: 13px;"><?=1?></td>
                            <td class="align-middle" style="font-size: 13px;"><?=1?></td>
                            <td class="text-center align-middle"><?= date('d.m.Y',$model->date) ?></td>
                            <td class="text-center align-middle"><? $date = date('w',$model->date); if($date == 0) echo 7; else echo $date; ?></td>
                            <td class="text-center align-middle"><?=$model->peremena ?></td>
                            <td class="text-center align-middle"><?= $model->count;?></td>
                            <td class="text-center align-middle"><?  echo $procent?></td>
                            <td class="text-center align-middle"><?=$test;?></td>
                            <td class="text-center align-middle"><?= $test_food;?></td>
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
                            <td class="text-center align-middle"><?= $model->yes_no(12, $model->question12, 'answer');?></td>
                            <td class="text-center align-middle"><?=$current_ball[12];?></td>

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

                <?}else{?>
                    <?$bad_organization[$organization->id] = (empty($organization->short_title)) ? $organization->title : $organization->short_title?>
                <?}?>
            <?}

            if($count_org_procent){
                $itog[$r_municipality->id]['max_procent'] = max($count_org_procent);
            }
            ?>
        <?}?>

        </tbody>
        </table>
        <hr><br><br>
        <? if (Yii::$app->user->can('minobr') || Yii::$app->user->can('rospotrebnadzor_nutrition') || Yii::$app->user->can('subject_minobr')) { ?>

            <p class="text-center"><b>ИТОГ ПО МУНИЦИПАЛЬНЫМ РАЙОНАМ </b></p>
            <table class="table_th0 table-hover" style="width: 100%;">
                <thead>
                <tr>

                    <th class="text-center align-middle" rowspan="2" style="width: 50px">Мун. район</th>
                    <th class="text-center align-middle" rowspan="2" style="width: 40px">Количество мероприятий</th>
                    <th class="text-center align-middle" rowspan="2" style="width: 40px">Процент несъеденной пищи</th>
                    <th class="text-center align-middle" rowspan="2" style="width: 40px">Максимальный процент несъеденной пищи</th>
                    <th class="text-center align-middle" rowspan="2" style="width: 40px">Количество баллов по тесту</th>
                    <th class="text-center align-middle" rowspan="2" style="width: 40px">Количество баллов по пищеблоку</th>
                    <th class="text-center align-middle" rowspan="2" style="width: 40px">Количество баллов за весь контроль</th>
                    <th class="text-center align-middle" rowspan="1" colspan="14">Положительных ответов</th>
                </tr>
                <tr>
                    <th class="text-center align-middle" rowspan="1"style="width: 40px">Вопрос 1</th>
                    <th class="text-center align-middle" rowspan="1"style="width: 40px">Вопрос 2</th>
                    <th class="text-center align-middle" rowspan="1"style="width: 40px">Вопрос 3</th>
                    <th class="text-center align-middle" rowspan="1"style="width: 40px">Вопрос 4</th>
                    <th class="text-center align-middle" rowspan="1"style="width: 40px">Вопрос 5</th>
                    <th class="text-center align-middle" rowspan="1"style="width: 40px">Вопрос 6</th>
                    <th class="text-center align-middle" rowspan="1"style="width: 40px">Вопрос 7</th>
                    <th class="text-center align-middle" rowspan="1"style="width: 40px">Вопрос 8</th>
                    <th class="text-center align-middle" rowspan="1"style="width: 40px">Вопрос 9</th>
                    <th class="text-center align-middle" rowspan="1"style="width: 40px">Вопрос 10</th>
                    <th class="text-center align-middle" rowspan="1"style="width: 40px">Вопрос 11</th>
                    <th class="text-center align-middle" rowspan="1"style="width: 40px">Вопрос 12</th>
                    <th class="text-center align-middle" rowspan="1"style="width: 40px">Вопрос 13</th>
                    <th class="text-center align-middle" rowspan="1"style="width: 40px">Вопрос 14</th>
                </tr>

                </thead>
                <tbody>
                <?foreach($report_municipalities as $r_municipality){?>
                    <tr class="table-warning">
                        <?if($itog[$r_municipality->id]['count_organization'] > 0){?>
                            <td class="" colspan="1"><?=$r_municipality->name;?></td>
                            <td class="text-center align-middle"><?= $itog[$r_municipality->id]['count']?></td>
                            <td class="text-center align-middle"><?=  round($itog[$r_municipality->id]['procent']/$itog[$r_municipality->id]['count_organization'],1)?></td>
                            <td class="text-center align-middle"><?=  $itog[$r_municipality->id]['max_procent']?></td>
                            <td class="text-center align-middle"><?= round($itog[$r_municipality->id]['test']/$itog[$r_municipality->id]['count_organization'],1)?></td>
                            <td class="text-center align-middle"><?= round($itog[$r_municipality->id]['test_food']/$itog[$r_municipality->id]['count_organization'],1)?></td>
                            <td class="text-center align-middle"><?= round($itog[$r_municipality->id]['test']/$itog[$r_municipality->id]['count_organization'],1) + round($itog[$r_municipality->id]['test_food']/$itog[$r_municipality->id]['count_organization'],1)?></td>
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
                            <td class="text-center align-middle"><?= $itog[$r_municipality->id]['12']?></td>
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
                        <?}?>
                    </tr>
                <?}?>

                <!--            --><?//if(\common\models\Region::findOne(Organization::findOne(Yii::$app->user->identity->organization_id)->region_id)->id == 48){?>
                <!--            <tr class="table-primary">-->
                <!--                <td class="" colspan="1">Итого по Новосибирской области:</td>-->
                <!--                <td class="text-center align-middle">2330</td>-->
                <!--                <td class="text-center align-middle">9.6</td>-->
                <!--                <td class="text-center align-middle">72</td>-->
                <!--                <td class="text-center align-middle">23.1</td>-->
                <!--                <td class="text-center align-middle">11.1</td>-->
                <!--                <td class="text-center align-middle">34.2</td>-->
                <!--                <td class="text-center align-middle">2310</td>-->
                <!--                <td class="text-center align-middle">1381</td>-->
                <!--                <td class="text-center align-middle">1700</td>-->
                <!--                <td class="text-center align-middle">2265</td>-->
                <!--                <td class="text-center align-middle">2322</td>-->
                <!--                <td class="text-center align-middle">2311</td>-->
                <!--                <td class="text-center align-middle">2324</td>-->
                <!--                <td class="text-center align-middle">2294</td>-->
                <!--                <td class="text-center align-middle">2308</td>-->
                <!--                <td class="text-center align-middle">2296</td>-->
                <!--                <td class="text-center align-middle">2307</td>-->
                <!--                <td class="text-center align-middle">366</td>-->
                <!--                <td class="text-center align-middle">2280</td>-->
                <!--                <td class="text-center align-middle">753</td>-->
                <!--            </tr>-->
                <!--            --><?//}?>
                </tbody>
            </table>
        <?}?>
        <br>


        <?if($count_org == 0){?>
            <p class="text-center text-danger">Нет данных</p>
        <?}?>

        <?if($count_org == 1){?>
            <div class="container">
                <div class="block" style="width: 100%!important;">
                    <canvas id="myChart" width="600" height="170"></canvas>

                    <p class="text-center">Количество контролей: <b><?=$count?></b></p>
                </div>
            </div>
        <?}?>





        <?if($post['field3'] == 0 && $post['field2'] > 0 && !Yii::$app->user->can('school_director') && !Yii::$app->user->can('foodworker')){?>

            <div style="width: 80%">
                <p class="text-center"><b>ДИНАМИКА по количеству проведенных мероприятий в разрезе каждой организации за указанный период времени(%)</b></p>
                <div class="block" style="width: 100%!important;">
                    <canvas id="organization_count" width="700" height="220"></canvas>
                </div>
            </div>
            <hr>
            <div style="width: 80%">
                <p class="text-center"><b>ДИНАМИКА несъеденной пищи по каждой организации в(%)</b></p>
                <div class="block" style="width: 100%!important;">
                    <canvas id="organization_sred_dinam" width="700" height="220"></canvas>
                </div>
            </div>


            <?//if(!empty($smena_peremens)){?>
            <?foreach ($smena_peremens as $key => $smena_peremen){
                $key = explode('_',$key);
                $peremens_for_graphic[] = $key[0].'-ая смена '.$key[1].'-ая перемена';
                $count_for_graphic[] = $smena_peremen;
            }?>
            <?//print_r($peremens_for_graphic);?>
            <hr>
            <div style="width: 60%">
                <p class="text-center"><b>ДИНАМИКА по количеству проведенных мероприятий в разрезе смены и перемены</b></p>
                <div class="block" style="width: 100%!important;">
                    <canvas id="peremens_count" width="700" height="220"></canvas>
                </div>
            </div>


            <?foreach ($smena_peremens_procent as $key => $procent){

                $procent_for_graphic[] = round($procent/$smena_peremens[$key],1);
            }?>
            <?//print_r($peremens_for_graphic);?>
            <hr>
            <div style="width: 60%">
                <p class="text-center"><b>ДИНАМИКА по количеству проведенных мероприятий в разрезе смены и перемены</b></p>
                <div class="block" style="width: 100%!important;">
                    <canvas id="peremens_procent" width="700" height="220"></canvas>
                </div>
            </div>

            <?//}?>

            <?if(count($bad_organization) > 0){?><hr>
                <div style="width: 80%">
                <p class="text-center"><b>ОРГАНИЗАЦИИ, которые не внесли данные по контрольным мероприятиям за указанный период(<?=count($bad_organization)?>):</b></p>
                <?$bad_org_count = 0;foreach ($bad_organization as $b_org){$bad_org_count++;?>
                    <p class="text-danger"><?=$bad_org_count.') '.$b_org?></p>
                <?}?>
            <?}else{?>
                <p class="text-success"><b>Все организации внесли данные 1 и более раз за указаный период</b></p>
            <?}?>
            </div>
            <hr>

        <?}?>

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
            <p><b>Вопрос 12:</b> Участвуют ли дети в накрывании на столы?</p>
            <p><b>Вопрос 13:</b> Лица, накрывающие на столы, работают в специальной одежде (халат, головной убор)?</p>
            <p><b>Вопрос 14:</b> Организовано ли наряду с основным питанием дополнительное питание (возможность самостоятельного приобретения блюд через линию раздачи или буфет)?</p>
        </div>

    <? } ?>




    <?if($post['field3'] != 0 || Yii::$app->user->can('school_director') || Yii::$app->user->can('foodworker')){//одна орг?>
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

        </script>
    <?}?>








    <?if($post['field3'] == 0){//все орг?>

        <script>
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





            var ctx = document.getElementById('peremens_count').getContext('2d');
            var myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: <?php echo json_encode($peremens_for_graphic);?>,
                    datasets: [{
                        label: 'Количество контрольных мероприятий по переменам',
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
                        label: 'Процент несъеденной пищи по переменам (в%)',
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


/*$( ".beforeload" ).click(function() {
  $('.beforeload').append('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');
});*/






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
