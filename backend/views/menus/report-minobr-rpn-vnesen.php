<?php

use common\models\AnketParentControl;
use yii\bootstrap4\Html;
use yii\grid\GridView;
use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Button;
use common\models\Menus;
use common\models\Days;
use common\models\MenusDays;
use common\models\MenusDishes;
use common\models\MenusNutrition;
use common\models\Organization;
use yii\helpers\ArrayHelper;


/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Отчет о внесенной информации';
$this->params['breadcrumbs'][] = $this->title;


$organization_id = Yii::$app->user->identity->organization_id;
$region_id = Organization::findOne($organization_id)->region_id;

if (Yii::$app->user->can('minobr') || Yii::$app->user->can('rospotrebnadzor_nutrition'))
{
    $municipality_items = Yii::$app->territory->municipalities($region_id, true, false);
}
if (Yii::$app->user->can('subject_minobr'))
{
    $municipality_items = Yii::$app->territory->my_municipality();
}
$organization_items = Yii::$app->territory->my_organizations();
if (!empty($post))
{
    $params_organization = ['class' => 'form-control', 'options' => [$post['organization_id'] => ['Selected' => true]]];
}

?>

<h1 class="text-center"><?= Html::encode($this->title) ?></h1>

<? if (empty($my_menus) && (!(Yii::$app->user->can('rospotrebnadzor_camp') || Yii::$app->user->can('rospotrebnadzor_nutrition') || Yii::$app->user->can('subject_minobr') || Yii::$app->user->can('minobr')))) { ?>
    <p class="text-center" style="color: red"><b>У Вас не созданы меню и не добавлены блюда.(Перейдите в раздел "Архив
            меню" или "Настройка меню")</b></p>
<? } ?>

<?php $form = ActiveForm::begin([]); ?>
<div class="container mb-5 mt-5">
    <div class="row">
        <div class="col-md-6">
            <? if (Yii::$app->user->can('minobr') || Yii::$app->user->can('rospotrebnadzor_nutrition')) { ?>
                <?= $form->field($model, 'parent_id')->dropDownList($municipality_items, [
                    'class' => 'form-control text-center', 'options' => [$post['parent_id'] => ['Selected' => true]],
                    'onchange' => '
                  $.get("../menus/orglist?id="+$(this).val(), function(data){
                    $("select#menus-organization_id").html(data);
                  });'
                ])->label('Муниципальный округ'); ?>
            <?}?>
            <? if (Yii::$app->user->can('subject_minobr')) { ?>
                <?= $form->field($model, 'parent_id')->dropDownList($municipality_items, [
                    'class' => 'form-control text-center', 'options' => [$post['parent_id'] => ['Selected' => true]],
                    'disabled' => 'disabled',
                    'onchange' => '
                  $.get("../menus/orglist?id="+$(this).val(), function(data){
                    $("select#menus-organization_id").html(data);
                  });'
                ])->label('Муниципальный округ'); ?>
            <?}?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'organization_id')->dropDownList($organization_items, [
                'class' => 'form-control text-center', 'options' => [$post['organization_id'] => ['Selected' => true]],
            ]); ?>
        </div>


    </div>


    <div class="row">
        <div class="form-group" style="margin: 0 auto">
            <?= Html::submitButton('Посмотреть', ['name' => 'identificator', 'value' => 'view', 'class' => 'btn main-button-3 beforeload mt-3']) ?>
            <button class="btn main-button-3 load mt-3" type="button" disabled style="display: none">
                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                Посмотреть...
            </button>
        </div>
    </div>
    <p class="text-center"><b>Для крупных городов и муниципальных образований формирование отчета может занимать некоторое время</b></p>
    <?php ActiveForm::end(); ?>
