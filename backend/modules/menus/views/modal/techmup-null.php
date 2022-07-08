<?php

use common\models\Products;
use common\models\ProductsChange;
use common\models\ProductsChangeOrganization;
use yii\bootstrap4\Html;
use yii\helpers\ArrayHelper;

echo '<div class="table-success p-3">';
echo '<p class="mb-0"><b>Технологическая карта кулинарного изделия (блюда):</b> '.$dishes->techmup_number.'</p>';
echo '<p class="mb-0"><b>Наименование изделия:</b> '.$dishes->name.'</p>';
echo '<p class="mb-0"><b>Номер рецептуры:</b> '.$dishes->techmup_number.'</p>';
echo '<p class="mb-0"><b>Наименование сборника рецептур, год выпуска, автор:</b> '.$dishes->get_recipes_collection($dishes->recipes_collection_id)->name.', '. $dishes->get_recipes_collection($dishes->recipes_collection_id)->year.' </p>';
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
    <?php $menus_dishes_model = new \common\models\MenusDishes(); $super_total_yield = 0; $super_total_protein = 0; $super_total_fat = 0; $super_total_carbohydrates_total = 0; $super_total_energy_kkal = 0; $super_total_vitamin_a = 0; $super_total_vitamin_c = 0; $super_total_vitamin_b1 = 0; $super_total_vitamin_b2 = 0; $super_total_vitamin_d = 0; $super_total_vitamin_pp = 0; $super_total_na = 0; $super_total_k = 0; $super_total_ca = 0; $super_total_f = 0; $super_total_se = 0; $super_total_i = 0; $super_total_fe = 0; $super_total_p = 0; $super_total_mg = 0; $number_row = 1;?>
    <? foreach ($dishes_products as $d_product){?>

<!--        $brutto_netto_products в нем будут лежать нормативы по брутто нужных продуктов. Чтобы картоха морковь свекла вывелись по количеству раз равных количеству нормативов брутто-->
<!--        Вся процедура начнется если в выпадающем списке указано вариация брутто-->
        <?$brutto_netto_products=[];?>
        <?if(($d_product->products_id == 14 || $d_product->products_id == 142 || $d_product->products_id == 152)){
            $brutto_netto_products = \common\models\BruttoNettoKoef::find()->where(['products_id' => $d_product->products_id])->all();
        }else{
            $brutto_netto_products[] = 1;
        }?>
    <?$count_oborot = 0; foreach($brutto_netto_products as $brutto_netto_product){ $count_oborot++;?>
        <?if(!($d_product->products_id == 14 || $d_product->products_id == 142 || $d_product->products_id == 152)){
                $koef_change = 1;//коэффициент замены, если нет - 1, если есть смотрим из базы.ex молоко сухое/молоко обыч 1/5
                $products_change = ProductsChangeOrganization::find()->where(['organization_id' => Yii::$app->user->identity->organization_id, 'products_id' => $d_product->products_id])->one();
                if(!empty($products_change)){
                    $koef_change = ProductsChange::find()->where(['products_id' => $products_change->products_id, 'change_products_id' => $products_change->change_products_id])->one()->value;
                }

            ?>
            <tr>
                <td class="text-center"><?=$number_row?></td>
<!--                ПОИСК ЗАМЕНЫ. ЕСЛИ МЕНЮ ПОЛУЧЕНО ОТ ОПЕРАТОРА ПИТАНИЯ, ТО НУЖНО УЧЕСТЬ ЗАМЕНЫ ОПЕРАТОРА!!!!.*/-->
                <?$menus_send = \common\models\MenusSend::find()->where(['reciever_menu_id' => \common\models\MenusDishes::findOne($id)->menu_id])->one();
                if(!empty($menus_send)){
                $products_change_operator = ProductsChangeOrganization::find()->where(['organization_id' => $menus_send->sender_org_id, 'products_id' =>$d_product->products_id])->one();
                if(!empty($products_change_operator)){
                $p_name = $products_change_operator->change_products_id;
                }
                }?>
<!--                КОНЕЦ ЗАМЕНЫ-->
                <td class=""><?if(!empty($p_name)){echo $d_product->get_products($p_name)->name;$p_name ='';}else{echo $d_product->get_products($d_product->products_id)->name;}?></td>
                <?if($d_product->products_id == 213 || $d_product->products_id == 214 || $d_product->products_id == 29 || $d_product->products_id == 218 || $d_product->products_id == 8 || $d_product->products_id == 4 || $d_product->products_id == 9 || $d_product->products_id == 10 || $d_product->products_id == 11 || $d_product->products_id == 240 || $d_product->products_id == 242 || \common\models\Products::findOne($d_product->products_id)->products_category_id == 29){?>
                    <td class="text-center"><?= sprintf("%.2f" ,$d_product->gross_weight * $indicator*$koef_change)?></td>
                    <td class="text-center"><?= sprintf("%.2f" ,$d_product->net_weight * $indicator*$koef_change)?></td>
                <?}else{?>
                    <td class="text-center"><?= sprintf("%.1f" ,$d_product->gross_weight * $indicator*$koef_change)?></td>
                    <td class="text-center"><?= sprintf("%.1f" ,$d_product->net_weight * $indicator*$koef_change)?></td>
                <?}?>
                <!--РАНЬШЕ БЖУ ПОЛУЧАЛИ ИЗ ФУНКЦИИ get_products(), ТЕПЕРЬ ИЗ get_products_bju() В ЭТОЙ ФУНКЦИИ ИДЕТ РАСЧЕТ С УЧЕТОМ КОЭФ-В ПОТЕРЬ-->
                <td class="text-center"><? $protein = sprintf("%.2f" ,$menus_dishes_model->get_products_bju_techmup($d_product->products_id, $id, 'protein') * (($d_product->net_weight)/100) * $indicator); echo $protein; $super_total_protein = $super_total_protein + $protein; ?></td>
                <td class="text-center"><? $fat = sprintf("%.2f" ,$menus_dishes_model->get_products_bju_techmup($d_product->products_id, $id, 'fat') * (($d_product->net_weight)/100) * $indicator); echo $fat; $super_total_fat = $super_total_fat + $fat; ?></td>
                <td class="text-center"><? $carbohydrates_total = sprintf("%.2f" ,$menus_dishes_model->get_products_bju_techmup($d_product->products_id, $id, 'carbohydrates_total') * (($d_product->net_weight)/100) * $indicator); echo $carbohydrates_total; $super_total_carbohydrates_total = $super_total_carbohydrates_total + $carbohydrates_total; ?></td>
                <td class="text-center"><? $energy_kkal = sprintf("%.1f" ,$menus_dishes_model->get_kkal_techmup($d_product->products_id, $id) * (($d_product->net_weight)/100) * $indicator); echo $energy_kkal; $super_total_energy_kkal = $super_total_energy_kkal + $energy_kkal;?></td>
            </tr>
        <?}else{?>
            <tr>
                <td class="text-center"><?=$number_row?></td>
                <!--КАРТОХА-->
                <?if($brutto_netto_product->products_id == 14 && $brutto_netto_product->season == 2){?>
                    <td style="min-width: 200px;" class="align-middle"><?= $d_product->get_products($d_product->products_id)->name?><small class="text-primary"> 01.09-31.10</small></td>
                <?}elseif($brutto_netto_product->products_id == 14 && $brutto_netto_product->season == 3){?>
                    <td style="min-width: 200px;" class="align-middle"><?= $d_product->get_products($d_product->products_id)->name?><small class="text-primary"> 31.10-31.12</small></td>

                <?}elseif($brutto_netto_product->products_id == 14 && $brutto_netto_product->season == 4){?>
                    <td style="min-width: 200px;" class="align-middle"><?= $d_product->get_products($d_product->products_id)->name?><small class="text-primary"> 31.12-28.02</small></td>
                <?}elseif($brutto_netto_product->products_id == 14 && $brutto_netto_product->season == 1){?>
                <td style="min-width: 200px;" class="align-middle"><?= $d_product->get_products($d_product->products_id)->name?><small class="text-primary"> 29.02-01.09</small></td>

                <!--МОРКОВЬ-->
                <?}elseif($brutto_netto_product->products_id == 142 && $brutto_netto_product->season == 1){?>
                <td style="min-width: 200px;" class="align-middle"><?=$d_product->get_products($d_product->products_id)->name?><small class="text-primary"> 01.09-31.12</small></td>
                <?}elseif($brutto_netto_product->products_id == 142 && $brutto_netto_product->season == 3){?>
                <td style="min-width: 200px;" class="align-middle"><?=$d_product->get_products($d_product->products_id)->name?><small class="text-primary"> 01.01-31.08</small></td>

                <!--СВЕКЛУХА-->
                <?}elseif($brutto_netto_product->products_id == 152 && $brutto_netto_product->season == 1){?>
                <td style="min-width: 200px;" class="align-middle"><?=$d_product->get_products($d_product->products_id)->name?><small class="text-primary"> 01.09-31.12</small></td>
                <?}elseif($brutto_netto_product->products_id == 152 && $brutto_netto_product->season == 3){?>
                <td style="min-width: 200px;" class="align-middle"><?=$d_product->get_products($d_product->products_id)->name?><small class="text-primary"> 01.01-31.08</small></td>
                <?}?>

                <td class="text-center"><?= sprintf("%.1f" ,$d_product->net_weight * $indicator*$brutto_netto_product->koeff_netto)?></td>
                <?if($count_oborot <=1){?>
                    <td class="text-center"><?= sprintf("%.1f" ,$d_product->net_weight * $indicator)?></td>
                    <td class="text-center"><? $protein = sprintf("%.2f" ,$d_product->get_products_bju($d_product->products_id, $id, 'protein') * (($d_product->net_weight)/100) * $indicator); echo $protein; $super_total_protein = $super_total_protein + $protein; ?></td>
                    <td class="text-center"><? $fat = sprintf("%.2f" ,$d_product->get_products_bju($d_product->products_id, $id, 'fat') * (($d_product->net_weight)/100) * $indicator); echo $fat; $super_total_fat = $super_total_fat + $fat; ?></td>
                    <td class="text-center"><? $carbohydrates_total = sprintf("%.2f" ,$d_product->get_products_bju($d_product->products_id, $id, 'carbohydrates_total') * (($d_product->net_weight)/100) * $indicator); echo $carbohydrates_total; $super_total_carbohydrates_total = $super_total_carbohydrates_total + $carbohydrates_total; ?></td>
                    <td class="text-center"><? $energy_kkal = sprintf("%.1f" ,$d_product->get_kkal($d_product->products_id, $id) * (($d_product->net_weight)/100) * $indicator); echo $energy_kkal; $super_total_energy_kkal = $super_total_energy_kkal + $energy_kkal;?></td>
                <?}else{?>
                    <td class="text-center"></td>
                    <td class="text-center"></td>
                    <td class="text-center"></td>
                    <td class="text-center"></td>
                    <td class="text-center"></td>
                <?}?>
            </tr>
        <?}?>
        <?}?>
        <?$number_row++;?>
    <?}?>
    <tr>
        <td colspan="3"><b>Выход:</b></td>
        <td class="text-center"><b><?= round(($dishes->yield*$indicator),1)?></b></td>
        <td class="text-center"><b><?= $super_total_protein; ?></b></td>
        <td class="text-center"><b><?= $super_total_fat; ?></b></td>
        <td class="text-center"><b><?= $super_total_carbohydrates_total; ?></b></td>
        <td class="text-center"><b><?= $super_total_energy_kkal; ?></b></td>
    </tr>
