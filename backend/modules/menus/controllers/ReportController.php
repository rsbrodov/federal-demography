<?php

namespace backend\modules\menus\controllers;

use common\models\Days;
use common\models\MenuForm;
use common\models\Menus;
use common\models\MenusDays;
use common\models\MenusDishes;
use common\models\MenuSearch;
use common\models\MenusNutrition;
use common\models\MenusOrgRpnMinobr;
use common\models\MenusSend;
use common\models\MenusVariativity;
use common\models\NutritionInfo;
use common\models\DishesProducts;
use common\models\ProductsCategory;
use common\models\FactdateForm;
use common\models\Products;
use common\models\Organization;
use common\models\Variativity;
use Yii;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\web\Controller;
use yii\web\NotFoundHttpException;


class ReportController extends Controller
{
    public function actionMenusDays()
    {
        $model = new MenusDishes();
        if (Yii::$app->request->post())
        {
            $post = Yii::$app->request->post()['MenusDishes'];
            $menus_nutrition_id = MenusNutrition::find()->where(['menu_id' => $post['menu_id']])->all();//ВСЕ ПРИЕМЫ ПИЩИ КОНКРЕТНОГО МЕНЮ
            $ids = [];
            foreach ($menus_nutrition_id as $m_id)
            {
                $ids[] = $m_id->nutrition_id;//МАССИВ ID ПРИЕМОВ ПИЩИ КЛНКРЕЬНОГО МЕНЮ
            }
            $nutritions = NutritionInfo::find()->where(['id' => $ids])->all();//ОБЪЕКТ С НАБОРОМ ПРИЕМОВ ПИЩИ ИЗ БАЗЫ С УЧЕТОМ НАШИХ АЙДИ
            //$menus_dishes = MenusDishes::find()->where(['date_fact_menu' => 0, 'menu_id' => $post['menu_id'], 'cycle' => $post['cycle'], 'days_id' => $post['days_id']])->orderby(['nutrition_id' => SORT_ASC])->all();
            $menus_dishes = MenusDishes::find()->
            select(['menus_dishes.id as id', 'menus_dishes.date_fact_menu as date_fact_menu', 'menus_dishes.menu_id as menu_id', 'menus_dishes.cycle as cycle', 'menus_dishes.days_id as days_id', 'menus_dishes.nutrition_id as nutrition_id', 'menus_dishes.dishes_id as dishes_id', 'menus_dishes.yield as yield', 'dishes_category.sort as sort'])->
            leftJoin('dishes', 'menus_dishes.dishes_id = dishes.id')->
            leftJoin('dishes_category', 'dishes.dishes_category_id = dishes_category.id')->
            where(['date_fact_menu' => 0, 'menu_id' => $post['menu_id'], 'cycle' => $post['cycle'], 'days_id' => $post['days_id']])->
            orderby(['cycle' => SORT_ASC, 'days_id' => SORT_ASC, 'nutrition_id' => SORT_ASC, 'sort' => SORT_ASC])->
            all();

            return $this->render('menus-days', [
                'menus_dishes' => $menus_dishes,
                'nutritions' => $nutritions,
                'model' => $model,
                'post' => $post,
            ]);
        }
        return $this->render('menus-days', [
            //'menus_dishes' => $menus_dishes,
            //'nutritions' => $nutritions,
            'model' => $model,
        ]);
    }

