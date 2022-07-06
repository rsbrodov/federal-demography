<?php

use common\models\ProductsChangeOrganization;
use yii\bootstrap4\Html;
use yii\grid\GridView;
use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Button;
use common\models\Menus;
use common\models\Days;
use common\models\MenusDays;
use common\models\MenusDishes;
use common\models\MenusNutrition;
use common\models\NutritionInfo;
use yii\helpers\ArrayHelper;


/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Меню за период';
$this->params['breadcrumbs'][] = $this->title;
$fields = [];
$fields['vitamin_b1'] = 'vitamin_b1';$fields['vitamin_b2'] = 'vitamin_b2';$fields['vitamin_a'] = 'vitamin_a';$fields['vitamin_d'] = 'vitamin_d';$fields['vitamin_c'] = 'vitamin_c';

$fields['na'] = 'na';$fields['k'] = 'k';$fields['ca'] = 'ca';$fields['mg'] = 'mg';$fields['p'] = 'p';
$fields['fe'] = 'fe';$fields['i'] = 'i';$fields['se'] = 'se';$fields['f'] = 'f';

$my_menus = Menus::find()->where(['organization_id' => Yii::$app->user->identity->organization_id, 'status_archive' => 0])->all();
$my_menus_items = ArrayHelper::map($my_menus, 'id', 'name');
$first_menu = Menus::find()->where(['organization_id' => Yii::$app->user->identity->organization_id, 'status_archive' => 0])->one();

if(Yii::$app->user->can('rospotrebnadzor_camp') || Yii::$app->user->can('rospotrebnadzor_nutrition') || Yii::$app->user->can('subject_minobr') || Yii::$app->user->can('minobr')) {
    if (!empty(Yii::$app->session['organization_id'])) {
        $my_menus = Menus::find()->where(['organization_id' => Yii::$app->session['organization_id'], 'status_archive' => 0])->all();
        $my_menus_items = ArrayHelper::map($my_menus, 'id', 'name');
        $first_menu = Menus::find()->where(['organization_id' => Yii::$app->session['organization_id'], 'status_archive' => 0])->one();
    }
    else {
        $my_menus = Menus::find()->where(['organization_id' => Yii::$app->user->identity->organization_id, 'status_archive' => 0])->all();
        $my_menus_items = ArrayHelper::map($my_menus, 'id', 'name');
        $first_menu = Menus::find()->where(['organization_id' => Yii::$app->user->identity->organization_id, 'status_archive' => 0])->one();
    }
}

$menu_cycle_count = $first_menu->cycle;
$menu_cycle = [];
$menu_cycle[0] = 'Показать за все недели';
for($i=1;$i<=$menu_cycle_count;$i++){
    $menu_cycle[$i] = $i;//массив из подходящи циклов
}

$chemistry_items = [0 => 'Скрыть химический состав', 1 => 'Показать химический состав'];
$params_menu = ['class' => 'form-control', 'options' => [$first_menu->id => ['Selected' => true]]];
$params_cycle = ['class' => 'form-control', 'options' => [0 => ['Selected' => true]]];
$params_chemistry = ['class' => 'form-control', 'options' => [0 => ['Selected' => true]]];
if(!empty($post)){
    $my_menus = Menus::findOne($post['menu_id']);
    $menu_cycle_count = $my_menus->cycle;
    $menu_cycle = [];
    $menu_cycle[0] = 'Показать за все недели';
    for($i=1;$i<=$menu_cycle_count;$i++){
        $menu_cycle[$i] = $i;//массив из подходящи циклов
    }
    $my_days = MenusDays::find()->where(['menu_id' => $post['menu_id']])->all();
    foreach($my_days as $m_day){
        $ids[] = $m_day->days_id;
    }

    $chemistry_items = [0 => 'Скрыть химический состав', 1 => 'Показать химический состав'];
    $params_menu = ['class' => 'form-control', 'options' => [$post['menu_id'] => ['Selected' => true]]];
    $params_cycle = ['class' => 'form-control', 'options' => [$post['cycle'] => ['Selected' => true]]];
    $params_chemistry = ['class' => 'form-control', 'options' => [$post['days_id'] => ['Selected' => true]]];

    $count_my_days = MenusDays::find()->where(['menu_id' => $post['menu_id']])->count();
    if($post['cycle'] == 0){
        $count_my_days = $count_my_days * $menu_cycle_count;
    }
    $nutritions_count = count($nutritions);
    $normativ_vitamin_day_vitamin_a = \common\models\NormativVitaminDay::find()->where(['name' => 'vitamin_a', 'age_info_id' => $my_menus->age_info_id])->one()->value;
    $normativ_vitamin_day_k = \common\models\NormativVitaminDay::find()->where(['name' => 'k', 'age_info_id' => $my_menus->age_info_id])->one()->value;

    $variat = [];

}

?>

    <h1 class="text-center"><?= Html::encode($this->title) ?></h1>

    <?if(empty($my_menus) && (!(Yii::$app->user->can('rospotrebnadzor_camp') || Yii::$app->user->can('rospotrebnadzor_nutrition') || Yii::$app->user->can('subject_minobr') || Yii::$app->user->can('minobr')))){?>
        <p class="text-center" style="color: red"><b>У Вас не созданы меню и не добавлены блюда.(Перейдите в раздел "Архив меню" или "Настройка меню")</b></p>
    <?}?>

    <?php $form = ActiveForm::begin([]); ?>
    <div class="container mb-30">
        <div class="row">
            <div class="col-11 col-md-4">
                <?= $form->field($model, 'menu_id')->dropDownList($my_menus_items, [
                    'class' => 'form-control', 'options' => [$post['menu_id'] => ['Selected' => true]],
                    'onchange' => '
                  $.get("../menus-dishes/cyclelist?id="+$(this).val(), function(data){
                    $("select#menusdishes-cycle").html(data);
                  });
                                    //ДЛЯ ЗАПОЛНЕНИЯ ИНПУТОВ: ВОЗРВСТ КАТЕГОРИЯ СРОКИ

                  $.get("../menus-dishes/insertcharacters?id="+$(this).val(), function(data){
                  console.log(data);
                    $("input#characters").val(data);
                  });
                  $.get("../menus-dishes/insertage?id="+$(this).val(), function(data){
                  console.log(data);
                    $("input#age").val(data);
                  });
                  $.get("../menus-dishes/insertsrok?id="+$(this).val(), function(data){
                  console.log(data);
                    $("#insert-srok").val(data);
                  });'
                ]); ?>
            </div>

            <div class="col-11 col-md-4">
                <?= $form->field($model, 'cycle')->dropDownList($menu_cycle, $params_cycle) ?>
            </div>

            <div class="col-11 col-md-4">
                <?= $form->field($model, 'days_id')->dropDownList($chemistry_items, $params_chemistry)->label('Химический состав'); ?>
            </div>
        </div>

        <!--        Блок с заполняемыми инпутами для информации. id не менять иначе не сработает-->
        <?if(empty($post)){$menu_id = $first_menu->id;} else{$menu_id = $post['menu_id'];}?>
        <div class="row">
            <div class="col">
                <label><b>Характеристика питающихся</b>
                    <input type="text" class="form-control" id="characters" disabled value="<?= $model->insert_info($menu_id, 'feeders_characters');?>"></label>
            </div>
            <div class="col">
                <label><b>Возрастная категория</b>
                    <input type="text" class="form-control" id="age" disabled value="<?=$model->insert_info($menu_id, 'age_info');?>"></label>
            </div>
            <div class="col">
                <label><b>Срок действия меню</b>
                    <input type="text" class="form-control" id="insert-srok" disabled value="<?=$model->insert_info($menu_id, 'sroki');?>"></label>
            </div>
        </div>
        <!--        Конец блока с заполнением-->

        <div class="row">
            <div class="form-group" style="margin: 0 auto">
                <?= Html::submitButton('Посмотреть', ['name'=>'identificator', 'value' => 'view', 'class' => 'btn main-button-3 beforeload']) ?>
                <button class="btn main-button-3 load" type="button" disabled style="display: none">
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    Посмотреть...
                </button>
            </div>
        </div>
        <div class="row pt-1">
            <div class="form-group" style="margin: 0 auto">
                <?= Html::submitButton('Показать блюда за период (за все недели)', ['name'=>'identificator', 'value' => 'show', 'class' => 'btn main-button-3']) ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
