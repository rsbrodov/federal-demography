<?php

use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $model common\models\PasswordChange */

$this->title = 'Изменение пароля';
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
    <style>
        .img-blok{
            display: flex;
            justify-content: center;
        }
    </style>

    <div class="change-password">
    <div class="row justify-content-center mt-3">
    <div class="col-md-6">
        <h1 class="text-center"><?= Html::encode($this->title) ?></h1>

        <div class="img-blok">
            <img src="https://www.saferstdtesting.com/wp-content/uploads/1.png" width="25%" height="auto" alt="">
        </div>
        <div>
            <p class="text-center mt-3" style="color: #636363"><b><?=$organization->title;?></b></p>
        </div>

        <?php $form = ActiveForm::begin(['id' => 'change-password']); ?>

        <?= $form->field($model, 'password_old')->passwordInput(['maxlength' => true,'class' => 'form-control']) ?>

        <?= $form->field($model, 'password_new')->passwordInput(['maxlength' => true,'class' => 'form-control']) ?>

        <?= $form->field($model, 'password_repeat')->passwordInput(['maxlength' => true,'class' => 'form-control']) ?>

        <div class="form-group">
            <?= Html::submitButton('Изменить', ['class' => 'btn main-button-3 col-md-12', 'name' => 'change-password-button']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>

<?php
$js = <<< JS
	$('form').on('beforeSubmit', function(){
        var form = $(this);
        var submit = form.find(':submit');
        submit.html('<span class="fa fa-spin fa-spinner"></span> Пожалуйста, подождите...');
        submit.prop('disabled', true);
    });
JS;
$this->registerJs($js, View::POS_READY);