<?php

use common\models\NutritionApplications;
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
use common\models\Organization;
use yii\helpers\ArrayHelper;


/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Анализ разработанных меню';
$this->params['breadcrumbs'][] = $this->title;

    $normativ = [];
    $normativ['vitamin_c'] = \common\models\NormativVitaminDay::find()->where(['name' => 'vitamin_c', 'age_info_id' => 6])->one()->value;
    $normativ['vitamin_b1'] = \common\models\NormativVitaminDay::find()->where(['name' => 'vitamin_b1', 'age_info_id' => 6])->one()->value;
    $normativ['vitamin_b2'] = \common\models\NormativVitaminDay::find()->where(['name' => 'vitamin_b2', 'age_info_id' => 6])->one()->value;
    $normativ['vitamin_a'] = \common\models\NormativVitaminDay::find()->where(['name' => 'vitamin_a', 'age_info_id' => 6])->one()->value;
    $normativ['ca'] = \common\models\NormativVitaminDay::find()->where(['name' => 'ca', 'age_info_id' => 6])->one()->value;
    $normativ['p'] = \common\models\NormativVitaminDay::find()->where(['name' => 'p', 'age_info_id' => 6])->one()->value;
    $normativ['mg'] = \common\models\NormativVitaminDay::find()->where(['name' => 'mg', 'age_info_id' => 6])->one()->value;
    $normativ['fe'] = \common\models\NormativVitaminDay::find()->where(['name' => 'fe', 'age_info_id' => 6])->one()->value;
    $normativ['i'] = \common\models\NormativVitaminDay::find()->where(['name' => 'i', 'age_info_id' => 6])->one()->value;
    $normativ['se'] = \common\models\NormativVitaminDay::find()->where(['name' => 'se', 'age_info_id' => 6])->one()->value;

