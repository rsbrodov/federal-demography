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

$this->title = 'Структура классов в образовательных учреждениях';
$this->params['breadcrumbs'][] = $this->title;

$student_mas = [];
$field_mas = [];
$shapka_mas = [];

$field_mas['dis_sahar'] = 'СД';$field_mas['dis_ovz'] = 'ОВЗ';$field_mas['dis_cialic'] = 'Целиакия';$field_mas['dis_fenilketon'] = 'Фенилкетонурия';
$field_mas['dis_mukovis'] = 'Муковисцидоз';

$field_mas['al_moloko'] = 'Аллергия на молоко';$field_mas['al_yico'] = 'Аллергия на яйцо';$field_mas['al_fish'] = 'Аллергия на рыбу';
$field_mas['al_orehi'] = 'Аллергия на орехи';$field_mas['al_chocolad'] = 'Аллергия на шоколад';$field_mas['al_citrus'] = 'Аллергия на цитрус';
$field_mas['al_med'] = 'Аллергия на мед';$field_mas['al_pshenica'] = 'Аллергия на пшеницу';$field_mas['al_arahis'] = 'Аллергия на арахис';
$field_mas['al_inoe'] = 'Иная аллергия';

$organization_id = Yii::$app->user->identity->organization_id;

$region_id = Organization::findOne($organization_id)->region_id;

if (Yii::$app->user->can('minobr') || Yii::$app->user->can('rospotrebnadzor_nutrition'))
{
    $municipalities = \common\models\Municipality::find()->where(['region_id' => $region_id])->all();
    $municipality_items = ArrayHelper::map($municipalities, 'id', 'name');
    $organization = Organization::find()->where(['type_org' => 3, 'region_id' => $region_id])->andWhere(['!=', 'id', 7])->all();
}
if (Yii::$app->user->can('subject_minobr'))
{
    $my_org = Organization::findOne($organization_id);
    $municipalities = \common\models\Municipality::find()->where(['id' =>$my_org->municipality_id])->all();
    $municipality_items = ArrayHelper::map($municipalities, 'id', 'name');
    $organization = Organization::find()->where(['type_org' => 3, 'municipality_id' => $my_org->municipality_id])->all();
}
$organization_items = ArrayHelper::map($organization, 'id', 'title');
if (!empty($post))
{
    $params_organization = ['class' => 'form-control', 'options' => [$post['organization_id'] => ['Selected' => true]]];
}

?>

<h1 class="text-center"><?= Html::encode($this->title) ?></h1>

<? if (empty($my_menus) && (!(Yii::$app->user->can('rospotrebnadzor_camp') || Yii::$app->user->can('rospotrebnadzor_nutrition') || Yii::$app->user->can('subject_minobr') || Yii::$app->user->can('minobr')))) { ?>
    <p class="text-center" style="color: red"><b>У Вас не созданы меню и не добавлены блюда.(Перейдите в раздел "Архив
            меню" или "Настройка меню")</b></p>
<? } ?>

<?php $form = ActiveForm::begin([]); ?>
<div class="container mb-5 mt-5">
    <div class="row">
        <div class="col-md-6">
            <? if (Yii::$app->user->can('minobr') || Yii::$app->user->can('rospotrebnadzor_nutrition')) { ?>
                <?= $form->field($model, 'parent_id')->dropDownList($municipality_items, [
                    'class' => 'form-control', 'options' => [$post['parent_id'] => ['Selected' => true]],
                    'onchange' => '
                  $.get("../menus/orglist?id="+$(this).val(), function(data){
                    $("select#menus-organization_id").html(data);
                  });'
                ])->label('Муниципальный округ'); ?>
            <?}?>
            <? if (Yii::$app->user->can('subject_minobr')) { ?>
                <?= $form->field($model, 'parent_id')->dropDownList($municipality_items, [
                    'class' => 'form-control', 'options' => [$post['parent_id'] => ['Selected' => true]],
                    'disabled' => 'disabled',
                    'onchange' => '
                  $.get("../menus/orglist?id="+$(this).val(), function(data){
                    $("select#menus-organization_id").html(data);
                  });'
                ])->label('Муниципальный округ'); ?>
            <?}?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'organization_id')->dropDownList($organization_items, [
                'class' => 'form-control', 'options' => [$post['organization_id'] => ['Selected' => true]],
            ]); ?>
        </div>


    </div>


    <div class="row">
        <div class="form-group" style="margin: 0 auto">
            <?= Html::submitButton('Посмотреть', ['name' => 'identificator', 'value' => 'view', 'class' => 'btn main-button-3 beforeload mt-3']) ?>
            <button class="btn main-button-3 load mt-3" type="button" disabled style="display: none">
                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                Посмотреть...
            </button>
        </div>
    </div>
    <p class="text-center"><b>Для крупных городов и муниципальных образований формирование отчета может занимать некоторое время</b></p>
    <?php ActiveForm::end(); ?>
