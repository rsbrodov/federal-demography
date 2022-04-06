<?php

use common\models\AnketParentControl;
use common\models\Menus;
use common\models\User;
use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model common\models\AnketParentControl */
/* @var $form yii\widgets\ActiveForm */
if(!Yii::$app->user->can('teacher'))
{
    $my_teachers = User::find()->where(['organization_id' => Yii::$app->user->identity->organization_id])->all();
}else{
    $my_teachers = User::find()->where(['id' => Yii::$app->user->id])->all();
}
$my_teachers = ArrayHelper::map($my_teachers, 'id', 'name');

$class_item = [
    1 => '1',
    2 => '2',
    3 => '3',
    4 => '4',
    5 => '5',
    6 => '6',
    7 => '7',
    8 => '8',
    9 => '9',
    10 => '10',
    11 => '11',
    12 => '12',
    13 => 'Коррекционный',
    21 => '1(подготовительный)',
    22 => '2(подготовительный)',
    23 => '3(подготовительный)',
    24 => '4(подготовительный)',

];

$letter_item = [
    'А(1)' => 'А(1)',
    'Б(2)' => 'Б(2)',
    'В(3)' => 'В(3)',
    'Г(4)' => 'Г(4)',
    'Д(5)' => 'Д(5)',
    'Е(6)' => 'Е(6)',
    'Ж(7)' => 'Ж(7)',
    'З(8)' => 'З(8)',
    'И(9)' => 'И(9)',
    'К(10)' => 'К(10)',
    'Л(11)' => 'Л(11)',
    'М(12)' => 'М(12)',
    'Н(13)' => 'Н(13)',
    'О(14)' => 'О(14)',
    'П(15)' => 'П(15)',
    'Р(16)' => 'Р(16)',
    'С(17)' => 'С(17)',
    'Т(18)' => 'Т(18)',
    'У(19)' => 'У(19)',
    'Ф(20)' => 'Ф(20)',
    'Х(21)' => 'Х(21)',
    'Ц(22)' => 'Ц(22)',
    '(нет буквы)' => '(нет буквы)',
];

$smena_item = [
    1 => '1',
    2 => '2',
];

?>

<div class="anket-parent-control-form container mt-5">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'class_number')->dropDownList($class_item) ?>

    <?= $form->field($model, 'class_letter')->dropDownList($letter_item) ?>

    <?= $form->field($model, 'smena')->dropDownList($smena_item) ?>

    <?= $form->field($model, 'user_id')->dropDownList($my_teachers) ?>



    <div class="form-group text-center">
            <?= Html::submitButton('Сохранить', ['class' => 'btn main-button-3']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
