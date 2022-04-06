<?php

use common\models\CharactersStolovaya;
use common\models\Organization;
use common\models\SchoolBreak;
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

$this->title = 'Результаты проведения мероприятий контроля';
$this->params['breadcrumbs'][] = $this->title;

$organization_id = Yii::$app->user->identity->organization_id;
$region_id = Organization::findOne($organization_id)->region_id;
$my_mun = Organization::findOne($organization_id)->municipality_id;
if(Yii::$app->user->can('rospotrebnadzor_nutrition') || Yii::$app->user->can('minobr')){
    $municipalities = \common\models\Municipality::find()->where(['region_id' => $region_id])->all();
    $municipality_items = ArrayHelper::map($municipalities, 'id', 'name');
}else{
    $municipalities = \common\models\Municipality::find()->where(['id' => $my_mun])->all();
    $municipality_items = ArrayHelper::map($municipalities, 'id', 'name');
}




if(!empty($post)){
    $params_mun = ['class' => 'form-control', 'options' => [$post['menu_id'] => ['Selected' => true]]];

    $organizations = Organization::find()->where(['municipality_id' => $post['menu_id'], 'type_org' => 3])->andWhere(['!=', 'id', 7])->orderBy(['short_title'=> SORT_ASC,'title'=> SORT_ASC])->all();
    $mun = \common\models\Municipality::findOne($post['menu_id'])->name;
    $region_id = \common\models\Municipality::findOne($post['menu_id'])->region_id;
}

?>
<style>
    th, td {
        border: 1px solid black!important;
        color: black;
        font-size: 10px;

    }
    th {
        background-color: #ede8b9;
        font-size: 15px;
    }
    thead, th {
        background-color: #ede8b9;
        font-size: 10px;
    }
</style>



<?php $form = ActiveForm::begin([]); ?>
<div class="container mb-30">
    <h1 class="text-center"><?= Html::encode($this->title) ?></h1>
    <div class="row">
        <div class="col-11 col-md-12">
            <?= $form->field($model, 'menu_id')->dropDownList($municipality_items, [
                'class' => 'form-control', 'options' => [$post['menu_id'] => ['Selected' => true]],
            ])->label('Муниципальный округ'); ?>
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group" style="margin: 0 auto">
        <?= Html::submitButton('Посмотреть', ['class' => 'btn main-button-3 beforeload']) ?>
        <button class="btn main-button-3 load" type="button" disabled style="display: none">
            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
            Посмотреть...
        </button>
    </div>
</div>
<p class="text-center"><b>Для крупных городов и муниципальных образований формирование отчета может занимать некоторое время</b></p>

<?php ActiveForm::end(); ?>




