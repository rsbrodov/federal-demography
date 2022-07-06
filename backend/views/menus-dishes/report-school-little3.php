<?php

use common\models\AnketParentControl;
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
$yes_no_items = [
    '1' => "Родительский",
    '2' => "Внутренний",
    '3' => "Родительский и внутренний",
];
$params_field = ['class' => 'form-control', 'options' => [1 => ['Selected' => true]]];
$params_date_start = '01.09.2021';
$params_date_end = date('d.m.Y');
$organization_id = Yii::$app->user->identity->organization_id;
$region_id = Organization::findOne($organization_id)->region_id;
$my_mun = Organization::findOne($organization_id)->municipality_id;
if(Yii::$app->user->can('rospotrebnadzor_nutrition') || Yii::$app->user->can('minobr')){
    $municipality_items = Yii::$app->territory->municipalities();
}else{
    $municipality_items = Yii::$app->territory->my_municipality();
}

$menus_dishes_model = new MenusDishes();


if(!empty($post)){
    if($post['field2'] == 0){
        $municipalities = \common\models\Municipality::find()->where(['region_id' => $region_id])->all();
    }else{
        $municipalities = \common\models\Municipality::find()->where(['id' => $post['field2']])->all();
    }

    $params_mun = ['class' => 'form-control', 'options' => [$post['field2'] => ['Selected' => true]]];
    $params_field = ['class' => 'form-control', 'options' => [$post['field'] => ['Selected' => true]]];
    $params_date_start = $post['date_start'];
    $params_date_end = $post['date_end'];
    $organizations = Organization::find()->where(['municipality_id' => $post['field2'], 'type_org' => 3])->andWhere(['!=', 'id', 7])->orderBy(['short_title'=> SORT_ASC,'title'=> SORT_ASC])->all();

    if($post['field'] = 3){
        $type_control = [1,2];
    }else{
        $type_control = $post['field'];
    }

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
            <?= $form->field($model, 'field2')->dropDownList($municipality_items, [
                'class' => 'form-control', 'options' => [$post['field2'] => ['Selected' => true]],
            ])->label('Муниципальный округ'); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'date_start')->textInput(['class'=>'datepicker-here form-control', 'autocomplete' => 'off', 'value' => $params_date_start])->label('Начало периода'); ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'date_end')->textInput(['class'=>'datepicker-here form-control', 'autocomplete' => 'off', 'value' => $params_date_end])->label('Конец периода'); ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'field')->dropDownList($yes_no_items, $params_field)->label('Вид контроля'); ?>
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

    <?$mas = [1,3, 2, 4,5,6,0];
    if($post['field2'] == 0){
        $organization_ids = Organization::find()->where(['region_id' => $region_id, 'type_org' => 3])->andWhere(['!=', 'id', 7])->orderBy(['short_title' => SORT_ASC, 'title' => SORT_ASC])->all();
        $title = \common\models\Region::findOne($region_id)->name;
    }else{
        $organization_ids = Organization::find()->where(['municipality_id' => $post['field2'], 'type_org' => 3])->andWhere(['!=', 'id', 7])->orderBy(['short_title' => SORT_ASC, 'title' => SORT_ASC])->all();
        $title = \common\models\Municipality::findOne($post['field2'])->name;
    }
    $organization_ids = ArrayHelper::map($organization_ids, 'id', 'id');
    foreach($mas as $m){
        if($m != 0){$rk_title[] = NutritionInfo::findOne($m)->name;}else{$rk_title[] = 'Не определен';};
        $count = AnketParentControl::find()->where(['organization_id' => $organization_ids, 'status' => 1, 'nutrition_id' => $m])->andWhere([ '>=', 'date', (strtotime('01.09.2021'))])->count();;
        $rk_count[] = $count;
    }?>
    <div class="box-shadow text-center nt-4" style="max-width: 750px; height: auto">
        <p class="text-center" style="font-size: 20px;"><b>Детализация РК по приемам пищи</b></p>
        <p class="text-center" style="font-size: 14px;"><b>Всего:<?=AnketParentControl::find()->where(['organization_id' => $organization_ids, 'status' => $type_control])->andWhere([ '>=', 'date', (strtotime($post['date_start']))])->andWhere([ '<=', 'date', (strtotime($post['date_end']))])->count();?></b></p>
        <canvas id="myChart" width="500px" height="200px"></canvas>
    </div>



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
           <td rowspan="2">Количество мероприятий</td>
            <td colspan="3">Количество баллов</td>
            <td colspan="3">Процент несъеденной пищи</td>

            <td rowspan="2">Количество мероприятий</td>
            <td colspan="3">Количество баллов</td>
            <td colspan="3">Процент несъеденной пищи</td>

            <td rowspan="2">Количество мероприятий</td>
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
        <?$super_itog_string = []; foreach ($municipalities as $municipality){ if(!empty($itog_string)){unset($itog_string);}?>
            <?$organizations = Organization::find()->where(['municipality_id' => $municipality->id, 'type_org' => 3])->andWhere(['!=', 'id', 7])->orderBy(['short_title'=> SORT_ASC,'title'=> SORT_ASC])->all();?>
            <? $itog_string = []; $count = 0; $sred = [];
            foreach ($organizations as $organization) { $count++;?>
                <?
                    if(!empty($control_zavtrak)){unset($control_zavtrak);}
                    if(!empty($control_obed)){unset($control_obed);}
                    if(!empty($control_inoe)){unset($control_inoe);}
                    $control_zavtrak = $menus_dishes_model->get_control_information($organization->id, 1, $post['field'], $post['date_start'], $post['date_end']);
                    $control_obed = $menus_dishes_model->get_control_information($organization->id, 3, $post['field'], $post['date_start'], $post['date_end']);
                    $control_inoe = $menus_dishes_model->get_control_information($organization->id, 'inoe', $post['field'], $post['date_start'], $post['date_end']);

                    if(!($control_zavtrak == 'null' || empty($control_zavtrak) || $control_zavtrak['vnutr'] == 0)){
                        $itog_string['zavtrak_control_count'] ++;
                        $itog_string['zavtrak_vnutr'] = $itog_string['zavtrak_vnutr'] + $control_zavtrak['vnutr'];
                        if(empty($itog_string['zavtrak_min_ball'])){$itog_string['zavtrak_min_ball'] = round($control_zavtrak['min_ball'],1);}elseif ($itog_string['zavtrak_min_ball'] > round($control_zavtrak['min_ball'],1)){$itog_string['zavtrak_min_ball'] = round($control_zavtrak['min_ball'],1);}
                        $itog_string['zavtrak_sred_ball'] = $itog_string['zavtrak_sred_ball'] + round($control_zavtrak['sred_ball'],1);
                        if(empty($itog_string['zavtrak_max_ball'])){$itog_string['zavtrak_max_ball'] = round($control_zavtrak['max_ball'],1);}elseif ($itog_string['zavtrak_max_ball'] < round($control_zavtrak['max_ball'],1)){$itog_string['zavtrak_max_ball'] = round($control_zavtrak['max_ball'],1);}
                        if(empty($itog_string['zavtrak_min_procent'])){$itog_string['zavtrak_min_procent'] = round($control_zavtrak['min_procent'],1);}elseif ($itog_string['zavtrak_min_procent'] > round($control_zavtrak['min_procent'],1)){$itog_string['zavtrak_min_procent'] = round($control_zavtrak['min_procent'],1);}
                        $itog_string['zavtrak_sred_procent'] = $itog_string['zavtrak_sred_procent'] +round($control_zavtrak['sred_procent'],1);
                        if(empty($itog_string['zavtrak_max_procent'])){$itog_string['zavtrak_max_procent'] = round($control_zavtrak['max_procent'],1);}elseif ($itog_string['zavtrak_max_procent'] < round($control_zavtrak['max_procent'],1)){$itog_string['zavtrak_max_procent'] = round($control_zavtrak['max_procent'],1);}
                    }
                    if(!($control_obed == 'null' || empty($control_obed) || $control_obed['vnutr'] == 0)){
                        $itog_string['obed_control_count'] ++;
                        $itog_string['obed_vnutr'] = $itog_string['obed_vnutr'] + $control_obed['vnutr'];
                        if(empty($itog_string['obed_min_ball'])){$itog_string['obed_min_ball'] = round($control_obed['min_ball'],1);}elseif ($itog_string['obed_min_ball'] > round($control_obed['min_ball'],1)){$itog_string['obed_min_ball'] = round($control_obed['min_ball'],1);}
                        $itog_string['obed_sred_ball'] = $itog_string['obed_sred_ball'] + round($control_obed['sred_ball'],1);
                        if(empty($itog_string['obed_max_ball'])){$itog_string['obed_max_ball'] = round($control_obed['max_ball'],1);}elseif ($itog_string['obed_max_ball'] < round($control_obed['min_ball'],1)){$itog_string['obed_max_ball'] = round($control_obed['max_ball'],1);}
                        if(empty($itog_string['obed_min_procent'])){$itog_string['obed_min_procent'] = round($control_obed['min_procent'],1);}elseif ($itog_string['obed_min_procent'] > round($control_obed['min_procent'],1)){$itog_string['obed_min_procent'] = round($control_obed['min_procent'],1);}
                        $itog_string['obed_sred_procent'] = $itog_string['obed_sred_procent'] +round($control_obed['sred_procent'],1);
                        if(empty($itog_string['obed_max_procent'])){$itog_string['obed_max_procent'] = round($control_obed['max_procent'],1);}elseif ($itog_string['obed_max_procent'] < round($control_obed['max_procent'],1)){$itog_string['obed_max_procent'] = round($control_obed['max_procent'],1);}
                    }
                    if(!($control_inoe == 'null' || empty($control_inoe) || $control_inoe['vnutr'] == 0)){
                        $itog_string['inoe_control_count']++;
                        $itog_string['inoe_vnutr'] = $itog_string['inoe_vnutr'] + $control_inoe['vnutr'];
                        if(empty($itog_string['inoe_min_ball'])){$itog_string['inoe_min_ball'] = round($control_inoe['min_ball'],1);}elseif ($itog_string['inoe_min_ball'] > round($control_inoe['min_ball'],1)){$itog_string['inoe_min_ball'] = round($control_inoe['min_ball'],1);}
                        $itog_string['inoe_sred_ball'] = $itog_string['inoe_sred_ball'] +round($control_inoe['sred_ball'],1);
                        if(empty($itog_string['inoe_max_ball'])){$itog_string['inoe_max_ball'] = round($control_inoe['max_ball'],1);}elseif ($itog_string['inoe_max_ball'] < round($control_inoe['min_ball'],1)){$itog_string['inoe_max_ball'] = round($control_inoe['max_ball'],1);}
                        if(empty($itog_string['inoe_min_procent'])){$itog_string['inoe_min_procent'] = round($control_inoe['min_procent'],1);}elseif ($itog_string['inoe_min_procent'] > round($control_inoe['min_procent'],1)){$itog_string['inoe_min_procent'] = round($control_inoe['min_procent'],1);}
                        $itog_string['inoe_sred_procent'] = $itog_string['inoe_sred_procent'] +round($control_inoe['sred_procent'],1);
                        if(empty($itog_string['inoe_max_procent'])){$itog_string['inoe_max_procent'] = round($control_inoe['max_procent'],1);}elseif ($itog_string['inoe_max_procent'] < round($control_inoe['max_procent'],1)){$itog_string['inoe_max_procent'] = round($control_inoe['max_procent'],1);}
                    }
                ?>
                <?if($post['field2'] > 0){?>
                <tr>
                    <td class="text-center align-middle"><?=$count?></td>
                    <td class="text-center align-middle"><?=$municipality->name?></td>
                    <td class="align-middle"><?echo (empty($organization->short_title)) ? $organization->title : $organization->short_title;?></td>

                    <?if($control_zavtrak == 'null' || empty($control_zavtrak) || $control_zavtrak['vnutr'] == 0){?>
                        <td colspan="7" class="text-center align-middle <?if(($control_zavtrak == 'null' || empty($control_zavtrak) || $control_zavtrak['vnutr'] == 0) && ($control_obed == 'null' || empty($control_obed) || $control_obed['vnutr'] == 0) && ($control_inoe == 'null' || empty($control_inoe) || $control_inoe['vnutr'] == 0)){ echo 'bg-danger';}?>">Контроль в завтрак не проводился</td>
                    <?}else{?>
                        <td class="text-center align-middle"><?=$control_zavtrak['vnutr'];?></td>
                        <td class="text-center align-middle"><?=number_format($control_zavtrak['min_ball'], 1, ',', ''); ?></td>
                        <td class="text-center align-middle"><?=number_format($control_zavtrak['sred_ball'], 1, ',', ''); ?></td>
                        <td class="text-center align-middle"><?=number_format($control_zavtrak['max_ball'], 1, ',', ''); ?></td>
                        <td class="text-center align-middle"><?=number_format($control_zavtrak['min_procent'], 1, ',', '');?></td>
                        <td class="text-center align-middle <?if(round($control_zavtrak['sred_procent'],1) >= 30){echo "bg-danger";}?>"><?=number_format($control_zavtrak['sred_procent'], 1, ',', ''); ?></td>
                        <td class="text-center align-middle <?if(round($control_zavtrak['max_procent'],1) >= 30){echo "bg-danger";}?>"><?=number_format($control_zavtrak['max_procent'], 1, ',', '');?></td>
                    <?}?>

                    <?if($control_obed == 'null' || empty($control_obed) || $control_obed['vnutr'] == 0){?>
                        <td colspan="7" class="text-center align-middle <?if(($control_zavtrak == 'null' || empty($control_zavtrak) || $control_zavtrak['vnutr'] == 0) && ($control_obed == 'null' || empty($control_obed) || $control_obed['vnutr'] == 0) && ($control_inoe == 'null' || empty($control_inoe) || $control_inoe['vnutr'] == 0)){ echo 'bg-danger';}?>">Контроль в обед не проводился</td>
                    <?}else{?>
                        <td class="text-center align-middle"><?=$control_obed['vnutr'];?></td>
                        <td class="text-center align-middle"><?=number_format($control_obed['min_ball'], 1, ',', '');?></td>
                        <td class="text-center align-middle"><?=number_format($control_obed['sred_ball'], 1, ',', '');?></td>
                        <td class="text-center align-middle"><?=number_format($control_obed['max_ball'], 1, ',', '');?></td>
                        <td class="text-center align-middle"><?=number_format($control_obed['min_procent'], 1, ',', ''); ?></td>
                        <td class="text-center align-middle <?if(round($control_obed['sred_procent'],1) >= 30){echo "bg-danger";}?>"><?=number_format($control_obed['sred_procent'], 1, ',', ''); ?></td>
                        <td class="text-center align-middle <?if(round($control_obed['max_procent'],1) >= 30){echo "bg-danger";}?>"><?=number_format($control_obed['max_procent'], 1, ',', ''); ?></td>
                    <?}?>


                    <?if($control_inoe == 'null' || empty($control_inoe) || $control_inoe['vnutr'] == 0){?>
                        <td colspan="7" class="text-center align-middle <?if(($control_zavtrak == 'null' || empty($control_zavtrak) || $control_zavtrak['vnutr'] == 0) && ($control_obed == 'null' || empty($control_obed) || $control_obed['vnutr'] == 0) && ($control_inoe == 'null' || empty($control_inoe) || $control_inoe['vnutr'] == 0)){ echo 'bg-danger';}?>">Контроль не проводился</td>
                    <?}else{?>
                        <td class="text-center align-middle"><?=$control_inoe['vnutr'];?></td>
                        <td class="text-center align-middle"><?=number_format($control_inoe['min_ball'], 1, ',', '');?></td>
                        <td class="text-center align-middle"><?=number_format($control_inoe['sred_ball'], 1, ',', '');?></td>
                        <td class="text-center align-middle"><?=number_format($control_inoe['max_ball'], 1, ',', '');?></td>
                        <td class="text-center align-middle"><?=number_format($control_inoe['min_procent'], 1, ',', '');?></td>
                        <td class="text-center align-middle <?if(round($control_inoe['sred_procent'],1) >= 30){echo "bg-danger";}?>"><?=number_format($control_inoe['sred_procent'], 1, ',', '');?></td>
                        <td class="text-center align-middle <?if(round($control_inoe['max_procent'],1) >= 30){echo "bg-danger";}?>"><?=number_format($control_inoe['max_procent'], 1, ',', ''); ?></td>
                    <?}?>
                </tr>
                <?}?>
            <?}?>


            <tr class="<?if($post['field2'] != 0){echo 'table-danger';}?>">
                <td class="" colspan="3"><?=$municipality->name;?>:</td>
                <td class="text-center align-middle"><?=$itog_string['zavtrak_vnutr']; $super_itog_string['zavtrak_vnutr'] = $super_itog_string['zavtrak_vnutr'] + $itog_string['zavtrak_vnutr'];if($itog_string['zavtrak_vnutr'] > 0){$super_itog_string['zavtrak_mun']++;}?></td>
                <td class="text-center align-middle"><?=number_format($itog_string['zavtrak_min_ball'], 1, ',', ''); if($itog_string['zavtrak_min_ball'] < $super_itog_string['zavtrak_min_ball']){$super_itog_string['zavtrak_min_ball'] = $itog_string['zavtrak_min_ball'];}?></td>
                <td class="text-center align-middle"><?if($itog_string['zavtrak_control_count'] > 0){ echo number_format($itog_string['zavtrak_sred_ball']/$itog_string['zavtrak_control_count'], 1, ',', '');$super_itog_string['zavtrak_sred_ball'] = $super_itog_string['zavtrak_sred_ball']+$itog_string['zavtrak_sred_ball']/$itog_string['zavtrak_control_count'];}?></td>
                <td class="text-center align-middle"><?=number_format($itog_string['zavtrak_max_ball'], 1, ',', ''); if($itog_string['zavtrak_max_ball'] > $super_itog_string['zavtrak_max_ball']){$super_itog_string['zavtrak_max_ball'] = $itog_string['zavtrak_max_ball'];}?></td>
                <td class="text-center align-middle"><?=number_format($itog_string['zavtrak_min_procent'], 1, ',', '');if($itog_string['zavtrak_min_procent'] < $super_itog_string['zavtrak_min_procent']){$super_itog_string['zavtrak_min_procent'] = $itog_string['zavtrak_min_procent'];}?></td>
                <td class="text-center align-middle"><?if($itog_string['zavtrak_control_count'] > 0){ echo number_format($itog_string['zavtrak_sred_procent']/$itog_string['zavtrak_control_count'], 1, ',', '');$super_itog_string['zavtrak_sred_procent'] = $super_itog_string['zavtrak_sred_procent']+$itog_string['zavtrak_sred_procent']/$itog_string['zavtrak_control_count'];}?></td>
                <td class="text-center align-middle"><?=number_format($itog_string['zavtrak_max_procent'], 1, ',', ''); if($itog_string['zavtrak_max_procent'] > $super_itog_string['zavtrak_max_procent']){$super_itog_string['zavtrak_max_procent'] = $itog_string['zavtrak_max_procent'];}?></td>

                <td class="text-center align-middle"><?=$itog_string['obed_vnutr']; $super_itog_string['obed_vnutr'] = $super_itog_string['obed_vnutr'] + $itog_string['obed_vnutr'];if($itog_string['obed_vnutr'] > 0){$super_itog_string['obed_mun']++;}?></td>
                <td class="text-center align-middle"><?=number_format($itog_string['obed_min_ball'], 1, ',', '');if($itog_string['obed_min_ball'] < $super_itog_string['obed_min_ball']){$super_itog_string['obed_min_ball'] = $itog_string['obed_min_ball'];}?></td>
                <td class="text-center align-middle"><?if($itog_string['obed_control_count'] > 0){ echo number_format($itog_string['obed_sred_ball']/$itog_string['obed_control_count'], 1, ',', '');$super_itog_string['obed_sred_ball'] = $super_itog_string['obed_sred_ball']+$itog_string['obed_sred_ball']/$itog_string['obed_control_count'];}?></td>
                <td class="text-center align-middle"><?=number_format($itog_string['obed_max_ball'], 1, ',', ''); if($itog_string['obed_max_ball'] > $super_itog_string['obed_max_ball']){$super_itog_string['obed_max_ball'] = $itog_string['obed_max_ball'];}?></td>
                <td class="text-center align-middle"><?=number_format($itog_string['obed_min_procent'], 1, ',', '');if($itog_string['obed_min_procent'] < $super_itog_string['obed_min_procent']){$super_itog_string['obed_min_procent'] = $itog_string['obed_min_procent'];}?></td>
                <td class="text-center align-middle"><?if($itog_string['obed_control_count'] > 0){ echo number_format($itog_string['obed_sred_procent']/$itog_string['obed_control_count'], 1, ',', '');$super_itog_string['obed_sred_procent'] = $super_itog_string['obed_sred_procent']+$itog_string['obed_sred_procent']/$itog_string['obed_control_count'];}?></td>
                <td class="text-center align-middle"><?=number_format($itog_string['obed_max_procent'], 1, ',', ''); if($itog_string['obed_max_procent'] > $super_itog_string['obed_max_procent']){$super_itog_string['obed_max_procent'] = $itog_string['obed_max_procent'];}?></td>

                <td class="text-center align-middle"><?=$itog_string['inoe_vnutr']; $super_itog_string['inoe_vnutr'] = $super_itog_string['inoe_vnutr'] + $itog_string['inoe_vnutr']; if($itog_string['inoe_vnutr'] > 0){$super_itog_string['inoe_mun']++;}?></td>
                <td class="text-center align-middle"><?=number_format($itog_string['inoe_min_ball'], 1, ',', '');if($itog_string['inoe_min_ball'] < $super_itog_string['inoe_min_ball']){$super_itog_string['inoe_min_ball'] = $itog_string['inoe_min_ball'];}?></td>
                <td class="text-center align-middle"><?if($itog_string['inoe_control_count'] > 0){ echo number_format($itog_string['inoe_sred_ball']/$itog_string['inoe_control_count'], 1, ',', '');$super_itog_string['inoe_sred_ball'] = $super_itog_string['inoe_sred_ball']+$itog_string['inoe_sred_ball']/$itog_string['inoe_control_count'];}?></td>
                <td class="text-center align-middle"><?=number_format($itog_string['inoe_max_ball'], 1, ',', ''); if($itog_string['inoe_max_ball'] > $super_itog_string['inoe_max_ball']){$super_itog_string['inoe_max_ball'] = $itog_string['inoe_max_ball'];}?></td>
                <td class="text-center align-middle"><?=number_format($itog_string['inoe_min_procent'], 1, ',', '');if($itog_string['inoe_min_procent'] < $super_itog_string['inoe_min_procent']){$super_itog_string['inoe_min_procent'] = $itog_string['inoe_min_procent'];}?></td>
                <td class="text-center align-middle"><?if($itog_string['inoe_control_count'] > 0){ echo number_format($itog_string['inoe_sred_procent']/$itog_string['inoe_control_count'], 1, ',', '');$super_itog_string['inoe_sred_procent'] = $super_itog_string['inoe_sred_procent']+$itog_string['inoe_sred_procent']/$itog_string['inoe_control_count'];}?></td>
                <td class="text-center align-middle"><?=number_format($itog_string['inoe_max_procent'], 1, ',', ''); if($itog_string['inoe_max_procent'] > $super_itog_string['inoe_max_procent']){$super_itog_string['inoe_max_procent'] = $itog_string['inoe_max_procent'];}?></td>
            </tr>
        <?}?>
        <?if($post['field2'] == 0){?>
            <tr class="table-primary">
                <td class="align-middle" colspan="3"><?=\common\models\Region::findOne($region_id)->name?></td>
                <td class="text-center align-middle"><?=$super_itog_string['zavtrak_vnutr'];  ?></td>
                <td class="text-center align-middle"><?=number_format($super_itog_string['zavtrak_min_ball'], 1, ',', ''); ?></td>
                <td class="text-center align-middle"><?=number_format($super_itog_string['zavtrak_sred_ball']/$super_itog_string['zavtrak_mun'], 1, ',', ''); ?></td>
                <td class="text-center align-middle"><?=number_format($super_itog_string['zavtrak_max_ball'], 1, ',', ''); ?></td>
                <td class="text-center align-middle"><?=number_format($super_itog_string['zavtrak_min_procent'], 1, ',', '');?></td>
                <td class="text-center align-middle"><?=number_format($super_itog_string['zavtrak_sred_procent']/$super_itog_string['zavtrak_mun'], 1, ',', ''); ?></td>
                <td class="text-center align-middle"><?=number_format($super_itog_string['zavtrak_max_procent'], 1, ',', '');?></td>

                <td class="text-center align-middle"><?=$super_itog_string['obed_vnutr'];?></td>
                <td class="text-center align-middle"><?=number_format($super_itog_string['obed_min_ball'], 1, ',', '');?></td>
                <td class="text-center align-middle"><?=number_format($super_itog_string['obed_sred_ball']/$super_itog_string['obed_mun'], 1, ',', '');?></td>
                <td class="text-center align-middle"><?=number_format($super_itog_string['obed_max_ball'], 1, ',', '');?></td>
                <td class="text-center align-middle"><?=number_format($super_itog_string['obed_min_procent'], 1, ',', ''); ?></td>
                <td class="text-center align-middle"><?=number_format($super_itog_string['obed_sred_procent']/$super_itog_string['obed_mun'], 1, ',', ''); ?></td>
                <td class="text-center align-middle"><?=number_format($super_itog_string['obed_max_procent'], 1, ',', ''); ?></td>

                <td class="text-center align-middle"><?=$super_itog_string['inoe_vnutr'];?></td>
                <td class="text-center align-middle"><?=number_format($super_itog_string['inoe_min_ball'], 1, ',', '');?></td>
                <td class="text-center align-middle"><?=number_format($super_itog_string['inoe_sred_ball']/$super_itog_string['inoe_mun'], 1, ',', '');?></td>
                <td class="text-center align-middle"><?=number_format($super_itog_string['inoe_max_ball'], 1, ',', '');?></td>
                <td class="text-center align-middle"><?=number_format($super_itog_string['inoe_min_procent'], 1, ',', '');?></td>
                <td class="text-center align-middle"><?=number_format($super_itog_string['inoe_sred_procent']/$super_itog_string['inoe_mun'], 1, ',', '');?></td>
                <td class="text-center align-middle"><?=number_format($super_itog_string['inoe_max_procent'], 1, ',', ''); ?></td>
            </tr>
        <?}?>
        </tbody>
    </table>


    <div class="text-center mt-3 mb-3">
        <button id="pechat_control" class="btn btn-success">
            <span class="glyphicon glyphicon-download"></span> Скачать отчет в Excel
        </button>
        <p class="text-center text-danger"><b><small>Для крупных городов формирование отчета Excel может занимать некоторое время</small></b></p>
    </div>

<?}?>

<script>
    var ctx = document.getElementById('myChart').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: <?php echo json_encode($rk_title);?>,
            datasets: [{
                label: 'Распределение мероприятий по приемам пищи',
                data: <?php echo json_encode($rk_count);?>,
                backgroundColor: [
                    'rgba(255, 99, 132)',
                    'rgba(255, 206, 86)',
                    'rgba(54, 162, 235)',
                    'rgba(75, 192, 192)',
                    'rgba(153, 102, 255)',
                    'rgba(255, 99, 132)',
                    'rgba(54, 162, 235)',
                    '#900020',

                ],
                borderColor: [

                ],
                borderWidth: 1
            }]
        },
        options: {}
    });
</script>
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
