<?php

use common\models\AnketParentControl;
use common\models\NutritionApplications;
use common\models\Region;
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

$this->title = 'Отчет по контрольным мероприятиям';
$this->params['breadcrumbs'][] = $this->title;


?>
<style>
    th, td {
        border: 1px solid black!important;
        color: black;

    }
    th {
        background-color: #ede8b9;
        font-size: 13px;
    }
</style>
            <table id="table_control" class="table_th0 table-hover table2excel_with_colors" style="width: 100%;">
            <thead>
                <tr>
                    <th class="text-center align-middle" rowspan="2" style="width: 20px">№</th>
                    <th class="text-center align-middle" rowspan="2" style="width: 40px">Мун. район</th>
                    <th class="text-center align-middle" rowspan="2" style="width: 40px">Организация</th>
                    <th class="text-center align-middle" rowspan="2" style="width: 40px">Ключ-ссылка для входа</th>
                </tr>
            </thead>
            <tbody>


        <?
        $municipalities = \common\models\Municipality::find()->where(['region_id' => 48])->all();
        foreach($municipalities as $r_municipality){$count = 0;
                $organizations = Organization::find()->where(['municipality_id' => $r_municipality->id, 'type_org' => 3])->all();
                foreach($organizations as $organization){$count++?>
                        <tr>
                            <td class="text-center align-middle"><?= $count?></td>
                            <td class="text-center align-middle"><?=$r_municipality->name;?></td>
                            <td class="align-middle" style="font-size: 13px;"><?echo (empty($organization->short_title)) ? $organization->title : $organization->short_title;?></td>
                            <td class="text-center align-middle">https://demography.site/anket-parent-control/parent-outside-link?id=<?= $organization->anket_parent_control_link ?></td>
                        </tr>
                    <?}?>
                <?}?>
        </tbody>
        </table>
<br><br><p></p>
        <script>

        </script>


    <?

    $script = <<< JS
$(function () {
  $('[data-toggle="tooltip"]').tooltip()
})
//$('#menus-parent_id').attr('disabled', 'true');
$( ".beforeload" ).click(function() {
  $(".beforeload").css('display','none');
  $(".load").css('display','block');
  
});

$(document).ready(function(){
	$('.content_toggle').click(function(){
		$('.content_block').slideToggle(300);      
		return false;
	});
});

/*$( ".beforeload" ).click(function() {
  $('.beforeload').append('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');
});*/

$("#pechat_itog_mun").click(function () {
    var table = $('#tableId');
    if (table && table.length) {
        var preserveColors = (table.hasClass('table2excel_with_colors') ? true : false);
        $(table).table2excel({
            exclude: ".noExl",
            name: "Excel Document Name",
            filename: "Отчет по контрольным мероприятиям(итог).xls",
            fileext: ".xls",
            exclude_img: true,
            exclude_links: true,
            exclude_inputs: true,
            preserveColors: preserveColors
        });
    }
});
$("#pechat_control").click(function () {
    var table = $('#table_control');
    if (table && table.length) {
        var preserveColors = (table.hasClass('table2excel_with_colors') ? true : false);
        $(table).table2excel({
            exclude: ".noExl",
            name: "Excel Document Name",
            filename: "Отчет по контрольным мероприятиям(итог).xls",
            fileext: ".xls",
            exclude_img: true,
            exclude_links: true,
            exclude_inputs: true,
            preserveColors: preserveColors
        });
    }
});



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
