<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;

$this->title = 'Авторизация';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login m-4">
		<p></p>
		<!--<p class="text-center" style="color: red"><strong>Внимание! 29.10.2020 в связи с техническим обновлением программное средство будет недоступно с 04:00 до 05:00 (мск).</strong></a></p>-->
		<!--<p><p class="text-center" style="color: red"><strong>Внимание! Вебинар от 18.11.2020 в 11:00 Новосибирск (10:00 Омск) можно посмотреть сейчас в прямом эфире, перейдя по ссылке<a target="_blank" href="https://www.youtube.com/watch?v=hXQF9GTXMIw"> по ссылке</strong></a>. 
			</p>-->	
			
<!--<p class="text-center" style="color: #28a745;font-size:20px;"><strong>21.03.21 весь день будут производиться технические работы. Программное средство будет доступно 22.03.21 с 03:00-06:00 мск</strong></p>-->
		
<p class="text-left" style="color: red">Внимание! После регистрации Вы получите логин и пароль на электронную почту. 
		<br>Ваша заявка будет не активирована и авторизоваться в системе Вы не сможете.
		<br><strong>Доступ для входа в программное средство Вам будет открыт в течение 48 часов, после проверки Вашей заявки.</strong>
		<br>Просим Вас не регистрироваться повторно с другой почтой. Спасибо!
		<br>
		<br><strong>Если Вам необходимо пройти обучение по санитарно-просветительским программам "Основы здорового питания", 
		<br>то регистрация и вход в программное средство осуществляется на другом сайте</strongs> (<a href="https://edu.demography.site">Перейти на сайт для регистрации и прохождения обучения</a>)</p>
		

    <h1><?= Html::encode($this->title) ?></h1>
	<p>Пожалуйста, заполните поля, чтобы авторизироваться:</p>

    <div class="container-fluid">
        <div class="row">
            <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

            <?= $form->field($model, 'login')->textInput(['autofocus' => true])->label('Логин (Email ответственного лица)') ?>

            <?= $form->field($model, 'password')->passwordInput() ?>

            <?= Html::submitButton('Войти в программу', ['class' => 'btn main-button-3', 'name' => 'login-button']) ?>

            <a href="#" class="btn main-button-2-hover-orange" data-target="#changePassword" data-toggle="modal">Восстановить
                пароль</a>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>


<div id="changePassword" class="modal" role="dialog">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Восстановление доступа</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?php if (!Yii::$app->session->hasFlash('changePassword'))
            { ?>
                <?php $change_form = ActiveForm::begin([
                'id' => 'change-form',
                'layout' => 'horizontal',
                'fieldConfig' => [
                    'template' => "{label}\n<div class=\"\">{input}{error}</div>",
                    'labelOptions' => ['class' => 'control-label'],
                ],
            ]); ?>
                <div class="modal-body">
                    <div class="ml-4">
                        <?= $change_form->field($change, 'email')->textInput(['class' => 'ml-3']) ?>
                    </div>

                </div>
                <div class="form-group">
                    <div class="text-center">
                        <?= Html::submitButton('Выслать пароль на почту', ['class' => 'btn btn-outline-warning']) ?>
                    </div>
                </div>
                <?php ActiveForm::end(); ?>
            <?php }
            else
            { ?>
                <div class="modal-body">
                    <div class="alert alert-success" role="alert">
                        <h4>Сообщение с паролем отправлено на Ваш Email</h4>
                        <p>Зайдите на почту и скопируйте Ваш новый пароль</p>
                    </div>
                </div>
            <?php } ?>

            <?php if (Yii::$app->session->hasFlash('changeErrorPassword'))
            { ?>
                <div class="modal-body">
                    <div class="alert alert-danger" role="alert">
                        <h4>Пользователь с указанным Email не зарегистрирован</h4>
                        <p>Перейдите в раздел регистрациии</p>
                    </div>
                </div>
            <?php }?>
        </div>
    </div>
</div>

<?php if (Yii::$app->session->hasFlash('changePassword'))
{ ?>
    <? $script = <<< JS
    $('#changePassword').modal('show');
JS;
    $this->registerJs($script, yii\web\View::POS_READY);
}
?>


<?php if (Yii::$app->session->hasFlash('changeErrorPassword'))
{ ?>
    <? $script = <<< JS
    $('#changePassword').modal('show');
JS;
    $this->registerJs($script, yii\web\View::POS_READY);
}
?>