</div>
<div>

    <? if ($post){ ?>

    <div class="">
        <!--        <p style="font-size: 20px;"><b>Архив моих меню</b></p>-->
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'options' => [
                'class' => 'menus-table table-responsive '],
            'tableOptions' => [
                'class' => 'table table-bordered table-responsive'
            ],

            'columns' => [
                ['class' => 'yii\grid\SerialColumn',
                    'headerOptions' => ['class' => 'grid_table_th align-middle', 'style' => ['width' => '25px']],
                ],
                [
                    'attribute' => 'title',
                    'value' => 'title',
                    'label' => 'Наименование организации',
                    'headerOptions' => ['class' => 'grid_table_th text-center align-middle', 'style' => ['width' => '200px']],
                    'contentOptions' => ['class' => ''],
                    'footer' => 'Всего внесено данных:',
                ],
                [
                    'attribute' => 'Общая информация',
                    'value' => function ($model) {
                        $org = \common\models\Organization::findOne($model->id);
                        if(!empty($org->address) && !empty($org->email) && !empty($org->phone) && !empty($org->name_dir)){

                            return 'Внесена';
                        }
                        else{
                            return 'Не внесена';
                        }
                    },
                    'footer' => Menus::get_total_org($dataProvider->models, 'ob_info'),
                    'contentOptions' =>function ($model) {
                        $org = \common\models\Organization::findOne($model->id);
                        if(!empty($org->address) && !empty($org->email) && !empty($org->phone) && !empty($org->name_dir))
                        {
                            return ['class' => 'text-center', 'style' => 'background-color:#68ed9d;'];
                        }else{
                            return ['class' => 'text-center', 'style' => 'background-color:#f74868;'];
                        }
                    },
                    'label' => 'Общая информация',
                    'headerOptions' => ['class' => 'grid_table_th text-center align-middle', 'style' => ['width' => '150px']],

                ],



                [
                    'attribute' => 'Информация о сменах и переменах',
                    'value' => function ($model) {
                        if(\common\models\SchoolBreak::find()->where(['organization_id' => $model->id])->count() > 0){
                            return 'Внесена';
                        }
                        else{
                            return 'Не внесена';
                        }
                    },
                    'footer' => Menus::get_total_org($dataProvider->models, 'smena_peremena_info'),
                    'label' => 'Информация о сменах и переменах',
                    'headerOptions' => ['class' => 'grid_table_th text-center align-middle', 'style' => ['width' => '200px']],
                    'contentOptions' =>function ($model) {
                         if(\common\models\SchoolBreak::find()->where(['organization_id' => $model->id])->count() > 0)
                        {
                            return ['class' => 'text-center', 'style' => 'background-color:#68ed9d;'];
                        }else{
                            return ['class' => 'text-center', 'style' => 'background-color:#f74868;'];
                        }
                    },
                ],
                [
                    'attribute' => 'Информация о количестве обучающихся',
                    'value' => function ($model) {
                        if(\common\models\InformationEducation::find()->where(['organization_id' => $model->id, 'year' => '2021/2022'])->count() > 0){
                            return 'Внесена';
                        }
                        else{
                            return 'Не внесена';
                        }
                    },
                    'footer' => Menus::get_total_org($dataProvider->models, 'count_study_info'),
                    'label' => 'Информация о количестве обучающихся',
                    'headerOptions' => ['class' => 'grid_table_th text-center align-middle', 'style' => ['width' => '200px']],
                    'contentOptions' =>function ($model) {
                        if(\common\models\InformationEducation::find()->where(['organization_id' => $model->id, 'year' => '2021/2022'])->count() > 0){
                            return ['class' => 'text-center', 'style' => 'background-color:#68ed9d;'];
                        }else{
                            return ['class' => 'text-center', 'style' => 'background-color:#f74868;'];
                        }
                    },
                ],
                
                [
                    'attribute' => 'Информация о характеристике обучающихся',
                    'value' => function ($model) {
                        if(\common\models\Students::find()->where(['organization_id' => $model->id])->count() > 0){
                            return 'Внесена';
                        }
                        else{
                            return 'Не внесена';
                        }
                    },
                    'footer' => Menus::get_total_org($dataProvider->models, 'characters_study_info'),
                    'label' => 'Информация о характеристике обучающихся',
                    'headerOptions' => ['class' => 'grid_table_th text-center align-middle', 'style' => ['width' => '200px']],
                    'contentOptions' =>function ($model) {
                        if(\common\models\Students::find()->where(['organization_id' => $model->id])->count() > 0){
                            return ['class' => 'text-center', 'style' => 'background-color:#68ed9d;'];
                        }else{
                            return ['class' => 'text-center', 'style' => 'background-color:#f74868;'];
                        }
                    },
                ],

                [
                    'attribute' => 'Информация о производственных помещениях',
                    'value' => function ($model) {
                        if(\common\models\BasicInformation::find()->where(['organization_id' => $model->id])->count() > 0){
                            return 'Внесена';
                        }
                        else{
                            return 'Не внесена';
                        }

                    },
                    'footer' => Menus::get_total_org($dataProvider->models, 'room_info'),
                    'label' => 'Информация о производственных помещениях',
                    'headerOptions' => ['class' => 'grid_table_th text-center align-middle', 'style' => ['width' => '150px']],
                    'contentOptions' =>function ($model) {
                        if(\common\models\BasicInformation::find()->where(['organization_id' => $model->id])->count() > 0){
                            return ['class' => 'text-center', 'style' => 'background-color:#68ed9d;'];
                        }else{
                            return ['class' => 'text-center', 'style' => 'background-color:#f74868;'];
                        }
                    },
                ],

                [
                    'attribute' => 'Информация о столовой',
                    'value' => function ($model) {
                        if(\common\models\CharactersStolovaya::find()->where(['organization_id' => $model->id])->count() > 0){
                            return 'Внесена';
                        }
                        else{
                            return 'Не внесена';
                        }
                        //return 'В разработке';
                    },
                    'footer' => Menus::get_total_org($dataProvider->models, 'stolovaya_info'),
                    'label' => 'Информация о столовой',
                    'headerOptions' => ['class' => 'grid_table_th text-center align-middle', 'style' => ['width' => '150px']],
                    'contentOptions' =>function ($model) {
                        if(\common\models\CharactersStolovaya::find()->where(['organization_id' => $model->id])->count() > 0){
                            return ['class' => 'text-center', 'style' => 'background-color:#68ed9d;'];
                        }else{
                            return ['class' => 'text-center', 'style' => 'background-color:#f74868;'];
                        }
                    },
                ],

                [
                    'attribute' => 'Количество внесенных меню',
                    'value' => function ($model) {
                        $menus_count = \common\models\Menus::find()->where(['organization_id' => $model->id, 'status_archive' => 0 ])->andWhere(['>=', 'date_end', strtotime("now")])->count();
                        return $menus_count;
                    },
                    //'footer' => '-',
                    'label' => 'Информация о внесенных меню',
                    'headerOptions' => ['class' => 'grid_table_th text-center align-middle', 'style' => ['width' => '150px']],
                    'contentOptions' =>function ($model) {
                        if(\common\models\Menus::find()->where(['organization_id' => $model->id, 'status_archive' => 0 ])->andWhere(['>=', 'date_end', strtotime("now")])->count() > 0){
                            return ['class' => 'text-center', 'style' => 'background-color:#68ed9d;'];
                        }else{
                            return ['class' => 'text-center', 'style' => 'background-color:#f74868;'];
                        }
                    },
                ],

                [
                    'attribute' => 'Родительский контроль(количество мероприятий)',
                    'value' => function ($model) {
                        $menus_count = AnketParentControl::find()->where(['organization_id' => $model->id, 'status'=>1])->andWhere(['>',  'date', strtotime('01.09.2021')])->count();
                        return $menus_count;
                    },
                    //'footer' => '-',
                    'label' => 'Родительский контроль(количество мероприятий)',
                    'headerOptions' => ['class' => 'grid_table_th text-center align-middle', 'style' => ['width' => '150px']],
                    'contentOptions' =>function ($model) {
                        $count = AnketParentControl::find()->where(['organization_id' => $model->id, 'status'=>1])->andWhere(['>',  'date', strtotime('01.09.2021')])->count();
                        if($count > 0){
                            return ['class' => 'text-center', 'style' => 'background-color:#68ed9d;'];
                        }else{
                            return ['class' => 'text-center', 'style' => 'background-color:#f74868;'];
                        }
                    },
                ],
            ],
            'showFooter' => true,
            'footerRowOptions'=>['style'=>'font-weight:bold;text-decoration: underline;', 'class' => 'text-center' ],
        ]); ?>
    </div>