<?if(!empty($post)){?>
    <table id="table_control" class="table table-sm fixtable table2excel_with_colors mt-5" style="width:70%">
        <thead>
        <tr>
            <td rowspan="3">№</td>
            <td rowspan="3">Муниципальное образование</td>
            <td rowspan="3">Наименование общеобразовательной организации</td>
            <td class="text-center align-middle" colspan="7">ЗАВТРАКИ</td>
            <td class="text-center align-middle" colspan="7">ОБЕДЫ</td>
            <td class="text-center align-middle" colspan="7">ИНЫЕ ПРИЕМЫ ПИЩИ</td>
        </tr>
        <tr>
           <td rowspan="2">Количество мероприятий родительского контроля</td>
            <td colspan="3">Количество баллов</td>
            <td colspan="3">Процент несъеденной пищи</td>

            <td rowspan="2">Количество мероприятий родительского контроля</td>
            <td colspan="3">Количество баллов</td>
            <td colspan="3">Процент несъеденной пищи</td>

            <td rowspan="2">Количество мероприятий родительского контроля</td>
            <td colspan="3">Количество баллов</td>
            <td colspan="3">Процент несъеденной пищи</td>
        </tr>
        <tr>
            <td>мин</td>
            <td>ср</td>
            <td>макс</td>
            <td>мин</td>
            <td>ср</td>
            <td>макс</td>

            <td>мин</td>
            <td>ср</td>
            <td>макс</td>
            <td>мин</td>
            <td>ср</td>
            <td>макс</td>

            <td>мин</td>
            <td>ср</td>
            <td>макс</td>
            <td>мин</td>
            <td>ср</td>
            <td>макс</td>

        </tr>

        </thead>
        <tbody>
        <? $itog_string = []; $count = 0; $sred = []; foreach ($organizations as $organization) { $count++;?>

            <tr>
                <td class="text-center align-middle"><?=$count?></td>
                <td class="text-center align-middle"><?=$mun?></td>
                <td class="align-middle"><?echo (empty($organization->short_title)) ? $organization->title : $organization->short_title;?></td>
                <?
                    if(!empty($control_zavtrak)){
                        unset($control_zavtrak);
                    }
                    if(!empty($control_obed)){
                        unset($control_obed);
                    }

                $control_zavtrak = $model->get_control_information($organization->id, 1);
                $control_obed = $model->get_control_information($organization->id, 3);
                $control_inoe = $model->get_control_information($organization->id, 'inoe');
                ?>
                <?if($control_zavtrak == 'null' || empty($control_zavtrak) || $control_zavtrak['vnutr'] == 0){?>
                    <td colspan="7" class="text-center align-middle <?if(($control_zavtrak == 'null' || empty($control_zavtrak) || $control_zavtrak['vnutr'] == 0) && ($control_obed == 'null' || empty($control_obed) || $control_obed['vnutr'] == 0) && ($control_inoe == 'null' || empty($control_inoe) || $control_inoe['vnutr'] == 0)){ echo 'bg-danger';}?>">Контроль в завтрак не проводился</td>
                <?}else{
                    $itog_string['zavtrak_control_count'] ++;?>
                    <td class="text-center align-middle"><?=$control_zavtrak['vnutr']; $itog_string['zavtrak_vnutr'] = $itog_string['zavtrak_vnutr'] + $control_zavtrak['vnutr']; ?></td>
                    <td class="text-center align-middle"><?=number_format($control_zavtrak['min_ball'], 1, ',', ''); if(empty($itog_string['zavtrak_min_ball'])){$itog_string['zavtrak_min_ball'] = round($control_zavtrak['min_ball'],1);}elseif ($itog_string['zavtrak_min_ball'] > round($control_zavtrak['min_ball'],1)){$itog_string['zavtrak_min_ball'] = round($control_zavtrak['min_ball'],1);}?></td>
                    <td class="text-center align-middle"><?=number_format($control_zavtrak['sred_ball'], 1, ',', ''); $itog_string['zavtrak_sred_ball'] = $itog_string['zavtrak_sred_ball'] + round($control_zavtrak['sred_ball'],1);?></td>
                    <td class="text-center align-middle"><?=number_format($control_zavtrak['max_ball'], 1, ',', ''); if(empty($itog_string['zavtrak_max_ball'])){$itog_string['zavtrak_max_ball'] = round($control_zavtrak['max_ball'],1);}elseif ($itog_string['zavtrak_max_ball'] < round($control_zavtrak['max_ball'],1)){$itog_string['zavtrak_max_ball'] = round($control_zavtrak['max_ball'],1);}?></td>
                    <td class="text-center align-middle"><?=number_format($control_zavtrak['min_procent'], 1, ',', '');if(empty($itog_string['zavtrak_min_procent'])){$itog_string['zavtrak_min_procent'] = round($control_zavtrak['min_procent'],1);}elseif ($itog_string['zavtrak_min_procent'] > round($control_zavtrak['min_procent'],1)){$itog_string['zavtrak_min_procent'] = round($control_zavtrak['min_procent'],1);}?></td>
                    <td class="text-center align-middle <?if(round($control_zavtrak['sred_procent'],1) >= 30){echo "bg-danger";}?>"><?=number_format($control_zavtrak['sred_procent'], 1, ',', ''); $itog_string['zavtrak_sred_procent'] = $itog_string['zavtrak_sred_procent'] +round($control_zavtrak['sred_procent'],1);?></td>
                    <td class="text-center align-middle <?if(round($control_zavtrak['max_procent'],1) >= 30){echo "bg-danger";}?>"><?=number_format($control_zavtrak['max_procent'], 1, ',', '');if(empty($itog_string['zavtrak_max_procent'])){$itog_string['zavtrak_max_procent'] = round($control_zavtrak['max_procent'],1);}elseif ($itog_string['zavtrak_max_procent'] < round($control_zavtrak['max_procent'],1)){$itog_string['zavtrak_max_procent'] = round($control_zavtrak['max_procent'],1);}?></td>
                <?}?>

                <?if($control_obed == 'null' || empty($control_obed) || $control_obed['vnutr'] == 0){?>
                    <td colspan="7" class="text-center align-middle <?if(($control_zavtrak == 'null' || empty($control_zavtrak) || $control_zavtrak['vnutr'] == 0) && ($control_obed == 'null' || empty($control_obed) || $control_obed['vnutr'] == 0) && ($control_inoe == 'null' || empty($control_inoe) || $control_inoe['vnutr'] == 0)){ echo 'bg-danger';}?>">Контроль в обед не проводился</td>
                <?}else{
                    $itog_string['obed_control_count'] ++;?>
                    <td class="text-center align-middle"><?=$control_obed['vnutr']; $itog_string['obed_vnutr'] = $itog_string['obed_vnutr'] + $control_obed['vnutr'];?></td>
                    <td class="text-center align-middle"><?=number_format($control_obed['min_ball'], 1, ',', ''); if(empty($itog_string['obed_min_ball'])){$itog_string['obed_min_ball'] = round($control_obed['min_ball'],1);}elseif ($itog_string['obed_min_ball'] > round($control_obed['min_ball'],1)){$itog_string['obed_min_ball'] = round($control_obed['min_ball'],1);}?></td>
                    <td class="text-center align-middle"><?=number_format($control_obed['sred_ball'], 1, ',', ''); $itog_string['obed_sred_ball'] = $itog_string['obed_sred_ball'] +round($control_obed['sred_ball'],1);?></td>
                    <td class="text-center align-middle"><?=number_format($control_obed['max_ball'], 1, ',', ''); if(empty($itog_string['obed_max_ball'])){$itog_string['obed_max_ball'] = round($control_obed['max_ball'],1);}elseif ($itog_string['obed_max_ball'] < round($control_obed['min_ball'],1)){$itog_string['obed_max_ball'] = round($control_obed['max_ball'],1);}?></td>
                    <td class="text-center align-middle"><?=number_format($control_obed['min_procent'], 1, ',', ''); if(empty($itog_string['obed_min_procent'])){$itog_string['obed_min_procent'] = round($control_obed['min_procent'],1);}elseif ($itog_string['obed_min_procent'] > round($control_obed['min_procent'],1)){$itog_string['obed_min_procent'] = round($control_obed['min_procent'],1);}?></td>
                    <td class="text-center align-middle <?if(round($control_obed['sred_procent'],1) >= 30){echo "bg-danger";}?>"><?=number_format($control_obed['sred_procent'], 1, ',', ''); $itog_string['obed_sred_procent'] = $itog_string['obed_sred_procent'] +round($control_obed['sred_procent'],1);?></td>
                    <td class="text-center align-middle <?if(round($control_obed['max_procent'],1) >= 30){echo "bg-danger";}?>"><?=number_format($control_obed['max_procent'], 1, ',', ''); if(empty($itog_string['obed_max_procent'])){$itog_string['obed_max_procent'] = round($control_obed['max_procent'],1);}elseif ($itog_string['obed_max_procent'] < round($control_obed['max_procent'],1)){$itog_string['obed_max_procent'] = round($control_obed['max_procent'],1);}?></td>
                <?}?>


                <?if($control_inoe == 'null' || empty($control_inoe) || $control_inoe['vnutr'] == 0){?>
                    <td colspan="7" class="text-center align-middle <?if(($control_zavtrak == 'null' || empty($control_zavtrak) || $control_zavtrak['vnutr'] == 0) && ($control_obed == 'null' || empty($control_obed) || $control_obed['vnutr'] == 0) && ($control_inoe == 'null' || empty($control_inoe) || $control_inoe['vnutr'] == 0)){ echo 'bg-danger';}?>">Контроль не проводился</td>
                <?}else{
                    $itog_string['inoe_control_count']++;?>
                    <td class="text-center align-middle"><?=$control_inoe['vnutr']; $itog_string['inoe_vnutr'] = $itog_string['inoe_vnutr'] + $control_inoe['vnutr'];?></td>
                    <td class="text-center align-middle"><?=number_format($control_inoe['min_ball'], 1, ',', ''); if(empty($itog_string['inoe_min_ball'])){$itog_string['inoe_min_ball'] = round($control_inoe['min_ball'],1);}elseif ($itog_string['inoe_min_ball'] > round($control_inoe['min_ball'],1)){$itog_string['inoe_min_ball'] = round($control_inoe['min_ball'],1);}?></td>
                    <td class="text-center align-middle"><?=number_format($control_inoe['sred_ball'], 1, ',', ''); $itog_string['inoe_sred_ball'] = $itog_string['inoe_sred_ball'] +round($control_inoe['sred_ball'],1);?></td>
                    <td class="text-center align-middle"><?=number_format($control_inoe['max_ball'], 1, ',', ''); if(empty($itog_string['inoe_max_ball'])){$itog_string['inoe_max_ball'] = round($control_inoe['max_ball'],1);}elseif ($itog_string['inoe_max_ball'] < round($control_inoe['min_ball'],1)){$itog_string['inoe_max_ball'] = round($control_inoe['max_ball'],1);}?></td>
                    <td class="text-center align-middle"><?=number_format($control_inoe['min_procent'], 1, ',', ''); if(empty($itog_string['inoe_min_procent'])){$itog_string['inoe_min_procent'] = round($control_inoe['min_procent'],1);}elseif ($itog_string['inoe_min_procent'] > round($control_inoe['min_procent'],1)){$itog_string['inoe_min_procent'] = round($control_inoe['min_procent'],1);}?></td>
                    <td class="text-center align-middle <?if(round($control_inoe['sred_procent'],1) >= 30){echo "bg-danger";}?>"><?=number_format($control_inoe['sred_procent'], 1, ',', ''); $itog_string['inoe_sred_procent'] = $itog_string['inoe_sred_procent'] +round($control_inoe['sred_procent'],1);?></td>
                    <td class="text-center align-middle <?if(round($control_inoe['max_procent'],1) >= 30){echo "bg-danger";}?>"><?=number_format($control_inoe['max_procent'], 1, ',', ''); if(empty($itog_string['inoe_max_procent'])){$itog_string['inoe_max_procent'] = round($control_inoe['max_procent'],1);}elseif ($itog_string['inoe_max_procent'] < round($control_inoe['max_procent'],1)){$itog_string['inoe_max_procent'] = round($control_inoe['max_procent'],1);}?></td>
                <?}?>
            </tr>
        <?}?>


        <tr class="table-danger">
            <td class="" colspan="3">Итого <?=$mun;?>:</td>
            <td class="text-center align-middle"><?=$itog_string['zavtrak_vnutr']?></td>
            <td class="text-center align-middle"><?=number_format($itog_string['zavtrak_min_ball'], 1, ',', '')?></td>
            <td class="text-center align-middle"><?if(!empty($itog_string['zavtrak_control_count'])){ echo number_format($itog_string['zavtrak_sred_ball']/$itog_string['zavtrak_control_count'], 1, ',', '');}?></td>
            <td class="text-center align-middle"><?=number_format($itog_string['zavtrak_max_ball'], 1, ',', '')?></td>
            <td class="text-center align-middle"><?=number_format($itog_string['zavtrak_min_procent'], 1, ',', '')?></td>
            <td class="text-center align-middle"><?if(!empty($itog_string['zavtrak_control_count'])){ echo number_format($itog_string['zavtrak_sred_procent']/$itog_string['zavtrak_control_count'], 1, ',', '');}?></td>
            <td class="text-center align-middle"><?=number_format($itog_string['zavtrak_max_procent'], 1, ',', '')?></td>
            <td class="text-center align-middle"><?=$itog_string['obed_vnutr']?></td>
            <td class="text-center align-middle"><?=number_format($itog_string['obed_min_ball'], 1, ',', '')?></td>
            <td class="text-center align-middle"><?if(!empty($itog_string['obed_control_count'])){ echo number_format($itog_string['obed_sred_ball']/$itog_string['obed_control_count'], 1, ',', '');}?></td>
            <td class="text-center align-middle"><?=number_format($itog_string['obed_max_ball'], 1, ',', '')?></td>
            <td class="text-center align-middle"><?=number_format($itog_string['obed_min_procent'], 1, ',', '')?></td>
            <td class="text-center align-middle"><?if(!empty($itog_string['obed_control_count'])){ echo number_format($itog_string['obed_sred_procent']/$itog_string['obed_control_count'], 1, ',', '');}?></td>
            <td class="text-center align-middle"><?=number_format($itog_string['obed_max_procent'], 1, ',', '')?></td>

            <td class="text-center align-middle"><?=$itog_string['inoe_vnutr']?></td>
            <td class="text-center align-middle"><?=number_format($itog_string['inoe_min_ball'], 1, ',', '')?></td>
            <td class="text-center align-middle"><?if(!empty($itog_string['inoe_control_count'])){ echo number_format($itog_string['inoe_sred_ball']/$itog_string['inoe_control_count'], 1, ',', '');}?></td>
            <td class="text-center align-middle"><?=number_format($itog_string['inoe_max_ball'], 1, ',', '')?></td>
            <td class="text-center align-middle"><?=number_format($itog_string['inoe_min_procent'], 1, ',', '')?></td>
            <td class="text-center align-middle"><?if(!empty($itog_string['inoe_control_count'])){ echo number_format($itog_string['inoe_sred_procent']/$itog_string['inoe_control_count'], 1, ',', '');}?></td>
            <td class="text-center align-middle"><?=number_format($itog_string['inoe_max_procent'], 1, ',', '')?></td>
        </tr>

        </tbody>
    </table>

    <div class="text-center mt-3 mb-3">
        <button id="pechat_control" class="btn btn-success">
            <span class="glyphicon glyphicon-download"></span> Скачать отчет в Excel
        </button>
        <p class="text-center text-danger"><b><small>Для крупных городов формирование отчета Excel может занимать некоторое время</small></b></p>
    </div>
<?}?>


<?
//print_r($data);
$script = <<< JS



$( ".beforeload" ).click(function() {
  $(".beforeload").css('display','none');
  $(".load").css('display','block');
  
});




$("#pechat_control").click(function () {
    var table = $('#table_control');
    if (table && table.length) {
        var preserveColors = (table.hasClass('table2excel_with_colors') ? true : false);
        $(table).table2excel({
            exclude: ".noExl",
            name: "Excel Document Name",
            filename: "Скачать отчет в Excel.xls",
            fileext: ".xls",
            exclude_img: true,
            exclude_links: true,
            exclude_inputs: true,
            preserveColors: preserveColors
        });
    }
});



JS;

$this->registerJs($script, yii\web\View::POS_READY);
?>
