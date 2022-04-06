<?php

use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;
use yii\widgets\MaskedInput;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model common\models\Organization */

$this->title = 'Редактирование информации об организации';
if(Yii::$app->user->can('rospotrebnadzor_camp') || Yii::$app->user->can('subject_minobr') || Yii::$app->user->can('minobr'))
{
    $this->title = 'Просмотр информации об организации';
}
//$this->params['breadcrumbs'][] = ['label' => 'Организации', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$param1 = ['class' => 'form-control col-11 col-md-4', 'options' => [$my_organization->organizator_food => ['Selected' => true]]];
$param2 = ['class' => 'form-control col-11 col-md-4', 'options' => [$my_organization->medic_service_programm => ['Selected' => true]]];
$param3 = ['class' => 'form-control col-11 col-md-4', 'options' => [$my_organization->type_lager_id => ['Selected' => true]]];
$param4 = ['class' => 'form-control col-11 col-md-4', 'options' => [$my_organization->forma_sobstvennosti_id => ['Selected' => true]]];
$param5 = ['class' => 'form-control col-11 col-md-4', 'options' => [$my_organization->regim_id => ['Selected' => true]]];
$medic_items = [
    0 => 'Полный',
    1 => 'Краткий',
];
$food_items = [
    0 => 'Отсутствует',
    1 => 'Присутствует',
];

$regim_items = [
    0 => 'Не указан',
    1 => 'Круглогодичний',
    2 => 'Летний',
];

$food_items_camp = [
    1 => 'Полный',
    0 => 'Краткий',
];
$type_lagers = \common\models\TypeLager::find()->all();
$type_lagers_items = ArrayHelper::map($type_lagers, 'id', 'name');

$forma_sobstvennosti = \common\models\FormaSobstvennosti::find()->all();
$forma_sobstvennosti_items = ArrayHelper::map($forma_sobstvennosti, 'id', 'name');
//echo $my_organization->title;
$my_organization = \common\models\Organization::findOne(Yii::$app->user->identity->organization_id);
$my_municipality = \common\models\Municipality::findOne($my_organization->municipality_id);
?>