    public function actionMenusPeriod()
    {
        $model = new MenusDishes();
        if (Yii::$app->request->post())
        {
            $identificator = Yii::$app->request->post()['identificator'];

            $post = Yii::$app->request->post()['MenusDishes'];

            $menus_nutrition_id = MenusNutrition::find()->where(['menu_id' => $post['menu_id']])->all();//ВСЕ ПРИЕМЫ ПИЩИ КОНКРЕТНОГО МЕНЮ
            $ids = [];
            foreach ($menus_nutrition_id as $m_id)
            {
                $ids[] = $m_id->nutrition_id;//МАССИВ ID ПРИЕМОВ ПИЩИ КЛНКРЕЬНОГО МЕНЮ
            }
            $nutritions = NutritionInfo::find()->where(['id' => $ids])->all();//ОБЪЕКТ С НАБОРОМ ПРИЕМОВ ПИЩИ ИЗ БАЗЫ С УЧЕТОМ НАШИХ АЙДИ
            $my_menus = Menus::findOne($post['menu_id']);
            $menu_cycle_count = $my_menus->cycle;
            if ($post['cycle'] != 0)
            {
                $cycle_ids[$post['cycle']] = $post['cycle'];
            }
            else
            {
                for ($i = 1; $i <= $menu_cycle_count; $i++)
                {
                    $cycle_ids[$i] = $i;//массив из подходящи циклов
                }
            }

			$dishes_check = MenusDishes::find()->where(['date_fact_menu' => 0, 'menu_id' => $post['menu_id'], 'cycle' => $cycle_ids])->count();
            if($dishes_check == 0){
                Yii::$app->session->setFlash('error', "В меню не внесены блюда. Перейдите в раздел 'Разработка редактирование действующего цикличного меню' и добавьте блюда в меню");
                return $this->redirect(['menus-dishes/index']);
            }
			
            $menus_dishes = MenusDishes::find()->
            select(['menus_dishes.id as id', 'menus_dishes.date_fact_menu as date_fact_menu', 'menus_dishes.menu_id as menu_id', 'menus_dishes.cycle as cycle', 'menus_dishes.days_id as days_id', 'menus_dishes.nutrition_id as nutrition_id', 'menus_dishes.dishes_id as dishes_id', 'menus_dishes.yield as yield', 'dishes_category.sort as sort'])->
            leftJoin('dishes', 'menus_dishes.dishes_id = dishes.id')->
            leftJoin('dishes_category', 'dishes.dishes_category_id = dishes_category.id')->
            where(['date_fact_menu' => 0, 'menu_id' => $post['menu_id'], 'cycle' => $cycle_ids])->
            orderby(['cycle' => SORT_ASC, 'days_id' => SORT_ASC, 'nutrition_id' => SORT_ASC, 'sort' => SORT_ASC])->
            all();

            $menus_days_id = MenusDays::find()->where(['menu_id' => $post['menu_id']])->all();//ВСЕ ДНИ КОНКРЕТНОГО МЕНЮ
            $days_ids = [];
            foreach ($menus_days_id as $day_id)
            {
                $days_ids[] = $day_id->days_id;//МАССИВ ID ДНЕЙ КЛНКРЕЬНОГО МЕНЮ
            }

            $days = Days::find()->where(['id' => $days_ids])->all();//ОБЪЕКТ С НАБОРОМ ПРИЕМОВ ПИЩИ ИЗ БАЗЫ С УЧЕТОМ НАШИХ АЙДИ


            return $this->render('menus-period', [
                'menus_dishes' => $menus_dishes,
                'nutritions' => $nutritions,
                'days' => $days,
                'model' => $model,
                'post' => $post,
                'identificator' => $identificator,
            ]);
        }

        return $this->render('menus-period', [
            'model' => $model,
        ]);
    }

    //Отчет о повторяемости!
    public function actionRepeat()
    {
        $model = new MenusDishes();

        if (Yii::$app->request->post())
        {
            $post = Yii::$app->request->post()['MenusDishes'];

            $menus_dishes = MenusDishes::find()->where(['date_fact_menu' => 0, 'menu_id' => $post['menu_id']])->orderby(['dishes_id' => SORT_ASC])->all();

            return $this->render('repeat-report', [
                'menus_dishes' => $menus_dishes,
                'model' => $model,
                'post' => $post,
            ]);
        }

        return $this->render('repeat', [
            'model' => $model,
        ]);
    }

    public function actionProductsList()
    {
        $model = new MenusDishes();
        if (Yii::$app->request->post())
        {
            $post = Yii::$app->request->post()['MenusDishes'];
            $menus_dishes = MenusDishes::find()->where(['date_fact_menu' => 0, 'menu_id' => $post['menu_id']])->orderby(['dishes_id' => SORT_ASC])->all();
            $dishes_ids = [];
            $categories_ids = [];
            foreach ($menus_dishes as $m_dish)
            {
                $dishes_products = DishesProducts::find()->where(['dishes_id' => $m_dish->dishes_id])->all();
                foreach ($dishes_products as $d_product)
                {
                    if (!in_array($d_product->products_id, $dishes_ids))
                    {
                        $dishes_ids[] = $d_product->products_id;
                    }
                }
            }
            $products = Products::find()->where(['id' => $dishes_ids])->orderby(['sort' => SORT_ASC])->all();
            foreach ($products as $product){
                if (!in_array($product->products_category_id, $categories_ids)){
                    $categories_ids[] = $product->products_category_id;
                }
            }
            $products_categories = ProductsCategory::find()->where(['id' => $categories_ids])->orderby(['sort' => SORT_ASC])->all();
            return $this->render('products-list', [
                'products_categories' => $products_categories,
                'products' => $products,
                'model' => $model,
                'post' => $post,
            ]);
        }
        return $this->render('products-list', [
            'model' => $model,
        ]);
    }

