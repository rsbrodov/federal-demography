<?php

use common\models\MenusDishes;
use common\models\ProductsChangeOrganization;
use yii\bootstrap4\Html;
use yii\grid\GridView;
use yii\bootstrap4\ActiveForm;
use common\models\Menus;
use common\models\Days;
use common\models\MenusDays;
use yii\helpers\ArrayHelper;


/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Гигиеническая оценка меню';
$this->params['breadcrumbs'][] = $this->title;
$kkal_neok_mas=[];
$my_menus = Menus::find()->where(['organization_id' => Yii::$app->user->identity->organization_id, 'status_archive' => 0])->all();
$my_menus_items = ArrayHelper::map($my_menus, 'id', 'name');
$first_menu = Menus::find()->where(['organization_id' => Yii::$app->user->identity->organization_id])->one();
$uvarka_mas['vitamin_a'] = 0.6;$uvarka_mas['vitamin_b1'] = 0.72;$uvarka_mas['vitamin_c'] = 0.72;
$uvarka_mas['vitamin_pp'] = 0.8;$uvarka_mas['vitamin_b2'] = 0.8;$uvarka_mas['vitamin_b_carotene'] = 0.8;
$uvarka_mas['mg'] = 0.87;$uvarka_mas['p'] = 0.87;$uvarka_mas['fe'] = 0.87;
$uvarka_mas['ca'] = 0.88;$uvarka_mas['se'] = 0.88;$uvarka_mas['na'] = 0.76;
$uvarka_mas['k'] = 0.83;

$vitamins_mas=[];
$vitamins_mas['kkal'] = 'Калорийность, ккал.';
$vitamins_mas['protein'] = 'Количество белков (г)';
$vitamins_mas['fat'] = 'Количество жиров (г)';
$vitamins_mas['carbohydrates'] = 'Количество углеводов (г)';
$vitamins_mas['vitamin_c'] = 'Витамин С, мг';
$vitamins_mas['vitamin_b1'] = 'Витамин В1, мг';
$vitamins_mas['vitamin_b2'] = 'Витамин В2, мг';
$vitamins_mas['vitamin_a'] = 'Витамин А, мкг рэ';
$vitamins_mas['ca'] = 'Кальций, мг';
$vitamins_mas['mg'] = 'Магний, мг';
$vitamins_mas['fe'] = 'Железо, мг ';
$vitamins_mas['k'] = 'Калий, мг ';
$vitamins_mas['i'] = 'Йод, мкг ';
$vitamins_mas['se'] = 'Селен, мкг ';



$effect_bad = [];
$effect_bad['vitamin_c'] = 'Быстрая утомляемость, отдышка при незначительной физической нагрузке, слабый иммунитет. При длительном дефиците развивается цинга. Снижение резистентности организма.';
$effect_bad['vitamin_b1'] = 'Повышенная утомляемость и раздражительность, ухудшение памяти';
$effect_bad['vitamin_b2'] = 'Появляются трещинки и слущивания кожи в губ и области носа, светобоязнь, слезоточивость, конъюктивит. Появляется  раздражительность , появляется мышечная слабость и боли в конечностях. Дерматиты, нарушения зрения.';
$effect_bad['vitamin_a'] = 'Сухость кожи и слизистых оболочек';
$effect_bad['ca'] = 'Ломкость волос, слоение ногтей, кариес, ослабевание иммунитета. Замедляются процессы роста и развития, появляется склонность к переломам, вывихам. Риски кариеса, риски переломов трубчатых костей';
$effect_bad['mg'] = 'Выпадение волос и ломкость ногтей, сухость и шелушение кожи, проблемы с пищеварением, повышенное давление, боли в мышцах и суставах. Кроме того при дефиците магния происходят сбои в работе надпочечников, развиваются болезни сердечно-сосудистой системы и начальные стадии диабета, увеличивается риск появления опухолевых образований и камней в почках. Риски формирования заболеваний органов пищеварения и болезней системы кровообращения';
$effect_bad['fe'] = 'Снижение концентрации, нарушения сна, развитие анемии, задержка умственного развития. Риски анемии, нарушения сна';
$effect_bad['i'] = 'Нарушения работы нервной системы, физического и умственного развития. Дефицит йода влечет за собой возникновение различных патологий, таких как нарушение синтеза гормонов щитовидной железы, появление зоба, снижение памяти и слуха, повышение уровня холестерина в крови, брадикардию, расстройство стула, снижение иммунитета. Риски снижения памяти и слуха, патологии нервной системы';

if(Yii::$app->user->can('rospotrebnadzor_camp') || Yii::$app->user->can('rospotrebnadzor_nutrition') || Yii::$app->user->can('subject_minobr') || Yii::$app->user->can('minobr'))
{
    if (!empty(Yii::$app->session['organization_id']))
    {
        $my_menus = Menus::find()->where(['organization_id' => Yii::$app->session['organization_id'], 'status_archive' => 0])->all();
        $my_menus_items = ArrayHelper::map($my_menus, 'id', 'name');
        $first_menu = Menus::find()->where(['organization_id' => Yii::$app->session['organization_id'], 'status_archive' => 0])->one();
    }
    else
    {
        $my_menus = Menus::find()->where(['organization_id' => Yii::$app->user->identity->organization_id, 'status_archive' => 0])->all();
        $my_menus_items = ArrayHelper::map($my_menus, 'id', 'name');
        $first_menu = Menus::find()->where(['organization_id' => Yii::$app->user->identity->organization_id, 'status_archive' => 0])->one();
    }
}

$params_menu = ['class' => 'form-control', 'options' => [$first_menu->id => ['Selected' => true]]];
//$post['date'] = '';
if(!empty($post)){
    $my_menus = Menus::findOne($post['id']);
    $params_menu = ['class' => 'form-control', 'options' => [$post['menu_id'] => ['Selected' => true]]];


    //c 03.02.22 принято решения считать норматив не по типу организации и по типу меню. Для лагерей показатель нормативов !!!!+10%!!!!! для этого делаем if
    $lager_koef = 0;
    if($my_menus->type_org_id == 1){//если меню предназначено для лагеря
        $lager_koef = 0.1;
    }
    //print_r($lager_koef);

}
$md = new \common\models\MenusDishes();


?>

    <h1 class="text-center"><?= Html::encode($this->title) ?></h1>
<!--    <h1 class="text-center text-danger">Раздел на техническом обслуживании <b>20.12.2021 c 07:00 - 13:00</b>(мск)</h1>-->
<!--    <p class="text-danger text-center" style="font-size: 20px;"><b>Раздел на технической доработке до 15 июня. Возможны ошибки</b></p>-->
    <?php $form = ActiveForm::begin(); ?>
    <div class="container mb-30 mt-5">
        <div class="row">
            <div class="col">
                <?= $form->field($menus_model, 'id')->dropDownList($my_menus_items, [
                    'class' => 'form-control', 'options' => [$post['id'] => ['Selected' => true]],
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
                    ])->label('Меню'); ?>
            </div>
        </div>

        <!--        Блок с заполняемыми инпутами для информации. id не менять иначе не сработает-->
        <?if(empty($post)){$menu_id = $first_menu->id;} else{$menu_id = $post['id'];} $model = new MenusDishes();?>
        <div class="row mb-3">
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



        <div class="row mb-3">
            <div class="form-group" style="margin: 0 auto">
                <?= Html::submitButton('Посмотреть', ['class' => 'btn main-button-3 beforeload']) ?>
                <button class="btn main-button-3 load" type="button" disabled style="display: none">
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    Посмотреть...
                </button>

            </div>
        </div>
        <?php ActiveForm::end(); ?>
