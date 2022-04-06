<?php

use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\CharactersStudyAllergy */

$this->title = 'Добавление ребенка в класс';
$this->params['breadcrumbs'][] = ['label' => 'Characters Study Allergies', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$yes_no_items = [
    '0' => "Нет",
    '1' => "Да",
];


$no_yes_items = [
    '1' => "Есть",
    '0' => "Нет",
];

$form_study_items = [
    '1' => "Очная",
    '2' => "Домашняя",
];

$pitanie_items = [
    '1' => "Питается",
    '0' => "Не питается",
];
$prichina_otkaza_items = [
    '1' => "По причине перечисленных заболеваний",
    '2' => "По причине домашнего обучения",
    '3' => "По иным причинам",
];

$peremena_items = [
    '1' => "Первая",
    '2' => "Вторая",
    '3' => "Третья",
    '4' => "Четвертая",
    '5' => "Пятая",
    '6' => "Шестая",
    '7' => "Седьмая",
    '8' => "Восьмая",
    '15' => "До уроков",
    '16' => "После уроков",
];
$sub = array('' => 'Выберите прием пищи ...');
$nutrition_items = \common\models\NutritionInfo::find()->all();
$nutrition_items = \yii\helpers\ArrayHelper::map($nutrition_items, 'id', 'name');
$nutrition_items = \yii\helpers\ArrayHelper::merge($sub,$nutrition_items);

?>
<style>
    .general{
        display: flex;
    }
    .block{
        display:flex;
        justify-content: start;
        flex-wrap:wrap;
        margin: 0 -5px;
    }
    .item-block{
        flex: 1 1 auto;
        margin: 0 5px;
    }
    .diseases{
        width:80%;
        margin-left: 20%;
    }
    .start{
        width:80%;
        margin-right: 20%;
    }
    .pitanie{
        width:50%;
        /*margin-left: 20%;*/
    }



    .box-shadow {
        /*min-width: 650px;
        min-height: 350px;*/
        padding: 1em;
        box-shadow: 0 2px 4px rgba(0, 0, 0, .2);
        border-radius: 5px;
        background-color: #ffe2a8d8;
        /*margin-left: 20px;*/
    }
    .characters-study-allergy-create{
        width: 90%;
        margin: 0 auto;
        margin-top: 5px;
    }
</style>
<?$class_number = \common\models\StudentsClass::findOne($_GET['id'])->class_number;
if ($class_number == 13)
{
    $class_number = 'Коррекционный';
}
if($class_number>=21 && $class_number>=21){ $class_number = $class_number %10 .'(подготовительный)';}?>
<div class="characters-study-allergy-create">

    <h1 class="text-center mb-5"><?= Html::encode($this->title) ?></h1>
    <?php $form = ActiveForm::begin(); ?>
    <div class="start box-shadow">
        <div class="block">
            <div class="item-block">
                <b>Фамилия(1-ая буква) Имя:</b><?= $form->field($model_form, 'f_name')->textInput(['class' => 'form-control', 'autocomplete' => 'off'])->label(false) ?>
            </div>
            <div class="item-block">
                <b>Класс:</b><?= $form->field($model_form, 'class_number')->textInput(['class' => 'form-control', 'disabled'=>'disabled', 'value'=>$class_number])->label(false) ?>
            </div>
            <div class="item-block">
                <b>Буква:</b><?= $form->field($model_form, 'class_letter')->textInput(['class' => 'form-control', 'disabled'=>'disabled', 'value'=>\common\models\StudentsClass::findOne($_GET['id'])->class_letter])->label(false) ?>
            </div>
            <div class="item-block">
                <b>Форма обучения:</b><?= $form->field($model_form, 'form_study')->dropDownList($form_study_items)->label(false) ?>
            </div>
        </div>
    </div>

    <p class="mt-4"><b>Заболевания:</b><?= $form->field($model_form, 'zabolevaniya_est_net')->dropDownList($no_yes_items)->label(false) ?></p>
    <div class="diseases_all">
        <div class="diseases box-shadow">
            <p class="mb-4"><b>ПЕРЕЧЕНЬ ЗАБОЛЕВАНИЙ:</b></p>
            <div class="block">
                <div class="item-block">
                    <b>Сахарный диабет:</b><?= $form->field($model_form, 'dis_sahar')->dropDownList($yes_no_items)->label(false) ?>
                </div>
                <div class="item-block">
                    <b>Целиакия:</b><?= $form->field($model_form, 'dis_cialic')->dropDownList($yes_no_items)->label(false) ?>
                </div>
                <div class="item-block">
                    <b>Фенилкетонурия:</b><?= $form->field($model_form, 'dis_fenilketon')->dropDownList($yes_no_items)->label(false) ?>
                </div>
                <div class="item-block">
                    <b>Муковисцидоз:</b><?= $form->field($model_form, 'dis_mukovis')->dropDownList($yes_no_items)->label(false) ?>
                </div>
                <div class="item-block">
                    <b>ОВЗ:</b><?= $form->field($model_form, 'dis_ovz')->dropDownList($yes_no_items)->label(false) ?>
                </div>
            </div>
        </div>


        <div class="diseases box-shadow mt-3">
            <p class="mb-4"><b>АЛЛЕРГИЯ:</b></p>
            <div class="block">
                <div class="item-block">
                    <b>Коровье молоко:</b><?= $form->field($model_form, 'al_moloko')->dropDownList($yes_no_items)->label(false) ?>
                </div>
                <div class="item-block">
                    <b>Яйцо:</b><?= $form->field($model_form, 'al_yico')->dropDownList($yes_no_items)->label(false) ?>
                </div>
                <div class="item-block">
                    <b>Пшеница:</b><?= $form->field($model_form, 'al_pshenica')->dropDownList($yes_no_items)->label(false) ?>
                </div>
                <div class="item-block">
                    <b>Рыба и морепродукты:</b><?= $form->field($model_form, 'al_fish')->dropDownList($yes_no_items)->label(false) ?>
                </div>
                <div class="item-block">
                    <b>Шоколад:</b><?= $form->field($model_form, 'al_chocolate')->dropDownList($yes_no_items)->label(false) ?>
                </div>
                <div class="item-block">
                    <b>Орехи:</b><?= $form->field($model_form, 'al_orehi')->dropDownList($yes_no_items)->label(false) ?>
                </div>
                <div class="item-block">
                    <b>Цитрусовые:</b><?= $form->field($model_form, 'al_citrus')->dropDownList($yes_no_items)->label(false) ?>
                </div>
                <div class="item-block">
                    <b>Мед:</b><?= $form->field($model_form, 'al_med')->dropDownList($yes_no_items)->label(false) ?>
                </div>
                <div class="item-block">
                    <b>Арахис:</b><?= $form->field($model_form, 'al_arahis')->dropDownList($yes_no_items)->label(false) ?>
                </div>
                <div class="item-block">
                    <b>Иное:</b><?= $form->field($model_form, 'al_inoe')->dropDownList($yes_no_items)->label(false) ?>
                </div>
            </div>
        </div>
    </div>
<br><br>
    <b>Питание:</b><?= $form->field($model_form, 'pit_nepit')->dropDownList($pitanie_items)->label(false) ?>
    <div class="otkaz">
        <b>Причина:</b><?= $form->field($model_form, 'prichina_otkaza')->dropDownList($prichina_otkaza_items)->label(false) ?>
    </div>




    <div class="pitanie box-shadow">
        <p class="mb-4 text-center"><b>ПРИЕМЫ ПИЩИ:</b></p>
        <?for($i=1;$i<=6;$i++){?>
            <div class="block nutrition<?=$i?>">
                <div class="item-block">
                    <b>Прием пищи:</b><?= $form->field($model_form, 'nutrition'.$i)->dropDownList($nutrition_items)->label(false) ?>
                </div>
                <div class="item-block">
                    <b>Перемена:</b><?= $form->field($model_form, 'peremena'.$i)->dropDownList($peremena_items)->label(false) ?>
                </div>
            </div>
        <?}?>

        <div class="hiddenButton_1 text-center"><span class="glyphicon glyphicon-plus btn btn-sm btn-success add">Добавить еще прием пищи</span></div>

    </div>

    <div class="form-group text-center mt-5">
        <?= Html::submitButton('Сохранить', ['class' => 'btn main-button-3 mt-3 col-7', 'name' => 'signup-button']) ?>
    </div>
    <?php ActiveForm::end(); ?>



</div>


<?
//print_r($data);
$script = <<< JS

window.onload = function() {
    $('.otkaz').hide();
    $('.nutrition2').hide();
    $('.nutrition3').hide();
    $('.nutrition4').hide();
    $('.nutrition5').hide();
    $('.nutrition6').hide();
    }

    
    var field = $('#studentform-pit_nepit');
    var field2 = $('#studentform-zabolevaniya_est_net');
    field.on('change', function () {
           if (field.val() === "0") {
               console.log('222');
               $('.otkaz').show('slow');
               $('.pitanie').hide('slow');
           }
            else if(field.val() === "1") {
              $('.otkaz').hide('slow');
              $('.pitanie').show('slow');
           }
           
    });
    
    
    field2.on('change', function () {
           if (field2.val() === "0") {
               $('.diseases_all').hide('slow');
           }
            else if(field2.val() === "1") {
              $('.diseases_all').show('slow');
           }
    });
    field.trigger('change');
    
    
    
    var i = 1;
    $('.add').click(function() {
        i++;console.log('.nutriton'+i);
    //$('.hiddenButton_'+(i-1)).hide();
    $('.nutrition'+i).show('slow');
});
    
    
JS;

$this->registerJs($script, yii\web\View::POS_READY);
?>