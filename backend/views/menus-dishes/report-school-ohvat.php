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

$this->title = 'Отчет по школам';
$this->params['breadcrumbs'][] = $this->title;


$organization_id = Yii::$app->user->identity->organization_id;
$region_id = Organization::findOne($organization_id)->region_id;

$municipalities = \common\models\Municipality::find()->where(['region_id' => 49])->all();
$municipality_null = array(0 => 'Все муниципальные округа ...');
$municipality_items = ArrayHelper::map($municipalities, 'id', 'name');
$municipality_items = ArrayHelper::merge($municipality_null, $municipality_items);

if(!empty($post)){
    $params_mun = ['class' => 'form-control', 'options' => [$post['menu_id'] => ['Selected' => true]]];
    $params_cycle = ['class' => 'form-control', 'options' => [$post['cycle'] => ['Selected' => true]]];
    $params_chemistry = ['class' => 'form-control', 'options' => [$post['days_id'] => ['Selected' => true]]];

    $count_my_days = MenusDays::find()->where(['menu_id' => $post['menu_id']])->count();
    if($post['cycle'] == 0){
        $count_my_days = $count_my_days * $menu_cycle_count;
    }


}

?>
<style>
    th, td {
        border: 1px solid black!important;
        color: black;

    }
    thead, th {
        background-color: #ede8b9;
        font-size: 15px;
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




<?if(!empty($post)){?>
    <table class="table table-bordered table-sm">
        <thead>
        <tr>
            <td rowspan="3">№</td>
            <td rowspan="3">Муниципальное образование</td>
            <td colspan="30">количество школьников</td>

        </tr>
        <tr>



            <td colspan="5" class="text-center">СД</td>
            <td colspan="5" class="text-center">целиакия</td>
            <td colspan="5" class="text-center">муковисцедоз</td>
            <td colspan="5" class="text-center">ФКУ</td>
            <td colspan="5" class="text-center">ОВЗ</td>
            <td colspan="5" class="text-center">ПА</td>

        </tr>
        <tr>
            <td>1</td>
            <td>2</td>
            <td>3</td>
            <td>4</td>
            <td>Кол. детей для которых нет меню</td>

            <td>1</td>
            <td>2</td>
            <td>3</td>
            <td>4</td>
            <td>Кол. детей для которых нет меню</td>


            <td>1</td>
            <td>2</td>
            <td>3</td>
            <td>4</td>
            <td>Кол. детей для которых нет меню</td>

            <td>1</td>
            <td>2</td>
            <td>3</td>
            <td>4</td>
            <td>Кол. детей для которых нет меню</td>

            <td>1</td>
            <td>2</td>
            <td>3</td>
            <td>4</td>
            <td>Кол. детей для которых нет меню</td>

            <td>1</td>
            <td>2</td>
            <td>3</td>
            <td>4</td>
            <td>Кол. детей для которых нет меню</td>

        </tr>

        </thead>
        <tbody>
        <? $itog_string = []; $count = 0; $sred = []; foreach ($municipalities as $municipality) { $count++; $cs_mas = []; ?>
            <?$organizations = Organization::find()->where(['municipality_id' => $municipality->id])->all(); $organization_ids = ArrayHelper::map($organizations, 'id', 'id');?>
           <? $characters_studies = \common\models\CharactersStudy::find()->where(['organization_id' => $organization_ids, 'class_number' => [1,2,3,4]])->all();?>


            <?foreach ($organizations as $organization){
                $sahar = \common\models\Menus::find()->where(['organization_id' => $organization->id, 'age_info_id' => 6, 'feeders_characters_id' => 5])->count();
                $ovz = \common\models\Menus::find()->where(['organization_id' => $organization->id, 'age_info_id' => 6, 'feeders_characters_id' => 4])->count();
                $cialic = \common\models\Menus::find()->where(['organization_id' => $organization->id, 'age_info_id' => 6, 'feeders_characters_id' => 6])->count();
                $allergy = \common\models\Menus::find()->where(['organization_id' => $organization->id, 'age_info_id' => 6, 'feeders_characters_id' => 10])->count();

                $characters_studies_orgs = \common\models\CharactersStudy::find()->where(['organization_id' => $organization->id, 'class_number' => [1, 2, 3, 4]])->all();
                foreach($characters_studies_orgs as $ch_org){

                if($sahar == 0){
                    $cs_mas['sahar']['no'] = $cs_mas['sahar']['no']+ $ch_org->sahar;
                    $itog_string['sahar']['no'] = $itog_string['sahar']['no'] + $ch_org->sahar;
                }
                if($ovz == 0)
                {
                    $cs_mas['ovz']['no'] = $cs_mas['ovz']['no'] + $ch_org->ovz;
                    $itog_string['ovz']['no'] = $itog_string['ovz']['no'] + $ch_org->ovz;
                }

                if($cialic == 0)
                {
                    $cs_mas['cialic']['no'] = $cs_mas['cialic']['no'] + $ch_org->cialic;
                    $itog_string['cialic']['no'] = $itog_string['cialic']['no'] + $ch_org->cialic;
                }

                if($allergy == 0)
                {
                    $cs_mas['allergy']['no'] = $cs_mas['allergy']['no'] + $ch_org->allergy;
                    $itog_string['allergy']['no'] = $itog_string['allergy']['no'] + $ch_org->allergy;
                }

                //if($sahar == 0){
                $cs_mas['mukovis']['no'] = $cs_mas['mukovis']['no']  + $ch_org->mukovis;
                    $itog_string['mukovis']['no'] = $itog_string['mukovis']['no'] + $ch_org->mukovis;

                //if($sahar == 0){
                $cs_mas['fenilketon']['no']= $cs_mas['fenilketon']['no']  + $ch_org->fenilketon;
                    $itog_string['fenilketon']['no'] = $itog_string['fenilketon']['no'] + $ch_org->fenilketon;

                }
                }?>
            <?
            $number = [];


            foreach ($characters_studies as $characters_study){
                //$number[$characters_study->smena."_".$characters_study->number_peremena] = $number[$characters_study->smena."_".$characters_study->number_peremena] + $characters_study->count_ochno;

                if($characters_study->count_ochno > 0){
                    $cs_mas['count_kid'] = $cs_mas['count_kid'] + $characters_study->count;
                }

                //$cs_mas['count_kid_ochno']['class_number'] = $cs_mas['count_kid_ochno'] + $characters_study->count_ochno;

                $cs_mas['sahar'][$characters_study->class_number] = $cs_mas['sahar'][$characters_study->class_number]+ $characters_study->sahar;
                $cs_mas['ovz'][$characters_study->class_number] = $cs_mas['ovz'][$characters_study->class_number]  + $characters_study->ovz;
                $cs_mas['cialic'][$characters_study->class_number] = $cs_mas['cialic'][$characters_study->class_number] + $characters_study->cialic;
                $cs_mas['allergy'][$characters_study->class_number] = $cs_mas['allergy'][$characters_study->class_number]  + $characters_study->allergy;
                $cs_mas['mukovis'][$characters_study->class_number] = $cs_mas['mukovis'][$characters_study->class_number]  + $characters_study->mukovis;
                $cs_mas['fenilketon'][$characters_study->class_number] = $cs_mas['fenilketon'][$characters_study->class_number]  + $characters_study->fenilketon;


//                $cs_mas['otkaz'] = $cs_mas['otkaz'] + $characters_study->otkaz_sahar + $characters_study->otkaz_cialic + $characters_study->otkaz_allergy + $characters_study->otkaz_inoe;
//                $cs_mas['otkaz_allergy'] = $cs_mas['otkaz_allergy'] + $characters_study->otkaz_allergy;
//                $cs_mas['otkaz_cialic'] = $cs_mas['otkaz_cialic'] + $characters_study->otkaz_cialic;
//                $cs_mas['otkaz_sahar'] = $cs_mas['otkaz_sahar'] + $characters_study->otkaz_sahar;
//
//                $cs_mas['otkaz_ovz'] = $cs_mas['otkaz_ovz'] + $characters_study->otkaz_ovz;
//                $cs_mas['otkaz_mukovis'] = $cs_mas['otkaz_mukovis'] + $characters_study->otkaz_mukovis;
//                $cs_mas['otkaz_fenilketon'] = $cs_mas['otkaz_fenilketon'] + $characters_study->otkaz_fenilketon;
            }
            if(empty($cs_mas['otkaz'])){
                $cs_mas['otkaz'] = 0;
            }
            if(!empty($number)){
                $pita = max($number);
            }else{
                $pita = 0;
            }


            ?>



            <tr>
                <td class="text-center align-middle"><?=$count?></td>
                <td class="text-center align-middle"><?=$municipality->name?></td>
                    <td class="text-center align-middle <? if(empty($cs_mas['sahar'][1])){echo 'bg-secondary';}?>"><?= $cs_mas['sahar'][1]; $itog_string['sahar'][1] = $itog_string['sahar'][1] + $cs_mas['sahar'][1];?></td>
                    <td class="text-center align-middle <? if(empty($cs_mas['sahar'][2])){echo 'bg-secondary';}?>"><?= $cs_mas['sahar'][2]; $itog_string['sahar'][2] = $itog_string['sahar'][2] + $cs_mas['sahar'][2];?></td>
                    <td class="text-center align-middle <? if(empty($cs_mas['sahar'][3])){echo 'bg-secondary';}?>"><?= $cs_mas['sahar'][3]; $itog_string['sahar'][3] = $itog_string['sahar'][3] + $cs_mas['sahar'][3];?></td>
                    <td class="text-center align-middle <? if(empty($cs_mas['sahar'][4])){echo 'bg-secondary';}?>"><?= $cs_mas['sahar'][4]; $itog_string['sahar'][4] = $itog_string['sahar'][4] + $cs_mas['sahar'][4];?></td>
                    <td class="text-center align-middle"><?=$cs_mas['sahar']['no']?></td>

                    <td class="text-center align-middle <? if(empty($cs_mas['cialic'][1])){echo 'bg-secondary';}?>"><?= $cs_mas['cialic'][1]; $itog_string['cialic'][1] = $itog_string['cialic'][1] + $cs_mas['cialic'][1];?></td>
                    <td class="text-center align-middle <? if(empty($cs_mas['cialic'][2])){echo 'bg-secondary';}?>"><?= $cs_mas['cialic'][2]; $itog_string['cialic'][2] = $itog_string['cialic'][2] + $cs_mas['cialic'][2];?></td>
                    <td class="text-center align-middle <? if(empty($cs_mas['cialic'][3])){echo 'bg-secondary';}?>"><?= $cs_mas['cialic'][3]; $itog_string['cialic'][3] = $itog_string['cialic'][3] + $cs_mas['cialic'][3];?></td>
                    <td class="text-center align-middle <? if(empty($cs_mas['cialic'][4])){echo 'bg-secondary';}?>"><?= $cs_mas['cialic'][4]; $itog_string['cialic'][4] = $itog_string['cialic'][4] + $cs_mas['cialic'][4];?></td>
                    <td class="text-center align-middle"><?=$cs_mas['cialic']['no']?></td>

                    <td class="text-center align-middle <? if(empty($cs_mas['mukovis'][1])){echo 'bg-secondary';}?>"><?= $cs_mas['mukovis'][1]; $itog_string['mukovis'][1] = $itog_string['mukovis'][1] + $cs_mas['mukovis'][1];?></td>
                    <td class="text-center align-middle <? if(empty($cs_mas['mukovis'][2])){echo 'bg-secondary';}?>"><?= $cs_mas['mukovis'][2]; $itog_string['mukovis'][2] = $itog_string['mukovis'][2] + $cs_mas['mukovis'][2];?></td>
                    <td class="text-center align-middle <? if(empty($cs_mas['mukovis'][3])){echo 'bg-secondary';}?>"><?= $cs_mas['mukovis'][3]; $itog_string['mukovis'][3] = $itog_string['mukovis'][3] + $cs_mas['mukovis'][3];?></td>
                    <td class="text-center align-middle <? if(empty($cs_mas['mukovis'][4])){echo 'bg-secondary';}?>"><?= $cs_mas['mukovis'][4]; $itog_string['mukovis'][4] = $itog_string['mukovis'][4] + $cs_mas['mukovis'][4];?></td>
                    <td class="text-center align-middle"><?=$cs_mas['mukovis']['no']?></td>

                    <td class="text-center align-middle <? if(empty($cs_mas['fenilketon'][1])){echo 'bg-secondary';}?>"><?= $cs_mas['fenilketon'][1]; $itog_string['fenilketon'][1] = $itog_string['fenilketon'][1] + $cs_mas['fenilketon'][1];?></td>
                    <td class="text-center align-middle <? if(empty($cs_mas['fenilketon'][2])){echo 'bg-secondary';}?>"><?= $cs_mas['fenilketon'][2]; $itog_string['fenilketon'][2] = $itog_string['fenilketon'][2] + $cs_mas['fenilketon'][2];?></td>
                    <td class="text-center align-middle <? if(empty($cs_mas['fenilketon'][3])){echo 'bg-secondary';}?>"><?= $cs_mas['fenilketon'][3]; $itog_string['fenilketon'][3] = $itog_string['fenilketon'][3] + $cs_mas['fenilketon'][3];?></td>
                    <td class="text-center align-middle <? if(empty($cs_mas['fenilketon'][4])){echo 'bg-secondary';}?>"><?= $cs_mas['fenilketon'][4]; $itog_string['fenilketon'][4] = $itog_string['fenilketon'][4] + $cs_mas['fenilketon'][4];?></td>
                    <td class="text-center align-middle"><?=$cs_mas['fenilketon']['no']?></td>

                    <td class="text-center align-middle <? if(empty($cs_mas['ovz'][4])){echo 'bg-secondary';}?>"><?= $cs_mas['ovz'][1]; $itog_string['ovz'][1] = $itog_string['ovz'][1] + $cs_mas['ovz'][1];?></td>
                    <td class="text-center align-middle <? if(empty($cs_mas['ovz'][4])){echo 'bg-secondary';}?>"><?= $cs_mas['ovz'][2]; $itog_string['ovz'][2] = $itog_string['ovz'][2] + $cs_mas['ovz'][2];?></td>
                    <td class="text-center align-middle <? if(empty($cs_mas['ovz'][4])){echo 'bg-secondary';}?>"><?= $cs_mas['ovz'][3]; $itog_string['ovz'][3] = $itog_string['ovz'][3] + $cs_mas['ovz'][3];?></td>
                    <td class="text-center align-middle <? if(empty($cs_mas['ovz'][4])){echo 'bg-secondary';}?>"><?= $cs_mas['ovz'][4]; $itog_string['ovz'][4] = $itog_string['ovz'][4] + $cs_mas['ovz'][4];?></td>
                    <td class="text-center align-middle"><?=$cs_mas['ovz']['no']?></td>

                    <td class="text-center align-middle <? if(empty($cs_mas['allergy'][1])){echo 'bg-secondary';}?>"><?= $cs_mas['allergy'][1]; $itog_string['allergy'][1] = $itog_string['allergy'][1] + $cs_mas['allergy'][1];?></td>
                    <td class="text-center align-middle <? if(empty($cs_mas['allergy'][2])){echo 'bg-secondary';}?>"><?= $cs_mas['allergy'][2]; $itog_string['allergy'][2] = $itog_string['allergy'][2] + $cs_mas['allergy'][2];?></td>
                    <td class="text-center align-middle <? if(empty($cs_mas['allergy'][3])){echo 'bg-secondary';}?>"><?= $cs_mas['allergy'][3]; $itog_string['allergy'][3] = $itog_string['allergy'][3] + $cs_mas['allergy'][3];?></td>
                    <td class="text-center align-middle <? if(empty($cs_mas['allergy'][4])){echo 'bg-secondary';}?>"><?= $cs_mas['allergy'][4]; $itog_string['allergy'][4] = $itog_string['allergy'][4] + $cs_mas['allergy'][4];?></td>
                    <td class="text-center align-middle"><?=$cs_mas['allergy']['no']?></td>
            </tr>


        <?}?>


        <tr class="table-danger">
            <td class="text-center align-middle" colspan="2">Итого по региону</td>
            <td class="text-center align-middle "><?= $itog_string['sahar'][1]?></td>
            <td class="text-center align-middle"><?= $itog_string['sahar'][2]?></td>
            <td class="text-center align-middle"><?= $itog_string['sahar'][3]?></td>
            <td class="text-center align-middle"><?= $itog_string['sahar'][4]?></td>
            <td class="text-center align-middle"><?=$itog_string['sahar']['no']?></td>

            <td class="text-center align-middle"><?=  $itog_string['cialic'][1];?></td>
            <td class="text-center align-middle"><?=  $itog_string['cialic'][2];?></td>
            <td class="text-center align-middle"><?=  $itog_string['cialic'][3];?></td>
            <td class="text-center align-middle"><?=  $itog_string['cialic'][4];?></td>
            <td class="text-center align-middle"><?=$itog_string['cialic']['no']?></td>

            <td class="text-center align-middle"><?= $itog_string['mukovis'][1];?></td>
            <td class="text-center align-middle"><?= $itog_string['mukovis'][2];?></td>
            <td class="text-center align-middle"><?= $itog_string['mukovis'][3];?></td>
            <td class="text-center align-middle"><?= $itog_string['mukovis'][4];?></td>
            <td class="text-center align-middle"><?=$itog_string['mukovis']['no']?></td>

            <td class="text-center align-middle"><?= $itog_string['fenilketon'][1] ?></td>
            <td class="text-center align-middle"><?=  $itog_string['fenilketon'][2]?></td>
            <td class="text-center align-middle"><?=  $itog_string['fenilketon'][3]?></td>
            <td class="text-center align-middle"><?=  $itog_string['fenilketon'][4]?></td>
            <td class="text-center align-middle"><?=$itog_string['fenilketon']['no']?></td>

            <td class="text-center align-middle"><?= $itog_string['ovz'][1]?></td>
            <td class="text-center align-middle"><?= $itog_string['ovz'][2]?></td>
            <td class="text-center align-middle"><?= $itog_string['ovz'][3]?></td>
            <td class="text-center align-middle"><?= $itog_string['ovz'][4]?></td>
            <td class="text-center align-middle"><?=$itog_string['ovz']['no']?></td>

            <td class="text-center align-middle"><?= $itog_string['allergy'][1]?></td>
            <td class="text-center align-middle"><?= $itog_string['allergy'][2]?></td>
            <td class="text-center align-middle"><?= $itog_string['allergy'][3]?></td>
            <td class="text-center align-middle"><?= $itog_string['allergy'][4]?></td>
            <td class="text-center align-middle"><?=$itog_string['allergy']['no']?></td>
        </tr>


        </tbody>
    </table><br><br><br>
<?}?>


<?
//print_r($data);
$script = <<< JS



$( ".beforeload" ).click(function() {
  $(".beforeload").css('display','none');
  $(".load").css('display','block');
  
});


JS;

$this->registerJs($script, yii\web\View::POS_READY);
?>
