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

$this->title = 'Результаты проведения мероприятий контроля по переменам';
$this->params['breadcrumbs'][] = $this->title;

$organization_id = Yii::$app->user->identity->organization_id;
$region_id = Organization::findOne($organization_id)->region_id;
$my_mun = Organization::findOne($organization_id)->municipality_id;
if(Yii::$app->user->can('rospotrebnadzor_nutrition') || Yii::$app->user->can('minobr')){
    $municipalities = \common\models\Municipality::find()->where(['region_id' => $region_id])->all();
}else{
    $municipalities = \common\models\Municipality::find()->where(['id' => $my_mun])->all();
}
$municipality_null = array(0 => 'Все муниципальные округа ...');
$municipality_items = ArrayHelper::map($municipalities, 'id', 'name');
$municipality_items = ArrayHelper::merge($municipality_null, $municipality_items);
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
        font-weight: bold;
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

<?php ActiveForm::end(); ?>
<p class="text-center text-danger">Загрузка отчета может занимать до 2х минут</p>


<?if(!empty($post)){?>
    <table class="table table-bordered table-sm" style="width:70%">
        <thead>
        <tr>
            <td rowspan="3">№</td>
            <td rowspan="3">Муниципальное образование</td>
            <td rowspan="3">Наименование общеобразовательной организации</td>
            <td class="text-center align-middle" colspan="16">1я смена</td>
            <td class="text-center align-middle" colspan="16">2я смена</td>
        </tr>
        <tr>
            <td rowspan="1" colspan="4">1я перемена</td>
            <td rowspan="1" colspan="4">2я перемена</td>
            <td rowspan="1" colspan="4">3я перемена</td>
            <td rowspan="1" colspan="4">4я перемена</td>

            <td rowspan="1" colspan="4">1я перемена</td>
            <td rowspan="1" colspan="4">2я перемена</td>
            <td rowspan="1" colspan="4">3я перемена</td>
            <td rowspan="1" colspan="4">4я перемена</td>
        </tr>
        <tr>
            <td>количество</td>
            <td>балл</td>
            <td>% несъед. пищи</td>
            <td>max % несъед. пищи</td>

            <td>количество</td>
            <td>балл</td>
            <td>% несъед. пищи</td>
            <td>max % несъед. пищи</td>

            <td>количество</td>
            <td>балл</td>
            <td>% несъед. пищи</td>
            <td>max % несъед. пищи</td>

            <td>количество</td>
            <td>балл</td>
            <td>% несъед. пищи</td>
            <td>max % несъед. пищи</td>

            <td>количество</td>
            <td>балл</td>
            <td>% несъед. пищи</td>
            <td>max % несъед. пищи</td>

            <td>количество</td>
            <td>балл</td>
            <td>% несъед. пищи</td>
            <td>max % несъед. пищи</td>

            <td>количество</td>
            <td>балл</td>
            <td>% несъед. пищи</td>
            <td>max % несъед. пищи</td>

            <td>количество</td>
            <td>балл</td>
            <td>% несъед. пищи</td>
            <td>max % несъед. пищи</td>
        </tr>

        </thead>
        <tbody>
        <?if($post['menu_id'] == 0){
            $municipalities = \common\models\Municipality::find()->where(['region_id' => $region_id])->all();
        }else{
            $municipalities = \common\models\Municipality::find()->where(['id' => $post['menu_id']])->all();
        }?>

        <? $super_itog_string = [];?>
        <?$super_itog_string[1][1]['max_procent'] = 0?>
        <?$super_itog_string[1][2]['max_procent'] = 0?>
        <?$super_itog_string[1][3]['max_procent'] = 0?>
        <?$super_itog_string[1][4]['max_procent'] = 0?>
        <?$super_itog_string[2][1]['max_procent'] = 0?>
        <?$super_itog_string[2][2]['max_procent'] = 0?>
        <?$super_itog_string[2][3]['max_procent'] = 0?>
        <?$super_itog_string[2][4]['max_procent'] = 0?>
        <?foreach($municipalities as $municipality){ $organizations = Organization::find()->where(['municipality_id' => $municipality->id, 'type_org' => 3])->all();?>
            <? $itog_string = []; $count = 0; $sred = []; $model = new \common\models\AnketParentControl(); foreach ($organizations as $organization) { $count++;?>


                <?$controls_count = \common\models\AnketParentControl::find()->where(['organization_id' => $organization->id, 'peremena' => [1 ,2 ,3 ,4], 'status' => 1])->andWhere([ '>=', 'date', (strtotime('01.09.2021'))])->orderBy(['smena'=> SORT_ASC, 'peremena'=> SORT_ASC])->count();?>
                <?if($controls_count == 0 ){ ?>
                    <?if($post['menu_id'] > 0){?>
                        <tr>
                            <td class="text-center align-middle bg-danger"><?=$count?></td>
                            <td class="text-center align-middle"><?=$municipality->name;?></td>
                            <td class="align-middle"><?echo (empty($organization->short_title)) ? $organization->title : $organization->short_title;?></td>
                            <td colspan="24" class="text-center align-middle text-danger">Контроль не проводился</td>
                        </tr>
                    <?}?>
                <?}else{ $itog_organization = [];
                    $offset = -1; $count_dop = 0; $controls_print_count = $controls_count; $braker = 0; while ($controls_print_count > 0){ $offset++;?>


                        <?if($post['menu_id'] > 0){?>
                            <tr>
                            <td class="text-center align-middle bg-success"><?=$count?></td>
                            <td class="text-center align-middle"><?=$municipality->name;?></td>
                            <td class="align-middle"><?echo (empty($organization->short_title)) ? $organization->title : $organization->short_title;?></td>
                        <?}?>


                        <?$control = \common\models\AnketParentControl::find()->where(['organization_id' => $organization->id, 'smena' => 1, 'peremena' => 1, 'status' => 1])->andWhere([ '>=', 'date', (strtotime('01.09.2021'))])->offset($offset)->one();
                        if(!empty($control)){ $controls_print_count--;
                            $ball = $model->get_ball($control->id); $itog_string[1][1]['count'] = $itog_string[1][1]['count']+1; $itog_string[1][1]['ball'] = $itog_string[1][1]['ball']+$ball;  $itog_organization[1][1]['count'] = $itog_organization[1][1]['count'] + 1; $itog_organization[1][1]['ball'] = $itog_organization[1][1]['ball'] + $ball;
                            $procent = $model->get_result_food($control->id, 'procent');$itog_string[1][1]['procent'] = $itog_string[1][1]['procent']+$procent;    $itog_organization[1][1]['procent'] = $itog_organization[1][1]['procent']+$procent;
                            if($procent > $super_itog_string[1][1]['max_procent']){
                                $super_itog_string[1][1]['max_procent'] = $procent;
                            }
                            ?>
                            <?if($post['menu_id'] > 0){?>
                                <td class="text-center" >1</td>
                                <td class="text-center"><?=$ball; ?></td>
                                <td class="text-center"><?=$procent; ?></td>
                                <td class="text-center"></td>
                            <?}?>
                        <?}else{?>
                            <?if($post['menu_id'] > 0){?>
                                <td class="bg-secondary">-</td>
                                <td class="bg-secondary">-</td>
                                <td class="bg-secondary">-</td>
                                <td class="text-center"></td>
                            <?}?>
                        <?}?>

                        <?$control = \common\models\AnketParentControl::find()->where(['organization_id' => $organization->id, 'smena' => 1, 'peremena' => 2, 'status' => 1])->andWhere([ '>=', 'date', (strtotime('01.09.2021'))])->offset($offset)->one();
                        if(!empty($control)){ $controls_print_count--;
                            $ball = $model->get_ball($control->id);
                            $procent = $model->get_result_food($control->id, 'procent'); $itog_string[1][2]['procent'] = $itog_string[1][2]['procent']+$procent;   $itog_organization[1][2]['procent'] = $itog_organization[1][2]['procent']+$procent;

                            if($procent > $super_itog_string[1][2]['max_procent']){
                                $super_itog_string[1][2]['max_procent'] = $procent;
                            }
                            $itog_string[1][2]['count'] = $itog_string[1][2]['count']+1; $itog_string[1][2]['ball'] = $itog_string[1][2]['ball']+$ball;       $itog_organization[1][2]['count'] = $itog_organization[1][2]['count'] + 1; $itog_organization[1][2]['ball'] = $itog_organization[1][2]['ball'] + $ball;
                            ?>
                            <?if($post['menu_id'] > 0){?>
                                <td class="text-center" >1</td>
                                <td class="text-center"><?=$ball; ?></td>
                                <td class="text-center"><?=$procent; ?></td>
                                <td class="text-center"></td>
                            <?}?>
                        <?}else{?>
                            <?if($post['menu_id'] > 0){?>
                                <td class="bg-secondary">-</td>
                                <td class="bg-secondary">-</td>
                                <td class="bg-secondary">-</td>
                                <td class="text-center"></td>
                            <?}?>
                        <?}?>

                        <?$control = \common\models\AnketParentControl::find()->where(['organization_id' => $organization->id, 'smena' => 1, 'peremena' => 3, 'status' => 1])->andWhere([ '>=', 'date', (strtotime('01.09.2021'))])->offset($offset)->one();
                        if(!empty($control)){ $controls_print_count--;
                            $ball = $model->get_ball($control->id);  $itog_string[1][3]['count'] = $itog_string[1][3]['count']+1; $itog_string[1][3]['ball'] = $itog_string[1][3]['ball']+$ball;       $itog_organization[1][3]['count'] = $itog_organization[1][3]['count']+1; $itog_organization[1][3]['ball'] = $itog_organization[1][3]['ball']+$ball;
                            $procent = $model->get_result_food($control->id, 'procent');  $itog_string[1][3]['procent'] = $itog_string[1][3]['procent']+$procent;       $itog_organization[1][3]['procent'] = $itog_organization[1][3]['procent']+$procent;

                            if($procent > $super_itog_string[1][3]['max_procent']){
                                $super_itog_string[1][3]['max_procent'] = $procent;
                            }
                            ?>

                            <?if($post['menu_id'] > 0){?>
                                <td class="text-center" >1</td>
                                <td class="text-center"><?=$ball; ?></td>
                                <td class="text-center"><?=$procent; ?></td>
                                <td class="text-center"></td>
                            <?}?>
                        <?}else{?>
                            <?if($post['menu_id'] > 0){?>
                                <td class="bg-secondary">-</td>
                                <td class="bg-secondary">-</td>
                                <td class="bg-secondary">-</td>
                                <td class="text-center"></td>
                            <?}?>
                        <?}?>

                        <?$control = \common\models\AnketParentControl::find()->where(['organization_id' => $organization->id, 'smena' => 1, 'peremena' => 4, 'status' => 1])->andWhere([ '>=', 'date', (strtotime('01.09.2021'))])->offset($offset)->one();
                        if(!empty($control)){ $controls_print_count--;
                            $ball = $model->get_ball($control->id);  $itog_string[1][4]['count'] = $itog_string[1][4]['count']+1; $itog_string[1][4]['ball'] = $itog_string[1][4]['ball']+$ball;     $itog_organization[1][4]['count'] = $itog_organization[1][4]['count']+1; $itog_organization[1][4]['ball'] = $itog_organization[1][4]['ball']+$ball;
                            $procent = $model->get_result_food($control->id, 'procent'); $itog_string[1][4]['procent'] = $itog_string[1][4]['procent']+$procent;       $itog_organization[1][4]['procent'] = $itog_organization[1][4]['procent']+$procent;
                            if($procent > $super_itog_string[1][4]['max_procent']){
                                $super_itog_string[1][4]['max_procent'] = $procent;
                            }
                            ?>


                            <?if($post['menu_id'] > 0){?>
                                <td class="text-center" >1</td>
                                <td class="text-center"><?=$ball; ?></td>
                                <td class="text-center"><?=$procent; ?></td>
                                <td class="text-center"></td>
                            <?}?>
                        <?}else{?>
                            <?if($post['menu_id'] > 0){?>
                                <td class="bg-secondary">-</td>
                                <td class="bg-secondary">-</td>
                                <td class="bg-secondary">-</td>
                                <td class="text-center"></td>
                            <?}?>
                        <?}?>



                        <!--                            Переходим ко второй смене-->
                        <?$control = \common\models\AnketParentControl::find()->where(['organization_id' => $organization->id, 'smena' => 2, 'peremena' => 1, 'status' => 1])->andWhere([ '>=', 'date', (strtotime('01.09.2021'))])->offset($offset)->one();
                        if(!empty($control)){ $controls_print_count--;
                            $ball = $model->get_ball($control->id);  $itog_string[2][1]['count'] = $itog_string[2][1]['count']+1; $itog_string[2][1]['ball'] = $itog_string[2][1]['ball']+$ball;        $itog_organization[2][1]['count'] = $itog_organization[2][1]['count']+1; $itog_organization[2][1]['ball'] = $itog_organization[2][1]['ball']+$ball;
                            $procent = $model->get_result_food($control->id, 'procent'); $itog_string[2][1]['procent'] = $itog_string[2][1]['procent']+$procent;       $itog_organization[2][1]['procent'] = $itog_organization[2][1]['procent']+$procent;

                            if($procent > $super_itog_string[2][1]['max_procent']){
                                $super_itog_string[2][1]['max_procent'] = $procent;
                            }
                            ?>

                            <?if($post['menu_id'] > 0){?>
                                <td class="text-center" >1</td>
                                <td class="text-center"><?=$ball; ?></td>
                                <td class="text-center"><?=$procent; ?></td>
                                <td class="text-center"></td>
                            <?}?>
                        <?}else{?>
                            <?if($post['menu_id'] > 0){?>
                                <td class="bg-secondary">-</td>
                                <td class="bg-secondary">-</td>
                                <td class="bg-secondary">-</td>
                                <td class="text-center"></td>
                            <?}?>
                        <?}?>

                        <?$control = \common\models\AnketParentControl::find()->where(['organization_id' => $organization->id, 'smena' => 2, 'peremena' => 2, 'status' => 1])->andWhere([ '>=', 'date', (strtotime('01.09.2021'))])->offset($offset)->one();
                        if(!empty($control)){ $controls_print_count--;
                            $ball = $model->get_ball($control->id);  $itog_string[2][2]['count'] = $itog_string[2][2]['count']+1; $itog_string[2][2]['ball'] = $itog_string[2][2]['ball']+$ball;       $itog_organization[2][2]['count'] = $itog_organization[2][2]['count']+1; $itog_organization[2][2]['ball'] = $itog_organization[2][2]['ball']+$ball;
                            $procent = $model->get_result_food($control->id, 'procent');  $itog_string[2][2]['procent'] = $itog_string[2][2]['procent']+$procent;      $itog_organization[2][2]['procent'] = $itog_organization[2][2]['procent']+$procent;

                            if($procent > $super_itog_string[2][2]['max_procent']){
                                $super_itog_string[2][2]['max_procent'] = $procent;
                            }
                            ?>
                            <?if($post['menu_id'] > 0){?>
                                <td class="text-center" >1</td>
                                <td class="text-center"><?=$ball; ?></td>
                                <td class="text-center"><?=$procent; ?></td>
                                <td class="text-center"></td>
                            <?}?>
                        <?}else{?>
                            <?if($post['menu_id'] > 0){?>
                                <td class="bg-secondary">-</td>
                                <td class="bg-secondary">-</td>
                                <td class="bg-secondary">-</td>
                                <td class="text-center"></td>
                            <?}?>
                        <?}?>

                        <?$control = \common\models\AnketParentControl::find()->where(['organization_id' => $organization->id, 'smena' => 2, 'peremena' => 3, 'status' => 1])->andWhere([ '>=', 'date', (strtotime('01.09.2021'))])->offset($offset)->one();
                        if(!empty($control)){ $controls_print_count--;
                            $ball = $model->get_ball($control->id);  $itog_string[2][3]['count'] = $itog_string[2][3]['count']+1; $itog_string[2][3]['ball'] = $itog_string[2][3]['ball']+$ball;       $itog_organization[2][3]['count'] = $itog_organization[2][3]['count']+1; $itog_organization[2][3]['ball'] = $itog_organization[2][3]['ball']+$ball;
                            $procent = $model->get_result_food($control->id, 'procent');  $itog_string[2][3]['procent'] = $itog_string[2][3]['procent']+$procent;       $itog_organization[2][3]['procent'] = $itog_organization[2][3]['procent']+$procent;

                            if($procent > $super_itog_string[2][3]['max_procent']){
                                $super_itog_string[2][3]['max_procent'] = $procent;
                            }

                            ?>
                            <?if($post['menu_id'] > 0){?>
                                <td class="text-center" >1</td>
                                <td class="text-center"><?=$ball; ?></td>
                                <td class="text-center"><?=$procent; ?></td>
                                <td class="text-center"></td>
                            <?}?>
                        <?}else{?>
                            <?if($post['menu_id'] > 0){?>
                                <td class="bg-secondary">-</td>
                                <td class="bg-secondary">-</td>
                                <td class="bg-secondary">-</td>
                                <td class="text-center"></td>
                            <?}?>
                        <?}?>

                        <?$control = \common\models\AnketParentControl::find()->where(['organization_id' => $organization->id, 'smena' => 2, 'peremena' => 4, 'status' => 1])->andWhere([ '>=', 'date', (strtotime('01.09.2021'))])->offset($offset)->one();
                        if(!empty($control)){ $controls_print_count--;
                            $ball = $model->get_ball($control->id); $itog_string[2][4]['count'] = $itog_string[2][4]['count']+1; $itog_string[2][4]['ball'] = $itog_string[2][4]['ball']+$ball;
                            $procent = $model->get_result_food($control->id, 'procent'); $itog_string[2][4]['procent'] = $itog_string[2][4]['procent']+$procent;

                            if($procent > $super_itog_string[2][4]['max_procent']){
                                $super_itog_string[2][4]['max_procent'] = $procent;
                            }
                            ?>
                            <?if($post['menu_id'] > 0){?>
                                <td class="text-center" >1</td>
                                <td class="text-center"><?=$ball; ?></td>
                                <td class="text-center"><?=$procent; ?></td>
                                <td class="text-center"></td>
                            <?}?>
                        <?}else{?>
                            <?if($post['menu_id'] > 0){?>
                                <td class="bg-secondary">-</td>
                                <td class="bg-secondary">-</td>
                                <td class="bg-secondary">-</td>
                                <td class="text-center"></td>
                            <?}?>
                        <?}?>

                        </tr>
                    <?}?>

                    <?if($post['menu_id'] > 0){?>
                        <tr class="bg-warning">
                            <td colspan="3" style="font-size: 12px;">Итого по <?echo (empty($organization->short_title)) ? $organization->title : $organization->short_title;?></td>
                            <td class="text-center align-middle"><?=$itog_organization[1][1]['count']?></td>
                            <td class="text-center align-middle"><?if(!empty($itog_organization[1][1]['count'])){ echo round($itog_organization[1][1]['ball']/$itog_organization[1][1]['count'],1);}?></td>
                            <td class="text-center align-middle"><?if(!empty($itog_organization[1][1]['count'])){ echo round($itog_organization[1][1]['procent']/$itog_organization[1][1]['count'],1);}?></td>


                            <td class="text-center align-middle"><?=$itog_organization[1][2]['count']?></td>
                            <td class="text-center align-middle"><?if(!empty($itog_organization[1][2]['count'])){ echo round($itog_organization[1][2]['ball']/$itog_organization[1][2]['count'],1);}?></td>
                            <td class="text-center align-middle"><?if(!empty($itog_organization[1][2]['count'])){ echo round($itog_organization[1][2]['procent']/$itog_organization[1][2]['count'],1);}?></td>


                            <td class="text-center align-middle"><?=$itog_organization[1][3]['count']?></td>
                            <td class="text-center align-middle"><?if(!empty($itog_organization[1][3]['count'])){ echo round($itog_organization[1][3]['ball']/$itog_organization[1][3]['count'],1);}?></td>
                            <td class="text-center align-middle"><?if(!empty($itog_organization[1][3]['count'])){ echo round($itog_organization[1][3]['procent']/$itog_organization[1][3]['count'],1);}?></td>


                            <td class="text-center align-middle"><?=$itog_organization[1][4]['count']?></td>
                            <td class="text-center align-middle"><?if(!empty($itog_organization[1][4]['count'])){ echo round($itog_organization[1][4]['ball']/$itog_organization[1][4]['count'],1);}?></td>
                            <td><?if(!empty($itog_organization[1][4]['count'])){ echo round($itog_organization[1][4]['procent']/$itog_organization[1][4]['count'],1);}?></td>



                            <td class="text-center align-middle"><?=$itog_organization[2][1]['count']?></td>
                            <td class="text-center align-middle"><?if(!empty($itog_organization[2][1]['count'])){ echo round($itog_organization[2][1]['ball']/$itog_organization[2][1]['count'],1);}?></td>
                            <td class="text-center align-middle"><?if(!empty($itog_organization[2][1]['count'])){ echo round($itog_organization[2][1]['procent']/$itog_organization[2][1]['count'],1);}?></td>


                            <td class="text-center align-middle"><?=$itog_organization[2][2]['count']?></td>
                            <td class="text-center align-middle"><?if(!empty($itog_organization[2][2]['count'])){ echo round($itog_organization[2][2]['ball']/$itog_organization[2][2]['count'],1);}?></td>
                            <td class="text-center align-middle"><?if(!empty($itog_organization[2][2]['count'])){ echo round($itog_organization[2][2]['procent']/$itog_organization[2][2]['count'],1);}?></td>


                            <td class="text-center align-middle"><?=$itog_organization[2][3]['count']?></td>
                            <td class="text-center align-middle"><?if(!empty($itog_organization[2][3]['count'])){ echo round($itog_organization[2][3]['ball']/$itog_organization[2][3]['count'],1);}?></td>
                            <td class="text-center align-middle"><?if(!empty($itog_organization[2][3]['count'])){ echo round($itog_organization[2][3]['procent']/$itog_organization[2][3]['count'],1);}?></td>


                            <td class="text-center align-middle"><?=$itog_organization[2][4]['count']?></td>
                            <td class="text-center align-middle"><?if(!empty($itog_organization[2][4]['count'])){ echo round($itog_organization[2][4]['ball']/$itog_organization[2][4]['count'],1);}?></td>
                            <td class="text-center align-middle"><?if(!empty($itog_organization[2][4]['count'])){ echo round($itog_organization[2][4]['procent']/$itog_organization[2][4]['count'],1);}?></td>

                        </tr>
                    <?}?>
                <?}?>



            <?}?>


            <tr class="table-danger">
                <td class="" colspan="3">Итого <?=$municipality->name;?>:</td>
                <?if(!empty($itog_string[1][1]['count'])){?>
                    <td class="text-center align-middle"><?=$itog_string[1][1]['count']?></td>
                    <td class="text-center align-middle"><?=round($itog_string[1][1]['ball']/$itog_string[1][1]['count'],1);?></td>
                    <td class="text-center align-middle"><?=round($itog_string[1][1]['procent']/$itog_string[1][1]['count'],1);?></td>
                    <td class="text-center"></td>
                <?}else{?>
                    <td class="bg-secondary"></td>
                    <td class="bg-secondary"></td>
                    <td class="bg-secondary"></td>
                    <td class="text-center"></td>
                <?}?>

                <?if(!empty($itog_string[1][2]['count'])){?>
                    <td class="text-center align-middle"><?=$itog_string[1][2]['count']?></td>
                    <td class="text-center align-middle"><?=round($itog_string[1][2]['ball']/$itog_string[1][2]['count'],1);?></td>
                    <td class="text-center align-middle"><?=round($itog_string[1][2]['procent']/$itog_string[1][2]['count'],1);?></td>
                    <td class="text-center"></td>
                <?}else{?>
                    <td class="bg-secondary"></td>
                    <td class="bg-secondary"></td>
                    <td class="bg-secondary"></td>
                    <td class="text-center"></td>
                <?}?>

                <?if(!empty($itog_string[1][3]['count'])){?>
                    <td class="text-center align-middle"><?=$itog_string[1][3]['count']?></td>
                    <td class="text-center align-middle"><?=round($itog_string[1][3]['ball']/$itog_string[1][3]['count'],1);?></td>
                    <td class="text-center align-middle"><?=round($itog_string[1][3]['procent']/$itog_string[1][3]['count'],1);?></td>
                    <td class="text-center"></td>
                <?}else{?>
                    <td class="bg-secondary"></td>
                    <td class="bg-secondary"></td>
                    <td class="bg-secondary"></td>
                    <td class="text-center"></td>
                <?}?>

                <?if(!empty($itog_string[1][4]['count'])){?>
                    <td class="text-center align-middle"><?=$itog_string[1][4]['count']?></td>
                    <td class="text-center align-middle"><?=round($itog_string[1][4]['ball']/$itog_string[1][4]['count'],1);?></td>
                    <td class="text-center align-middle"><?=round($itog_string[1][4]['procent']/$itog_string[1][4]['count'],1);?></td>
                    <td class="text-center"></td>
                <?}else{?>
                    <td class="bg-secondary"></td>
                    <td class="bg-secondary"></td>
                    <td class="bg-secondary"></td>
                    <td class="text-center"></td>
                <?}?>

                <?if(!empty($itog_string[2][1]['count'])){?>
                    <td class="text-center align-middle"><?=$itog_string[2][1]['count']?></td>
                    <td class="text-center align-middle"><?=round($itog_string[2][1]['ball']/$itog_string[2][1]['count'],1);?></td>
                    <td class="text-center align-middle"><?=round($itog_string[2][1]['procent']/$itog_string[2][1]['count'],1);?></td>
                    <td class="text-center"></td>
                <?}else{?>
                    <td class="bg-secondary"></td>
                    <td class="bg-secondary"></td>
                    <td class="bg-secondary"></td>
                    <td class="text-center"></td>
                <?}?>

                <?if(!empty($itog_string[2][2]['count'])){?>
                    <td class="text-center align-middle"><?=$itog_string[2][2]['count']?></td>
                    <td class="text-center align-middle"><?=round($itog_string[2][2]['ball']/$itog_string[2][2]['count'],1);?></td>
                    <td class="text-center align-middle"><?=round($itog_string[2][2]['procent']/$itog_string[2][2]['count'],1);?></td>
                    <td class="text-center"></td>
                <?}else{?>
                    <td class="bg-secondary"></td>
                    <td class="bg-secondary"></td>
                    <td class="bg-secondary"></td>
                    <td class="text-center"></td>
                <?}?>


                <?if(!empty($itog_string[2][3]['count'])){?>
                    <td class="text-center align-middle"><?=$itog_string[2][3]['count']?></td>
                    <td class="text-center align-middle"><?=round($itog_string[2][3]['ball']/$itog_string[2][3]['count'],1);?></td>
                    <td class="text-center align-middle"><?=round($itog_string[2][3]['procent']/$itog_string[2][3]['count'],1);?></td>
                    <td class="text-center"></td>
                <?}else{?>
                    <td class="bg-secondary"></td>
                    <td class="bg-secondary"></td>
                    <td class="bg-secondary"></td>
                    <td class="text-center"></td>
                <?}?>

                <?if(!empty($itog_string[2][4]['count'])){?>
                    <td class="text-center align-middle"><?=$itog_string[2][4]['count']?></td>
                    <td class="text-center align-middle"><?=round($itog_string[2][4]['ball']/$itog_string[2][4]['count'],1);?></td>
                    <td class="text-center align-middle"><?=round($itog_string[2][4]['procent']/$itog_string[2][4]['count'],1);?></td>
                    <td class="text-center"></td>
                <?}else{?>
                    <td class="bg-secondary"></td>
                    <td class="bg-secondary"></td>
                    <td class="bg-secondary"></td>
                    <td class="text-center"></td>
                <?}?>

            </tr>

            <?
            //super itog
            $super_itog_string[1][1]['count'] = $super_itog_string[1][1]['count'] + $itog_string[1][1]['count'];
            $super_itog_string[1][2]['count'] = $super_itog_string[1][2]['count'] + $itog_string[1][2]['count'];
            $super_itog_string[1][3]['count'] = $super_itog_string[1][3]['count'] + $itog_string[1][3]['count'];
            $super_itog_string[1][4]['count'] = $super_itog_string[1][4]['count'] + $itog_string[1][4]['count'];

            $super_itog_string[2][1]['count'] = $super_itog_string[2][1]['count'] + $itog_string[2][1]['count'];
            $super_itog_string[2][2]['count'] = $super_itog_string[2][2]['count'] + $itog_string[2][2]['count'];
            $super_itog_string[2][3]['count'] = $super_itog_string[2][3]['count'] + $itog_string[2][3]['count'];
            $super_itog_string[2][4]['count'] = $super_itog_string[2][4]['count'] + $itog_string[2][4]['count'];




            $super_itog_string[1][1]['ball'] = $super_itog_string[1][1]['ball'] + (empty($itog_string[1][1]['count']) ? 0 : round($itog_string[1][1]['ball'],1));
            $super_itog_string[1][2]['ball'] = $super_itog_string[1][2]['ball'] + (empty($itog_string[1][2]['count']) ? 0 : round($itog_string[1][2]['ball'],1));
            $super_itog_string[1][3]['ball'] = $super_itog_string[1][3]['ball'] + (empty($itog_string[1][3]['count']) ? 0 : round($itog_string[1][3]['ball'],1));
            $super_itog_string[1][4]['ball'] = $super_itog_string[1][4]['ball'] + (empty($itog_string[1][4]['count']) ? 0 : round($itog_string[1][4]['ball'],1));

            $super_itog_string[2][1]['ball'] = $super_itog_string[2][1]['ball'] + (empty($itog_string[2][1]['count']) ? 0 : round($itog_string[2][1]['ball'],1));
            $super_itog_string[2][2]['ball'] = $super_itog_string[2][2]['ball'] + (empty($itog_string[2][2]['count']) ? 0 : round($itog_string[2][2]['ball'],1));
            $super_itog_string[2][3]['ball'] = $super_itog_string[2][3]['ball'] + (empty($itog_string[2][3]['count']) ? 0 : round($itog_string[2][3]['ball'],1));
            $super_itog_string[2][4]['ball'] = $super_itog_string[2][4]['ball'] + (empty($itog_string[2][4]['count']) ? 0 : round($itog_string[2][4]['ball'],1));




            $super_itog_string[1][1]['procent'] = $super_itog_string[1][1]['procent'] + (empty($itog_string[1][1]['count']) ? 0 : round($itog_string[1][1]['procent'],1));
            $super_itog_string[1][2]['procent'] = $super_itog_string[1][2]['procent'] + (empty($itog_string[1][2]['count']) ? 0 : round($itog_string[1][2]['procent'],1));
            $super_itog_string[1][3]['procent'] = $super_itog_string[1][3]['procent'] + (empty($itog_string[1][3]['count']) ? 0 : round($itog_string[1][3]['procent'],1));
            $super_itog_string[1][4]['procent'] = $super_itog_string[1][4]['procent'] + (empty($itog_string[1][4]['count']) ? 0 : round($itog_string[1][4]['procent'],1));

            $super_itog_string[2][1]['procent'] = $super_itog_string[2][1]['procent'] + (empty($itog_string[2][1]['count']) ? 0 : round($itog_string[2][1]['procent'],1));
            $super_itog_string[2][2]['procent'] = $super_itog_string[2][2]['procent'] + (empty($itog_string[2][2]['count']) ? 0 : round($itog_string[2][2]['procent'],1));
            $super_itog_string[2][3]['procent'] = $super_itog_string[2][3]['procent'] + (empty($itog_string[2][3]['count']) ? 0 : round($itog_string[2][3]['procent'],1));
            $super_itog_string[2][4]['procent'] = $super_itog_string[2][4]['procent'] + (empty($itog_string[2][4]['count']) ? 0 : round($itog_string[2][4]['procent'],1));






            ?>
        <?}?>


        <?if($post['menu_id'] == 0){?>
            <tr class="table-primary">
                <td class="" colspan="3">ИТОГО ПО СУБЪЕКТУ:</td>

                <td class="text-center align-middle"><?=$super_itog_string[1][1]['count']?></td>
                <td class="text-center align-middle"><?if(!empty($super_itog_string[1][1]['count'])){ echo round($super_itog_string[1][1]['ball']/$super_itog_string[1][1]['count'],1);}?></td>
                <td class="text-center align-middle"><?if(!empty($super_itog_string[1][1]['count'])){ echo round($super_itog_string[1][1]['procent']/$super_itog_string[1][1]['count'],1);}?></td>
                <td class="text-center align-middle"><?=round($super_itog_string[1][1]['max_procent'],1)?></td>


                <td class="text-center align-middle"><?=$super_itog_string[1][2]['count']?></td>
                <td class="text-center align-middle"><?if(!empty($super_itog_string[1][2]['count'])){ echo round($super_itog_string[1][2]['ball']/$super_itog_string[1][2]['count'],1);}?></td>
                <td class="text-center align-middle"><?if(!empty($super_itog_string[1][2]['count'])){ echo round($super_itog_string[1][2]['procent']/$super_itog_string[1][2]['count'],1);}?></td>
                <td class="text-center align-middle"><?=round($super_itog_string[1][2]['max_procent'],1)?></td>

                <td class="text-center align-middle"><?=$super_itog_string[1][3]['count']?></td>
                <td class="text-center align-middle"><?if(!empty($super_itog_string[1][3]['count'])){ echo round($super_itog_string[1][3]['ball']/$super_itog_string[1][3]['count'],1);}?></td>
                <td class="text-center align-middle"><?if(!empty($super_itog_string[1][3]['count'])){ echo round($super_itog_string[1][3]['procent']/$super_itog_string[1][3]['count'],1);}?></td>
                <td class="text-center align-middle"><?=round($super_itog_string[1][3]['max_procent'],1)?></td>

                <td class="text-center align-middle"><?=$super_itog_string[1][4]['count']?></td>
                <td class="text-center align-middle"><?if(!empty($super_itog_string[1][4]['count'])){ echo round($super_itog_string[1][4]['ball']/$super_itog_string[1][4]['count'],1);}?></td>
                <td class="text-center"><?if(!empty($super_itog_string[1][4]['count'])){ echo round($super_itog_string[1][4]['procent']/$super_itog_string[1][4]['count'],1);}?></td>
                <td class="text-center align-middle"><?=round($super_itog_string[1][4]['max_procent'],1)?></td>


                <td class="text-center align-middle"><?=$super_itog_string[2][1]['count']?></td>
                <td class="text-center align-middle"><?if(!empty($super_itog_string[2][1]['count'])){ echo round($super_itog_string[2][1]['ball']/$super_itog_string[2][1]['count'],1);}?></td>
                <td class="text-center align-middle"><?if(!empty($super_itog_string[2][1]['count'])){ echo round($super_itog_string[2][1]['procent']/$super_itog_string[2][1]['count'],1);}?></td>
                <td class="text-center align-middle"><?=round($super_itog_string[2][1]['max_procent'],1)?></td>

                <td class="text-center align-middle"><?=$super_itog_string[2][2]['count']?></td>
                <td class="text-center align-middle"><?if(!empty($super_itog_string[2][2]['count'])){ echo round($super_itog_string[2][2]['ball']/$super_itog_string[2][2]['count'],1);}?></td>
                <td class="text-center align-middle"><?if(!empty($super_itog_string[2][2]['count'])){ echo round($super_itog_string[2][2]['procent']/$super_itog_string[2][2]['count'],1);}?></td>
                <td class="text-center align-middle"><?=round($super_itog_string[2][2]['max_procent'],1)?></td>

                <td class="text-center align-middle"><?=$super_itog_string[2][3]['count']?></td>
                <td class="text-center align-middle"><?if(!empty($super_itog_string[2][3]['count'])){ echo round($super_itog_string[2][3]['ball']/$super_itog_string[2][3]['count'],1);}?></td>
                <td class="text-center align-middle"><?if(!empty($super_itog_string[2][3]['count'])){ echo round($super_itog_string[2][3]['procent']/$super_itog_string[2][3]['count'],1);}?></td>
                <td class="text-center align-middle"><?=round($super_itog_string[2][3]['max_procent'],1)?></td>

                <td class="text-center align-middle"><?=$super_itog_string[2][4]['count']?></td>
                <td class="text-center align-middle"><?if(!empty($super_itog_string[2][4]['count'])){ echo round($super_itog_string[2][4]['ball']/$super_itog_string[2][4]['count'],1);}?></td>
                <td class="text-center align-middle"><?if(!empty($super_itog_string[2][4]['count'])){ echo round($super_itog_string[2][4]['procent']/$super_itog_string[2][4]['count'],1);}?></td>
                <td class="text-center align-middle"><?=round($super_itog_string[2][4]['max_procent'],1)?></td>
            </tr>
        <?}?>
        </tbody>
    </table><br><br><br>
<?}?>

<?
$script = <<< JS



$( ".beforeload" ).click(function() {
  $(".beforeload").css('display','none');
  $(".load").css('display','block');
  
});


JS;

$this->registerJs($script, yii\web\View::POS_READY);
?>
