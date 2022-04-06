<?php

use common\models\User;
use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;
use yii\widgets\MaskedInput;

/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = 'ЛИЧНЫЙ КАБИНЕТ';
$user = User::findOne(Yii::$app->user->id);
$organization = \common\models\Organization::findOne($user->organization_id);
?>
    <style>
        .img-blok{
            display: flex;
            justify-content: center;
        }
    </style>
<div class="user-create">
    <div class="row justify-content-center mt-3">
        <div class="col-md-6">
                <h1 class="text-center"><?= Html::encode($this->title) ?></h1>

                <div class="img-blok">
                    <img src="https://www.pinclipart.com/picdir/big/164-1640714_user-symbol-interface-contact-phone-set-add-sign.png" width="25%" height="auto" alt="">
                </div>
                <div>
                    <p class="text-center mt-3" style="color: #636363"><b><?=$organization->title;?></b></p>
                </div>

                <?php $form = ActiveForm::begin(); ?>

                <?= $form->field($signup_form, 'name')->textInput(['value' => $user->name])->label('ФИО'); ?>

                <?= $form->field($signup_form, 'post')->textInput(['value' => $user->post])->label('Должность') ?>

                <?= $form->field($signup_form, 'email')->textInput(['value' => $user->email])->label('Email и ЛОГИН'); ?>

                <?= $form->field($signup_form, 'phone')->widget(MaskedInput::className(), ['mask' => '+7-(999)-999-99-99', 'clientOptions' => ['removeMaskOnSubmit' => true]])->textInput(['placeholder' => '+7-(999)-999-99-99', 'class' => 'form-control', 'value' => $user->phone])->label('Телефон'); ?>

                <div class="form-group">
                    <?= Html::submitButton('Отредактировать данные', ['class' => 'btn main-button-3 col-md-12']) ?>
                </div>
            <div class="text-center">
                <?= Html::a('<span class="glyphicon glyphicon-key"></span> Изменить пароль', ['change-password'],
                    [
                        'class'=>'btn btn-danger',
                        'style' =>['width'=>'100%'],
                        'title' => Yii::t('yii', 'Изменить пароль'),
                        'data-toggle'=>'tooltip',
                    ])
                ?>
            </div>
                <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
<?
$script = <<< JS

    
JS;

$this->registerJs($script, yii\web\View::POS_READY);

/*<script>

    //function ChangeColor() {
    //    alert(document.getElementById("txt").value);
    //    console.log($('#txt').val());
    //}
    //document.getElementById("btn").onclick = someFunc;
</script>*/