</div>
<? } ?>

<?

$script = <<< JS
//$('#menus-parent_id').attr('disabled', 'true');
$( ".beforeload" ).click(function() {
  $(".beforeload").css('display','none');
  $(".load").css('display','block');
  
});


/*$( ".beforeload" ).click(function() {
  $('.beforeload').append('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');
});*/






function FixTable(table) {
	var inst = this;
	this.table  = table;
 
	$('thead > tr > th',$(this.table)).each(function(index) {
		var div_fixed = $('<div/>').addClass('fixtable-fixed');
		var div_relat = $('<div/>').addClass('fixtable-relative');
		div_fixed.html($(this).html());
		div_relat.html($(this).html());
		$(this).html('').append(div_fixed).append(div_relat);
		$(div_fixed).hide();
	});
	
 
	this.StyleColumns();
	this.FixColumns();
 
	$(window).scroll(function(){
		inst.FixColumns()
	}).resize(function(){
		inst.StyleColumns()
	});
}
 
FixTable.prototype.StyleColumns = function() {
	var inst = this;
	$('tr > th', $(this.table)).each(function(){
		var div_relat = $('div.fixtable-relative', $(this));
		var th = $(div_relat).parent('th');
		$('div.fixtable-fixed', $(this)).css({
			'width': $(th).outerWidth(true) - parseInt($(th).css('border-left-width')) + 'px',
			'height': $(th).outerHeight(true) + 'px',
			'left': $(div_relat).offset().left - parseInt($(th).css('padding-left')) + 'px',
			'padding-top': $(div_relat).offset().top - $(inst.table).offset().top + 'px',
			'padding-left': $(th).css('padding-left'),
			'padding-right': $(th).css('padding-right')
		});
	});
}
 
FixTable.prototype.FixColumns = function() {
	var inst = this;
	var show = false;
	var s_top = $(window).scrollTop();
	var h_top = $(inst.table).offset().top;
 
	if (s_top < (h_top + $(inst.table).height() - $(inst.table).find('.fixtable-fixed').outerHeight()) && s_top > h_top) {
		show = true;
	}
 
	$('tr > th > div.fixtable-fixed', $(this.table)).each(function(){
		show ? $(this).show() : $(this).hide()
	});
}
 
$(document).ready(function(){
	$('.fixtable').each(function() {
		new FixTable(this);
	});
});
JS;

$this->registerJs($script, yii\web\View::POS_READY);
?>
