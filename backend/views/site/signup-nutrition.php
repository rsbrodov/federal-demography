<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */

/* @var $model \frontend\models\SignupForm */

use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;
use yii\widgets\MaskedInput;
use yii\helpers\ArrayHelper;
use common\models\FederalDistrict;
use common\models\Region;
use common\models\TypeOrganization;

$this->title = 'Регистрация в программном средстве "Мониторинг питания и здоровья"';
$this->params['breadcrumbs'][] = $this->title;
   
$district_null = array('' => 'Выберите федеральный округ ...');
//$districs = FederalDistrict::find()->where(['id' => [2,5]])->all();
$districs = FederalDistrict::find()->all();
$district_items = ArrayHelper::map($districs, 'id', 'name');
$district_items = ArrayHelper::merge($district_null, $district_items);

$region_null = array('' => 'Выберите регион ...');
$regions = Region::find()->where(['district_id' => 1])->all();
$region_items = ArrayHelper::map($regions, 'id', 'name');
$region_items = ArrayHelper::merge($region_null, $region_items);


$municipality_null = array('' => 'Выберите муниципальное образование...');
$municipalities = \common\models\Municipality::find()->where(['region_id' => Region::find()->one()->id])->all();
$municipality_items = ArrayHelper::map($municipalities, 'id', 'name');


$type_lagers = \common\models\TypeLager::find()->all();
$type_lager_items = ArrayHelper::map($type_lagers, 'id', 'name');

$type_orgs = TypeOrganization::find()->where(['id' => [3, 5, 6, 4, 7, 2, 8]])->orderby(['name' => SORT_ASC])->all();
$mun_obr_items = ArrayHelper::map($type_orgs, 'id', 'name');


$subjects1 = [];

?>

    <div class="site-signup m-4">

        <h1><?= Html::encode($this->title) ?></h1>
		<p class="text-left" style="color: red">Внимание! После регистрации Вы получите логин и пароль на электронную почту.
		<br>Ваша заявка будет не активирована и авторизоваться в системе Вы не сможете.
		<br><strong>Доступ для входа в программное средство Вам будет открыт в течение 48 часов, после проверки Вашей заявки.</strong>
		<br>Просим Вас не регистрироваться повторно с другой почтой. Спасибо!
		<br>
		<br><strong><b>Если Вам необходимо пройти обучение по санитарно-просветительским программам "Основы здорового питания", 
		<br>то регистрация и вход в программное средство осуществляется на другом сайте</strongs> (<a href="http://edu.demography.site">Перейти на сайт для регистрации и прохождения обучения</a>)</b></p>
		
		<p>Пожалуйста, заполните следующие поля:</p>

        <div class="row">
            <div class="col-12">
                <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>

                <?= $form->field($model, 'federal_district_id', ['options' => ['class' => 'row'], 'labelOptions' => ['class' => 'col-3 col-form-label font-weight-bold']])->dropDownList($district_items, [
                    //'prompt' => 'Выберите федеральный округ ...',
                    'class' => 'form-control col-4',
                    //'options' => [$post['federal_district_id'] => ['Selected' => true]],
                    'onchange' => '
                  $.get("../site/subjectslistnutrition?id="+$(this).val(), function(data){
                  //$("#signupform-region_id").prop("disabled", true);
                  
                    $("select#signupform-region_id").html(data);
                    
                    document.getElementById("signupform-region_id").disabled = false;
                  });
                  
                  $.get("../site/municipalitylist?id=0", function(data){
                    $("select#signupform-municipality").html(data);
                    //document.getElementById("signupform-municipality").disabled = false;
                    });'
                ]); ?>
                <?php
                $two_column = ['options' => ['class' => 'row mt-3'], 'labelOptions' => ['class' => 'col-3 col-form-label font-weight-bold']];
                ?>
                <?= $form->field($model, 'region_id', $two_column)->dropDownList($region_items, [
                    //'prompt' => 'Выберите регион ...',
                    'class' => 'form-control col-4',
                    'onchange' => '
                  $.get("../site/municipalitylist?id="+$(this).val(), function(data){
                    $("select#signupform-municipality").html(data);
                    document.getElementById("signupform-municipality").disabled = false;
                  });'
                ]); ?>

                <?= $form->field($model, 'municipality', $two_column)->dropDownList($municipality_items, ['prompt' => 'Выберите муниципальное образование...', 'class' => 'form-control col-4']); ?>

                <?= $form->field($model, 'naseleni_punkt', $two_column)->textInput(['class' => 'form-control col-4']) ?>





                <?= $form->field($model, 'type_lager_id', $two_column)->dropDownList($type_lager_items, ['class' => 'form-control col-4']); ?>

                <br>
                <?= $form->field($model, 'type_org', $two_column)->dropDownList($mun_obr_items, [
                    'prompt' => 'Выберите тип организации ...',
                    'class' => 'form-control col-4',
                    'onchange' => 'if($(this).val()==7){console.log(123);$("input#signupform-title").val("Роспотребнадзор").prop("disabled", true);}else{$("input#signupform-title").val("");document.getElementById("signupform-title").disabled = false;}
                    '
                ]); ?>

                <?= $form->field($model, 'title', $two_column)->textInput(['class' => 'form-control col-4']) ?>



                <?= $form->field($model, 'name_dir', $two_column)->textInput(['class' => 'form-control col-4'])->label('ФИО руководителя') ?>

                <br>

                <?= $form->field($model, 'name', $two_column)->textInput(['class' => 'form-control col-4'])->label('ФИО ответственного лица в программе') ?>

                <?= $form->field($model, 'post', $two_column)->textInput(['class' => 'form-control col-4'])->label('Должность ответственного лица') ?>

                <?= $form->field($model, 'email', $two_column)->textInput(['class' => 'form-control col-4'])->label('Email организации') ?>
                <p class="text-primary"><small>На эту почту придет пароль. Почта используется в качестве логина для входа в программу.</small></p>

                <?= $form->field($model, 'phone', $two_column)->widget(MaskedInput::className(), ['mask' => '+7-(999)-999-99-99', 'clientOptions' => ['removeMaskOnSubmit' => true]])->textInput(['placeholder' => '+7-(999)-999-99-99', 'class' => 'form-control col-4']); ?>





                <?= $form->field($model, 'password', $two_column)->passwordInput(['class' => 'form-control col-4']) ?>
				<p class="text-left" style="color: red">
				<br><strong>Если Вам необходимо пройти обучение по санитарно-просветительским программам "Основы здорового питания", 
				<br>то регистрация и вход в программное средство осуществляется на другом сайте(<a href="http://edu.demography.site">Перейти на сайт для регистрации и прохождения обучения</a>)<br>Ваша заявка, если Вы (родитель, ученик, мама, папа, домохозяйка) будет отклонена.</strongs> </p>
				</p>
				
                <div class="form-group">
                    <?= Html::submitButton('Отправить запрос на регистрацию', ['class' => 'btn main-button-3 col-7 mt-3', 'name' => 'signup-button']) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
<?php
$js = <<< JS
	//document.getElementById("btn").disabled = false;
    $('#signupform-region_id').attr('disabled', 'true');
    $('#signupform-municipality').attr('disabled', 'true');
     
    var field = $('#signupform-type_org');
    field.on('change', function () {
           if (field.val() !== "1" ) {
               $('.field-signupform-type_lager_id').hide();
               $('.field-signupform-type_lager_id').val('0');
           }
           else{
              $('.field-signupform-type_lager_id').show();
           }
    });
    field.trigger('change');

    
JS;
$this->registerJs($js, \yii\web\View::POS_READY);
