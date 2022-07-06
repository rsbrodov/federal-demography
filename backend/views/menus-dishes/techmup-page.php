<?php

use common\models\ProductsChange;
use common\models\ProductsChangeOrganization;
use kartik\select2\Select2;
use yii\bootstrap4\Html;
use yii\grid\GridView;
use yii\bootstrap4\ActiveForm;
use common\models\Menus;
use common\models\MenusDays;
use common\models\Days;
use common\models\RecipesCollection;
use yii\helpers\ArrayHelper;


/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Технологические карты';
$this->params['breadcrumbs'][] = $this->title;

$recipes = array(0 => 'Все ...');
$recipes_bd = ArrayHelper::map(RecipesCollection::find()->where(['organization_id' => [7, Yii::$app->user->identity->organization_id]])->orderBy(['name'=> SORT_ASC])->all(), 'id', 'name');
$recipes = ArrayHelper::merge($recipes,$recipes_bd);

$dishes_items = ArrayHelper::map(\common\models\Dishes::find()->orderBy(['recipes_collection_id' => SORT_ASC, 'name'=> SORT_ASC])->all(), 'id', 'name');


?>
<div class="menus-dishes-index">
    <h1 class="text-center"><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin(); ?>
    <div class="container mb-30">
        <div class="row">
            <div class="col-11 col-md-3">
                <?= $form->field($model, 'recipes_id')->dropDownList($recipes, [
                    'class' => 'form-control', 'options' => [$post['TechmupForm']['recipes_id'] => ['Selected' => true]],
                    'onchange' => '
                  $.get("../menus-dishes/disheslist?id="+$(this).val(), function(data){
                    //$("select#techmupform-dishes_id").html(data);
                    $("#w1").html(data);
                    document.getElementById("techmupform-dishes_id").disabled = false;
                  });'
                ]); ?>
            </div>

            <div class="col-11 col-md-3">
                <p class="mb-2"><b>Блюдо</b></p>
                <?= Select2::widget([
                     'name' => 'dishes_id',
                     'data' => $dishes_items,
                     'options' => [
                         'required' => true,
                         'placeholder' => 'Начните вводить блюдо',
                     ],
                ]); ?>
            </div>

            <div class="col-11 col-md-3">
                <?= $form->field($model, 'netto')->TextInput(['value' => $post['TechmupForm']['netto']]); ?>
            </div>

            <div class="col-11 col-md-3">
                <?= $form->field($model, 'count')->TextInput(['value' => $post['TechmupForm']['count']])->label('Количество питающихся'); ?>
            </div>
        </div>
        <div class="row">
            <div class="form-group" style="margin: 0 auto">
                <?= Html::submitButton('Посмотреть', ['class' => 'btn main-button-3 beforeload mb-3']) ?>
                <button class="btn main-button-3 load" type="button" disabled style="display: none">
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    Посмотреть...
                </button>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>