//print_r($post);exit;
?>
    <style>
        th, td {
            border: 1px solid black!important;
            color: black;

        }
        th {
            background-color: #ede8b9;
            font-size: 12px;
        }
        thead, th {
            background-color: #ede8b9;
            font-size: 9px;
            font-weight: bold;
        }






        .block-items{
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }
        .organization{
            width:650px; border: 1px solid black; border-radius: 10px;
            flex: 0 0 650px;
        }
        .nutrition{
            width:650px; border: 1px solid black; border-radius: 10px;
            flex: 0 0 650px;
            margin-left: 50px;
        }
        .everyday-classes{
            width:650px; border: 1px solid black; border-radius: 10px;
            flex: 0 0 650px;
        }
        .item{
            margin-bottom: 45px;
        }
        .content p{
            margin-bottom: 7px;
        }
        .content img{
            width: 50px;
            height:auto;
        }
        .title{
            font-size: 18px;
        }
    </style>

    <h1 class="text-center mb-5"><?= Html::encode($this->title) ?></h1>



    <div class="block-items">
        <div class="nutrition item">
            <p class="text-center title"><b>ЗАМЕЧАНИЯ по Вкладке «Организация питания»</b></p>
            <div class="content" style="margin-left: 5px">

                <?$indicator_food_dir = 0; $sbornics = \common\models\RecipesCollection::find()->where(['organization_id' => Yii::$app->user->identity->organization_id])->all();
                if(!empty($sbornics)){
                    foreach ($sbornics as $sbornic){
                        $dishes = \common\models\Dishes::find()->where(['recipes_collection_id' => $sbornic->id])->all();
                        foreach ($dishes as $dish){
                            if(empty(\common\models\DishesProducts::find()->where(['dishes_id' => $dish->id])->all())){
                                $indicator_food_dir++;?>
                                <p class="ml-3">В блюдо: <b class="text-danger"><?=$dish->name;?></b> не добавлены продукты!<small><a href="http://demography.site/dishes/addproduct?id=<?=$dish->id;?>"> (Перейти к добавлению)</a></small></p>
                            <?}?>
                        <?}
                    }
                }
                    if($indicator_food_dir > 0){?>
                        <p class="ml-3 mt-5 text-danger text-center" style="font-size: 18px;"><b>Для корректного анализа необходимо устранить перечисленные замечания!</b></p>
                    <?}
                ?>

            </div>
        </div>


    </div>



                <table class="table table-bordered table-sm">
                    <thead>
                    <tr>
                        <td rowspan="3">№</td>
                        <td rowspan="3">Меню</td>
                        <td class="text-center align-middle" colspan="20">ЗАВТРАКИ</td>
                        <td class="text-center align-middle" colspan="20">ОБЕДЫ</td>
                    </tr>
                    <tr>
                        <!--ЗАВТРАК-->
                        <td >суммарная масса блюд (г.)</td>
                        <td>калорийность (ккал)</td>
                        <td colspan="4">Содержание витаминов</td>
                        <td colspan="6">Содержание минеральных веществ</td>
                        <td colspan="2">Содержание в среднем за прием пищи</td>
                        <td colspan="6">Содержание групп продуктов</td>



                        <!--ОБЕД-->
                        <td >суммарная масса блюд (г.)</td>
                        <td >калорийность (ккал)</td>
                        <td colspan="4">Содержание витаминов</td>
                        <td colspan="6">Содержание минеральных веществ</td>
                        <td colspan="2">Содержание в среднем за прием пищи</td>
                        <td colspan="6">Содержание групп продуктов</td>

                    </tr>
                    <tr>
                        <td>ср</td>
                        <td>ср</td>

                        <td>С(мг)</td>
                        <td>В1(мг)</td>
                        <td>В2(мг)</td>
                        <td>А (р.э.)</td>

                        <td>кальций (мг)</td>
                        <td>фосфор (мг)</td>
                        <td>магний (мг)</td>
                        <td>железо (мг)</td>
                        <td>йод (мг)</td>
                        <td>селен (мг)</td>

                        <td>соли (г)</td>
                        <td>сахара (г)</td>

                        <td>колбасных изделий</td>
                        <td>кондит. изделий</td>
                        <td>фруктов</td>
                        <td>ягод</td>
                        <td>меда</td>
                        <td>овощей</td>


                        <!--ОБЕД-->
                        <td>ср</td>

                        <td>ср</td>

                        <td>С(мг)</td>
                        <td>В1(мг)</td>
                        <td>В2(мг)</td>
                        <td>А (р.э.)</td>

                        <td>кальций (мг)</td>
                        <td>фосфор (мг)</td>
                        <td>магний (мг)</td>
                        <td>железо (мг)</td>
                        <td>йод (мг)</td>
                        <td>селен (мг)</td>

                        <td>соли (г)</td>
                        <td>сахара (г)</td>

                        <td>колбасных изделий</td>
                        <td>кондит. изделий</td>
                        <td>фруктов</td>
                        <td>ягод</td>
                        <td>меда</td>
                        <td>овощей</td>


                    </tr>

                    </thead>
                    <tbody>
                        <? $menus_dishes_model = new MenusDishes(); $count=0; foreach ($menus as $menu){ $count++;?>
                        <tr>
                            <td class="text-center align-middle" style="background-color: #ede8b9"><?=$count?></td>
                            <td class="text-center align-middle" style="background-color: #ede8b9"><?=$menu->name?></td>
<?                              //print_r($menu->id);exit;
                                $menu_zavtrak = $menus_dishes_model->get_menu_information_orgfood($menu->id, 1);
                                $menu_obed = $menus_dishes_model->get_menu_information_orgfood($menu->id, 3);



                                if($menu_zavtrak['yield'] == 0 || $menu_zavtrak== "null"){?>
                                    <td colspan="20" class="text-center align-middle text-danger">Завтраков в этом меню нет</td>
                                <?}else{?>
                                    <td class="text-center align-middle"><?= round($menu_zavtrak['yield'],1); $itog_string['menu_zavtrak_yield'] = $itog_string['menu_zavtrak_yield'] + round($menu_zavtrak['yield'],1);$itog_string['menu_zavtrak_count'] = $itog_string['menu_zavtrak_count'] + 1;?></td>
                                    <td class="text-center align-middle <?if(round($menu_zavtrak['kkal'],1) < 400){echo "bg-danger";}?>"><?= round($menu_zavtrak['kkal'],1);$itog_string['menu_zavtrak_kkal'] = $itog_string['menu_zavtrak_kkal'] + round($menu_zavtrak['kkal'],1);?></td>
                                    <td class="text-center align-middle <?if(round($menu_zavtrak['vitamin_c'],1) < $normativ['vitamin_c'] * 0.2){echo "bg-danger";}?>"><?= round($menu_zavtrak['vitamin_c'],1);$itog_string['menu_zavtrak_vitamin_c'] = $itog_string['menu_zavtrak_vitamin_c'] + round($menu_zavtrak['vitamin_c'],1);?></td>
                                    <td class="text-center align-middle <?if(round($menu_zavtrak['vitamin_b1'],1)< $normativ['vitamin_b1'] * 0.2){echo "bg-danger";}?>"><?= round($menu_zavtrak['vitamin_b1'],1);$itog_string['menu_zavtrak_vitamin_b1'] = $itog_string['menu_zavtrak_vitamin_b1'] + round($menu_zavtrak['vitamin_b1'],1);?></td>
                                    <td class="text-center align-middle <?if(round($menu_zavtrak['vitamin_b2'],1) < $normativ['vitamin_b2'] * 0.2){echo "bg-danger";}?>"><?= round($menu_zavtrak['vitamin_b2'],1);$itog_string['menu_zavtrak_vitamin_b2'] = $itog_string['menu_zavtrak_vitamin_b2'] + round($menu_zavtrak['vitamin_b2'],1);?></td>
                                    <td class="text-center align-middle <?if(round($menu_zavtrak['vitamin_a'],1) < $normativ['vitamin_a'] * 0.2){echo "bg-danger";}?>"><?= round($menu_zavtrak['vitamin_a'],1);$itog_string['menu_zavtrak_vitamin_a'] = $itog_string['menu_zavtrak_vitamin_a'] + round($menu_zavtrak['vitamin_a'],1);?></td>
                                    <td class="text-center align-middle <?if(round($menu_zavtrak['ca'],1) < $normativ['ca'] * 0.2){echo "bg-danger";}?>"><?= round($menu_zavtrak['ca'],1);$itog_string['menu_zavtrak_ca'] = $itog_string['menu_zavtrak_ca'] + round($menu_zavtrak['ca'],1);?></td>
                                    <td class="text-center align-middle <?if(round($menu_zavtrak['p'],1) < $normativ['p'] * 0.2){echo "bg-danger";}?>"><?= round($menu_zavtrak['p'],1);$itog_string['menu_zavtrak_p'] = $itog_string['menu_zavtrak_p'] + round($menu_zavtrak['p'],1);?></td>
                                    <td class="text-center align-middle <?if(round($menu_zavtrak['mg'],1) < $normativ['mg'] * 0.2){echo "bg-danger";}?>"><?= round($menu_zavtrak['mg'],1);$itog_string['menu_zavtrak_mg'] = $itog_string['menu_zavtrak_mg'] + round($menu_zavtrak['mg'],1);?></td>
                                    <td class="text-center align-middle <?if(round($menu_zavtrak['fe'],1) < $normativ['fe'] * 0.2){echo "bg-danger";}?>"><?= round($menu_zavtrak['fe'],1);$itog_string['menu_zavtrak_fe'] = $itog_string['menu_zavtrak_fe'] + round($menu_zavtrak['fe'],1);?></td>
                                    <td class="text-center align-middle <?if(round($menu_zavtrak['i'],1) < $normativ['i'] * 0.2){echo "bg-danger";}?>"><?= round($menu_zavtrak['i'],1);$itog_string['menu_zavtrak_i'] = $itog_string['menu_zavtrak_i'] + round($menu_zavtrak['i'],1);?></td>
                                    <td class="text-center align-middle <?if(round($menu_zavtrak['se'],1) < $normativ['se'] * 0.2){echo "bg-danger";}?>"><?= round($menu_zavtrak['se'],1);$itog_string['menu_zavtrak_se'] = $itog_string['menu_zavtrak_se'] + round($menu_zavtrak['se'],1);?></td>
                                    <td class="text-center align-middle <?if(round($menu_zavtrak['salt'],1) >= 1.25){echo "bg-danger";}?>"><?= round($menu_zavtrak['salt'],1);$itog_string['menu_zavtrak_salt'] = $itog_string['menu_zavtrak_salt'] + round($menu_zavtrak['salt'],1);?></td>
                                    <td class="text-center align-middle <?if(round($menu_zavtrak['sahar'],1) >= 10){echo "bg-danger";}?>"><?= round($menu_zavtrak['sahar'],1);$itog_string['menu_zavtrak_sahar'] = $itog_string['menu_zavtrak_sahar'] + round($menu_zavtrak['sahar'],1);?></td>

<!--                                    <td class="text-center align-middle --><?//if(round($menu_zavtrak['kolbasa'],0)>0){echo "bg-danger";}?><!--">--><?//if ( round($menu_zavtrak['kolbasa'],0)>0){echo 1;$itog_string['menu_zavtrak_kolbasa'] = $itog_string['menu_zavtrak_kolbasa']+1;}else{echo 0;}?><!--</td>-->
<!--                                    <td class="text-center align-middle --><?//if(round($menu_zavtrak['konditer'],0)>0){echo "bg-danger";}?><!--">--><?//if ( round($menu_zavtrak['konditer'],0)>0){echo 1;$itog_string['menu_zavtrak_konditer'] = $itog_string['menu_zavtrak_konditer']+1;}else{echo 0;}?><!--</td>-->
<!--                                    <td class="text-center align-middle">--><?//if ( round($menu_zavtrak['frukti'] ,0)>0){echo 1;$itog_string['menu_zavtrak_frukti'] = $itog_string['menu_zavtrak_frukti']+1;}else{echo 0;}?><!--</td>-->
<!--                                    <td class="text-center align-middle">--><?//if ( round($menu_zavtrak['yagoda'] ,0)>0){echo 1;$itog_string['menu_zavtrak_yagoda'] = $itog_string['menu_zavtrak_yagoda']+1;}else{echo 0;}?><!--</td>-->
<!--                                    <td class="text-center align-middle">--><?//if ( round($menu_zavtrak['med'] ,0) >0){echo 1;$itog_string['menu_zavtrak_med'] = $itog_string['menu_zavtrak_med']+1;}else{echo 0;}?><!--</td>-->
<!--                                    <td class="text-center align-middle">--><?//if ( round($menu_zavtrak['ovoshi'] ,0)>0){echo 1;$itog_string['menu_zavtrak_ovoshi'] = $itog_string['menu_zavtrak_ovoshi']+1;}else{echo 0;}?><!--</td>-->

                                    <td class="text-center align-middle <?if(round($menu_zavtrak['kolbasa'],0)>0){echo "bg-danger";}?>"><?= round($menu_zavtrak['kolbasa'],0);$itog_string['menu_zavtrak_kolbasa_count'] = $itog_string['menu_zavtrak_kolbasa_count']+round($menu_zavtrak['kolbasa'],0);?></td>
                                    <td class="text-center align-middle <?if(round($menu_zavtrak['konditer'],0)>0){echo "bg-danger";}?>"><?=round($menu_zavtrak['konditer'],0);$itog_string['menu_zavtrak_konditer_count'] = $itog_string['menu_zavtrak_konditer_count']+round($menu_zavtrak['konditer'],0);?></td>
                                    <td class="text-center align-middle"><?= round($menu_zavtrak['frukti'] ,0);$itog_string['menu_zavtrak_frukti_count'] = $itog_string['menu_zavtrak_frukti_count']+round($menu_zavtrak['frukti'] ,0);?></td>
                                    <td class="text-center align-middle"><?= round($menu_zavtrak['yagoda'] ,0);$itog_string['menu_zavtrak_yagoda_count'] = $itog_string['menu_zavtrak_yagoda_count']+round($menu_zavtrak['yagoda'] ,0);?></td>
                                    <td class="text-center align-middle"><?= round($menu_zavtrak['med'] ,0);$itog_string['menu_zavtrak_med_count'] = $itog_string['menu_zavtrak_med_count']+round($menu_zavtrak['med'] ,0);?></td>
                                    <td class="text-center align-middle"><?= round($menu_zavtrak['ovoshi'] ,0);$itog_string['menu_zavtrak_ovoshi_count'] = $itog_string['menu_zavtrak_ovoshi_count']+round($menu_zavtrak['ovoshi'] ,0); ?></td>
<!--                                    <td class="text-center align-middle --><?//if(round($menu_zavtrak['kkal'],1) < 400){echo "bg-danger";}?><!--">--><?//if(round($menu_zavtrak['kkal'],1) < 400){echo 1; $itog_string['deficit_blud_zavtrak'] = $itog_string['deficit_blud_zavtrak']+1;}else{ echo 0;}?><!--</td>-->

                                <?}?>


                                <?if($menu_obed['yield'] == 0 || $menu_obed== "null"){?>
                                    <td colspan="20" class="text-center align-middle text-danger">Обедов в этом меню нет</td>
                                <?}else{?>
                                    <td class="text-center align-middle"><?= round($menu_obed['yield'],1); $itog_string['menu_obed_yield'] = $itog_string['menu_obed_yield'] + round($menu_obed['yield'],1);$itog_string['menu_obed_count'] = $itog_string['menu_obed_count'] + 1;?></td>
                                    <td class="text-center align-middle <?if(round($menu_obed['kkal'],1) < 550){echo "bg-danger";}?>"><?= round($menu_obed['kkal'],1);$itog_string['menu_obed_kkal'] = $itog_string['menu_obed_kkal'] + round($menu_obed['kkal'],1);?></td>
                                    <td class="text-center align-middle <?if(round($menu_obed['vitamin_c'],1) < $normativ['vitamin_c'] * 0.3){echo "bg-danger";}?>"><?= round($menu_obed['vitamin_c'],1);$itog_string['menu_obed_vitamin_c'] = $itog_string['menu_obed_vitamin_c'] + round($menu_obed['vitamin_c'],1);?></td>
                                    <td class="text-center align-middle <?if(round($menu_obed['vitamin_b1'],1)< $normativ['vitamin_b1'] * 0.3){echo "bg-danger";}?>"><?= round($menu_obed['vitamin_b1'],1);$itog_string['menu_obed_vitamin_b1'] = $itog_string['menu_obed_vitamin_b1'] + round($menu_obed['vitamin_b1'],1);?></td>
                                    <td class="text-center align-middle <?if(round($menu_obed['vitamin_b2'],1) < $normativ['vitamin_b2'] * 0.3){echo "bg-danger";}?>"><?= round($menu_obed['vitamin_b2'],1);$itog_string['menu_obed_vitamin_b2'] = $itog_string['menu_obed_vitamin_b2'] + round($menu_obed['vitamin_b2'],1);?></td>
                                    <td class="text-center align-middle <?if(round($menu_obed['vitamin_a'],1) < $normativ['vitamin_a'] * 0.3){echo "bg-danger";}?>"><?= round($menu_obed['vitamin_a'],1);$itog_string['menu_obed_vitamin_a'] = $itog_string['menu_obed_vitamin_a'] + round($menu_obed['vitamin_a'],1);?></td>
                                    <td class="text-center align-middle <?if(round($menu_obed['ca'],1) < $normativ['ca'] * 0.3){echo "bg-danger";}?>"><?= round($menu_obed['ca'],1);$itog_string['menu_obed_ca'] = $itog_string['menu_obed_ca'] + round($menu_obed['ca'],1);?></td>
                                    <td class="text-center align-middle <?if(round($menu_obed['p'],1) < $normativ['p'] * 0.3){echo "bg-danger";}?>"><?= round($menu_obed['p'],1);$itog_string['menu_obed_p'] = $itog_string['menu_obed_p'] + round($menu_obed['p'],1);?></td>
                                    <td class="text-center align-middle <?if(round($menu_obed['mg'],1) < $normativ['mg'] * 0.3){echo "bg-danger";}?>"><?= round($menu_obed['mg'],1);$itog_string['menu_obed_mg'] = $itog_string['menu_obed_mg'] + round($menu_obed['mg'],1);?></td>
                                    <td class="text-center align-middle <?if(round($menu_obed['fe'],1) < $normativ['fe'] * 0.3){echo "bg-danger";}?>"><?= round($menu_obed['fe'],1);$itog_string['menu_obed_fe'] = $itog_string['menu_obed_fe'] + round($menu_obed['fe'],1);?></td>
                                    <td class="text-center align-middle <?if(round($menu_obed['i'],1) < $normativ['i'] * 0.3){echo "bg-danger";}?>"><?= round($menu_obed['i'],1);$itog_string['menu_obed_i'] = $itog_string['menu_obed_i'] + round($menu_obed['i'],1);?></td>
                                    <td class="text-center align-middle <?if(round($menu_obed['se'],1) < $normativ['se'] * 0.3){echo "bg-danger";}?>"><?= round($menu_obed['se'],1);$itog_string['menu_obed_se'] = $itog_string['menu_obed_se'] + round($menu_obed['se'],1);?></td>
                                    <td class="text-center align-middle <?if(round($menu_obed['salt'],1) >= 1.5){echo "bg-danger";}?>"><?= round($menu_obed['salt'],1);$itog_string['menu_obed_salt'] = $itog_string['menu_obed_salt'] + round($menu_obed['salt'],1);?></td>
                                    <td class="text-center align-middle <?if(round($menu_obed['sahar'],1) >= 10){echo "bg-danger";}?>"><?= round($menu_obed['sahar'],1);$itog_string['menu_obed_sahar'] = $itog_string['menu_obed_sahar'] + round($menu_obed['sahar'],1);?></td>


<!--                                    <td class="text-center align-middle --><?//if(round($menu_obed['kolbasa'],0)>0){echo "bg-danger";}?><!--">--><?//if ( round($menu_obed['kolbasa'],0)>0){echo 1;$itog_string['menu_obed_kolbasa'] = $itog_string['menu_obed_kolbasa']+1;}else{echo 0;}?><!--</td>-->
<!--                                    <td class="text-center align-middle --><?//if(round($menu_obed['konditer'],0)>0){echo "bg-danger";}?><!--">--><?//if ( round($menu_obed['konditer'],0)>0){echo 1;$itog_string['menu_obed_konditer'] = $itog_string['menu_obed_konditer']+1;}else{echo 0;}?><!--</td>-->
<!--                                    <td class="text-center align-middle">--><?//if ( round($menu_obed['frukti'] ,0)>0){echo 1;$itog_string['menu_obed_frukti'] = $itog_string['menu_obed_frukti']+1;}else{echo 0;}?><!--</td>-->
<!--                                    <td class="text-center align-middle">--><?//if ( round($menu_obed['yagoda'] ,0)>0){echo 1;$itog_string['menu_obed_yagoda'] = $itog_string['menu_obed_yagoda']+1;}else{echo 0;}?><!--</td>-->
<!--                                    <td class="text-center align-middle">--><?//if ( round($menu_obed['med'] ,0) >0){echo 1;$itog_string['menu_obed_med'] = $itog_string['menu_obed_med']+1;}else{echo 0;}?><!--</td>-->
<!--                                    <td class="text-center align-middle">--><?//if ( round($menu_obed['ovoshi'] ,0)>0){echo 1;$itog_string['menu_obed_ovoshi'] = $itog_string['menu_obed_ovoshi']+1;}else{echo 0;}?><!--</td>-->

                                    <td class="text-center align-middle <?if(round($menu_obed['kolbasa'],0)>0){echo "bg-danger";}?>"><?= round($menu_obed['kolbasa'],0);$itog_string['menu_obed_kolbasa_count'] = $itog_string['menu_obed_kolbasa_count']+round($menu_obed['kolbasa'],0);?></td>
                                    <td class="text-center align-middle <?if(round($menu_obed['konditer'],0)>0){echo "bg-danger";}?>"><?=round($menu_obed['konditer'],0);$itog_string['menu_obed_konditer_count'] = $itog_string['menu_obed_konditer_count']+round($menu_obed['konditer'],0);?></td>
                                    <td class="text-center align-middle"><?= round($menu_obed['frukti'] ,0);$itog_string['menu_obed_frukti_count'] = $itog_string['menu_obed_frukti_count']+round($menu_obed['frukti'] ,0);?></td>
                                    <td class="text-center align-middle"><?= round($menu_obed['yagoda'] ,0);$itog_string['menu_obed_yagoda_count'] = $itog_string['menu_obed_yagoda_count']+round($menu_obed['yagoda'] ,0);?></td>
                                    <td class="text-center align-middle"><?= round($menu_obed['med'] ,0);$itog_string['menu_obed_med_count'] = $itog_string['menu_obed_med_count']+round($menu_obed['med'] ,0);?></td>
                                    <td class="text-center align-middle"><?= round($menu_obed['ovoshi'] ,0);$itog_string['menu_obed_ovoshi_count'] = $itog_string['menu_obed_ovoshi_count']+round($menu_obed['ovoshi'] ,0); ?></td>
<!--                                    <td class="text-center align-middle --><?//if(round($menu_obed['kkal'],1) < 550){echo "bg-danger";}?><!--">--><?//if(round($menu_obed['kkal'],1) < 550){echo 1; $itog_string['deficit_blud_obed'] = $itog_string['deficit_blud_obed']+1;}else{ echo 0;}?><!--</td>-->
                                <?}?>

                            <?if($menu == 0){?>
                                <td colspan="27" class="text-center align-middle text-danger">Обедов в этом меню нет</td>
                            <?}?>

                        </tr>
                    <?}?>