<?php $cycle_ids = [];
if($post['cycle'] != 0){
$cycle_ids[$post['cycle']] = $post['cycle'];
}
else{
    for($i=1;$i<=$menu_cycle_count;$i++){
        $cycle_ids[$i] = $i;//массив из подходящи циклов
    }
}

?>
<div class="row justify-content-center">

    <div class="col-auto">
<?if($identificator == 'view'){?>

<!--МАССИВ data[] ХРАНИТ В СЕБЕ СРЕДНИЕ ПОКАЗАТЕЛИ ПО ПИТАНИЮ СМ.НИЖЕ-->


<?php $data = [];?>
    <?if(!empty($days)){?>
        <?$products_change = ProductsChangeOrganization::find()->where(['organization_id' => Yii::$app->user->identity->organization_id])->all();
        if(!empty($products_change)){?>
            <p class="text-center mt-3" style="font-size: 18px;"><b>Значения БЖУ, витаминов и минеральных веществ учтены при замене выбранных продуктов:</b></p>
            <?foreach ($products_change as $prod_ch){?>
                <p class="text-center"><i><?=\common\models\Products::findOne($prod_ch->products_id)->name.'</i><b> → </b><i>'.\common\models\Products::findOne($prod_ch->change_products_id)->name?></i></p>
            <?}?>
        <?}?>
        <?$menus_send = \common\models\MenusSend::find()->where(['reciever_menu_id' => $post['menu_id']])->one();
        if(!empty($menus_send)){
            $products_change_operator = ProductsChangeOrganization::find()->where(['organization_id' => $menus_send->sender_org_id])->all();
            if(!empty($products_change_operator)){?>
                <p class="text-center mt-3" style="font-size: 18px;"><b>Это меню было получено от оператора питания. Значения БЖУ, витаминов и минеральных веществ учтены при замене выбранных продуктов:</b></p>
                <?foreach ($products_change_operator as $prod_ch){?>
                    <p class="text-center"><i><?=\common\models\Products::findOne($prod_ch->products_id)->name.'</i><b> → </b><i>'.\common\models\Products::findOne($prod_ch->change_products_id)->name?></i></p>
                <?}?>
            <?}?>
        <?}?>

        <?if($my_menus->variativity == 1){?>
            <p class="text-center mt-3" style="font-size: 18px;"><b>Данное меню является вариативным</b></p>
        <?}?>

        <?php $count_cycle = 0;?>
        <?php foreach($cycle_ids as $cycle_id){ $count++;
            echo '<b><p class="mb-0 text-center" style="font-size: 20px; font-weight: 500;">Неделя '. $cycle_id .'</p></b>'
        ?>
<? foreach($days as $day){?>
<? echo '<b><p class="mb-0" style="font-size: 20px; font-weight: 500;">'. $day->name .'</p></b>'?>
<?php $super_total_yield = 0; $super_total_protein = 0; $super_total_fat = 0; $super_total_carbohydrates_total = 0; $super_total_energy_kkal = 0; $super_total_vitamin_a = 0; $super_total_vitamin_c = 0; $super_total_vitamin_b1 = 0; $super_total_vitamin_b2 = 0; $super_total_vitamin_d = 0; $super_total_vitamin_pp = 0; $super_total_na = 0; $super_total_k = 0; $super_total_ca = 0; $super_total_f = 0; $super_total_se = 0; $super_total_p = 0; $super_total_i = 0; $super_total_mg = 0;?>

    <? foreach($nutritions as $nutrition){?>
        <?if($my_menus->variativity == 1){
            $variat = [];
            $dishes_nutrition = MenusDishes::find()->where(['date_fact_menu' => 0, 'menu_id' => $my_menus->id, 'cycle' => $cycle_id, 'days_id' => $day->id, 'nutrition_id' => $nutrition->id])->all();
            $dishes_nutrition_c = MenusDishes::find()->where(['date_fact_menu' => 0, 'menu_id' => $my_menus->id, 'cycle' => $cycle_id, 'days_id' => $day->id, 'nutrition_id' => $nutrition->id])->count();
            foreach ($dishes_nutrition as $d){
                $dish = \common\models\Dishes::findOne($d->dishes_id);
                $v = \common\models\Variativity::find()->where(['nutrition_id' => $nutrition->id, 'dishes_category_id' => $dish->dishes_category_id])->one()->block_id;
                $mv = \common\models\MenusVariativity::find()->where(['menu_id' => $my_menus->id, 'variativity_id' => $v])->one();
                if($v && $mv){
                    $variat[$v]++;
                }
            }
        }?>

    <div class="block mt-0" style="margin-top: 10px;">
        <table class="table_th0 table-hover table-responsive last" >
            <thead>
            <tr class="text-center"><? echo '<p class="mb-0" style="font-size: 20px; font-weight: 500;">'. $nutrition->name .'</p>'?></tr>
            <tr>
                <th class="text-center align-middle" rowspan="2" style="min-width: 200px">№ рецептуры</th>
                <th class="text-center align-middle" rowspan="2" style="min-width: 400px">Название блюда</th>
                <th class="text-center align-middle" rowspan="2">Выход</th>
                <th class="text-center align-middle" rowspan="2">Белки</th>
                <th class="text-center align-middle" rowspan="2">Жиры</th>
                <th class="text-center align-middle" rowspan="2">Углеводы</th>
                <th class="text-center align-middle" rowspan="2">Эн. ценность</th>
                <? if($post['days_id'] == 1){?>
                    <th class="text-center" colspan="5">Витамины</th>
                    <th class="text-center" colspan="9">Минеральные вещества</th>
                <?}?>
            </tr>
            <tr>
                <? if($post['days_id'] == 1){?>
                    <th class="text-center">B1, мг</th>
                    <th class="text-center">B2, мг</th>
                    <th class="text-center">A, мкг рет.экв</th>
                    <th class="text-center">D, мкг</th>
                    <th class="text-center">C, мг</th>
                    <th class="text-center">Na, мг</th>
                    <th class="text-center">K, мг</th>
                    <th class="text-center">Ca, мг</th>
                    <th class="text-center">Mg, мг</th>
                    <th class="text-center">P, мг</th>
                    <th class="text-center">Fe, мг</th>
                    <th class="text-center">I, мкг</th>
                    <th class="text-center">Se, мкг</th>
                    <th class="text-center">F, мкг</th>
                <?}?>
            </tr>
            </thead>
            <tbody>
        <? $count = 0; $yield = 0;
        $indicator = 0; $energy_kkal = 0; $protein = 0; $fat = 0; $carbohydrates_total = 0; $vitamins = []; unset($vitamins); $vitamin_a = 0; $vitamin_c = 0; $vitamin_b1 = 0; $vitamin_b2 = 0; $vitamin_d = 0; $vitamin_pp = 0; $na = 0; $k = 0; $ca = 0; $f = 0; $p = 0; $se = 0; $i = 0; $mg = 0; $fe = 0;?>
        <?foreach($menus_dishes as $key => $m_dish){ ?>
                <? if($nutrition->id == $m_dish->nutrition_id && $m_dish->cycle == $cycle_id && $day->id == $m_dish->days_id){ ?>

                <? $count++;?>

                <?$dish = \common\models\Dishes::findOne($m_dish->dishes_id);
                $v = \common\models\Variativity::find()->where(['nutrition_id' => $nutrition->id, 'dishes_category_id' => $dish->dishes_category_id])->one()->block_id;
                $mv = \common\models\MenusVariativity::find()->where(['menu_id' => $my_menus->id, 'variativity_id' => $v])->one();
                if($v && $mv){
                    $ident = $variat[$v];
                }else{
                    $ident = 1;
                }?>

                <!--ВЫВОД ПОСТРОЧНО КАЖДОГО БЛЮДА В РАЗАРЕЗЕ ПРИЕМА ПИЩИ-->
                <tr data-id="<?= $m_dish->id;?>">
                <td class="text-center <?if($ident > 1){ echo \common\models\Variativity::find()->where(['block_id' => $v])->one()->color;}?>"><?= $m_dish->get_techmup($m_dish->dishes_id)?></td>
                <td><?= $m_dish->get_dishes($m_dish->dishes_id)?></td>

                <td class="text-center"><? $yield_dish = $m_dish->yield; echo $yield_dish; $yield = $yield + round($yield_dish/$ident, 1); ?></td>
                <td class="text-center"><? $protein_dish = round($m_dish->get_bju_dish($m_dish->id, 'protein'),1); echo $protein_dish; $protein = $protein + round($protein_dish/$ident, 1);?></td>
                <td class="text-center"><? $fat_dish = round($m_dish->get_bju_dish($m_dish->id, 'fat'),1); echo $fat_dish; $fat = $fat + round($fat_dish/$ident, 1);?></td>
                <td class="text-center"><? $carbohydrates_total_dish = round($m_dish->get_bju_dish($m_dish->id, 'carbohydrates_total'),1); echo $carbohydrates_total_dish; $carbohydrates_total = $carbohydrates_total + round($carbohydrates_total_dish/$ident, 1); ?></td>
                <td class="text-center"><? $kkal = round($m_dish->get_kkal_dish($m_dish->id),1); echo $kkal; $energy_kkal = $energy_kkal + round($kkal/$ident, 1); ?></td>
                <? if($post['days_id'] == 1){?>
                <td class="text-center"><? $vitamins['vitamin_b1'] = round($m_dish->get_vitamin($m_dish->id, $m_dish->yield, 'vitamin_b1'),2); echo $vitamins['vitamin_b1']; $vitamin_b1 = $vitamin_b1 + round($vitamins['vitamin_b1']/$ident, 2)?></td>
                <td class="text-center"><? $vitamins['vitamin_b2'] = round($m_dish->get_vitamin($m_dish->id, $m_dish->yield, 'vitamin_b2'),2); echo $vitamins['vitamin_b2']; $vitamin_b2 = $vitamin_b2 + round($vitamins['vitamin_b2']/$ident, 2)?></td>
                <td class="text-center"><? $vitamins['vitamin_a'] = round($m_dish->get_vitamin($m_dish->id, $m_dish->yield, 'vitamin_a'),2); echo $vitamins['vitamin_a']; $vitamin_a= $vitamin_a + round($vitamins['vitamin_a']/$ident, 2)?></td>
                <td class="text-center"><? $vitamins['vitamin_d'] = round($m_dish->get_vitamin($m_dish->id, $m_dish->yield, 'vitamin_d'),2); echo $vitamins['vitamin_d']; $vitamin_d = $vitamin_d + round($vitamins['vitamin_d']/$ident, 2)?></td>
                <td class="text-center"><? $vitamins['vitamin_c'] = round($m_dish->get_vitamin($m_dish->id, $m_dish->yield, 'vitamin_c'),2); echo $vitamins['vitamin_c']; $vitamin_c = $vitamin_c + round($vitamins['vitamin_c']/$ident, 2)?></td>
                <td class="text-center"><? $vitamins['na'] = round($m_dish->get_vitamin($m_dish->id, $m_dish->yield, 'na'),2); echo $vitamins['na']; $na = $na + round($vitamins['na']/$ident, 2)?></td>
                <td class="text-center"><? $vitamins['k'] = round($m_dish->get_vitamin($m_dish->id, $m_dish->yield, 'k'),2); echo $vitamins['k']; $k = $k + round($vitamins['k']/$ident, 2)?></td>
                <td class="text-center"><? $vitamins['ca'] = round($m_dish->get_vitamin($m_dish->id, $m_dish->yield, 'ca'),2); echo $vitamins['ca']; $ca = $ca + round($vitamins['ca']/$ident, 2)?></td>
                <td class="text-center"><? $vitamins['mg'] = round($m_dish->get_vitamin($m_dish->id, $m_dish->yield, 'mg'),2); echo $vitamins['mg']; $mg = $mg + round($vitamins['mg']/$ident, 2)?></td>
                <td class="text-center"><? $vitamins['p'] = round($m_dish->get_vitamin($m_dish->id, $m_dish->yield, 'p'),2); echo $vitamins['p']; $p = $p + round($vitamins['p']/$ident, 2)?></td>
                <td class="text-center"><? $vitamins['fe'] = round($m_dish->get_vitamin($m_dish->id, $m_dish->yield, 'fe'),2); echo $vitamins['fe']; $fe = $fe + round($vitamins['fe']/$ident, 2)?></td>
                <td class="text-center"><? $vitamins['i'] = round($m_dish->get_vitamin($m_dish->id, $m_dish->yield, 'i'),2); echo $vitamins['i']; $i = $i + round($vitamins['i']/$ident, 2)?></td>
                <td class="text-center"><? $vitamins['se'] = round($m_dish->get_vitamin($m_dish->id, $m_dish->yield, 'se'),2); echo $vitamins['se']; $se = $se + round($vitamins['se']/$ident, 2)?></td>
                <td class="text-center"><? $vitamins['f'] = round($m_dish->get_vitamin($m_dish->id, $m_dish->yield, 'f'),2); echo $vitamins['f']; $f = $f + round($vitamins['f']/$ident, 2)?></td>

                <?}?>
                <? unset($menus_dishes[$key]); ?>
                </tr>
        <?}else{break;}?>
        <?}?>
        <? if($count > 0){ ?>
            <!--ВЫВОД СТРОЧКИ "ИТОГО" В РАЗРЕЗЕ КАЖДОГО ПРИЕМА ПИЩИ-->

            <tr class="table-primary">
                <td colspan="2">Итого за <? echo $nutrition->name?></td>
                <!--МАССИВ data[<id приема пищи>][<название поля>] ХРАНИТ В СЕБЕ СРЕДНИЕ ПОКАЗАТЕЛИ ЗА <прием пищи>(т.е сумма за все завтраки, обеды и тд..) (самый низ таблицы)
                    $super_total_<название_поля> - ХРАНИТ ЗНАЧЕНИЕ 'ИТОГО ЗА ДЕНЬ'. РАСЧИТЫВАЕТСЯ ВСЕ В td и ниже вставляется в другие td-->
                <td class="text-center"><? echo $yield; $data[$nutrition->id]['yield'] = $data[$nutrition->id]['yield'] + $yield; $super_total_yield = $super_total_yield + $yield;?></td>
                <td class="text-center"><? echo $protein; $data[$nutrition->id]['protein'] = $data[$nutrition->id]['protein'] + $protein; $super_total_protein = $super_total_protein + $protein;?></td>
                <td class="text-center"><? echo $fat; $data[$nutrition->id]['fat'] = $data[$nutrition->id]['fat'] + $fat; $super_total_fat = $super_total_fat + $fat;?></td>
                <td class="text-center"><? echo $carbohydrates_total; $data[$nutrition->id]['carbohydrates_total'] = $data[$nutrition->id]['carbohydrates_total'] + $carbohydrates_total; $super_total_carbohydrates_total = $super_total_carbohydrates_total + $carbohydrates_total;?></td>
                <td class="text-center"><? echo $energy_kkal; $data[$nutrition->id]['energy_kkal'] = $data[$nutrition->id]['energy_kkal'] + $energy_kkal; $super_total_energy_kkal = $super_total_energy_kkal + $energy_kkal;?></td>
                <? if($post['days_id'] == 1){?>
                <td class="text-center"><?= $vitamin_b1; $data[$nutrition->id]['vitamin_b1'] = $data[$nutrition->id]['vitamin_b1'] + $vitamin_b1; $super_total_vitamin_b1 = $super_total_vitamin_b1 + $vitamin_b1;?></td>
                <td class="text-center"><?= $vitamin_b2; $data[$nutrition->id]['vitamin_b2'] = $data[$nutrition->id]['vitamin_b2'] + $vitamin_b2; $super_total_vitamin_b2 = $super_total_vitamin_b2 + $vitamin_b2;?></td>
                <td class="text-center"><?= $vitamin_a; $data[$nutrition->id]['vitamin_a'] = $data[$nutrition->id]['vitamin_a'] + $vitamin_a; $super_total_vitamin_a = $super_total_vitamin_a + $vitamin_a;?></td>
                <td class="text-center"><?= $vitamin_d; $data[$nutrition->id]['vitamin_d'] = $data[$nutrition->id]['vitamin_d'] + $vitamin_d; $super_total_vitamin_d = $super_total_vitamin_d + $vitamin_d;?></td>
                <td class="text-center"><?= $vitamin_c; $data[$nutrition->id]['vitamin_c'] = $data[$nutrition->id]['vitamin_c'] + $vitamin_c; $super_total_vitamin_c = $super_total_vitamin_c + $vitamin_c;?></td>
                <td class="text-center"><?= $na; $data[$nutrition->id]['vitamin_na'] = $data[$nutrition->id]['vitamin_na'] + $na; $super_total_na = $super_total_na + $na;?></td>
                <td class="text-center"><?= $k; $data[$nutrition->id]['vitamin_k'] = $data[$nutrition->id]['vitamin_k'] + $k; $super_total_k = $super_total_k + $k;?></td>
                <td class="text-center"><?= $ca; $data[$nutrition->id]['vitamin_ca'] = $data[$nutrition->id]['vitamin_ca'] + $ca; $super_total_ca = $super_total_ca + $ca;?></td>
                <td class="text-center"><?= $mg; $data[$nutrition->id]['vitamin_mg'] = $data[$nutrition->id]['vitamin_mg'] + $mg; $super_total_mg = $super_total_mg + $mg;?></td>
                <td class="text-center"><?= $p; $data[$nutrition->id]['vitamin_p'] = $data[$nutrition->id]['vitamin_p'] + $p; $super_total_p = $super_total_p + $p;?></td>
                <td class="text-center"><?= $fe; $data[$nutrition->id]['vitamin_fe'] = $data[$nutrition->id]['vitamin_fe'] + $fe; $super_total_fe = $super_total_fe + $fe;?></td>
                <td class="text-center"><?= $i; $data[$nutrition->id]['vitamin_i'] = $data[$nutrition->id]['vitamin_i'] + $i; $super_total_i = $super_total_i + $i;?></td>
                <td class="text-center"><?= $se; $data[$nutrition->id]['vitamin_se'] = $data[$nutrition->id]['vitamin_se'] + $se; $super_total_se = $super_total_se + $se;?></td>
                <td class="text-center"><?= $f; $data[$nutrition->id]['vitamin_f'] = $data[$nutrition->id]['vitamin_f'] + $f; $super_total_f = $super_total_f + $f;?></td>

                <?} ?>
            </tr>
            <tr class="table-success">
                <td colspan="2">Рекомендуемая величина</td>
                <td></td>
                <? $normativ = $model->get_recommended_normativ_new($post['menu_id'], $nutrition->id);?>
                <td class="text-center"><?= $normativ['protein'];?></td>
                <td class="text-center"><?= $normativ['fat'];?></td>
                <td class="text-center"><?= $normativ['carbohydrates'];?></td>
                <td class="text-center"><?= $normativ['kkal'];?></td>

                <? if($post['days_id'] == 1){?>
                    <?foreach($fields as $field){?>
                        <td class="text-center"><?= $normativ[$field]?></td>
                    <?}?>
                <?}?>

            </tr>
            <tr class="table-warning">
<!--                Для бжу функции есть в модели, но чтобы не нагружать страницу расчитывается через переменные-->
                <td colspan="2">Процентное соотношение БЖУ</td>
                <td></td>
                <td class="text-center">1</td>
                <td class="text-center"><? if($protein != 0){ echo round(($fat/$protein), 2);}else{ echo 0;}  ?></td>
                <td class="text-center"><? if($protein != 0){ echo round(($carbohydrates_total/$protein), 2);}else{ echo 0;} ?></td>
            </tr>
<!--            <tr class="table-warning">-->
<!--                <td colspan="2">Процент от общей массы пищевых веществ</td>-->
<!--                <td></td>-->
<!--                <td class="text-center">--><?//= $model->get_procent($post['menu_id'], $cycle_id, $day->id, $nutrition->id, 'protein').'%'; ?><!--</td>-->
<!--                <td class="text-center">--><?//= $model->get_procent($post['menu_id'], $cycle_id, $day->id, $nutrition->id, 'fat').'%'; ?><!--</td>-->
<!--                <td class="text-center">--><?//= $model->get_procent($post['menu_id'], $cycle_id, $day->id, $nutrition->id, 'carbohydrates_total').'%'; ?><!--</td>-->
<!--            </tr>-->
<!--            <tr class="table-info last2">-->
<!--                <td colspan="2">Процент от суток</td>-->
<!--                <td class="text-center">--><?//= $model->get_super_total_yield($post['menu_id'], $cycle_id, $day->id, $nutrition->id).'%'; ?><!--</td>-->
<!--                <td class="text-center">--><?//= $model->get_super_total_field($post['menu_id'], $cycle_id, $day->id, $nutrition->id, 'protein').'%'; ?><!--</td>-->
<!--                <td class="text-center">--><?//= $model->get_super_total_field($post['menu_id'], $cycle_id, $day->id, $nutrition->id, 'fat').'%'; ?><!--</td>-->
<!--                <td class="text-center">--><?//= $model->get_super_total_field($post['menu_id'], $cycle_id, $day->id, $nutrition->id, 'carbohydrates_total').'%'; ?><!--</td>-->
<!--                <td class="text-center">--><?//= $model->get_super_total_kkal($post['menu_id'], $cycle_id, $day->id, $nutrition->id, 'energy_kkal').'%'; ?><!--</td>-->
<!--            </tr>-->
            <?}?>

            <?}?>
            <tr class="table-danger itog_day">
                <td>Итого за день</td>
                <td></td>
                <td class="text-center"><?= $super_total_yield; ?></td>
                <td class="text-center"><?= $super_total_protein; ?></td>
                <td class="text-center"><?= $super_total_fat;?></td>
                <td class="text-center"><?= $super_total_carbohydrates_total; ?></td>
                <td class="text-center"><?= $super_total_energy_kkal; ?></td>
                <? if($post['days_id'] == 1){?>
                <td class="text-center"><?= $super_total_vitamin_b1; ?></td>
                <td class="text-center"><?= $super_total_vitamin_b2; ?></td>
                <td class="text-center"><?= $super_total_vitamin_a; ?></td>
                <td class="text-center"><?= $super_total_vitamin_d; ?></td>
                <td class="text-center"><?= $super_total_vitamin_c; ?></td>
                <td class="text-center"><?= $super_total_na; ?></td>
                <td class="text-center"><?= $super_total_k; ?></td>
                <td class="text-center"><?= $super_total_ca; ?></td>
                <td class="text-center"><?= $super_total_mg; ?></td>
                <td class="text-center"><?= $super_total_p; ?></td>
                <td class="text-center"><?= $super_total_fe; ?></td>
                <td class="text-center"><?= $super_total_i; ?></td>
                <td class="text-center"><?= $super_total_se; ?></td>
                <td class="text-center"><?= $super_total_f; ?></td>

                <?}?>
            </tr>

            <tr class="table-success">
                <td colspan="2">Рекомендуемая величина за день</td>
                <? $normativ = $model->get_recommended_normativ_new($post['menu_id'], 'day');?>
                <td></td>
                <td class="text-center"><?= $normativ['protein'];?></td>
                <td class="text-center"><?= $normativ['fat'];?></td>
                <td class="text-center"><?= $normativ['carbohydrates'];?></td>
                <td class="text-center"><?= $normativ['kkal'];?></td>

                <? if($post['days_id'] == 1){?>
                    <?foreach($fields as $field){?>
                        <td class="text-center"><?= $normativ[$field]?></td>
                    <?}?>
                <?}?>
            </tr>
            <tr class="procent_day table-danger">
                <td colspan="2">Процентное соотношение БЖУ за день</td>
                <td></td>
                <td class="text-center"><?= '100%'; ?></td>
                <td class="text-center"><? if($super_total_protein != 0){echo round(($super_total_fat/$super_total_protein) * 100, 2) .'%';} else{ echo 0;}?></td>
                <td class="text-center"><? if($super_total_protein != 0){echo  round(($super_total_carbohydrates_total/$super_total_protein) * 100, 2) .'%'; ;} else{ echo 0;}?></td>
            </tr>
        </tbody>
        </table>
        <?php //} ?>
        <?php } ?>
        <?php } ?>
    <table class="table_th0 table-hover table-responsive mt-1" >
        <thead>
        <tr>
            <th class="text-center align-middle" rowspan="2">Итого за период</th>
            <th class="text-center align-middle" rowspan="2">Выход</th>
            <th class="text-center align-middle" rowspan="2">Белки</th>
            <th class="text-center align-middle" rowspan="2">Жиры</th>
            <th class="text-center align-middle" rowspan="2">Углеводы</th>
            <th class="text-center align-middle" rowspan="2">Эн. ценность</th>
            <? if($post['days_id'] == 1){?>
                <th class="text-center" colspan="5">Витамины</th>
                <th class="text-center" colspan="9">Минеральные вещества</th>
            <?}?>
        </tr>
        <tr>
            <? if($post['days_id'] == 1){?>
                <th class="text-center">B1, мг</th>
                <th class="text-center">B2, мг</th>
                <th class="text-center">A, мкг рет.экв</th>
                <th class="text-center">D, мкг</th>
                <th class="text-center">C, мг</th>
                <th class="text-center">Na, мг</th>
                <th class="text-center">K, мг</th>
                <th class="text-center">Ca, мг</th>
                <th class="text-center">Mg, мг</th>
                <th class="text-center">P, мг</th>
                <th class="text-center">Fe, мг</th>
                <th class="text-center">I, мкг</th>
                <th class="text-center">Se, мкг</th>
                <th class="text-center">F, мкг</th>
            <?}?>
        </tr>
        </thead>
            <? $data_itog =[];?>
            <? foreach($nutritions as $nutrition){?>

                    <!--ИСКУССТВЕННОЕ ИЗМЕНЕНИЕ ЗНАЧЕНИЙ ДЛЯ МЕНЮ ПО ПРОСЬБЕ ИИ И РОМАНЕНКО МЕНЮ ПО НСК --КОСТЫЛИ---->
                    <!--НСК-1-->
                <?if(Menus::findOne($menu_id)->id == 16047/*У АДМИНА*/ || /*В АРХИВЕ*/Menus::findOne($menu_id)->id == 16079 || Menus::findOne($menu_id)->parent_id == 16079){
                    //по обедам и завтракам
                    if($nutrition->id == 1){
                        $data[$nutrition->id]['protein'] = 17.4*$count_my_days;
                        $data[$nutrition->id]['fat'] = 15.9*$count_my_days;
                        $data[$nutrition->id]['vitamin_se'] = 16.1*$count_my_days;
                    }
                    if($nutrition->id == 3){
                        $data[$nutrition->id]['protein'] = 24*$count_my_days;
                        $data[$nutrition->id]['fat'] = 24*$count_my_days;
                        $data[$nutrition->id]['vitamin_se'] = 14.4*$count_my_days;
                    }
                }?>

                <!--НСК-2-->
                <?if(Menus::findOne($menu_id)->id == 16048/*У АДМИНА*/ || /*В АРХИВЕ*/Menus::findOne($menu_id)->id == 16080 || Menus::findOne($menu_id)->parent_id == 16080){
                    //по обедам и завтракам
                    if($nutrition->id == 1){
                        $data[$nutrition->id]['protein'] = 20.1*$count_my_days;
                        $data[$nutrition->id]['fat'] = 16*$count_my_days;
                        $data[$nutrition->id]['vitamin_se'] = 15.2*$count_my_days;
                    }
                    if($nutrition->id == 3){
                        $data[$nutrition->id]['protein'] = 23.6*$count_my_days;
                        $data[$nutrition->id]['fat'] = 23.9*$count_my_days;
                        $data[$nutrition->id]['vitamin_se'] = 15.2*$count_my_days;
                    }
                }?>

                <!--НСК-3-->
                <?if(Menus::findOne($menu_id)->id == 16055/*У АДМИНА*/ || /*В АРХИВЕ*/Menus::findOne($menu_id)->id == 16081 || Menus::findOne($menu_id)->parent_id == 16081){
                    //по обедам и завтракам
                    if($nutrition->id == 1){
                        $data[$nutrition->id]['protein'] = 15.8*$count_my_days;
                        $data[$nutrition->id]['fat'] = 15.9*$count_my_days;
                        $data[$nutrition->id]['vitamin_se'] = 11.9*$count_my_days;
                    }
                    if($nutrition->id == 3){
                        $data[$nutrition->id]['protein'] = 24.0*$count_my_days;
                        $data[$nutrition->id]['fat'] = 23.9*$count_my_days;
                        $data[$nutrition->id]['vitamin_se'] = 18.7*$count_my_days;
                    }
                }?>

                <tr class="table-danger-2">
                    <!--$data_itog['название поля'] - ХРАНИТ ЗНАЧЕНИЕ СРЕДНИХ ПОКАЗАТЕЛЕЙ ЗА ВСЕ ДНИ КОНКРЕТНОГО ПРИЕМА ПИЩИ, Т.Е СУММА МАССИВА $DATA['ПРИЕМ ПИЩИ']-->
                    <td>Средние показатели за <? echo $nutrition->name?></td>
                    <td class="text-center"><?= round($data[$nutrition->id]['yield']/$count_my_days, 2); $data_itog['yield'] = $data_itog['yield'] + round($data[$nutrition->id]['yield']/$count_my_days, 2);?></td>
                    <td class="text-center"><?= round($data[$nutrition->id]['protein']/$count_my_days, 1); $data_itog['protein'] = $data_itog['protein'] + round($data[$nutrition->id]['protein']/$count_my_days, 2);?></td>
                    <td class="text-center"><?= round($data[$nutrition->id]['fat']/$count_my_days, 1); $data_itog['fat'] = $data_itog['fat'] + round($data[$nutrition->id]['fat']/$count_my_days, 2);?></td>
                    <td class="text-center"><?= round($data[$nutrition->id]['carbohydrates_total']/$count_my_days, 1); $data_itog['carbohydrates_total'] = $data_itog['carbohydrates_total'] + round($data[$nutrition->id]['carbohydrates_total']/$count_my_days, 2);?></td>
                    <td class="text-center"><?= round($data[$nutrition->id]['energy_kkal']/$count_my_days, 1); $data_itog['energy_kkal'] = $data_itog['energy_kkal'] + round($data[$nutrition->id]['energy_kkal']/$count_my_days, 2);?></td>
                    <? if($post['days_id'] == 1){?>
<!--                    <td class="text-center">--><?//=round($data[$nutrition->id]['vitamin_a']/$count_my_days, 2); $data_itog['vitamin_a'] = $data_itog['vitamin_a'] + round($data[$nutrition->id]['vitamin_a']/$count_my_days, 2);?><!--</td>-->
                    <td class="text-center"><?= round($data[$nutrition->id]['vitamin_b1']/$count_my_days, 2); $data_itog['vitamin_b1'] = $data_itog['vitamin_b1'] + round($data[$nutrition->id]['vitamin_b1']/$count_my_days, 2);?></td>
                    <td class="text-center"><?= round($data[$nutrition->id]['vitamin_b2']/$count_my_days, 2); $data_itog['vitamin_b2'] = $data_itog['vitamin_b2'] + round($data[$nutrition->id]['vitamin_b2']/$count_my_days, 2);?></td>
                    <td class="text-center"><? $data_vit_a = round($data[$nutrition->id]['vitamin_a']/$count_my_days, 2);$procent = \common\models\NutritionProcent::find()->where(['nutrition_id' =>$nutrition->id])->one()->procent/100; if($data_vit_a <= $normativ_vitamin_day_vitamin_a*1.5*$procent){ echo $data_vit_a; $data_itog['vitamin_a'] = $data_itog['vitamin_a'] + $data_vit_a;}else{echo $normativ_vitamin_day_vitamin_a*1.5*$procent; $data_itog['vitamin_a'] = $data_itog['vitamin_a'] + $normativ_vitamin_day_vitamin_a*1.5*$procent;} ?></td>
                    <td class="text-center"><?= round($data[$nutrition->id]['vitamin_d']/$count_my_days, 2); $data_itog['vitamin_d'] = $data_itog['vitamin_d'] + round($data[$nutrition->id]['vitamin_d']/$count_my_days, 2);?></td>
                    <td class="text-center"><?= round($data[$nutrition->id]['vitamin_c']/$count_my_days, 2); $data_itog['vitamin_c'] = $data_itog['vitamin_c'] + round($data[$nutrition->id]['vitamin_c']/$count_my_days, 2);?></td>
                    <td class="text-center"><?= round($data[$nutrition->id]['vitamin_na']/$count_my_days, 3); $data_itog['vitamin_na'] = $data_itog['vitamin_na'] + round($data[$nutrition->id]['vitamin_na']/$count_my_days, 2);?></td>
<!--                    <td class="text-center">--><?//= round($data[$nutrition->id]['vitamin_k']/$count_my_days, 3); $data_itog['vitamin_k'] = $data_itog['vitamin_k'] + round($data[$nutrition->id]['vitamin_k']/$count_my_days, 2);?><!--</td>-->

                    <td class="text-center"><? $data_vit_k = round($data[$nutrition->id]['vitamin_k']/$count_my_days, 2);$procent = \common\models\NutritionProcent::find()->where(['nutrition_id' =>$nutrition->id])->one()->procent/100; if($data_vit_k <= $normativ_vitamin_day_k*1.5*$procent){ echo $data_vit_k; $data_itog['vitamin_k'] = $data_itog['vitamin_k'] + $data_vit_a;}else{echo $normativ_vitamin_day_k*1.5*$procent; $data_itog['vitamin_k'] = $data_itog['vitamin_k'] + $normativ_vitamin_day_k*1.5*$procent;} ?></td>
                    <td class="text-center"><?= round($data[$nutrition->id]['vitamin_ca']/$count_my_days, 3); $data_itog['vitamin_ca'] = $data_itog['vitamin_ca'] + round($data[$nutrition->id]['vitamin_ca']/$count_my_days, 2);?></td>
                    <td class="text-center"><?= round($data[$nutrition->id]['vitamin_mg']/$count_my_days, 3); $data_itog['vitamin_mg'] = $data_itog['vitamin_mg'] + round($data[$nutrition->id]['vitamin_mg']/$count_my_days, 2);?></td>
                    <td class="text-center"><?= round($data[$nutrition->id]['vitamin_p']/$count_my_days, 3); $data_itog['vitamin_p'] = $data_itog['vitamin_p'] + round($data[$nutrition->id]['vitamin_p']/$count_my_days, 2);?></td>
                    <td class="text-center"><?= round($data[$nutrition->id]['vitamin_fe']/$count_my_days, 3); $data_itog['vitamin_fe'] = $data_itog['vitamin_fe'] + round($data[$nutrition->id]['vitamin_fe']/$count_my_days, 2);?></td>
                    <td class="text-center"><?= round($data[$nutrition->id]['vitamin_i']/$count_my_days, 3); $data_itog['vitamin_i'] = $data_itog['vitamin_i'] + round($data[$nutrition->id]['vitamin_i']/$count_my_days, 2);?></td>
                    <td class="text-center"><?= round($data[$nutrition->id]['vitamin_se']/$count_my_days, 3); $data_itog['vitamin_se'] = $data_itog['vitamin_se'] + round($data[$nutrition->id]['vitamin_se']/$count_my_days, 2);?></td>
                    <td class="text-center"><?= round($data[$nutrition->id]['vitamin_f']/$count_my_days, 3); $data_itog['vitamin_f'] = $data_itog['vitamin_f'] + round($data[$nutrition->id]['vitamin_f']/$count_my_days, 2);?></td>
                    <?}?>
                </tr>
            <?}?>
            <tr class="table-danger-2">
                <!--$data_itog['название поля'] - ХРАНИТ ЗНАЧЕНИЕ СРЕДНИХ ПОКАЗАТЕЛЕЙ ЗА ВСЕ ДНИ КОНКРЕТНОГО ПРИЕМА ПИЩИ, Т.Е СУММА МАССИВА $DATA['ПРИЕМ ПИЩИ']-->
                <td>Средние показатели за период</td>
                <td class="text-center"><?= round($data_itog['yield'],1);?></td>
                <td class="text-center"><?= round($data_itog['protein'],1);?></td>
                <td class="text-center"><?= round($data_itog['fat'],1);?></td>
                <td class="text-center"><?= round($data_itog['carbohydrates_total'],1);?></td>
                <td class="text-center"><?= round($data_itog['energy_kkal'],1);?></td>
                <? if($post['days_id'] == 1){?>
                <td class="text-center"><?= round($data_itog['vitamin_b1'],1);?></td>
                <td class="text-center"><?= round($data_itog['vitamin_b2'],1);?></td>
                <td class="text-center"><?= round($data_itog['vitamin_a'],1);?></td>
                <td class="text-center"><?= round($data_itog['vitamin_d'],1);?></td>
                <td class="text-center"><?= round($data_itog['vitamin_c'],1);?></td>
                <td class="text-center"><?= round($data_itog['vitamin_na'],1);?></td>
                <td class="text-center"><?= round($data_itog['vitamin_k'],1);?></td>
                <td class="text-center"><?= round($data_itog['vitamin_ca'],1);?></td>
                <td class="text-center"><?= round($data_itog['vitamin_mg'],1);?></td>
                <td class="text-center"><?= round($data_itog['vitamin_p'],1);?></td>
                <td class="text-center"><?= round($data_itog['vitamin_fe'],1);?></td>
                <td class="text-center"><?= round($data_itog['vitamin_i'],1);?></td>
                <td class="text-center"><?= round($data_itog['vitamin_se'],1);?></td>
                <td class="text-center"><?= round($data_itog['vitamin_f'],1);?></td>
                <?}?>
            </tr>
            <tr class="table-danger-2">
                <td colspan="2">Соотношение БЖУ</td>
                <td class="text-center">1,0</td>
                <td class="text-center"><?= round($data_itog['fat']/$data_itog['protein'], 2);?></td>
                <td class="text-center"><?= round($data_itog['carbohydrates_total']/$data_itog['protein'], 2);?></td>
            </tr>
            <tr class="table-danger-2">
                <td colspan="2">Процент от общей массы пищевых веществ	</td>
                <td class="text-center"><?= round($data_itog['protein']/($data_itog['fat']+$data_itog['protein']+$data_itog['carbohydrates_total']), 2) * 100 .'%';?></td>
                <td class="text-center"><?= round($data_itog['fat']/($data_itog['fat']+$data_itog['protein']+$data_itog['carbohydrates_total']), 2) * 100 .'%';?></td>
                <td class="text-center"><?= round($data_itog['carbohydrates_total']/($data_itog['fat']+$data_itog['protein']+$data_itog['carbohydrates_total']), 2) * 100 .'%';?></td>
            </tr>
        </table>
    </div>

<?php } ?>
    <br>
    <div class="text-center">
        <?= Html::a('<span class="glyphicon glyphicon-download"></span> Скачать меню за период в Excel', ['/prints/excel/export-menus-period-excel?menu_id=' . $post['menu_id'].'&cycle='.$post['cycle'].'&him='.$post['days_id']],
            [
                'class'=>'btn btn-secondary',
                'style' =>['width'=>'500px'],
                'title' => Yii::t('yii', 'Скачать отчет в формате Excel'),
                'data-toggle'=>'tooltip',
            ])
        ?>
    </div>

    <br>
    <div class="text-center">
        <?= Html::a('<span class="glyphicon glyphicon-download"></span> Скачать техкарты блюд этого меню в PDF', ['export-techmup-this-menu-vihod?menu_id=' . $post['menu_id']],
            [
                'class'=>'btn btn-danger',
                'style' =>['width'=>'500px'],
                'title' => Yii::t('yii', 'Скачать отчет в формате PDF'),
                'data-toggle'=>'tooltip',
            ])
        ?>
    </div>
    <br>
    <div class="text-center">
        <?= Html::a('<span class="glyphicon glyphicon-download"></span> Скачать техкарты блюд этого меню в Excel', ['export-techmup-this-menu-vihod-excel?menu_id=' . $post['menu_id']],
            [
                'class'=>'btn btn-success',
                'style' =>['width'=>'500px'],
                'title' => Yii::t('yii', 'Скачать отчет в формате PDF'),
                'data-toggle'=>'tooltip',
            ])
        ?>
    </div>

    <br>
    <div class="text-center">
        <?= Html::a('<span class="glyphicon glyphicon-download"></span> Скачать техкарты блюд по дням в PDF', ['export-techmup-this-menu-day-vihod?menu_id=' . $post['menu_id']],
            [
                'class'=>'btn btn-danger',
                'style' =>['width'=>'500px'],
                'title' => Yii::t('yii', 'Скачать отчет в формате PDF'),
                'data-toggle'=>'tooltip',
            ])
        ?>
    </div>
    <div class="text-center">
        <p>*Скачивание файлов может занять некоторое время</p>
    </div>
<?php } ?>
    </div>
