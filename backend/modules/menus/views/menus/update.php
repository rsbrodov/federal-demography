<?php

use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;
//use kartik\date\DatePicker;
//require 'C:\OSPanel\domains\yii2\vendor\kartik-v\yii2-widget-datepicker\src\DatePicker.php';
use common\models\AgeInfo;
use common\models\FeedersCharacters;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model common\models\Menus */

$this->title = 'Редактирование меню: ' . $menus->name;
$this->params['breadcrumbs'][] = ['label' => 'Menuses', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $menus->id, 'url' => ['view', 'id' => $menus->id]];

$Age = AgeInfo::find()->all();
$Age_items = ArrayHelper::map($Age, 'id', 'name');

$characters = FeedersCharacters::find()->all();
$characters_items = ArrayHelper::map($characters, 'id', 'name');

$param1 = ['options' =>[ $menus->feeders_characters_id => ['Selected' => true]], 'class'=>'form-control col-11 col-md-3'];
$param2 = ['options' =>[ $menus->age_info_id => ['Selected' => true]], 'class'=>'form-control col-11 col-md-3'];
$param3 = ['options' =>[ $menus->type_org_id => ['Selected' => true]], 'class'=>'form-control col-11 col-md-3'];
$param_v = ['options' =>[ $menus->variativity => ['Selected' => true]]];
$this->params['breadcrumbs'][] = 'Update';

$type_org = \common\models\TypeOrganization::find()->where(['id' => [3,1,5,6]])->all();
$type_org_items = ArrayHelper::map($type_org, 'id', 'name');

$variativity_items = [
    0 => 'Стандартное',
    1 => 'Вариативное',
];
?>
<?$variativity_mas = [
    'zavtrak' => [
        'zavtrak_kashi_garniri_yaich' => 'zavtrak_kashi_garniri_yaich',
        'zavtrak_masn_ryb' => 'zavtrak_masn_ryb',
        'zavtrak_napitki' => 'zavtrak_napitki',
        'zavtrak_souse' => 'zavtrak_souse',
    ],
    'obed' => [
        'obed_pervie' => 'obed_pervie',
        'obed_holod' => 'obed_holod',
        'obed_garniri' => 'obed_garniri',
        'obed_myasn_ryb' => 'obed_myasn_ryb',
        'obed_napitki' => 'obed_napitki',
        'obed_souse' => 'obed_souse',
    ],
    'ushin' => [
        'ushin_holod' => 'ushin_holod',
        'ushin_garniri' => 'ushin_garniri',
        'ushin_myas_ryb' => 'ushin_myas_ryb',
        'ushin_napitki' => 'ushin_napitki',
        'ushin_souse' => 'ushin_souse',
    ]

];?>
<style>
    .day-container{
        display:flex;
        flex-wrap: wrap;
        justify-content: space-around;
    }
    .nutrition-container{
        display:flex;
        flex-wrap: wrap;
        justify-content: space-around;
        padding: 5px;
    }
    .border-block{
        border:1px solid #ced4da;
        border-radius: 10px;
        margin-bottom: 25px;
        padding: 5px;
    }
