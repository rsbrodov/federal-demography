<?php

/* @var $this \yii\web\View */

/* @var $content string */

use backend\assets\AppAsset;
use yii\bootstrap4\Html;
use yii\bootstrap4\Nav;
use yii\bootstrap4\NavBar;
use yii\helpers\ArrayHelper;
use yii\bootstrap4\Breadcrumbs;
use yii\bootstrap4\Alert;
use common\models\Organization;
use common\models\SelectOrgForm;
use yii\bootstrap4\ActiveForm;
use xtetis\bootstrap4glyphicons\assets\GlyphiconAsset;
//header('Access-Control-Allow-Origin: *');
GlyphiconAsset::register($this);

AppAsset::register($this);
date_default_timezone_set('Europe/Moscow');
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <!--    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/gh/loadingio/ldLoader@v1.0.0/dist/ldld.min.css" >-->
    <!--    <script src="https://cdn.jsdelivr.net/gh/loadingio/ldLoader@v1.0.0/dist/ldld.min.js"></script>-->
    <!-- Yandex.Metrika counter -->
    <script type="text/javascript">
        (function (m, e, t, r, i, k, a)
        {
            m[i] = m[i] || function ()
                {
                    (m[i].a = m[i].a || []).push(arguments)
                };
            m[i].l = 1 * new Date();
            k = e.createElement(t), a = e.getElementsByTagName(t)[0], k.async = 1, k.src = r, a.parentNode.insertBefore(k, a)
        })
        (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");

        ym(67339711, "init", {
            clickmap: true,
            trackLinks: true,
            accurateTrackBounce: true,
            webvisor: true
        });
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js"></script>


    

    <noscript>
        <div><img src="https://mc.yandex.ru/watch/67339711" style="position:absolute; left:-9999px;" alt=""/></div>
    </noscript>
    <!-- /Yandex.Metrika counter -->
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
<?
$my_organization = Organization::findOne(Yii::$app->user->identity->organization_id);
if ($my_organization->type_org == 1)
{
    ?>
    <style>
        .main-color {
            background-color: #5eb057 !important;
        }
        ht
    </style>

<? }
else
{
    ?>
    <style>
        .main-color {
            background-color: #0ea1a8 !important;
        }

        .dropdown-menu-right-2 {
            right: auto !important;;
            left: 0 !important;;
        }
    </style>
<? } ?>
<style>
    .menu-custom {
        font-family: serif !important;
    }
</style>
<div class="wrap">
    <?php
    NavBar::begin([
        //'brandLabel' => Yii::$app->name,
        //'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-dark main-color menu-custom navbar-expand-lg p-0',
        ],
    ]);

    $model_user = new \common\models\User();
    if (Yii::$app->user->isGuest)
    {
        //$menuItems[] = ['label' => 'Регистрация в ПС "Оценка эффективности оздоровления детей" для детских оздоровительных организаций', 'url' => ['/site/signup'], 'options' => ['class' => 'btn btn-outline-light ml-4 mr-3 mt-2 mb-2']];
        //$menuItems[] = ['label' => 'Регистрация в ПС "Питание и мониторинг здоровья"', 'url' => ['/site/signup-nutrition'], 'options' => ['class' => 'btn btn-outline-light mr-3 mt-2 mb-2']];
        $menuItems[] = ['label' => 'Авторизация', 'url' => ['/site/login'], 'options' => ['class' => 'btn btn-outline-light mr-3 mt-2 mb-2']];
        $menuItems[] = ['label' => 'Анкета по изучению занятости детей в августе месяце', 'url' => ['/anket-employment-children-june/create'], 'options' => ['class' => 'btn btn-outline-light mr-3 mt-2 mb-2']];
        //$menuItems[] = ['label' => 'Анкетирование', 'url' => ['#'], 'options' => ['class' => 'btn btn-outline-light mr-3 mt-2 mb-2'],
        //'items'=>[
        //    ['label' => 'Анкета школьников', 'url' => ['/anket-children/create'], 'options' => ['class' => '']],
        //    ['label' => 'Анкета родителей школьников', 'url' => ['/anket-parents-school-children/create'], 'options' => ['class' => '']],
        //    ['label' => 'Анкета родителей дошкольников', 'url' => ['/anket-preschoolers/create'], 'options' => ['class' => '']],
        //    ['label' => 'Анкета учителей', 'url' => ['/anket-teacher/create'], 'options' => ['class' => '']],
        //['label' => 'Отчет по школьникам', 'url' => ['/anket-children/report'], 'options' => ['class' => '']],
        //['label' => 'Отчет по родителям школьников', 'url' => ['/anket-parents-school-children/report'], 'options' => ['class' => '']],
        //['label' => 'Отчет по родителям дошкольников', 'url' => ['/anket-preschoolers/report'], 'options' => ['class' => '']],
        //['label' => 'Отчет по учителям', 'url' => ['/anket-teacher/report'], 'options' => ['class' => '']],
        //]
        //];
        /*$menuItems[] = ['label' => 'Анкетирование лагерей', 'url' => ['#'], 'options' => ['class' => 'btn main-button-2-hover-orange mr-3 mt-2 mb-2'],
            'items'=>[
                ['label' => 'Анкета детей', 'url' => ['/anket-kids-camp/create-guest'], 'options' => ['class' => '']],
            ]
        ];*/
        $logout = '';
    }
    if (Yii::$app->user->can('admin'))
    {
        $menuItems = [
            ['label' => 'Главная', 'url' => ['site/index'], 'options' => ['class' => 'mr-3 p-2']],
            ['label' => 'Отчеты', 'url' => ['#'], 'options' => ['class' => 'mr-3 p-2'],
                'items' => [
                    //['label' => 'ОТЧЕТЫ', 'url' => ['#'], 'options' => ['class' => 'dropdown-item disabled']],
                    ['label' => 'Отчеты по анкеты 1-4 класс', 'url' => ['/anket-food/repoert-test-food'], 'options' => ['class' => '']],
                    ['label' => 'Анкета новая', 'url' => ['/anketa-daily-routine/report'], 'options' => ['class' => '']],
                    ['label' => 'Формирование документов для размещения на сайте', 'url' => ['/menus-dishes/report-document'], 'options' => ['class' => '']],
                    ['label' => 'Данные анкета новая', 'url' => ['/anketa-shapes/report-cor'], 'options' => ['class' => '']],
                    ['label' => 'Отчет по питанию детей по переменам', 'url' => ['/school-break/report-peremena'], 'options' => ['class' => '']],
                    ['label' => 'Гигиеническая оценка меню', 'url' => ['/menus-dishes/expertiza'], 'options' => ['class' => '']],
                    ['label' => 'Отчет по фейковым организациям', 'url' => ['/characters-study/organization-fake'], 'options' => ['class' => '']],
                    ['label' => 'Длинный отчет', 'url' => ['/menus-dishes/report-school'], 'options' => ['class' => '']],
                ]
            ],

            /*['label' => 'Организация', 'url' => ['#'], 'options' => ['class' => 'mr-3 p-2'],
                'items' => [
                    ['label' => 'Общая информация', 'url' => ['organizations/create'], 'options' => ['class' => '']],
                    ['label' => 'Перечень производственных помещений пищеблока', 'url' => ['basic-information/razdel'], 'options' => ['class' => '']],
                    ['label' => 'Перечень оборудования производственных помещений пищеблока', 'url' => ['basic-information/create'], 'options' => ['class' => '']],
                    ['label' => 'Характеристика работы пищеблока', 'url' => ['characters-stolovaya/index'], 'options' => ['class' => '']],
                    ['label' => 'Перемены, использующиеся для организации питания', 'url' => ['school-break/index'], 'options' => ['class' => '']],
                    //['label' => 'Информация о расходах на питание', 'url' => ['expenses-food/index'], 'options' => ['class' => '']],
                    ['label' => 'Информация о количестве обучающихся', 'url' => ['information-education/index'], 'options' => ['class' => '']],
                    ['label' => 'Характеристика обучающихся', 'url' => ['characters-study/index-new'], 'options' => ['class' => '']],
                    ['label' => 'Родительский контроль', 'url' => ['anket-parent-control/create'], 'options' => ['class' => '']],
                    ['label' => 'Внутренний контроль', 'url' => ['anket-parent-control/inside'], 'options' => ['class' => '']],
                    ['label' => 'Отчет по контрольным мероприятиям', 'url' => ['anket-parent-control/report'], 'options' => ['class' => '']],//['label' => 'Общественный контроль', 'url' => ['#'], 'options' => ['class' => '']],
                    //['label' => 'Производственный контроль', 'url' => ['#'], 'options' => ['class' => '']],
                ]
            ],*/

            ['label' => 'Организация питания', 'url' => ['#'], 'options' => ['class' => 'mr-3 p-2'],
                'items' => [
                    ['label' => 'Архив меню', 'url' => ['menus/archive'], 'options' => ['class' => 'm-5']],
                    ['label' => 'Настройки меню', 'url' => ['menus/index'], 'options' => ['class' => 'm-5']],
                    ['label' => 'Разработка (редактирование) цикличного меню', 'url' => ['menus-dishes/index'], 'options' => ['class' => '']],
                    ['label' => 'Просмотр действующего меню по дням', 'url' => ['menus-dishes/menus-days'], 'options' => ['class' => '']],
                    ['label' => 'Просмотр действующего меню за период', 'url' => ['menus-dishes/menus-period'], 'options' => ['class' => '']],
                    ['label' => 'Просмотр действующего меню за период(упрощенная)', 'url' => ['menus-dishes/menus-period-disable'], 'options' => ['class' => '']],
                    ['label' => 'Корректировка фактического меню по дате', 'url' => ['menus-dishes/fact-day-index'], 'options' => ['class' => '']],
                    ['label' => 'Фактическое меню за день', 'url' => ['menus-dishes/fact-date'], 'options' => ['class' => '']],
                    ['label' => 'Отчет о повторяемости', 'url' => ['menus-dishes/repeat-report'], 'options' => ['class' => '']],
                    ['label' => 'Отчет о перечне продуктов', 'url' => ['menus-dishes/products-list'], 'options' => ['class' => '']],
                    ['label' => 'Отчет о перечне продуктов за указанный период', 'url' => ['menus-dishes/fact-date-products-list'], 'options' => ['class' => '']],
                    ['label' => 'Прогнозная накопительная ведомость', 'url' => ['menus-dishes/prognos-storage'], 'options' => ['class' => '']],
                    //['label' => 'Фактическая накопительная ведомость', 'url' => ['menus-dishes/fact-storage'], 'options' => ['class' => '']],
                    //['label' => 'Фактическое меню за день', 'url' => ['menus-dishes/fact-day-index'], 'options' => ['class' => '']],
                    ['label' => 'Меню раскладка за день', 'url' => ['menus-dishes/raskladka'], 'options' => ['class' => '']],
                    ['label' => 'Проверка меню на аллергены', 'url' => ['/menus-dishes/show-allergen'], 'options' => ['class' => '']],
                    ['label' => 'Проверка меню на хлебные единицы', 'url' => ['/menus-dishes/show-sahar'], 'options' => ['class' => '']],
                    ['label' => 'Технологические карты с учетом количества питающихся (пересчет)', 'url' => ['menus-dishes/techmup-page'], 'options' => ['class' => '']],
                    ['label' => 'Сборники рецептур', 'url' => ['/settings/recipes-index'], 'options' => ['class' => '']],
                    ['label' => 'Добавить блюдо в сборник', 'url' => ['/dishes'], 'options' => ['class' => '']],
                    ['label' => 'База данных блюд', 'url' => ['dishes/dishes-base'], 'options' => ['class' => '']],
                    ['label' => 'База данных продуктов', 'url' => ['products/products-base'], 'options' => ['class' => '']],
                    ['label' => 'Замена продуктов в меню', 'url' => ['/products-change-organization/index'], 'options' => ['class' => '']],
                    ['label' => 'Отчет по витаминам и минеральным веществам', 'url' => ['/menus-dishes/report-vitamin'], 'options' => ['class' => '']],

                ]
            ],

            ['label' => 'Администратор питания', 'url' => ['#'], 'options' => ['class' => 'mr-3 p-2'],
                'items' => [
                    ['label' => 'База данных продуктов', 'url' => ['/products/products-base'], 'options' => ['class' => '']],
                    ['label' => 'База данных блюд', 'url' => ['/dishes/dishes-base'], 'options' => ['class' => '']],
                    ['label' => 'Редактирование продуктов', 'url' => ['/products'], 'options' => ['class' => '']],
                    ['label' => 'Редактирование блюд', 'url' => ['/dishes'], 'options' => ['class' => '']],
                    ['label' => 'Сборники рецептур(всех организаций)', 'url' => ['/settings/recipes-index-admin'], 'options' => ['class' => '']],
                    ['label' => 'Настройка коэффициентов брутто/нетто', 'url' => ['/brutto-netto-koef/index'], 'options' => ['class' => '']],
                    ['label' => 'Категории блюд', 'url' => ['/dishes-category/index'], 'options' => ['class' => '']],
                    ['label' => 'Категории продуктов', 'url' => ['/products-category/index'], 'options' => ['class' => '']],
                    ['label' => 'Подкатегории продуктов', 'url' => ['/products-subcategory/index'], 'options' => ['class' => '']],
                    //['label' => 'Нормативы по питанию', 'url' => ['/normativ-info/'], 'options' => ['class' => '']],
                    ['label' => 'Контроль ошибок', 'url' => ['/dishes-products/control'], 'options' => ['class' => '']],
                    ['label' => 'Общие настройки', 'url' => ['/settings-admin/index'], 'options' => ['class' => '']],
                    //['label' => 'Нормативы для прогнозной ведомости', 'url' => ['/normativ-prognos-storage'], 'options' => ['class' => '']],
                    ['label' => 'Аллергены', 'url' => ['/allergen/index'], 'options' => ['class' => '']],
                    ['label' => 'Замена продуктов в меню', 'url' => ['/products-change/index'], 'options' => ['class' => '']],
                    ['label' => 'Замена продуктов в сборниках рецептур(Администратор)', 'url' => ['/products-change-recipes-collection/index'], 'options' => ['class' => '']],
                    ['label' => 'Длинные отчеты', 'url' => ['/menus-dishes/report-school'], 'options' => ['class' => '']],
                    ['label' => 'Города/Районы', 'url' => ['/city/index'], 'options' => ['class' => '']],

                ]
            ],
            ['label' => 'Нормативы', 'url' => ['#'], 'options' => ['class' => 'mr-3 p-2'],
                'items' => [
                    //['label' => 'Нормативы по питанию', 'url' => ['/normativ-info/'], 'options' => ['class' => '']],
                    ['label' => 'Нормативы для прогнозной ведомости', 'url' => ['/normativ-prognos-storage'], 'options' => ['class' => '']],
                    ['label' => 'Нормативы по ХЕ', 'url' => ['/normativ-he'], 'options' => ['class' => '']],
                    ['label' => 'Нормативы по витаминам', 'url' => ['/normativ-vitamin-day'], 'options' => ['class' => '']],
                    ['label' => 'Нормативы по соль/сахар и МАССЕ', 'url' => ['/normativ-vitamin-day-new'], 'options' => ['class' => '']],
                ]
            ],
            /*['label' => 'Администратор анкетирования', 'url' => ['#'], 'options' => ['class' => 'mr-3 p-2'],
                'items' => [
                    ['label' => 'Анкета школьников', 'url' => ['/anket-children/create'], 'options' => ['class' => '']],
                    ['label' => 'Анкета родителей школьников', 'url' => ['/anket-parents-school-children/create'], 'options' => ['class' => '']],
                    ['label' => 'Анкета родителей дошкольников', 'url' => ['/anket-preschoolers/create'], 'options' => ['class' => '']],
                    ['label' => 'Анкета учителей', 'url' => ['/anket-teacher/create'], 'options' => ['class' => '']],
                    ['label' => 'Отчет по школьникам', 'url' => ['/anket-children/report'], 'options' => ['class' => '']],
                    ['label' => 'Отчет по родителям школьников', 'url' => ['/anket-parents-school-children/report'], 'options' => ['class' => '']],
                    ['label' => 'Отчет по родителям дошкольников', 'url' => ['/anket-preschoolers/report'], 'options' => ['class' => '']],
                    ['label' => 'Отчет по учителям', 'url' => ['/anket-teacher/report'], 'options' => ['class' => '']],
                ]
            ],*/
            ['label' => 'Администратор программы', 'url' => ['#'], 'options' => ['class' => 'mr-3 p-2'],
                'items' => [
                    ['label' => 'Отчет по регистрациям (ПС "Оценка эффективности оздоровления детей")', 'url' => ['/users/report'], 'options' => ['class' => '']],
                    ['label' => 'ПС "Питание" - отчет по организациям в уровне', 'url' => ['/users/nutrition-report'], 'options' => ['class' => '']],
                    ['label' => 'ПС "Питание" - отчет общий по всем уровням', 'url' => ['/users/nutrition-report-all-lvls'], 'options' => ['class' => '']],
                    ['label' => 'Муниципальные образования', 'url' => ['/municipality/index'], 'options' => ['class' => '']],
                    ['label' => 'Коллективный отчет', 'url' => ['/medicals/report-collective-admin'], 'options' => ['class' => '']],
                    ['label' => 'Оздоровление: Отчет по физическому развитию', 'url' => ['/medicals/report-physical-evolution-admin'], 'options' => ['class' => '']],
                    ['label' => 'Оздоровление: Отчет по группам здоровья - БЕТА', 'url' => ['/medicals/report-health-group-admin'], 'options' => ['class' => '']],
                    ['label' => 'Оздоровление: Отчет по возрастам', 'url' => ['/medicals/report-years-admin'], 'options' => ['class' => '']],
                    ['label' => 'Оздоровление: Баллы (непрерывная выгрузка) - БЕТА', 'url' => ['/medicals/report-kids-oeo-balls-non-stop-admin'], 'options' => ['class' => '']],
                    ['label' => 'Оздоровление: Средние показатели', 'url' => ['/medicals/report-average-indicators'], 'options' => ['class' => '']],
                ['label' => 'Оздоровление: Оценка эффективности по возрастам', 'url' => ['/medicals/report-oeo-for-ages'], 'options' => ['class' => '']]
            ]
        ],
            ['label' => 'Заявки на регистрацию', 'url' => ['users/request'], 'options' => ['class' => 'mr-3 p-2']],
            ['label' => 'Управление', 'url' => ['#'], 'options' => ['class' => 'mr-3 p-2'],
                'items' => [
                    ['label' => 'Организации', 'url' => ['/organizations/'], 'options' => ['class' => '']],
                    ['label' => 'Пользователи', 'url' => ['/users/'], 'options' => ['class' => '']],
                    ['label' => 'Дети', 'url' => ['/users/'], 'options' => ['class' => '']],
                ]
            ],
            ['label' => 'ЧАТ', 'url' => ['chat/index'], 'options' => ['class' => 'mr-3 p-2']],
            //['label' => 'Личные данные', 'url' => ['users/profile'], 'options' => ['class' => 'mr-3 p-2']],
            ['label' => 'Справочная информация', 'url' => ['site/download-document-index'], 'options' => ['class' => 'mr-3 p-2']],

        ];

        //$organization_name = Organization::find()->select('title')->where(['id'=>Yii::$app->user->identity->organization_id])->one();
        $logout = Html::begintag('div', ['class' => 'text-right'])
            .Html::tag('div', 'Пользователь:('.Yii::$app->user->identity->name.')', ['class' => ''])
            .Html::beginForm(['/site/logout'], 'post')
            .Html::submitButton(' Выход', ['class' => 'btn main-button-2-outline logout'])
            .Html::endForm()
            .Html::endTag('div');
    }


    if (Yii::$app->user->can('medicine_director'))
    {
        $menuItems = [
            ['label' => 'Организация', 'url' => ['#'], 'options' => ['class' => 'mr-3 p-2'],
                'items' => [
                    ['label' => 'Общая информация', 'url' => ['organizations/create'], 'options' => ['class' => '']],
                    ['label' => 'Перечень производственных помещений пищеблока', 'url' => ['basic-information/razdel'], 'options' => ['class' => '']],
                    ['label' => 'Перечень оборудования производственных помещений пищеблока', 'url' => ['basic-information/create'], 'options' => ['class' => '']],

                ]
            ],
            ['label' => 'Организация питания', 'url' => ['#'], 'options' => ['class' => 'mr-3 p-2'],
                'items' => [
                    ['label' => 'Архив меню', 'url' => ['menus/archive'], 'options' => ['class' => 'm-5']],
                    ['label' => 'Настройки меню', 'url' => ['menus/index'], 'options' => ['class' => 'm-5']],
                    ['label' => 'Разработка (редактирование) цикличного меню', 'url' => ['menus-dishes/index'], 'options' => ['class' => '']],
                    ['label' => 'Просмотр действующего меню по дням', 'url' => ['menus-dishes/menus-days'], 'options' => ['class' => '']],
                    ['label' => 'Просмотр действующего меню за период', 'url' => ['menus-dishes/menus-period'], 'options' => ['class' => '']],
                    ['label' => 'Отчет о повторяемости', 'url' => ['menus-dishes/repeat-report'], 'options' => ['class' => '']],
                    ['label' => 'Отчет о перечне продуктов', 'url' => ['menus-dishes/products-list'], 'options' => ['class' => '']],
                    ['label' => 'Отчет о перечне продуктов за указанный период', 'url' => ['menus-dishes/fact-date-products-list'], 'options' => ['class' => '']],
                    ['label' => 'Прогнозная накопительная ведомость', 'url' => ['menus-dishes/prognos-storage'], 'options' => ['class' => '']],
                    ['label' => 'Фактическое меню по дате', 'url' => ['menus-dishes/fact-date'], 'options' => ['class' => '']],
                    //['label' => 'Фактическая накопительная ведомость', 'url' => ['menus-dishes/fact-storage'], 'options' => ['class' => '']],
                    ['label' => 'Фактическое меню за день', 'url' => ['menus-dishes/fact-day-index'], 'options' => ['class' => '']],
                    ['label' => 'Меню раскладка за день', 'url' => ['menus-dishes/raskladka'], 'options' => ['class' => '']],
                    ['label' => 'Проверка меню на аллергены', 'url' => ['/menus-dishes/show-allergen'], 'options' => ['class' => '']],
                    ['label' => 'Проверка меню на хлебные единицы', 'url' => ['/menus-dishes/show-sahar'], 'options' => ['class' => '']],
                    ['label' => 'Технологические карты с учетом количества питающихся (пересчет)', 'url' => ['menus-dishes/techmup-page'], 'options' => ['class' => '']],
                    ['label' => 'Сборники рецептур', 'url' => ['/settings/recipes-index'], 'options' => ['class' => '']],
                    ['label' => 'Добавить блюдо в сборник', 'url' => ['/dishes'], 'options' => ['class' => '']],
                    ['label' => 'База данных блюд', 'url' => ['dishes/dishes-base'], 'options' => ['class' => '']],
                    ['label' => 'База данных продуктов', 'url' => ['products/products-base'], 'options' => ['class' => '']],
                ]],

            ['label' => 'Пользователи', 'url' => ['users/level'], 'options' => ['class' => 'mr-3 p-2'],
                'items' => [

                ]
            ],

        ];
        $logout = Html::begintag('div', ['class' => 'text-right'])
            .Html::tag('div', 'Пользователь:('.Yii::$app->user->identity->name.')', ['class' => ''])
            .Html::beginForm(['/site/logout'], 'post')
            .Html::submitButton(' Выход', ['class' => 'btn main-button-2-outline logout'])
            .Html::endForm()
            .Html::endTag('div');
    }


    if (Yii::$app->user->can('school_director') && Yii::$app->user->identity->organization_id != 306)
    {
        $menuItems = [
            ['label' => 'Главная', 'url' => ['/'], 'options' => ['class' => 'mr-3 p-2']],
            ['label' => 'Личные данные', 'url' => ['users/profile'], 'options' => ['class' => 'mr-3 p-2']],
            ['label' => 'Отчеты', 'url' => ['#'], 'options' => ['class' => 'main-button-2-hover-orange mr-3 p-2'],
                'items' => [
                    ['label' => 'Формирование документов для размещения на сайте', 'url' => ['/menus-dishes/report-document'], 'options' => ['class' => '']],
                    ['label' => 'Гигиеническая оценка меню', 'url' => ['/menus-dishes/expertiza'], 'options' => ['class' => '']],
                    ['label' => 'Отчет по контрольным мероприятиям', 'url' => ['anket-parent-control/report'], 'options' => ['class' => '']],

                ],
            ],
            ['label' => 'Организация', 'url' => ['#'], 'options' => ['class' => 'mr-3 p-2'],
                'items' => [
                    ['label' => 'Общая информация', 'url' => ['organizations/create'], 'options' => ['class' => '']],
                    ['label' => 'Перечень производственных помещений пищеблока', 'url' => ['basic-information/razdel'], 'options' => ['class' => '']],
                    ['label' => 'Перечень оборудования производственных помещений пищеблока', 'url' => ['basic-information/create'], 'options' => ['class' => '']],
                    ['label' => 'Характеристика работы пищеблока', 'url' => ['characters-stolovaya/index'], 'options' => ['class' => '']],
                    ['label' => 'Перемены, использующиеся для организации питания', 'url' => ['school-break/index'], 'options' => ['class' => '']],
                    ['label' => 'Информация о количестве обучающихся', 'url' => ['information-education/index'], 'options' => ['class' => '']],
                    ['label' => 'Характеристика обучающихся', 'url' => ['characters-study/index-new'], 'options' => ['class' => '']],
                    ['label' => 'Родительский контроль', 'url' => ['anket-parent-control/create'], 'options' => ['class' => '']],
                    ['label' => 'Внутренний контроль', 'url' => ['anket-parent-control/inside'], 'options' => ['class' => '']],
                    ['label' => 'Отчет по контрольным мероприятиям', 'url' => ['anket-parent-control/report'], 'options' => ['class' => '']],
                ]
            ],
            ['label' => 'Организация питания', 'url' => ['#'], 'options' => ['class' => 'mr-3 p-2'],
                'items' => [
                    ['label' => 'Архив меню', 'url' => ['menus/archive'], 'options' => ['class' => 'm-5']],
                    ['label' => 'Настройки меню', 'url' => ['menus/index'], 'options' => ['class' => 'm-5']],
                    ['label' => 'Разработка (редактирование) цикличного меню', 'url' => ['menus-dishes/index'], 'options' => ['class' => '']],
                    ['label' => 'Просмотр действующего меню по дням', 'url' => ['menus-dishes/menus-days'], 'options' => ['class' => '']],
                    ['label' => 'Просмотр действующего меню за период', 'url' => ['menus-dishes/menus-period'], 'options' => ['class' => '']],
                    ['label' => 'Корректировка фактического меню по дате', 'url' => ['menus-dishes/fact-day-index'], 'options' => ['class' => '']],
                    ['label' => 'Фактическое меню за день', 'url' => ['menus-dishes/fact-date'], 'options' => ['class' => '']],
                    ['label' => 'Отчет о повторяемости', 'url' => ['menus-dishes/repeat-report'], 'options' => ['class' => '']],
                    ['label' => 'Отчет о перечне продуктов', 'url' => ['menus-dishes/products-list'], 'options' => ['class' => '']],
                    ['label' => 'Отчет о перечне продуктов за указанный период', 'url' => ['menus-dishes/fact-date-products-list'], 'options' => ['class' => '']],
                    ['label' => 'Прогнозная накопительная ведомость', 'url' => ['menus-dishes/prognos-storage'], 'options' => ['class' => '']],
                    //['label' => 'Фактическая накопительная ведомость', 'url' => ['menus-dishes/fact-storage'], 'options' => ['class' => '']],
                    //['label' => 'Фактическое меню за день', 'url' => ['menus-dishes/fact-day-index'], 'options' => ['class' => '']],
                    ['label' => 'Меню раскладка за день', 'url' => ['menus-dishes/raskladka'], 'options' => ['class' => '']],
                    ['label' => 'Меню раскладка за день по приемам пищи', 'url' => ['menus-dishes/raskladka-nutrition'], 'options' => ['class' => '']],
                    ['label' => 'Проверка меню на аллергены', 'url' => ['/menus-dishes/show-allergen'], 'options' => ['class' => '']],
                    ['label' => 'Проверка меню на хлебные единицы', 'url' => ['/menus-dishes/show-sahar'], 'options' => ['class' => '']],
                    ['label' => 'Технологические карты с учетом количества питающихся (пересчет)', 'url' => ['menus-dishes/techmup-page'], 'options' => ['class' => '']],
                    ['label' => 'Формирование собственных сборников рецептур', 'url' => ['/settings/recipes-index'], 'options' => ['class' => '']],
                    ['label' => 'Добавить блюдо в сборник', 'url' => ['/dishes'], 'options' => ['class' => '']],
                    ['label' => 'База данных блюд', 'url' => ['dishes/dishes-base'], 'options' => ['class' => '']],
                    ['label' => 'База данных продуктов', 'url' => ['products/products-base'], 'options' => ['class' => '']],
                    ['label' => 'Формирование документов(печать меню)', 'url' => ['/menus-dishes/report-document'], 'options' => ['class' => '']],
                    ['label' => 'Замена продуктов в меню', 'url' => ['/products-change-organization/index'], 'options' => ['class' => '']],
                    ['label' => 'Отчет по витаминам и минеральным веществам', 'url' => ['/menus-dishes/report-vitamin'], 'options' => ['class' => '']],
                ]
            ],

            /*['label' => 'Количество питающихся', 'url' => ['#'], 'options' => ['class' => 'mr-3 p-2'],
                'items'=>[
                    //['label' => 'Настройка классов', 'url' => ['/everyday-classes/index'], 'options' => ['class' => '']],
                    //['label' => 'Внести ежедневную информацию', 'url' => ['/everyday-classes/enter'], 'options' => ['class' => '']],
                    ['label' => 'История', 'url' => ['/everyday-classes/history'], 'options' => ['class' => '']],
                ]
            ],*/
            ['label' => 'Ежедневная информация', 'url' => ['#'], 'options' => ['class' => 'mr-3 p-2'],
                'items' => [
                    //['label' => 'Настройка классов', 'url' => ['/everyday-classes/index'], 'options' => ['class' => '']],
                    ['label' => 'Внести ежедневную информацию', 'url' => ['/everyday-classes/enter'], 'options' => ['class' => '']],
                    //['label' => 'История', 'url' => ['/everyday-classes/history'], 'options' => ['class' => '']],
                    ['label' => 'Отчет о ежедневной информации по классам', 'url' => ['/everyday-classes/class-report'], 'options' => ['class' => '']],
                    //['label' => 'Отчет о ежедневной информации по переменам', 'url' => ['/everyday-classes/peremena-report'], 'options' => ['class' => '']],
                    ['label' => 'Статистика по заполняемости ежедневной информации', 'url' => ['/everyday-classes/statistic-enter'], 'options' => ['class' => '']],
                    //['label' => 'Статистика по датам, в которые были внесены данные', 'url' => ['/everyday-classes/statistic-days'], 'options' => ['class' => '']],
                    //['label' => 'Статистика посещаемости по школе', 'url' => ['/everyday-classes/report-school-visit'], 'options' => ['class' => '']],
                ]
            ],
            /*['label' => 'Дефицитные состояния', 'url' => ['#'], 'options' => ['class' => 'mr-3 p-2']],*/
            /*['label' => 'Информация классного руководителя', 'url' => ['#'], 'options' => ['class' => 'mr-3 p-2'],
                'items'=>[
                    ['label' => 'Настройки классов', 'url' => ['/configuration-classes'], 'options' => ['class' => 'm-5']],
                    //['label' => 'Ввод ежедневной информации', 'url' => ['/daily-informations'], 'options' => ['class' => '']],
                    //['label' => 'Отчет по ежедневной информации', 'url' => ['#'], 'options' => ['class' => '']],
                ]],*/


            ['label' => 'Организаторы питания', 'url' => ['#'], 'options' => ['class' => 'mr-3 p-2'],
                'items' => [
                    ['label' => 'Список организаций питания', 'url' => ['nutrition-applications/organizations'], 'options' => ['class' => 'm-5']],
                    ['label' => 'Отправленные заявки', 'url' => ['nutrition-applications/index'], 'options' => ['class' => 'm-5']],
                    ['label' => 'Полученные заявки', 'url' => ['nutrition-applications/receiving'], 'options' => ['class' => 'm-5']],
                    ['label' => 'Меню от организатора питания', 'url' => ['nutrition-applications/send-menu'], 'options' => ['class' => 'm-5']],


                ]
            ],
            /*['label' => 'Медицинская информация', 'url' => ['#'], 'options' => ['class' => 'mr-3 p-2']],*/
            //['label' => 'Информация о классных руководителях', 'url' => ['#'], 'options' => ['class' => 'mr-3 p-2']],

            ['label' => 'Пользователи', 'url' => ['users/level'], 'options' => ['class' => 'mr-3 p-2'],
                'items' => [

                ]
	    ],
	    ['label' => 'Видеоуроки', 'url' => ['#'], 'options' => ['class' => 'mr-3 p-2'],
            'items' => [
		        ['label' => 'Заполнение раздела «Характеристика обучающихся»', 'url' => 'https://youtu.be/jVrogZM7FQk', 'options' => ['class' => 'm-5'], 'linkOptions' => ['target'=>'_blank']],
	            ['label' => 'Заполнение раздела «Ежедневная информация»', 'url' => 'https://youtu.be/kGRgI1YlXko', 'options' => ['class' => 'm-5'], 'linkOptions' => ['target'=>'_blank']],
			    ['label' => 'Регистрация сотрудников учреждения (учителей), изменение почты у пользователя, восстановления пароля', 'url' => 'https://www.youtube.com/watch?v=bgSAm-0ZvJ0', 'options' => ['class' => 'm-5'], 'linkOptions' => ['target'=>'_blank']],
			    ['label' => 'Настройка, архив, разработка и редактирование меню', 'url' => 'https://www.youtube.com/watch?v=7AdfJTsJ6_k', 'options' => ['class' => 'm-5'], 'linkOptions' => ['target'=>'_blank']],
			    ['label' => 'Добавление иных технологических карт', 'url' => 'https://www.youtube.com/watch?v=ZsNVJoK6hVY', 'options' => ['class' => 'm-5'], 'linkOptions' => ['target'=>'_blank']],
				['label' => 'Адаптация меню для питания детей с сахарным диабетом и пищевой аллергией', 'url' => 'https://www.youtube.com/watch?v=PxfUL5bUQHs', 'options' => ['class' => 'm-5'], 'linkOptions' => ['target'=>'_blank']],
            ],
        ],
        ['label' => 'Справочная информация', 'url' => ['site/download-document-index'], 'options' => ['class' => 'mr-3 p-2']],
        ['label' => 'ЗАДАТЬ ВОПРОС(ЧАТ)', 'url' => ['chat/index'], 'options' => ['class' => 'mr-3 p-2'],
            'items' => [

            ]
        ],


        ];
        $logout = Html::begintag('div', ['class' => 'row'])
            . Html::begintag('div', ['class' => 'col-4'])
            . Html::beginForm(['#'], 'post')
            . //Html::submitInput('Регистрация работников учреждения', ['id' => 'registration_employee', 'class' => 'btn btn-outline-success'])
            Html::a('Регистрация работников учреждения', ['users/createuser'], ['class' => 'btn main-button-2-outline'])
            . Html::endForm()
            . Html::endtag('div')
            . Html::tag('div', 'Пользователь: ' . Yii::$app->user->identity->name . '('.Yii::$app->user->identity->organization_id.'-'.Yii::$app->user->identity->id.')', ['class' => 'col-6 text-right'])
            . Html::begintag('div', ['class' => 'col-2 text-right'])
            . Html::beginForm(['/site/logout'], 'post')
            . Html::submitButton(' Выход', ['class' => 'btn main-button-2-outline logout'])
            . Html::endForm()
            . Html::endtag('div')
            . Html::endtag('div');
    }



    if (Yii::$app->user->identity->organization_id == 306)
    {
        $menuItems = [
            ['label' => 'Главная', 'url' => ['/'], 'options' => ['class' => 'mr-3 p-2']],
            ['label' => 'Личные данные', 'url' => ['users/profile'], 'options' => ['class' => 'mr-3 p-2']],
            ['label' => 'Отчеты', 'url' => ['#'], 'options' => ['class' => 'main-button-2-hover-orange mr-3 p-2'],
                'items' => [
                    ['label' => 'Формирование документов для размещения на сайте', 'url' => ['/menus-dishes/report-document'], 'options' => ['class' => '']],
                    //['label' => 'Результаты мониторинга организации', 'url' => ['/menus/my-monitoring'], 'options' => ['class' => '']],
                    //['label' => 'Отчет по детям с патологиями, требующих индивидуального питания', 'url' => ['characters-study/report-characters-study-diseases'], 'options' => ['class' => '']],
                    ['label' => 'Гигиеническая оценка меню', 'url' => ['/menus-dishes/expertiza'], 'options' => ['class' => '']],
                ],
            ],
            ['label' => 'Организация', 'url' => ['#'], 'options' => ['class' => 'mr-3 p-2'],
                'items' => [
                    ['label' => 'Общая информация', 'url' => ['organizations/create'], 'options' => ['class' => '']],
                    ['label' => 'Перечень производственных помещений пищеблока', 'url' => ['basic-information/razdel'], 'options' => ['class' => '']],
                    ['label' => 'Перечень оборудования производственных помещений пищеблока', 'url' => ['basic-information/create'], 'options' => ['class' => '']],
                    ['label' => 'Характеристика работы пищеблока', 'url' => ['characters-stolovaya/index'], 'options' => ['class' => '']],
                    ['label' => 'Перемены, использующиеся для организации питания', 'url' => ['school-break/index'], 'options' => ['class' => '']],
                   ['label' => 'Информация о количестве обучающихся', 'url' => ['information-education/index'], 'options' => ['class' => '']],
                    //['label' => 'Характеристика обучающихся', 'url' => ['characters-study/index'], 'options' => ['class' => '']],
                    ['label' => 'Характеристика обучающихся', 'url' => ['characters-study/index-new'], 'options' => ['class' => '']],
                    ['label' => 'Родительский контроль', 'url' => ['anket-parent-control/create'], 'options' => ['class' => '']],
                    ['label' => 'Внутренний контроль', 'url' => ['anket-parent-control/inside'], 'options' => ['class' => '']],
                    ['label' => 'Отчет по контрольным мероприятиям', 'url' => ['anket-parent-control/report'], 'options' => ['class' => '']],
                ]
            ],
            ['label' => 'Организация питания', 'url' => ['#'], 'options' => ['class' => 'mr-3 p-2'],
                'items' => [
                    ['label' => 'Архив меню', 'url' => ['menus/archive'], 'options' => ['class' => 'm-5']],
                    ['label' => 'Настройки меню', 'url' => ['menus/index'], 'options' => ['class' => 'm-5']],
                    ['label' => 'Разработка (редактирование) цикличного меню', 'url' => ['menus-dishes/index'], 'options' => ['class' => '']],
                    ['label' => 'Просмотр действующего меню по дням', 'url' => ['menus-dishes/menus-days'], 'options' => ['class' => '']],
                    ['label' => 'Просмотр действующего меню за период', 'url' => ['menus-dishes/menus-period'], 'options' => ['class' => '']],
                    ['label' => 'Корректировка фактического меню по дате', 'url' => ['menus-dishes/fact-day-index'], 'options' => ['class' => '']],
                    ['label' => 'Фактическое меню за день', 'url' => ['menus-dishes/fact-date'], 'options' => ['class' => '']],
                    ['label' => 'Отчет о повторяемости', 'url' => ['menus-dishes/repeat-report'], 'options' => ['class' => '']],
                    ['label' => 'Отчет о перечне продуктов', 'url' => ['menus-dishes/products-list'], 'options' => ['class' => '']],
                    ['label' => 'Отчет о перечне продуктов за указанный период', 'url' => ['menus-dishes/fact-date-products-list'], 'options' => ['class' => '']],
                    ['label' => 'Прогнозная накопительная ведомость', 'url' => ['menus-dishes/prognos-storage'], 'options' => ['class' => '']],
                    ['label' => 'Меню раскладка за день', 'url' => ['menus-dishes/raskladka'], 'options' => ['class' => '']],
                    ['label' => 'Меню раскладка за день по приемам пищи', 'url' => ['menus-dishes/raskladka-nutrition'], 'options' => ['class' => '']],
                    ['label' => 'Проверка меню на аллергены', 'url' => ['/menus-dishes/show-allergen'], 'options' => ['class' => '']],
                    ['label' => 'Проверка меню на хлебные единицы', 'url' => ['/menus-dishes/show-sahar'], 'options' => ['class' => '']],
                    ['label' => 'Технологические карты с учетом количества питающихся (пересчет)', 'url' => ['menus-dishes/techmup-page'], 'options' => ['class' => '']],
                    ['label' => 'Формирование собственных сборников рецептур', 'url' => ['/settings/recipes-index'], 'options' => ['class' => '']],
                    ['label' => 'Добавить блюдо в сборник', 'url' => ['/dishes'], 'options' => ['class' => '']],
                    ['label' => 'База данных блюд', 'url' => ['dishes/dishes-base'], 'options' => ['class' => '']],
                    ['label' => 'База данных продуктов', 'url' => ['products/products-base'], 'options' => ['class' => '']],
                    ['label' => 'Формирование документов(печать меню)', 'url' => ['/menus-dishes/report-document'], 'options' => ['class' => '']],
                    ['label' => 'Замена продуктов в меню', 'url' => ['/products-change-organization/index'], 'options' => ['class' => '']],
                    ['label' => 'Отчет по витаминам и минеральным веществам', 'url' => ['/menus-dishes/report-vitamin'], 'options' => ['class' => '']],
                ]
            ],

            ['label' => 'Ежедневная информация', 'url' => ['#'], 'options' => ['class' => 'mr-3 p-2'],
                'items' => [
                    //['label' => 'Настройка классов', 'url' => ['/everyday-classes/index'], 'options' => ['class' => '']],
                    ['label' => 'Внести ежедневную информацию', 'url' => ['/everyday-classes/enter'], 'options' => ['class' => '']],
                    ['label' => 'Отчет о ежедневной информации по классам', 'url' => ['/everyday-classes/class-report'], 'options' => ['class' => '']],
                    //['label' => 'Отчет о ежедневной информации по переменам', 'url' => ['/everyday-classes/peremena-report'], 'options' => ['class' => '']],
                    ['label' => 'Статистика по заполняемости ежедневной информации', 'url' => ['/everyday-classes/statistic-enter'], 'options' => ['class' => '']],
                    /*['label' => 'Статистика по датам, в которые были внесены данные', 'url' => ['/everyday-classes/statistic-days'], 'options' => ['class' => '']],
                    ['label' => 'Статистика посещаемости по школе', 'url' => ['/everyday-classes/report-school-visit'], 'options' => ['class' => '']],*/
                ]
            ],

            ['label' => 'Организаторы питания', 'url' => ['#'], 'options' => ['class' => 'mr-3 p-2'],
                'items' => [
                    ['label' => 'Список организаций питания', 'url' => ['nutrition-applications/organizations'], 'options' => ['class' => 'm-5']],
                    ['label' => 'Отправленные заявки', 'url' => ['nutrition-applications/index'], 'options' => ['class' => 'm-5']],
                    ['label' => 'Полученные заявки', 'url' => ['nutrition-applications/receiving'], 'options' => ['class' => 'm-5']],
                    ['label' => 'Меню от организатора питания', 'url' => ['nutrition-applications/send-menu'], 'options' => ['class' => 'm-5']],


                ]
            ],
            /*['label' => 'Информация о классных руководителях', 'url' => ['#'], 'options' => ['class' => 'mr-3 p-2']],*/

            ['label' => 'Пользователи', 'url' => ['users/level'], 'options' => ['class' => 'mr-3 p-2'],
            ],

            ['label' => 'Видеоуроки', 'url' => ['#'], 'options' => ['class' => 'mr-3 p-2'],
                'items' => [
                    ['label' => 'Заполнение раздела «Характеристика обучающихся»', 'url' => 'https://youtu.be/jVrogZM7FQk', 'options' => ['class' => 'm-5'], 'linkOptions' => ['target'=>'_blank']],
                    ['label' => 'Заполнение раздела «Ежедневная информация»', 'url' => 'https://youtu.be/kGRgI1YlXko', 'options' => ['class' => 'm-5'], 'linkOptions' => ['target'=>'_blank']],
                    ['label' => 'Регистрация сотрудников учреждения (учителей), изменение почты у пользователя, восстановления пароля', 'url' => 'https://www.youtube.com/watch?v=bgSAm-0ZvJ0', 'options' => ['class' => 'm-5'], 'linkOptions' => ['target'=>'_blank']],
                    ['label' => 'Настройка, архив, разработка и редактирование меню', 'url' => 'https://www.youtube.com/watch?v=7AdfJTsJ6_k', 'options' => ['class' => 'm-5'], 'linkOptions' => ['target'=>'_blank']],
                    ['label' => 'Добавление иных технологических карт', 'url' => 'https://www.youtube.com/watch?v=ZsNVJoK6hVY', 'options' => ['class' => 'm-5'], 'linkOptions' => ['target'=>'_blank']],
                    ['label' => 'Адаптация меню для питания детей с сахарным диабетом и пищевой аллергией', 'url' => 'https://www.youtube.com/watch?v=PxfUL5bUQHs', 'options' => ['class' => 'm-5'], 'linkOptions' => ['target'=>'_blank']],

                ],
            ],
            ['label' => 'Справочная информация', 'url' => ['site/download-document-index'], 'options' => ['class' => 'mr-3 p-2']],
            ['label' => 'ЗАДАТЬ ВОПРОС(ЧАТ)', 'url' => ['chat/index'], 'options' => ['class' => 'mr-3 p-2'],
                'items' => [

                ]
            ],



        ];
        $logout = Html::begintag('div', ['class' => 'text-right'])
            .Html::tag('div', 'Пользователь:('.Yii::$app->user->identity->name.')', ['class' => ''])
            .Html::beginForm(['/site/logout'], 'post')
            .Html::submitButton(' Выход', ['class' => 'btn main-button-2-outline logout'])
            .Html::endForm()
            .Html::endTag('div');
    }


    if (Yii::$app->user->can('nutrition_director'))
    {
        $menuItems = [
            ['label' => 'Главная', 'url' => ['/'], 'options' => ['class' => 'mr-3 p-2']],

            ['label' => 'Отчеты', 'url' => ['#'], 'options' => ['class' => 'main-button-2-hover-orange mr-3 p-2'],
                'items' => [
                    ['label' => 'Формирование документов для размещения на сайте', 'url' => ['/menus-dishes/report-document'], 'options' => ['class' => '']],
                    //['label' => 'Информация о здоровом питании', 'url' => ['http://www.niig.su/%D0%BD%D0%B0%D1%83%D1%87%D0%BD%D0%B0%D1%8F-%D0%B4%D0%B5%D1%8F%D1%82%D0%B5%D0%BB%D1%8C%D0%BD%D0%BE%D1%81%D1%82%D1%8C/3942-%D0%B8%D0%BD%D1%84%D0%BE%D1%80%D0%BC%D0%B0%D1%86%D0%B8%D1%8F-%D0%BE-%D0%B7%D0%B4%D0%BE%D1%80%D0%BE%D0%B2%D0%BE%D0%BC-%D0%BF%D0%B8%D1%82%D0%B0%D0%BD%D0%B8%D0%B8'], 'options' => ['class' => '']],
                    //['label' => 'Результаты мониторинга организации', 'url' => ['/menus/my-monitoring'], 'options' => ['class' => '']],
                    //['label' => 'Отчет по детям с патологиями, требующих индивидуального питания', 'url' => ['characters-study/report-characters-study-diseases'], 'options' => ['class' => '']],

                ],
            ],

            ['label' => 'Организация питания', 'url' => ['#'], 'options' => ['class' => 'mr-3 p-2'],
                'items' => [
                    ['label' => 'Архив меню', 'url' => ['menus/archive'], 'options' => ['class' => 'm-5']],
                    ['label' => 'Настройки меню', 'url' => ['menus/index'], 'options' => ['class' => 'm-5']],
                    ['label' => 'Разработка (редактирование) цикличного меню', 'url' => ['menus-dishes/index'], 'options' => ['class' => '']],
                    ['label' => 'Просмотр действующего меню по дням', 'url' => ['menus-dishes/menus-days'], 'options' => ['class' => '']],
                    ['label' => 'Просмотр действующего меню за период', 'url' => ['menus-dishes/menus-period'], 'options' => ['class' => '']],
                    ['label' => 'Корректировка фактического меню по дате', 'url' => ['menus-dishes/fact-day-index'], 'options' => ['class' => '']],
                    ['label' => 'Фактическое меню за день', 'url' => ['menus-dishes/fact-date'], 'options' => ['class' => '']],
                    ['label' => 'Отчет о повторяемости', 'url' => ['menus-dishes/repeat-report'], 'options' => ['class' => '']],
                    ['label' => 'Отчет о перечне продуктов', 'url' => ['menus-dishes/products-list'], 'options' => ['class' => '']],
                    ['label' => 'Отчет о перечне продуктов за указанный период', 'url' => ['menus-dishes/fact-date-products-list'], 'options' => ['class' => '']],
                    ['label' => 'Прогнозная накопительная ведомость', 'url' => ['menus-dishes/prognos-storage'], 'options' => ['class' => '']],
                    //['label' => 'Фактическая накопительная ведомость', 'url' => ['menus-dishes/fact-storage'], 'options' => ['class' => '']],
                    //['label' => 'Фактическое меню за день', 'url' => ['menus-dishes/fact-day-index'], 'options' => ['class' => '']],
                    ['label' => 'Меню раскладка за день', 'url' => ['menus-dishes/raskladka'], 'options' => ['class' => '']],
                    ['label' => 'Меню раскладка за день по приемам пищи', 'url' => ['menus-dishes/raskladka-nutrition'], 'options' => ['class' => '']],
                    ['label' => 'Проверка меню на аллергены', 'url' => ['/menus-dishes/show-allergen'], 'options' => ['class' => '']],
                    ['label' => 'Проверка меню на хлебные единицы', 'url' => ['/menus-dishes/show-sahar'], 'options' => ['class' => '']],
                    ['label' => 'Технологические карты с учетом количества питающихся (пересчет)', 'url' => ['menus-dishes/techmup-page'], 'options' => ['class' => '']],
                    ['label' => 'Формирование собственных сборников рецептур', 'url' => ['/settings/recipes-index'], 'options' => ['class' => '']],
                    ['label' => 'Добавить блюдо в сборник', 'url' => ['/dishes'], 'options' => ['class' => '']],
                    ['label' => 'База данных блюд', 'url' => ['dishes/dishes-base'], 'options' => ['class' => '']],
                    ['label' => 'База данных продуктов', 'url' => ['products/products-base'], 'options' => ['class' => '']],
                    ['label' => 'Формирование документов(печать меню)', 'url' => ['/menus-dishes/report-document'], 'options' => ['class' => '']],
                    ['label' => 'Замена продуктов в меню', 'url' => ['/products-change-organization/index'], 'options' => ['class' => '']],
                    ['label' => 'Отчет по витаминам и минеральным веществам', 'url' => ['/menus-dishes/report-vitamin'], 'options' => ['class' => '']],
                ]
            ],


            ['label' => 'Пользователи', 'url' => ['users/level'], 'options' => ['class' => 'mr-3 p-2'],
                'items' => [

                ]
            ],
            ['label' => 'Справочная информация', 'url' => ['site/download-document-index'], 'options' => ['class' => 'mr-3 p-2']],
            ['label' => 'ЗАДАТЬ ВОПРОС(ЧАТ)', 'url' => ['chat/index'], 'options' => ['class' => 'mr-3 p-2'],
                'items' => [

                ]
            ],


        ];
        $logout = Html::begintag('div', ['class' => 'text-right'])
            .Html::tag('div', 'Пользователь:('.Yii::$app->user->identity->name.')', ['class' => ''])
            .Html::beginForm(['/site/logout'], 'post')
            .Html::submitButton(' Выход', ['class' => 'btn main-button-2-outline logout'])
            .Html::endForm()
            .Html::endTag('div');
    }


    if (Yii::$app->user->can('hidden_user'))
    {
        $menuItems = [
            ['label' => 'Главная', 'url' => ['/'], 'options' => ['class' => 'mr-3 p-2']],

            ['label' => 'Отчеты', 'url' => ['#'], 'options' => ['class' => 'main-button-2-hover-orange mr-3 p-2'],
                'items' => [
                    ['label' => 'Формирование документов для размещения на сайте', 'url' => ['/menus-dishes/report-document'], 'options' => ['class' => '']],
                    ['label' => 'Гигиеническая оценка меню', 'url' => ['/menus-dishes/expertiza'], 'options' => ['class' => '']],
                    //['label' => 'Результаты мониторинга организации', 'url' => ['/menus/my-monitoring'], 'options' => ['class' => '']],
                    //['label' => 'Отчет по детям с патологиями, требующих индивидуального питания', 'url' => ['characters-study/report-characters-study-diseases'], 'options' => ['class' => '']],

                ],
            ],

            ['label' => 'Организация питания', 'url' => ['#'], 'options' => ['class' => 'mr-3 p-2'],
                'items' => [
                    //['label' => 'Архив меню', 'url' => ['menus/archive'], 'options' => ['class' => 'm-5']],
                    ['label' => 'Настройки меню', 'url' => ['menus/index'], 'options' => ['class' => 'm-5']],
                    ['label' => 'Разработка (редактирование) цикличного меню', 'url' => ['menus-dishes/index'], 'options' => ['class' => '']],
                    ['label' => 'Просмотр действующего меню по дням', 'url' => ['menus-dishes/menus-days'], 'options' => ['class' => '']],
                    ['label' => 'Просмотр действующего меню за период', 'url' => ['menus-dishes/menus-period'], 'options' => ['class' => '']],
                    ['label' => 'Корректировка фактического меню по дате', 'url' => ['menus-dishes/fact-day-index'], 'options' => ['class' => '']],
                    ['label' => 'Фактическое меню за день', 'url' => ['menus-dishes/fact-date'], 'options' => ['class' => '']],
                    ['label' => 'Отчет о повторяемости', 'url' => ['menus-dishes/repeat-report'], 'options' => ['class' => '']],
                    ['label' => 'Отчет о перечне продуктов', 'url' => ['menus-dishes/products-list'], 'options' => ['class' => '']],
                    ['label' => 'Отчет о перечне продуктов за указанный период', 'url' => ['menus-dishes/fact-date-products-list'], 'options' => ['class' => '']],
                    ['label' => 'Прогнозная накопительная ведомость', 'url' => ['menus-dishes/prognos-storage'], 'options' => ['class' => '']],
                    //['label' => 'Фактическая накопительная ведомость', 'url' => ['menus-dishes/fact-storage'], 'options' => ['class' => '']],
                    //['label' => 'Фактическое меню за день', 'url' => ['menus-dishes/fact-day-index'], 'options' => ['class' => '']],
                    ['label' => 'Меню раскладка за день', 'url' => ['menus-dishes/raskladka'], 'options' => ['class' => '']],
                    ['label' => 'Меню раскладка за день по приемам пищи', 'url' => ['menus-dishes/raskladka-nutrition'], 'options' => ['class' => '']],
                    ['label' => 'Проверка меню на аллергены', 'url' => ['/menus-dishes/show-allergen'], 'options' => ['class' => '']],
                    ['label' => 'Проверка меню на хлебные единицы', 'url' => ['/menus-dishes/show-sahar'], 'options' => ['class' => '']],
                    ['label' => 'Технологические карты с учетом количества питающихся (пересчет)', 'url' => ['menus-dishes/techmup-page'], 'options' => ['class' => '']],
                    ['label' => 'Формирование собственных сборников рецептур', 'url' => ['/settings/recipes-index'], 'options' => ['class' => '']],
                    ['label' => 'Добавить блюдо в сборник', 'url' => ['/dishes'], 'options' => ['class' => '']],
                    ['label' => 'База данных блюд', 'url' => ['dishes/dishes-base'], 'options' => ['class' => '']],
                    ['label' => 'База данных продуктов', 'url' => ['products/products-base'], 'options' => ['class' => '']],
                    ['label' => 'Формирование документов(печать меню)', 'url' => ['/menus-dishes/report-document'], 'options' => ['class' => '']],
                    ['label' => 'Замена продуктов в меню', 'url' => ['/products-change-organization/index'], 'options' => ['class' => '']],
                    ['label' => 'Отчет по витаминам и минеральным веществам', 'url' => ['/menus-dishes/report-vitamin'], 'options' => ['class' => '']],
                ]
            ],
            ['label' => 'Видеоуроки', 'url' => ['#'], 'options' => ['class' => 'mr-3 p-2'],
                'items' => [
                    ['label' => 'Настройка, архив, разработка и редактирование меню', 'url' => 'https://www.youtube.com/watch?v=7AdfJTsJ6_k', 'options' => ['class' => 'm-5'], 'linkOptions' => ['target'=>'_blank']],
                    ['label' => 'Добавление иных технологических карт', 'url' => 'https://www.youtube.com/watch?v=ZsNVJoK6hVY', 'options' => ['class' => 'm-5'], 'linkOptions' => ['target'=>'_blank']],
                    ['label' => 'Адаптация меню для питания детей с сахарным диабетом и пищевой аллергией', 'url' => 'https://www.youtube.com/watch?v=PxfUL5bUQHs', 'options' => ['class' => 'm-5'], 'linkOptions' => ['target'=>'_blank']],
                ],
            ],
            ['label' => 'Справочная информация', 'url' => ['site/download-document-index'], 'options' => ['class' => 'mr-3 p-2']],
            ['label' => 'ЗАДАТЬ ВОПРОС(ЧАТ)', 'url' => ['chat/index'], 'options' => ['class' => 'mr-3 p-2'],
                'items' => [

                ]
            ],


        ];
        $logout = Html::begintag('div', ['class' => 'text-right'])
            .Html::tag('div', 'Пользователь:('.Yii::$app->user->identity->name.')', ['class' => ''])
            .Html::beginForm(['/site/logout'], 'post')
            .Html::submitButton(' Выход', ['class' => 'btn main-button-2-outline logout'])
            .Html::endForm()
            .Html::endTag('div');
    }


    if (Yii::$app->user->can('teacher'))
    {
        $menuItems = [
            ['label' => 'Личные данные', 'url' => ['users/profile'], 'options' => ['class' => 'mr-3 p-2']],
            ['label' => 'Организация', 'url' => ['#'], 'options' => ['class' => 'mr-3 p-2'],
                'items' => [
                    //['label' => 'Перемены, использующиеся для организации питания', 'url' => ['#'], 'options' => ['class' => '']],
                    //['label' => 'Информация о расходах на питание', 'url' => ['expenses-food/index'], 'options' => ['class' => '']],
                    ['label' => 'Характеристика обучающихся', 'url' => ['characters-study/index-new'], 'options' => ['class' => '']],
                ]
            ],

            ['label' => 'Ежедневная информация', 'url' => ['#'], 'options' => ['class' => 'mr-3 p-2'],
                'items' => [
                    //['label' => 'Настройка классов', 'url' => ['/everyday-classes/index'], 'options' => ['class' => '']],
                    ['label' => 'Внести ежедневную информацию', 'url' => ['/everyday-classes/enter'], 'options' => ['class' => '']],
                    //['label' => 'История', 'url' => ['/everyday-classes/history'], 'options' => ['class' => '']],
                    ['label' => 'Отчет о ежедневной информации по классам', 'url' => ['/everyday-classes/class-report'], 'options' => ['class' => '']],
                    //['label' => 'Отчет о ежедневной информации по переменам', 'url' => ['/everyday-classes/peremena-report'], 'options' => ['class' => '']],
                ]
            ],
            ['label' => 'Видеоуроки', 'url' => ['#'], 'options' => ['class' => 'mr-3 p-2'],
                'items' => [
                ['label' => 'Заполнение раздела «Характеристика обучающихся»', 'url' => 'https://youtu.be/jVrogZM7FQk', 'options' => ['class' => 'm-5'], 'linkOptions' => ['target'=>'_blank']],
                ['label' => 'Заполнение раздела «Ежедневная информация»', 'url' => 'https://youtu.be/kGRgI1YlXko', 'options' => ['class' => 'm-5'], 'linkOptions' => ['target'=>'_blank']],
		        ['label' => 'Регистрация сотрудников учреждения (учителей), изменение почты у пользователя, восстановления пароля', 'url' => 'https://www.youtube.com/watch?v=bgSAm-0ZvJ0', 'options' => ['class' => 'm-5'], 'linkOptions' => ['target'=>'_blank']],
		        ['label' => 'Настройка, архив, разработка и редактирование меню', 'url' => 'https://www.youtube.com/watch?v=7AdfJTsJ6_k', 'options' => ['class' => 'm-5'], 'linkOptions' => ['target'=>'_blank']],
		        ['label' => 'Добавление иных технологических карт', 'url' => 'https://www.youtube.com/watch?v=ZsNVJoK6hVY', 'options' => ['class' => 'm-5'], 'linkOptions' => ['target'=>'_blank']],
				['label' => 'Адаптация меню для питания детей с сахарным диабетом и пищевой аллергией', 'url' => 'https://www.youtube.com/watch?v=PxfUL5bUQHs', 'options' => ['class' => 'm-5'], 'linkOptions' => ['target'=>'_blank']],
	            ],
            ],
            ['label' => 'Справочная информация', 'url' => ['site/download-document-index'], 'options' => ['class' => 'mr-3 p-2']],
            ['label' => 'ЗАДАТЬ ВОПРОС(ЧАТ)', 'url' => ['chat/index'], 'options' => ['class' => 'mr-3 p-2'],
                'items' => [

                ]
            ],
        ];


        $logout = Html::begintag('div', ['class' => 'text-right'])
            .Html::tag('div', 'Пользователь:('.Yii::$app->user->identity->name.')', ['class' => ''])
            .Html::beginForm(['/site/logout'], 'post')
            .Html::submitButton(' Выход', ['class' => 'btn main-button-2-outline logout'])
            .Html::endForm()
            .Html::endTag('div');
    }


    if (Yii::$app->user->can('upolnomoch_kids'))
    {
        $menuItems = [

            ['label' => 'Организация', 'url' => ['#'], 'options' => ['class' => 'mr-3 p-2'],
                'items' => [
                    ['label' => 'Общественный контроль', 'url' => ['anket-parent-control/social'], 'options' => ['class' => '']],
                ]
            ],
            ['label' => 'Справочная информация', 'url' => ['site/download-document-index'], 'options' => ['class' => 'mr-3 p-2']],
            ['label' => 'ЗАДАТЬ ВОПРОС(ЧАТ)', 'url' => ['chat/index'], 'options' => ['class' => 'mr-3 p-2'],
                'items' => [

                ]
            ],
        ];


        $logout = Html::begintag('div', ['class' => 'row'])
            . Html::begintag('div', ['class' => 'col-4'])
            //. Html::beginForm(['#'], 'post')
            //. //Html::submitInput('Регистрация работников учреждения', ['id' => 'registration_employee', 'class' => 'btn btn-outline-success'])
            //Html::a('Регистрация работников учреждения', ['users/createuser'], ['class' => 'btn main-button-2-outline'])
            //. Html::endForm()
            . Html::endtag('div')
            . Html::tag('div', 'Пользователь: ' . Organization::findOne(Yii::$app->user->identity->organization_id)->title, ['class' => 'col-6 text-right'])
            . Html::begintag('div', ['class' => 'col-2 text-right'])
            . Html::beginForm(['/site/logout'], 'post')
            . Html::submitButton(' Выход', ['class' => 'btn main-button-2-outline logout'])
            . Html::endForm()
            . Html::endtag('div')
            . Html::endtag('div');
    }


    if (Yii::$app->user->can('foodworker'))
    {
        $menuItems = [
            ['label' => 'Главная', 'url' => ['site/index'], 'options' => ['class' => 'mr-3 p-2']],
            ['label' => 'Личные данные', 'url' => ['users/profile'], 'options' => ['class' => 'mr-3 p-2']],
            ['label' => 'Отчеты', 'url' => ['#'], 'options' => ['class' => 'main-button-2-hover-orange mr-3 p-2'],
                'items' => [
                    ['label' => 'Формирование документов для размещения на сайте', 'url' => ['/menus-dishes/report-document'], 'options' => ['class' => '']],
                    ['label' => 'Гигиеническая оценка меню', 'url' => ['/menus-dishes/expertiza'], 'options' => ['class' => '']],
                    ['label' => 'Отчет по контрольным мероприятиям', 'url' => ['anket-parent-control/report'], 'options' => ['class' => '']],

                ],
            ],
            ['label' => 'Организация', 'url' => ['#'], 'options' => ['class' => 'mr-3 p-2'],
                'items' => [
                    ['label' => 'Общая информация', 'url' => ['organizations/create'], 'options' => ['class' => '']],
                    ['label' => 'Перечень производственных помещений пищеблока', 'url' => ['basic-information/razdel'], 'options' => ['class' => '']],
                    ['label' => 'Перечень оборудования производственных помещений пищеблока', 'url' => ['basic-information/create'], 'options' => ['class' => '']],
                    ['label' => 'Характеристика работы пищеблока', 'url' => ['characters-stolovaya/index'], 'options' => ['class' => '']],
                    ['label' => 'Перемены, использующиеся для организации питания', 'url' => ['school-break/index'], 'options' => ['class' => '']],
                    ['label' => 'Информация о количестве обучающихся', 'url' => ['information-education/index'], 'options' => ['class' => '']],
                    ['label' => 'Характеристика обучающихся', 'url' => ['characters-study/index-new'], 'options' => ['class' => '']],
                    ['label' => 'Родительский контроль', 'url' => ['anket-parent-control/create'], 'options' => ['class' => '']],
                    ['label' => 'Внутренний контроль', 'url' => ['anket-parent-control/inside'], 'options' => ['class' => '']],
                    ['label' => 'Отчет по контрольным мероприятиям', 'url' => ['anket-parent-control/report'], 'options' => ['class' => '']],
                ]
            ],

            ['label' => 'Организация питания', 'url' => ['#'], 'options' => ['class' => 'mr-3 p-2'],
                'items' => [
                    ['label' => 'Архив меню', 'url' => ['menus/archive'], 'options' => ['class' => 'm-5']],
                    ['label' => 'Настройки меню', 'url' => ['menus/index'], 'options' => ['class' => 'm-5']],
                    ['label' => 'Разработка (редактирование) цикличного меню', 'url' => ['menus-dishes/index'], 'options' => ['class' => '']],
                    ['label' => 'Просмотр действующего меню по дням', 'url' => ['menus-dishes/menus-days'], 'options' => ['class' => '']],
                    ['label' => 'Просмотр действующего меню за период', 'url' => ['menus-dishes/menus-period'], 'options' => ['class' => '']],
                    ['label' => 'Корректировка фактического меню по дате', 'url' => ['menus-dishes/fact-day-index'], 'options' => ['class' => '']],
                    ['label' => 'Фактическое меню за день', 'url' => ['menus-dishes/fact-date'], 'options' => ['class' => '']],
                    ['label' => 'Отчет о повторяемости', 'url' => ['menus-dishes/repeat-report'], 'options' => ['class' => '']],
                    ['label' => 'Отчет о перечне продуктов', 'url' => ['menus-dishes/products-list'], 'options' => ['class' => '']],
                    ['label' => 'Отчет о перечне продуктов за указанный период', 'url' => ['menus-dishes/fact-date-products-list'], 'options' => ['class' => '']],
                    ['label' => 'Прогнозная накопительная ведомость', 'url' => ['menus-dishes/prognos-storage'], 'options' => ['class' => '']],
                    //['label' => 'Фактическая накопительная ведомость', 'url' => ['menus-dishes/fact-storage'], 'options' => ['class' => '']],
                    //['label' => 'Фактическое меню за день', 'url' => ['menus-dishes/fact-day-index'], 'options' => ['class' => '']],
                    ['label' => 'Меню раскладка за день', 'url' => ['menus-dishes/raskladka'], 'options' => ['class' => '']],
                    ['label' => 'Меню раскладка за день по приемам пищи', 'url' => ['menus-dishes/raskladka-nutrition'], 'options' => ['class' => '']],
                    ['label' => 'Проверка меню на аллергены', 'url' => ['/menus-dishes/show-allergen'], 'options' => ['class' => '']],
                    ['label' => 'Проверка меню на хлебные единицы', 'url' => ['/menus-dishes/show-sahar'], 'options' => ['class' => '']],
                    ['label' => 'Технологические карты с учетом количества питающихся (пересчет)', 'url' => ['menus-dishes/techmup-page'], 'options' => ['class' => '']],
                    ['label' => 'Формирование собственных сборников рецептур', 'url' => ['/settings/recipes-index'], 'options' => ['class' => '']],
                    ['label' => 'Добавить блюдо в сборник', 'url' => ['/dishes'], 'options' => ['class' => '']],
                    ['label' => 'База данных блюд', 'url' => ['dishes/dishes-base'], 'options' => ['class' => '']],
                    ['label' => 'База данных продуктов', 'url' => ['products/products-base'], 'options' => ['class' => '']],
                    ['label' => 'Формирование документов(печать меню)', 'url' => ['/menus-dishes/report-document'], 'options' => ['class' => '']],
                    ['label' => 'Замена продуктов в меню', 'url' => ['/products-change-organization/index'], 'options' => ['class' => '']],
                    ['label' => 'Отчет по витаминам и минеральным веществам', 'url' => ['/menus-dishes/report-vitamin'], 'options' => ['class' => '']],
                ]
            ],
            ['label' => 'Ежедневная информация', 'url' => ['#'], 'options' => ['class' => 'mr-3 p-2'],
                'items' => [
                    //['label' => 'Настройка классов', 'url' => ['/everyday-classes/index'], 'options' => ['class' => '']],
                    ['label' => 'Внести ежедневную информацию', 'url' => ['/everyday-classes/enter'], 'options' => ['class' => '']],
                    //['label' => 'История', 'url' => ['/everyday-classes/history'], 'options' => ['class' => '']],
                    ['label' => 'Отчет о ежедневной информации по классам', 'url' => ['/everyday-classes/class-report'], 'options' => ['class' => '']],
                    //['label' => 'Отчет о ежедневной информации по переменам', 'url' => ['/everyday-classes/peremena-report'], 'options' => ['class' => '']],
                ]
            ],
            ['label' => 'Видеоуроки', 'url' => ['#'], 'options' => ['class' => 'mr-3 p-2'],
                'items' => [
                    ['label' => 'Настройка, архив, разработка и редактирование меню', 'url' => 'https://www.youtube.com/watch?v=7AdfJTsJ6_k', 'options' => ['class' => 'm-5'], 'linkOptions' => ['target'=>'_blank']],
                    ['label' => 'Добавление иных технологических карт', 'url' => 'https://www.youtube.com/watch?v=ZsNVJoK6hVY', 'options' => ['class' => 'm-5'], 'linkOptions' => ['target'=>'_blank']],
                    ['label' => 'Адаптация меню для питания детей с сахарным диабетом и пищевой аллергией', 'url' => 'https://www.youtube.com/watch?v=PxfUL5bUQHs', 'options' => ['class' => 'm-5'], 'linkOptions' => ['target'=>'_blank']],
                ],
            ],
            ['label' => 'Справочная информация', 'url' => ['site/download-document-index'], 'options' => ['class' => 'mr-3 p-2']],
            ['label' => 'ЗАДАТЬ ВОПРОС(ЧАТ)', 'url' => ['chat/index'], 'options' => ['class' => 'mr-3 p-2'],
                'items' => [

                ]
            ],

            /*['label' => 'Медицинская информация', 'url' => ['#'], 'options' => ['class' => 'mr-3 p-2'],
                'items'=>[
                    ['label' => 'Добавление ребенка', 'url' => ['/kids/create'], 'options' => ['class' => 'm-5']],
                    ['label' => 'Список детей по медицинским осмотрам', 'url' => ['/kids'], 'options' => ['class' => '']],
                ]
            ],*/
        ];


        $logout = Html::begintag('div', ['class' => 'text-right'])
            .Html::tag('div', 'Пользователь:('.Yii::$app->user->identity->name.')', ['class' => ''])
            .Html::beginForm(['/site/logout'], 'post')
            .Html::submitButton(' Выход', ['class' => 'btn main-button-2-outline logout'])
            .Html::endForm()
            .Html::endTag('div');
    }


    if (Yii::$app->user->can('medic'))
    {
        $menuItems = [
            ['label' => 'Отчеты', 'url' => ['#'], 'options' => ['class' => 'main-button-2-hover-orange mr-3 p-2'],
                'items' => [
                    ['label' => 'Формирование документов для размещения на сайте', 'url' => ['/menus-dishes/report-document'], 'options' => ['class' => '']],
                    //['label' => 'Информация о здоровом питании', 'url' => ['http://www.niig.su/%D0%BD%D0%B0%D1%83%D1%87%D0%BD%D0%B0%D1%8F-%D0%B4%D0%B5%D1%8F%D1%82%D0%B5%D0%BB%D1%8C%D0%BD%D0%BE%D1%81%D1%82%D1%8C/3942-%D0%B8%D0%BD%D1%84%D0%BE%D1%80%D0%BC%D0%B0%D1%86%D0%B8%D1%8F-%D0%BE-%D0%B7%D0%B4%D0%BE%D1%80%D0%BE%D0%B2%D0%BE%D0%BC-%D0%BF%D0%B8%D1%82%D0%B0%D0%BD%D0%B8%D0%B8'], 'options' => ['class' => '']],
                    //['label' => 'Результаты мониторинга организации', 'url' => ['/menus/my-monitoring'], 'options' => ['class' => '']],
                    //['label' => 'Отчет по детям с патологиями, требующих индивидуального питания', 'url' => ['characters-study/report-characters-study-diseases'], 'options' => ['class' => '']],
                    ['label' => 'Гигиеническая оценка меню', 'url' => ['/menus-dishes/expertiza'], 'options' => ['class' => '']],
                    ['label' => 'Отчет по контрольным мероприятиям', 'url' => ['anket-parent-control/report'], 'options' => ['class' => '']],

                ],
            ],
            ['label' => 'Личные данные', 'url' => ['users/profile'], 'options' => ['class' => 'mr-3 p-2']],

            ['label' => 'Организация', 'url' => ['#'], 'options' => ['class' => 'mr-3 p-2'],
                'items' => [
                    ['label' => 'Общая информация', 'url' => ['organizations/create'], 'options' => ['class' => '']],
                    ['label' => 'Перечень производственных помещений пищеблока', 'url' => ['basic-information/razdel'], 'options' => ['class' => '']],
                    ['label' => 'Перечень оборудования производственных помещений пищеблока', 'url' => ['basic-information/create'], 'options' => ['class' => '']],
                    ['label' => 'Характеристика работы пищеблока', 'url' => ['characters-stolovaya/index'], 'options' => ['class' => '']],
                    ['label' => 'Перемены, использующиеся для организации питания', 'url' => ['school-break/index'], 'options' => ['class' => '']],
                    ['label' => 'Информация о количестве обучающихся', 'url' => ['information-education/index'], 'options' => ['class' => '']],
                    ['label' => 'Характеристика обучающихся', 'url' => ['characters-study/index-new'], 'options' => ['class' => '']],
                    ['label' => 'Родительский контроль', 'url' => ['anket-parent-control/create'], 'options' => ['class' => '']],
                    ['label' => 'Внутренний контроль', 'url' => ['anket-parent-control/inside'], 'options' => ['class' => '']],
                    ['label' => 'Отчет по контрольным мероприятиям', 'url' => ['anket-parent-control/report'], 'options' => ['class' => '']],
                ]
            ],

            /*['label' => 'Организация питания', 'url' => ['#'], 'options' => ['class' => 'mr-3 p-2'],
                'items'=>[
                    ['label' => 'Архив меню', 'url' => ['menus/archive'], 'options' => ['class' => 'm-5']],
                    ['label' => 'Настройки меню', 'url' => ['menus/index'], 'options' => ['class' => 'm-5']],
                    ['label' => 'Разработка (редактирование) цикличного меню', 'url' => ['menus-dishes/index'], 'options' => ['class' => '']],
                    ['label' => 'Просмотр действующего меню по дням', 'url' => ['menus-dishes/menus-days'], 'options' => ['class' => '']],
                    ['label' => 'Просмотр действующего меню за период', 'url' => ['menus-dishes/menus-period'], 'options' => ['class' => '']],
                    ['label' => 'Отчет о повторяемости', 'url' => ['menus-dishes/repeat-report'], 'options' => ['class' => '']],
                    ['label' => 'Отчет о перечне продуктов', 'url' => ['menus-dishes/products-list'], 'options' => ['class' => '']],
                    ['label' => 'Прогнозная накопительная ведомость', 'url' => ['menus-dishes/prognos-storage'], 'options' => ['class' => '']],
                    ['label' => 'База данных блюд', 'url' => ['dishes/dishes-base'], 'options' => ['class' => '']],
                    ['label' => 'База данных продуктов', 'url' => ['products/products-base'], 'options' => ['class' => '']],
                    ['label' => 'Технологические карты с учетом количества питающихся (пересчет)', 'url' => ['menus-dishes/techmup-page'], 'options' => ['class' => '']],
                    ['label' => 'Фактическое меню по дате', 'url' => ['menus-dishes/fact-date'], 'options' => ['class' => '']],
                    //['label' => 'Фактическая накопительная ведомость', 'url' => ['menus-dishes/fact-storage'], 'options' => ['class' => '']],
                    ['label' => 'Фактическое меню за день', 'url' => ['menus-dishes/fact-day-index'], 'options' => ['class' => '']],
                    ['label' => 'Меню раскладка за день', 'url' => ['menus-dishes/raskladka'], 'options' => ['class' => '']],
                    ['label' => 'Проверка меню на аллергены', 'url' => ['/menus-dishes/show-allergen'], 'options' => ['class' => '']],
                    ['label' => 'Сборники рецептур', 'url' => ['/settings/recipes-index'], 'options' => ['class' => '']],
                    ['label' => 'Добавить блюдо в сборник', 'url' => ['/dishes'], 'options' => ['class' => '']],
                ]
            ],
            ['label' => 'Ежедневная информация', 'url' => ['#'], 'options' => ['class' => 'mr-3 p-2'],
                'items'=>[
                    //['label' => 'Настройка классов', 'url' => ['/everyday-classes/index'], 'options' => ['class' => '']],
                    //['label' => 'Внести ежедневную информацию', 'url' => ['/everyday-classes/enter'], 'options' => ['class' => '']],
                    ['label' => 'История', 'url' => ['/everyday-classes/history'], 'options' => ['class' => '']],
                ]
            ],*/
            ['label' => 'Организация питания', 'url' => ['#'], 'options' => ['class' => 'mr-3 p-2'],
                'items' => [
                    ['label' => 'Архив меню', 'url' => ['menus/archive'], 'options' => ['class' => 'm-5']],
                    ['label' => 'Настройки меню', 'url' => ['menus/index'], 'options' => ['class' => 'm-5']],
                    ['label' => 'Разработка (редактирование) цикличного меню', 'url' => ['menus-dishes/index'], 'options' => ['class' => '']],
                    ['label' => 'Просмотр действующего меню по дням', 'url' => ['menus-dishes/menus-days'], 'options' => ['class' => '']],
                    ['label' => 'Просмотр действующего меню за период', 'url' => ['menus-dishes/menus-period'], 'options' => ['class' => '']],
                    ['label' => 'Корректировка фактического меню по дате', 'url' => ['menus-dishes/fact-day-index'], 'options' => ['class' => '']],
                    ['label' => 'Фактическое меню за день', 'url' => ['menus-dishes/fact-date'], 'options' => ['class' => '']],
                    ['label' => 'Отчет о повторяемости', 'url' => ['menus-dishes/repeat-report'], 'options' => ['class' => '']],
                    ['label' => 'Отчет о перечне продуктов', 'url' => ['menus-dishes/products-list'], 'options' => ['class' => '']],
                    ['label' => 'Отчет о перечне продуктов за указанный период', 'url' => ['menus-dishes/fact-date-products-list'], 'options' => ['class' => '']],
                    ['label' => 'Прогнозная накопительная ведомость', 'url' => ['menus-dishes/prognos-storage'], 'options' => ['class' => '']],
                    //['label' => 'Фактическая накопительная ведомость', 'url' => ['menus-dishes/fact-storage'], 'options' => ['class' => '']],
                    //['label' => 'Фактическое меню за день', 'url' => ['menus-dishes/fact-day-index'], 'options' => ['class' => '']],
                    ['label' => 'Меню раскладка за день', 'url' => ['menus-dishes/raskladka'], 'options' => ['class' => '']],
                    ['label' => 'Меню раскладка за день по приемам пищи', 'url' => ['menus-dishes/raskladka-nutrition'], 'options' => ['class' => '']],
                    ['label' => 'Проверка меню на аллергены', 'url' => ['/menus-dishes/show-allergen'], 'options' => ['class' => '']],
                    ['label' => 'Проверка меню на хлебные единицы', 'url' => ['/menus-dishes/show-sahar'], 'options' => ['class' => '']],
                    ['label' => 'Технологические карты с учетом количества питающихся (пересчет)', 'url' => ['menus-dishes/techmup-page'], 'options' => ['class' => '']],
                    ['label' => 'Формирование собственных сборников рецептур', 'url' => ['/settings/recipes-index'], 'options' => ['class' => '']],
                    ['label' => 'Добавить блюдо в сборник', 'url' => ['/dishes'], 'options' => ['class' => '']],
                    ['label' => 'База данных блюд', 'url' => ['dishes/dishes-base'], 'options' => ['class' => '']],
                    ['label' => 'База данных продуктов', 'url' => ['products/products-base'], 'options' => ['class' => '']],
                    ['label' => 'Отчет по витаминам и минеральным веществам', 'url' => ['/menus-dishes/report-vitamin'], 'options' => ['class' => '']],
                ]
            ],

            /*['label' => 'Медицинская информация', 'url' => ['#'], 'options' => ['class' => 'mr-3 p-2'],
                'items'=>[
                    ['label' => 'Добавление ребенка', 'url' => ['/kids/create'], 'options' => ['class' => 'm-5']],
                    ['label' => 'Список детей по медицинским осмотрам', 'url' => ['/kids'], 'options' => ['class' => '']],
                ]
            ],*/
            ['label' => 'Видеоуроки', 'url' => ['#'], 'options' => ['class' => 'mr-3 p-2'],
                'items' => [
                    ['label' => 'Заполнение раздела «Характеристика обучающихся»', 'url' => 'https://youtu.be/jVrogZM7FQk', 'options' => ['class' => 'm-5'], 'linkOptions' => ['target'=>'_blank']],
		            ['label' => 'Заполнение раздела «Ежедневная информация»', 'url' => 'https://youtu.be/kGRgI1YlXko', 'options' => ['class' => 'm-5'], 'linkOptions' => ['target'=>'_blank']],
		            ['label' => 'Регистрация сотрудников учреждения (учителей), изменение почты у пользователя, восстановления пароля', 'url' => 'https://www.youtube.com/watch?v=bgSAm-0ZvJ0', 'options' => ['class' => 'm-5'], 'linkOptions' => ['target'=>'_blank']],
		            ['label' => 'Настройка, архив, разработка и редактирование меню', 'url' => 'https://www.youtube.com/watch?v=7AdfJTsJ6_k', 'options' => ['class' => 'm-5'], 'linkOptions' => ['target'=>'_blank']],
		            ['label' => 'Добавление иных технологических карт', 'url' => 'https://www.youtube.com/watch?v=ZsNVJoK6hVY', 'options' => ['class' => 'm-5'], 'linkOptions' => ['target'=>'_blank']],
					['label' => 'Адаптация меню для питания детей с сахарным диабетом и пищевой аллергией', 'url' => 'https://www.youtube.com/watch?v=PxfUL5bUQHs', 'options' => ['class' => 'm-5'], 'linkOptions' => ['target'=>'_blank']],
                ],
            ],
            ['label' => 'Справочная информация', 'url' => ['site/download-document-index'], 'options' => ['class' => 'mr-3 p-2']],
            ['label' => 'ЗАДАТЬ ВОПРОС(ЧАТ)', 'url' => ['chat/index'], 'options' => ['class' => 'mr-3 p-2'],
                'items' => [

                ]
            ],
        ];


        $logout = Html::begintag('div', ['class' => 'text-right'])
            .Html::tag('div', 'Пользователь:('.Yii::$app->user->identity->name.')', ['class' => ''])
            .Html::beginForm(['/site/logout'], 'post')
            .Html::submitButton(' Выход', ['class' => 'btn main-button-2-outline logout'])
            .Html::endForm()
            .Html::endTag('div');
    }


    if (Yii::$app->user->can('camp_director'))
    {
        $menuItems = [
            ['label' => 'Главная', 'url' => ['site/index'], 'options' => ['class' => 'mr-3 p-2']],
            ['label' => 'Личные данные', 'url' => ['users/profile'], 'options' => ['class' => 'mr-3 p-2']],
            ['label' => 'Организация', 'url' => ['#'], 'options' => ['class' => 'mr-3 p-2'],
                'items' => [
                    ['label' => 'Общая информация', 'url' => ['organizations/create'], 'options' => ['class' => '']],
                    //['label' => 'Перечень производственных помещений пищеблока', 'url' => ['basic-information/razdel'], 'options' => ['class' => '']],
                    ['label' => 'Планируемая информация (смены/дети)', 'url' => ['plan-inf-camp/create'], 'options' => ['class' => '']],
                    ['label' => 'Фактическая информация (смены/дети)', 'url' => ['fact-inf-camp/create'], 'options' => ['class' => '']],
                    ['label' => 'Акарицидные обработки (планируемые)', 'url' => ['acaricidal-plan/create'], 'options' => ['class' => '']],
                    ['label' => 'Акарицидные обработки (фактическая)', 'url' => ['acaricidal-fact/create'], 'options' => ['class' => '']],
                ]
            ],
            ['label' => 'Организация питания', 'url' => ['#'], 'options' => ['class' => 'mr-3 p-2'],
                'items' => [
                    ['label' => 'Архив меню', 'url' => ['menus/archive'], 'options' => ['class' => 'm-5']],
                    ['label' => 'Настройки меню', 'url' => ['menus/index'], 'options' => ['class' => 'm-5']],
                    ['label' => 'Разработка (редактирование) цикличного меню', 'url' => ['menus-dishes/index'], 'options' => ['class' => '']],
                    ['label' => 'Просмотр действующего меню по дням', 'url' => ['menus-dishes/menus-days'], 'options' => ['class' => '']],
                    ['label' => 'Просмотр действующего меню за период', 'url' => ['menus-dishes/menus-period'], 'options' => ['class' => '']],
                    ['label' => 'Отчет о повторяемости', 'url' => ['menus-dishes/repeat-report'], 'options' => ['class' => '']],
                    ['label' => 'Отчет о перечне продуктов', 'url' => ['menus-dishes/products-list'], 'options' => ['class' => '']],
                    ['label' => 'Прогнозная накопительная ведомость', 'url' => ['menus-dishes/prognos-storage'], 'options' => ['class' => '']],
                    ['label' => 'Фактическое меню по дате', 'url' => ['menus-dishes/fact-date'], 'options' => ['class' => '']],
                    //['label' => 'Фактическая накопительная ведомость', 'url' => ['menus-dishes/fact-storage'], 'options' => ['class' => '']],
                    ['label' => 'Фактическое меню за день', 'url' => ['menus-dishes/fact-day-index'], 'options' => ['class' => '']],
                    ['label' => 'Меню раскладка за день', 'url' => ['menus-dishes/raskladka'], 'options' => ['class' => '']],
                    ['label' => 'Меню раскладка за день по приемам пищи', 'url' => ['menus-dishes/raskladka-nutrition'], 'options' => ['class' => '']],
                    ['label' => 'Проверка меню на аллергены', 'url' => ['/menus-dishes/show-allergen'], 'options' => ['class' => '']],
                    ['label' => 'Проверка меню на хлебные единицы', 'url' => ['/menus-dishes/show-sahar'], 'options' => ['class' => '']],
                    ['label' => 'Технологические карты с учетом количества питающихся (пересчет)', 'url' => ['menus-dishes/techmup-page'], 'options' => ['class' => '']],
                    ['label' => 'Сборники рецептур', 'url' => ['/settings/recipes-index'], 'options' => ['class' => '']],
                    ['label' => 'Добавить блюдо в сборник', 'url' => ['/dishes'], 'options' => ['class' => '']],
                    ['label' => 'База данных блюд', 'url' => ['dishes/dishes-base'], 'options' => ['class' => '']],
                    ['label' => 'База данных продуктов', 'url' => ['products/products-base'], 'options' => ['class' => '']],
                    ['label' => 'Замена продуктов в меню', 'url' => ['/products-change-organization/index'], 'options' => ['class' => '']],
                    ['label' => 'Отчет по витаминам и минеральным веществам', 'url' => ['/menus-dishes/report-vitamin'], 'options' => ['class' => '']],
                ]],
            ['label' => 'Медицинская информация', 'url' => ['#'], 'options' => ['class' => 'mr-3 p-2'],
                'items' => [
                    ['label' => 'Список детей по отрядам', 'url' => ['kids/choice-list-kids-camp'], 'options' => ['class' => '']],
                    ['label' => 'Добавление ребенка в отряд', 'url' => ['kids/kids-med-create'], 'options' => ['class' => '']],
                    ['label' => 'Добавление медицинской информации по детям', 'url' => ['kids/list-kids-camp'], 'options' => ['class' => '']],
                    ['label' => 'Термометрия детей', 'url' => ['kids/choice-list-kids-ter-camp'], 'options' => ['class' => '']],
//                    ['label' => 'Журнал регистрации амбулаторных больных (Форма №074/у)', 'url' => ['kids/ambulatory-cart-camp-index'], 'options' => ['class' => '']],
//                    ['label' => 'Журнал изолятора (Форма №059/у)', 'url' => ['kids/isolator-cart-camp'], 'options' => ['class' => '']],
                    ['label' => 'Журнал оценки эффективности оздоровления', 'url' => ['medicals/report-journal-oeo'], 'options' => ['class' => '']],
                ]
            ],
            ['label' => 'Ежедневная информация', 'url' => ['#'], 'options' => ['class' => 'mr-3 p-2'],
                'items' => [
                    ['label' => 'Внештатные ситуации', 'url' => ['/daily-informations-camp/index'], 'options' => ['class' => '']],
                    //['label' => 'Отчет о внештатных ситуациях', 'url' => ['/everyday-camp/class-report'], 'options' => ['class' => '']],
                ]
            ],
            ['label' => 'Отчеты', 'url' => ['#'], 'options' => ['class' => 'mr-3 p-2'],
                'items' => [
                    ['label' => 'Список детей по отрядам', 'url' => ['kids/choice-list-kids-camp'], 'options' => ['class' => '']],
                    ['label' => 'Индивидуальный отчет', 'url' => ['kids/individual-report-kids-camp'], 'options' => ['class' => '']],
                    ['label' => 'Дети/Заезды', 'url' => ['fact-inf-camp/report'], 'options' => ['class' => '']],
                    ['label' => 'Отчет об акарицидных обработках', 'url' => ['acaricidal-fact/report'], 'options' => ['class' => '']],
                    ['label' => 'Отчет в Роспотребнадзор', 'url' => ['medicals/report-rospotrebnadzor'], 'options' => ['class' => '']],
                    ['label' => 'Коллективный отчет по оценке эффективности оздоровления', 'url' => ['medicals/report-collective'], 'options' => ['class' => '']],
                    ['label' => 'Гигиеническая оценка меню', 'url' => ['/menus-dishes/expertiza'], 'options' => ['class' => '']],
                ]
            ],
            ['label' => 'Видеоуроки', 'url' => ['#'], 'options' => ['class' => 'mr-3 p-2'],
                'items' => [
                    ['label' => '№1 Регистрация, внесение общей информации, письмо в службу поддержки', 'url' => 'https://www.youtube.com/watch?v=ta528neUmt4', 'options' => ['class' => ''], 'linkOptions' => ['target' => '_blank']],
                    ['label' => '№2 Внесение планируемой и фактической информации, медицинской информации', 'url' => 'https://www.youtube.com/watch?v=cy6VIXOad_w', 'options' => ['class' => ''], 'linkOptions' => ['target' => '_blank']],
                    ['label' => '№3 Журнал оценки эффективности оздоровления детей. Построение отчетов.', 'url' => 'https://www.youtube.com/watch?v=NWzAf5oc8n4', 'options' => ['class' => ''], 'linkOptions' => ['target' => '_blank']],
                    ['label' => '№4 Внесение ежедневной информации. Термометрия детей.', 'url' => 'https://www.youtube.com/watch?v=yZUv06QE_xE', 'options' => ['class' => ''], 'linkOptions' => ['target' => '_blank']],
                    ['label' => '№5 Настройка, архив, разработка и редактирование меню', 'url' => 'https://www.youtube.com/watch?v=7AdfJTsJ6_k', 'options' => ['class' => 'm-5'], 'linkOptions' => ['target'=>'_blank']],
                    ['label' => '№6 Добавление иных технологических карт', 'url' => 'https://www.youtube.com/watch?v=ZsNVJoK6hVY', 'options' => ['class' => 'm-5'], 'linkOptions' => ['target'=>'_blank']],
                    ['label' => '№7 Адаптация меню для питания детей с сахарным диабетом и пищевой аллергией', 'url' => 'https://www.youtube.com/watch?v=PxfUL5bUQHs', 'options' => ['class' => 'm-5'], 'linkOptions' => ['target'=>'_blank']],
                ]
            ],
            ['label' => 'Справочная информация', 'url' => ['site/download-document-index'], 'options' => ['class' => 'mr-3 p-2']],
            ['label' => 'ЗАДАТЬ ВОПРОС(ЧАТ)', 'url' => ['chat/index'], 'options' => ['class' => 'mr-3 p-2'],
                'items' => [

                ]
            ],

            /*['label' => 'Анкетирование', 'url' => ['#'], 'options' => ['class' => 'mr-3 p-2'],
                    'items'=>[
                        ['label' => 'Анкета для детей', 'url' => ['anket-kids-camp/create'], 'options' => ['class' => '']],
                    ]
                ],*/
            /*['label' => 'Пользователи', 'url' => ['users/level'], 'options' => ['class' => 'mr-3 p-2'],
                'items'=>[

                ]
	],*/
            /*['label' => 'Дефицитные состояния', 'url' => ['#'], 'options' => ['class' => 'mr-3 p-2']],*/

        ];
        if ($my_organization->organizator_food == 0)
        {
            $menuItems[1] =
                ['label' => 'Организация питания', 'url' => ['#'], 'options' => ['class' => 'mr-3 p-2'],
                    'items' => [
                        ['label' => 'Прогнозная накопительная ведомость', 'url' => ['prognos-storage-org-food/create'], 'options' => ['class' => '']],
                    ]];
        }


        $logout = Html::begintag('div', ['class' => 'row'])
            . Html::begintag('div', ['class' => 'col-4'])
            . Html::beginForm(['#'], 'post')
            //. //Html::submitInput('Регистрация работников учреждения', ['id' => 'registration_employee', 'class' => 'btn btn-outline-success'])
            // Html::a('Регистрация работников учреждения', ['users/createuser'], ['class' => 'btn main-button-2-outline'])
            . Html::endForm()
            . Html::endtag('div')
            . Html::tag('div', 'Пользователь: ' . Yii::$app->user->identity->name . '(' . Yii::$app->user->identity->organization_id . ')', ['class' => 'col-6 text-right'])
            . Html::begintag('div', ['class' => 'col-2 text-right'])
            . Html::beginForm(['/site/logout'], 'post')
            . Html::submitButton(' Выход', ['class' => 'btn main-button-2-outline logout'])
            . Html::endForm()
            . Html::endtag('div')
            . Html::endtag('div');
    }


    if (Yii::$app->user->can('internat_director'))
    {
        $menuItems = [
            ['label' => 'Главная', 'url' => ['/'], 'options' => ['class' => 'mr-3 p-2']],
            ['label' => 'Отчеты', 'url' => ['#'], 'options' => ['class' => 'main-button-2-hover-orange mr-3 p-2'],
                'items' => [
                    ['label' => 'Формирование документов для размещения на сайте', 'url' => ['/menus-dishes/report-document'], 'options' => ['class' => '']],
                    ['label' => 'Гигиеническая оценка меню', 'url' => ['/menus-dishes/expertiza'], 'options' => ['class' => '']],
                    ['label' => 'Отчет по контрольным мероприятиям', 'url' => ['anket-parent-control/report'], 'options' => ['class' => '']],
                ],
            ],
            ['label' => 'Личные данные', 'url' => ['users/profile'], 'options' => ['class' => 'mr-3 p-2']],
            ['label' => 'Организация', 'url' => ['#'], 'options' => ['class' => 'mr-3 p-2'],
                'items' => [
                    ['label' => 'Общая информация', 'url' => ['organizations/create'], 'options' => ['class' => '']],
                    ['label' => 'Перечень производственных помещений пищеблока', 'url' => ['basic-information/razdel'], 'options' => ['class' => '']],
                    ['label' => 'Перечень оборудования производственных помещений пищеблока', 'url' => ['basic-information/create'], 'options' => ['class' => '']],
                    ['label' => 'Характеристика работы пищеблока', 'url' => ['characters-stolovaya/index'], 'options' => ['class' => '']],
                    ['label' => 'Перемены, использующиеся для организации питания', 'url' => ['school-break/index'], 'options' => ['class' => '']],
                    //['label' => 'Информация о расходах на питание', 'url' => ['expenses-food/index'], 'options' => ['class' => '']],
                    ['label' => 'Информация о количестве обучающихся', 'url' => ['information-education/index'], 'options' => ['class' => '']],
                    ['label' => 'Характеристика обучающихся', 'url' => ['characters-study/index-new'], 'options' => ['class' => '']],
                    ['label' => 'Родительский контроль', 'url' => ['anket-parent-control/create'], 'options' => ['class' => '']],
                    ['label' => 'Внутренний контроль', 'url' => ['anket-parent-control/inside'], 'options' => ['class' => '']],
                    //['label' => 'Общественный контроль', 'url' => ['anket-parent-control/social'], 'options' => ['class' => '']],
                    //['label' => 'Производственный контроль', 'url' => ['#'], 'options' => ['class' => '']],
                    ['label' => 'Отчет по контрольным мероприятиям', 'url' => ['anket-parent-control/report'], 'options' => ['class' => '']],
                ]
            ],
            ['label' => 'Организация питания', 'url' => ['#'], 'options' => ['class' => 'mr-3 p-2'],
                'items' => [
                    ['label' => 'Архив меню', 'url' => ['menus/archive'], 'options' => ['class' => 'm-5']],
                    ['label' => 'Настройки меню', 'url' => ['menus/index'], 'options' => ['class' => 'm-5']],
                    ['label' => 'Разработка (редактирование) цикличного меню', 'url' => ['menus-dishes/index'], 'options' => ['class' => '']],
                    ['label' => 'Просмотр действующего меню по дням', 'url' => ['menus-dishes/menus-days'], 'options' => ['class' => '']],
                    ['label' => 'Просмотр действующего меню за период', 'url' => ['menus-dishes/menus-period'], 'options' => ['class' => '']],
                    ['label' => 'Корректировка фактического меню по дате', 'url' => ['menus-dishes/fact-day-index'], 'options' => ['class' => '']],
                    ['label' => 'Фактическое меню за день', 'url' => ['menus-dishes/fact-date'], 'options' => ['class' => '']],
                    ['label' => 'Отчет о повторяемости', 'url' => ['menus-dishes/repeat-report'], 'options' => ['class' => '']],
                    ['label' => 'Отчет о перечне продуктов', 'url' => ['menus-dishes/products-list'], 'options' => ['class' => '']],
                    ['label' => 'Отчет о перечне продуктов за указанный период', 'url' => ['menus-dishes/fact-date-products-list'], 'options' => ['class' => '']],
                    ['label' => 'Прогнозная накопительная ведомость', 'url' => ['menus-dishes/prognos-storage'], 'options' => ['class' => '']],
                    //['label' => 'Фактическая накопительная ведомость', 'url' => ['menus-dishes/fact-storage'], 'options' => ['class' => '']],
                    //['label' => 'Фактическое меню за день', 'url' => ['menus-dishes/fact-day-index'], 'options' => ['class' => '']],
                    ['label' => 'Меню раскладка за день', 'url' => ['menus-dishes/raskladka'], 'options' => ['class' => '']],
                    ['label' => 'Меню раскладка за день по приемам пищи', 'url' => ['menus-dishes/raskladka-nutrition'], 'options' => ['class' => '']],
                    ['label' => 'Проверка меню на аллергены', 'url' => ['/menus-dishes/show-allergen'], 'options' => ['class' => '']],
                    ['label' => 'Проверка меню на хлебные единицы', 'url' => ['/menus-dishes/show-sahar'], 'options' => ['class' => '']],
                    ['label' => 'Технологические карты с учетом количества питающихся (пересчет)', 'url' => ['menus-dishes/techmup-page'], 'options' => ['class' => '']],
                    ['label' => 'Формирование собственных сборников рецептур', 'url' => ['/settings/recipes-index'], 'options' => ['class' => '']],
                    ['label' => 'Добавить блюдо в сборник', 'url' => ['/dishes'], 'options' => ['class' => '']],
                    ['label' => 'База данных блюд', 'url' => ['dishes/dishes-base'], 'options' => ['class' => '']],
                    ['label' => 'База данных продуктов', 'url' => ['products/products-base'], 'options' => ['class' => '']],
                    ['label' => 'Формирование документов(печать меню)', 'url' => ['/menus-dishes/report-document'], 'options' => ['class' => '']],
                    ['label' => 'Отчет по витаминам и минеральным веществам', 'url' => ['/menus-dishes/report-vitamin'], 'options' => ['class' => '']],
                ]
            ],
            ['label' => 'Ежедневная информация', 'url' => ['#'], 'options' => ['class' => 'mr-3 p-2'],
                'items' => [
                    //['label' => 'Настройка классов', 'url' => ['/everyday-classes/index'], 'options' => ['class' => '']],
                    ['label' => 'Внести ежедневную информацию', 'url' => ['/everyday-classes/enter'], 'options' => ['class' => '']],
                    //['label' => 'История', 'url' => ['/everyday-classes/history'], 'options' => ['class' => '']],
                    ['label' => 'Отчет о ежедневной информации по классам', 'url' => ['/everyday-classes/class-report'], 'options' => ['class' => '']],
                    //['label' => 'Отчет о ежедневной информации по переменам', 'url' => ['/everyday-classes/peremena-report'], 'options' => ['class' => '']],
                ]
            ],
            ['label' => 'Пользователи', 'url' => ['users/level'], 'options' => ['class' => 'mr-3 p-2'],
                'items' => [

                ]
            ],
            ['label' => 'Видеоуроки', 'url' => ['#'], 'options' => ['class' => 'mr-3 p-2'],
                'items' => [
                    ['label' => 'Заполнение раздела «Характеристика обучающихся»', 'url' => 'https://youtu.be/jVrogZM7FQk', 'options' => ['class' => 'm-5'], 'linkOptions' => ['target'=>'_blank']],
		            ['label' => 'Заполнение раздела «Ежедневная информация»', 'url' => 'https://youtu.be/kGRgI1YlXko', 'options' => ['class' => 'm-5'], 'linkOptions' => ['target'=>'_blank']],
			    ['label' => 'Регистрация сотрудников учреждения (учителей), изменение почты у пользователя, восстановления пароля', 'url' => 'https://www.youtube.com/watch?v=bgSAm-0ZvJ0', 'options' => ['class' => 'm-5'], 'linkOptions' => ['target'=>'_blank']],
			    ['label' => 'Настройка, архив, разработка и редактирование меню', 'url' => 'https://www.youtube.com/watch?v=7AdfJTsJ6_k', 'options' => ['class' => 'm-5'], 'linkOptions' => ['target'=>'_blank']],
			    ['label' => 'Добавление иных технологических карт', 'url' => 'https://www.youtube.com/watch?v=ZsNVJoK6hVY', 'options' => ['class' => 'm-5'], 'linkOptions' => ['target'=>'_blank']],
			    ['label' => 'Адаптация меню для питания детей с сахарным диабетом и пищевой аллергией', 'url' => 'https://www.youtube.com/watch?v=PxfUL5bUQHs', 'options' => ['class' => 'm-5'], 'linkOptions' => ['target'=>'_blank']],
                ],
            ],
            ['label' => 'Справочная информация', 'url' => ['site/download-document-index'], 'options' => ['class' => 'mr-3 p-2']],
            ['label' => 'ЗАДАТЬ ВОПРОС(ЧАТ)', 'url' => ['chat/index'], 'options' => ['class' => 'mr-3 p-2'],
                'items' => [

                ]
            ],
            /*['label' => 'Дефицитные состояния', 'url' => ['#'], 'options' => ['class' => 'mr-3 p-2']],*/
            /*['label' => 'Информация классного руководителя', 'url' => ['#'], 'options' => ['class' => 'mr-3 p-2'],
                'items'=>[
                    ['label' => 'Настройки классов', 'url' => ['/configuration-classes'], 'options' => ['class' => 'm-5']],
                    //['label' => 'Ввод ежедневной информации', 'url' => ['/daily-informations'], 'options' => ['class' => '']],
                    //['label' => 'Отчет по ежедневной информации', 'url' => ['#'], 'options' => ['class' => '']],
                ]],*/
            /*['label' => 'Медицинская информация', 'url' => ['#'], 'options' => ['class' => 'mr-3 p-2'],
                'items'=>[
                    ['label' => 'Добавление ребенка', 'url' => ['/kids/create'], 'options' => ['class' => 'm-5']],
                    ['label' => 'Список детей по медицинским осмотрам', 'url' => ['/kids'], 'options' => ['class' => '']],
                ]],*/

            /*['label' => 'Анкетирование', 'url' => ['#'], 'options' => ['class' => 'mr-3 p-2'],
                'items'=>[
                    ['label' => 'Анкета школьников', 'url' => ['/anket-children/create'], 'options' => ['class' => '']],
                    ['label' => 'Анкета родителей школьников', 'url' => ['/anket-parents-school-children/create'], 'options' => ['class' => '']],
                    ['label' => 'Анкета родителей дошкольников', 'url' => ['/anket-preschoolers/create'], 'options' => ['class' => '']],
                    ['label' => 'Анкета учителей', 'url' => ['/anket-teacher/create'], 'options' => ['class' => '']],
                    ['label' => 'Отчет по школьникам', 'url' => ['/anket-children/report'], 'options' => ['class' => '']],
                    ['label' => 'Отчет по родителям школьников', 'url' => ['/anket-parents-school-children/report'], 'options' => ['class' => '']],
                    ['label' => 'Отчет по родителям дошкольников', 'url' => ['/anket-preschoolers/report'], 'options' => ['class' => '']],
                    ['label' => 'Отчет по учителям', 'url' => ['/anket-teacher/report'], 'options' => ['class' => '']],
                ]
            ],*/
            /*['label' => 'Организаторы питания', 'url' => ['#'], 'options' => ['class' => 'mr-3 p-2'],
                'items'=>[
                    ['label' => 'Список список организаций питания', 'url' => ['nutrition-applications/organizations'], 'options' => ['class' => 'm-5']],
                    ['label' => 'Отправленные заявки', 'url' => ['nutrition-applications/index'], 'options' => ['class' => 'm-5']],
                    ['label' => 'Полученные заявки', 'url' => ['nutrition-applications/receiving'], 'options' => ['class' => 'm-5']],
                    ['label' => 'Меню от организатора питания', 'url' => ['nutrition-applications/send-menu'], 'options' => ['class' => 'm-5']],


                ]
            ],*/


        ];
        $logout = Html::begintag('div', ['class' => 'text-right'])
            .Html::tag('div', 'Пользователь:('.Yii::$app->user->identity->name.')', ['class' => ''])
            .Html::beginForm(['/site/logout'], 'post')
            .Html::submitButton(' Выход', ['class' => 'btn main-button-2-outline logout'])
            .Html::endForm()
            .Html::endTag('div');
    }


    if (Yii::$app->user->can('kindergarten_director'))
    {
        $menuItems = [
            /*['label' => 'Отчеты', 'url' => ['#'], 'options' => ['class' => 'main-button-2-hover-orange mr-3 p-2'],
                'items'=>[
                    ['label' => 'Накопительная ведомость', 'url' => ['#'], 'options' => ['class' => '']],
                    ['label' => 'Меню раскладка за день', 'url' => ['#'], 'options' => ['class' => '']],
                    ['label' => 'Отчет о повторяемости', 'url' => ['#'], 'options' => ['class' => '']],
                    ['label' => 'Отчет (охват питания)', 'url' => ['#'], 'options' => ['class' => '']],
                    ['label' => 'Отчет по коллективной оценке здоровья', 'url' => ['#'], 'options' => ['class' => '']],
                    ['label' => 'Индивидуальное меню', 'url' => ['#'], 'options' => ['class' => '']],
                    ['label' => 'Отчет об анкетировании', 'url' => ['#'], 'options' => ['class' => '']],
                ],
            ],*/
            ['label' => 'Отчеты', 'url' => ['#'], 'options' => ['class' => 'main-button-2-hover-orange mr-3 p-2'],
                'items' => [
                    ['label' => 'Гигиеническая оценка меню', 'url' => ['/menus-dishes/expertiza'], 'options' => ['class' => '']],

                ],
            ],
            ['label' => 'Личные данные', 'url' => ['users/profile'], 'options' => ['class' => 'mr-3 p-2']],
            ['label' => 'Организация', 'url' => ['#'], 'options' => ['class' => 'mr-3 p-2'],
                'items' => [
                    ['label' => 'Общая информация', 'url' => ['organizations/create'], 'options' => ['class' => '']],
                    ['label' => 'Перечень производственных помещений пищеблока', 'url' => ['basic-information/razdel'], 'options' => ['class' => '']],
                    ['label' => 'Перечень оборудования производственных помещений пищеблока', 'url' => ['basic-information/create'], 'options' => ['class' => '']],
                    //['label' => 'Общественный контроль', 'url' => ['#'], 'options' => ['class' => '']],
                    //['label' => 'Производственный контроль', 'url' => ['#'], 'options' => ['class' => '']],
                ]
            ],
            ['label' => 'Организация питания', 'url' => ['#'], 'options' => ['class' => 'mr-3 p-2'],
                'items' => [
                    ['label' => 'Архив меню', 'url' => ['menus/archive'], 'options' => ['class' => 'm-5']],
                    ['label' => 'Настройки меню', 'url' => ['menus/index'], 'options' => ['class' => 'm-5']],
                    ['label' => 'Разработка (редактирование) цикличного меню', 'url' => ['menus-dishes/index'], 'options' => ['class' => '']],
                    ['label' => 'Просмотр действующего меню по дням', 'url' => ['menus-dishes/menus-days'], 'options' => ['class' => '']],
                    ['label' => 'Просмотр действующего меню за период', 'url' => ['menus-dishes/menus-period'], 'options' => ['class' => '']],
                    ['label' => 'Просмотр действующего меню за период(упрощенная)', 'url' => ['menus-dishes/menus-period-disable'], 'options' => ['class' => '']],
                    ['label' => 'Корректировка фактического меню по дате', 'url' => ['menus-dishes/fact-day-index'], 'options' => ['class' => '']],
                    ['label' => 'Фактическое меню за день', 'url' => ['menus-dishes/fact-date'], 'options' => ['class' => '']],
                    ['label' => 'Отчет о повторяемости', 'url' => ['menus-dishes/repeat-report'], 'options' => ['class' => '']],
                    ['label' => 'Отчет о перечне продуктов', 'url' => ['menus-dishes/products-list'], 'options' => ['class' => '']],
                    ['label' => 'Отчет о перечне продуктов за указанный период', 'url' => ['menus-dishes/fact-date-products-list'], 'options' => ['class' => '']],
                    ['label' => 'Прогнозная накопительная ведомость', 'url' => ['menus-dishes/prognos-storage'], 'options' => ['class' => '']],
                    //['label' => 'Фактическая накопительная ведомость', 'url' => ['menus-dishes/fact-storage'], 'options' => ['class' => '']],
                    ['label' => 'Фактическое меню за день', 'url' => ['menus-dishes/fact-day-index'], 'options' => ['class' => '']],
                    ['label' => 'Меню раскладка за день', 'url' => ['menus-dishes/raskladka'], 'options' => ['class' => '']],
                    ['label' => 'Меню раскладка за день по приемам пищи', 'url' => ['menus-dishes/raskladka-nutrition'], 'options' => ['class' => '']],
                    ['label' => 'Проверка меню на аллергены', 'url' => ['/menus-dishes/show-allergen'], 'options' => ['class' => '']],
                    ['label' => 'Проверка меню на хлебные единицы', 'url' => ['/menus-dishes/show-sahar'], 'options' => ['class' => '']],
                    ['label' => 'Технологические карты с учетом количества питающихся (пересчет)', 'url' => ['menus-dishes/techmup-page'], 'options' => ['class' => '']],
                    ['label' => 'Сборники рецептур', 'url' => ['/settings/recipes-index'], 'options' => ['class' => '']],
                    ['label' => 'Добавить блюдо в сборник', 'url' => ['/dishes'], 'options' => ['class' => '']],
                    ['label' => 'База данных блюд', 'url' => ['dishes/dishes-base'], 'options' => ['class' => '']],
                    ['label' => 'База данных продуктов', 'url' => ['products/products-base'], 'options' => ['class' => '']],
                    ['label' => 'Формирование документов(печать меню)', 'url' => ['/menus-dishes/report-document'], 'options' => ['class' => '']],
                    ['label' => 'Замена продуктов в меню', 'url' => ['/products-change-organization/index'], 'options' => ['class' => '']],
                    ['label' => 'Отчет по витаминам и минеральным веществам', 'url' => ['/menus-dishes/report-vitamin'], 'options' => ['class' => '']],
                ]],
            /*['label' => 'Дефицитные состояния', 'url' => ['#'], 'options' => ['class' => 'mr-3 p-2']],*/
            /*['label' => 'Информация классного руководителя', 'url' => ['#'], 'options' => ['class' => 'mr-3 p-2'],
                'items'=>[
                    ['label' => 'Настройки классов', 'url' => ['/configuration-classes'], 'options' => ['class' => 'm-5']],
                    ['label' => 'Ввод ежедневной информации', 'url' => ['/daily-informations'], 'options' => ['class' => '']],
                    ['label' => 'Отчет по ежедневной информации', 'url' => ['#'], 'options' => ['class' => '']],
                ]],*/
            /*['label' => 'Медицинская информация', 'url' => ['#'], 'options' => ['class' => 'mr-3 p-2'],
                'items'=>[
                    ['label' => 'Добавление медицинского осмотра', 'url' => ['/kids/create'], 'options' => ['class' => 'm-5']],
                    ['label' => 'Список детей по медицинским осмотрам', 'url' => ['/kids'], 'options' => ['class' => '']],
                ]
			],*/
            ['label' => 'Пользователи', 'url' => ['users/level'], 'options' => ['class' => 'mr-3 p-2'],
                'items' => [

                ]
            ],
            ['label' => 'Видеоуроки', 'url' => ['#'], 'options' => ['class' => 'mr-3 p-2'],
            'items' => [
		        //['label' => 'Заполнение раздела «Характеристика обучающихся»', 'url' => 'https://youtu.be/jVrogZM7FQk', 'options' => ['class' => 'm-5'], 'linkOptions' => ['target'=>'_blank']],
	            //['label' => 'Заполнение раздела «Ежедневная информация»', 'url' => 'https://youtu.be/kGRgI1YlXko', 'options' => ['class' => 'm-5'], 'linkOptions' => ['target'=>'_blank']],
			    //['label' => 'Регистрация сотрудников учреждения (учителей), изменение почты у пользователя, восстановления пароля', 'url' => 'https://www.youtube.com/watch?v=bgSAm-0ZvJ0', 'options' => ['class' => 'm-5'], 'linkOptions' => ['target'=>'_blank']],
			    ['label' => 'Настройка, архив, разработка и редактирование меню', 'url' => 'https://www.youtube.com/watch?v=7AdfJTsJ6_k', 'options' => ['class' => 'm-5'], 'linkOptions' => ['target'=>'_blank']],
			    ['label' => 'Добавление иных технологических карт', 'url' => 'https://www.youtube.com/watch?v=ZsNVJoK6hVY', 'options' => ['class' => 'm-5'], 'linkOptions' => ['target'=>'_blank']],
				['label' => 'Адаптация меню для питания детей с сахарным диабетом и пищевой аллергией', 'url' => 'https://www.youtube.com/watch?v=PxfUL5bUQHs', 'options' => ['class' => 'm-5'], 'linkOptions' => ['target'=>'_blank']],
            ],
            ],
            ['label' => 'Справочная информация', 'url' => ['site/download-document-index'], 'options' => ['class' => 'mr-3 p-2']],
            ['label' => 'ЗАДАТЬ ВОПРОС(ЧАТ)', 'url' => ['chat/index'], 'options' => ['class' => 'mr-3 p-2'],
                'items' => [

                ]
            ],

            /*['label' => 'Анкетирование', 'url' => ['#'], 'options' => ['class' => 'mr-3 p-2'],
                'items'=>[
                    ['label' => 'Анкета школьников', 'url' => ['/anket-children/create'], 'options' => ['class' => '']],
                    ['label' => 'Анкета родителей школьников', 'url' => ['/anket-parents-school-children/create'], 'options' => ['class' => '']],
                    ['label' => 'Анкета родителей дошкольников', 'url' => ['/anket-preschoolers/create'], 'options' => ['class' => '']],
                    ['label' => 'Анкета учителей', 'url' => ['/anket-teacher/create'], 'options' => ['class' => '']],
                    ['label' => 'Отчет по школьникам', 'url' => ['/anket-children/report'], 'options' => ['class' => '']],
                    ['label' => 'Отчет по родителям школьников', 'url' => ['/anket-parents-school-children/report'], 'options' => ['class' => '']],
                    ['label' => 'Отчет по родителям дошкольников', 'url' => ['/anket-preschoolers/report'], 'options' => ['class' => '']],
                    ['label' => 'Отчет по учителям', 'url' => ['/anket-teacher/report'], 'options' => ['class' => '']],
                ]
            ],*/


        ];
        $logout = Html::begintag('div', ['class' => 'text-right'])
            .Html::tag('div', 'Пользователь:('.Yii::$app->user->identity->name.')', ['class' => ''])
            .Html::beginForm(['/site/logout'], 'post')
            .Html::submitButton(' Выход', ['class' => 'btn main-button-2-outline logout'])
            .Html::endForm()
            .Html::endTag('div');
    }

    if (Yii::$app->user->can('food_director'))
    {
        $menuItems = [
            /*['label' => 'Отчеты', 'url' => ['#'], 'options' => ['class' => 'main-button-2-hover-orange mr-3 p-2'],
                'items'=>[
                    ['label' => 'Формирование документов(печать меню)', 'url' => ['/menus-dishes/report-document'], 'options' => ['class' => '']],
                ],
            ],*/
            ['label' => 'Главная', 'url' => ['site/index'], 'options' => ['class' => 'mr-3 p-2']],
            ['label' => 'Личные данные', 'url' => ['users/profile'], 'options' => ['class' => 'mr-3 p-2']],
            ['label' => 'Организация', 'url' => ['#'], 'options' => ['class' => 'mr-3 p-2'],
                'items' => [
                    ['label' => 'Общая информация', 'url' => ['organizations/create'], 'options' => ['class' => '']],
                ]
            ],
            ['label' => 'Организация питания', 'url' => ['#'], 'options' => ['class' => 'mr-3 p-2'],
                'items' => [
                    ['label' => 'Архив меню', 'url' => ['menus/archive'], 'options' => ['class' => 'm-5']],
                    ['label' => 'Настройки меню', 'url' => ['menus/index'], 'options' => ['class' => 'm-5']],
                    ['label' => 'Разработка (редактирование) цикличного меню', 'url' => ['menus-dishes/index'], 'options' => ['class' => '']],
                    ['label' => 'Просмотр действующего меню по дням', 'url' => ['menus-dishes/menus-days'], 'options' => ['class' => '']],
                    ['label' => 'Просмотр действующего меню за период', 'url' => ['menus-dishes/menus-period'], 'options' => ['class' => '']],
                    ['label' => 'Корректировка фактического меню по дате', 'url' => ['menus-dishes/fact-day-index'], 'options' => ['class' => '']],
                    ['label' => 'Фактическое меню за день', 'url' => ['menus-dishes/fact-date'], 'options' => ['class' => '']],
                    ['label' => 'Отчет о повторяемости', 'url' => ['menus-dishes/repeat-report'], 'options' => ['class' => '']],
                    ['label' => 'Отчет о перечне продуктов', 'url' => ['menus-dishes/products-list'], 'options' => ['class' => '']],
                    ['label' => 'Отчет о перечне продуктов за указанный период', 'url' => ['menus-dishes/fact-date-products-list'], 'options' => ['class' => '']],
                    ['label' => 'Прогнозная накопительная ведомость', 'url' => ['menus-dishes/prognos-storage'], 'options' => ['class' => '']],
                    //['label' => 'Фактическая накопительная ведомость', 'url' => ['menus-dishes/fact-storage'], 'options' => ['class' => '']],
                    ['label' => 'Меню раскладка за день', 'url' => ['menus-dishes/raskladka'], 'options' => ['class' => '']],
                    ['label' => 'Меню раскладка за день по приемам пищи', 'url' => ['menus-dishes/raskladka-nutrition'], 'options' => ['class' => '']],
                    ['label' => 'Проверка меню на аллергены', 'url' => ['/menus-dishes/show-allergen'], 'options' => ['class' => '']],
                    ['label' => 'Проверка меню на хлебные единицы', 'url' => ['/menus-dishes/show-sahar'], 'options' => ['class' => '']],
                    ['label' => 'Технологические карты с учетом количества питающихся (пересчет)', 'url' => ['menus-dishes/techmup-page'], 'options' => ['class' => '']],
                    ['label' => 'Сборники рецептур', 'url' => ['/settings/recipes-index'], 'options' => ['class' => '']],
                    ['label' => 'Добавить блюдо в сборник', 'url' => ['/dishes'], 'options' => ['class' => '']],
                    ['label' => 'База данных блюд', 'url' => ['dishes/dishes-base'], 'options' => ['class' => '']],
                    ['label' => 'База данных продуктов', 'url' => ['products/products-base'], 'options' => ['class' => '']],
                    ['label' => 'Формирование документов(печать меню)', 'url' => ['/menus-dishes/report-document'], 'options' => ['class' => '']],
                    ['label' => 'Замена продуктов в меню', 'url' => ['/products-change-organization/index'], 'options' => ['class' => '']],
                    ['label' => 'Отчет по витаминам и минеральным веществам', 'url' => ['/menus-dishes/report-vitamin'], 'options' => ['class' => '']],
                    ['label' => 'Шаблоны цен для продуктов', 'url' => ['/products-cost-shablon/index'], 'options' => ['class' => '']],
                    ['label' => 'Отчет по стоимости меню', 'url' => ['/products-cost-shablon/report-document'], 'options' => ['class' => '']],
                    ['label' => 'Стоимость перечня продуктов', 'url' => ['/products-cost-shablon/products-list'], 'options' => ['class' => '']],
                ]
            ],
            ['label' => 'Отчеты', 'url' => ['#'], 'options' => ['class' => 'mr-3 p-2'],
                'items' => [
                    //['label' => 'Анализ разработанных меню', 'url' => ['menus/menus-monitoring'], 'options' => ['class' => '']],
                    ['label' => 'Характеристика детей и имеющихся меню по школам', 'url' => ['menus-dishes/report-school-little2'], 'options' => ['class' => '']],
                    ['label' => 'Отчет по питанию детей по переменам', 'url' => ['/school-break/report-peremena'], 'options' => ['class' => '']],
                    //['label' => 'Мониторинг питания', 'url' => ['menus/monitoring'], 'options' => ['class' => '']],
                    ['label' => 'Формирование документов(печать меню)', 'url' => ['/menus-dishes/report-document'], 'options' => ['class' => '']],
                    ['label' => 'Полный отчет по родительскому и внутреннему контролю', 'url' => ['anket-parent-control/report'], 'options' => ['class' => '']],
                    ['label' => 'Гигиеническая оценка меню', 'url' => ['/menus-dishes/expertiza'], 'options' => ['class' => '']],
                ]
            ],
            ['label' => 'Работа с образовательными организациями', 'url' => ['#'], 'options' => ['class' => 'mr-3 p-2'],
                'items' => [
                    ['label' => 'Список образовательных организаций', 'url' => ['nutrition-applications/organizations'], 'options' => ['class' => 'm-5']],
                    ['label' => 'Заявки на сотрудничество', 'url' => ['nutrition-applications/index'], 'options' => ['class' => 'm-5']],
                    //['label' => 'Полученные заявки', 'url' => ['nutrition-applications/receiving'], 'options' => ['class' => 'm-5']],
                    ['label' => 'Отправить меню в организацию', 'url' => ['nutrition-applications/send-menu'], 'options' => ['class' => 'm-5']],

                ]
            ],
            ['label' => 'Работа с организаторами питания', 'url' => ['#'], 'options' => ['class' => 'mr-3 p-2'],
                'items' => [
                    //['label' => 'Список образовательных организаций', 'url' => ['nutrition-applications/organizations'], 'options' => ['class' => 'm-5']],
                    ['label' => 'Заявки на сотрудничество', 'url' => ['nutrition-applications/request-food'], 'options' => ['class' => 'm-5']],
                    //['label' => 'Полученные заявки', 'url' => ['nutrition-applications/receiving'], 'options' => ['class' => 'm-5']],
                    ['label' => 'Отправить меню в организацию', 'url' => ['nutrition-applications/send-menu-food-director'], 'options' => ['class' => 'm-5']],

                ]
            ],
            ['label' => 'Пользователи', 'url' => ['users/level'], 'options' => ['class' => 'mr-3 p-2'],
                'items' => [

                ]
            ],
            ['label' => 'Видеоуроки', 'url' => ['#'], 'options' => ['class' => 'mr-3 p-2'],
                'items' => [
                    ['label' => 'Настройка, архив, разработка и редактирование меню', 'url' => 'https://www.youtube.com/watch?v=7AdfJTsJ6_k', 'options' => ['class' => 'm-5'], 'linkOptions' => ['target'=>'_blank']],
                    ['label' => 'Добавление иных технологических карт', 'url' => 'https://www.youtube.com/watch?v=ZsNVJoK6hVY', 'options' => ['class' => 'm-5'], 'linkOptions' => ['target'=>'_blank']],
                    ['label' => 'Адаптация меню для питания детей с сахарным диабетом и пищевой аллергией', 'url' => 'https://www.youtube.com/watch?v=PxfUL5bUQHs', 'options' => ['class' => 'm-5'], 'linkOptions' => ['target'=>'_blank']],
                ],
            ],
            ['label' => 'Справочная информация', 'url' => ['site/download-document-index'], 'options' => ['class' => 'mr-3 p-2']],
            ['label' => 'Обновления', 'url' => ['site/update-info'], 'options' => ['class' => 'mr-3 p-2']],
            ['label' => 'ЗАДАТЬ ВОПРОС(ЧАТ)', 'url' => ['chat/index'], 'options' => ['class' => 'mr-3 p-2'],
                'items' => [

                ]
            ],
        ];
        $logout = Html::begintag('div', ['class' => 'text-right'])
            .Html::tag('div', 'Пользователь:('.Yii::$app->user->identity->name.')', ['class' => ''])
            .Html::beginForm(['/site/logout'], 'post')
            .Html::submitButton(' Выход', ['class' => 'btn main-button-2-outline logout'])
            .Html::endForm()
            .Html::endTag('div');
    }

    if (Yii::$app->user->can('rospotrebnadzor_camp'))
    {
        $menuItems = [
            ['label' => 'Личные данные', 'url' => ['users/profile'], 'options' => ['class' => 'mr-3 p-2']],
            ['label' => 'Организация', 'url' => ['#'], 'options' => ['class' => 'mr-3 p-2'],
                'items' => [
                    ['label' => 'Общая информация', 'url' => ['organizations/create'], 'options' => ['class' => '']],
                    ['label' => 'Планируемая информация (смены/дети)', 'url' => ['plan-inf-camp/create'], 'options' => ['class' => '']],
                    ['label' => 'Фактическая информация (смены/дети)', 'url' => ['fact-inf-camp/create'], 'options' => ['class' => '']],
                    ['label' => 'Акарицидные обработки (планируемые)', 'url' => ['acaricidal-plan/create'], 'options' => ['class' => '']],
                    ['label' => 'Акарицидные обработки (фактическая)', 'url' => ['acaricidal-fact/create'], 'options' => ['class' => '']],
                ]
            ],
            ['label' => 'Организация питания', 'url' => ['#'], 'options' => ['class' => 'mr-3 p-2'],
                'items' => [
                    ['label' => 'Просмотр действующего меню по дням', 'url' => ['menus-dishes/menus-days'], 'options' => ['class' => '']],
                    ['label' => 'Просмотр действующего меню за период', 'url' => ['menus-dishes/menus-period'], 'options' => ['class' => '']],
                    ['label' => 'Отчет о повторяемости', 'url' => ['menus-dishes/repeat-report'], 'options' => ['class' => '']],
                    ['label' => 'Отчет о перечне продуктов', 'url' => ['menus-dishes/products-list'], 'options' => ['class' => '']],
                    ['label' => 'Прогнозная накопительная ведомость', 'url' => ['menus-dishes/prognos-storage'], 'options' => ['class' => '']],
                    //['label' => 'Технологические карты с учетом количества питающихся (пересчет)', 'url' => ['menus-dishes/techmup-page'], 'options' => ['class' => '']],
                    ['label' => 'Фактическое меню по дате', 'url' => ['menus-dishes/fact-date'], 'options' => ['class' => '']],
                    ['label' => 'Меню раскладка за день', 'url' => ['menus-dishes/raskladka'], 'options' => ['class' => '']],
                    ['label' => 'Проверка меню на аллергены', 'url' => ['/menus-dishes/show-allergen'], 'options' => ['class' => '']],
                    ['label' => 'Сборники рецептур', 'url' => ['/settings/recipes-index'], 'options' => ['class' => '']],
                    //['label' => 'Добавить блюдо в сборник', 'url' => ['/dishes'], 'options' => ['class' => '']],
                    ['label' => 'База данных блюд', 'url' => ['dishes/dishes-base'], 'options' => ['class' => '']],
                    ['label' => 'База данных продуктов', 'url' => ['products/products-base'], 'options' => ['class' => '']],
                    ['label' => 'Замена продуктов в меню', 'url' => ['/products-change-organization/index'], 'options' => ['class' => '']],
                    ['label' => 'Отчет по витаминам и минеральным веществам', 'url' => ['/menus-dishes/report-vitamin'], 'options' => ['class' => '']],
                ]],
            ['label' => 'Медицинская информация', 'url' => ['#'], 'options' => ['class' => 'mr-3 p-2'],
                'items' => [
                    //['label' => 'Добавление ребенка в отряд', 'url' => ['kids/kids-med-create'], 'options' => ['class' => '']],
                    //['label' => 'Добавление медицинской информации по детям', 'url' => ['kids/list-kids-camp'], 'options' => ['class' => '']],
                    ['label' => 'Список детей по отрядам', 'url' => ['kids/choice-list-kids-camp'], 'options' => ['class' => '']],
                    //['label' => 'Термометрия детей', 'url' => ['kids/choice-list-kids-ter-camp'], 'options' => ['class' => '']],
                    //['label' => 'Журнал регистрации амбулаторных больных (Форма №074/у)', 'url' => ['kids/ambulatory-cart-camp-index'], 'options' => ['class' => '']],
                    //['label' => 'Журнал изолятора (Форма №059/у)', 'url' => ['kids/isolator-cart-camp'], 'options' => ['class' => '']],
                ]
            ],
            ['label' => 'Отчеты', 'url' => ['#'], 'options' => ['class' => 'mr-3 p-2'],
                'items' => [
                    ['label' => 'Список детей по отрядам', 'url' => ['kids/choice-list-kids-camp'], 'options' => ['class' => '']],
                    //['label' => 'Индивидуальный отчет', 'url' => ['kids/individual-report-kids-camp'], 'options' => ['class' => '']],
                    ['label' => 'Дети/Заезды', 'url' => ['fact-inf-camp/report'], 'options' => ['class' => '']],
                    ['label' => 'Отчет об акарицидных обработках', 'url' => ['acaricidal-fact/report'], 'options' => ['class' => '']],
                    ['label' => 'Отчет в Роспотребнадзор', 'url' => ['medicals/report-rospotrebnadzor'], 'options' => ['class' => '']],
                    ['label' => 'Термометрия детей', 'url' => ['kids/choice-list-kids-ter-camp'], 'options' => ['class' => '']],
                ]
            ],
            ['label' => 'Роспотребнадзор Ввод', 'url' => ['#'], 'options' => ['class' => 'mr-3 p-2'],
                'items' => [
                    ['label' => 'Контрольно-надзорные мероприятия', 'url' => ['/control-activities/create'], 'options' => ['class' => '']],
                    ['label' => 'Оценка недополученного оздоровительного эффекта', 'url' => ['/rpn-tests/create'], 'options' => ['class' => '']],
                ]
            ],
            ['label' => 'Роспотребнадзор Отчеты', 'url' => ['#'], 'options' => ['class' => 'mr-3 p-2'],
                'items' => [
                    ['label' => 'Оценка соблюдения санитарного законодательства', 'url' => ['/rpn-tests/report-noe-one'], 'options' => ['class' => '']],
                    ['label' => 'Отчет КНМ по протоколам (по лагерям)', 'url' => ['/control-activities/report-protocol'], 'options' => ['class' => '']],
                    ['label' => 'Отчет КНМ по лаборатории (по лагерям)', 'url' => ['/control-activities/report-lab'], 'options' => ['class' => '']],
                    ['label' => 'Отчет КНМ среднее по количеству проб', 'url' => ['/control-activities/report-average-prob'], 'options' => ['class' => '']],
                    ['label' => 'Отчет КНМ сумма по количеству проб', 'url' => ['/control-activities/report-sum-prob'], 'options' => ['class' => '']],
                    ['label' => 'Отчет по оценке недополученного оздоровитльного эффекта (по всем лагерям)', 'url' => ['/rpn-tests/report-noe'], 'options' => ['class' => '']],
                    ['label' => 'Отчет по эффективности оздоровительного эффекта (по всем лагерям)', 'url' => ['/medicals/report-collective-rpn'], 'options' => ['class' => '']],
                ]
            ],
            ['label' => 'Видеоуроки для лагерей', 'url' => ['#'], 'options' => ['class' => 'mr-3 p-2'],
                'items' => [
                    ['label' => '№1 Регистрация, внесение общей информации, письмо в службу поддеркжи', 'url' => 'https://www.youtube.com/watch?v=ta528neUmt4', 'options' => ['class' => ''], 'linkOptions' => ['target' => '_blank']],
                    ['label' => '№2 Внесение планируемой и фактической информации, медицинской информации', 'url' => 'https://www.youtube.com/watch?v=cy6VIXOad_w', 'options' => ['class' => ''], 'linkOptions' => ['target' => '_blank']],
                    ['label' => '№3 Журнал оценки эффективности оздоровления детей. Построение отчетов.', 'url' => 'https://www.youtube.com/watch?v=NWzAf5oc8n4', 'options' => ['class' => ''], 'linkOptions' => ['target' => '_blank']],
                    ['label' => '№4 Внесение ежедневной информации. Термометрия детей.', 'url' => 'https://www.youtube.com/watch?v=yZUv06QE_xE', 'options' => ['class' => ''], 'linkOptions' => ['target' => '_blank']],
                    //['label' => 'Добавление медицинской информации по детям', 'url' => ['kids/list-kids-camp'], 'options' => ['class' =>'']],
                ]
            ],
            ['label' => 'Видеоуроки для РПН', 'url' => ['#'], 'options' => ['class' => 'mr-3 p-2 dropdown-menu-right-2'],
                'items' => [
                    ['label' => '№1 Регистрация сотрудников. Просмотр информации по лагерям.', 'url' => 'https://www.youtube.com/watch?v=a4Z1ciHzgp8', 'options' => ['class' => ''], 'linkOptions' => ['target' => '_blank']],
                    ['label' => '№2 Внесение результатов контрольно-надзорных мероприятий, построение отчетов.', 'url' => 'https://www.youtube.com/watch?v=Hwn1BPMGu9o', 'options' => ['class' => ''], 'linkOptions' => ['target' => '_blank']],
                ]
            ],
            ['label' => 'Справочная информация', 'url' => ['site/download-document-index'], 'options' => ['class' => 'mr-3 p-2']],
            ['label' => 'ЗАДАТЬ ВОПРОС(ЧАТ)', 'url' => ['chat/index'], 'options' => ['class' => 'mr-3 p-2'],
                'items' => [

                ]
            ],
        ];
    }
    if (Yii::$app->user->can('subject_minobr'))
    {
        $menuItems = [
            ['label' => 'Главная', 'url' => ['/'], 'options' => ['class' => 'mr-3 p-2']],
            ['label' => 'Личные данные', 'url' => ['users/profile'], 'options' => ['class' => 'mr-3 p-2']],
            ['label' => 'Организация', 'url' => ['#'], 'options' => ['class' => 'mr-3 p-2'],
                'items' => [
                    ['label' => 'Общая информация о выбранной организации', 'url' => ['organizations/create'], 'options' => ['class' => '']],
                    //['label' => 'Информация о количестве обучающихся', 'url' => ['information-education/index'], 'options' => ['class' => '']],
                ]
            ],
            ['label' => 'Отчеты по организации питания', 'url' => ['#'], 'options' => ['class' => 'mr-3 p-2'],
                'items' => [
                    ['label' => 'Просмотр действующего меню по дням', 'url' => ['menus-dishes/menus-days'], 'options' => ['class' => '']],
                    ['label' => 'Просмотр действующего меню за период', 'url' => ['menus-dishes/menus-period'], 'options' => ['class' => '']],
                    ['label' => 'Отчет о повторяемости', 'url' => ['menus-dishes/repeat-report'], 'options' => ['class' => '']],
                    ['label' => 'Отчет о перечне продуктов', 'url' => ['menus-dishes/products-list'], 'options' => ['class' => '']],
                    ['label' => 'Отчет о перечне продуктов за указанный период', 'url' => ['menus-dishes/fact-date-products-list'], 'options' => ['class' => '']],
                    ['label' => 'Прогнозная накопительная ведомость', 'url' => ['menus-dishes/prognos-storage'], 'options' => ['class' => '']],
                    //['label' => 'Технологические карты с учетом количества питающихся (пересчет)', 'url' => ['menus-dishes/techmup-page'], 'options' => ['class' => '']],
                    ['label' => 'Фактическое меню по дате', 'url' => ['menus-dishes/fact-date'], 'options' => ['class' => '']],
                    ['label' => 'Меню раскладка за день', 'url' => ['menus-dishes/raskladka'], 'options' => ['class' => '']],
                    ['label' => 'Проверка меню на аллергены', 'url' => ['/menus-dishes/show-allergen'], 'options' => ['class' => '']],
                    ['label' => 'Проверка меню на хлебные единицы', 'url' => ['/menus-dishes/show-sahar'], 'options' => ['class' => '']],
                    ['label' => 'Формирование документов меню', 'url' => ['/menus-dishes/report-document'], 'options' => ['class' => '']],
                ]
            ],
            ['label' => 'Отчеты', 'url' => ['#'], 'options' => ['class' => 'mr-3 p-2'],
                'items' => [
                    //['label' => 'Общий отчет', 'url' => ['menus/report-minobr-rpn'], 'options' => ['class' => '']],
                    ['label' => 'Отчет о внесенной информации', 'url' => ['menus/report-minobr-rpn-vnesen'], 'options' => ['class' => '']],
                    ['label' => 'Характеристика детей и имеющихся меню', 'url' => ['menus-dishes/report-school-little2'], 'options' => ['class' => '']],
                    ['label' => 'Структура классов в образовательном учреждении', 'url' => ['characters-study/report'], 'options' => ['class' => '']],
                    ['label' => 'Отчет по питанию детей по переменам', 'url' => ['/school-break/report-peremena'], 'options' => ['class' => '']],
                    ['label' => 'Отчет по операторам питания', 'url' => ['menus-dishes/report-orgpit'], 'options' => ['class' => '']],
                    ['label' => 'Результаты проведения мероприятий родительского контроля(краткий)', 'url' => ['menus-dishes/report-school-little3'], 'options' => ['class' => '']],
                    ['label' => 'Гигиеническая оценка меню', 'url' => ['/menus-dishes/expertiza'], 'options' => ['class' => '']],
                    ['label' => 'Полный отчет по родительскому и внутреннему контролю', 'url' => ['anket-parent-control/report'], 'options' => ['class' => '']],
                    ['label' => 'МОНИТОРИНГ', 'url' => ['/menus-dishes/report-school'], 'options' => ['class' => '']],
                ]
            ],
            //['label' => 'Мониторинг питания', 'url' => ['menus/monitoring'], 'options' => ['class' => 'mr-3 p-2']],
            /*['label' => 'Ввод данных', 'url' => ['#'], 'options' => ['class' => 'mr-3 p-2'],
                'items'=>[
                    ['label' => 'Информация о расходах на питание', 'url' => ['expenses-food/index'], 'options' => ['class' => '']],

                ]
            ],*/
            /*['label' => 'Контроль ежедневных данных', 'url' => ['#'], 'options' => ['class' => 'mr-3 p-2'],
                'items' => [
                    //['label' => 'Отчет о ежедневной информации по классам', 'url' => ['/everyday-classes/class-report'], 'options' => ['class' => '']],
                    //['label' => 'Отчет о ежедневной информации по переменам', 'url' => ['/everyday-classes/peremena-report'], 'options' => ['class' => '']],
                    ['label' => 'Статистика заполняемости ежедневной информации по муниципальному району', 'url' => ['/everyday-classes/report-school-count'], 'options' => ['class' => '']],
                    ['label' => 'Статистика по заполняемости ежедневной информации', 'url' => ['/everyday-classes/statistic-enter'], 'options' => ['class' => '']],
                    ['label' => 'Статистика посещаемости по школе', 'url' => ['/everyday-classes/report-school-visit'], 'options' => ['class' => '']],
                    ['label' => 'Отчет о ежедневной информации по классам', 'url' => ['/everyday-classes/class-report'], 'options' => ['class' => '']],

                ]
            ],*/
            ['label' => 'Видеоуроки', 'url' => ['#'], 'options' => ['class' => 'mr-3 p-2'],
                'items' => [
                    ['label' => 'Настройка, архив, разработка и редактирование меню', 'url' => 'https://www.youtube.com/watch?v=7AdfJTsJ6_k', 'options' => ['class' => 'm-5'], 'linkOptions' => ['target'=>'_blank']],
                    ['label' => 'Добавление иных технологических карт', 'url' => 'https://www.youtube.com/watch?v=ZsNVJoK6hVY', 'options' => ['class' => 'm-5'], 'linkOptions' => ['target'=>'_blank']],
                    ['label' => 'Адаптация меню для питания детей с сахарным диабетом и пищевой аллергией', 'url' => 'https://www.youtube.com/watch?v=PxfUL5bUQHs', 'options' => ['class' => 'm-5'], 'linkOptions' => ['target'=>'_blank']],
                ],
            ],
            ['label' => 'Справочная информация', 'url' => ['site/download-document-index'], 'options' => ['class' => 'mr-3 p-2']],
            ['label' => 'ЗАДАТЬ ВОПРОС(ЧАТ)', 'url' => ['chat/index'], 'options' => ['class' => 'mr-3 p-2'],
                'items' => [

                ]
            ],


            ['label' => 'Контроль меню', 'url' => ['#'], 'options' => ['class' => 'mr-3 p-2'],
                'items' => [
                    ['label' => 'Архив меню', 'url' => ['menus/archive'], 'options' => ['class' => 'm-5']],
                    ['label' => 'Настройки меню', 'url' => ['menus/index'], 'options' => ['class' => 'm-5']],
                    ['label' => 'Разработка цикличного/однодневного меню', 'url' => ['menus-dishes/index'], 'options' => ['class' => '']],
//                    ['label' => 'Просмотр действующего меню по дням', 'url' => ['menus-dishes/menus-days'], 'options' => ['class' => '']],
//                    ['label' => 'Просмотр действующего меню за период', 'url' => ['menus-dishes/menus-period'], 'options' => ['class' => '']],
//                    ['label' => 'Корректировка фактического меню по дате', 'url' => ['menus-dishes/fact-day-index'], 'options' => ['class' => '']],
//                    ['label' => 'Фактическое меню за день', 'url' => ['menus-dishes/fact-date'], 'options' => ['class' => '']],
//                    ['label' => 'Отчет о повторяемости', 'url' => ['menus-dishes/repeat-report'], 'options' => ['class' => '']],
//                    ['label' => 'Отчет о перечне продуктов', 'url' => ['menus-dishes/products-list'], 'options' => ['class' => '']],
//                    ['label' => 'Отчет о перечне продуктов за указанный период', 'url' => ['menus-dishes/fact-date-products-list'], 'options' => ['class' => '']],
//                    ['label' => 'Прогнозная накопительная ведомость', 'url' => ['menus-dishes/prognos-storage'], 'options' => ['class' => '']],
//                    ['label' => 'Меню раскладка за день', 'url' => ['menus-dishes/raskladka'], 'options' => ['class' => '']],
//                    ['label' => 'Меню раскладка за день по приемам пищи', 'url' => ['menus-dishes/raskladka-nutrition'], 'options' => ['class' => '']],
//                    ['label' => 'Проверка меню на аллергены', 'url' => ['/menus-dishes/show-allergen'], 'options' => ['class' => '']],
//                    ['label' => 'Проверка меню на хлебные единицы', 'url' => ['/menus-dishes/show-sahar'], 'options' => ['class' => '']],
                    ['label' => 'Технологические карты с учетом количества питающихся (пересчет)', 'url' => ['menus-dishes/techmup-page'], 'options' => ['class' => '']],
                    ['label' => 'Сборники рецептур', 'url' => ['/settings/recipes-index'], 'options' => ['class' => '']],
                    ['label' => 'Добавить блюдо в сборник', 'url' => ['/dishes'], 'options' => ['class' => '']],
                    ['label' => 'База данных блюд', 'url' => ['dishes/dishes-base'], 'options' => ['class' => '']],
                    ['label' => 'База данных продуктов', 'url' => ['products/products-base'], 'options' => ['class' => '']],
                    ['label' => 'Формирование документов(печать меню)', 'url' => ['/menus-dishes/report-document'], 'options' => ['class' => '']],
                    ['label' => 'Замена продуктов в меню', 'url' => ['/products-change-organization/index'], 'options' => ['class' => '']],
                    ['label' => 'Отчет по витаминам и минеральным веществам', 'url' => ['/menus-dishes/report-vitamin'], 'options' => ['class' => '']],
                ]
            ],
        ];
    }


    if (Yii::$app->user->can('rospotrebnadzor_nutrition') || Yii::$app->user->can('minobr'))
    {
        $menuItems = [
            ['label' => 'Главная', 'url' => ['/'], 'options' => ['class' => 'mr-3 p-2']],
            ['label' => 'Личные данные', 'url' => ['users/profile'], 'options' => ['class' => 'mr-3 p-2']],
            ['label' => 'Организация', 'url' => ['#'], 'options' => ['class' => 'mr-3 p-2'],
                'items' => [
                    ['label' => 'Общая информация о выбранной организации', 'url' => ['organizations/create'], 'options' => ['class' => '']],
                    //['label' => 'Информация о количестве обучающихся', 'url' => ['information-education/index'], 'options' => ['class' => '']],
                ]
            ],
            ['label' => 'Отчеты по организации питания', 'url' => ['#'], 'options' => ['class' => 'mr-3 p-2'],
                'items' => [
                    ['label' => 'Просмотр действующего меню по дням', 'url' => ['menus-dishes/menus-days'], 'options' => ['class' => '']],
                    ['label' => 'Просмотр действующего меню за период', 'url' => ['menus-dishes/menus-period'], 'options' => ['class' => '']],
                    ['label' => 'Отчет о повторяемости', 'url' => ['menus-dishes/repeat-report'], 'options' => ['class' => '']],
                    ['label' => 'Отчет о перечне продуктов', 'url' => ['menus-dishes/products-list'], 'options' => ['class' => '']],
                    ['label' => 'Отчет о перечне продуктов за указанный период', 'url' => ['menus-dishes/fact-date-products-list'], 'options' => ['class' => '']],
                    ['label' => 'Прогнозная накопительная ведомость', 'url' => ['menus-dishes/prognos-storage'], 'options' => ['class' => '']],
                    //['label' => 'Технологические карты с учетом количества питающихся (пересчет)', 'url' => ['menus-dishes/techmup-page'], 'options' => ['class' => '']],
                    ['label' => 'Фактическое меню по дате', 'url' => ['menus-dishes/fact-date'], 'options' => ['class' => '']],
                    ['label' => 'Меню раскладка за день', 'url' => ['menus-dishes/raskladka'], 'options' => ['class' => '']],
                    ['label' => 'Проверка меню на аллергены', 'url' => ['/menus-dishes/show-allergen'], 'options' => ['class' => '']],
                    //['label' => 'Сборники рецептур', 'url' => ['/settings/recipes-index'], 'options' => ['class' => '']],
                    //['label' => 'Добавить блюдо в сборник', 'url' => ['/dishes'], 'options' => ['class' => '']],
                    //['label' => 'База данных блюд', 'url' => ['dishes/dishes-base'], 'options' => ['class' => '']],
                    //['label' => 'База данных продуктов', 'url' => ['products/products-base'], 'options' => ['class' => '']],
                    //['label' => 'Отчет по витаминам и микроэлементам', 'url' => ['/menus-dishes/report-vitamin'], 'options' => ['class' => '']],
                    ['label' => 'Формирование документов меню', 'url' => ['/menus-dishes/report-document'], 'options' => ['class' => '']],
                ]
            ],
            ['label' => 'Отчеты', 'url' => ['#'], 'options' => ['class' => 'mr-3 p-2'],
                'items' => [
                    //['label' => 'Общий отчет', 'url' => ['menus/report-minobr-rpn'], 'options' => ['class' => '']],
                    ['label' => 'Отчет о внесенной информации', 'url' => ['menus/report-minobr-rpn-vnesen'], 'options' => ['class' => '']],
                    //['label' => 'Характеристика детей и имеющихся меню', 'url' => ['menus-dishes/report-school-little2'], 'options' => ['class' => '']],
                    ['label' => 'Характеристика детей и имеющихся меню', 'url' => ['menus-dishes/report-school-little2'], 'options' => ['class' => '']],

                    ['label' => 'Структура классов в образовательном учреждении', 'url' => ['characters-study/report'], 'options' => ['class' => '']],
                    ['label' => 'Отчет по питанию детей по переменам', 'url' => ['/school-break/report-peremena'], 'options' => ['class' => '']],
                    ['label' => 'Результаты проведения мероприятий родительского контроля краткий', 'url' => ['menus-dishes/report-school-little3'], 'options' => ['class' => '']],
                    ['label' => 'Результаты проведения мероприятий контроля по переменам', 'url' => ['menus-dishes/report-school-little3-long'], 'options' => ['class' => '']],
                    ['label' => 'Отчет по операторам питания', 'url' => ['menus-dishes/report-orgpit'], 'options' => ['class' => '']],
                    ['label' => 'Гигиеническая оценка меню', 'url' => ['/menus-dishes/expertiza'], 'options' => ['class' => '']],
                    ['label' => 'Полный отчет по родительскому и внутреннему контролю', 'url' => ['anket-parent-control/report'], 'options' => ['class' => '']],
                    ['label' => 'МОНИТОРИНГ', 'url' => ['/menus-dishes/report-school'], 'options' => ['class' => '']],
                ]
            ],
            //['label' => 'Мониторинг питания', 'url' => ['menus/monitoring'], 'options' => ['class' => 'mr-3 p-2']],
            /*['label' => 'Контроль ежедневных данных', 'url' => ['#'], 'options' => ['class' => 'mr-3 p-2'],
                'items' => [
                    //['label' => 'Отчет о ежедневной информации по классам', 'url' => ['/everyday-classes/class-report'], 'options' => ['class' => '']],
                    //['label' => 'Отчет о ежедневной информации по переменам', 'url' => ['/everyday-classes/peremena-report'], 'options' => ['class' => '']],
                    ['label' => 'Статистика заполняемости ежедневной информации по муниципальному району', 'url' => ['/everyday-classes/report-school-count'], 'options' => ['class' => '']],
                    ['label' => 'Статистика по заполняемости ежедневной информации', 'url' => ['/everyday-classes/statistic-enter'], 'options' => ['class' => '']],
                    ['label' => 'Статистика посещаемости по школе', 'url' => ['/everyday-classes/report-school-visit'], 'options' => ['class' => '']],
                    ['label' => 'Отчет о ежедневной информации по классам', 'url' => ['/everyday-classes/class-report'], 'options' => ['class' => '']],
                ]
            ],*/
            ['label' => 'Справочная информация', 'url' => ['site/download-document-index'], 'options' => ['class' => 'mr-3 p-2']],
            ['label' => 'ЗАДАТЬ ВОПРОС(ЧАТ)', 'url' => ['chat/index'], 'options' => ['class' => 'mr-3 p-2'],
                'items' => [

                ]
            ],

            ['label' => 'Контроль меню', 'url' => ['#'], 'options' => ['class' => 'mr-3 p-2'],
                'items' => [
                    ['label' => 'Архив меню', 'url' => ['menus/archive'], 'options' => ['class' => 'm-5']],
                    ['label' => 'Настройки меню', 'url' => ['menus/index'], 'options' => ['class' => 'm-5']],
                    ['label' => 'Разработка цикличного/однодневного меню', 'url' => ['menus-dishes/index'], 'options' => ['class' => '']],
//                    ['label' => 'Просмотр действующего меню по дням', 'url' => ['menus-dishes/menus-days'], 'options' => ['class' => '']],
//                    ['label' => 'Просмотр действующего меню за период', 'url' => ['menus-dishes/menus-period'], 'options' => ['class' => '']],
//                    ['label' => 'Корректировка фактического меню по дате', 'url' => ['menus-dishes/fact-day-index'], 'options' => ['class' => '']],
//                    ['label' => 'Фактическое меню за день', 'url' => ['menus-dishes/fact-date'], 'options' => ['class' => '']],
//                    ['label' => 'Отчет о повторяемости', 'url' => ['menus-dishes/repeat-report'], 'options' => ['class' => '']],
//                    ['label' => 'Отчет о перечне продуктов', 'url' => ['menus-dishes/products-list'], 'options' => ['class' => '']],
//                    ['label' => 'Отчет о перечне продуктов за указанный период', 'url' => ['menus-dishes/fact-date-products-list'], 'options' => ['class' => '']],
//                    ['label' => 'Прогнозная накопительная ведомость', 'url' => ['menus-dishes/prognos-storage'], 'options' => ['class' => '']],
//                    ['label' => 'Меню раскладка за день', 'url' => ['menus-dishes/raskladka'], 'options' => ['class' => '']],
//                    ['label' => 'Меню раскладка за день по приемам пищи', 'url' => ['menus-dishes/raskladka-nutrition'], 'options' => ['class' => '']],
                    ['label' => 'Проверка меню на аллергены', 'url' => ['/menus-dishes/show-allergen'], 'options' => ['class' => '']],
                    ['label' => 'Проверка меню на хлебные единицы', 'url' => ['/menus-dishes/show-sahar'], 'options' => ['class' => '']],
                    ['label' => 'Технологические карты с учетом количества питающихся (пересчет)', 'url' => ['menus-dishes/techmup-page'], 'options' => ['class' => '']],
                    ['label' => 'Сборники рецептур', 'url' => ['/settings/recipes-index'], 'options' => ['class' => '']],
                    ['label' => 'Добавить блюдо в сборник', 'url' => ['/dishes'], 'options' => ['class' => '']],
                    ['label' => 'База данных блюд', 'url' => ['dishes/dishes-base'], 'options' => ['class' => '']],
                    ['label' => 'База данных продуктов', 'url' => ['products/products-base'], 'options' => ['class' => '']],
                    ['label' => 'Формирование документов(печать меню)', 'url' => ['/menus-dishes/report-document'], 'options' => ['class' => '']],
                    ['label' => 'Замена продуктов в меню', 'url' => ['/products-change-organization/index'], 'options' => ['class' => '']],
                    ['label' => 'Отчет по витаминам и минеральным веществам', 'url' => ['/menus-dishes/report-vitamin'], 'options' => ['class' => '']],
                ]
            ],
        ];
    }

    //повсем
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-left main-color '],
        'items' => $menuItems,
    ]);
    NavBar::end();
    ?>
    <? if (Yii::$app->user->can('rospotrebnadzor_camp'))
    {
        $organization_id = Yii::$app->user->identity->organization_id;
        $region_id = Organization::findOne($organization_id)->region_id;
        $my_org = Organization::findOne($organization_id);
        $municipalities = \common\models\Municipality::find()->where(['region_id' => $region_id])->all();

        $municipality_null = array(0 => 'Все муниципальные округа ...');
        $municipality_items = ArrayHelper::map($municipalities, 'id', 'name');
        $municipality_items = ArrayHelper::merge($municipality_null, $municipality_items);


        $session = Yii::$app->session;
        $organization_id = Yii::$app->user->identity->organization_id;

        $organization = Organization::find()->where(['type_org' => 1, 'region_id' => $region_id])->orderBy(['title' => SORT_ASC])->all();
        $organization_items = ArrayHelper::map($organization, 'id', 'title');

        $model = new \common\models\Menus();
        $form = ActiveForm::begin([
            'action' => ['site/select-organization'],
        ]);
        echo Html::begintag('div', ['class' => 'row']);


        echo Html::begintag('div', ['class' => 'col-2']);
        $choose_municipality_item = $form->field($model, 'parent_id')->dropDownList($municipality_items, [
            'class' => 'form-control mt-3',
            'onchange' => '
                  $.get("../menus/orglist?id="+$(this).val(), function(data){
                    $("select#menus-organization_id").html(data);
                  });'
        ])->label(false);
        echo $choose_municipality_item;
        echo Html::endtag('div');


        echo Html::begintag('div', ['class' => 'col-2']);
        $choose_organization_item = $form->field($model, 'organization_id')->dropDownList($organization_items, [
            'class' => 'form-control mt-3', 'options' => [$session['organization_id'] => ['Selected' => true]]])->label(false);
        echo $choose_organization_item;
        echo Html::endtag('div');


        echo Html::begintag('div', ['class' => 'col-2 mt-3']);
        echo Html::submitButton('Выбрать организацию', ['class' => 'btn main-button-2-outline']);
        echo Html::endtag('div');

        echo Html::begintag('div', ['class' => 'col-2 mt-4 text-success']);
        echo Html::tag('div', \common\models\Region::findOne(Organization::findOne(Yii::$app->user->identity->organization_id)->region_id)->name, ['class' => 'text-right']);
        echo Html::endtag('div');

        echo Html::begintag('div', ['class' => 'col-3']);
        echo Html::tag('div', 'Пользователь: ' . Organization::findOne(Yii::$app->user->identity->organization_id)->title, ['class' => 'text-right mt-4']);
        echo Html::endtag('div');

        echo Html::begintag('div', ['class' => 'col-1 mt-3']);
        echo Html::beginForm(['/site/logout'], 'post');

        echo Html::a(' Выход', ['/site/logout'],
            [
                'class' => 'btn main-button-2-outline',
            ]);

        echo Html::endForm();
        echo Html::endtag('div');
        echo Html::endtag('div');

        echo Html::begintag('div', ['class' => 'row']);
        echo Html::begintag('div', ['class' => 'col-4']);
        if (!empty($session['organization_id']))
        {
            echo '<p class="text-success ml-3">Выбрана организация: ' . Organization::findOne($session['organization_id'])->title . '</p>';
        }
        else
        {
            echo '<p class="text-danger ml-3"><b>Организация не выбрана</b></p>';
        }
        echo Html::endtag('div');

        echo Html::begintag('div', ['class' => 'col-4']);
        if (!empty($session['organization_id']))
        {
            echo Html::a(' Выйти из выбранной организации', ['/site/session-delete'],
                [
                    'class' => 'btn btn-danger',
                ]);
        }

        echo Html::endtag('div');

        echo Html::endtag('div');
        ActiveForm::end();
        /*if ($session->has('organization_id')){
            print_r($session['organization_id']) ;
        }*/
        //сессия организация
        //echo $session['organization_id'];
    }
    ?>
    <? if (Yii::$app->user->can('subject_minobr'))
    {
        $session = Yii::$app->session;
        $organization_id = Yii::$app->user->identity->organization_id;
        $municipality_id = Organization::findOne($organization_id)->municipality_id;

        $my_organization = \common\models\Organization::findOne(Yii::$app->user->identity->organization_id);
        $my_municipality = \common\models\Municipality::findOne($my_organization->municipality_id);

        //echo $organization_id;
        //сессия организация выборка
        $region_id = Organization::findOne($organization_id)->region_id;
        $organization = Organization::find()->where(['type_org' => 3, 'municipality_id' => $municipality_id])->andWhere(['!=', 'id', 7])->all();
        $organization_items = ArrayHelper::map($organization, 'id', 'title');
        $model = new SelectOrgForm();
        $form = ActiveForm::begin([
            'action' => ['site/select-organization'],
        ]);
        echo Html::begintag('div', ['class' => 'row']);

        if($my_municipality->city_status == 1){
            $cities_null = array('' => 'Выберите городской район...');
            $cities = \common\models\City::find()->where(['municipality_id' => $my_municipality->id])->all();
            $cities_items = ArrayHelper::map($cities, 'id', 'name');
            $cities_items = ArrayHelper::merge($cities_null, $cities_items);



            echo Html::begintag('div', ['class' => 'col-2']);
            $choose_municipality_item = $form->field($model, 'city_id')->dropDownList($cities_items, [
                'class' => 'form-control mt-3',
                'onchange' => '
                  $.get("../menus/orgcity?id="+$(this).val(), function(data){
                    $("select#selectorgform-organization").html(data);
                  });'
            ])->label(false);
            echo $choose_municipality_item;
            echo Html::endtag('div');

            echo Html::begintag('div', ['class' => 'col-2']);
            $choose_organization_item = $form->field($model, 'organization')->dropDownList($organization_items, [
                'class' => 'form-control mt-3 ml-3', 'options' => [$session['organization_id'] => ['Selected' => true]]])->label(false);
            echo $choose_organization_item;
            echo Html::endtag('div');


        }else{
            echo Html::begintag('div', ['class' => 'col-4']);
            $choose_organization_item = $form->field($model, 'organization')->dropDownList($organization_items, [
                'class' => 'form-control mt-3 ml-3', 'options' => [$session['organization_id'] => ['Selected' => true]]])->label(false);
            echo $choose_organization_item;
            echo Html::endtag('div');
        }


        echo Html::begintag('div', ['class' => 'col-2 mt-3']);
        echo Html::submitButton('Выбрать организацию', ['class' => 'btn main-button-2-outline']);
        echo Html::endtag('div');


        echo Html::begintag('div', ['class' => 'col-2 mt-4 text-success']);
        echo Html::tag('div', \common\models\Region::findOne(Organization::findOne(Yii::$app->user->identity->organization_id)->region_id)->name, ['class' => 'text-right']);
        echo Html::endtag('div');


        echo Html::begintag('div', ['class' => 'col-3']);
        echo Html::tag('div', 'Пользователь: Муниципальный орган управления образования'/*. Yii::$app->user->identity->name*/, ['class' => 'text-right mt-4']);
        echo Html::endtag('div');

        echo Html::begintag('div', ['class' => 'col-1 mt-3']);
        echo Html::beginForm(['/site/logout'], 'post');

        echo Html::a(' Выход', ['/site/logout'],
            [
                'class' => 'btn main-button-2-outline',
            ]);

        echo Html::endForm();
        echo Html::endtag('div');
        echo Html::endtag('div');


        echo Html::begintag('div', ['class' => 'row']);
        echo Html::begintag('div', ['class' => 'col-4']);
        if (!empty($session['organization_id']))
        {
            echo '<p class="text-success ml-3"><b>Выбрана организация: ' . Organization::findOne($session['organization_id'])->title . '</b></p>';
        }
        else
        {
            echo '<p class="text-danger ml-3"><b>Организация не выбрана</b></p>';
        }
        echo Html::endtag('div');
        echo Html::endtag('div');


        ActiveForm::end();

        /*if ($session->has('organization_id')){
            print_r($session['organization_id']) ;
        }*/
        //сессия организация
        //echo $session['organization_id'];
    }
    ?>

    <? if (Yii::$app->user->can('rospotrebnadzor_nutrition') || Yii::$app->user->can('minobr'))
    {
        $organization_id = Yii::$app->user->identity->organization_id;
        $region_id = Organization::findOne($organization_id)->region_id;
        $my_org = Organization::findOne($organization_id);
        $municipalities = \common\models\Municipality::find()->where(['region_id' => $region_id])->all();

        $municipality_null = array(0 => 'Все муниципальные округа ...');
        $municipality_items = ArrayHelper::map($municipalities, 'id', 'name');
        $municipality_items = ArrayHelper::merge($municipality_null, $municipality_items);


        $session = Yii::$app->session;
        $organization_id = Yii::$app->user->identity->organization_id;

        $organization = Organization::find()->where(['type_org' => 3, 'region_id' => $region_id])->andWhere(['!=', 'id', 7])->all();
        $organization_items = ArrayHelper::map($organization, 'id', 'title');

        $model = new \common\models\Menus();
        $form = ActiveForm::begin([
            'action' => ['site/select-organization'],
        ]);
        echo Html::begintag('div', ['class' => 'row']);


        echo Html::begintag('div', ['class' => 'col-2']);
        $choose_municipality_item = $form->field($model, 'parent_id')->dropDownList($municipality_items, [
            'class' => 'form-control mt-3',
            'onchange' => '
                  $.get("../menus/orglist?id="+$(this).val(), function(data){
                    $("select#menus-organization_id").html(data);
                  });'
        ])->label(false);
        echo $choose_municipality_item;
        echo Html::endtag('div');


        echo Html::begintag('div', ['class' => 'col-2']);
        $choose_organization_item = $form->field($model, 'organization_id')->dropDownList($organization_items, [
            'class' => 'form-control mt-3', 'options' => [$session['organization_id'] => ['Selected' => true]]])->label(false);
        echo $choose_organization_item;
        echo Html::endtag('div');


        echo Html::begintag('div', ['class' => 'col-2 mt-3']);
        echo Html::submitButton('Выбрать организацию', ['class' => 'btn main-button-2-outline']);
        echo Html::endtag('div');

        echo Html::begintag('div', ['class' => 'col-2 mt-4 text-success']);
        echo Html::tag('div', \common\models\Region::findOne(Organization::findOne(Yii::$app->user->identity->organization_id)->region_id)->name, ['class' => 'text-right']);
        echo Html::endtag('div');

        echo Html::begintag('div', ['class' => 'col-3']);
        echo Html::tag('div', 'Пользователь: ' . Organization::findOne(Yii::$app->user->identity->organization_id)->title, ['class' => 'text-right mt-4']);
        echo Html::endtag('div');

        echo Html::begintag('div', ['class' => 'col-1 mt-3']);
        echo Html::beginForm(['/site/logout'], 'post');

        echo Html::a(' Выход', ['/site/logout'],
            [
                'class' => 'btn main-button-2-outline',
            ]);

        echo Html::endForm();
        echo Html::endtag('div');
        echo Html::endtag('div');

        echo Html::begintag('div', ['class' => 'row']);
        echo Html::begintag('div', ['class' => 'col-4']);
        if (!empty($session['organization_id']))
        {
            echo '<p class="text-success ml-3">Выбрана организация: ' . Organization::findOne($session['organization_id'])->title . '</p>';
        }
        else
        {
            echo '<p class="text-danger ml-3"><b>Организация не выбрана</b></p>';
        }
        echo Html::endtag('div');

        echo Html::begintag('div', ['class' => 'col-4']);
        if (!empty($session['organization_id']))
        {
            echo Html::a(' Выйти из выбранной организации', ['/site/session-delete'],
                [
                    'class' => 'btn btn-danger',
                ]);
        }

        echo Html::endtag('div');

        echo Html::endtag('div');
        ActiveForm::end();
        /*if ($session->has('organization_id')){
            print_r($session['organization_id']) ;
        }*/
        //сессия организация
        //echo $session['organization_id'];
    }
    ?>

    <div class="container-fluid mt-3">

        <?php if (Yii::$app->session->hasFlash('success')): ?>
            <div class="alert alert-success alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <?php echo Yii::$app->session->getFlash('success'); ?>
            </div>
        <?php endif; ?>
        <?php if (Yii::$app->session->hasFlash('error')): ?>
            <div class="alert alert-danger alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <?php echo Yii::$app->session->getFlash('error'); ?>
            </div>
        <?php endif; ?>



        <?= $logout ?>


        <? if (!Yii::$app->user->isGuest && !Yii::$app->user->can('camp_director') && !Yii::$app->user->can('rospotrebnadzor_nutrition'))
        { ?>
            <!--<p class="text-center" style="color: red">Внимание! 11 августа в 11:00 (07:00 по мск) состоится вебинар по работе в программном средстве
            "Питание и мониторинг здоровья". <br>Конференция будет проводиться через <a href="https://zoom.us/">zoom</a>. Идентификатор конференции: 599 534 2526 Пароль: Fbun54</p>-->

<!--            <p class="text-center" style="font-size: 18px;">Запись конференции <b>"О промежуточных итогах мониторинга питания и здоровья детей (Новосибирская область)"</b> можно посмотреть, перейдя по <a target="_blank" href="https://www.youtube.com/watch?v=8V39P8sGxsw">ссылке</a></p>-->
<!--            <p class="text-center" style="font-size: 18px;">Запись конференции <b>"О промежуточных итогах мониторинга питания и здоровья детей (Омская область)"</b> можно посмотреть, перейдя по <a target="_blank" href="https://www.youtube.com/watch?v=Is593czBrRo">ссылке</a></p>-->


        <? } ?>
