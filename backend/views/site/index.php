<?php

/* @var $this yii\web\View */

use common\models\AnketParentControl;
use common\models\MenusSend;
use common\models\NutritionApplications;
use common\models\Organization;
use common\models\City;
use common\models\User;
use yii\bootstrap4\Html;
use yii\helpers\ArrayHelper;

$this->title = 'Главная';

$users = User::find()->where(['organization_id' => Yii::$app->user->identity->organization_id])->all();
$user_ids = [];
foreach($users as $user){
        $user_ids[] = $user->id;
}
$organization = \common\models\Organization::findOne(Yii::$app->user->identity->organization_id);
$perechen = \common\models\BasicInformationRazdelOrganization::find()->where(['organization_id' => Yii::$app->user->identity->organization_id])->all();
$perechen_oborudovaniya = \common\models\BasicInformation::find()->where(['organization_id' => Yii::$app->user->identity->organization_id])->all();
$characters_stolovaya = \common\models\CharactersStolovaya::find()->where(['organization_id' => Yii::$app->user->identity->organization_id])->all();
$peremens = \common\models\SchoolBreak::find()->where(['organization_id' => Yii::$app->user->identity->organization_id])->all();
$information_education = \common\models\InformationEducation::find()->where(['organization_id' => Yii::$app->user->identity->organization_id, 'year' => '2021/2022'])->all();
$characters_study = \common\models\StudentsClass::find()->where(['organization_id' => Yii::$app->user->identity->organization_id])->all();
$students = \common\models\Students::find()->where(['organization_id' => Yii::$app->user->identity->organization_id])->count();
$control_rod = \common\models\AnketParentControl::find()->where(['organization_id' => Yii::$app->user->identity->organization_id, 'status' => 1])->all();
$control_vnutr = \common\models\AnketParentControl::find()->where(['organization_id' => Yii::$app->user->identity->organization_id, 'status' => 2])->all();
$nastroika_menu = \common\models\Menus::find()->where(['organization_id' => Yii::$app->user->identity->organization_id, 'status_archive' => 0])->andWhere(['>=', 'date_end', strtotime(date('d.m.y'))])->all();
$nastroika_menu_count = \common\models\Menus::find()->where(['organization_id' => Yii::$app->user->identity->organization_id, 'status_archive' => 0])->andWhere(['>=', 'date_end', strtotime(date('d.m.y'))])->count();
$menu_bez_osobennostey = \common\models\Menus::find()->where(['organization_id' => Yii::$app->user->identity->organization_id, 'age_info_id' => [6, 9], 'feeders_characters_id' => 3, 'status_archive' => 0])->one();
$menus_dishes_bez_osobennostey = \common\models\MenusDishes::find()->where(['menu_id' => $menu_bez_osobennostey->id])->all();

$everyday_classes = \common\models\EverydayClasses::find()->where(['user_id' => $user_ids])->all();




$days_items = [];
$d = strtotime("+1 day");
$days = range(strtotime('2022-03-16'), strtotime(date('Y-m-d')), (24*60*60));
foreach ($days as $key => $day){
    $counts[] = \common\models\UserAutorizationStatistic::find()->where(['>=', 'time_auth', $day])->andWhere(['<=', 'time_auth', $day+24*60*60])->count();
}
foreach ($days as $key => $day){
    $days_items[] = date('d.m.Y', $day);
}
//print_r(Yii::$app->user->identity->organization_id);
?>