    public function actionPrognosStorage()
    {
        $model = new FactdateForm();


        $model4 = new ProductsCategory();
        $model3 = new MenusDishes();
        if (Yii::$app->request->post())
        {
            $post = Yii::$app->request->post()['FactdateForm'];;
            $menus_dishes = MenusDishes::find()->where(['date_fact_menu' => 0, 'menu_id' => $post['menu_id']])->orderby(['nutrition_id' => SORT_ASC])->all();
            $dishes_ids = [];

            foreach ($menus_dishes as $m_dish)
            {
                $dishes_products = DishesProducts::find()->where(['dishes_id' => $m_dish->dishes_id])->one();
                if (!in_array($dishes_products->dishes_id, $dishes_ids))
                {
                    /*Массив используемых продуктов. Продукты пока что не уникальных в 1м из 2х случаях*/
                    $dishes_ids[] = $dishes_products->dishes_id;
                }
            }

            $dishes_dishes_products = DishesProducts::find()->where(['dishes_id' => $dishes_ids])->orderby(['id' => SORT_ASC])->all();
            $categories_ids = [];

            foreach ($dishes_dishes_products as $d_d_product)
            {
                $product = Products::find()->where(['id' => $d_d_product->products_id])->one();
                $categories = ProductsCategory::find()->where(['id' => $product->products_category_id])->one();
                if (!in_array($product->products_category_id, $categories_ids))
                {
                    $categories_ids[] = $product->products_category_id;
                }
            }

            $categories = ProductsCategory::find()->where(['id' => $categories_ids])->orderby(['sort' => SORT_ASC])->all();

            $menus_nutritions = MenusNutrition::find()->where(['menu_id' => $post['menu_id']])->all();
            $nutrition_ids = [];

            foreach ($menus_nutritions as $m_nutrition)
            {
                $nutrition_ids[] = $m_nutrition->nutrition_id;
            }

            $nutritions = NutritionInfo::find()->where(['id' => $nutrition_ids])->all();
            $model2 = new ProductsCategory;
            return $this->render('prognos-storage', [
                'categories' => $categories,
                'nutritions' => $nutritions,
                'model' => $model,
                'model2' => $model2,
                'model3' => $model3,
                'model4' => $model4,
                'post' => $post,
            ]);
        }

        return $this->render('prognos-storage', [
            'model' => $model,
            'model3' => $model3,
        ]);
    }