</div>
<br>


<!--ПРИ НАЖАТИИ НА  'ПОКАЗАТЬ БЛЮДА ЗА ПЕРИОД' БУДЕТ ВЫПОЛНЯТЬСЯ КОД НИЖЕ-->
<div class="row justify-content-center">
    <div class="col-auto">
<?php if($identificator == 'show'){?>
    <?php $count_cycle = 0;?>
    <table class="table_th0 table-hover table-responsive mt-1">
        <tbody>
    <?php foreach($cycle_ids as $cycle_id){
        $count++;
        $table .= '<tr class=""><td class="text-center main-info-see" colspan="4"><b>Неделя ' . $cycle_id .'</b></td></tr>';
        foreach($days as $day)
        {
            $table .= '<tr><td class="main-info-see p-2"><b>'. $day->name .'</b></td>';
            foreach($nutritions as $nutrition){
                $table .= '<td class="text-center p-2" rowspan="1"><b>'. $nutrition->name .'</b>';
                $table .= '<br>';
                $count = 0;
                $indicator = 0;
                foreach($menus_dishes as $key => $m_dish){
                    if($nutrition->id == $m_dish->nutrition_id && $m_dish->cycle == $cycle_id && $day->id == $m_dish->days_id){
                        $table .= ''.$m_dish->get_dishes($m_dish->dishes_id).' : '. $m_dish->get_techmup($m_dish->dishes_id).' <small>(id:'.$m_dish->dishes_id.')</small>';
                        $table .= '<br>';
                        unset($menus_dishes[$key]);
                    }
                }
                $table .= '</td>';
            }
            $table .= '</tr>';
        }
    }?>
    <?php
        echo $table;
    ?>
    <?php /*foreach($cycle_ids as $cycle_id){ $count++; echo 'Неделя'.$cycle_id;*/?><!--
        <?/* foreach($days as $day){*/?>
            <?/* echo '<b><p class="mb-0" style="font-size: 20px; font-weight: 500;">'. $day->name .'</p></b>'*/?>
            <?/* foreach($nutritions as $nutrition){*/?>
                <?/* echo '<b><p class="mb-0" style="font-size: 10px; font-weight: 300;">'. $nutrition->name .'</p></b>'*/?>
                <?/* $count = 0;
                $indicator = 0;*/?>
                <?/*foreach($menus_dishes as $key => $m_dish){*/?>
                    <?/* if($nutrition->id == $m_dish->nutrition_id && $m_dish->cycle == $cycle_id && $day->id == $m_dish->days_id){ */?>
                        <?php /*echo $m_dish->get_dishes($m_dish->dishes_id).' : '. $m_dish->get_techmup($m_dish->dishes_id)*/?>
                        <?/* unset($menus_dishes[$key]); */?>
                    <?php /*}*/?>
                <?php /*}*/?>
            <?php /*}*/?>
        <?php /*}*/?>
    --><?php /*}*/?>
        </tbody>
    </table>
<?php }?>
    </div>
</div>
<?
//print_r($data);
$script = <<< JS



$( ".beforeload" ).click(function() {
  $(".beforeload").css('display','none');
  $(".load").css('display','block');
  
});


/*$( ".beforeload" ).click(function() {
  $('.beforeload').append('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');
});*/
JS;

$this->registerJs($script, yii\web\View::POS_READY);
?>
