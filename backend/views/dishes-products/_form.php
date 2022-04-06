<?php

use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\DishesProducts */
/* @var $form yii\widgets\ActiveForm */
?>
<br><br>
<div class="dishes-products-form" style="width:350px">

    <?php $form = ActiveForm::begin(); ?>

    <?//= $form->field($model, 'dishes_id')->textInput() ?>

    <?//= $form->field($model, 'products_id')->textInput() ?>

    <?= $form->field($model, 'gross_weight')->textInput() ?>

    <?= $form->field($model, 'net_weight')->textInput() ?>



    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
