<?php

use common\models\AuthAssignment;
use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = 'Редактирование данных сотрудника';
$post_items = [
        1 => 'Медицинский работник',
        2 => 'Работник столовой',
        3 => 'Учитель/Классный руководитель',
];
$auth = AuthAssignment::find()->where(['user_id' => $_GET['id']])->one()->item_name;

if ($auth == 'medic')
{
    $role = 1 ;
}
elseif ($auth == 'foodworker')
{
    $role = 2;
}
elseif ($auth == 'teacher')
{
    $role = 3;
}

$params_role= ['class' => 'form-control', 'options' => [$role => ['Selected' => true]]];
?>
<div class="user-create">
    <div class="row justify-content-center mt-3">
        <div class="col-md-6">
                <h1 class="text-center"><?= Html::encode($this->title) ?></h1>

                <?php $form = ActiveForm::begin(); ?>

                <?= $form->field($model, 'name')->textInput(); ?>

           <? if (Yii::$app->user->id != $_GET['id']){?>
                <?= $form->field($model, 'post')->dropDownList($post_items, $params_role) ?>
           <?}?>
                <?= $form->field($model, 'email')->textInput()->label('Email сотрудника'); ?>

                <?//= $form->field($model2, 'password')->textInput(); ?>
                <div class="form-group">
                    <?= Html::submitButton('Сохранить изменения', ['class' => 'btn main-button-3 col-md-12']) ?>
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