<style>
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
<div class="site-index">
    <h1 class="text-center mb-5"><?= Html::encode($this->title) ?></h1>
    <?$my_organization = \common\models\Organization::findOne(Yii::$app->user->identity->organization_id);
    $my_municipality = \common\models\Municipality::findOne($my_organization->municipality_id);?>
    <?if((Yii::$app->user->can('internat_director') || Yii::$app->user->can('kindergarten_director') || Yii::$app->user->can('school_director')) && $my_municipality->city_status == 1 && empty($my_organization->city_id)){?>
    <div class="container mt-4 mb-4" style="border:1px solid red; border-radius: 10px">
        <p class="text-danger text-center"><b>Укажите район города в разделе ОРГАНИЗАЦИЯ! <small><a href="http://demography.site/organizations/create"> (Перейти)</a></small></b></p>
    </div>
        <?}?>



    <div class="body-content">
	<?if(Yii::$app->user->can('school_director') || Yii::$app->user->can('medic') || Yii::$app->user->can('internat_director') || Yii::$app->user->can('foodworker')){?>
        <div class="container mt-4 mb-4" style="border:1px solid black; border-radius: 10px">
            <p class="text-center">Внешняя ссылка для внесения результатов мероприятий родительского контроля представителями родительского комитета(не требует авторизации): <b>https://demography.site/anket-parent-control/parent-outside-link?id=<?=Organization::findOne(Yii::$app->user->identity->organization_id)->anket_parent_control_link; ?></b></p>
        </div>

        <div class="block-items">
            <div class="organization item">
                <p class="text-center title"><b>Вкладка «Организация»</b></p>
                <div class="content" style="margin-left: 5px">
                    <?if(empty($organization->short_title) || empty($organization->address) || empty($organization->phone) || empty($organization->email) || empty($organization->name_dir) || empty($organization->inn)){?>
                        <p><b>Общая информация: </b><b class="text-danger">Не заполнено</b><small><a href="http://demography.site/organizations/create"> (Перейти)</a></small></p>
                    <?}else{?>
                        <p><b>Общая информация: </b><b class="text-success">Заполнено</b><small><a href="http://demography.site/organizations/create"> (Перейти)</a></small></p></p>
                    <?}?>
                    <?if(empty($perechen)){?>
                        <p><b>Перечень произв. помещений пищеблока: </b><b class="text-danger">Не заполнено</b><small><a href="http://demography.site/basic-information/razdel"> (Перейти)</a></small></p>
                    <?}else{?>
                        <p><b>Перечень произв. помещений пищеблока: </b><b class="text-success">Заполнено</b><small><a href="http://demography.site/basic-information/razdel"> (Перейти)</a></small></p>
                    <?}?>
                    <?if(empty($perechen_oborudovaniya)){?>
                        <p><b>Перечень оборудования произв. помещений пищеблока: </b><b class="text-danger">Не заполнено</b><small><a href="http://demography.site/basic-information/create"> (Перейти)</a></small></p>
                    <?}else{?>
                        <p><b>Перечень оборудования произв. помещений пищеблока: </b><b class="text-success">Заполнено</b><small><a href="http://demography.site/basic-information/create"> (Перейти)</a></small></p>
                    <?}?>
                    <?if(empty($characters_stolovaya)){?>
                        <p><b>Характеристика работы пищеблока: </b><b class="text-danger">Не заполнено</b><small><a href="http://demography.site/characters-stolovaya/index"> (Перейти)</a></small></p>
                    <?}else{?>
                        <p><b>Характеристика работы пищеблока: </b><b class="text-success">Заполнено</b><small><a href="http://demography.site/characters-stolovaya/index"> (Перейти)</a></small></p>
                    <?}?>

                    <?if(empty($peremens)){?>
                        <p><b>Перемены исп. для организации питания: </b><b class="text-danger">Не заполнено</b><small><a href="http://demography.site/school-break/index"> (Перейти)</a></small></p>
                    <?}else{?>
                        <p><b>Перемены исп. для организации питания: </b><b class="text-success">Заполнено</b><small><a href="http://demography.site/school-break/index"> (Перейти)</a></small></p>
                    <?}?>

                    <?if(empty($information_education)){?>
                        <p><b>Информация о количестве обучающихся: </b><b class="text-danger">Не заполнено</b><small><a href="http://demography.site/information-education/index"> (Перейти)</a></small></p>
                    <?}else{?>
                        <p><b>Информация о количестве обучающихся: </b><b class="text-success">Заполнено</b><small><a href="http://demography.site/information-education/index"> (Перейти)</a></small></p>
                    <?}?>
                    <hr>
                    <?if(empty($characters_study) ){?>
                        <p><b>Характеристика обучающихся: </b><b class="text-danger">Не заполнено</b><small><a href="http://demography.site/characters-study/index-new"> (Перейти)</a></small></p>
                        <p style="font-size: 7px;"><?=Html::a('Видео по заполнению "Характеристика обучающихся"', 'https://www.youtube.com/watch?v=jVrogZM7FQk', [
                                'class' => 'btn btn-danger btn-sm', 'target'=>'_blank'
                            ]) ?></p>
                    <?}else{?>
                        <p><b>Характеристика обучающихся: </b><b class="text-success">Внесено(<?=$students?> детей)</b><small><a href="http://demography.site/characters-study/index-new"> (Перейти)</a></small></p>
                    <?}?>
                    <hr>
                    <?if(empty($control_rod)){?>
                        <p><b>Родительский контроль: </b><b class="text-danger">Не заполнено</b><small><a href="http://demography.site/anket-parent-control/create"> (Перейти)</a></small></p>
                    <?}else{?>
                        <?$last_control_rod_date = AnketParentControl::find()->where(['organization_id' => Yii::$app->user->identity->organization_id, 'status' => 1])->orderBy('id desc')->one()->date?>
                        <?if($last_control_rod_date + 60*60*24*30 < strtotime(date('d.m.Y'))){?>
                            <p><b>Родительский контроль: </b><small><b class="text-danger">С последнего момента внесения данных прошло более одного месяца, необходимо внести данные</b><a href="http://demography.site/anket-parent-control/create"> (Перейти)</a></small></p>
                            <p style="color: #0ea1a8">Дата последнего мероприятия: <?= date('d.m.Y', $last_control_rod_date);?></p>
                        <?}else{?>
                            <p><b>Родительский контроль: </b><b class="text-success">Заполнено</b><small><a href="http://demography.site/anket-parent-control/create"> (Перейти)</a></small></p>
                            <p style="color: #0ea1a8">Дата последнего мероприятия: <?= date('d.m.Y', $last_control_rod_date);?></p>
                        <?}?>
                    <?}?>
                    <hr>

                    <?if(empty($control_vnutr)){?>
                        <p><b>Внутренний контроль: </b><b class="text-danger">Не заполнено</b><small><a href="http://demography.site/anket-parent-control/inside"> (Перейти)</a></small></p>
                    <?}else{?>
                        <?$last_control_vnutr_date = AnketParentControl::find()->where(['organization_id' => Yii::$app->user->identity->organization_id, 'status' => 2])->orderBy('id desc')->one()->date?>
                        <?if($last_control_vnutr_date + 60*60*24*30 < strtotime(date('d.m.Y'))){?>
                            <p><b>Внутренний контроль: </b><small><b class="text-danger">С последнего момента внесения данных прошло более одного месяца, необходимо внести данные</b><a href="http://demography.site/anket-parent-control/inside"> (Перейти)</a></small></p>
                            <p style="color: #0ea1a8">Дата последнего мероприятия: <?= date('d.m.Y', $last_control_vnutr_date);?></p>
                        <?}else{?>
                            <p><b>Внутренний контроль: </b><b class="text-success">Заполнено</b><small><a href="http://demography.site/anket-parent-control/inside"> (Перейти)</a></small></p>
                            <p style="color: #0ea1a8">Дата последнего мероприятия: <?= date('d.m.Y', $last_control_vnutr_date);?></p>
                        <?}?>
                    <?}?>

                    <hr>

                </div>
            </div>
            <div class="nutrition item">
                <p class="text-center title"><b>Вкладка «Организация питания»</b></p>
                <div class="content" style="margin-left: 5px">
                    <?if(empty($nastroika_menu)){?>
                        <p><b>Работа с меню: </b><b class="text-danger">Меню не внесено</b><small><a href="http://demography.site/menus/create"> (Перейти)</a></small></p>
                        <p style="font-size: 7px;"><?=Html::a('Видео инструкция по разработке меню', 'https://www.youtube.com/watch?v=7AdfJTsJ6_k', [
                                'class' => 'btn btn-danger btn-sm', 'target'=>'_blank'
                            ]) ?></p>
                    <?}else{?>
                        <p><b>Работа с меню: </b><b class="text-success">Внесено <?=$nastroika_menu_count?> меню</b></p>
                        <?foreach($nastroika_menu as $menu){$days_neok_count = 0;$menus_days = \common\models\MenusDays::find()->where(['menu_id' => $menu->id])->all();?>
                            <?if($menu->cycle > 6){?>
                                <p class="ml-4 "><small><i class="glyphicon glyphicon-exclamation-sign mr-1 text-danger"></i>Вероятно, в меню с ID: <b class="text-danger"><?=$menu->id?></b> ошибка в цикле меню</small></p>
                            <?}?>
                            <?for($i=1;$i<=$menu->cycle;$i++){?>
                                <?foreach($menus_days as $day){?>
                                    <?if(\common\models\MenusDishes::find()->where(['menu_id' => $menu->id, 'cycle' =>$i, 'days_id'=>$day->days_id])->count() == 0){
                                        $days_neok_count++;
                                    }?>
                                <?}?>
                            <?}?>
                            <?if($days_neok_count>0){?>
                                <p class="ml-4"><small><i class="glyphicon glyphicon-exclamation-sign mr-1 text-danger"></i>В меню с ID: <b class="text-danger"><?=$menu->id?></b> не внесены блюда по <?=$days_neok_count?> дням</small></p>
                            <?}?>
                        <?}?>
                    <?}?>



					<?$sbornics = \common\models\RecipesCollection::find()->where(['organization_id' => Yii::$app->user->identity->organization_id])->all();
                        if(!empty($sbornics)){
                            foreach ($sbornics as $sbornic){
                                $dishes = \common\models\Dishes::find()->where(['recipes_collection_id' => $sbornic->id])->all();
                                foreach ($dishes as $dish){
                                    if(empty(\common\models\DishesProducts::find()->where(['dishes_id' => $dish->id])->all())){?>
                                        <p class="ml-3">В блюдо: <b class="text-danger"><?=$dish->name;?></b> не добавлены продукты!<small><a href="http://demography.site/dishes/addproduct?id=<?=$dish->id;?>"> (Перейти к добавлению)</a></small></p>
                                    <?}?>
                                <?}
                            }
                        }
                    ?>


                </div>
            </div>


    </div>

        <p class="text-center"><?= Html::a('Проверить меню на соответствие СанПин', ['/menus-dishes/expertiza'], ['class' => 'btn btn-info btn-lg mb-3']) ?></p>
        <p class="text-center"><?= Html::a('Отчет по мероприятиям родительского и внутреннего контроля', ['anket-parent-control/report'], ['class' => 'btn btn-success btn-lg']) ?></p>

        <?}?>


        <?if(Yii::$app->user->can('kindergarten_director')){?>
        <div class="block-items">
            <div class="nutrition item">
                <p class="text-center title"><b>Вкладка «Организация питания»</b></p>
                <div class="content" style="margin-left: 5px">
                    <?if(empty($nastroika_menu)){?>
                        <p><b>Работа с меню: </b><b class="text-danger">Меню не внесено</b><small><a href="http://demography.site/menus/create"> (Перейти)</a></small></p>
                        <p style="font-size: 7px;"><?=Html::a('Видео инструкция по разработке меню', 'https://www.youtube.com/watch?v=7AdfJTsJ6_k', [
                                'class' => 'btn btn-danger btn-sm', 'target'=>'_blank'
                            ]) ?></p>

                    <?}else{?>
                        <p><b>Работа с меню: </b><b class="text-success">Внесено <?=$nastroika_menu_count?> меню</b></p>
                    <?}?>



                    <?$sbornics = \common\models\RecipesCollection::find()->where(['organization_id' => Yii::$app->user->identity->organization_id])->all();
                    if(!empty($sbornics)){
                        foreach ($sbornics as $sbornic){
                            $dishes = \common\models\Dishes::find()->where(['recipes_collection_id' => $sbornic->id])->all();
                            foreach ($dishes as $dish){
                                if(empty(\common\models\DishesProducts::find()->where(['dishes_id' => $dish->id])->all())){?>
                                    <p class="ml-3">В блюдо: <b class="text-danger"><?=$dish->name;?></b> не добавлены продукты!<small><a href="http://demography.site/dishes/addproduct?id=<?=$dish->id;?>"> (Перейти к добавлению)</a></small></p>
                                <?}?>
                            <?}
                        }
                    }
                    ?>


                </div>
            </div>
        </div>
            <p class="text-center"><?= Html::a('Проверить меню на соответствие СанПин', ['/menus-dishes/expertiza'], ['class' => 'btn btn-info btn-lg']) ?></p>
        <?}?>
		
		<?if(Yii::$app->user->can('admin')){?>
            <?/*$organizations = Organization::find()->where(['type_org' => 1])->all();
            $org_ids = ArrayHelper::map($organizations, 'id', 'id');
            $users = User::find()->where(['organization_id' => $org_ids])->all();
            $users_ids = ArrayHelper::map($users, 'id', 'id');
            $chat_count = \common\models\Chat::find()->where(['sender_user_id' => $users_ids])->count();
            $chats = \common\models\Chat::find()->where(['sender_user_id' => $users_ids])->all();
            foreach($chats as $chat){
                echo $chat->sender_user_id.' ';
            }
            print_r($chat_count);*/?>
            <style>
                .super-block{
                    display: flex;
                    justify-content: space-between;

                }
                .super-block2{
                    display: flex;
                    flex-wrap: wrap;
                    margin-bottom: 30px;
                    margin-top: 30px;
                    justify-content: space-around;

                }
                .box-shadow {
                    /*width: 80%;*/
                    min-width: 375px;
                    min-height: 180px;
                    /*margin: 1em auto;*/
                    padding: 1em;
                    box-shadow: 0 2px 4px rgba(0, 0, 0, .2);
                    margin-left: 20px;
                    margin-bottom: 30px;
                    background: linear-gradient(to left, orange 2%, transparent 2%);
                    border-radius: 10px;
                }
                .box-shadow:hover {
                    box-shadow: 0 4px 8px rgba(0, 0, 0, .2);
                    cursor: pointer;

                }
                .title_number{
                    margin-top: 30px;
                    font-size: 32px;
                    color: #6610f2
                }
            </style>
            <div class="super-block">
                <div class="block" style="width: 45%!important;">
                    <canvas id="myChart" width="600" height="170"></canvas>

<!--                    <p class="text-center">Средняя посещаемость в день: <b>--><?//=round(\common\models\UserAutorizationStatistic::find()->count()/(count($days_items)+13),1);?><!-- человек</b></p>-->
                </div>

                <div class="block" style="width: 45%!important;">
                    <canvas id="myChartEveryday" width="600" height="170"></canvas>

                    <p class="text-center">Всего внесено данных: <b><?=\common\models\EverydayDailyInfo::find()->count();?></b> <small> с 4.09.2020</small></p>
                </div>
            </div>


            <p class="text-center" style="font-size: 32px;">Статистика в цифрах</p>
            <div class="super-block2">

                <div class="box-shadow">
                    <p class="text-center"><b>Количество детей(1-4 класс) внесено в 2021 году:</b></p>
                    <?$organizations = ArrayHelper::map(Organization::find()->all(), 'id', 'id');?>
                    <?$students_class = ArrayHelper::map(\common\models\StudentsClass::find()->where(['organization_id' => $organizations, 'class_number' => [1,2,3,4]])->all(), 'id', 'id');?>
                    <?$users_count= \common\models\Students::find()->where(['students_class_id' => $students_class])->count(); ?>
                    <p class="text-center title_number"><b><?=$users_count?></b></p>
                </div>


                <div class="box-shadow">
                    <p class="text-center"><b>Всего разработано сборников рецептур:</b></p>
                    <?$recipes = \common\models\RecipesCollection::find()->all(); $count_recipes = 0;
                    foreach($recipes as $recipe){
                        $dishes_recipe = \common\models\Dishes::find()->where(['recipes_collection_id' => $recipe->id])->count();
                        if($dishes_recipe >= 1){
                            $count_recipes++;
                        }
                    }?>
                    <p class="text-center title_number"><b><?=$count_recipes?></b></p>
                </div>


                <div class="box-shadow">
                    <p class="text-center"><b>Всего разработано технологических карт:</b></p>
                    <?$dishes = \common\models\Dishes::find()->count();?>
                    <p class="text-center title_number"><b><?=$dishes?></b></p>
                </div>


                <div class="box-shadow">
                    <p class="text-center"><b>Всего разработано меню:</b></p>
                    <?$menus = \common\models\Menus::find()->where(['status_archive' => 0])->count();?>
                    <p class="text-center title_number"><b><?=$menus?></b></p>
                </div>


                <div class="box-shadow">
                    <p class="text-center"><b>Количество применений меню разработанных НИИ:</b></p>
                    <?$menus_archive_nii = \common\models\Menus::find()->where(['status_archive' => 1, 'organization_id' => Yii::$app->user->identity->organization_id])->all();
                      $menus_items = ArrayHelper::map($menus_archive_nii, 'id', 'id');
                      $menus_used_count = \common\models\Menus::find()->where(['parent_id' => $menus_items])->orWhere(['parent_id' => [83]])->count();
                    ?>

                    <p class="text-center title_number"><b><?=$menus_used_count?></b></p>
                </div>


                <div class="box-shadow">
                    <p class="text-center"><b>Количество блюд во всех меню:</b></p>
                    <?$menus_dishes_count = \common\models\MenusDishes::find()->where(['date_fact_menu' => 0])->count();?>
                    <p class="text-center title_number"><b><?=$menus_dishes_count?></b></p>
                </div>


                <div class="box-shadow">
                    <p class="text-center"><b>Всего продуктов в программе:</b></p>
                    <?$products_count = \common\models\Products::find()->count();?>
                    <p class="text-center title_number"><b><?=$products_count?></b></p>
                </div>


                <div class="box-shadow">
                    <p class="text-center"><b>Проведено мероприятий <br>родительского и внутреннего контроля:</b></p>
                    <?$controls_count = \common\models\AnketParentControl::find()->count();?>
                    <p class="text-center title_number"><b><?=$controls_count?></b></p>
                </div>


                <div class="box-shadow">
                    <p class="text-center"><b>Зарегистрировано участников:</b></p>
                    <?$users_count = \common\models\User::find()->count();?>
                    <p class="text-center title_number"><b><?=$users_count?></b></p>
                </div>


                <div class="box-shadow">
                    <p class="text-center"><b>Зарегистрировано организаций:</b></p>
                    <?$organizations_count = \common\models\Organization::find()->count();?>
                    <p class="text-center title_number"><b><?=$organizations_count?></b></p>
                </div>

                <div class="box-shadow">
                    <p class="text-center"><b>Количество человек, обратившихся за помощью в чат:</b></p>
                    <?$chats = \common\models\Chat::find()->all();
                        foreach ($chats as $chat){
                            if(!array_key_exists($chat->sender_user_id, $users)){
                                $users[$chat->sender_user_id] = $chat->sender_user_id;
                            }
                        }
                    ?>
                    <p class="text-center title_number"><b><?=count($users);?></b></p>
                </div>

            </div>
<!--        <div class="super-block2">-->
<!--            <div class="box-shadow">-->
<!--                <p class="text-center"><b>ТОП 10 самых активных участников:</b></p>-->
<!--                --><?//$posts = Yii::$app->db->createCommand('SELECT user_id, COUNT(*) as cnt FROM user_autorization_statistic GROUP BY user_id ORDER BY cnt DESC LIMIT 10')
//                    ->queryAll();?>
<!---->
<!--                --><?//foreach ($posts as $post){?>
<!--                    <p class="text-center title_number" style="font-size: 10px;"><b>--><?//=User::findOne($post['user_id'])->name.'-'.$post['cnt'];?><!--</b></p>-->
<!--                --><?//} ?>
<!--            </div>-->
<!--        </div>-->




        <?$date_minus7 = strtotime('-15 days');
            $days_items_for_everydays = [];
            $days_for_everydays = range($date_minus7, strtotime(date('Y-m-d')), (24*60*60));
            foreach ($days_for_everydays as $key => $days_for_everyday){
                $counts_everyday[] = \common\models\EverydayDailyInfoNew::find()->where(['date' => date('d.m.Y', $days_for_everyday)])->count();
            }
            foreach ($days_for_everydays as $key => $days_for_everyday){
                $days_items_for_everydays[] = date('d.m.Y', $days_for_everyday);
            }
		}?>





		<?if(Yii::$app->user->can('food_director')){?>
            <div class="block-items">
                <div class="nutrition item">
                    <p class="text-center title"><b>Вкладка «Организация питания»</b></p>
                    <div class="content" style="margin-left: 5px">
                        <?if(empty($nastroika_menu)){?>
                            <p><b>Настройка меню: </b><b class="text-danger">Не заполнено(меню не создано)</b><small><a href="http://demography.site/menus/create"> (Перейти)</a></small></p>
                            <p style="font-size: 7px;"><?=Html::a('Видео инструкция по разработке меню', 'https://www.youtube.com/watch?v=7AdfJTsJ6_k', [
                                    'class' => 'btn btn-danger btn-sm', 'target'=>'_blank'
                                ]) ?></p>
                        <?}else{?>
                            <p><b>Настройка меню: </b><b class="text-success">Заполнено</b></p>
                        <?}?>
                        <?if(empty($menu_bez_osobennostey)){?>
                            <p class="ml-3">Меню(без особенностей): <b class="text-danger">Не создано</b><small><a href="http://demography.site/menus/create"> (Перейти)</a></small></p>
                        <?}else{?>
                            <p class="ml-3">Меню(без особенностей): <b class="text-success">Создано</b><?if(empty($menus_dishes_bez_osobennostey)){?>,<b class="text-danger"> блюда не добавлены</b><small><a href="http://demography.site/menus-dishes/index"> (Перейти)</a></small><?}?></p>
                        <?}?>

                        <?$sbornics = \common\models\RecipesCollection::find()->where(['organization_id' => Yii::$app->user->identity->organization_id])->all();
                        if(!empty($sbornics)){
                            foreach ($sbornics as $sbornic){
                                $dishes = \common\models\Dishes::find()->where(['recipes_collection_id' => $sbornic->id])->all();
                                foreach ($dishes as $dish){
                                    if(empty(\common\models\DishesProducts::find()->where(['dishes_id' => $dish->id])->all())){?>
                                        <p class="ml-3">В блюдо: <b class="text-danger"><?=$dish->name;?></b> не добавлены продукты!<small><a href="http://demography.site/dishes/addproduct?id=<?=$dish->id;?>"> (Перейти к добавлению)</a></small></p>
                                    <?}?>
                                <?}
                            }
                        }
                        ?>

                    </div>
                </div>


            </div>




            <style>
                .super-block{
                    display: flex;
                    justify-content: space-between;
                }
                .super-block2{
                    display: flex;
                    flex-wrap: wrap;
                    margin-bottom: 30px;
                    margin-top: 30px;
                    justify-content: space-around;

                }
                .box-shadow {
                    /*width: 80%;*/
                    min-width: 375px;
                    min-height: 180px;
                    /*margin: 1em auto;*/
                    padding: 1em;
                    box-shadow: 0 2px 4px rgba(0, 0, 0, .2);
                    margin-left: 20px;
                    margin-bottom: 30px;
                    background: linear-gradient(to left, orange 2%, transparent 2%);
                    border-radius: 10px;
                }
                .box-shadow:hover {
                    box-shadow: 0 4px 8px rgba(0, 0, 0, .2);
                    cursor: pointer;

                }
                .title_number{
                    margin-top: 30px;
                    font-size: 32px;
                    color: #6610f2
                }
            </style>



            <div class="super-block2">
                <div class="box-shadow">
                    <p class="text-center"><b>Количество образовательных учреждений <br>работающих с оператором питания:</b></p>
                    <?$dishes = NutritionApplications::find()->where(['sender_org_id' => Yii::$app->user->identity->organization_id, 'type_org_id' => 3, 'status' => 1])->orWhere(['reciever_org_id' => Yii::$app->user->identity->organization_id, 'type_org_id' => 3, 'status' => 1])->count();?>
                    <p class="text-center title_number"><b><?=$dishes?></b></p>
                </div>
                <div class="box-shadow">
                    <p class="text-center"><b>Всего разработано меню:</b></p>
                    <?$menus = \common\models\Menus::find()->where(['status_archive' => 0, 'organization_id' => Yii::$app->user->identity->organization_id])->count();?>
                    <p class="text-center title_number"><b><?=$menus?></b></p>
                </div>
                <div class="box-shadow">
                    <p class="text-center"><b>Всего разработано технологических карт:</b></p>
                    <?$recipes = \common\models\RecipesCollection::find()->where(['organization_id' => Yii::$app->user->identity->organization_id])->all();
                      $recipes_items = ArrayHelper::map($recipes, 'id', 'id');
                      $dishes = \common\models\Dishes::find()->where(['recipes_collection_id' => $recipes_items])->count();?>
                    <p class="text-center title_number"><b><?=$dishes?></b></p>
                </div>
                <div class="box-shadow">
                    <p class="text-center"><b>Количество отправленных меню в организации:</b></p>
                    <?$menus_dishes_count = MenusSend::find()->where(['sender_org_id' => Yii::$app->user->identity->organization_id, 'reciever_type_org' => 3])->count();?>
                    <p class="text-center title_number"><b><?=$menus_dishes_count?></b></p>
                </div>
            </div>

            <p class="text-center"><?= Html::a('Проверить меню на соответствие СанПин', ['/menus-dishes/expertiza'], ['class' => 'btn btn-info btn-lg']) ?></p>
            <?}?>













        <?if(Yii::$app->user->can('rospotrebnadzor_nutrition') || Yii::$app->user->can('minobr') || (Yii::$app->user->can('subject_minobr') && empty($my_organization->city_id))){?>

            <style>
                .super-block{
                    display: flex;
                    justify-content: space-between;

                }
                .super-block2{
                    display: flex;
                    flex-wrap: wrap;
                    margin-bottom: 30px;
                    margin-top: 30px;
                    justify-content: space-around;

                }
                .box-shadow {
                    /*width: 80%;*/
                    min-width: 375px;
                    min-height: 180px;
                    /*margin: 1em auto;*/
                    padding: 1em;
                    box-shadow: 0 2px 4px rgba(0, 0, 0, .2);
                    margin-left: 20px;
                    margin-bottom: 30px;
                    background: linear-gradient(to left, orange 2%, transparent 2%);
                    border-radius: 10px;
                }
                .box-shadow:hover {
                    box-shadow: 0 4px 8px rgba(0, 0, 0, .2);
                    cursor: pointer;

                }
                .title_number{
                    margin-top: 30px;
                    font-size: 32px;
                    color: #6610f2
                }
            </style>



            <div class="super-block2">
                <?$organization_id = Yii::$app->user->identity->organization_id;
                $region_id = Organization::findOne($organization_id)->region_id;
                $my_mun = Organization::findOne($organization_id)->municipality_id;?>


                <div class="box-shadow">
                    <?if(Yii::$app->user->can('rospotrebnadzor_nutrition') || Yii::$app->user->can('minobr')){?>
                        <p class="text-center"><b>Всего образовательных учреждений <br>зарегистрировано в программе по региону:</b></p>
                        <?$orgs = \common\models\Organization::find()->where(['region_id' => $region_id, 'type_org' => 3])->count();?>
                    <?}else{?>
                        <p class="text-center"><b>Всего образовательных учреждений <br>зарегистрировано в программе по муниципальному району:</b></p>
                        <?$orgs = \common\models\Organization::find()->where(['municipality_id' => $my_mun, 'type_org' => 3])->count();?>
                    <?}?>
                    <p class="text-center title_number"><b><?=$orgs?></b></p>
                </div>

                <div class="box-shadow">
                    <?if(Yii::$app->user->can('rospotrebnadzor_nutrition') || Yii::$app->user->can('minobr')){?>
                        <p class="text-center"><b>Всего операторов питания <br>зарегистрировано в программе по региону:</b></p>
                        <?$orgs = \common\models\Organization::find()->where(['region_id' => $region_id, 'type_org' => 4])->count();?>
                    <?}else{?>
                        <p class="text-center"><b>Всего операторов питания <br>зарегистрировано в программе по муниципальному району:</b></p>
                        <?$orgs = \common\models\Organization::find()->where(['municipality_id' => $my_mun, 'type_org' => 4])->count();?>
                    <?}?>
                    <p class="text-center title_number"><b><?=$orgs?></b></p>
                </div>


                <div class="box-shadow">
                    <p class="text-center"><b>Количество образовательных учреждений <br>работающих с оператором питания:</b></p>
                    <?if(Yii::$app->user->can('rospotrebnadzor_nutrition') || Yii::$app->user->can('minobr')){?>
                        <?$orgs_operator = \common\models\Organization::find()->where(['region_id' => $region_id, 'type_org' => 4])->all();?>
                    <?}else{?>
                        <?$orgs_operator = \common\models\Organization::find()->where(['municipality_id' => $my_mun, 'type_org' => 4])->all();?>
                    <?}?>
                    <?$orgs_operator_item = ArrayHelper::map($orgs_operator, 'id', 'id');?>
                    <?$count_connect = NutritionApplications::find()->where(['sender_org_id' => $orgs_operator_item, 'type_org_id' => 3, 'status' => 1])->orWhere(['reciever_org_id' => $orgs_operator_item, 'type_org_id' => 3, 'status' => 1])->count();?>
                    <p class="text-center title_number"><b><?=$count_connect?></b></p>
                </div>


                <div class="box-shadow">
                    <p class="text-center"><b>Количество образовательных учреждений, <br>у которых не внесено меню:</b></p>
                    <?if(Yii::$app->user->can('rospotrebnadzor_nutrition') || Yii::$app->user->can('minobr')){?>
                        <?$count_org = 0; $orgs_school = \common\models\Organization::find()->where(['region_id' => $region_id, 'type_org' => 3])->all();?>
                    <?}else{?>
                        <?$count_org = 0; $orgs_school = \common\models\Organization::find()->where(['municipality_id' => $my_mun, 'type_org' => 3])->all();?>
                    <?}?>
                    <?foreach ($orgs_school as $orgs_sch){
                        if(\common\models\Menus::find()->where(['organization_id' => $orgs_sch->id, 'status_archive' => 0])->count() == 0){
                            $count_org++;
                        }
                    }?>
                    <p class="text-center title_number"><b><?=$count_org?></b></p>
                </div>

            </div>
            <?if(Yii::$app->user->can('rospotrebnadzor_nutrition') || Yii::$app->user->can('minobr')){?>
                <p class="text-center mb-3"><?= Html::a('Мониторинг работы образовательных учреждений региона', ['/menus-dishes/report-school'], ['class' => 'btn btn-info btn-lg']) ?></p>
                <p class="text-center"><?= Html::a('Отчет о внесенной информации', ['/menus/report-minobr-rpn-vnesen'], ['class' => 'btn btn-success btn-lg']) ?></p>
            <?}else{?>
                <p class="text-center"><?= Html::a('Мониторинг работы образовательных учреждений мун.района', ['/menus-dishes/report-school'], ['class' => 'btn btn-info btn-lg']) ?></p>
            <?}?>
        <?}?>




        <?if((Yii::$app->user->can('subject_minobr') && !empty($my_organization->city_id))){?>
            <p class="text-center text-success"><b><?=City::findOne($my_organization->city_id)->name?></b></p>

<style>
    .super-block{
        display: flex;
        justify-content: space-between;

    }
    .super-block2{
        display: flex;
        flex-wrap: wrap;
        margin-bottom: 30px;
        margin-top: 30px;
        justify-content: space-around;

    }
    .box-shadow {
        /*width: 80%;*/
        min-width: 375px;
        min-height: 180px;
        /*margin: 1em auto;*/
        padding: 1em;
        box-shadow: 0 2px 4px rgba(0, 0, 0, .2);
        margin-left: 20px;
        margin-bottom: 30px;
        background: linear-gradient(to left, orange 2%, transparent 2%);
        border-radius: 10px;
    }
    .box-shadow:hover {
        box-shadow: 0 4px 8px rgba(0, 0, 0, .2);
        cursor: pointer;

    }
    .title_number{
        margin-top: 30px;
        font-size: 32px;
        color: #6610f2
    }
</style>



<div class="super-block2">
    <?$organization_id = Yii::$app->user->identity->organization_id;
    $region_id = Organization::findOne($organization_id)->region_id;
    $my_mun = Organization::findOne($organization_id)->municipality_id;?>


    <div class="box-shadow">
            <p class="text-center"><b>Всего образовательных учреждений <br>зарегистрировано в программе по <?=City::findOne($my_organization->city_id)->name?>:</b></p>
            <?$orgs = \common\models\Organization::find()->where(['city_id' => $my_organization->city_id, 'type_org' => 3])->count();?>
    
        <p class="text-center title_number"><b><?=$orgs?></b></p>
    </div>

    <div class="box-shadow">
            <p class="text-center"><b>Всего операторов питания <br>зарегистрировано в программе по <?=City::findOne($my_organization->city_id)->name?>:</b></p>
            <?$orgs = \common\models\Organization::find()->where(['city_id' => $my_organization->city_id, 'type_org' => 4])->count();?>
        <p class="text-center title_number"><b><?=$orgs?></b></p>
    </div>



    <div class="box-shadow">
        <p class="text-center"><b>Количество образовательных учреждений, <br>у которых не внесено меню:</b></p>
            <?$count_org = 0; $orgs_school = \common\models\Organization::find()->where(['city_id' => $my_organization->city_id, 'type_org' => 3])->all();?>
        <?foreach ($orgs_school as $orgs_sch){
            if(\common\models\Menus::find()->where(['organization_id' => $orgs_sch->id, 'status_archive' => 0])->count() == 0){
                $count_org++;
            }
        }?>
        <p class="text-center title_number"><b><?=$count_org?></b></p>
    </div>

</div>

    <p class="text-center"><?= Html::a('Мониторинг работы образовательных учреждений', ['/menus-dishes/report-school'], ['class' => 'btn btn-info btn-lg']) ?></p>

<?}?>



        <?if(Yii::$app->user->can('camp_director')){?>

            <style>
                .super-block{
                    display: flex;
                    justify-content: space-between;

                }
                .super-block2{
                    display: flex;
                    flex-wrap: wrap;
                    margin-bottom: 30px;
                    margin-top: 30px;
                    justify-content: space-around;

                }
                .box-shadow {
                    /*width: 80%;*/
                    min-width: 375px;
                    min-height: 180px;
                    /*margin: 1em auto;*/
                    padding: 1em;
                    box-shadow: 0 2px 4px rgba(0, 0, 0, .2);
                    margin-left: 20px;
                    margin-bottom: 30px;
                    background: linear-gradient(to left, orange 2%, transparent 2%);
                    border-radius: 10px;
                }
                .box-shadow:hover {
                    box-shadow: 0 4px 8px rgba(0, 0, 0, .2);
                    cursor: pointer;

                }
                .title_number{
                    margin-top: 30px;
                    font-size: 32px;
                    color: #6610f2
                }
            </style>



            <div class="super-block2">
                <?$organization_id = Yii::$app->user->identity->organization_id;
                $region_id = Organization::findOne($organization_id)->region_id;
                $my_mun = Organization::findOne($organization_id)->municipality_id;?>


                <div class="box-shadow">
                    <p class="text-center"><b>Количество разработанных меню:</b></p>
                    <?$menu_count = \common\models\Menus::find()->where(['organization_id' =>$organization_id])->andWhere(['>=', 'date_end', strtotime("now")])->count()?>
                    <p class="text-center title_number"><b><?=$menu_count?></b></p>
                    <?if($menu_count == 0){?>
                    <p style="font-size: 7px;"><?=Html::a('Видео инструкция по разработке меню', 'https://www.youtube.com/watch?v=7AdfJTsJ6_k', [
                            'class' => 'btn btn-danger btn-sm', 'target'=>'_blank'
                        ]) ?></p>
                    <?}?>
                </div>

                <div class="box-shadow">
                    <p class="text-center"><b>Количество детей в лагере за все сезоны:</b></p>
                    <?$kids_count = \common\models\Kids::find()->where(['organization_id' =>$organization_id])->count()?>
                    <p class="text-center title_number"><b><?=$kids_count?></b></p>
                    <?if($kids_count == 0){?>
                        <p><b>Список детей: </b><b class="text-danger">Не заполнено</b><small><a href="http://demography.site/kids/kids-med-create"> (Перейти)</a></small></p>
                    <?}?>
                </div>



            </div>
        <p class="text-center"><?= Html::a('Проверить меню на соответствие СанПин', ['/menus-dishes/expertiza'], ['class' => 'btn btn-info btn-lg']) ?></p>

    <?}?>


        <?if(Yii::$app->user->can('hidden_user')){?>

            <style>
                .super-block{
                    display: flex;
                    justify-content: space-between;

                }
                .super-block2{
                    display: flex;
                    flex-wrap: wrap;
                    margin-bottom: 30px;
                    margin-top: 30px;
                    justify-content: space-around;

                }
                .box-shadow {
                    /*width: 80%;*/
                    min-width: 375px;
                    min-height: 180px;
                    /*margin: 1em auto;*/
                    padding: 1em;
                    box-shadow: 0 2px 4px rgba(0, 0, 0, .2);
                    margin-left: 20px;
                    margin-bottom: 30px;
                    background: linear-gradient(to left, orange 2%, transparent 2%);
                    border-radius: 10px;
                }
                .box-shadow:hover {
                    box-shadow: 0 4px 8px rgba(0, 0, 0, .2);
                    cursor: pointer;

                }
                .title_number{
                    margin-top: 30px;
                    font-size: 32px;
                    color: #6610f2
                }
            </style>



            <div class="super-block2">
                <?$organization_id = Yii::$app->user->identity->organization_id;
                $region_id = Organization::findOne($organization_id)->region_id;
                $my_mun = Organization::findOne($organization_id)->municipality_id;?>


                <div class="box-shadow">
                    <p class="text-center"><b>Количество разработанных меню:</b></p>
                    <?$menu_count = \common\models\Menus::find()->where(['organization_id' =>$organization_id])->andWhere(['>=', 'date_end', strtotime("now")])->count()?>
                    <p class="text-center title_number"><b><?=$menu_count?></b></p>
                    <?if($menu_count == 0){?>
                        <p style="font-size: 7px;"><?=Html::a('Видео инструкция по разработке меню', 'https://www.youtube.com/watch?v=7AdfJTsJ6_k', [
                                'class' => 'btn btn-danger btn-sm', 'target'=>'_blank'
                            ]) ?></p>
                    <?}?>
                </div>

                <div class="box-shadow">
                    <p class="text-center"><b>Количество сборников рецептур:</b></p>
                    <?$kids_count = \common\models\RecipesCollection::find()->where(['organization_id' =>$organization_id])->count()?>
                    <p class="text-center title_number"><b><?=$kids_count?></b></p>

                </div>

                <div class="box-shadow">
                    <p class="text-center"><b>Количество блюд:</b></p>
                    <?$dishes_items = \common\models\Dishes::find()->where(['recipes_collection_id' => ArrayHelper::map(\common\models\RecipesCollection::find()->where(['organization_id' => $organization_id])->all(), 'id', 'id')])->count();?>
                    <p class="text-center title_number"><b><?=$dishes_items?></b></p>

                </div>



            </div>
            <p class="text-center"><?= Html::a('Проверить меню на соответствие СанПин', ['/menus-dishes/expertiza'], ['class' => 'btn btn-info btn-lg']) ?></p>

        <?}?>


