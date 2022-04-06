<?php

namespace backend\controllers;

use common\models\Menus;
use common\models\MenusDishes;
use Yii;
use common\models\DishesProducts;
use common\models\Dishes;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * DishesProductsController implements the CRUD actions for DishesProducts model.
 */
class DishesProductsController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors() {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all DishesProducts models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => DishesProducts::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionDish($id)
    {
        $dataProvider = new ActiveDataProvider([
            'query' => DishesProducts::find()->where(['dishes_id' => $id]),
        ]);

        return $this->render('dish', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single DishesProducts model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new DishesProducts model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new DishesProducts();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing DishesProducts model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['/dishes/addproduct', 'id' => $model->dishes_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing DishesProducts model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $this->findModel($id)->delete();

        return $this->redirect(['dish', 'id' => $model->dishes_id]);
    }

    /**
     * Finds the DishesProducts model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DishesProducts the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DishesProducts::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }


    public function actionControl()
    {
        $count2 = 0;$count = 0;$count3 = 0; $count4 = 0;
        $dishes_products = DishesProducts::find()->all();
        $menus_dishes = MenusDishes::find()->all();
        //$dishes = Dishes::find()->all();
        print_r('<b>Несуществующие блюда(продукт в блюде есть, а самого блюда больше не существует):</b><br>');
        foreach($dishes_products as $d_product){
            $dish = Dishes::findOne($d_product->dishes_id);
            if(empty($dish)){
                $count++;
                print_r('ID несуществующее блюдо:'.$d_product->id.'<br>');
            }
        }
        print_r('Количество ошибок:'.$count.'<br>-----------------------------------------------<br><br>');

        print_r('Отчет об ошибке: <b>Брутто меньше чем нетто:</b><br>');
        foreach($dishes_products as $d_product){
            if($d_product->gross_weight < $d_product->net_weight){
                $count2++;
                print_r('В блюде:<b>'.$d_product->get_dishes($d_product->dishes_id).($d_product->dishes_id).'</b><br> У продукта<b> '.$d_product->get_product($d_product->products_id).'</b> вес брутто меньше чем нетто<br>--------------------------------------------------------------------------------------------<br>');
            }
        }
        print_r('<b>Количество ошибок с брутто/нетто:'.$count2.'</b><br><br>');

        print_r('<b>В меню забиты не существющие блюда:</b><br>');
        foreach($menus_dishes as $m_dish){
            $dish = Dishes::findOne($m_dish->dishes_id);
            if(empty($dish)){
                $count3++;
                //print_r('ID $menus_dishes:'.$m_dish->id.'<br>');
                print_r('Меню:'.$m_dish->get_menus($m_dish->menu_id).' ID: '.$m_dish->menu_id.'<br><b>Блюдо: с ID </b>'.$m_dish->dishes_id.' больше не существует<br>В таблице menus_dishes удалить запись с id: '.$m_dish->id.'<br>---------------------------------------------------------<br>');
            }
        }
        print_r('<b>Количество ошибок с меню не существующими блюдами:'.$count3.'</b><br><br><br>');


        print_r('<b>В МЕНЮ УКАЗАНЫ НЕВЕРНЫЕ ДАТЫ НАЧАЛА ИЛИ КОНЦА:(даты в прошедшем времени/дата начала равна или больше даты конца/либо даты вобще были не указаны)</b><br>');
        $menus = Menus::find()->all();
        foreach ($menus as $menu){
            if($menu->date_start < 1000000 || $menu->date_end < 1000000 || $menu->date_end == $menu->date_start){
                $count4 ++;
                print_r('Меню:'.$menu->name.' ID: '.$menu->id.'<br><b>Ошибка в датах начала или конца</b><br>---------------------------------------------------------<br>');
            }
        }
        print_r('<b>Количество ошибок с меню с неправильными датами:'.$count4.'</b><br><br>');
        $count_end = $count + $count2 + $count3 + $count4;
        print_r('<b>Всего ошибок:'.$count_end.'</b><br><br>');
        exit;
    }
}
