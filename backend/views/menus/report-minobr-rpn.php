<?php

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

$this->title = 'Общий отчет';
$this->params['breadcrumbs'][] = $this->title;


$organization_id = Yii::$app->user->identity->organization_id;

$region_id = Organization::findOne($organization_id)->region_id;

if (Yii::$app->user->can('minobr') || Yii::$app->user->can('rospotrebnadzor_nutrition'))
{
    $municipalities = \common\models\Municipality::find()->where(['region_id' => $region_id])->all();
    $municipality_null = array(0 => 'Все муниципальные округа ...');
    $municipality_items = ArrayHelper::map($municipalities, 'id', 'name');
    $municipality_items = ArrayHelper::merge($municipality_null, $municipality_items);
    $organization = Organization::find()->where(['type_org' => 3, 'region_id' => $region_id])->all();
}
if (Yii::$app->user->can('subject_minobr'))
{
    $my_org = Organization::findOne($organization_id);
    $municipalities = \common\models\Municipality::find()->where(['id' =>$my_org->municipality_id])->all();
    $municipality_items = ArrayHelper::map($municipalities, 'id', 'name');
    $organization = Organization::find()->where(['type_org' => 3, 'municipality_id' => $my_org->municipality_id])->andWhere(['!=', 'id', 7])->all();
}
$organization_null = array(0 => 'Все организации ...');
$organization_items = ArrayHelper::map($organization, 'id', 'title');
$organization_items = ArrayHelper::merge($organization_null, $organization_items);
if (!empty($post))
{
    $params_organization = ['class' => 'form-control', 'options' => [$post['organization_id'] => ['Selected' => true]]];
}

?>

<h1 class="text-center"><?= Html::encode($this->title) ?></h1>

