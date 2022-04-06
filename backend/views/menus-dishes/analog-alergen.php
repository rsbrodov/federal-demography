<?php

use yii\bootstrap4\Html;
use yii\helpers\ArrayHelper;

?>
<div class="block ml-2 mt-3">
<?php foreach($alergens as $alergen)
{
    echo '<p class="mb-0"><b>Алерген:</b> ' . $alergen->name . '</p>';
}
    echo '<p class="mb-0"><b>Подобрать аналоги к блюду:</b> ' . $this_dishes->name .'</p>';
echo '<p class="mb-0"><b>Категория блюда:</b> ' . \common\models\DishesCategory::findOne($this_dishes->dishes_category_id)->name . '</p>';
    //echo '<p class="mb-0"><b>Аналоги блюд из сборника рецептур:</b> ' . $this_dishes->get_recipes_collection($this_dishes->recipes_collection_id)->name . '</p>';

?>
    <br>
<?php if(!empty($correct_dishes)){?>
    <table class="table_th0 table-responsive">
        <tr class="" style="font-size: 15px;">
            <th class="text-center">№</th>
            <th class="text-center">Сборник</th>
            <th class="text-center">Номер рецептуры</th>
            <th class="text-center">Блюдо</th>
            <th class="text-center">Заменить</th>
        </tr>
        <tbody style="font-size: 13px;">
        <? foreach ($correct_dishes as $key => $c_dish){?>
            <tr>
                <td><b><?=$key +1;?></b></td>
                <td><?= \common\models\RecipesCollection::findOne($c_dish->recipes_collection_id)->short_title?></td>
                <td class="text-center"><?= $c_dish->techmup_number?></td>
                <td style="font-size: 16px;"><?= $c_dish->name?></td>
                <td style="font-size: 16px;"><?= Html::a('Заменить', ['menus-dishes/change-analog?menus_dishes_id=' . $menus_dishes->id.'&dishes_id='.$c_dish->id], [
                        'class'=>'btn btn-sm main-button-edit'
                ])?></td>
            </tr>
        <?}?>

        </tbody>
    </table>
    <?php }else{echo '<p style="color:red" class="mb-0"><b>Аналогов блюд не найдено</b></p>';}?>
    </div>
<?

$script = <<< JS
JS;
$this->registerJs($script, yii\web\View::POS_READY);
?>