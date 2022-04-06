<?php

namespace backend\controllers;

use common\models\City;
use common\models\Days;
use common\models\MenuSearch;
use common\models\MenusOrgRpnMinobr;
use common\models\MenusSend;
use common\models\NutritionApplications;
use common\models\NutritionInfo;
use common\models\Organization;
use common\models\SelectOrgForm;
use Yii;
use common\models\Menus;
use common\models\MenusNutrition;
use common\models\MenusDays;
use common\models\MenusDishes;
use common\models\MenuForm;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * MenusController implements the CRUD actions for Menus model.
 */
class MenusController extends Controller
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


    public function actionIndex()
    {

        $dataProvider = new ActiveDataProvider([
            'query' => Menus::find()->where(['status_archive' => 0, 'organization_id' => Yii::$app->user->identity->organization_id]),
            'pagination' => [
                'pageSize' => 15,
            ],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionArchive()
    {

        if(Yii::$app->user->can('admin')){
            $searchModel = new MenuSearch();
            $search = Yii::$app->request->queryParams;

            $dataProvider = $searchModel->search($search);
            return $this->render('archive', [
                'dataProvider' => $dataProvider,
                'searchModel' => $searchModel,
            ]);
        }

        if(Yii::$app->user->can('rospotrebnadzor_nutrition') || Yii::$app->user->can('minobr') || Yii::$app->user->can('subject_minobr')){
            $dataProvider = new ActiveDataProvider([
                'query' => Menus::find()->where(['status_archive' => 1, 'show_indicator' => [2, 3, 4, 5, 6, 7, 8, 9]])->orWhere(['organization_id' => Yii::$app->user->identity->organization_id, 'status_archive' => 1]),
            ]);
        }

        if(Yii::$app->user->can('medicine_director')){
            $dataProvider = new ActiveDataProvider([
                'query' => Menus::find()->where(['status_archive' => 1, 'show_indicator' => [2, 3, 4, 5, 6, 7, 8, 9]])->orWhere(['organization_id' => Yii::$app->user->identity->organization_id, 'status_archive' => 1]),
            ]);
        }

        if(Yii::$app->user->can('school_director') || Yii::$app->user->can('food_director') || Yii::$app->user->can('foodworker')|| Yii::$app->user->can('medic')){
            $dataProvider = new ActiveDataProvider([
                'query' => Menus::find()->where(['status_archive' => 1, 'show_indicator' => [2, 3, 8]])->orWhere(['organization_id' => Yii::$app->user->identity->organization_id, 'status_archive' => 1]),
            ]);
        }

        if(Yii::$app->user->can('internat_director')){
            $dataProvider = new ActiveDataProvider([
                'query' => Menus::find()->where(['status_archive' => 1, 'show_indicator' => [2, 5, 9]])->orWhere(['organization_id' => Yii::$app->user->identity->organization_id, 'status_archive' => 1]),
            ]);
        }

        if(Yii::$app->user->can('kindergarten_director')){
            $dataProvider = new ActiveDataProvider([
                'query' => Menus::find()->where(['status_archive' => 1, 'show_indicator' => [2, 4, 8]])->orWhere(['organization_id' => Yii::$app->user->identity->organization_id, 'status_archive' => 1]),
            ]);
        }

        if(Yii::$app->user->can('camp_director')){
            $dataProvider = new ActiveDataProvider([
                'query' => Menus::find()->where(['status_archive' => 1, 'show_indicator' => [2, 7, 9]])->orWhere(['organization_id' => Yii::$app->user->identity->organization_id, 'status_archive' => 1]),
            ]);
        }

        if(Yii::$app->user->can('nutrition_director')){
            $dataProvider = new ActiveDataProvider([
                'query' => Menus::find()->where(['status_archive' => 1, 'show_indicator' => [2, 3, 4, 5, 6, 7, 8, 9]])->orWhere(['organization_id' => Yii::$app->user->identity->organization_id, 'status_archive' => 1]),
            ]);
        }
		//Исключение для id270 организации, одновременно показать меню интернатов и школ
        if(Yii::$app->user->identity->organization_id == 270 ){
            $dataProvider = new ActiveDataProvider([
                'query' => Menus::find()->where(['status_archive' => 1, 'show_indicator' => [2, 3, 5, 8, 9]])->orWhere(['organization_id' => Yii::$app->user->identity->organization_id, 'status_archive' => 1]),
            ]);
        }

        if(Yii::$app->user->can('food_director')){
            $dataProvider = new ActiveDataProvider([
                'query' => Menus::find()->where(['status_archive' => 1, 'show_indicator' => [2, 3, 8, 7, 9, 4]])->orWhere(['organization_id' => Yii::$app->user->identity->organization_id, 'status_archive' => 1]),
            ]);
        }


        return $this->render('archive', [
            'dataProvider' => $dataProvider,
            //'dataProvider2' => $dataProvider2,
        ]);
    }


    public function actionUsed()
    {

        $dataProvider = new ActiveDataProvider([
            'query' => Menus::find()->where(['status_archive' => 1]),
            'pagination' => [
                'pageSize' => 15,
            ],
        ]);

        return $this->render('used', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Menus model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $menus = Menus::findOne($id);
        $menus_days = MenusDays::find()->where(['menu_id' => $id])->all();
        $menus_nutrition = MenusNutrition::find()->where(['menu_id' => $id])->all();

        return $this->render('view', [
            'menus' => $menus,
            'menus_days' => $menus_days,
            'menus_nutrition' => $menus_nutrition,
        ]);
    }

    public function actionViewUsed($id)
    {
        $menus = Menus::findOne($id);
        $menus_days = MenusDays::find()->where(['menu_id' => $id])->all();
        $menus_nutrition = MenusNutrition::find()->where(['menu_id' => $id])->all();

        return $this->render('view-used', [
            'menus' => $menus,
            'menus_days' => $menus_days,
            'menus_nutrition' => $menus_nutrition,
        ]);
    }


    public function actionCreate()
    {
        $model = new MenuForm();

        if(Yii::$app->request->post()){
            $mas_days = array();
            $mas_nutritions = array();
            $post = Yii::$app->request->post()['MenuForm'];
            $model2 = new Menus();
            //ЕСЛИ РОСПОТРЕБ ИЛИ МИНОБР ПРИСТУПИЛИ К СОЗДАНИЮ СВОИХ МЕНЮ
            if(Yii::$app->user->can('rospotrebnadzor_nutrition') ||Yii::$app->user->can('minobr'))
            {
                //print_r($post['odno_vnogodnev']);exit;
                //ПРОВЕРЯЕМ: ОНО ОДНОДНЕНВОЕ?В ПЕРЕМЕНУЮ odno_vnogodnev ПОЛОЖИЛИ УКАЗАТЕЛЬ ОДНОДНЕВНОСТИ ИЛИ МНОГОДНЕВНОСТИ
                if($post['odno_vnogodnev'] == 1){
                    $model2->parent_id = 0;
                    $model2->show_indicator = 0;
                    $model2->organization_id = Yii::$app->user->identity->organization_id;
                    $model2->type_org_id = Yii::$app->request->post()['MenuForm']['type_org_id'];
                    $model2->feeders_characters_id = Yii::$app->request->post()['MenuForm']['characters'];
                    $model2->age_info_id = Yii::$app->request->post()['MenuForm']['age'];
                    $model2->name = Yii::$app->request->post()['MenuForm']['name'];
                    $model2->cycle = 1;
                    $model2->date_start = strtotime(Yii::$app->request->post()['MenuForm']['date_start']);
                    $model2->date_end = strtotime($post['date_start']);
                    $model2->status_archive = 0;
                    //ГОВОРИМ ЧТО У НАС ТОЛЬКО ОДИН ДЕНЬ И ЭТО ПОНЕДЕЛЬНИК
                    $day_of_week = date("w", strtotime($post['date_start']));//День недели выбранной даты
                    if ($day_of_week == 0)
                    {
                        $day_of_week = 7;
                    }
                    $mas_days[] = $day_of_week;
                }
                else{
                    //ИНАЧЕ БУДЕТ МНОГО ДНЕЙ
                    $model2->parent_id = 0;
                    $model2->show_indicator = 0;
                    $model2->type_org_id = Yii::$app->request->post()['MenuForm']['type_org_id'];
                    $model2->type_org_id = Organization::findOne(Yii::$app->user->identity->organization_id)->type_org;
                    $model2->feeders_characters_id = Yii::$app->request->post()['MenuForm']['characters'];
                    $model2->age_info_id = Yii::$app->request->post()['MenuForm']['age'];
                    $model2->name = Yii::$app->request->post()['MenuForm']['name'];
                    $model2->cycle = Yii::$app->request->post()['MenuForm']['cycles'];
                    $model2->date_start = strtotime(Yii::$app->request->post()['MenuForm']['date_start']);
                    $model2->date_end = strtotime(Yii::$app->request->post()['MenuForm']['date_end']);
                    $model2->status_archive = 0;
                }
            //ЕСЛИ ПОЛЬЗОВАТЕЛЬ ЯВЛЯЕТСЯ ЛЮБЫМ ДРУГИМ ЮЗЕРОМ
            }else
            {


                $model2->parent_id = 0;
                $model2->show_indicator = 0;
                $model2->organization_id = Yii::$app->user->identity->organization_id;
                if(Yii::$app->user->can('rospotrebnadzor_camp') || Yii::$app->user->can('rospotrebnadzor_nutrition') || Yii::$app->user->can('admin') || Yii::$app->user->can('food_director') || Yii::$app->user->can('subject_minobr')  || Yii::$app->user->can('minobr')   || Yii::$app->user->can('hidden_user'))
                {
                    $model2->type_org_id = Yii::$app->request->post()['MenuForm']['type_org_id'];
                }else{
                    $model2->type_org_id = Organization::findOne(Yii::$app->user->identity->organization_id)->type_org;
                }

                $model2->feeders_characters_id = Yii::$app->request->post()['MenuForm']['characters'];
                $model2->age_info_id = Yii::$app->request->post()['MenuForm']['age'];
                $model2->name = Yii::$app->request->post()['MenuForm']['name'];
                $model2->cycle = Yii::$app->request->post()['MenuForm']['cycles'];
                $model2->date_start = strtotime(Yii::$app->request->post()['MenuForm']['date_start']);
                $model2->date_end = strtotime(Yii::$app->request->post()['MenuForm']['date_end']);
                $model2->status_archive = 0;
            }
            //БЛОК ОБЩЕЙ ИНФОРМАЦИИ И ДАННЫХ СКОЛЬКО ДНЕЙ В МЕНЮ И ПРИЕМОВ ПИЩИ
            if($model2->save()){


                if($post['days1'] == 1){
                    $mas_days[] = 1;
                }
                if($post['days2'] == 1){
                    $mas_days[] = 2;
                }
                if($post['days3'] == 1){
                    $mas_days[] = 3;
                }
                if($post['days4'] == 1){
                    $mas_days[] = 4;
                }
                if($post['days5'] == 1){
                    $mas_days[] = 5;
                }
                if($post['days6'] == 1){
                    $mas_days[] = 6;
                }
                if($post['days7'] == 1){
                    $mas_days[] = 7;
                }

                //nutrition
                if($post['nutrition1'] == 1){
                    $mas_nutritions[] = 1;//ЦИФРА НАПРОТИВ МАССИВА ОБОЗНАЧАЕТ ID ПРИЕМА ПИЩИ В ТАБЛИЦЕ NUTRITION_INFO!!!
                }
                if($post['nutrition2'] == 1){
                    $mas_nutritions[] = 2;
                }
                if($post['nutrition3'] == 1){
                    $mas_nutritions[] = 3;
                }
                if($post['nutrition4'] == 1){
                    $mas_nutritions[] = 4;
                }
                if($post['nutrition5'] == 1){
                    $mas_nutritions[] = 5;
                }
                if($post['nutrition6'] == 1){
                    $mas_nutritions[] = 6;
                }

                foreach($mas_days as $day){
                    $model3 = new MenusDays();
                    $model3->menu_id = $model2->id;
                    $model3->days_id = $day;
                    $model3->save(false);
                }

                foreach ($mas_nutritions as $nutrition){
                    $model4 = new MenusNutrition();
                    $model4->menu_id = $model2->id;
                    $model4->nutrition_id = $nutrition;
                    $model4->save(false);
                }

                //ЗАПИСЫВАЕМ В БД ДЛЯ КАКОЙ ОРГАНИЗАЦИИ РПН СОЗДАЛ МЕНЮ. А ЕСЛИ ОН НЕ ВЫЮРАЛ ОРГ ТО ТОГДА НИЧЕ НЕ БУДЕТ
                if(Yii::$app->user->can('rospotrebnadzor_nutrition') ||Yii::$app->user->can('minobr'))
                {
                    if($post['organization_id'] > 0){
                        $rpn_minobr = new MenusOrgRpnMinobr();
                        $rpn_minobr->menu_id = $model2->id;
                        $rpn_minobr->organization_id = $model2->organization_id;
                        $rpn_minobr->save();
                    }
                }
                Yii::$app->session->setFlash('success', "Меню успешно сохранено");
                return $this->redirect(['menus/index']);

            }
            else{
                Yii::$app->session->setFlash('error', "Произошла ошибка при создании меню. Данные не были сохранены");
                return $this->redirect(['menus/create']);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }



    public function actionCreateArchive()
    {
        $model = new MenuForm();

        if(Yii::$app->request->post()){
            $post = Yii::$app->request->post()['MenuForm'];
            $model2 = new Menus();
            $model2->show_indicator = 1;
            $model2->parent_id = 0;
            $model2->organization_id = Yii::$app->user->identity->organization_id;
            $model2->type_org_id = Yii::$app->request->post()['MenuForm']['type_org_id'];
            $model2->show_indicator = Yii::$app->request->post()['MenuForm']['show_indicator'];
            $model2->feeders_characters_id = Yii::$app->request->post()['MenuForm']['characters'];
            $model2->age_info_id = Yii::$app->request->post()['MenuForm']['age'];
            $model2->name = Yii::$app->request->post()['MenuForm']['name'];
            $model2->cycle = Yii::$app->request->post()['MenuForm']['cycles'];
            $model2->date_start = strtotime(Yii::$app->request->post()['MenuForm']['date_start']);
            $model2->date_end = strtotime(Yii::$app->request->post()['MenuForm']['date_end']);
            $model2->status_archive = 1;
            if($model2->save()){
                $mas_days = array();
                $mas_nutritions = array();

                if($post['days1'] == 1){
                    $mas_days[] = 1;
                }
                if($post['days2'] == 1){
                    $mas_days[] = 2;
                }
                if($post['days3'] == 1){
                    $mas_days[] = 3;
                }
                if($post['days4'] == 1){
                    $mas_days[] = 4;
                }
                if($post['days5'] == 1){
                    $mas_days[] = 5;
                }
                if($post['days6'] == 1){
                    $mas_days[] = 6;
                }
                if($post['days7'] == 1){
                    $mas_days[] = 7;
                }

                //nutrition
                if($post['nutrition1'] == 1){
                    $mas_nutritions[] = 1;//ЦИФРА НАПРОТИВ МАССИВА ОБОЗНАЧАЕТ ID ПРИЕМА ПИЩИ В ТАБЛИЦЕ NUTRITION_INFO!!!
                }
                if($post['nutrition2'] == 1){
                    $mas_nutritions[] = 2;
                }
                if($post['nutrition3'] == 1){
                    $mas_nutritions[] = 3;
                }
                if($post['nutrition4'] == 1){
                    $mas_nutritions[] = 4;
                }
                if($post['nutrition5'] == 1){
                    $mas_nutritions[] = 5;
                }
                if($post['nutrition6'] == 1){
                    $mas_nutritions[] = 6;
                }

                foreach($mas_days as $day){
                    $model3 = new MenusDays();
                    $model3->menu_id = $model2->id;
                    $model3->days_id = $day;
                    $model3->save(false);
                }

                foreach ($mas_nutritions as $nutrition){
                    $model4 = new MenusNutrition();
                    $model4->menu_id = $model2->id;
                    $model4->nutrition_id = $nutrition;
                    $model4->save(false);
                }
                Yii::$app->session->setFlash('success', "Меню успешно добавлено в архив");
                return $this->redirect(['menus/archive']);
            }
            else{
                Yii::$app->session->setFlash('error', "Произошла ошибка при добавлении меню в архив. Попробуйте добавить снова");
                return $this->redirect(['menus/archive']);
            }
        }

        return $this->render('create-archive', [
            'model' => $model,
        ]);
    }



    public function actionPushArchive($id)
    {
        $model = Menus::findOne($id);
            $model2 = new Menus();
        /*$menus_dishes = MenusDishes::find()->where(['menu_id' => $id, 'date_fact_menu' => 0])->all();
        print_r($menus_dishes);
        exit;*/
            $model2->organization_id = $model->organization_id;
            //тип меню
            if(!empty($model->type_org_id)){
                $model2->type_org_id = $model->type_org_id;
            }else{
                $model2->type_org_id = 3;
            }

            $model2->parent_id = 0;
            $model2->show_indicator = 1;
            $model2->feeders_characters_id = $model->feeders_characters_id;
            $model2->age_info_id = $model->age_info_id;
            $model2->name = $model->name;
            $model2->cycle = $model->cycle;
            $model2->date_start = $model->date_start;
            $model2->date_end = $model->date_end;
            $model2->status_archive = 1;
            if($model2->save()){
                $days = MenusDays::find()->where(['menu_id' => $id])->all();
                $nutritions = MenusNutrition::find()->where(['menu_id' => $id])->all();
                $menus_dishes = MenusDishes::find()->where(['menu_id' => $id, 'date_fact_menu' => 0])->all();
                /*print_r($menus_dishes);
                exit;*/

                foreach($days as $day){
                    $model3 = new MenusDays();
                    $model3->menu_id = $model2->id;
                    $model3->days_id = $day->days_id;
                    $model3->save(false);
                }

                foreach ($nutritions as $nutrition){
                    $model4 = new MenusNutrition();
                    $model4->menu_id = $model2->id;
                    $model4->nutrition_id = $nutrition->nutrition_id;
                    $model4->save(false);
                }

                foreach ($menus_dishes as $m_dish){
                    $model5 = new MenusDishes();
                    $model5->date_fact_menu = 0;
                    $model5->menu_id = $model2->id;
                    $model5->cycle = $m_dish->cycle;
                    $model5->days_id = $m_dish->days_id;
                    $model5->nutrition_id = $m_dish->nutrition_id;
                    $model5->dishes_id = $m_dish->dishes_id;
                    $model5->yield = $m_dish->yield;
                    $model5->save();
                }
                Yii::$app->session->setFlash('success', "Меню успешно добавлено в архив. ". Html::a("Перейти в архив.", "/menus/archive"));
                return $this->redirect(['menus/index']);
            }
            else{
                Yii::$app->session->setFlash('error', "Произошла ошибка при добавлении меню в архив. Попробуйте добавить снова");
                return $this->redirect(['menus/index']);
            }
    }


    public function actionPutArchive($id)//взять меню с архива
    {
        $my_org = Organization::findOne(Yii::$app->user->identity->organization_id);
        $model = Menus::findOne($id);
        $model2 = new Menus();
        /*parent_id нужен для того чтобы знать чье меню мы берем, чтобы знать кто его родитель это нужно для отчета, т.е. каким архивным меню сколько раз воспользовались*/
        $model2->parent_id = $model->id;
        $model2->show_indicator = 0;
        $model2->organization_id = Yii::$app->user->identity->organization_id;
        if(!empty($model->type_org_id)){
            $model2->type_org_id = $model->type_org_id;
        }
        elseif($my_org->type_org != 2 && $my_org->type_org != 4  && $my_org->type_org != 1  && $my_org->type_org != 9  && $my_org->type_org != 8  && $my_org->type_org != 11){
            $model2->type_org_id = $my_org->type_org;
        }else{
            $model2->type_org_id = 3;
        }
        $model2->feeders_characters_id = $model->feeders_characters_id;
        $model2->age_info_id = $model->age_info_id;
        $model2->name = $model->name;
        $model2->cycle = $model->cycle;
        $model2->date_start = $model->date_start;
        $model2->date_end = $model->date_end;
        $model2->status_archive = 0;
        if($model2->save()){
            $days = MenusDays::find()->where(['menu_id' => $id])->all();
            $nutritions = MenusNutrition::find()->where(['menu_id' => $id])->all();
            $menus_dishes = MenusDishes::find()->where(['menu_id' => $id, 'date_fact_menu' => 0])->all();

            foreach($days as $day){
                $model3 = new MenusDays();
                $model3->menu_id = $model2->id;
                $model3->days_id = $day->days_id;
                $model3->save(false);
            }

            foreach ($nutritions as $nutrition){
                $model4 = new MenusNutrition();
                $model4->menu_id = $model2->id;
                $model4->nutrition_id = $nutrition->nutrition_id;
                $model4->save(false);
            }

            foreach ($menus_dishes as $m_dish){
                $model5 = new MenusDishes();
                $model5->date_fact_menu = 0;
                $model5->menu_id = $model2->id;
                $model5->cycle = $m_dish->cycle;
                $model5->days_id = $m_dish->days_id;
                $model5->nutrition_id = $m_dish->nutrition_id;
                $model5->dishes_id = $m_dish->dishes_id;
                $model5->yield = $m_dish->yield;
                $model5->save(false);
            }
            Yii::$app->session->setFlash('success', "Это архивное меню было успешно подгружено к Вам. ". Html::a("Перейти к своим меню.", "/menus/index"));
            return $this->redirect(['menus/archive']);
        }
        else{
            Yii::$app->session->setFlash('error', "Произошла ошибка при подгрузке меню. Попробуйте снова");
            return $this->redirect(['menus/archive']);
        }
    }




    /**
     * Updates an existing Menus model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        //$model = $this->findModel($id);
        $my_org = Organization::findOne(Yii::$app->user->identity->organization_id);
        $model = new MenuForm();
        $menus = Menus::findOne($id);
        $menus_days = MenusDays::find()->where(['menu_id' => $id])->all();
        $menus_nutrition = MenusNutrition::find()->where(['menu_id' => $id])->all();


        if (Yii::$app->request->post()) {
            /*print_r(date("d.m.Y", strtotime(Yii::$app->request->post()['MenuForm']['date_start'])));
            exit;*/
            $post = Yii::$app->request->post()['MenuForm'];
            $model2 = Menus::findOne($id);
			if($model2->cycle > Yii::$app->request->post()['MenuForm']['cycles']){
               $fordeletes = MenusDishes::find()->where(['menu_id' => $id])->andWhere(['>=', 'cycle', $model2->cycle])->all();
               foreach($fordeletes as $f){
                   $f->delete();
               }
            }

            if(!empty(Yii::$app->request->post()['MenuForm']['type_org_id'])){
                $model2->type_org_id = Yii::$app->request->post()['MenuForm']['type_org_id'];
            }
            elseif($my_org->type_org != 2 && $my_org->type_org != 4  && $my_org->type_org != 1  && $my_org->type_org != 9  && $my_org->type_org != 8  && $my_org->type_org != 11){
                $model2->type_org_id = $my_org->type_org;
            }else{
                $model2->type_org_id = 3;
            }

            $model2->feeders_characters_id = Yii::$app->request->post()['MenuForm']['characters'];
            $model2->age_info_id = Yii::$app->request->post()['MenuForm']['age'];
            $model2->name = Yii::$app->request->post()['MenuForm']['name'];
            $model2->cycle = Yii::$app->request->post()['MenuForm']['cycles'];
            $model2->date_start = strtotime(Yii::$app->request->post()['MenuForm']['date_start']);
            $model2->date_end = strtotime(Yii::$app->request->post()['MenuForm']['date_end']);
            if($model2->save()){

                $old_menus_days = MenusDays::deleteAll('menu_id =:id', [':id' => $id]);
                //$old_menus_days = MenusDays::find()->where(['menu_id' => $id])->all();
                //$old_menus_days->delete();

                $old_menus_nutrition = MenusNutrition::deleteAll('menu_id =:id', [':id' => $id]);
                //$old_menus_nutrition->delete();

                $mas_days = array();
                $mas_nutritions = array();

                if($post['days1'] == 1){
                    $mas_days[] = 1;
                }
                if($post['days2'] == 1){
                    $mas_days[] = 2;
                }
                if($post['days3'] == 1){
                    $mas_days[] = 3;
                }
                if($post['days4'] == 1){
                    $mas_days[] = 4;
                }
                if($post['days5'] == 1){
                    $mas_days[] = 5;
                }
                if($post['days6'] == 1){
                    $mas_days[] = 6;
                }
                if($post['days7'] == 1){
                    $mas_days[] = 7;
                }

                //nutrition
                if($post['nutrition1'] == 1){
                    $mas_nutritions[] = 1;//ЦИФРА НАПРОТИВ МАССИВА ОБОЗНАЧАЕТ ID ПРИЕМА ПИЩИ В ТАБЛИЦЕ NUTRITION_INFO!!!
                }
                if($post['nutrition2'] == 1){
                    $mas_nutritions[] = 2;
                }
                if($post['nutrition3'] == 1){
                    $mas_nutritions[] = 3;
                }
                if($post['nutrition4'] == 1){
                    $mas_nutritions[] = 4;
                }
                if($post['nutrition5'] == 1){
                    $mas_nutritions[] = 5;
                }
                if($post['nutrition6'] == 1){
                    $mas_nutritions[] = 6;
                }

                foreach($mas_days as $day){
                    $model3 = new MenusDays();
                    $model3->menu_id = $model2->id;
                    $model3->days_id = $day;
                    $model3->save(false);
                }

                foreach ($mas_nutritions as $nutrition){
                    $model4 = new MenusNutrition();
                    $model4->menu_id = $model2->id;
                    $model4->nutrition_id = $nutrition;
                    $model4->save(false);
                }
                /*При снятии чекбоксов удаление всех блюд из данного приема пищи или меню*/
                $my_menus_dishes = MenusDishes::find()->where(['menu_id' =>$model2->id])->distinct()->all();
                $count = 0;
                foreach ($my_menus_dishes as $m_m_dish){
                    if (!in_array($m_m_dish->nutrition_id, $mas_nutritions)) {
                        //$count = $count + 1;
                        $delete = MenusDishes::findOne($m_m_dish->id);
                        if(!empty($delete)){
                            $delete->delete();
                        }
                        
                    }
                }
                foreach ($my_menus_dishes as $m_m_dish){
                    if (!in_array($m_m_dish->days_id, $mas_days)) {
                        //$count = $count + 1;
                        $delete = MenusDishes::findOne($m_m_dish->id);
                        if(!empty($delete)){
                            $delete->delete();
                        }
                    }
                }
                /*Конец удаления*/
                /*print_r($count);
                exit;*/

                Yii::$app->session->setFlash('success', "Меню успешно сохранено");
                return $this->redirect(['index']);
            }
            else{
                Yii::$app->session->setFlash('error', "Произошла ошибка при создании меню. Данные не были сохранены");
                return $this->redirect(['menus/create']);
            }
        }

        return $this->render('update', [
            'model' => $model,
            'menus' => $menus,
            'menus_days' => $menus_days,
            'menus_nutrition' => $menus_nutrition,
        ]);
    }


    public function actionSettingArchive($id)
    {
        //$model = $this->findModel($id);
        $my_org = Organization::findOne(Yii::$app->user->identity->organization_id);
        $model = new Menus();
        $menus = Menus::findOne($id);
        $menus_days = MenusDays::find()->where(['menu_id' => $id])->all();
        $menus_nutrition = MenusNutrition::find()->where(['menu_id' => $id])->all();


        if (Yii::$app->request->post()) {
            /*print_r(Yii::$app->request->post());
            exit;*/
            $post = Yii::$app->request->post()['Menus'];
            $model2 = Menus::findOne($id);

            if(!empty($post['type_org_id'])){
                $model2->type_org_id = $post['type_org_id'];
            }
            elseif($my_org->type_org != 2 && $my_org->type_org != 4  && $my_org->type_org != 1  && $my_org->type_org != 9  && $my_org->type_org != 8  && $my_org->type_org != 11){
                $model2->type_org_id = $my_org->type_org;
            }else{
                $model2->type_org_id = 3;
            }


            $model2->feeders_characters_id = $post['feeders_characters_id'];
            $model2->age_info_id = $post['age_info_id'];
            $model2->show_indicator = $post['show_indicator'];
            $model2->name = $post['name'];
            $model2->cycle = $post['cycle'];
            $model2->date_start = strtotime($post['date_start']);
            $model2->date_end = strtotime($post['date_end']);
            if($model2->save()){
                Yii::$app->session->setFlash('success', "Изменения сохранены!");
                return $this->redirect(['archive']);
            }
            else{
                Yii::$app->session->setFlash('error', "Произошла ошибка при редактировании. Данные не были сохранены");
                return $this->redirect(['menus/setting-archive?id='.$id]);
            }
        }

        return $this->render('setting-archive', [
            'model' => $model,
            'menus' => $menus,
            'menus_days' => $menus_days,
            'menus_nutrition' => $menus_nutrition,
        ]);
    }

    /**
     * Deletes an existing Menus model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        /*Массовое удаление меню из всех таблиц при удалении меню*/
        $this->findModel($id)->delete();



        return $this->redirect(['index']);
    }

    public function actionDeleteArchive($id)
    {
        /*Массовое удаление меню из всех таблиц при удалении меню*/
        $this->findModel($id)->delete();

        $old_menus_days = MenusDays::deleteAll('menu_id =:id', [':id' => $id]);

        $old_menus_nutrition = MenusNutrition::deleteAll('menu_id =:id', [':id' => $id]);

        $old_menus_dishes = MenusDishes::deleteAll('menu_id =:id', [':id' => $id]);
        Yii::$app->session->setFlash('success', "Меню успешно удалено!");

        return $this->redirect(['archive']);
    }

    /**
     * Finds the Menus model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Menus the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Menus::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }



    public function actionViewMenus($id)
    {
        $model = new MenusDishes();


            $menus_nutrition_id = MenusNutrition::find()->where(['menu_id' => $id])->all();//ВСЕ ПРИЕМЫ ПИЩИ КОНКРЕТНОГО МЕНЮ
            $ids = [];
            foreach ($menus_nutrition_id as $m_id)
            {
                $ids[] = $m_id->nutrition_id;//МАССИВ ID ПРИЕМОВ ПИЩИ КЛНКРЕЬНОГО МЕНЮ
            }
            $nutritions = NutritionInfo::find()->where(['id' => $ids])->all();//ОБЪЕКТ С НАБОРОМ ПРИЕМОВ ПИЩИ ИЗ БАЗЫ С УЧЕТОМ НАШИХ АЙДИ
            $menus_dishes = MenusDishes::find()->where(['date_fact_menu' => 0, 'menu_id' => $id/*, 'cycle' => $post['cycle']*/])->orderby(['cycle' => SORT_ASC, 'days_id' => SORT_ASC, 'nutrition_id' => SORT_ASC])->all();


            $menus_days_id = MenusDays::find()->where(['menu_id' => $id])->all();//ВСЕ ДНИ КОНКРЕТНОГО МЕНЮ
            $days_ids = [];
            foreach ($menus_days_id as $day_id)
            {
                $days_ids[] = $day_id->days_id;//МАССИВ ID ДНЕЙ КЛНКРЕЬНОГО МЕНЮ
            }

            $days = Days::find()->where(['id' => $days_ids])->all();//ОБЪЕКТ С НАБОРОМ ПРИЕМОВ ПИЩИ ИЗ БАЗЫ С УЧЕТОМ НАШИХ АЙДИ
            $get = $id;
       // print_r($days);
            return $this->render('view-menus', [
                'menus_dishes' => $menus_dishes,
                'nutritions' => $nutritions,
                'days' => $days,
                'get' => $get,
                'model' => $model,
            ]);


    }



    //СДЕЛАТЬ КОПИЮ МЕНЮ
    public function actionCopy($id)
    {
        $my_org = Organization::findOne(Yii::$app->user->identity->organization_id);
        $model = Menus::findOne($id);
        $model2 = new Menus();

        $model2->organization_id = $model->organization_id;

        if(!empty($post['type_org_id'])){
            $model2->type_org_id = $post['type_org_id'];
        }
        elseif($my_org->type_org != 2 && $my_org->type_org != 4  && $my_org->type_org != 1  && $my_org->type_org != 9  && $my_org->type_org != 8  && $my_org->type_org != 11){
            $model2->type_org_id = $my_org->type_org;
        }else{
            $model2->type_org_id = 3;
        }

        $model2->parent_id = 0;
        $model2->show_indicator = 0;
        $model2->feeders_characters_id = $model->feeders_characters_id;
        $model2->age_info_id = $model->age_info_id;
        $model2->name = $model->name.'(КОПИЯ)';
        $model2->cycle = $model->cycle;
        $model2->date_start = $model->date_start;
        $model2->date_end = $model->date_end;
        $model2->status_archive = 0;
        if($model2->save()){
            $days = MenusDays::find()->where(['menu_id' => $id])->all();
            $nutritions = MenusNutrition::find()->where(['menu_id' => $id])->all();
            $menus_dishes = MenusDishes::find()->where(['menu_id' => $id, 'date_fact_menu' => 0])->all();


            foreach($days as $day){
                $model3 = new MenusDays();
                $model3->menu_id = $model2->id;
                $model3->days_id = $day->days_id;
                $model3->save(false);
            }

            foreach ($nutritions as $nutrition){
                $model4 = new MenusNutrition();
                $model4->menu_id = $model2->id;
                $model4->nutrition_id = $nutrition->nutrition_id;
                $model4->save(false);
            }

            foreach ($menus_dishes as $m_dish){
                $model5 = new MenusDishes();
                $model5->date_fact_menu = 0;
                $model5->menu_id = $model2->id;
                $model5->cycle = $m_dish->cycle;
                $model5->days_id = $m_dish->days_id;
                $model5->nutrition_id = $m_dish->nutrition_id;
                $model5->dishes_id = $m_dish->dishes_id;
                $model5->yield = $m_dish->yield;
                $model5->save();
            }
            Yii::$app->session->setFlash('success', "Копия меню успешно создана.");
            return $this->redirect(['menus/index']);
        }
        else{
            Yii::$app->session->setFlash('error', "Произошла ошибка копировании меню");
            return $this->redirect(['menus/index']);
        }
    }


    public function actionReportMinobrRpn()
    {
        $model = new SelectOrgForm();

        if (Yii::$app->request->post())
        {
            $post = Yii::$app->request->post()['SelectOrgForm'];

            if (Yii::$app->user->can('minobr') || Yii::$app->user->can('rospotrebnadzor_nutrition'))
            {

                if ($post['organization'] == 0 && $post['municipality_id'] == 0)
                {
                    $organization_id = Yii::$app->user->identity->organization_id;
                    $region_id = Organization::findOne($organization_id)->region_id;
                    $organizations = Organization::find()->where(['type_org' => 3, 'region_id' => $region_id])->andWhere(['!=', 'id', 7])->all();
                    $org_ids = [];
                    foreach ($organizations as $organization)
                    {
                        $org_ids[] = $organization->id;
                    }
					//print_r($org_ids);exit;
                    $dataProvider = new ActiveDataProvider([
                        'query' => Organization::find()->where(['id' => $org_ids]),
                        'pagination' => [
                            //'forcePageParam' => false,
                            //'pageSizeParam' => false,
                            'pageSize' => 2000
                        ]

                    ]);
                }

                elseif ($post['organization'] == 0 && $post['municipality_id'] != 0)
                {
                    $organization_id = Yii::$app->user->identity->organization_id;
                    $region_id = Organization::findOne($organization_id)->region_id;
                    $organizations = Organization::find()->where(['type_org' => 3, 'region_id' => $region_id, 'municipality_id' => $post['municipality_id']])->andWhere(['!=', 'id', 7])->all();
                    $org_ids = [];
                    foreach ($organizations as $organization)
                    {
                        $org_ids[] = $organization->id;
                    }
                    $dataProvider = new ActiveDataProvider([
                        'query' => Organization::find()->where(['id' => $org_ids]),
                        'pagination' => [
                            //'forcePageParam' => false,
                            //'pageSizeParam' => false,
                            'pageSize' => 2000
                        ]

                    ]);
                }
                else
                {
                    $dataProvider = new ActiveDataProvider([
                        'query' => Organization::find()->where(['id' => $post['organization']]),
                        'pagination' => [
                            //'forcePageParam' => false,
                            //'pageSizeParam' => false,
                            'pageSize' => 2000
                        ]
                    ]);
                }
            }
            if (Yii::$app->user->can('subject_minobr'))
            {

                //print_r(Yii::$app->request->post());exit;
                if ($post['organization'] == 0)
                {
                    $organization_id = Yii::$app->user->identity->organization_id;
                    $org = Organization::findOne($organization_id);
                    if($post['city_id'] != 0){
                        $organizations = Organization::find()->where(['type_org' => 3, 'city_id' => $post['city_id']])->all();
                    }else{
                        $organizations = Organization::find()->where(['type_org' => 3, 'municipality_id' => $org->municipality_id])->andWhere(['!=', 'id', 7])->all();

                    }
                    $org_ids = [];

                    foreach ($organizations as $organization)
                    {
                        $org_ids[] = $organization->id;
                    }
                    //print_r($org_ids);exit;
                    $dataProvider = new ActiveDataProvider([
                        'query' => Organization::find()->where(['id' => $org_ids]),
                        'pagination' => [
                            //'forcePageParam' => false,
                            //'pageSizeParam' => false,
                            'pageSize' => 2000
                        ]

                    ]);
                }
                else{
                    $dataProvider = new ActiveDataProvider([
                        'query' => Organization::find()->where(['id' => $post['organization']]),
                        'pagination' => [
                            'pageSize' => 2000
                        ]
                    ]);
                }
            }
            return $this->render('report-minobr-rpn', [
                'dataProvider' => $dataProvider,
                'model' => $model,
                'post' => $post,
            ]);
        }

        return $this->render('report-minobr-rpn', [
            'model' => $model,
        ]);


    }


	public function actionReportMinobrRpnVnesen()
    {
        $model = new Menus();

        if (Yii::$app->request->post())
        {
            $post = Yii::$app->request->post()['Menus'];
            if (Yii::$app->user->can('minobr')|| Yii::$app->user->can('rospotrebnadzor_nutrition'))
            {

                if ($post['organization_id'] == 0 && $post['parent_id'] == 0)
                {
                    $organization_id = Yii::$app->user->identity->organization_id;
                    $region_id = Organization::findOne($organization_id)->region_id;
                    $organizations = Organization::find()->where(['type_org' => 3, 'region_id' => $region_id])->andWhere(['!=', 'id', 7])->all();
                    $org_ids = [];
                    foreach ($organizations as $organization)
                    {
                        $org_ids[] = $organization->id;
                    }
                    $dataProvider = new ActiveDataProvider([
                        'query' => Organization::find()->where(['id' => $org_ids]),
                        'pagination' => [
                            //'forcePageParam' => false,
                            //'pageSizeParam' => false,
                            'pageSize' => 2000
                        ]

                    ]);
                }

                elseif ($post['organization_id'] == 0 && $post['parent_id'] != 0)
                {
                    $organization_id = Yii::$app->user->identity->organization_id;
                    $region_id = Organization::findOne($organization_id)->region_id;
                    $organizations = Organization::find()->where(['type_org' => 3, 'region_id' => $region_id, 'municipality_id' => $post['parent_id']])->andWhere(['!=', 'id', 7])->all();
                    $org_ids = [];
                    foreach ($organizations as $organization)
                    {
                        $org_ids[] = $organization->id;
                    }
                    $dataProvider = new ActiveDataProvider([
                        'query' => Organization::find()->where(['id' => $org_ids]),
                        'pagination' => [
                            //'forcePageParam' => false,
                            //'pageSizeParam' => false,
                            'pageSize' => 2000
                        ]

                    ]);
                }
                else
                {
                    $dataProvider = new ActiveDataProvider([
                        'query' => Organization::find()->where(['id' => $post['organization_id']]),
                        'pagination' => [
                            //'forcePageParam' => false,
                            //'pageSizeParam' => false,
                            'pageSize' => 2000
                        ]
                    ]);
                }
            }
            if (Yii::$app->user->can('subject_minobr'))
            {
                //print_r(Yii::$app->request->post());exit;
                if ($post['organization_id'] == 0)
                {
                    $organization_id = Yii::$app->user->identity->organization_id;
                    $org = Organization::findOne($organization_id);
                    $organizations = Organization::find()->where(['type_org' => 3, 'municipality_id' => $org->municipality_id])->andWhere(['!=', 'id', 7])->all();
                    $org_ids = [];

                    foreach ($organizations as $organization)
                    {
                        $org_ids[] = $organization->id;
                    }
                    //print_r($org_ids);exit;
                    $dataProvider = new ActiveDataProvider([
                        'query' => Organization::find()->where(['id' => $org_ids]),
                        'pagination' => [
                            //'forcePageParam' => false,
                            //'pageSizeParam' => false,
                            'pageSize' => 2000
                        ]

                    ]);
                }
                else{
                    $dataProvider = new ActiveDataProvider([
                        'query' => Organization::find()->where(['id' => $post['organization_id']]),
                        'pagination' => [
                            'pageSize' => 2000
                        ]
                    ]);
                }
            }
            return $this->render('report-minobr-rpn-vnesen', [
                'dataProvider' => $dataProvider,
                'model' => $model,
                'post' => $post,
            ]);
        }

        return $this->render('report-minobr-rpn-vnesen', [
            'model' => $model,
        ]);


    }

    public function actionMonitoring()
    {
        ini_set('max_execution_time', 3600);
        ini_set('memory_limit', '5092M');
        $model = new Menus();

        if (Yii::$app->request->post())
        {
            $post = Yii::$app->request->post()['Menus'];
            if (Yii::$app->user->can('minobr') || Yii::$app->user->can('rospotrebnadzor_nutrition'))
            {

                if ($post['parent_id'] == 0)
                {
                    $organization_id = Yii::$app->user->identity->organization_id;
                    $region_id = Organization::findOne($organization_id)->region_id;
                    $organizations = Organization::find()->where(['type_org' => 3, 'region_id' => $region_id])->all();

                }

                elseif ($post['parent_id'] != 0)
                {
                    $organization_id = Yii::$app->user->identity->organization_id;
                    $region_id = Organization::findOne($organization_id)->region_id;
                    $organizations = Organization::find()->where(['type_org' => 3, 'municipality_id' => $post['parent_id']])->all();

                }

            }
            if (Yii::$app->user->can('subject_minobr'))
            {

                $organization_id = Yii::$app->user->identity->organization_id;
                $org = Organization::findOne($organization_id);
                $organizations = Organization::find()->where(['type_org' => 3, 'municipality_id' => $org->municipality_id])->all();
                //print_r($organizations);exit;
                $post['parent_id'] = $org->municipality_id;

            }

            if (Yii::$app->user->can('food_director'))
            {
                /*print_r(123);
                exit;*/
                $ids = [];
                $nutrition_aplications = NutritionApplications::find()->where(['sender_org_id' => Yii::$app->user->identity->organization_id, 'status' => 1])->orWhere(['reciever_org_id' => Yii::$app->user->identity->organization_id, 'status' => 1])->all();
                foreach ($nutrition_aplications as $n_aplication)
                {
                    if ($n_aplication->sender_org_id != Yii::$app->user->identity->organization_id)
                    {
                        $ids[] = $n_aplication->sender_org_id;
                    }
                    if ($n_aplication->reciever_org_id != Yii::$app->user->identity->organization_id)
                    {
                        $ids[] = $n_aplication->reciever_org_id;
                    }
                }


                $organization_id = Yii::$app->user->identity->organization_id;
                $org = Organization::findOne($organization_id);
                $organizations = Organization::find()->where(['id' => $ids])->all();
                //print_r($organizations);exit;
                $post['parent_id'] = $org->municipality_id;

            }
            return $this->render('monitoring', [
                'post_orgs' => $organizations,
                'model' => $model,
                'post' => $post,
            ]);
        }


        return $this->render('monitoring', [
            'model' => $model,
        ]);


    }


    public function actionMyMonitoring()
    {
        $model = new Menus();
            $organizations = Organization::find()->where(['id' => Yii::$app->user->identity->organization_id])->all();

            return $this->render('my-monitoring', [
                'organizations' => $organizations,
                'model' => $model,
            ]);
    }
	
	
	
	
	 public function actionMenusMonitoring()
    {
        $menus = Menus::find()->where(['organization_id' => Yii::$app->user->identity->organization_id, 'status_archive' => 0])->all();
        //print_r($menus);exit;
            return $this->render('menus-monitoring', [
                'menus' => $menus,
            ]);
    }


    /*Подставляет организации в выпадающий список*/
    public function actionOrglist($id){
        $organization_id = Yii::$app->user->identity->organization_id;
        $region_id = Organization::findOne($organization_id)->region_id;

        if($id == 0){
            if (Yii::$app->user->can('rospotrebnadzor_camp')){
                $groups = Organization::find()->where(['region_id' => $region_id,'type_org' => 3])->orderby(['title' => SORT_ASC])->all();
            }else{
                $groups = Organization::find()->where(['region_id' => $region_id,'type_org' => 4])->orderby(['title' => SORT_ASC])->all();
            }

            echo '<option value="0">Все организации...</option>';
        }else{
            if (Yii::$app->user->can('rospotrebnadzor_camp')){
                $groups = Organization::find()->where(['region_id' => $region_id, 'municipality_id'=>$id, 'type_org' => 4])->orderby(['title' => SORT_ASC])->all();
            }else{
                $groups = Organization::find()->where(['region_id' => $region_id, 'municipality_id'=>$id, 'type_org' => 3])->orderby(['title' => SORT_ASC])->all();
            }
            echo '<option value="0">Все организации...</option>';
        }

        if(!empty($groups)){
            foreach ($groups as $key => $group) {
                echo '<option value="'.$group->id.'">'.$group->title.'</option>';
            }
        }
    }

	public function actionOrglist2($id){
        $organization_id = Yii::$app->user->identity->organization_id;
        $region_id = Organization::findOne($organization_id)->region_id;

        if($id == 0){
            $groups = Organization::find()->where(['region_id' => $region_id,'type_org' => 3])->orderby(['title' => SORT_ASC])->all();
        }else{
            $groups = Organization::find()->where(['municipality_id'=> $id, 'type_org' => 3])->orderby(['title' => SORT_ASC])->all();

        }
        //print_r($groups);exit;

        if(!empty($groups)){
            foreach ($groups as $key => $group) {
                echo '<option value="'.$group->id.'">'.$group->title.'</option>';
            }
        }
    }

    public function actionScriptReplace()
    {
        $count = 0;
       // $menus = Menus::find()->where(['>', 'food_director', 0])->count();//798 отправленых оргпитами меню в организации.
        $menus_orgpit_sends = Menus::find()->where(['>', 'food_director', 0])->all();//798 отправленых оргпитами меню в организации.
        foreach($menus_orgpit_sends as $menus_orgpit_send){
            $menus_org_pit = Menus::find()->where(['organization_id' => $menus_orgpit_send->food_director])->all();
            $check_in = 0;
            foreach ($menus_org_pit as $menu_org_pit){
                if($check_in == 0 && $menus_orgpit_send->name == $menu_org_pit->name){
                    $count++;$check_in++;
                    //print_r($count.'<br>');
                    $menus_send = new MenusSend();
                    $menus_send->sender_org_id = $menus_orgpit_send->food_director;
                    $menus_send->reciever_org_id = $menus_orgpit_send->organization_id;
                    $menus_send->reciever_type_org = 3;
                    $menus_send->sender_menu_id = $menu_org_pit->id;
                    $menus_send->reciever_menu_id = $menus_orgpit_send->id;
                    $menus_send->created_at = $menus_orgpit_send->created_at;
                    $menus_send->save();
                }
            }
        }
        //print_r($menus);exit;
        exit;

    }


    /*Подставляет организации в выпадающий список*/
    public function actionOrgcity($id){
        $cities = Organization::find()->where(['city_id' => $id])->all();
        $return = '<option value="0">Все организации района</option>';
        if(!empty($cities)){
            foreach ($cities as $key => $city) {
                $return .= '<option value="'.$city->id.'">'.$city->short_title.'</option>';
            }
        }
        return $return;
    }
}
