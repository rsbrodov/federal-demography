<?php

use common\models\User;
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

$this->title = 'Отчет по операторам питания';
$this->params['breadcrumbs'][] = $this->title;

$model_stolovaya = new \common\models\CharactersStolovaya();
$region_id = Organization::findOne(Yii::$app->user->identity->organization_id)->region_id;

if (Yii::$app->user->can('minobr') || Yii::$app->user->can('rospotrebnadzor_nutrition'))
{
    $municipality_items = Yii::$app->territory->municipalities($region_id, true, false);
}
if (Yii::$app->user->can('subject_minobr'))
{
    $municipality_items = Yii::$app->territory->my_municipality();
}

if (!empty($post))
{
    $params_organization = ['class' => 'form-control', 'options' => [$post['organization_id'] => ['Selected' => true]]];
}

?>

<h1 class="text-center"><?= Html::encode($this->title) ?></h1>

<?php $form = ActiveForm::begin([]); ?>
<div class="container mb-5 mt-5">
    <div class="row">
        <div class="col-md-12">
            <? if (Yii::$app->user->can('minobr') || Yii::$app->user->can('rospotrebnadzor_nutrition')) { ?>
                <?= $form->field($model, 'municipality_id')->dropDownList($municipality_items, [
                    'class' => 'form-control text-center', 'options' => [$post['municipality_id'] => ['Selected' => true]],
                    'onchange' => '
                  $.get("../menus/orglist?id="+$(this).val(), function(data){
                    $("select#menus-organization_id").html(data);
                  });'
                ])->label('Муниципальный округ'); ?>
            <?}?>
            <? if (Yii::$app->user->can('subject_minobr')) { ?>
                <?= $form->field($model, 'municipality_id')->dropDownList($municipality_items, [
                    'class' => 'form-control text-center', 'options' => [$post['municipality_id'] => ['Selected' => true]],
                    'disabled' => 'disabled',
                    'onchange' => '
                  $.get("../menus/orglist?id="+$(this).val(), function(data){
                    $("select#menus-organization_id").html(data);
                  });'
                ])->label('Муниципальный округ'); ?>
            <?}?>
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
<? if ($post){//print_r($organizations);exit;
    foreach ($organizations as $key => $organization){
        if(!empty($organization->short_title)){
            $organizations_items[] = $organization->short_title;
            $table_items[$organization->id]['organization'] = $organization->short_title;
        }else{
            $organizations_items[] = $organization->title;
            $table_items[$organization->id]['organization'] = $organization->title;
        }
        $aplication = \common\models\NutritionApplications::find()->where(['sender_org_id' => $organization->id, 'status' => 1])->orWhere(['reciever_org_id' => $organization->id, 'status' => 1])->one();

        if(!empty($aplication)){

            if($aplication->sender_org_id != $organization->id){
                //print_r($aplication);
                $table_items[$organization->id]['operator'] = Organization::findOne($aplication->sender_org_id)->title;
            }
            if(!$aplication->reciever_org_id != $organization->id){
                $table_items[$organization->id]['operator'] = Organization::findOne($aplication->reciever_org_id)->title;
            }
        }else{
            $table_items[$organization->id]['operator'] = '-';
        }

    }

?>
    <div class="container">
        <table class="table_th0 table-responsive table-bordered">
            <tr class="">
                <th class="text-center">№</th>
                <th class="text-center">Наименование организации</th>
                <th class="text-center">Оператор питания</th>
            </tr>
            <?$number_row = 0; $check_zero = 0; foreach($table_items as $table_item){ $number_row++;?>
                <tr class="">
                    <td class="text-center"><?=$number_row?></td>
                    <td class=""><?=$table_item['organization']?></td>
                    <td class="text-center"><?=$table_item['operator']?></td>
                </tr>
            <?}?>
        </table>
        </div>
<? } ?>





<?
$script = <<< JS
//$('#menus-parent_id').attr('disabled', 'true');
$( ".beforeload" ).click(function() {
  $(".beforeload").css('display','none');
  $(".load").css('display','block');
  
});

JS;

$this->registerJs($script, yii\web\View::POS_READY);
?>
    <style>
        th, td {
            border: 1px solid black!important;
            color: black;
            font-size: 14px;

        }
        th {
            background-color: #ede8b9;
            font-size: 16px;
        }
        thead, th {
            background-color: #ede8b9;
            font-size: 16px;
        }
    </style>