<div class="organization-create mt-3">
    <h1 class="text-center"><?= Html::encode($this->title) ?></h1>
    <div class="organization-form">

        <?php $form = ActiveForm::begin(); ?>
        <?php
        $two_column = ['options' => ['class' => 'row justify-content-center mt-3'], 'labelOptions' => ['class' => 'col-11 col-md-3 col-form-label font-weight-bold']];
        ?>
        <?php
        echo '<div class="row justify-content-center mt-3 field-organization-short_title">
                <label class="col-11 col-md-3 col-form-label font-weight-bold">Страна:</label>
                <input type="text" class="form-control col-11 col-md-4" value="Россия" readonly="true">
                <div class="invalid-feedback"></div>
              </div>';
        echo '<div class="row justify-content-center mt-3 field-organization-short_title">
                <label class="col-11 col-md-3 col-form-label font-weight-bold">Федеральный округ:</label>
                <input type="text" class="form-control col-11 col-md-4" value="' . $model->get_district($my_organization->federal_district_id) . '" readonly="true">
                <div class="invalid-feedback"></div>
              </div>';
        echo '<div class="row justify-content-center mt-3 field-organization-short_title">
                <label class="col-11 col-md-3 col-form-label font-weight-bold">Субъект федерации:</label>
                <input type="text" class="form-control col-11 col-md-4" value="' . $model->get_region($my_organization->region_id) . '" readonly="true">
                <div class="invalid-feedback"></div>
              </div>';
        echo '<div class="row justify-content-center mt-3 field-organization-short_title">
                <label class="col-11 col-md-3 col-form-label font-weight-bold">Муниципальное образование:</label>
                <input type="text" class="form-control col-11 col-md-4" value="' . $model->get_municipality($my_organization->municipality_id) . '" readonly="true">
                <div class="invalid-feedback"></div>
              </div>';
        if((Yii::$app->user->can('internat_director') || Yii::$app->user->can('kindergarten_director') || Yii::$app->user->can('school_director') || Yii::$app->user->can('admin')) && $my_municipality->city_status == 1 && empty($my_organization->city_id)){?>
            <div class="container mt-4" style="border:1px solid red; border-radius: 10px">
                <p class="text-danger text-center"><b>Укажите район города!</b></p>
        <?}
        if((Yii::$app->user->can('internat_director') || Yii::$app->user->can('kindergarten_director') || Yii::$app->user->can('school_director') || Yii::$app->user->can('admin')) && $my_municipality->city_status == 1){
            $cities_null = array('' => 'Выберите городской район...');
            $cities = \common\models\City::find()->where(['municipality_id' => $my_municipality->id])->all();
            $cities_mas = ArrayHelper::map($cities, 'id', 'name');
            $cities_mas = ArrayHelper::merge($cities_null, $cities_mas);

            echo '<div class="row justify-content-center mt-3 field-organization-short_title">
                <label class="col-11 col-md-3 col-form-label font-weight-bold" style="margin-left: 35px;">Городской район:</label>'.
                $form->field($model, 'city_id')->dropDownList($cities_mas, [
                    'class' => 'form-control col-12 col-md-11', 'options' => [$my_organization->city_id => ['Selected' => true]],
                ])->label(false).
                '<div class="invalid-feedback"></div>
            </div>';
        }?>
        <?if((Yii::$app->user->can('internat_director') || Yii::$app->user->can('kindergarten_director') || Yii::$app->user->can('school_director') || Yii::$app->user->can('admin')) && $my_municipality->city_status == 1 && empty($my_organization->city_id)){?>
        </div>
        <?}?>
        <?echo '<div class="row justify-content-center mt-3">
                <label class="col-11 col-md-3 col-form-label font-weight-bold">Название организации:</label>
                <p class="col-11 col-md-4">' . $my_organization->title . '</p>
                <div class="invalid-feedback"></div>
              </div>';
        echo '<div class="row justify-content-center mt-3 field-organization-short_title">
                <label class="col-11 col-md-3 col-form-label font-weight-bold">ФИО ответственного лица в программе:</label>
                <input type="text" class="form-control col-11 col-md-4" value="' . $me->name . '" readonly="true">
                <div class="invalid-feedback"></div>
              </div>';
        ?>

        <br>
        <?if(Yii::$app->user->can('internat_director') || Yii::$app->user->can('kindergarten_director') || Yii::$app->user->can('school_director') || Yii::$app->user->can('admin')){?>

            <?= $form->field($model, 'naseleni_punkt', $two_column)->textInput(['maxlength' => true, 'value' => $my_organization->naseleni_punkt, 'class' => 'form-control col-11 col-md-4']) ?>

            <?= $form->field($model, 'name_dir', $two_column)->textInput(['maxlength' => true, 'value' => $my_organization->name_dir, 'class' => 'form-control col-11 col-md-4']) ?>


            <?= $form->field($model, 'short_title', $two_column)->textInput(['maxlength' => true, 'value' => $my_organization->short_title, 'class' => 'form-control col-11 col-md-4']) ?>

            <?= $form->field($model, 'address', $two_column)->textInput(['maxlength' => true, 'value' => $my_organization->address, 'class' => 'form-control col-11 col-md-4']) ?>

            <?= $form->field($model, 'phone', $two_column)->textInput(['maxlength' => true])->widget(MaskedInput::className(), ['mask' => '+7(999)-999-99-99', 'clientOptions' => ['removeMaskOnSubmit' => true]])->textInput(['value'=>$my_organization->phone, 'class' => 'form-control col-11 col-md-4']); ?>

            <?= $form->field($model, 'email', $two_column)->textInput(['maxlength' => true, 'value' => $my_organization->email, 'class' => 'form-control col-11 col-md-4']) ?>

            <?= $form->field($model, 'inn', $two_column)->textInput(['maxlength' => true, 'value' => $my_organization->inn, 'class' => 'form-control col-11 col-md-4']) ?>

            <?//= $form->field($model, 'organizator_food', $two_column)->dropDownList($food_items, $param1) ?>

            <?//= $form->field($model, 'medic_service_programm', $two_column)->dropDownList($medic_items, $param2) ?>
            <div class="justify-content-center">
                <div class="col-auto">
                    <div class="form-group text-center">
                        <?= Html::submitButton('Сохранить', ['class' => 'btn main-button-3 mt-3 col-7', 'name' => 'signup-button']) ?>
                    </div>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
        <?}?>


        <?if(Yii::$app->user->can('medicine_director') || Yii::$app->user->can('food_director')){?>
            <?php $form = ActiveForm::begin(); ?>
            <?php
            $two_column = ['options' => ['class' => 'row justify-content-center mt-3'], 'labelOptions' => ['class' => 'col-11 col-md-3 col-form-label font-weight-bold']];
            ?>
            <?= $form->field($model, 'short_title', $two_column)->textInput(['maxlength' => true, 'value' => $my_organization->short_title, 'class' => 'form-control col-11 col-md-4']) ?>

            <?= $form->field($model, 'address', $two_column)->textInput(['maxlength' => true, 'value' => $my_organization->address, 'class' => 'form-control col-11 col-md-4']) ?>

            <?= $form->field($model, 'phone', $two_column)->textInput(['maxlength' => true])->widget(MaskedInput::className(), ['mask' => '+7(999)-999-99-99', 'clientOptions' => ['removeMaskOnSubmit' => true]])->textInput(['value'=>$my_organization->phone, 'class' => 'form-control col-11 col-md-4']); ?>

            <?= $form->field($model, 'email', $two_column)->textInput(['maxlength' => true, 'value' => $my_organization->email, 'class' => 'form-control col-11 col-md-4']) ?>

            <?= $form->field($model, 'inn', $two_column)->textInput(['maxlength' => true, 'value' => $my_organization->inn, 'class' => 'form-control col-11 col-md-4']) ?>

            <div class="justify-content-center">
                <div class="col-auto">
                    <div class="form-group text-center">
                        <?= Html::submitButton('Сохранить', ['class' => 'btn main-button-3 mt-3 col-7', 'name' => 'signup-button']) ?>
                    </div>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
        <?}?>

        <?if(Yii::$app->user->can('camp_director')){?>
            <?php $form = ActiveForm::begin(); ?>
            <?php
            $two_column = ['options' => ['class' => 'row justify-content-center mt-3'], 'labelOptions' => ['class' => 'col-11 col-md-3 col-form-label font-weight-bold']];
            ?>
            <?= $form->field($model, 'short_title', $two_column)->textInput(['maxlength' => true, 'value' => $my_organization->short_title, 'class' => 'form-control col-11 col-md-4']) ?>

            <?= $form->field($model, 'address', $two_column)->textInput(['maxlength' => true, 'value' => $my_organization->address, 'class' => 'form-control col-11 col-md-4']) ?>

            <?= $form->field($model, 'phone', $two_column)->textInput(['maxlength' => true])->widget(MaskedInput::className(), ['mask' => '+7(999)-999-99-99', 'clientOptions' => ['removeMaskOnSubmit' => true]])->textInput(['value'=>$my_organization->phone, 'class' => 'form-control col-11 col-md-4']); ?>

            <?= $form->field($model, 'email', $two_column)->textInput(['maxlength' => true, 'value' => $my_organization->email, 'class' => 'form-control col-11 col-md-4']) ?>

            <?= $form->field($model, 'inn', $two_column)->textInput(['maxlength' => true, 'value' => $my_organization->inn, 'class' => 'form-control col-11 col-md-4']) ?>

            <?= $form->field($model, 'type_lager_id', $two_column)->dropDownList($type_lagers_items, $param3) ?>

            <?= $form->field($model, 'organizator_food', $two_column)->dropDownList($food_items_camp, $param1)->label('Настройки программы питания') ?>

            <!-- --><?/*= $form->field($model, 'medic_service_programm', $two_column)->dropDownList($medic_items, $param2) */?>

            <?= $form->field($model, 'org_balansodergatel', $two_column)->textInput(['maxlength' => true, 'value' => $my_organization->org_balansodergatel, 'class' => 'form-control col-11 col-md-4']) ?>

            <?= $form->field($model, 'forma_sobstvennosti_id', $two_column)->dropDownList($forma_sobstvennosti_items, $param4) ?>

            <?= $form->field($model, 'sez_build', $two_column)->textInput(['maxlength' => true, 'value' => $my_organization->sez_build, 'class' => 'form-control col-11 col-md-4']) ?>

            <?= $form->field($model, 'date_sez_build', $two_column)->textInput(['class'=>'datepicker-here form-control col-11 col-md-4', 'value' => date('d.m.Y', $my_organization->date_sez_build), 'autocomplete'=>'off']) ?>

            <?= $form->field($model, 'sez_med', $two_column)->textInput(['maxlength' => true, 'value' => $my_organization->sez_med, 'class' => 'form-control col-11 col-md-4']) ?>

            <?= $form->field($model, 'date_sez_med', $two_column)->textInput(['class'=>'datepicker-here form-control col-11 col-md-4', 'value' => date('d.m.Y', $my_organization->date_sez_med), 'autocomplete'=>'off']) ?>

            <?= $form->field($model, 'regim_id', $two_column)->dropDownList($regim_items, $param5) ?>

            <?= $form->field($model, 'moshnost_lager_leto', $two_column)->textInput(['maxlength' => true, 'value' => $my_organization->moshnost_lager_leto, 'class' => 'form-control col-11 col-md-4']) ?>

            <?= $form->field($model, 'moshnost_lager_inoe', $two_column)->textInput(['maxlength' => true, 'value' => $my_organization->moshnost_lager_inoe, 'class' => 'form-control col-11 col-md-4']) ?>

            <div class="justify-content-center">
                <div class="col-auto">
                    <div class="form-group text-center">
                        <?
                        if($my_organization->type_org == 1)
                        {?>
                            <style>
                                .main-button-3 {
                                    color: #fff!important;
                                    background-color: #d98a43 !important;
                                    border-color: #d98a43!important;
                                }
                                }
                            </style>

                        <?}else{?>
                            <style>
                                .main-button-3 {
                                    color: #fff!important;
                                    background-color: #2cb8c1 !important;
                                    border-color: #2cb8c1!important;
                                }
                                }
                            </style>
                        <?}?>
                        <?= Html::submitButton('Сохранить', ['class' => 'btn main-button-3 mt-3 col-7', 'name' => 'signup-button']) ?>
                    </div>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
        <?}?>

        <?if(Yii::$app->user->can('rospotrebnadzor_camp') || Yii::$app->user->can('rospotrebnadzor_nutrition') || Yii::$app->user->can('subject_minobr') || Yii::$app->user->can('minobr')){?>

            <? echo '<div class="row justify-content-center mt-3 field-organization-short_title">
                <label class="col-11 col-md-3 col-form-label font-weight-bold">Короткое название:</label>
                <input type="text" class="form-control col-11 col-md-4" value="' . $my_organization->short_title . '" readonly="true">
                <div class="invalid-feedback"></div>
            </div>';?>

            <? echo '<div class="row justify-content-center mt-3 field-organization-short_title">
                <label class="col-11 col-md-3 col-form-label font-weight-bold">Адрес:</label>
                <input type="text" class="form-control col-11 col-md-4" value="' . $my_organization->address . '" readonly="true">
                <div class="invalid-feedback"></div>
            </div>';?>

            <? echo '<div class="row justify-content-center mt-3 field-organization-short_title">
                <label class="col-11 col-md-3 col-form-label font-weight-bold">Телефон:</label>
                <input type="text" class="form-control col-11 col-md-4" value="' . $my_organization->phone . '" readonly="true">
                <div class="invalid-feedback"></div>
            </div>';?>

            <? echo '<div class="row justify-content-center mt-3 field-organization-short_title">
                <label class="col-11 col-md-3 col-form-label font-weight-bold">Email:</label>
                <input type="text" class="form-control col-11 col-md-4" value="' . $my_organization->email . '" readonly="true">
                <div class="invalid-feedback"></div>
            </div>';?>

            <? echo '<div class="row justify-content-center mt-3 field-organization-short_title">
                <label class="col-11 col-md-3 col-form-label font-weight-bold">ИНН:</label>
                <input type="text" class="form-control col-11 col-md-4" value="' . $my_organization->inn . '" readonly="true">
                <div class="invalid-feedback"></div>
            </div>';?>


            <?//= $form->field($model, 'organizator_food', $two_column)->dropDownList($food_items, $param1) ?>

            <?//= $form->field($model, 'medic_service_programm', $two_column)->dropDownList($medic_items, $param2) ?>

        <?}?>
    </div>

</div>