</style>
<div class="menus-update">

    <h1 class="text-center"><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin(); ?>
    <?php
    $two_column = ['options'=>['class'=>'row justify-content-center mt-3'], 'labelOptions' => ['class' => 'col-11 col-md-3 col-form-label font-weight-bold']];
    ?>
    <?= $form->field($model, 'name', $two_column)->textInput(['value' => $menus->name, 'class'=>'form-control col-11 col-md-3']) ?>

    <?if(Yii::$app->user->can('rospotrebnadzor_camp') || Yii::$app->user->can('rospotrebnadzor_nutrition') || Yii::$app->user->can('admin') || Yii::$app->user->can('food_director') || Yii::$app->user->can('subject_minobr')  || Yii::$app->user->can('minobr') || Yii::$app->user->can('hidden_user')){?>
        <?= $form->field($model, 'type_org_id', $two_column)->dropDownList($type_org_items, $param3) ?>
    <?}?>

    <?= $form->field($model, 'age', $two_column)->dropDownList($Age_items, $param2) ?>

    <?= $form->field($model, 'characters', $two_column)->dropDownList($characters_items, $param1) ?>

    <?= $form->field($model, 'cycles', $two_column)->textInput(['value' => $menus->cycle, 'class'=>'form-control col-11 col-md-3 mb-3']) ?>

    <?php
        $days = ArrayHelper::getColumn($menus_days, 'days_id');
        $div = '<div class="border-block container">';
        $div .= '<p style="font-size: 18px;"><b>Дни меню <small>(отметьте нужные варианты галочками)</small></b></p>';
        $div .= '<div class="day-container">';
        for($j=0,$i=1;$i<8;$i++) {
            $attribute = 'days';
            $attribute .= $i;
            if($days[$j] == $i) {
                $div .= '<div>';
                $div .= $form->field($model, $attribute, ['options'=>['class'=>'form-group form-check-inline']])->checkbox(['value' => '1', 'checked ' => true]);
                $div .= '</div>';
                $j++;
            } else {
                $div .= '<div>';
                $div .= $form->field($model, $attribute, ['options'=>['class'=>'form-group form-check-inline']])->checkbox();
                $div .= '</div>';
            }
        }
        $div .= '</div>';
        $div .= '</div>';
        echo $div;
    ?>
    <br>

    <?php
        $nutrition = ArrayHelper::getColumn($menus_nutrition, 'nutrition_id');
    $div = '<div class="border-block container">';
    $div .= '<p style="font-size: 18px;"><b>Приемы пищи <small>(отметьте нужные варианты галочками)</small></b></p>';
    $div .= '<div class="day-container">';
        for($j=0,$i=1;$i<7;$i++) {
            $attribute = 'nutrition';
            $attribute .= $i;
            if($nutrition[$j] == $i) {
                $div .= '<div>';
                $div .= $form->field($model, $attribute, ['options'=>['class'=>'form-group form-check-inline']])->checkbox([ 'value' => '1', 'checked ' => true]);
                $div .= '</div>';
                $j++;
            } else {
                $div .= '<div>';
                $div .= $form->field($model, $attribute, ['options'=>['class'=>'form-group form-check-inline']])->checkbox();
                $div .= '</div>';
            }
        }
        $div .= '</div>';
        $div .= '</div>';
        echo $div;
    ?>

    <?= $form->field($model, 'date_start', $two_column)->textInput([ 'value' => date("d.m.Y", $menus->date_start), 'class'=>'datepicker-here form-control col-11 col-md-3']) ?>

    <?= $form->field($model, 'date_end', $two_column)->textInput([ 'value' => date("d.m.Y", $menus->date_end), 'class'=>'datepicker-here form-control col-11 col-md-3 mb-3']) ?>

    <?if(Yii::$app->user->can('admin')){?>
        <div class="container">
            <?= $form->field($model, 'variativity')->dropDownList($variativity_items, $param_v)->label('Вариативность') ?>
            <div class="variativity">
                <div class="border-block mt-3 ml-5">
                    <p style="font-size: 18px;"><b>Завтраки<small>(отметьте нужные варианты галочками)</small></b></p>
                    <div class="nutrition-container">
                        <?foreach($variativity_mas['zavtrak'] as $v){?>
                            <?if(\common\models\MenusVariativity::find()->where(['menu_id' => $menus->id, 'variativity_id' => \common\models\Variativity::find()->where(['name' => $v])->one()->block_id])->one()){?>
                                <?= $form->field($model, $v)->checkbox([ 'value' => '1', 'checked ' => true]) ?>
                            <?}else{?>
                                <?= $form->field($model, $v)->checkbox() ?>
                            <?}?>
                        <?}?>
                    </div>
                </div>

                <div class="border-block mt-3 ml-5">
                    <p style="font-size: 18px;"><b>Обеды<small>(отметьте нужные варианты галочками)</small></b></p>
                    <div class="nutrition-container">
                        <?foreach($variativity_mas['obed'] as $v){?>
                            <?if(\common\models\MenusVariativity::find()->where(['menu_id' => $menus->id, 'variativity_id' => \common\models\Variativity::find()->where(['name' => $v])->one()->block_id])->one()){?>
                                <?= $form->field($model, $v)->checkbox([ 'value' => '1', 'checked ' => true]) ?>
                            <?}else{?>
                                <?= $form->field($model, $v)->checkbox() ?>
                            <?}?>
                        <?}?>
                    </div>
                </div>

                <div class="border-block mt-3 ml-5">
                    <p style="font-size: 18px;"><b>Ужины<small>(отметьте нужные варианты галочками)</small></b></p>
                    <div class="nutrition-container">
                        <?foreach($variativity_mas['ushin'] as $v){?>
                            <?if(\common\models\MenusVariativity::find()->where(['menu_id' => $menus->id, 'variativity_id' => \common\models\Variativity::find()->where(['name' => $v])->one()->block_id])->one()){?>
                                <?= $form->field($model, $v)->checkbox([ 'value' => '1', 'checked ' => true]) ?>
                            <?}else{?>
                                <?= $form->field($model, $v)->checkbox() ?>
                            <?}?>
                        <?}?>
                    </div>
                </div>
            </div>
        </div>
    <?}?>

    <div class="form-group justify-content-center text-center" style="margin-bottom: 135px">
        <?= Html::submitButton('Отредактировать меню', ['class' => 'mt-2 btn main-button-3 col-11 col-md-6']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
<?if($menus->variativity == 1){?>
    <script>
        $('.variativity').show();
    </script>
<?}else{?>
    <script>
        $('.variativity').hide();
    </script>
<?}?>
<?
//print_r($data);
$script = <<< JS
    var field2 = $('#menuform-variativity');
    field2.on('change', function () {
        if (field2.val() === "1") {
            $('.variativity').show();
        }
        else{
           $('.variativity').hide();
        }
    });
    field2.trigger('change');
JS;

$this->registerJs($script, yii\web\View::POS_READY);
?>