<!--        <p class="text-center text-danger" style="font-size:17px;"><strong>10.03.22 проводятся технические работы в разделе "Родительский контроль". Раздел временно может быть недоступен.</strong></p>-->
        <? $count_mess = \common\models\Chat::find()->where(['receiver_user_id' => Yii::$app->user->id, 'status' => 0])->count();
        if ($count_mess > 0)
        {
            ?>
            <p class="text-center text-success"><strong>У вас +<?= $count_mess; ?> сообщение в чате <a target="_blank"
                                                                                                       href="http://demography.site/chat/index">перейти</strong></a>
            </p>
        <? } ?>

<!--        --><?//if(\common\models\Chat::find()->where(['sender_user_id' => Yii::$app->user->id, 'status' => 0])->count() > 0 && !Yii::$app->user->can('admin')){?>
<!--            <p class="text-center text-danger" style="font-size: 20px;"><b>Внимание! Если Вы задавали вопросы в чате, то тветы на них будут даны в понедельник (24.01)</b></p>-->
<!--        --><?//}?>
        


        <?= $content ?>
    </div>

</div>

<footer class="footer main-color">
    <!--<p class="text-light ml-3 font-weight-bold">Разработчик: <a href="http://niig.su" class="text-light font-weight-normal">ФБУН "Новосибирский НИИ гигиены" Роспотребнадзора</a></p>-->
    <p class="text-light ml-3 font-weight-bold">2022 год</p>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