<? if($post){?>
<?
    echo '<div class="table p-3 container">';
    echo '<p class="mb-1 mt-3"><b>Технологическая карта кулинарного изделия (блюда):</b> '.$dishes->techmup_number.'</p>';
    echo '<p class="mb-1"><b>Наименование изделия:</b> '.$dishes->name.'</p>';
    echo '<p class="mb-1"><b>Номер рецептуры:</b> '.$dishes->techmup_number.'</p>';
    echo '<p class="mb-3" style="max-width: 1200px;"><b>Наименование сборника рецептур, год выпуска, автор:</b> '.$dishes->get_recipes($dishes->recipes_collection_id)->name.', '. $dishes->get_recipes($dishes->recipes_collection_id)->year.' </p>';
    ?>
    <b>Пищевые вещества:</b><br>
    <table class="table_th0 table-responsive">
        <tr class="">
            <th class="text-center">№</th>
            <th class="text-center">Наименование сырья</th>
            <th class="text-center">Брутто, г.</th>
            <th class="text-center">Нетто, г.</th>
            <th class="text-center">Белки, г.</th>
            <th class="text-center">Жиры, г.</th>
            <th class="text-center">Углеводы, г.</th>
            <th class="text-center">Энергетическая ценность, ккал.</th>
        </tr>
        <?php $menus_dishes_model = new \common\models\MenusDishes();  $super_total_yield = 0; $super_total_protein = 0; $super_total_fat = 0; $super_total_carbohydrates_total = 0; $super_total_energy_kkal = 0; $super_total_vitamin_a = 0; $super_total_vitamin_c = 0; $super_total_vitamin_b1 = 0; $super_total_vitamin_b2 = 0; $super_total_vitamin_d = 0; $super_total_vitamin_pp = 0; $super_total_na = 0; $super_total_k = 0; $super_total_ca = 0; $super_total_f = 0; $super_total_se = 0; $super_total_i = 0; $super_total_fe = 0; $super_total_p = 0; $super_total_mg = 0; $number_row = 1;?>
        <? foreach ($dishes_products as $d_product){

            $koef_change = 1;//коэффициент замены, если нет - 1, если есть смотрим из базы.ex молоко сухое/молоко обыч 1/5
            $products_change = ProductsChangeOrganization::find()->where(['organization_id' => Yii::$app->user->identity->organization_id, 'products_id' => $d_product->products_id])->one();
            if(!empty($products_change)){
                $koef_change = ProductsChange::find()->where(['products_id' => $products_change->products_id, 'change_products_id' => $products_change->change_products_id])->one()->value;
            }

            ?>
            <tr>
                <td class="text-center"><?=$number_row?></td>
                <td class="text-center"><?= $d_product->get_products($d_product->products_id)->name?></td>
                <td class="text-center"><?= round($d_product->gross_weight * $indicator * $post['TechmupForm']['count']*$koef_change, 1) ?></td>
                <td class="text-center"><?= round($d_product->net_weight * $indicator * $post['TechmupForm']['count']*$koef_change, 1)?></td>
                <td class="text-center"><? $protein = round($menus_dishes_model->get_products_bju($d_product->products_id, $d_product->dishes_id, 'protein') * (($d_product->net_weight/100) *($post['TechmupForm']['netto'] / $dishes->yield)) * $post['TechmupForm']['count'], 2); echo $protein; $super_total_protein = $super_total_protein + $protein; ?></td>
                <td class="text-center"><? $fat = round($menus_dishes_model->get_products_bju($d_product->products_id, $d_product->dishes_id, 'fat') * (($d_product->net_weight/100) *($post['TechmupForm']['netto'] / $dishes->yield)) * $post['TechmupForm']['count'], 2); echo $fat; $super_total_fat = $super_total_fat + $fat;?></td>
                <td class="text-center"><? $carbohydrates_total = round($menus_dishes_model->get_products_bju($d_product->products_id, $d_product->dishes_id, 'carbohydrates_total') * (($d_product->net_weight/100) *($post['TechmupForm']['netto'] / $dishes->yield)) * $post['count'], 2); echo $carbohydrates_total; $super_total_carbohydrates_total = $super_total_carbohydrates_total + $carbohydrates_total;?></td>
                <td class="text-center"><? $energy_kkal = round($menus_dishes_model->get_kkal($d_product->products_id, $d_product->dishes_id) * (($d_product->net_weight/100) *($post['TechmupForm']['netto'] / $dishes->yield)) * $post['TechmupForm']['count'], 2); echo $energy_kkal; $super_total_energy_kkal = $super_total_energy_kkal + $energy_kkal;?></td>
            </tr>
            <?$number_row++;?>
        <?}?>
        <tr>
            <td colspan="3"><b>Выход:</b></td>
            <td class="text-center"><b><?= $post['TechmupForm']['netto'] * $post['TechmupForm']['count']?></b></td>
            <td class="text-center"><b><?= $super_total_protein; ?></b></td>
            <td class="text-center"><b><?= $super_total_fat; ?></b></td>
            <td class="text-center"><b><?= $super_total_carbohydrates_total; ?></b></td>
            <td class="text-center"><b><?= $super_total_energy_kkal; ?></b></td>
        </tr>
    </table>


    <? echo '<p class="mb-1 mt-3"><b>Способ обработки:</b> '.$dishes->get_culinary_processing($dishes->culinary_processing_id).'</p>';?>
    <? echo '<p class="mb-2" style="max-width: 1200px;"><b>Технология приготовления:</b> '.$dishes->description.'</p>';?>

    <b>Характеристика блюда на выходе:</b>
    <? echo '<p class="mb-3" style="max-width: 1200px;">'.$dishes->dishes_characters.'</p>';?>
<div class="text-center mt-5">
    <?/*= Html::button('<span class="glyphicon glyphicon-download"></span> Скачать в PDF технологическую карту', [
        'title' => Yii::t('yii', 'Скачать в PDF технологическую карту'),
        'data-toggle'=>'tooltip',
        'class'=>'btn btn-secondary',
    ]);*/?>
</div>
<?php echo '</div>'; ?>
<? } ?>
<?
$script = <<< JS
//$('#techmupform-dishes_id').attr('disabled', 'true');
JS;
$this->registerJs($script, yii\web\View::POS_READY);
?>