</div>
<div>
<?if ($post){ ?>
    <?foreach ($students_classes as $model){
    $students = \common\models\Students::find()->where(['students_class_id' => $model->id])->all();
    foreach ($students as $student){
    $student_mas[$model->id]['count'] = $student_mas[$model->id]['count'] + 1;
    $student_diseases = [];
    if ($student->form_study == 1){
    $student_mas[$model->id]['count_ochnoe'] = $student_mas[$model->id]['count_ochnoe'] + 1;
    }
    if ($student->form_study == 2){
    $student_mas[$model->id]['count_domashnee'] = $student_mas[$model->id]['count_domashnee'] + 1;
    }
    //Болезни и аллергии. Собираем в один массив чтобы знать сколько болезней у ребенка
    foreach ($field_mas as $key => $field){
    if ($student->$key == 1){
    $student_diseases[$key] = $field;
    }
    }
    /**/
    if (count($student_diseases) > 1){
    $kew_long = '';
    $name_long = '';
    $count_turn = 0;
    foreach ($student_diseases as $student_disease_key => $student_disease){
    $count_turn++;//количество оборотов
    if ($count_turn > 1){//если это второй оборот
    $kew_long .= '+' . $student_disease_key;
    //если встречаются 2 раза слова Аллергия - 1 из них нужно удалить
    if (preg_match('/Аллергия/', $name_long)) {
    $name_long .= ' и ' . mb_substr($student_disease, mb_strpos($student_disease, ' '));
    }else{
    $name_long .= ' и ' . $student_disease;
    }

    }
    elseif ($count_turn == 1 || $count_turn == count($student_diseases))
    {
    $kew_long .= $student_disease_key;
    $name_long .= $student_disease;
    }
    }
    //добавляем множественное название в бд
    if (!array_key_exists($kew_long, $shapka_mas))
    {
    $shapka_mas[$kew_long] = $name_long;

    }
    $student_disease_itog_key = $kew_long;
    //print_r($kew_long.'-'.$name_long.'<br>');

    }

    if (count($student_diseases) == 1)
    {//тут понятно что будет один оборот но мне нужно получить ключ а я могу только из форича его взять
    foreach ($student_diseases as $student_disease_key => $student_disease){
    if (!array_key_exists($student_disease_key, $shapka_mas)){

    $shapka_mas[$student_disease_key] = $student_disease;
    }
    $student_disease_itog_key = $student_disease_key;
    }
    //print_r($student_disease_key.'-'.$student_disease.'<br>');
    }


    //записываем в общий массив чтобы потом вывести
    if(!empty($student_diseases)){
    $student_mas[$model->id][$student_disease_itog_key] = $student_mas[$model->id][$student_disease_itog_key]+1;
    }
    //print_r($student_diseases);

    $students_nutrition = \common\models\StudentsNutrition::find()->where(['students_id' => $student->id])->all();
    foreach ($students_nutrition as $student_n){
    $student_mas[$model->id]['nutrition'][$student_n->nutrition_id] = $student_mas[$model->id]['nutrition'][$student_n->nutrition_id]+1;
    $student_mas[$model->id]['peremena'][$student_n->peremena] = $student_n->peremena;
    }

    }
    }
    ?>
    <style>
        th, td {
            border: 1px solid black!important;
            color: black;

        }
        th {
            background-color: #ede8b9;
            font-size: 14px;
            font-weight: bold;
        }
        .form-group {
            margin-bottom: 0rem;
        }
        .block-items{
            display: flex;
            justify-content: space-around;
            align-items: center;
        }
    </style>
    <div class="characters-study-index" style="max-width: 1500px">

        <?if(!empty(\common\models\StudentsClass::find()->where(['organization_id' => $post['organization_id']])->all())){?>
            <p>Структура классов по <b><?=Organization::findOne($post['organization_id'])->title;?></b></p>
            <table class="table-hover fixtable table2excel_with_colors" id="table_report">
                <thead>
                <tr>
                    <th class="text-center align-middle" rowspan="3" style="width: 70px">№</th>
                    <th class="text-center align-middle" rowspan="3" style="max-width: 50px">Класс</th>
                    <th class="text-center align-middle" rowspan="3" style="width: 50px">Буква/ цифра</th>
                    <th class="text-center align-middle" rowspan="3" style="width: 50px">Смена обучения</th>
                    <th class="text-center align-middle" rowspan="3" style="width: 70px">Количество детей</th>
                    <th class="align-middle" colspan="<?=count($shapka_mas)+2 ;?>" style="width: 230px">Из них</th>

                    <th class="text-center align-middle" rowspan="3" style="width: 190px">Перемены, на которых организ. питание</th>
                    <th class="text-center align-middle" rowspan="3" style="min-width: 190px">Виды организованного питания</th>
                    <th class="text-center align-middle" rowspan="3" style="width: 140px">Не питаются</th>

                </tr>
                <tr>
                    <th class="text-center align-middle" rowspan="2">на очном обучении</th>
                    <th class="text-center align-middle" rowspan="2">на домашнем обучении</th>
                    <?if(!empty($shapka_mas)){?>
                        <th class="text-center align-middle" colspan="<?=count($shapka_mas);?>">В том числе</th>
                    <?}?>

                </tr>
                <tr>
                    <?foreach($shapka_mas as $shapka){?>
                        <th class="text-center align-middle" style="width: 70px"><?=$shapka?></th>
                    <?}?>
                </tr>
                </thead>
                <tbody>
                <?$itog_mas = []; $count = 0; foreach ($students_classes as $model){ $count++;?>
                    <tr style="max-height: 30px;">
                        <td class="align-middle text-center" style="width: 50px"><b><?= $count ?></b></td>
                        <?if($model->class_number>=21 && $model->class_number>=21){ $model->class_number = $model->class_number %10 .'(подготовительный)';}?>
                        <td class="text-center align-middle" style="width: 100px"><? if($model->class_number == 13) echo 'Коррекц.'; else{ echo $model->class_number;}?></td>
                        <td class="text-center align-middle" style="width: 100px"><?= $model->class_letter ?></td>
                        <td class="text-center align-middle" style="width: 100px"><?= $model->smena ?></td>
                        <td class="text-center align-middle" style="width: 100px; <?if(empty($student_mas[$model->id]['count'])){ echo 'color:red;';}?>"><b><?= empty($student_mas[$model->id]['count']) ? 0 : $student_mas[$model->id]['count']; $itog_mas['count'] = $itog_mas['count'] +$student_mas[$model->id]['count'];?></b></td>
                        <td class="text-center align-middle" style="width: 100px"><?= empty($student_mas[$model->id]['count_ochnoe']) ? 0 : $student_mas[$model->id]['count_ochnoe']; $itog_mas['count_ochnoe'] = $itog_mas['count_ochnoe'] +$student_mas[$model->id]['count_ochnoe'];?></td>
                        <td class="text-center align-middle" style="width: 100px"><?= empty($student_mas[$model->id]['count_domashnee']) ? 0 : $student_mas[$model->id]['count_domashnee']; $itog_mas['count_domashnee'] = $itog_mas['count_domashnee'] +$student_mas[$model->id]['count_domashnee'];?></td>
                        <?foreach($shapka_mas as $key => $shapka){?>
                            <td class="text-center align-middle <?if(empty($student_mas[$model->id][$key])) echo 'table-secondary';?>" style="width: 100px"><?= empty($student_mas[$model->id][$key]) ? '-' : $student_mas[$model->id][$key]; $itog_mas[$key] = $itog_mas[$key] +$student_mas[$model->id][$key];?></td>
                        <?}?>

                        <td class="" style="width: 100px"><? $count_peremena = 1;
                            for($i=1;$i<17;$i++){
                                //контроль запятых находим и понимаем что запись последняя и запятую не ставим
                                if(!empty($student_mas[$model->id]['peremena'][$i]) && count($student_mas[$model->id]['peremena']) == $count_peremena){
                                    echo \common\models\SmenaPeremena::findOne($student_mas[$model->id]['peremena'][$i])->name. ' ';
                                    $count_peremena++;
                                }
                                elseif(!empty($student_mas[$model->id]['peremena'][$i]) && count($student_mas[$model->id]['peremena']) != $count_peremena){
                                    echo \common\models\SmenaPeremena::findOne($student_mas[$model->id]['peremena'][$i])->name. ', ';
                                    $count_peremena++;
                                }
                            }?>
                        </td>
                        <td class="" style="width: 100px"><? $count_nutrition = 1;
                            for($i=1;$i<7;$i++){

                                //контроль запятых находим и понимаем что запись последняя и запятую не ставим
                                if(!empty($student_mas[$model->id]['nutrition'][$i]) && count($student_mas[$model->id]['nutrition']) == $count_nutrition){
                                    echo \common\models\NutritionInfo::findOne($i)->name . ' - ' . $student_mas[$model->id]['nutrition'][$i].'  чел. ';
                                    $itog_mas['nutrition'][$i] = $itog_mas['nutrition'][$i]+$student_mas[$model->id]['nutrition'][$i];
                                    $count_nutrition++;
                                }
                                elseif(!empty($student_mas[$model->id]['nutrition'][$i]) && count($student_mas[$model->id]['nutrition']) != $count_nutrition){
                                    echo \common\models\NutritionInfo::findOne($i)->name . ' - ' . $student_mas[$model->id]['nutrition'][$i].' чел., <br>';
                                    $itog_mas['nutrition'][$i] = $itog_mas['nutrition'][$i]+$student_mas[$model->id]['nutrition'][$i];
                                    $count_nutrition++;
                                }
                            }?>
                        </td>
                        <td class="text-center" style="width: 100px; <?if(\common\models\Students::find()->where(['students_class_id' => $model->id, 'otkaz_pitaniya' => 0])->count() > 0){ echo 'color:blue; font-weight:bold;';}?>"><?$otkaz = \common\models\Students::find()->where(['students_class_id' => $model->id, 'otkaz_pitaniya' => 0])->count(); echo $otkaz; $itog_mas['otkaz'] = $itog_mas['otkaz'] +$otkaz;?></td>

                    </tr>

                <?}?>
                <?if(empty($students_classes)){?>
                    <tr>
                        <td class="text-center text-danger" colspan="18">Нет данных</td>
                    </tr>
                <?}?>
                <?if(!empty($students_classes)){?>
                    <tr class="table-primary font-weight-bold">
                        <td class="text-center" colspan="4">Итоговая информация по классам</td>
                        <td class="text-center" colspan="1"><?=$itog_mas['count']?></td>
                        <td class="text-center" colspan="1"><?=$itog_mas['count_ochnoe']?></td>
                        <td class="text-center" colspan="1"><?=$itog_mas['count_domashnee']?></td>
                        <?foreach($shapka_mas as $key => $shapka){?>
                            <td class="text-center" colspan="1"><?=$itog_mas[$key]?></td>
                        <?}?>
                        <td class="text-center" colspan="1"></td>
                        <td class="" style="width: 100px; font-size: 12px;"><? $count_nutrition = 1;
                            for($i=1;$i<7;$i++){
                                //контроль запятых находим и понимаем что запись последняя и запятую не ставим
                                if(!empty($itog_mas['nutrition'][$i]) && count($itog_mas['nutrition']) == $count_nutrition){
                                    echo \common\models\NutritionInfo::findOne($i)->name . ' - ' . $itog_mas['nutrition'][$i].'  чел. ';
                                    $count_nutrition++;
                                }
                                elseif(!empty($itog_mas['nutrition'][$i]) && count($itog_mas['nutrition']) != $count_nutrition){
                                    echo \common\models\NutritionInfo::findOne($i)->name . ' - ' . $itog_mas['nutrition'][$i].' чел., <br>';
                                    $count_nutrition++;
                                }
                            }?>
                        </td>
                        <td class="text-center" colspan="1"><?=$itog_mas['otkaz']?></td>
                    </tr>

                <?}?>
                </tbody>
            </table>
            <div class="text-center mt-3 mb-3">
                <button id="pechat_report" class="btn btn-success">
                    <span class="glyphicon glyphicon-download"></span> Скачать отчет в Excel
                </button>
                <p class="text-center text-danger"><b><small>Для крупных городов формирование отчета Excel может занимать некоторое время</small></b></p>
            </div>
        <?}else{?>
          <p class="text-danger text-center"><b>Данных не обнаружено</b></p>
        <?}?>
        <br>
        <br>
    </div>

<?}?>

<?

$script = <<< JS
//$('#menus-parent_id').attr('disabled', 'true');
$( ".beforeload" ).click(function() {
  $(".beforeload").css('display','none');
  $(".load").css('display','block');
  
});


$("#pechat_report").click(function () {
    var table = $('#table_report');
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