<!--
                        <tr class="table-success">
                            <td class="" colspan="2">Нормативы:</td>

                            <td class="text-center align-middle">-</td>
                            <td class="text-center align-middle">>400</td>
                            <td class="text-center align-middle">><?=$normativ['vitamin_c'] *0.2?></td>
                            <td class="text-center align-middle">><?=$normativ['vitamin_b1']*0.2?></td>
                            <td class="text-center align-middle">><?=$normativ['vitamin_b2']*0.2?></td>
                            <td class="text-center align-middle">><?=$normativ['vitamin_a'] *0.2?></td>
                            <td class="text-center align-middle">><?=$normativ['ca']*0.2?></td>
                            <td class="text-center align-middle">><?=$normativ['p'] *0.2?></td>
                            <td class="text-center align-middle">><?=$normativ['mg']*0.2?></td>
                            <td class="text-center align-middle">><?=$normativ['fe'] *0.2?></td>
                            <td class="text-center align-middle">><?=$normativ['i'] *0.2?></td>
                            <td class="text-center align-middle">><?=$normativ['se']*0.2?></td>
                            <td class="text-center align-middle"><1.25</td>
                            <td class="text-center align-middle"><10</td>


                            <td class="text-center align-middle">0</td>
                            <td class="text-center align-middle">0</td>
                            <td class="text-center align-middle">>0</td>
                            <td class="text-center align-middle">>0</td>
                            <td class="text-center align-middle">>0</td>
                            <td class="text-center align-middle">>0</td>


                            <td class="text-center align-middle">-</td>
                            <td class="text-center align-middle">>550</td>
                            <td class="text-center align-middle">><?=$normativ['vitamin_c'] *0.3?></td>
                            <td class="text-center align-middle">><?=$normativ['vitamin_b1']*0.3?></td>
                            <td class="text-center align-middle">><?=$normativ['vitamin_b2']*0.3?></td>
                            <td class="text-center align-middle">><?=$normativ['vitamin_a'] *0.3?></td>
                            <td class="text-center align-middle">><?=$normativ['ca']*0.3?></td>
                            <td class="text-center align-middle">><?=$normativ['p'] *0.3?></td>
                            <td class="text-center align-middle">><?=$normativ['mg']*0.3?></td>
                            <td class="text-center align-middle">><?=$normativ['fe'] *0.3?></td>
                            <td class="text-center align-middle">><?=$normativ['i'] *0.3?></td>
                            <td class="text-center align-middle">><?=$normativ['se']*0.3?></td>
                            <td class="text-center align-middle"><1.5</td>
                            <td class="text-center align-middle"><10</td>

                            <td class="text-center align-middle">0</td>
                            <td class="text-center align-middle">0</td>
                            <td class="text-center align-middle">>0</td>
                            <td class="text-center align-middle">>0</td>
                            <td class="text-center align-middle">>0</td>
                            <td class="text-center align-middle">>0</td>

                        </tr>

-->
                    </tbody>
                </table><br><br><br>



<?

$script = <<< JS

$( ".beforeload" ).click(function() {
  $(".beforeload").css('display','none');
  $(".load").css('display','block');
  
});


/*$( ".beforeload" ).click(function() {
  $('.beforeload').append('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');
});*/






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