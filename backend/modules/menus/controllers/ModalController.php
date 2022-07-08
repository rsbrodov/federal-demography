<?php

namespace backend\modules\menus\controllers;

use common\models\Days;
use common\models\Dishes;
use common\models\DishesProducts;
use common\models\MenuForm;
use common\models\Menus;
use common\models\MenusDays;
use common\models\MenusDishes;
use common\models\MenuSearch;
use common\models\MenusNutrition;
use common\models\MenusOrgRpnMinobr;
use common\models\MenusVariativity;
use common\models\NutritionInfo;
use common\models\Organization;
use common\models\Variativity;
use Yii;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\web\Controller;
use yii\web\NotFoundHttpException;


class ModalController extends Controller
{

    /*МЕТОД ДЛЯ ДЕМОНСТРАЦИИ ТЕХКАРТЫ НА (НА НАШИ) КОЛИЧЕСТВО ГР*/
    public function actionShowtechmup($id)
    {
        //$id - id блюда
        $this->layout = false;
        $indicator = 1;
        $menus_dishes = MenusDishes::findOne($id);
        $dishes = Dishes::findOne($menus_dishes->dishes_id);
        //$dishes_products = DishesProducts::find()->where(['dishes_id' => $id])->orderBy(['created_at' => 'SORT_ASK'])->all();

        $dishes_products = DishesProducts::find()->
        select(['dishes_products.id as id', 'dishes_products.dishes_id as dishes_id', 'dishes_products.products_id as products_id', 'dishes_products.net_weight as net_weight', 'dishes_products.gross_weight as gross_weight', 'products_category.sort as sort'])->
        leftJoin('products', 'dishes_products.products_id = products.id')->
        leftJoin('products_category', 'products.products_category_id = products_category.id')->
        where(['dishes_id' => $dishes->id])->
        orderby(['sort_techmup' => SORT_ASC])->
        all();


        return $this->render('techmup', [
            'indicator' => $indicator,
            'dishes' => $dishes,
            'dishes_products' => $dishes_products,
            'id' => $id,
        ]);
    }
    
    /*МЕТОД ДЛЯ ДЕМОНСТРАЦИИ ТЕХКАРТЫ С УЧЕТОМ ТЕКУЩ. ВЫВОДА*/
    public function actionShowtechmup_current_yield($id)
    {
        //$id - id из таблицы menus_dishes
        $this->layout = false;
        $menus_dishes = MenusDishes::findOne($id);
        $dishes = Dishes::findOne($menus_dishes->dishes_id);
        //$dishes_products = DishesProducts::find()->where(['dishes_id' => $menus_dishes->dishes_id])->all();

        $dishes_products = DishesProducts::find()->
        select(['dishes_products.id as id', 'dishes_products.dishes_id as dishes_id', 'dishes_products.products_id as products_id', 'dishes_products.net_weight as net_weight', 'dishes_products.gross_weight as gross_weight', 'products_category.sort as sort'])->
        leftJoin('products', 'dishes_products.products_id = products.id')->
        leftJoin('products_category', 'products.products_category_id = products_category.id')->
        where(['dishes_id' => $menus_dishes->dishes_id])->
        orderby(['sort_techmup' => SORT_ASC])->
        all();

        $indicator = $menus_dishes->yield / $dishes->yield;
        return $this->render('techmup', [
            'indicator' => $indicator,
            'dishes' => $dishes,
            'dishes_products' => $dishes_products,
            'id' => $id,
        ]);
    }


    /*МЕТОД ДЛЯ ДЕМОНСТРАЦИИ ТЕХКАРТЫ НА В МОМЕНТ СОЗДАНИЯ БЛЮДА ПО ДОП КНОПКЕ КЛИК*/
    public function actionShowtechmupadd($id)
    {
        $this->layout = false;
        //$id - id блюда
        $dishes = Dishes::findOne($id);
        //$dishes_products = DishesProducts::find()->where(['dishes_id' => $id])->orderBy(['created_at' => 'SORT_ASK'])->all();

        $dishes_products = DishesProducts::find()->
        select(['dishes_products.id as id', 'dishes_products.dishes_id as dishes_id', 'dishes_products.products_id as products_id', 'dishes_products.net_weight as net_weight', 'dishes_products.gross_weight as gross_weight', 'products_category.sort as sort'])->
        leftJoin('products', 'dishes_products.products_id = products.id')->
        leftJoin('products_category', 'products.products_category_id = products_category.id')->
        where(['dishes_id' => $dishes->id])->
        orderby(['sort_techmup' => SORT_ASC])->
        all();


        return $this->render('techmup-null', [
            'indicator' => 1,
            'dishes' => $dishes,
            'dishes_products' => $dishes_products,
            'id' => $id,
        ]);
    }



    /*МЕТОД ДЛЯ ДЕМОНСТРАЦИИ 'ПОКАЗАТЬ СОСТАВ ЗА <ПРИЕМ ПИЩИ>' НА СТРАНИЦЕ menus-dishes/index*/
    public function actionShow_composition($menu_id, $cycle, $days_id, $nutrition_id)
    {
        $menus_nutrition_id = MenusNutrition::find()->where(['menu_id' => $menu_id])->all();//ВСЕ ПРИЕМЫ ПИЩИ КОНКРЕТНОГО МЕНЮ
        $ids = [];
        foreach ($menus_nutrition_id as $m_id)
        {
            $ids[] = $m_id->nutrition_id;//МАССИВ ID ПРИЕМОВ ПИЩИ КЛНКРЕЬНОГО МЕНЮ
        }

        $nutritions = NutritionInfo::find()->where(['id' => $ids])->all();//ОБЪЕКТ С НАБОРОМ ПРИЕМОВ ПИЩИ ИЗ БАЗЫ С УЧЕТОМ НАШИХ АЙДИ

        //$id - id приема пищи
        $this->layout = false;
        if ($nutrition_id > 0)
        {
            $menus_dishes = MenusDishes::find()->where(['date_fact_menu' => 0, 'menu_id' => $menu_id, 'cycle' => $cycle, 'days_id' => $days_id, 'nutrition_id' => $nutrition_id])->all();
        }
        else
        {
            $menus_dishes = MenusDishes::find()->where(['date_fact_menu' => 0, 'menu_id' => $menu_id, 'cycle' => $cycle, 'days_id' => $days_id])->all();
        }

        if (empty($menus_dishes))
        {
            return 'Необходимо заполнить информацию о съеденной за день пище.';
        }

        $indicator_page = $nutrition_id;
        $model = new MenusDishes();
        return $this->render('composition', [
            'menus_dishes' => $menus_dishes,
            'nutritions' => $nutritions,
            'indicator_page' => $indicator_page,
            'model' => $model,
        ]);
    }
}
