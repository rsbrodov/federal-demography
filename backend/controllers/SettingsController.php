<?php

namespace backend\controllers;

use common\models\Dishes;
use common\models\DishesCategory;
use common\models\DishesProducts;
use common\models\FeedersCharacters;
use common\models\MenusDishes;
use common\models\RecipesCollection;
use common\models\NutritionInfo;
use common\models\Resource;
use common\models\CulinaryProcessing;
use Yii;
use common\models\AgeInfo;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * AgeInfoController implements the CRUD actions for AgeInfo model.
 */
class SettingsController extends Controller
{

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


    public function actionAgeIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => AgeInfo::find(),
        ]);

        return $this->render('age-index', [
            'dataProvider' => $dataProvider,
        ]);
    }


    public function actionAgeView($id)
    {
        return $this->render('age-view', [
            'model' => $this->findModelAge($id),
        ]);
    }


    public function actionAgeCreate()
    {
        $model = new AgeInfo();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['age-view', 'id' => $model->id]);
        }

        return $this->render('age-create', [
            'model' => $model,
        ]);
    }


    public function actionAgeUpdate($id)
    {
        $model = $this->findModelAge($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['age-view', 'id' => $model->id]);
        }

        return $this->render('age-update', [
            'model' => $model,
        ]);
    }


    public function actionAgeDelete($id)
    {
        $this->findModelAge($id)->delete();

        return $this->redirect(['age-index']);
    }


    protected function findModelAge($id)
    {
        if (($model = AgeInfo::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }






    public function actionCharactersIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => FeedersCharacters::find(),
        ]);

        return $this->render('characters-index', [
            'dataProvider' => $dataProvider,
        ]);
    }


    public function actionCharactersView($id)
    {
        return $this->render('characters-view', [
            'model' => $this->findModelCharacters($id),
        ]);
    }

    public function actionCharactersCreate()
    {
        $model = new FeedersCharacters();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['characters-view', 'id' => $model->id]);
        }

        return $this->render('characters-create', [
            'model' => $model,
        ]);
    }


    public function actionCharactersUpdate($id)
    {
        $model = $this->findModelCharacters($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['characters-view', 'id' => $model->id]);
        }

        return $this->render('characters-update', [
            'model' => $model,
        ]);
    }


    public function actionCharactersDelete($id)
    {
        $this->findModelCharacters($id)->delete();

        return $this->redirect(['characters-index']);
    }


    protected function findModelCharacters($id)
    {
        if (($model = FeedersCharacters::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }





    public function actionResourcesIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Resource::find(),
        ]);

        return $this->render('resources-index', [
            'dataProvider' => $dataProvider,
        ]);
    }


    public function actionResourcesView($id)
    {
        return $this->render('resources-view', [
            'model' => $this->findModelResources($id),
        ]);
    }


    public function actionResourcesCreate()
    {
        $model = new Resource();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['resources-view', 'id' => $model->id]);
        }

        return $this->render('resources-create', [
            'model' => $model,
        ]);
    }


    public function actionResourcesUpdate($id)
    {
        $model = $this->findModelResources($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['resources-view', 'id' => $model->id]);
        }

        return $this->render('resources-update', [
            'model' => $model,
        ]);
    }


    public function actionResourcesDelete($id)
    {
        $this->findModelResources($id)->delete();

        return $this->redirect(['resources-index']);
    }


    protected function findModelResources($id)
    {
        if (($model = Resource::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }




    public function actionNutritionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => NutritionInfo::find(),
        ]);

        return $this->render('nutrition-index', [
            'dataProvider' => $dataProvider,
        ]);
    }


    public function actionNutritionView($id)
    {
        return $this->render('nutrition-view', [
            'model' => $this->findModelNutrition($id),
        ]);
    }


    public function actionNutritionCreate()
    {
        $model = new NutritionInfo();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['nutrition-view', 'id' => $model->id]);
        }

        return $this->render('nutrition-create', [
            'model' => $model,
        ]);
    }


    public function actionNutritionUpdate($id)
    {
        $model = $this->findModelNutrition($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['nutrition-view', 'id' => $model->id]);
        }

        return $this->render('nutrition-update', [
            'model' => $model,
        ]);
    }


    public function actionNutritionDelete($id)
    {
        $this->findModelNutrition($id)->delete();

        return $this->redirect(['nutrition-index']);
    }


    protected function findModelNutrition($id)
    {
        if (($model = NutritionInfo::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }


    public function actionRecipesIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => RecipesCollection::find()->where(['organization_id' => Yii::$app->user->identity->organization_id]),
        ]);

        /*if(Yii::$app->user->can('rospotrebnadzor_camp') || Yii::$app->user->can('rospotrebnadzor_nutrition') || Yii::$app->user->can('subject_minobr'))
        {
            $dataProvider = new ActiveDataProvider([
                'query' => RecipesCollection::find()->where(['organization_id' => Yii::$app->session['organization_id']]),
            ]);
        }*/

        return $this->render('recipes-index', [
            'dataProvider' => $dataProvider,
        ]);
    }



    public function actionRecipesIndexAdmin()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => RecipesCollection::find(),
        ]);

        return $this->render('recipes-index', [
            'dataProvider' => $dataProvider,
        ]);
    }


    public function actionRecipesView($id)
    {
        return $this->render('recipes-view', [
            'model' => $this->findModelRecipes($id),
        ]);
    }


    public function actionRecipesCreate()
    {
        $model = new RecipesCollection();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', "Сборник успешно создан");
            if(Yii::$app->user->can('admin')){
                return $this->redirect(['recipes-index-admin']);
            }
            return $this->redirect(['recipes-index']);
        }

        return $this->render('recipes-create', [
            'model' => $model,
        ]);
    }


    /*public function actionRecipesPrintExcel()
    {
        $model = new RecipesCollection();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', "Сборник успешно создан");
            if(Yii::$app->user->can('admin')){
                return $this->redirect(['recipes-index-admin']);
            }
            return $this->redirect(['recipes-index']);
        }

        return $this->render('recipes-create', [
            'model' => $model,
        ]);
    }*/


    public function actionRecipesUpdate($id)
    {
        $model = $this->findModelRecipes($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', "Сборник успешно обновлен");
            if(Yii::$app->user->can('admin')){
                return $this->redirect(['recipes-index-admin']);
            }
            return $this->redirect(['recipes-index']);
        }

        return $this->render('recipes-update', [
            'model' => $model,
        ]);
    }


    public function actionRecipesDelete($id)
    {
        $this->findModelRecipes($id)->delete();
        if(Yii::$app->user->can('admin')){
            return $this->redirect(['recipes-index-admin']);
        }

        return $this->redirect(['recipes-index']);
    }


    protected function findModelRecipes($id)
    {
        if (($model = RecipesCollection::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }



    public function actionCulinaryIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => CulinaryProcessing::find(),
        ]);

        return $this->render('culinary-index', [
            'dataProvider' => $dataProvider,
        ]);
    }


    public function actionCulinaryView($id)
    {
        return $this->render('culinary-view', [
            'model' => $this->findModelCulinaryProcessing($id),
        ]);
    }


    public function actionCulinaryCreate()
    {
        $model = new CulinaryProcessing();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['culinary-view', 'id' => $model->id]);
        }

        return $this->render('culinary-create', [
            'model' => $model,
        ]);
    }


    public function actionCulinaryUpdate($id)
    {
        $model = $this->findModelCulinaryProcessing($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['culinary-view', 'id' => $model->id]);
        }

        return $this->render('culinary-update', [
            'model' => $model,
        ]);
    }


    public function actionCulinaryDelete($id)
    {
        $this->findModelCulinaryProcessing($id)->delete();

        return $this->redirect(['culinary-index']);
    }


    protected function findModelCulinaryProcessing($id)
    {
        if (($model = CulinaryProcessing::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }





    //ПЕЧАТЬ ОПРЕДЕЛЕННОГО СБОРНИКА В ФОРМАТЕ EXCEL
    public function actionRecipesPrintExcel($id)
    {
        require_once Yii::$app->basePath . '/Excel/PHPExcel.php';
        require_once Yii::$app->basePath . '/Excel/PHPExcel/IOFactory.php';

        $document = new \PHPExcel();

        ob_start();

        $sheet = $document->getActiveSheet(0);
        //$sheet->setTitle('666');

        $this->layout = false;
        $indicator = 1;

        $menus_dishes_model= new MenusDishes();

        $categories = DishesCategory::find()->all();
        $cat_count = 0; foreach($categories as $category)
    {
        $dishes = Dishes::find()->where(['recipes_collection_id' => $id, 'dishes_category_id' => $category->id])->orderby(['techmup_number' => SORT_ASC])->all();
        if(!empty($dishes))
        {
            $num_st = 1;
            $num = 3;
            $sheet->getRowDimension($num_st)->setRowHeight(25);

            $objWorkSheet = $document->createSheet($cat_count);
            $objWorkSheet->getStyle('A1:X300')->getFont()->setName('Times New Roman');
            $objWorkSheet->getStyle('A1:X300')->getFont()->setSize(12);
            $objWorkSheet->setTitle("$category->name");
            $cat_count++;


            $document->getActiveSheet($cat_count)->getColumnDimension('A')->setWidth(45);
            $document->getActiveSheet($cat_count)->getColumnDimension('B')->setWidth(10);
            $document->getActiveSheet($cat_count)->getColumnDimension('D')->setWidth(10);
            $document->getActiveSheet($cat_count)->getColumnDimension('J')->setWidth(10);


            $objWorkSheet->setCellValue('A' . $num_st, "Продукты");
            $objWorkSheet->mergeCells("B" . $num_st . ":C" . $num_st);
            $objWorkSheet->mergeCells("H" . $num_st . ":L" . $num_st);
            $objWorkSheet->mergeCells("M" . $num_st . ":T" . $num_st);
            $objWorkSheet->setCellValue('B' . $num_st, "Расход сырья(г.)");
            $objWorkSheet->setCellValue('D' . $num_st, "Белки");
            $objWorkSheet->setCellValue('E' . $num_st, "Жиры");
            $objWorkSheet->setCellValue('F' . $num_st, "Углеводы");
            $objWorkSheet->setCellValue('G' . $num_st, "Эн. ценность");
            $objWorkSheet->setCellValue('H' . $num_st, "Витамины(В1,В2,C - в мг; А - мкг рет.экв; D - мкг)");
            $objWorkSheet->setCellValue('M' . $num_st, "Минералы(Na, Ca, K, Mg, P, Fe - в мг; I, Se, F - в мкг)");

            $num_st++;

            $objWorkSheet->setCellValue('A' . $num_st, "");
            $objWorkSheet->setCellValue('B' . $num_st, "брутто");
            //$sheet->setCellValue('C' . $num_st, "Наименование сборника рецептур, год выпуска, автор");
            $objWorkSheet->setCellValue('C' . $num_st, "нетто");
            $objWorkSheet->setCellValue('D' . $num_st, "(г.)");
            $objWorkSheet->setCellValue('E' . $num_st, "(г.)");
            $objWorkSheet->setCellValue('F' . $num_st, "(г.)");
            $objWorkSheet->setCellValue('G' . $num_st, "(ккал.)");
            $objWorkSheet->setCellValue('H' . $num_st, "B1");
            $objWorkSheet->setCellValue('I' . $num_st, "B2");
            $objWorkSheet->setCellValue('J' . $num_st, "А");
            $objWorkSheet->setCellValue('K' . $num_st, "D");
            $objWorkSheet->setCellValue('L' . $num_st, "C");
            $objWorkSheet->setCellValue('M' . $num_st, "Na");
            $objWorkSheet->setCellValue('N' . $num_st, "K");
            $objWorkSheet->setCellValue('O' . $num_st, "Ca");
            $objWorkSheet->setCellValue('P' . $num_st, "Mg");
            $objWorkSheet->setCellValue('Q' . $num_st, "P");
            $objWorkSheet->setCellValue('R' . $num_st, "Fe");
            $objWorkSheet->setCellValue('S' . $num_st, "I");
            $objWorkSheet->setCellValue('T' . $num_st, "Se");
            $objWorkSheet->setCellValue('U' . $num_st, "F");
            foreach ($dishes as $dish)
            {
                $dishes_products = DishesProducts::find()->
                select(['dishes_products.id as id', 'dishes_products.dishes_id as dishes_id', 'dishes_products.products_id as products_id', 'dishes_products.net_weight as net_weight', 'dishes_products.gross_weight as gross_weight', 'products_category.sort as sort'])->
                leftJoin('products', 'dishes_products.products_id = products.id')->
                leftJoin('products_category', 'products.products_category_id = products_category.id')->
                where(['dishes_id' => $dish->id])->
                orderby(['sort_techmup' => SORT_ASC])->
                all();


                //$sheet->setCellValue('A' . $numRow, $array_org[$i][2]);
                $objWorkSheet->getStyle("A" . $num)->getFont()->setBold(true);
                $objWorkSheet->setCellValue('A' . $num, mb_strtoupper($dish->name));
                $num++;
                $objWorkSheet->getStyle("A" . $num)->getFont()->setBold(true);
                $objWorkSheet->setCellValue('A' . $num, "Номер рецептуры: " . $dish->techmup_number);
                $num++;
                $super_total_yield = 0;
                $super_total_protein = 0;
                $super_total_fat = 0;
                $super_total_carbohydrates_total = 0;
                $super_total_energy_kkal = 0;
                $super_total_vitamin_a = 0;
                $super_total_vitamin_c = 0;
                $super_total_vitamin_b1 = 0;
                $super_total_vitamin_b2 = 0;
                $super_total_vitamin_d = 0;
                $super_total_vitamin_pp = 0;
                $super_total_na = 0;
                $super_total_k = 0;
                $super_total_ca = 0;
                $super_total_f = 0;
                $super_total_se = 0;
                $super_total_i = 0;
                $super_total_fe = 0;
                $super_total_p = 0;
                $super_total_mg = 0;
                $super_total_f = 0;
                $number_row = 1;
                foreach ($dishes_products as $d_product)
                {
                    $objWorkSheet->setCellValue('A' . $num, $d_product->get_products($d_product->products_id)->name);
                    if($d_product->products_id == 8 || $d_product->products_id == 10 || $d_product->products_id == 9){
                        $objWorkSheet->setCellValue('B' . $num, sprintf("%.2f", $d_product->gross_weight * $indicator));
                        $objWorkSheet->setCellValue('C' . $num, sprintf("%.2f", $d_product->net_weight * $indicator));
                        $protein = sprintf("%.2f", $menus_dishes_model->get_products_bju_techmup($d_product->products_id, $d_product->dishes_id, 'protein') * (($d_product->net_weight) / 100) * $indicator);
                        $objWorkSheet->setCellValue('D' . $num, $protein);
                        $fat = sprintf("%.2f", $menus_dishes_model->get_products_bju_techmup($d_product->products_id, $d_product->dishes_id, 'fat') * (($d_product->net_weight) / 100) * $indicator);
                        $objWorkSheet->setCellValue('E' . $num, $fat);
                        $carbohydrates_total = sprintf("%.2f", $menus_dishes_model->get_products_bju_techmup($d_product->products_id, $d_product->dishes_id, 'carbohydrates_total') * (($d_product->net_weight) / 100) * $indicator);
                        $objWorkSheet->setCellValue('F' . $num, $carbohydrates_total);
                    }else{
                        $objWorkSheet->setCellValue('B' . $num, sprintf("%.1f", $d_product->gross_weight * $indicator));
                        $objWorkSheet->setCellValue('C' . $num, sprintf("%.1f", $d_product->net_weight * $indicator));
                        $protein = sprintf("%.1f", $menus_dishes_model->get_products_bju_techmup($d_product->products_id, $d_product->dishes_id, 'protein') * (($d_product->net_weight) / 100) * $indicator);
                        $objWorkSheet->setCellValue('D' . $num, $protein);
                        $fat = sprintf("%.1f", $menus_dishes_model->get_products_bju_techmup($d_product->products_id, $d_product->dishes_id, 'fat') * (($d_product->net_weight) / 100) * $indicator);
                        $objWorkSheet->setCellValue('E' . $num, $fat);
                        $carbohydrates_total = sprintf("%.1f", $menus_dishes_model->get_products_bju_techmup($d_product->products_id, $d_product->dishes_id, 'carbohydrates_total') * (($d_product->net_weight) / 100) * $indicator);
                        $objWorkSheet->setCellValue('F' . $num, $carbohydrates_total);
                    }
                    $energy_kkal = sprintf("%.1f", $menus_dishes_model->get_kkal_techmup($d_product->products_id, $d_product->dishes_id) * (($d_product->net_weight) / 100) * $indicator);
                    $objWorkSheet->setCellValue('G' . $num, $energy_kkal);

                    $vitamin_b1 = sprintf("%.2f", $d_product->get_vitamin($d_product->products_id, $d_product->dishes_id, 'vitamin_b1') * (($d_product->net_weight) / 100) * $indicator);
                    $objWorkSheet->setCellValue('H' . $num, $vitamin_b1);
                    $vitamin_b2 = sprintf("%.2f", $d_product->get_vitamin($d_product->products_id, $d_product->dishes_id, 'vitamin_b2') * (($d_product->net_weight) / 100) * $indicator);
                    $objWorkSheet->setCellValue('I' . $num, $vitamin_b2);
                    $vitamin_a = sprintf("%.2f", $d_product->get_vitamin($d_product->products_id, $d_product->dishes_id, 'vitamin_a') * (($d_product->net_weight) / 100) * $indicator);
                    $objWorkSheet->setCellValue('J' . $num, $vitamin_a);
                    $vitamin_d = sprintf("%.2f", $d_product->get_vitamin($d_product->products_id, $d_product->dishes_id, 'vitamin_d') * (($d_product->net_weight) / 100) * $indicator);
                    $objWorkSheet->setCellValue('K' . $num, $vitamin_d);
                    $vitamin_c = sprintf("%.2f", $d_product->get_vitamin($d_product->products_id, $d_product->dishes_id, 'vitamin_c') * (($d_product->net_weight) / 100) * $indicator);
                    $objWorkSheet->setCellValue('L' . $num, $vitamin_c);
                    $na = sprintf("%.2f", $d_product->get_vitamin($d_product->products_id, $d_product->dishes_id, 'na') * (($d_product->net_weight) / 100) * $indicator);
                    $objWorkSheet->setCellValue('M' . $num, $na);
                    $k = sprintf("%.2f", $d_product->get_vitamin($d_product->products_id, $d_product->dishes_id, 'k') * (($d_product->net_weight) / 100) * $indicator);
                    $objWorkSheet->setCellValue('N' . $num, $k);
                    $ca = sprintf("%.2f", $d_product->get_vitamin($d_product->products_id, $d_product->dishes_id, 'ca') * (($d_product->net_weight) / 100) * $indicator);
                    $objWorkSheet->setCellValue('O' . $num, $ca);
                    $mg = sprintf("%.2f", $d_product->get_vitamin($d_product->products_id, $d_product->dishes_id, 'mg') * (($d_product->net_weight) / 100) * $indicator);
                    $objWorkSheet->setCellValue('P' . $num, $mg);
                    $p = sprintf("%.2f", $d_product->get_vitamin($d_product->products_id, $d_product->dishes_id, 'p') * (($d_product->net_weight) / 100) * $indicator);
                    $objWorkSheet->setCellValue('Q' . $num, $p);
                    $fe = sprintf("%.2f", $d_product->get_vitamin($d_product->products_id, $d_product->dishes_id, 'fe') * (($d_product->net_weight) / 100) * $indicator);
                    $objWorkSheet->setCellValue('R' . $num, $fe);
                    $i = sprintf("%.2f", $d_product->get_vitamin($d_product->products_id, $d_product->dishes_id, 'i') * (($d_product->net_weight) / 100) * $indicator);
                    $objWorkSheet->setCellValue('S' . $num, $i);
                    $se = sprintf("%.2f", $d_product->get_vitamin($d_product->products_id, $d_product->dishes_id, 'se') * (($d_product->net_weight) / 100) * $indicator);
                    $objWorkSheet->setCellValue('T' . $num, $se);
                    $f = sprintf("%.2f", $d_product->get_vitamin($d_product->products_id, $d_product->dishes_id, 'f') * (($d_product->net_weight) / 100) * $indicator);
                    $objWorkSheet->setCellValue('U' . $num, $f);

                    $number_row++;
                    $num++;
                    $super_total_protein = $super_total_protein + $protein;
                    $super_total_fat = $super_total_fat + $fat;
                    $super_total_carbohydrates_total = $super_total_carbohydrates_total + $carbohydrates_total;
                    $super_total_energy_kkal = $super_total_energy_kkal + $energy_kkal;

                    $super_total_vitamin_b1 = $super_total_vitamin_b1 + $vitamin_b1;
                    $super_total_vitamin_b2 = $super_total_vitamin_b2 + $vitamin_b2;
                    $super_total_vitamin_a = $super_total_vitamin_a + $vitamin_a;
                    $super_total_vitamin_d = $super_total_vitamin_d + $vitamin_d;
                    $super_total_vitamin_c = $super_total_vitamin_c + $vitamin_c;
                    $super_total_na = $super_total_na + $na;
                    $super_total_k = $super_total_k + $k;
                    $super_total_ca = $super_total_ca + $ca;
                    $super_total_mg = $super_total_mg + $mg;
                    $super_total_p = $super_total_p + $p;
                    $super_total_fe = $super_total_fe + $fe;
                    $super_total_i = $super_total_i + $i;
                    $super_total_se = $super_total_se + $se;
                    $super_total_f = $super_total_f + $f;
                }
                $objWorkSheet->setCellValue('A' . $num, "Выход:");
                $objWorkSheet->setCellValue('C' . $num, $dish->yield * $indicator);
                $objWorkSheet->setCellValue('D' . $num, $super_total_protein);
                $objWorkSheet->setCellValue('E' . $num, $super_total_fat);
                $objWorkSheet->setCellValue('F' . $num, $super_total_carbohydrates_total);
                $objWorkSheet->setCellValue('G' . $num, $super_total_energy_kkal);

                $objWorkSheet->setCellValue('H' . $num, $super_total_vitamin_b1);
                $objWorkSheet->setCellValue('I' . $num, $super_total_vitamin_b2);
                $objWorkSheet->setCellValue('J' . $num, $super_total_vitamin_a);
                $objWorkSheet->setCellValue('K' . $num, $super_total_vitamin_d);
                $objWorkSheet->setCellValue('L' . $num, $super_total_vitamin_c);
                $objWorkSheet->setCellValue('M' . $num, $super_total_na);
                $objWorkSheet->setCellValue('N' . $num, $super_total_k);
                $objWorkSheet->setCellValue('O' . $num, $super_total_ca);
                $objWorkSheet->setCellValue('P' . $num, $super_total_mg);
                $objWorkSheet->setCellValue('Q' . $num, $super_total_p);
                $objWorkSheet->setCellValue('R' . $num, $super_total_fe);
                $objWorkSheet->setCellValue('S' . $num, $super_total_i);
                $objWorkSheet->setCellValue('T' . $num, $super_total_se);
                $objWorkSheet->setCellValue('U' . $num, $super_total_f);
                $objWorkSheet->getStyle("A" . $num . ":U" . $num)->getFont()->setBold(true);
                $num++;
                $num++;

                $objWorkSheet = $document->getActiveSheet($cat_count);
            }
        }
    }

        /*$document->createSheet(1);
        $newSheet = $document->getActiveSheet(1);
        $newSheet->setTitle('111');

        $document->createSheet(2);
        $newSheet2 = $document->getActiveSheet(2);
        $newSheet2->setTitle('222');*/
        //First sheet
         //$sheet = $document->getActiveSheet();
        //Start adding next sheets
        //$i=0; while ($i < 10) {
            // Add new sheet
        //$objWorkSheet = $document->createSheet($i);
        //Setting index when creating
        //Write cells
        //$objWorkSheet->setCellValue('A1', 'Hello'.$i) ->setCellValue('B2', 'world!') ->setCellValue('C1', 'Hello') ->setCellValue('D2', 'world!');
        // Rename sheet
        //$objWorkSheet->setTitle("$i"); $i++; }

        $filename = 'generator_' . date('Y_m_d_H_i', time()) . '.xlsx';
        header('Content-type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public');
        $objWriter = \PHPExcel_IOFactory::createWriter($document, 'Excel2007');
        $objWriter->save('php://output');
        exit;

    }
}