</table>

    <b>Витамины и минеральные вещества</b>
    <table class="table_th0 table-responsive">
        <tr class="">
            <th class="text-center">№</th>
            <th class="text-center">Продукт</th>
            <th class="text-center">B1, мг</th>
            <th class="text-center">B2, мг</th>
            <th class="text-center">А, мкг. рет. экв.</th>
            <th class="text-center">D, мкг.</th>
            <th class="text-center">C, мг.</th>
            <th class="text-center">Na, мг.</th>
            <th class="text-center">K, мг.</th>
            <th class="text-center">Ca, мг.</th>
            <th class="text-center">Mg, мг.</th>
            <th class="text-center">P, мг.</th>
            <th class="text-center">Fe, мг.</th>
            <th class="text-center">I, мкг.</th>
            <th class="text-center">Se, мкг.</th>
            <th class="text-center">F, мг.</th>
        </tr>
        <?$number_row=1;?>
        <? foreach ($dishes_products as $d_product){?>
            <tr>
                <td class="text-center"><?=$number_row?></td>

                <td class=""><?if(!empty($p_name)){echo $d_product->get_products($p_name)->name;$p_name ='';}else{echo $d_product->get_products($d_product->products_id)->name;}?></td>
                <td class="text-center"><? $vitamin_b1 = sprintf("%.2f" ,$d_product->get_vitamin($d_product->products_id, $id,  'vitamin_b1') * (($d_product->net_weight)/100) * $indicator); echo $vitamin_b1; $super_total_vitamin_b1 = $super_total_vitamin_b1 + $vitamin_b1; ?></td>
                <td class="text-center"><? $vitamin_b2 = sprintf("%.2f" ,$d_product->get_vitamin($d_product->products_id, $id,  'vitamin_b2') * (($d_product->net_weight)/100) * $indicator); echo $vitamin_b2; $super_total_vitamin_b2 = $super_total_vitamin_b2 + $vitamin_b2; ?></td>
                <td class="text-center"><? $vitamin_a = sprintf("%.2f" ,$d_product->get_vitamin($d_product->products_id, $id,  'vitamin_a') * (($d_product->net_weight)/100) * $indicator); echo $vitamin_a; $super_total_vitamin_a = $super_total_vitamin_a + $vitamin_a; ?></td>
                <td class="text-center"><? $vitamin_d = sprintf("%.2f" ,$d_product->get_vitamin($d_product->products_id, $id,  'vitamin_d') * (($d_product->net_weight)/100) * $indicator); echo $vitamin_d; $super_total_vitamin_d = $super_total_vitamin_d + $vitamin_d; ?></td>
                <td class="text-center"><? $vitamin_c = sprintf("%.2f" ,$d_product->get_vitamin($d_product->products_id, $id, 'vitamin_c') * (($d_product->net_weight)/100) * $indicator); echo $vitamin_c; $super_total_vitamin_c = $super_total_vitamin_c + $vitamin_c; ?></td>
                <td class="text-center"><? $na = sprintf("%.2f" ,$d_product->get_vitamin($d_product->products_id, $id,  'na') * (($d_product->net_weight)/100) * $indicator); echo $na; $super_total_na = $super_total_na + $na; ?></td>
                <td class="text-center"><? $k = sprintf("%.2f" ,$d_product->get_vitamin($d_product->products_id, $id,  'k') * (($d_product->net_weight)/100) * $indicator); echo round($k, 2); $super_total_k = $super_total_k + $k; ?></td>
                <td class="text-center"><? $ca = sprintf("%.2f" ,$d_product->get_vitamin($d_product->products_id, $id,  'ca') * (($d_product->net_weight)/100) * $indicator); echo $ca; $super_total_ca = $super_total_ca + $ca; ?></td>
                <td class="text-center"><? $mg = sprintf("%.2f" ,$d_product->get_vitamin($d_product->products_id, $id,  'mg') * (($d_product->net_weight)/100) * $indicator); echo $mg; $super_total_mg = $super_total_mg + $mg; ?></td>
                <td class="text-center"><? $p = sprintf("%.2f" ,$d_product->get_vitamin($d_product->products_id, $id,  'p') * (($d_product->net_weight)/100) * $indicator); echo $p; $super_total_p = $super_total_p + $p; ?></td>
                <td class="text-center"><? $fe = sprintf("%.2f" ,$d_product->get_vitamin($d_product->products_id, $id,  'fe') * (($d_product->net_weight)/100) * $indicator); echo $fe; $super_total_fe = $super_total_fe + $fe; ?></td>
                <td class="text-center"><? $i = sprintf("%.1f" ,$d_product->get_vitamin($d_product->products_id, $id,  'i') * (($d_product->net_weight)/100) * $indicator); echo $i; $super_total_i = $super_total_i + $i; ?></td>
                <td class="text-center"><? $se = sprintf("%.1f" ,$d_product->get_vitamin($d_product->products_id, $id,  'se') * (($d_product->net_weight)/100) * $indicator); echo $se; $super_total_se = $super_total_se + $se; ?></td>
                <td class="text-center"><? $f = sprintf("%.1f" ,$d_product->get_vitamin($d_product->products_id, $id,  'f') * (($d_product->net_weight)/100) * $indicator); echo $f; $super_total_f = $super_total_f + $f; ?></td>
            </tr>
            <?$number_row++;?>
        <?}?>
        <tr>
            <td colspan="2"><b>Итого</b></td>
            <td class="text-center"><b><?= $super_total_vitamin_b1;?></b></td>
            <td class="text-center"><b><?= $super_total_vitamin_b2;?></b></td>
            <td class="text-center"><b><?= $super_total_vitamin_a;?></b></td>
            <td class="text-center"><b><?= $super_total_vitamin_d;?></b></td>
            <td class="text-center"><b><?= $super_total_vitamin_c;?></b></td>
            <td class="text-center"><b><?= $super_total_na;?></b></td>
            <td class="text-center"><b><?= round($super_total_k, 2);?></b></td>
            <td class="text-center"><b><?= $super_total_ca;?></b></td>
            <td class="text-center"><b><?= $super_total_mg;?></b></td>
            <td class="text-center"><b><?= $super_total_p;?></b></td>
            <td class="text-center"><b><?= $super_total_fe;?></b></td>
            <td class="text-center"><b><?= $super_total_i;?></b></td>
            <td class="text-center"><b><?= $super_total_se;?></b></td>
            <td class="text-center"><b><?= $super_total_f;?></b></td>
        </tr>
    </table>
    <? echo '<p class="mb-0"><b>Способ обработки:</b> '.$dishes->get_culinary_processing($dishes->culinary_processing_id).'</p>';?>
<? echo '<p class="mb-0"><b>Технология приготовления:</b> '.$dishes->description.'</p>';?>


    <b>Характеристика блюда на выходе:</b>
    <? echo '<p class="mb-0">'.$dishes->dishes_characters.'</p>';?>


<?= Html::a('<span class="glyphicon glyphicon-download"></span> Скачать в PDF технологическую карту', ['techmup-export-null?dishes_id=' . $id.'&indicator='.$indicator],
    [
        'class'=>'btn btn-white',
        'title' => Yii::t('yii', 'Вы можете скачать технологическую карту в PDF формате'),
        'data-toggle'=>'tooltip',
    ])
?>
<?/*= Html::a('<span class="glyphicon glyphicon-download"></span> Скачать в Ексел', ['export-excel4?id=' . $id.'&indicator='.$indicator],
    [
        'class'=>'btn btn-white',
        'title' => Yii::t('yii', 'Вы можете скачать технологическую карту в PDF формате'),
        'data-toggle'=>'tooltip',
    ])*/
?>
<?php echo '</div>'; ?>
<?
$script = <<< JS
JS;
$this->registerJs($script, yii\web\View::POS_READY);
?>