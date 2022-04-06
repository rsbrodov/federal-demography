<?php

use common\models\Menus;
use common\models\Organization;
use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model common\models\AnketParentControl */
/* @var $form yii\widgets\ActiveForm */


$this->title = 'Ввод данных по мероприятию родительского контроля';
$yes_no_items = [
     '' => 'Выберите вариант ответа',
    '1' => "Да",
    '0' => "Нет",
];
$three_items = [
    '' => 'Выберите вариант ответа',
    '1' => "Да",
    '0' => "Нет",
    '4' => "Детей с нарушениями здоровья в организации нет",
];
$yes_no_chast_items = [
    '' => 'Выберите вариант ответа',
    '1' => "Да",
    '0' => "Нет",
    '3' => "Частично",
    '4' => "Детей с нарушениями здоровья в организации нет",
];


$smena_item = [
    '' => 'Выберите смену',
    1 => '1',
    2 => '2',
];

$peremena_item = [
    '' => 'Выберите перемену',
    1 => '1',
    2 => '2',
    3 => '3',
    4 => '4',
    5 => '5',
    6 => '6',
    7 => '7',
    8 => '8',
    'До уроков' => 'До уроков',
    'После уроков' => 'После уроков',
];


?>
<h1 class="text-center"><?= Html::encode($this->title) ?></h1>
<div class="anket-parent-control-form container mt-5">


    <?php $form = ActiveForm::begin(); ?>


    <div class="form-group field-anketparentcontrol-date required">
        <label for="anketparentcontrol-date">Образовательное учреждение:</label>
        <input type="text" id="characters-date" class="form-control disabled" disabled="disabled" name="characters[date]" autocomplete="off" aria-required="true" value="<?=$organization->title?>">
    </div>

    <?= $form->field($model, 'date')->textInput(['class'=>'datepicker-here form-control', 'autocomplete' => 'off'])->label('Дата проведения мероприятия контроля') ?>

    <?= $form->field($model, 'name')->textInput(['autocomplete' => 'off']) ?>

    <?= $form->field($model, 'smena')->dropDownList($smena_item) ?>

    <?= $form->field($model, 'peremena')->dropDownList($peremena_item) ?>

    <?= $form->field($model, 'question1')->dropDownList($yes_no_items) ?>

    <?= $form->field($model, 'question2')->dropDownList($yes_no_chast_items) ?>

    <?= $form->field($model, 'question3')->dropDownList($three_items) ?>

    <?= $form->field($model, 'question5')->dropDownList($yes_no_items) ?>

    <?= $form->field($model, 'question4')->dropDownList($yes_no_items) ?>

    <?= $form->field($model, 'question6')->dropDownList($yes_no_items) ?>

    <?= $form->field($model, 'question7')->dropDownList($yes_no_items) ?>

    <?= $form->field($model, 'question8')->dropDownList($yes_no_items) ?>

    <?= $form->field($model, 'question9')->dropDownList($yes_no_items) ?>

    <?= $form->field($model, 'question10')->dropDownList($yes_no_items) ?>

    <?= $form->field($model, 'question11')->dropDownList($yes_no_items) ?>

    <?= $form->field($model, 'question13')->dropDownList($yes_no_items) ?>

    <?= $form->field($model, 'question14')->dropDownList($yes_no_items) ?>

    <?= $form->field($model, 'count')->textInput(['autocomplete' => 'off']) ?>

    <?= $form->field($model, 'masa_porcii')->textInput(['autocomplete' => 'off']) ?>

    <p class="text-danger">Если число не является целым, то разделителем целой и дробной части является точка. (Например:2.85)</p>
    <?= $form->field($model, 'masa_othodov')->textInput(['autocomplete' => 'off']) ?>

    <?//= $form->field($model, 'created_at')->textInput() ?>

    <div class="form-group text-center">
            <?= Html::submitButton('Отправить', ['class' => 'btn main-button-3']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