<!--        <p class="text-center text-danger" style="font-size: 20px;"><b>Раздел в разработке</b></p>-->
    </div>
    <?if(Yii::$app->user->can('rospotrebnadzor_camp') || Yii::$app->user->can('rospotrebnadzor_nutrition') || Yii::$app->user->can('subject_minobr') || Yii::$app->user->can('minobr'))
    {
    if (empty(Yii::$app->session['organization_id']))
    {?>
    <p class="text-center mt-5" style="font-size: 30px;"><b>Выберите организацию в верхнем левом углу для просмотра информации по образовательному учреждению</b></p>
<?}
}?>
<?if(!empty($post)){
    //print_r(Menus::findOne($post['id'])->type_org_id);
    if(Yii::$app->user->can('rospotrebnadzor_camp') || Yii::$app->user->can('rospotrebnadzor_nutrition') || Yii::$app->user->can('subject_minobr') || Yii::$app->user->can('minobr')){
        $organization_id = Yii::$app->session['organization_id'];
    }else{
        $organization_id = Yii::$app->user->identity->organization_id;
    }
    $smena_items = [];
    $peremena_items = [];
    $post_menu = Menus::findOne($post['id']);
    if (count(MenusDishes::find()->where(['menu_id' => $post['id']])->asArray()->all()) > 2){
        $menus_nutrition = \common\models\MenusNutrition::find()->where(['menu_id' => $post['id']])->all();
        $menus_nutrition_col = \common\models\MenusNutrition::find()->where(['menu_id' => $post['id']])->count();
        if(Yii::$app->user->can('rospotrebnadzor_camp') || Yii::$app->user->can('rospotrebnadzor_nutrition') || Yii::$app->user->can('subject_minobr') || Yii::$app->user->can('minobr')){
            $characters_stolovaya = \common\models\CharactersStolovaya::find()->where(['organization_id' => Yii::$app->session['organization_id']])->one();
            $duration_peremena = \common\models\SchoolBreak::find()->where(['organization_id' => Yii::$app->session['organization_id']])->max('duration');
        }else{
            $characters_stolovaya = \common\models\CharactersStolovaya::find()->where(['organization_id' => Yii::$app->user->identity->organization_id])->one();
            $duration_peremena = \common\models\SchoolBreak::find()->where(['organization_id' => Yii::$app->user->identity->organization_id])->max('duration');
        }

        /*foreach ($characters_study as $ch_study){
            if(!array_key_exists($ch_study->smena, $smena_items))
            {
                $smena_items[$ch_study->smena] = $ch_study->smena;
                if(Yii::$app->user->can('rospotrebnadzor_camp') || Yii::$app->user->can('rospotrebnadzor_nutrition') || Yii::$app->user->can('subject_minobr') || Yii::$app->user->can('minobr')){
                    $peremens = \common\models\CharactersStudy::find()->where(['organization_id' => Yii::$app->session['organization_id'], 'smena' =>$ch_study->smena])->orderby(['number_peremena' => SORT_ASC])->all();
                }else{
                    $peremens = \common\models\CharactersStudy::find()->where(['organization_id' => Yii::$app->user->identity->organization_id, 'smena' =>$ch_study->smena])->orderby(['number_peremena' => SORT_ASC])->all();
                }

                foreach ($peremens as $peremen){

                    if(!array_key_exists($ch_study->smena.'_'.$peremen->number_peremena, $peremena_items))
                    {
                        $peremena_items[$ch_study->smena.'_'.$peremen->number_peremena] = $peremen->number_peremena;
                    }
                }
            }
        }
        //переборочный массив
        $peremena_mas = [];
        foreach ($peremena_items as $key => $peremena_item){
            $masss = explode('_', $key);
            $peremena_mas[$masss[0]][] = $masss[1];
        }
        //
        $smena_string = '';
        foreach ($smena_items as $smena_item){
            $smena_string .= $smena_item. " ";
        }*/
        $nutrition_string = '';
        $nutrition_string_count = 0;
        foreach ($menus_nutrition as $m_nutrition){$nutrition_string_count++;
            if($nutrition_string_count == 1){
                $nutrition_string .= \common\models\NutritionInfo::findOne($m_nutrition->nutrition_id)->name. ", ";
            }
            elseif($menus_nutrition_col != $nutrition_string_count && $nutrition_string_count > 1 && $menus_nutrition_col > 1){
                $nutrition_string .= mb_strtolower(\common\models\NutritionInfo::findOne($m_nutrition->nutrition_id)->name). ", ";
            }
            else{
                $nutrition_string .= mb_strtolower(\common\models\NutritionInfo::findOne($m_nutrition->nutrition_id)->name);
            }
        }

        $diseases_string = '';
        $diseases_string_count = 0;


        $characters_study_model = new \common\models\CharactersStudy();
        $diseases_mas = $characters_study_model->diseasesOrganization($organization_id);
        if(count($diseases_mas) > 1){
            foreach ($diseases_mas['shapka'] as $key => $d_mas){
                $diseases_string .= 'Детей с ' . $d_mas . ' в организации - ' . $diseases_mas['aliases'][$key] . '. ';
            }

        }else{
            $diseases_string .= 'Детей с сахарным диабетом, целиакией, пищевой аллергией, фенилкетонурией, муковисцидозом в организации – нет.';
        }
        //print_r($diseases_string);

        $vitamins_effect = [];//массив где неблагоприятные витамины
        $kkal_bju_mas = [];//здесь будет храниться калорийность бжу
        $recipes_mas = [];
        $dishes_mas = [];
        $recipes_string = '';
        $notice_string = '';
        $yield_string_ok = '';
        $yield_string_neok = '';
        $kkal_string_ok = '';
        $kkal_string_neok = '';
        $salt_string_neok = '';
        $sahar_string_neok = '';
        $kolbasa_neok = '';
        $konditer_neok = '';
        $buterbrod_neok = '';
        $vitamins_neok = [];
        $vitamins_ok = [];
        $notice_count = 0;
        $conservir_tomat_neok = 0;
        $conservir_ogurec_neok = 0;
        $menus_dishes = MenusDishes::find()->where(['date_fact_menu' => 0, 'menu_id' => $post['id']])->all();
        foreach ($menus_dishes as $m_dish){
            $dish = \common\models\Dishes::findOne($m_dish->dishes_id);
            if (!array_key_exists($dish->recipes_collection_id, $recipes_mas) && $dish->recipes_collection_id != 4 && $dish->recipes_collection_id != 263){
                $recipes_mas[$dish->recipes_collection_id] = $dish->recipes_collection_id;
                $recipes_string .= '«'.\common\models\RecipesCollection::findOne($dish->recipes_collection_id)->name.'», ';
            }

            if (!array_key_exists($dish->id, $dishes_mas)){
                $dishes_mas[$dish->id] = $dish->id;
            }
        }
        foreach ($dishes_mas as $dish_mas){
            if(empty(\common\models\DishesProducts::find()->where(['dishes_id' => $dish_mas])->all())){
                $notice_count++;
            }
        }
        if($notice_count > 0){
            $notice_string = '<b class="text-danger">Обнаружены замечания по '.$notice_count.' технологическим картам. Не добавлены продукты в техкарты</b>';
        }else{
            $notice_string = 'Замечаний по технологическим картам нет, представлены в полном объеме';
        }

        $menus_dihes_model = New MenusDishes();
        $num_first_table = 0;
        $repeat_mas = $menus_dihes_model->get_repeat_dishes_correct($post_menu->id);
        //print_r(Menus::findOne($menu_id)->id);


        ?>
        <div class="tables">
            <div class="table_block" style="width: 70%">
                <p class="text-center"><b>Оценка меню:</b></p>
                <table class="table_th0 table-hover table-responsive last" >
                    <thead>
                    <tr>
                        <th class="text-center align-middle" rowspan="2" style="min-width: 50px">№</th>
                        <!--            <th class="text-center align-middle" rowspan="2" style="max-width: 150px">Информация</th>-->
                        <th class="text-center align-middle" rowspan="2" style="max-width: 150px">Перечень детализируемой информации</th>
                        <th class="text-center align-middle" rowspan="2" style="max-width: 500px">Детализация информации</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td class="text-center align-middle"><?$num_first_table++; echo $num_first_table;?></td>
                        <td class="align-middle">Название меню</td>
                        <td><?=\common\models\Menus::findOne($post_menu->id)->name ?></td>
                    </tr>
                    <tr>
                        <td class="text-center"><?$num_first_table++; echo $num_first_table;?></td>
                        <td class="">Информация о возрастной группе детей</td>
                        <td><?=\common\models\AgeInfo::findOne($post_menu->age_info_id)->name;?></td>
                    </tr>
                    <?if(!Yii::$app->user->can('food_director')){?>
                        <!--            РАСКОМЕНТИТЬ как доделаю функционал-->
                        <!--            <tr>-->
                        <!--                <td class="text-center">2</td>-->
                        <!--                <td class="">Режим обучения</td>-->
                        <!--                <td>--><?//=$smena_string. ' смена';?><!--</td>-->
                        <!--            </tr>-->
                        <tr>
                            <td class="text-center"><?$num_first_table++; echo $num_first_table;?></td>
                            <td class="">Режим питания</td>
                            <td><?=$nutrition_string;?></td>
                        </tr>
                        <!--            РАСКОМЕНТИТЬ как доделаю функционал-->


                        <!--            <tr>-->
                        <!--                <td class="text-center">4</td>-->
                        <!--                <td class="">Продолжительность приемов пищи</td>-->
                        <!--                <td>--><?//foreach ($smena_items as $smena_item){
//                        $count_p = 0;
//                        if($smena_item == 1){ echo ' В первую смену - ';
//                            foreach ($peremena_mas[$smena_item] as $p){
//                                $count_p++;
//                                echo mb_strtolower(\common\models\SmenaPeremena::findOne($p)->name).' перемена ('.\common\models\SchoolBreak::find()->where(['organization_id' => $organization_id, 'smena' =>$smena_item, 'peremena' =>$p])->one()->duration.' минут)';
//                                if(count($peremena_mas[$smena_item]) > 1 && count($peremena_mas[$smena_item]) != $count_p){
//                                    echo ', ';
//                                }
//                            }
//                        }
//                        if($smena_item == 2){ echo ' во вторую смену - ';
//                            foreach ($peremena_mas[$smena_item] as $p){
//                                $count_p++;
//                                echo mb_strtolower(\common\models\SmenaPeremena::findOne($p)->name).' перемена ('.\common\models\SchoolBreak::find()->where(['organization_id' => $organization_id, 'smena' =>$smena_item, 'peremena' =>$p])->one()->duration.' минут)';
//                                if(count($peremena_mas[$smena_item]) > 1 && count($peremena_mas[$smena_item]) != $count_p){
//                                    echo ', ';
//                                }
//                            }
//                        }
//                    }?>
                        <!--                    .</p></td>-->
                        <!--            </tr>-->
                    <?}?>
                    <tr>
                        <td class="text-center"><?$num_first_table++; echo $num_first_table;?></td>
                        <td class="">Технологические карты на заявленные в меню блюда с указанием сборника рецептур предназначенных для питания детей</td>
                        <td class="">Представлены в полном объеме, все технологические карты заимствованы из <?=$recipes_string;?></td>
                    </tr>
<!--                    <tr>-->
<!--                        <td class="text-center">6</td>-->
<!--                        <td class="">Информация об использовании в меню, обогащенных витаминами и минеральными веществами продуктов питания</td>-->
<!--                        <td>Специализированной продукции, обогащенной витаминами и минеральными веществами в питании детей нет.-->
<!--                            Для приготовления блюд используется йодированная соль.</td>-->
<!--                    </tr>-->

                        <tr>
                            <?//Проверка на существование полуфабрикатов
                            $polufabricat = 0; foreach ($menus_nutrition as $m_nutrition)
                            {
                                $mn = $md->get_menu_information_one($post['id'], $m_nutrition->nutrition_id);
                                if($mn != 'null'){
                                    $polufabricat_nutrition = $md->get_menu_information_one($post['id'], $m_nutrition->nutrition_id)['polufabricat'];
                                    $polufabricat = $polufabricat + $polufabricat_nutrition;
                                }
                                //print_r($m_nutrition->nutrition_id.'<br>');

                            }?>
                            <td class="text-center"><?$num_first_table++; echo $num_first_table;?></td>
                            <td class="">Информация о планируемых к использованию полуфабрикатах</td>
                            <?if($polufabricat > 0){?>
                                <td class="align-middle">Планируется использование полуфабрикатов.</td>
                            <?}else{?>
                                <td class="align-middle">Использование полуфабрикатов в меню не планируется</td>
                            <?}?>
                        </tr>
                    <?if(!Yii::$app->user->can('food_director')){?>
                        <tr>
                            <td class="text-center"><?$num_first_table++; echo $num_first_table;?></td>
                            <td class="">Информация о наличии в организации детей с сахарным диабетом, целиакией, пищевой аллергией, фенилкетонурией, муковисцидозом</td>
                            <td class=""><?=$diseases_string?></td>
                        </tr>
                    <?}?>
                    </tbody>
                </table>
</div>
    </div>



    <style>
        .tables{
            display:flex;
            flex-wrap: wrap;
            justify-content: space-around ;
        }
        th, td {
            border: 1px solid black!important;
            color: black;

        }
        th {
            background-color: #ede8b9;
            font-size: 15px;
        }
        thead, th {
            background-color: #ede8b9;
            font-size: 14px;
        }
    </style>
    <p class="text-center mt-4"><b>Информация об энергетической, пищевой и витаминно-минеральной ценности меню</b></p>
    <div class="tables">
        <?$salt_sahar_mas = [];?>
        <?$itog_normativ = [];?>
        <?$itog_vitamin = [];?>
<?foreach ($menus_nutrition as $m_nutrition){
    //c 03.02.22 принято решения считать норматив не по типу организации и по типу меню. Для лагерей показатель нормативов !!!!+10%!!!!! для этого делаем if
    //$nutrition_koeff = \common\models\NutritionProcent::find()->where(['type_org' => \common\models\Organization::findOne(Yii::$app->user->identity->organization_id)->type_org, 'nutrition_id' => $m_nutrition->nutrition_id])->one()->procent/100;
        $nutrition_koeff = \common\models\NutritionProcent::find()->where(['type_org' => $my_menus->type_org_id, 'nutrition_id' => $m_nutrition->nutrition_id])->one()->procent/100;

        $nutrition_info = \common\models\NutritionInfo::find()->where(['id' => $m_nutrition->nutrition_id])->one()->name;?>

            <div class="table_block mb-5">
                <p class="text-center mt-4"><b>Меню <?=$nutrition_info?></b></p>
                <table class="table_th0 table-hover table-responsive last" >
                    <thead>
                    <tr>
                        <th class="text-center align-middle" rowspan="2" style="max-width: 150px">Показатели</th>
                        <th class="text-center align-middle" rowspan="2" style="max-width: 200px">Фактические значения по меню в среднем за день цикла (<?=$nutrition_info?>)</th>
                        <th class="text-center align-middle" rowspan="2" style="max-width: 200px">Рекомендуемые значения по меню в среднем за день цикла (<?=$nutrition_info?>) </th>
                        <th class="text-center align-middle" rowspan="1"  colspan="2" style="max-width: 290px">Удельный вес от рекомендуемой величины на  </th>
                    </tr>
                    <tr>
                        <th class="text-center align-middle" rowspan="2" style="min-width: 50px"><?=$nutrition_info?> (в %)</th>
                        <th class="text-center align-middle" rowspan="2" style="max-width: 150px">сутки (в %)</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?$value_vitamin = $md->get_menu_information_one($post['id'], $m_nutrition->nutrition_id);//print_r($value_vitamin);

                    if ($value_vitamin == 'null'){
                        print_r('--------------------------------ОШИБКА. ПРИЕМ ПИЩИ '.\common\models\NutritionInfo::findOne($m_nutrition->nutrition_id)->name.' НЕ ЗАПОЛНЕН------------------');exit;
                    }
                    $salt_sahar_mas[$m_nutrition->nutrition_id]['salt'] = $value_vitamin['salt'];
                    $salt_sahar_mas[$m_nutrition->nutrition_id]['sahar'] = $value_vitamin['sahar'];
                    $salt_sahar_mas[$m_nutrition->nutrition_id]['yield'] = $value_vitamin['yield'];
                    //-------ИТОГОВЫЕ СУММЫ ПО СОЛИ И САХАРУ//
                    $salt_sahar_mas['total_salt'] = $salt_sahar_mas['total_salt'] + $value_vitamin['salt'];
                    $salt_sahar_mas['total_sahar'] = $salt_sahar_mas['total_sahar'] + $value_vitamin['sahar'];


                    //$salt_sahar_mas[$m_nutrition->nutrition_id]['kkal'] = $value_vitamin['kkal'];
                    if(!empty($value_vitamin['kkal_neok'])){
                        $kkal_string_neok .= \common\models\NutritionInfo::findOne($m_nutrition->nutrition_id)->name.' - '.count($value_vitamin['kkal_neok']).' дня(ей)';
                        $kkal_neok_mas[] = $value_vitamin['kkal_neok'];
                    }
                    if(!empty($value_vitamin['kkal_ok'])){
                        $kkal_string_ok .= \common\models\NutritionInfo::findOne($m_nutrition->nutrition_id)->name.' - '.count($value_vitamin['kkal_ok']).' дня(ей)';
                    }
                    ?>
<!--                    Выводим строчку по массе-->
                    <? $normativ_yield = \common\models\NormativVitaminDayNew::find()->where(['name' => 'yield', 'nutrition_id' => $m_nutrition->nutrition_id, 'age_info_id' => $my_menus->age_info_id])->one()->value/* + (\common\models\NormativVitaminDayNew::find()->where(['name' => 'yield', 'nutrition_id' => $m_nutrition->nutrition_id, 'age_info_id' => $my_menus->age_info_id])->one()->value *$lager_koef)*/;?>
                    <? $normativ_day = \common\models\NormativVitaminDay::find()->where(['name' => 'yield', 'age_info_id' => $my_menus->age_info_id])->one()->value/* + (\common\models\NormativVitaminDay::find()->where(['name' => 'yield', 'age_info_id' => $my_menus->age_info_id])->one()->value* $lager_koef)/* + (\common\models\NormativVitaminDayNew::find()->where(['name' => 'yield', 'nutrition_id' => $m_nutrition->nutrition_id, 'age_info_id' => $my_menus->age_info_id])->one()->value *$lager_koef)*/;?>
                    <?//if (empty($normativ_day) || $normativ_day == 0){
                        //print_r('--------------------------------ОШИБКА. НОРМАТИВ ПО МАССЕ');exit;
                    //}
                    //print_r($normativ_yield);?>
                    <?if (empty($normativ_yield) || $normativ_yield == 0){
                        print_r('--------------------------------ОШИБКА. НОРМАТИВ ПО МАССЕ');exit;
                    } ?>
                    <tr>
                        <td class="">Масса(г)</td>
                        <td class="text-center <?if(ceil($value_vitamin['yield'] * 10) / 10 < $normativ_yield){echo 'bg-danger';}?>"><?=ceil($value_vitamin['yield'] * 10) / 10;?></td>
                        <td class="text-center"><?=$normativ_yield?></td>
                        <td class="text-center"><?=round(ceil($value_vitamin['yield'] * 10) / 10/$normativ_yield, 2)*100;?>%</td>

                        <td class="text-center"><?=round(ceil($value_vitamin['yield'] * 10) / 10/$normativ_day, 2)*100;?>%</td>
                    </tr>
                    <?foreach ($vitamins_mas as $key => $vitamin_m){ ?>

                        <? $normativ_day = \common\models\NormativVitaminDay::find()->where(['name' => $key, 'age_info_id' => $my_menus->age_info_id])->one()->value + (\common\models\NormativVitaminDay::find()->where(['name' => $key, 'age_info_id' => $my_menus->age_info_id])->one()->value*$lager_koef);?>
                        <?$itog_vitamin[$key] = $itog_vitamin[$key] + (ceil($value_vitamin[$key] * 10) / 10)?>
                        <?$itog_normativ[$key] = $itog_normativ[$key] + (round($normativ_day * $nutrition_koeff,1))?>
                        <?if (empty($normativ_day) || $normativ_day == 0){
                            print_r('--------------------------------ОШИБКА. НОРМАТИВ ПО '. $vitamin_m);exit;
                        } ?>

                    <tr>
                        <td class=""><?=$vitamin_m;?></td>
                        <td class="text-center <?if(ceil($value_vitamin[$key] * 10) / 10 < round($normativ_day * $nutrition_koeff,2)){echo 'bg-danger';}?>"><?=ceil($value_vitamin[$key] * 10) / 10;?></td>
                        <td class="text-center"><?=round($normativ_day * $nutrition_koeff,1);?></td>
                        <td class="text-center"><?=round(ceil($value_vitamin[$key] * 10) / 10/round($normativ_day * $nutrition_koeff,2), 2)*100;?>%</td>

                        <td class="text-center"><?=round(ceil($value_vitamin[$key] * 10) / 10/$normativ_day, 2)*100;?>%</td>
                    </tr>
                    <?}?>
                    </tbody>
                </table>
            </div>



            <!--            калорийность бжу-->
            <? if(round($value_vitamin['kkal'],1) != 0){
                    $kkal_bju_mas[$m_nutrition->nutrition_id]['protein'] = round(((round($value_vitamin['protein'], 1) * 4) / round($value_vitamin['kkal'], 1))*100, 1);
                    $kkal_bju_mas[$m_nutrition->nutrition_id]['fat'] = round(((round($value_vitamin['fat'], 1) * 9) / round($value_vitamin['kkal'], 1))*100, 1);
                    $kkal_bju_mas[$m_nutrition->nutrition_id]['carbohydrates'] = round(((round($value_vitamin['carbohydrates'], 1) * 4) / round($value_vitamin['kkal'], 1))*100, 1);
                }else{
                    $kkal_bju_mas[$m_nutrition->nutrition_id]['protein'] = 0;
                    $kkal_bju_mas[$m_nutrition->nutrition_id]['fat'] = 0;
                    $kkal_bju_mas[$m_nutrition->nutrition_id]['carbohydrates'] = 0;
                }
            ?>


            <!--Рассчитаем здесь инфу по колбасным изделиям кондитеским и бутерам-->
            <? if($value_vitamin['kolbasa'] > 0){
                $kolbasa_neok .= \common\models\NutritionInfo::findOne($m_nutrition->nutrition_id)->name.' - '.$value_vitamin['kolbasa']. ' ед. ';
            }
            if($value_vitamin['konditer'] > 0){
                $konditer_neok .= \common\models\NutritionInfo::findOne($m_nutrition->nutrition_id)->name.' - '.$value_vitamin['konditer']. ' ед. ';
            }
            if($value_vitamin['buterbrod'] > 0){
                $buterbrod_neok .= \common\models\NutritionInfo::findOne($m_nutrition->nutrition_id)->name.' - '.$value_vitamin['buterbrod']. ' ед. ';
            }
            if($value_vitamin['conservir_tomat'] > 0){
                $conservir_tomat_neok = 1;
            }
            if($value_vitamin['conservir_ogurec'] > 0){
                $conservir_ogurec_neok = 1;
            }
            ?>






<?/*print_r($value_vitamin);*/} /*print_r($salt_sahar_mas['total_salt']);*/?>


        <!--сборка плохих средних витаминов-->
        <?foreach ($vitamins_mas as $key => $vitamin_m){
            if((ceil($itog_vitamin[$key] * 10) / 10) < (ceil($itog_normativ[$key] * 10) / 10) && ($key != 'kkal' && $key != 'protein' && $key != 'fat' && $key != 'carbohydrates')){
                $vitamins_neok[$key] .= explode(',', $vitamin_m)[0] . ' ';
                $vitamins_effect[$key] =  explode(',', $vitamin_m)[0] . ' ';
            }elseif((ceil($itog_vitamin[$key] * 10) / 10) >= (ceil($itog_normativ[$key] * 10) / 10) && ($key != 'kkal' && $key != 'protein' && $key != 'fat' && $key != 'carbohydrates')){
                $vitamins_ok[$key] .= explode(',',$vitamin_m)[0].' ';
            }

            ?>
        <?}?>
    </div>



    <p class="text-center mt-4"><b>Информация о содержании соли и сахара в меню</b></p>
    <div class="tables">
        <?foreach ($menus_nutrition as $m_nutrition){
            /*if($m_nutrition->nutrition_id == 1 && \common\models\MenusNutrition::find()->where(['menu_id' => $post['id'], 'nutrition_id' => 2])->count()==0){
                $nutrition_koeff = \common\models\NutritionProcent::find()->where(['nutrition_id' => $m_nutrition->nutrition_id])->one()->procent/100 + $nutrition_koeff = \common\models\NutritionProcent::find()->where(['nutrition_id' => 2])->one()->procent/100;
            } else{*/
                $nutrition_koeff = \common\models\NutritionProcent::find()->where(['type_org' => \common\models\Organization::findOne(Yii::$app->user->identity->organization_id)->type_org, 'nutrition_id' => $m_nutrition->nutrition_id])->one()->procent/100;
            //}
            $nutrition_info = \common\models\NutritionInfo::find()->where(['id' => $m_nutrition->nutrition_id])->one()->name;?>

            <div class="table_block mb-5">
                <p class="text-center mt-4"><b>Меню <?=$nutrition_info?></b></p>
                <table class="table_th0 table-hover table-responsive last" >
                    <thead>
                    <tr>
                        <th class="text-center align-middle" rowspan="2" style="max-width: 150px">Показатели</th>
                        <th class="text-center align-middle" rowspan="2" style="max-width: 200px">Фактические значения по меню в среднем за день цикла (<?=$nutrition_info?>)</th>
                        <th class="text-center align-middle" rowspan="2" style="max-width: 200px">Рекомендуемые значения по меню в среднем за день цикла (<?=$nutrition_info?>) </th>
                        <th class="text-center align-middle" rowspan="2"  colspan="1" style="max-width: 290px">Удельный вес от рекомендуемой величины на <?=\common\models\NutritionInfo::findOne($m_nutrition->nutrition_id)->name?> (в %)  </th>
                    </tr>

                    </thead>
                    <tbody>
                        <tr>
                            <? $normativ_day = \common\models\NormativVitaminDayNew::find()->where(['name' => 'salt', 'age_info_id' => $my_menus->age_info_id, 'nutrition_id'=>$m_nutrition->nutrition_id])->one()->value?>
                            <? $total_normativ_nutrition = \common\models\NormativVitaminDayNew::find()->where(['name' => 'salt', 'age_info_id' => $my_menus->age_info_id, 'nutrition_id'=>ArrayHelper::map(\common\models\MenusNutrition::find()->where(['menu_id' => $my_menus->id])->all(), 'nutrition_id', 'nutrition_id')])->sum('value')?>
                            <td>Соль (г)</td>

                            <td class="text-center
                            <?if(round($salt_sahar_mas[$m_nutrition->nutrition_id]['salt'],1) > $normativ_day){
                                //if(round($salt_sahar_mas[$m_nutrition->nutrition_id]['salt'],1) < round($salt_sahar_mas['total_salt'], 1)){
                                if(round($salt_sahar_mas['total_salt'], 1) < $total_normativ_nutrition){
                                    echo 'bg-warning';
                                }else{
                                    echo 'bg-danger';
                                }
                            }?>">
                                <?=round($salt_sahar_mas[$m_nutrition->nutrition_id]['salt'],1);?>
                            </td>

                            <td class="text-center"><?=$normativ_day;?></td>
                            <td class="text-center"><? if($normativ_day == 0){ echo $salt_sahar_mas[$m_nutrition->nutrition_id]['salt'] *100;}else{ echo round($salt_sahar_mas[$m_nutrition->nutrition_id]['salt']/$normativ_day, 2)*100;}?>%</td>
                        </tr>

                        <tr>
                            <? $normativ_day = \common\models\NormativVitaminDayNew::find()->where(['name' => 'sahar', 'age_info_id' => $my_menus->age_info_id, 'nutrition_id'=>$m_nutrition->nutrition_id])->one()->value?>
                            <? $total_normativ_nutrition = \common\models\NormativVitaminDayNew::find()->where(['name' => 'sahar', 'age_info_id' => $my_menus->age_info_id, 'nutrition_id'=>ArrayHelper::map(\common\models\MenusNutrition::find()->where(['menu_id' => $my_menus->id])->all(), 'nutrition_id', 'nutrition_id')])->sum('value')?>
                            <td>Сахар (г)</td>
                            <td class="text-center
                            <?if(round($salt_sahar_mas[$m_nutrition->nutrition_id]['sahar'],1) >= $normativ_day){
                                //if(round($salt_sahar_mas[$m_nutrition->nutrition_id]['sahar'],1) < round($salt_sahar_mas['total_sahar'], 1)){
                                if(round($salt_sahar_mas['total_sahar'], 1) <= $total_normativ_nutrition){
                                    echo 'bg-warning';
                                }else{
                                    echo 'bg-danger';
                                }
                            } ?>">
                                <?=round($salt_sahar_mas[$m_nutrition->nutrition_id]['sahar'],1);?>
                            </td>
                            <td class="text-center"><?=$normativ_day;?></td>
                            <td class="text-center"><?if($normativ_day == 0){ echo $salt_sahar_mas[$m_nutrition->nutrition_id]['sahar'] *100;}else{ echo round($salt_sahar_mas[$m_nutrition->nutrition_id]['sahar']/$normativ_day, 2)*100;}?>%</td>
                        </tr>
                    </tbody>
                </table>

                <!--Рассчитаем здесь инфу по массе продукта-->
                <? $normativ_yield = \common\models\NormativVitaminDayNew::find()->where(['name' => 'yield', 'nutrition_id' => $m_nutrition->nutrition_id, 'age_info_id' => $my_menus->age_info_id])->one()->value;
                if(round($salt_sahar_mas[$m_nutrition->nutrition_id]['yield'], 1) < $normativ_yield){
                    $yield_string_neok .= \common\models\NutritionInfo::findOne($m_nutrition->nutrition_id)->name.' ';
                }
                else{
                    $yield_string_ok .= \common\models\NutritionInfo::findOne($m_nutrition->nutrition_id)->name.' ';
                }
                ?>

                <!--Рассчитаем здесь инфу по калориям продукта-->
<!--                --><?// $normativ_day = \common\models\NormativVitaminDay::find()->where(['name' => 'kkal', 'age_info_id' => $my_menus->age_info_id])->one()->value;
//                if($salt_sahar_mas[$m_nutrition->nutrition_id]['kkal'] < round($normativ_day * $nutrition_koeff,1)){
//                    $kkal_string_neok .= \common\models\NutritionInfo::findOne($m_nutrition->nutrition_id)->name.' ';
//                }
//                else{
//                    $kkal_string_ok .= \common\models\NutritionInfo::findOne($m_nutrition->nutrition_id)->name.' ';
//                }
//                ?>


                <!--Рассчитаем здесь инфу по соли и сахару-->
<!--                --><?// $salt_normativ_day = \common\models\NormativVitaminDayNew::find()->where(['name' => 'salt', 'age_info_id' => $my_menus->age_info_id, 'nutrition_id' => $m_nutrition->nutrition_id])->one()->value;
//                    $sahar_normativ_day = \common\models\NormativVitaminDayNew::find()->where(['name' => 'sahar', 'age_info_id' => $my_menus->age_info_id, 'nutrition_id' => $m_nutrition->nutrition_id])->one()->value;
//                if(round($salt_sahar_mas[$m_nutrition->nutrition_id]['salt'], 1) > $salt_normativ_day){
//                    $salt_string_neok .= \common\models\NutritionInfo::findOne($m_nutrition->nutrition_id)->name.' ';
//                }
//                if(round($salt_sahar_mas[$m_nutrition->nutrition_id]['sahar'], 1) > $sahar_normativ_day){
//                    $sahar_string_neok .= \common\models\NutritionInfo::findOne($m_nutrition->nutrition_id)->name.' ';
//                }
//                ?>
                <?$salt_normativ_day = \common\models\NormativVitaminDayNew::find()->where(['name' => 'salt', 'age_info_id' => $my_menus->age_info_id, 'nutrition_id' => ArrayHelper::map($menus_nutrition, 'nutrition_id', 'nutrition_id')])->sum('value');
                $sahar_normativ_day = \common\models\NormativVitaminDayNew::find()->where(['name' => 'sahar', 'age_info_id' => $my_menus->age_info_id, 'nutrition_id' => ArrayHelper::map($menus_nutrition, 'nutrition_id', 'nutrition_id')])->sum('value');

                $normativ_nutrition_salt= \common\models\NormativVitaminDayNew::find()->where(['name' => 'salt', 'age_info_id' => $my_menus->age_info_id, 'nutrition_id'=>$m_nutrition->nutrition_id])->one()->value;
                $normativ_nutrition_sahar= \common\models\NormativVitaminDayNew::find()->where(['name' => 'sahar', 'age_info_id' => $my_menus->age_info_id, 'nutrition_id'=>$m_nutrition->nutrition_id])->one()->value;
                if(round($salt_sahar_mas[$m_nutrition->nutrition_id]['salt'], 1) > $normativ_nutrition_salt && round($salt_sahar_mas['total_salt'], 1) > $salt_normativ_day){
                    $salt_string_neok .= \common\models\NutritionInfo::findOne($m_nutrition->nutrition_id)->name.' ';
                }
                if(round($salt_sahar_mas[$m_nutrition->nutrition_id]['sahar'], 1) > $normativ_nutrition_sahar && round($salt_sahar_mas['total_sahar'], 1) > $sahar_normativ_day){
                    $sahar_string_neok .= \common\models\NutritionInfo::findOne($m_nutrition->nutrition_id)->name.' ';
                }

                ?>




            </div>
        <?}//print_r($itog_vitamin);print_r($itog_normativ);?>
    </div>
    <div class="container" style="font-size: 18px;">
        <p class="text-center" style="font-size: 20px;"><b>Фрагмент заключительной части экспертного заключения</b></p>

            <!--СКРЫВАЕМ ПО ПРОСЬБЕ ИИ-->

<!--        --><?//if(!Yii::$app->user->can('food_director')){?>
<!--            <p >Учитывая режим функционирования общеобразовательной организации, обучающиеся 1-4 классов предусматривает --><?//if(count($smena_items) == 2){ echo 'две смены';}elseif (count($smena_items) == 1){ echo 'одну смену';}?><!--,-->
<!--                продленного дня – нет. Для питания обучающихся 1-4 классов-->
<!--                --><?//foreach ($smena_items as $smena_item){
//                    $count_p = 0;
//                    if($smena_item == 1){ echo ' в первую смену выделены ';
//                        foreach ($peremena_mas[$smena_item] as $p){
//                            $count_p++;
//                            echo mb_strtolower(\common\models\SmenaPeremena::findOne($p)->name).' перемена ';
//                            if(count($peremena_mas[$smena_item]) > 1 && count($peremena_mas[$smena_item]) != $count_p){
//                                echo ' и ';
//                            }
//                        }
//                    }
//                    if($smena_item == 2){ echo ' во вторую смену выделены ';
//                        foreach ($peremena_mas[$smena_item] as $p){
//                            $count_p++;
//                            echo mb_strtolower(\common\models\SmenaPeremena::findOne($p)->name).' перемена ';
//                            if(count($peremena_mas[$smena_item]) > 1 && count($peremena_mas[$smena_item]) != $count_p){
//                                echo ' и ';
//                            }
//                        }
//                    }
//                }?>
<!--                . Продолжительность перемены – --><?//=$duration_peremena?><!-- минут.</p>-->
<!--            --><?//}?>
<!---->
<!--        <p>--><?//=$notice_string?><!--. Все технологические карты заимствованы из --><?//=$recipes_string?><!--. В технологических картах-->
<!--            приведена информация о технологии приготовления блюд, калорийности, содержании белков, жиров и углеводов, витаминов и минералов,-->
<!--            для горячих блюд – информация о температуре их выдачи. Все технологические карты предусматривают использование щадящих способов-->
<!--            кулинарной обработки для приготовления блюд. Использование полуфабрикатов в организации при приготовлении блюд меню не планируется.-->
<!--            Наличие специализированной продукции, обогащенной витаминами и минералами в меню не предусмотрено-->
<!--            (регион не входит в число эндемичных территорий, за исключением дефицита йода). Для приготовления блюд используется йодированная соль.</p>-->
<!--        -->
<!--            <p>--><?//if($cs_mas['sahar'] == 0 && $cs_mas['ovz'] == 0 && $cs_mas['fenilketon'] == 0 && $cs_mas['mukovis'] == 0 && $cs_mas['allergy'] == 0){
//                echo 'Детей с сахарным диабетом, целиакией, пищевой аллергией, фенилкетонурией, муковисцидозом в организации – нет.';
//            }else{
//                echo $diseases_string;
//            }?><!--</p>-->

<!--        КОНЕЦ СКРЫТИ-->




        <p><b>Рассмотренное меню соответствует требованиям СанПиН 2.3/2.4.3590-20 по следующим пунктам: </b></p>
        <? $count = 1;?>

        <p class="ml-4"><?=$count?>. <b class="text-success">Меню безопасное</b> (запрещенных к употреблению в организованных детских коллективах блюд и продуктов – нет); </p>


        <?if(empty($repeat_mas)){ $count++;?>
            <p class="ml-4"><?=$count?>. <b class="text-success">Меню разнообразное</b> (повторов блюд в течение дня и двух смежных дней нет); </p>
        <?}else{ $count++;?>
            <?if(empty(array_count_values($repeat_mas)['between_one']) && empty(array_count_values($repeat_mas)['between_two'])){?>
                <p class="ml-4"><?=$count?>. Повторов блюд в двух смежных днях - <b class="text-success">нет</b>. </p>
            <?}?>
            <?if(empty(array_count_values($repeat_mas)['current'])){?>
                <p class="ml-4"><?=$count?>. Повторов блюд в течение дня - <b class="text-success">нет</b>. </p>
            <?}?>
        <?}?>

        <?if(!empty($yield_string_ok)){$count++;?>
            <p class="ml-4"><?=$count?>. Суммарная масса блюд по приемам пищи (<?=mb_strtolower($yield_string_ok);?>)
                <b class="text-success">соответствует</b> регламентированным значениям.</p>
        <?}?>

        <p class="ml-4"><?$count++; echo $count?>. <?if(!empty($kkal_string_ok)){?>Калорийность меню <b class="text-success">не ниже регламентированных значений</b> по приемам пищи: <?=$kkal_string_ok?>,<?}?>
            <?foreach ($menus_nutrition as $m_nutrition){
                echo 'Удельный вес белков, жиров и углеводов в '. mb_strtolower(\common\models\NutritionInfo::findOne($m_nutrition->nutrition_id)->name). ' '. $kkal_bju_mas[$m_nutrition->nutrition_id]['protein'].'%, '. $kkal_bju_mas[$m_nutrition->nutrition_id]['fat'].'%, и '. $kkal_bju_mas[$m_nutrition->nutrition_id]['carbohydrates'].'% соотвественно. ';
            }?>
        </p>



        <?if(!empty($vitamins_ok)){ $count++;?>
            <p class="ml-4"><?=$count?>. Потребность в витаминах и минеральных веществах <b class="text-success">соответствует </b>регламентированным показателям:</p>
            <p class="ml-5 text-success" style="margin-top: -4px;font-style: italic">
            <?foreach($vitamins_ok as $key => $vit){?>
                <?= $vit." ";?>
            <?}?>
            .</p>
        <?}?>


        <?if(empty($sahar_string_neok) && empty($salt_string_neok)){$count++;?>
            <p class="ml-4"><?=$count?>. <b class="text-success">В меню не превышены регламентированные уровни содержания соли и сахара.</b></p>
        <?}elseif(empty($sahar_string_neok) && !empty($salt_string_neok)){$count++;?>
            <p class="ml-4"><?=$count?>. <b class="text-success">В меню не превышены регламентированные уровни содержания сахара.</b></p>
        <?}elseif(!empty($sahar_string_neok) && empty($salt_string_neok)){$count++;?>
            <p class="ml-4"><?=$count?>. <b class="text-success">В меню не превышены регламентированные уровни содержания соли.</b></p>
        <? }?>


        <?if(empty($kolbasa_neok) || empty($konditer_neok) || empty($buterbrod_neok) || empty($conservir_tomat_neok) || empty($conservir_ogurec_neok)){$count++;?>
            <p class="ml-4"><?=$count?>. <b class="text-success">В меню отсутствуют <?if(empty($kolbasa_neok)){ echo 'колбасные изделия,';}?> <?if(empty($konditer_neok)){ echo 'кондитерские изделия,';}?> <?if(empty($buterbrod_neok)){ echo 'бутерброды, ';}?> <?if(empty($conservir_tomat_neok)){ echo 'консервированные томаты, ';}?> <?if(empty($conservir_ogurec_neok)){ echo 'консервированные огурцы';}?>.</b></p>
        <?}?>

        <!--Было принято решение, чтобы колбаса конд изделия и тд не считались причиной не соответсвия санпин а было как предупрежеднеия-->
        <?if(!empty($kolbasa_neok) || !empty($konditer_neok) || !empty($buterbrod_neok) || !empty($conservir_tomat_neok) || !empty($conservir_ogurec_neok)){$count++;?>
            <p class="ml-4"><?=$count?>. <b class="text-warning">В меню присутствуют <?if(!empty($kolbasa_neok)){ echo 'колбасные изделия,';}?> <?if(!empty($konditer_neok)){ echo 'кондитерские изделия,';}?> <?if(!empty($buterbrod_neok)){ echo 'бутерброды,';}?> <?if(!empty($conservir_tomat_neok)){ echo 'консервированные томаты,';}?> <?if(!empty($conservir_ogurec_neok)){ echo 'консервированные огурцы,';}?> рекомендуем исключить их из меню</b></p>
        <?}?>


        <?if(!empty($vitamins_neok) || !empty($sahar_string_neok) || !empty($salt_string_neok) || !empty($repeat_mas) || !empty($kkal_string_neok) || !empty($yield_string_neok)/* || !empty($kolbasa_neok) || !empty($konditer_neok)*/){?>
            <p><b>Меню не соответствует требованиям СанПиН 2.3/2.4.3590-20 по следующим пунктам: </b></p>
            <? $count = 0;?>

            <?if(!empty($repeat_mas)){ $count++;?>
                <?if(!empty(array_count_values($repeat_mas)['current'])){?>
                    <p class="ml-4"><?=$count?>. Обнаружено <b class="text-danger"><?=array_count_values($repeat_mas)['current']?></b> повтор(-ов) блюд в течение дня. </p>
                    <?foreach ($repeat_mas as $key => $repeat_m){?>
                        <?if($repeat_m == 'current'){?>
                            <p class="ml-5 text-danger" style="font-size: 15px;"><i><?$val = explode('_',$key); echo $val[0].'-я неделя '.Days::findOne($val[1])->name.' '. \common\models\Dishes::findOne($val[2])->name?></i></p>
                        <?}?>
                    <?}?>
                <?}?>

                <?if(!empty(array_count_values($repeat_mas)['between_one']) || !empty(array_count_values($repeat_mas)['between_two'])){?>
                    <p class="ml-4"><?=$count?>. Обнаружены повторы блюд в меню. </p><!--ДОБАВИТЬ ЕЩЕ $repeat_mas['between_two']-->
                    <?foreach ($repeat_mas as $key => $repeat_m){?>
                        <?if($repeat_m == 'between_one'){?>
                            <p class="ml-5 text-danger" style="font-size: 15px;"><i><?$val = explode('_',$key); if($val[0] > 1 && $val[1] == 1){$last_day = 7;$last_cycle = $val[0] - 1;}else{$last_day = $val[1]-1;$last_cycle = $val[0];}
                            echo \common\models\Dishes::findOne($val[2])->name. " " .Days::findOne($last_day)->name.'('. $last_cycle.'-я неделя )'.' и '. Days::findOne($val[1])->name.'('. $val[0].'-я неделя )';?></i></p>
                        <?}?>
                        <?if($repeat_m == 'between_two'){?>
                            <p class="ml-5 text-danger" style="font-size: 15px;"><i><?$val = explode('_',$key); if($val[0] > 1 && $val[1] == 1){$last_day = 6;$last_cycle = $val[0] - 1;}elseif($val[0] > 1 && $val[1] == 2){$last_day = 7;$last_cycle = $val[0] - 1;}else{$last_day = $val[1]-2;$last_cycle = $val[0];}
                                    echo \common\models\Dishes::findOne($val[2])->name. " " .Days::findOne($last_day)->name.'('. $last_cycle.'-я неделя)'.' и '. Days::findOne($val[1])->name.'('. $val[0].'-я неделя)';?></i></p>
                        <?}?>
                    <?}?>
                <?}?>

            <?}?>


            <?if(!empty($yield_string_neok)){$count++;?>
                <p class="ml-4"><?=$count?>. Суммарная масса блюд по приемам пищи (<?=mb_strtolower($yield_string_neok);?>) <b class="text-danger">не
                        соответствует </b> регламентированным значениям для данной возрастной группы.  </p>
            <?}?>

            <?if(!empty($kkal_string_neok)){$count++;?><p class="ml-4"><?=$count?>. Калорийность меню <b class="text-danger">ниже регламентированных значений</b> по приемам пищи: <?//=$kkal_string_neok?> </p>
            <table class="table_th0 table-hover ml-4" style="width: 60%; font-size: 14px;">
                <thead>
                <tr class="table-danger">
                    <th class="text-center align-middle" rowspan="2" style="width: 20px">Прием пищи</th>
                    <th class="text-center align-middle" rowspan="2" style="width: 40px">Неделя</th>
                    <th class="text-center align-middle" rowspan="2" style="width: 40px">День</th>
                    <th class="text-center align-middle" rowspan="2" style="width: 40px">Значение</th>
                    <th class="text-center align-middle" rowspan="2" style="width: 40px">Норматив</th>
                </tr>
                </thead>
                <tbody>
                <?foreach($kkal_neok_mas as $key => $kkal_neok_m){
                    if(!empty($kkal_neok_m)){
                        foreach($kkal_neok_m as $value_v){  $explode_mas = explode('_',$value_v);
                            $nutrition_koeff = \common\models\NutritionProcent::find()->where(['type_org' => $my_menus->type_org_id, 'nutrition_id' => $explode_mas[0]])->one()->procent/100;
                            $normativ_day = \common\models\NormativVitaminDay::find()->where(['name' => 'kkal', 'age_info_id' => $my_menus->age_info_id])->one()->value + (\common\models\NormativVitaminDay::find()->where(['name' => 'kkal', 'age_info_id' => $my_menus->age_info_id])->one()->value*$lager_koef);?>
                            <tr>
                                <td class="align-middle"><?=\common\models\NutritionInfo::findOne($explode_mas[0])->name; ?></td>
                                <td class="align-middle"><?= $explode_mas[1]?></td>
                                <td class="align-middle"><?=\common\models\Days::findOne($explode_mas[2])->name; ?></td>
                                <td class="text-center align-middle"><?=round($explode_mas[3],1) ?></td>
                                <td class="text-center align-middle"><?=round($normativ_day*$nutrition_koeff,2);?></td>
                            </tr>
                        <?}
                    }
                }?>
                </tbody>
            </table>
        <?}?>


            <?if(!empty($vitamins_neok)){$count++;?>
                <p class="ml-4"><?=$count?>. Потребность в витаминах и минеральных веществах <b class="text-danger">не соответствует</b> регламентированным показателям:</p>
                <p class="text-danger ml-5" style="margin-top: -4px; font-style: italic">
                <?foreach($vitamins_neok as $key => $vit){?>
                    <?= $vit." ";?>
                <?}?>
                .</p>
            <?}?>

            <?if(empty($sahar_string_neok) && !empty($salt_string_neok)){$count++;?>
                <p class="ml-4"><?=$count?>. <b class="text-danger">В меню превышены регламентированные уровни содержания соли.</b></p>
            <?}elseif(!empty($sahar_string_neok) && empty($salt_string_neok)){$count++;?>
                <p class="ml-4"><?=$count?>. <b class="text-danger">В меню превышены регламентированные уровни содержания сахара.</b></p>
            <?}elseif(!empty($sahar_string_neok) && !empty($salt_string_neok)){$count++;?>
                <p class="ml-4"><?=$count?>. <b class="text-danger">В меню превышены регламентированные уровни содержания соли и сахара.</b>  </p>
            <? }?>



        <?}?>



        <hr>
        <!--Было принято решение, чтобы колбаса конд изделия и тд не считались причиной не соответсвия санпин-->
        <?if(!empty($vitamins_neok) || !empty($sahar_string_neok) || !empty($salt_string_neok) || !empty($repeat_mas) || !empty($kkal_string_neok) || !empty($yield_string_neok)){?>
            <p><b class="text-danger" style="font-size: 20px!important;">Рассмотренное меню не соответствует требованиям СанПиН 2.3/2.4.3590-20
                    «Санитарно-эпидемиологические требования к организации общественного питания населения». </b></p>

        <?}else{?>
            <p><b class="text-success" style="font-size: 20px!important;">Рассмотренное меню отвечает принципам здорового питания и требованиям СанПиН 2.3/2.4.3590-20
                «Санитарно-эпидемиологические требования к организации общественного питания населения» и может быть предложено к утверждению и
                реализации. </b></p>
        <?}?>
        <hr>
        <?if(!empty($vitamins_effect)){?>
            <p style="font-size: 20px;"><b>Возможные неблагоприятные эффекты: </b></p>

            <table class="table_th0 table-hover table-responsive last" >
                <thead>
                <tr class="bg-danger">
                    <th class="text-center align-middle" rowspan="2" style="min-width: 150px">Показатели</th>
                    <th class="text-center align-middle" rowspan="2" style="min-width: 300px">Последствия недостаточного потребления:</th>
                </tr>

                </thead>
                <tbody>
                <?foreach ($vitamins_effect as $key => $vitamin_ef){?>
                    <tr>
                        <td class="text-center align-middle"><?=$vitamin_ef?></td>
                        <td class="align-middle"><?=$effect_bad[$key]?></td>
                    </tr>
                <?}?>
                </tbody>
            </table>
        <?}?>
        <p style="font-size: 20px;" class="mt-3"><b>Рекомендуется: </b></p>
        <?if(!empty($cs_mas['sahar']) && \common\models\Menus::find()->where(['organization_id' => $organization_id, 'feeders_characters_id' => 5])->count() == 0){?>
            <p class="ml-4">Составить меню для детей с сахарным диабетом.</p>
        <?}?>
        <?/*print_r($cs_mas);*/if(!empty($cs_mas['allergy']) && \common\models\Menus::find()->where(['organization_id' => $organization_id, 'feeders_characters_id' => 10])->count() == 0){?>
            <p class="ml-4">Составить меню для детей с пищевой аллергией.</p>
        <?}?>
        <?/*print_r($cs_mas);*/if(!empty($cs_mas['cialic']) && \common\models\Menus::find()->where(['organization_id' => $organization_id, 'feeders_characters_id' => 6])->count() == 0){?>
            <p class="ml-4">Составить меню для детей с целиакией.</p>
        <?}?>
        <p>-</p>
    </div>

        <br>
        <div class="text-center">
            <?= Html::a('<span class="glyphicon glyphicon-download" ></span> Скачать документ в PDF', ['expertiza-export-pdf?menu_id=' . $my_menus->id],
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
    <?}else{?>
        <p class="text-center mt-5" style="font-size: 24px;"><b>В меню не добавлены блюда.</b></p>
    <?}?>
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