    public function actionFactDate()
    {
        $model2 = new FactdateForm();
        $model = new MenusDishes();

        if (Yii::$app->request->post())
        {
            //$nutritions = NutritionInfo::find()->all();
            $post = Yii::$app->request->post()['FactdateForm'];
            $my_menus = Menus::findOne($post['menu_id']);

            if ($my_menus->date_end < strtotime($post['date']) || $my_menus->date_start > strtotime($post['date']))
            {
                Yii::$app->session->setFlash('error', "Указанная дата не входит в диапозон даты начала или даты окончания меню");
                return $this->redirect(['menus-dishes/fact-date']);
            }

            $menus_days_id = MenusDays::find()->where(['menu_id' => $post['menu_id']])->all();//ВСЕ ДНИ КОНКРЕТНОГО МЕНЮ
            $days_ids = [];
            foreach ($menus_days_id as $day_id)
            {
                if ($day_id->days_id != 7)
                {
                    $days_ids[] = $day_id->days_id;//МАССИВ ID ДНЕЙ КЛНКРЕЬНОГО МЕНЮ
                }
                /*ПРОБЛЕМА В ТОМ ЧТО ДНИ НЕДЕЛИ В БАЗЕ ХРАНЯТСЯ С ID ОТ 1-7(ВОСКРЕСЕНЬЕ - 7), А PHP ФУНКЦИИ DATE() ВОСКРЕСЕНЬЕ - 0 ДЕНЬ, ПОЭТОМУ НУЖЕН ELSE ЧТОБЫ 7 ЗАМЕНИТЬ НА 0*/
                else
                {
                    $days_ids[] = 0;
                }

            }
            if (!in_array(date("w", strtotime($post['date'])), $days_ids))
            {
                Yii::$app->session->setFlash('error', "Этот день недели отсутсвует в меню");
                return $this->redirect(['menus-dishes/fact-date']);
            }


            $start_date = date('d.m.Y', $my_menus->date_start);//Дата старта меню
            $day_of_week = date("w", strtotime($post['date']));//День недели выбранной даты
            $day_of_week_start_date = date("w", strtotime($start_date));//День недели даты старта меню
            /*ПРОБЛЕМА В ТОМ ЧТО ДНИ НЕДЕЛИ В БАЗЕ ХРАНЯТСЯ С ID ОТ 1-7(ВОСКРЕСЕНЬЕ - 7), А PHP ВОСКРЕСЕНЬЕ - 0 ДЕНЬ, ПОЭТОМУ НУЖЕН ELSE ЧТОБЫ 7 ЗАМЕНИТЬ НА 0*/
            /*ПЕРЕОПРЕДЕЛЯЕМ ОБРАТНО ДЕЛАЕМ ВОСКРЕСЕНЬЕ 7М ДНЕМ*/
            if ($day_of_week_start_date == 0)
            {
                $day_of_week_start_date = 7;
            }
            if ($day_of_week == 0)
            {
                $day_of_week = 7;
            }
            /*КОНЕЦ ПЕРЕОПРЕДЕЛЕНИЯ*/
            $day_offset = $day_of_week_start_date - 1;//СКОЛЬКО ДНЕЙ НУЖНО ОТНИМАТЬ ДЛЯ ТОГО ЧТОБЫ ПЕРЕЙТИ К ПОНЕДЕЛЬНИКУ

            $date_monday = date('d.m.Y', strtotime(($start_date) . ' - ' . $day_offset . ' day'));//ДАТА ПОНЕДЕЛЬНИКА САМОГО ПЕРВОГО
            $dif_monday_and_start = ceil(((strtotime($start_date)) - (strtotime($date_monday))) / 86400);//РАЗНИЦА МЕЖДУ ПОНЕДЕЛЬНИКОМ И СТАРТОВОЙ ДАТЫ В ДНЯХ
            $count_week = ceil((((strtotime($post['date']) - $my_menus->date_start) / 86400) + $dif_monday_and_start) / 7);//РАСЧЕТ КОЛИЧЕСТВА НЕДЕЛЬ МЕЖДУ ВЫБРАННОЙ ДАТОЙ И ПОНЕДЕЛЬНИКОМ КОТОРЫЙ САМЫЙ ПЕРВЫЙ

            $cycle = $count_week;//ПРИРАВНИВАЕМ ЦИКЛ КОЛИЧЕСТВУ НЕДЕЛЬ ДО НАШЕЙ ДАТЫ
            /*ЕСЛИ ВЫБРАННЫЙ ДЕНЬ ЯВЛЯЕТСЯ ПОНЕДЕЛЬНИКОМ, ТО ПРОГРАММА СЧИТАЕТ РАЗНИЦУ МЕЖДУ ДВУМЯ ПОНЕДЕЛЬНИКАМИ, СООТВЕТСТВЕННО ОШИБОЧНО ПРИБАВЛЯЕТСЯ ЛИШНЯЯ НЕДЕЛЯ, ПОЭТОМУ ЕЕ СЛЕДУЮТ УБИРАТЬ. ТАК КАК МЫ ИЩЕМ ПОНЕДЕЛЬНИК( И ОН МОЖЕТ И НЕ ВХОДИТ В ДИАПОЗОН СТАРТА И ОКОНЧАНИЯ, ВОЗНИКАЕТ ОШИБКА ОПРЕДЕЛЕНИЯ ЦИКЛА. СЛЕДУЮЩЕЕ УСЛОВИЕЕ ЕЕ ИСПРАВЛЯЕТ)*/
            if ($day_of_week == 1)
            {
                $cycle = $count_week - 1;
            }
            /*$date_monday дата понедельника с которого идет отсчет. ПРОБЛЕМА В ТОМ ЧТО ЭТОТ ПОНЕДЕЛЬНИК МОЖЕТ ЯВЛЯТЬСЯ ПЕРВЫМ ДНЕМ НАШЕГО МЕНЮ И СООТВЕТСТВЕННО РАЗНИЦА МЕЖДУ ЭТИМИ ДНЯМИ БУДЕТ 0 И ЦИКЛ СООТВЕТСТВЕННО -1. ПОЭТОМУ В ЭТОМ СЛУЧАЕ МЫ НАЗНАЧАЕМ ТАКОЙ ПОНЕДЕЛЬНИК ПЕРВОЙ НЕДЕЛЕЙ*/
            if ($count_week == 0)
            {
                $cycle = 1;
            }

            /*ПРОЦЕСС ИЗМЕНЕНИЯ ЦИКЛА ВЗАВИСИМОСТИ ОТ КОЛИЧЕСТВО НЕДЕЛЬ*/
            while ($cycle > $my_menus->cycle)
            {
                $cycle = $cycle - $my_menus->cycle;
            }
            if ($cycle == 0)
            {
                $cycle = $my_menus->cycle;
            }
            /*КОНЕЦ ПРОЦЕССА ИЗМЕНЕИЯ ЦИКЛАБ ДАЛЕЕ ЦИКЛ ОТПРАВЛЯЕМ ВО VIEW*/

            $menus_nutrition_id = MenusNutrition::find()->where(['menu_id' => $post['menu_id']])->all();//ВСЕ ПРИЕМЫ ПИЩИ КОНКРЕТНОГО МЕНЮ
            $ids = [];
            foreach ($menus_nutrition_id as $m_id)
            {
                $ids[] = $m_id->nutrition_id;//МАССИВ ID ПРИЕМОВ ПИЩИ КЛНКРЕЬНОГО МЕНЮ
            }

            $nutritions = NutritionInfo::find()->where(['id' => $ids])->all();//ОБЪЕКТ С НАБОРОМ ПРИЕМОВ ПИЩИ ИЗ БАЗЫ С УЧЕТОМ НАШИХ АЙДИ
            //$menus_dishes = MenusDishes::find()->where(['menu_id' => $post['menu_id'], 'cycle' => $cycle, 'days_id' => $day_of_week])->orderby(['nutrition_id' => SORT_ASC])->all();

            $menus_dishes = MenusDishes::find()->
            select(['menus_dishes.id as id', 'menus_dishes.date_fact_menu as date_fact_menu', 'menus_dishes.menu_id as menu_id', 'menus_dishes.cycle as cycle', 'menus_dishes.days_id as days_id', 'menus_dishes.nutrition_id as nutrition_id', 'menus_dishes.dishes_id as dishes_id', 'menus_dishes.yield as yield', 'dishes_category.sort as sort'])->
            leftJoin('dishes', 'menus_dishes.dishes_id = dishes.id')->
            leftJoin('dishes_category', 'dishes.dishes_category_id = dishes_category.id')->
            where(['date_fact_menu' => strtotime($post['date']), 'menu_id' => $post['menu_id']])->
            orderby(['cycle' => SORT_ASC, 'days_id' => SORT_ASC, 'nutrition_id' => SORT_ASC, 'sort' => SORT_ASC])->
            all();


            if(empty($menus_dishes)){
                $menus_dishes = MenusDishes::find()->
                select(['menus_dishes.id as id', 'menus_dishes.date_fact_menu as date_fact_menu', 'menus_dishes.menu_id as menu_id', 'menus_dishes.cycle as cycle', 'menus_dishes.days_id as days_id', 'menus_dishes.nutrition_id as nutrition_id', 'menus_dishes.dishes_id as dishes_id', 'menus_dishes.yield as yield', 'dishes_category.sort as sort'])->
                leftJoin('dishes', 'menus_dishes.dishes_id = dishes.id')->
                leftJoin('dishes_category', 'dishes.dishes_category_id = dishes_category.id')->
                where(['date_fact_menu' => 0, 'menu_id' => $post['menu_id'], 'cycle' => $cycle, 'days_id' => $day_of_week])->
                orderby(['cycle' => SORT_ASC, 'days_id' => SORT_ASC, 'nutrition_id' => SORT_ASC, 'sort' => SORT_ASC])->
                all();
            }


            return $this->render('fact-date', [
                'menus_dishes' => $menus_dishes,
                'nutritions' => $nutritions,
                'model' => $model,
                'model2' => $model2,
                'post' => $post,
                'day_of_week' => $day_of_week,
                'cycle' => $cycle,
            ]);
        }


        return $this->render('fact-date', [
            'model2' => $model2,
            'model' => $model,
        ]);
    }
}