<?// if (empty($my_menus) && (!(Yii::$app->user->can('rospotrebnadzor_camp') || Yii::$app->user->can('rospotrebnadzor_nutrition') || Yii::$app->user->can('subject_minobr')))) { ?>
<!--    <p class="text-center" style="color: red"><b>У Вас не созданы меню и не добавлены блюда.(Перейдите в раздел "Архив-->
<!--            меню" или "Настройка меню")</b></p>-->
<? //} ?>

<?php $form = ActiveForm::begin([]); ?>
<div class="container mb-5 mt-5">
    <div class="row">
        <? if (Yii::$app->user->can('minobr') || Yii::$app->user->can('rospotrebnadzor_nutrition')) { ?>
            <div class="col-6">
                <?= $form->field($model, 'municipality_id')->dropDownList($municipality_items, [
                    'class' => 'form-control', 'options' => [$post['parent_id'] => ['Selected' => true]],
                    'onchange' => '
                      $.get("../menus/orglist?id="+$(this).val(), function(data){
                        $("select#menus-organization_id").html(data);
                      });'
                ])->label('Муниципальный округ'); ?>
            </div>
        <?}?>
        <? if (Yii::$app->user->can('subject_minobr')) { ?>
            <?$my_organization = \common\models\Organization::findOne(Yii::$app->user->identity->organization_id);
            $my_municipality = \common\models\Municipality::findOne($my_organization->municipality_id);
                if ($my_municipality->city_status == 1) { ?>
                    <?$cities_null = array(0 => 'Все районы...');
                    $cities = \common\models\City::find()->where(['municipality_id' => $my_municipality->id])->all();
                    $cities_items = ArrayHelper::map($cities, 'id', 'name');
                    $cities_items = ArrayHelper::merge($cities_null, $cities_items);?>
                    <div class="col-3">
                        <?= $form->field($model, 'municipality_id')->dropDownList($municipality_items, [
                            'class' => 'form-control', 'options' => [$post['municipality_id'] => ['Selected' => true]],
                            'disabled' => 'disabled',
                        ])->label('Муниципальный округ'); ?>
                    </div>
                    <div class="col-3">
                        <?= $form->field($model, 'city_id')->dropDownList($cities_items, [
                            'class' => 'form-control', 'options' => [$post['city_id'] => ['Selected' => true]],
                            'onchange' => '
                                $.get("../menus/orgcity?id="+$(this).val(), function(data){
                                  $("select#selectorgform-organization").html(data);
                                });'
                        ])->label('Городской район'); ?>
                    </div>
                <?}else{?>
                    <div class="col-6">
                        <?= $form->field($model, 'municipality_id')->dropDownList($municipality_items, [
                            'class' => 'form-control', 'options' => [$post['municipality_id'] => ['Selected' => true]],
                            'disabled' => 'disabled',
                        ])->label('Муниципальный округ'); ?>
                    </div>
                <?}?>
            <?}?>

        <div class="col-6">
            <?= $form->field($model, 'organization')->dropDownList($organization_items, [
                'class' => 'form-control', 'options' => [$post['organization'] => ['Selected' => true]],
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
                    'headerOptions' => ['class' => 'grid_table_th text-center align-middle', 'style' => ['width' => '500px']],
                    'contentOptions' => ['class' => ''],
                ],
                [
                    'attribute' => 'Количество меню',
                    'value' => function ($model) {
                        return Menus::find()->where(['organization_id' => $model->id, 'status_archive' => 0])->count();
                    },
                    'label' => 'Количество меню',
                    'headerOptions' => ['class' => 'grid_table_th text-center align-middle', 'style' => ['width' => '150px']],
                    'contentOptions' => ['class' => 'text-center'],
                ],
                [
                    'attribute' => 'Количество созданных рецептур',
                    'value' => function ($model) {
                        return \common\models\RecipesCollection::find()->where(['organization_id' => $model->id])->count();
                    },
                    'label' => 'Количество созданных рецептур',
                    'headerOptions' => ['class' => 'grid_table_th text-center align-middle', 'style' => ['width' => '200px']],
                    'contentOptions' => ['class' => 'text-center'],
                ],
                [
                    'attribute' => 'Дата последней работы с меню',
                    'value' => function ($model) {
                        $menus = Menus::find()->where(['organization_id' => $model->id, 'status_archive' => 0])->all();
                        $m_id = [];
                        foreach ($menus as $menu)
                        {
                            $m_id[] = $menu->id;
                        }
                        $c = \common\models\MenusDishes::find()->where(['menu_id' => $m_id])
                            ->orderBy(['created_at' => SORT_DESC])
                            ->limit(1)
                            ->one()->created_at;
                        $u = \common\models\MenusDishes::find()->where(['menu_id' => $m_id])
                            ->orderBy(['updated_at' => SORT_DESC])
                            ->limit(1)
                            ->one()->updated_at;
                        if (empty($u) && !empty($c))
                        {
                            return date('d.m.Y H:i', strtotime($c));
                        }
                        if (empty($c) && !empty($u))
                        {
                            return date('d.m.Y H:i', strtotime($u));
                        }
                        if (empty($c) && empty($u))
                        {
                            return 'Не работал';
                        }
                        if (strtotime($u) > strtotime($c))
                        {
                            return date('d.m.Y H:i', strtotime($u));
                        }
                        else
                        {
                            return date('d.m.Y H:i', strtotime($c));
                        }

                    },
                    'label' => 'Дата последней работы с меню',
                    'headerOptions' => ['class' => 'grid_table_th text-center align-middle', 'style' => ['width' => '200px']],
                    'contentOptions' => ['class' => 'text-center'],
                ],
                [
                    'attribute' => 'Организатор питания',
                    'value' => function ($model) {
                        if(\common\models\NutritionApplications::find()->where(['sender_org_id' => $model->id, 'status' => 1])->orWhere(['reciever_org_id' => $model->id, 'status' => 1])->count() > 0){
                            return 'Есть';
                        }
                        else{
                            return 'Не выбран';
                        }

                    },
                    'headerOptions' => ['class' => 'grid_table_th text-center align-middle', 'style' => ['width' => '150px']],
                    'contentOptions' => ['class' => 'text-center'],
                ],

                [
                    'attribute' => 'Количество зарегистрированных аккаунтов',
                    'value' => function ($model) {
                    return \common\models\User::find()->where(['organization_id' => $model->id])->count();

                    },
                    'headerOptions' => ['class' => 'grid_table_th text-center align-middle', 'style' => ['width' => '150px']],
                    'contentOptions' => ['class' => 'text-center'],
                ],
                [
                    'attribute' => 'Количество классов в питании',
                    'value' => function ($model) {
                        $mas_u = [];
                        $my_teachers = \common\models\User::find()->where(['organization_id' =>$model->id])->all();
                        foreach ($my_teachers as $teacher){
                            $mas_u[] = $teacher->id;
                        }
                        $infos = \common\models\EverydayClasses::find()->where(['user_id' => $mas_u])->count();
                        return $infos;

                    },
                    'label' => 'Количество внесенной информации по классам (охват питания)',
                    'headerOptions' => ['class' => 'grid_table_th text-center align-middle', 'style' => ['width' => '150px']],
                    'contentOptions' => ['class' => 'text-center'],
                ],
                [
                    'attribute' => 'Количество структурных подразделений',
                    'value' => function ($model) {
                        return '1(основное)';

                    },
                    'headerOptions' => ['class' => 'grid_table_th text-center align-middle', 'style' => ['width' => '150px']],
                    'contentOptions' => ['class' => 'text-center'],
                ],
                /*[
                    'attribute' => 'feeders_characters_id',
                    'value' => function($model){
                        return $model->get_characters($model->feeders_characters_id);
                    },
                    'headerOptions' => ['class' => 'grid_table_th'],
                    'contentOptions' => ['class' => ''],
                ],
                [
                    'attribute' => 'age_info_id',
                    'value' => function($model){
                        return $model->get_age($model->age_info_id);
                    },
                    'headerOptions' => ['class' => 'grid_table_th'],
                    'contentOptions' => ['class' => 'text-center'],
                ],
                [
                    'attribute' => 'Дни меню',
                    'value' => function($model){
                        return $model->get_days($model->id, 'short_name');
                    },
                    'headerOptions' => ['class' => 'grid_table_th'],
                    'contentOptions' => ['class' => ''],
                ],
                [
                    'attribute' => 'cycle',
                    'value' => 'cycle',
                    'headerOptions' => ['class' => 'grid_table_th'],
                    'contentOptions' => ['class' => 'text-center'],
                ],*/
                /*[
                    'attribute' => 'status_archive',
                    'value' => 'status_archive',
                    'headerOptions' => ['class' => 'grid_table_th'],
                    'contentOptions' => ['class' => 'text-center'],
                ],*/
                /*[
                    'attribute' => 'Дата добавления',
                    'value' => function($model){
                        return $model->get_date($model->id);
                    },
                    'headerOptions' => ['class' => 'grid_table_th'],
                    'contentOptions' => ['class' => 'text-center'],
                ],*/

            ],
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