</div>





<script>
var ctx = document.getElementById('myChart').getContext('2d');
var myChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: <?php echo json_encode($days_items);?>,
        datasets: [{
            label: 'Посещаемость',
            data: <?php echo json_encode($counts);?>,
            backgroundColor: [
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(153, 102, 255, 0.2)',
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(153, 102, 255, 0.2)',
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(153, 102, 255, 0.2)',
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(153, 102, 255, 0.2)',
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(153, 102, 255, 0.2)',
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(153, 102, 255, 0.2)',
                'rgba(255, 159, 64, 0.2)'
            ],
            borderColor: [
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 1)'
            ],
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero: true
                }
            }]
        }
    }
});










var ctx = document.getElementById('myChartEveryday').getContext('2d');
var myChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: <?php echo json_encode($days_items_for_everydays);?>,
        datasets: [{
            label: 'Количество введенных данных по классам в ежедневной информации',
            data: <?php echo json_encode($counts_everyday);?>,
            backgroundColor: [
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(153, 102, 255, 0.2)',
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(153, 102, 255, 0.2)',
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(153, 102, 255, 0.2)',
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(153, 102, 255, 0.2)',
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(153, 102, 255, 0.2)',
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(153, 102, 255, 0.2)',
                'rgba(255, 159, 64, 0.2)'
            ],
            borderColor: [
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 1)'
            ],
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero: true
                }
            }]
        }
    }
});


    </script>
