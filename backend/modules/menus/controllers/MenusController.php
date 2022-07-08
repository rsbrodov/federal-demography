<?php

namespace backend\modules\menus\controllers;

use common\models\Days;
use common\models\MenuForm;
use common\models\Menus;
use common\models\Dishes;
use common\models\RecipesCollection;
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
            'query' => Menus::find()->select(['*'])
                ->with([
                'characters' => function($q){
                    return $q->select(['id', 'name']);
                },
                'age' => function($q){
                    return $q->select(['id', 'name']);
                }])
                ->where(['status_archive' => 0, 'organization_id' => Yii::$app->user->identity->organization_id]),
            'pagination' => [
                'pageSize' => 15,
            ],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

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
                    $model2->variativity = Yii::$app->request->post()['MenuForm']['variativity'];
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
                    $model2->variativity = Yii::$app->request->post()['MenuForm']['variativity'];
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
                $model2->variativity = Yii::$app->request->post()['MenuForm']['variativity'];
                $model2->status_archive = 0;
            }
            //БЛОК ОБЩЕЙ ИНФОРМАЦИИ И ДАННЫХ СКОЛЬКО ДНЕЙ В МЕНЮ И ПРИЕМОВ ПИЩИ
            if($model2->save()){
                foreach(Yii::$app->chemical_value->days_mas() as $key => $d){
                    if($post[$key] == 1){
                        $mas_days[] = $d;
                    }
                }

                foreach(Yii::$app->chemical_value->nutritions_mas() as $key => $n){
                    if($post[$key] == 1){
                        $mas_nutritions[] = $n;//ЦИФРА НАПРОТИВ МАССИВА ОБОЗНАЧАЕТ ID ПРИЕМА ПИЩИ В ТАБЛИЦЕ NUTRITION_INFO!!!
                    }
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
                if(Yii::$app->request->post()['MenuForm']['variativity'] == 1) {
                    $variativity_mas = Yii::$app->chemical_value->variativity_mas();
                    foreach ($variativity_mas as $v){
                        if(Yii::$app->request->post()['MenuForm'][$v] == 1){
                            $new_menus_variativity = new MenusVariativity();
                            $new_menus_variativity->organization_id = Yii::$app->user->identity->organization_id;
                            $new_menus_variativity->menu_id = $model2->id;
                            $new_menus_variativity->variativity_id = Variativity::find()->where(['name' => $v])->one()->block_id;
                            $new_menus_variativity->save();
                        }
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

    public function actionUpdate($id)
    {
        $my_org = Organization::findOne(Yii::$app->user->identity->organization_id);
        $model = new MenuForm();
        $menus = Menus::findOne($id);
        $menus_days = MenusDays::find()->where(['menu_id' => $id])->all();
        $menus_nutrition = MenusNutrition::find()->where(['menu_id' => $id])->all();
        if (Yii::$app->request->post()) {
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
            $model2->variativity = Yii::$app->request->post()['MenuForm']['variativity'];
            if($model2->save()){
                MenusDays::deleteAll('menu_id =:id', [':id' => $id]);
                MenusNutrition::deleteAll('menu_id =:id', [':id' => $id]);
                $mas_days = array();
                $mas_nutritions = array();
                foreach(Yii::$app->chemical_value->days_mas() as $key => $d){
                    if($post[$key] == 1){
                        $mas_days[] = $d;
                    }
                }

                foreach(Yii::$app->chemical_value->nutritions_mas() as $key => $n){
                    if($post[$key] == 1){
                        $mas_nutritions[] = $n;//ЦИФРА НАПРОТИВ МАССИВА ОБОЗНАЧАЕТ ID ПРИЕМА ПИЩИ В ТАБЛИЦЕ NUTRITION_INFO!!!
                    }
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

                MenusVariativity::deleteAll('menu_id =:id', [':id' => $model2->id]);
                if(Yii::$app->request->post()['MenuForm']['variativity'] == 1) {
                    $variativity_mas = Yii::$app->chemical_value->variativity_mas();
                    foreach ($variativity_mas as $v){
                        if(Yii::$app->request->post()['MenuForm'][$v] == 1){
                            $new_menus_variativity = new MenusVariativity();
                            $new_menus_variativity->organization_id = Yii::$app->user->identity->organization_id;
                            $new_menus_variativity->menu_id = $model2->id;
                            $new_menus_variativity->variativity_id = Variativity::find()->where(['name' => $v])->one()->block_id;
                            $new_menus_variativity->save();
                        }
                    }
                }

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

    public function actionDelete($id)
    {
        /*Массовое удаление меню из всех таблиц при удалении меню*/
        $menu = $this->findModel($id);
        MenusDays::deleteAll('menu_id =:id', [':id' => $id]);
        MenusNutrition::deleteAll('menu_id =:id', [':id' => $id]);
        MenusDishes::deleteAll('menu_id =:id', [':id' => $id]);
        if($menu->variativity == 1) {
            $variativity_menu = MenusVariativity::find()->where(['menu_id' => $menu->id])->all();
            foreach ($variativity_menu as $v){
                $v->delete();
            }
        }
        $menu->delete();
        return $this->redirect(['index']);
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
                'query' => Menus::find()->where(['status_archive' => 1, 'show_indicator' => [2, 7, 9, 6]])->orWhere(['organization_id' => Yii::$app->user->identity->organization_id, 'status_archive' => 1]),
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
                foreach(Yii::$app->chemical_value->days_mas() as $key => $d){
                    if($post[$key] == 1){
                        $mas_days[] = $d;
                    }
                }

                foreach(Yii::$app->chemical_value->nutritions_mas() as $key => $n){
                    if($post[$key] == 1){
                        $mas_nutritions[] = $n;//ЦИФРА НАПРОТИВ МАССИВА ОБОЗНАЧАЕТ ID ПРИЕМА ПИЩИ В ТАБЛИЦЕ NUTRITION_INFO!!!
                    }
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
        $model2->variativity = $model->variativity;
        $model2->cycle = $model->cycle;
        $model2->date_start = $model->date_start;
        $model2->date_end = $model->date_end;
        $model2->status_archive = 1;
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

            if($model->variativity == 1) {
                $variativity_menu = MenusVariativity::find()->where(['menu_id' => $model->id])->all();
                foreach ($variativity_menu as $v){
                    $new_menus_variativity = new MenusVariativity();
                    $new_menus_variativity->organization_id = Yii::$app->user->identity->organization_id;
                    $new_menus_variativity->menu_id = $model2->id;
                    $new_menus_variativity->variativity_id = $v->variativity_id;
                    $new_menus_variativity->save();
                }
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

            if($model->variativity == 1) {
                $variativity_menu = MenusVariativity::find()->where(['menu_id' => $model->id])->all();
                foreach ($variativity_menu as $v){
                    $new_menus_variativity = new MenusVariativity();
                    $new_menus_variativity->organization_id = Yii::$app->user->identity->organization_id;
                    $new_menus_variativity->menu_id = $model2->id;
                    $new_menus_variativity->variativity_id = $v->variativity_id;
                    $new_menus_variativity->save();
                }
            }

            Yii::$app->session->setFlash('success', "Это архивное меню было успешно подгружено к Вам. ". Html::a("Перейти к своим меню.", "/menus/index"));
            return $this->redirect(['menus/archive']);
        }
        else{
            Yii::$app->session->setFlash('error', "Произошла ошибка при подгрузке меню. Попробуйте снова");
            return $this->redirect(['menus/archive']);
        }
    }

    public function actionSettingArchive($id)
    {
        $my_org = Organization::findOne(Yii::$app->user->identity->organization_id);
        $model = new Menus();
        $menus = Menus::findOne($id);
        $menus_days = MenusDays::find()->where(['menu_id' => $id])->all();
        $menus_nutrition = MenusNutrition::find()->where(['menu_id' => $id])->all();


        if (Yii::$app->request->post()) {
            $post = Yii::$app->request->post()['Menus'];
            $model2 = Menus::findOne($id);

            if (!empty($post['type_org_id'])) {
                $model2->type_org_id = $post['type_org_id'];
            } elseif ($my_org->type_org != 2 && $my_org->type_org != 4 && $my_org->type_org != 1 && $my_org->type_org != 9 && $my_org->type_org != 8 && $my_org->type_org != 11) {
                $model2->type_org_id = $my_org->type_org;
            } else {
                $model2->type_org_id = 3;
            }


            $model2->feeders_characters_id = $post['feeders_characters_id'];
            $model2->age_info_id = $post['age_info_id'];
            $model2->show_indicator = $post['show_indicator'];
            $model2->name = $post['name'];
            $model2->cycle = $post['cycle'];
            $model2->date_start = strtotime($post['date_start']);
            $model2->date_end = strtotime($post['date_end']);
            if ($model2->save()) {
                Yii::$app->session->setFlash('success', "Изменения сохранены!");
                return $this->redirect(['archive']);
            } else {
                Yii::$app->session->setFlash('error', "Произошла ошибка при редактировании. Данные не были сохранены");
                return $this->redirect(['menus/setting-archive?id=' . $id]);
            }
        }

        return $this->render(
            'setting-archive',
            [
                'model' => $model,
                'menus' => $menus,
                'menus_days' => $menus_days,
                'menus_nutrition' => $menus_nutrition,
            ]
        );
    }

    public function actionDeleteArchive($id)
    {
        /*Массовое удаление меню из всех таблиц при удалении меню*/
        $menu = $this->findModel($id);
        MenusDays::deleteAll('menu_id =:id', [':id' => $id]);
        MenusNutrition::deleteAll('menu_id =:id', [':id' => $id]);
        MenusDishes::deleteAll('menu_id =:id', [':id' => $id]);
        if($menu->variativity == 1) {
            $variativity_menu = MenusVariativity::find()->where(['menu_id' => $menu->id])->all();
            foreach ($variativity_menu as $v){
                $v->delete();
            }
        }
        $menu->delete();
        Yii::$app->session->setFlash('success', "Меню успешно удалено!");
        return $this->redirect(['archive']);
    }

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

    public function actionDevelop()
    {
        $model = new MenusDishes();


        if (Yii::$app->request->post())
        {
            //$nutritions = NutritionInfo::find()->all();
            $post = Yii::$app->request->post()['MenusDishes'];

            $menus_nutrition_id = MenusNutrition::find()->where(['menu_id' => $post['menu_id']])->all();//ВСЕ ПРИЕМЫ ПИЩИ КОНКРЕТНОГО МЕНЮ
            $ids = [];
            foreach ($menus_nutrition_id as $m_id)
            {
                $ids[] = $m_id->nutrition_id;//МАССИВ ID ПРИЕМОВ ПИЩИ КЛНКРЕЬНОГО МЕНЮ
            }
            $nutritions = NutritionInfo::find()->where(['id' => $ids])->all();//ОБЪЕКТ С НАБОРОМ ПРИЕМОВ ПИЩИ ИЗ БАЗЫ С УЧЕТОМ НАШИХ АЙДИ

            $menus_dishes = MenusDishes::find()->where(['menu_id' => $post['menu_id'], 'cycle' => $post['cycle'], 'days_id' => $post['days_id'], 'date_fact_menu' => 0])->orderby(['nutrition_id' => SORT_ASC])->all();

            return $this->render('develop', [
                'menus_dishes' => $menus_dishes,
                'nutritions' => $nutritions,
                'model' => $model,
                'post' => $post,
            ]);
        }
        return $this->render('develop', [
            'model' => $model,
        ]);
    }

    /*МЕТОД ЛЯ АВТОПОДСТАНОВКИ ТЕКСТА*/
    public function actionSearchfulltext()
    {
        $json = array();
        $json[] = array();
        $e = Yii::$app->request->post()['e'];
        $recipes_collections = Yii::$app->request->post()['recipes_collections'];
        $dishes = Dishes::find()->where(['like', 'name', $e])->andWhere(['in', 'recipes_collection_id', $recipes_collections])->orderBy(['techmup_number' => SORT_ASC])->all();

        $field = array();
        foreach ($dishes as $dish)
        {
            $short_title_recipes_collections = RecipesCollection::find()->where(['id' => $dish->recipes_collection_id])->one()->short_title;
            //$field[$i] = $dish->name;
            if($dish->dishes_category_id == 7) {
                $field[] = array('id' => $dish->id, 'name' => $dish->name, 'techmup_number' => $dish->techmup_number, 'recipes_collections' => $short_title_recipes_collections, 'yield' => 200);
            } else {
                $field[] = array('id' => $dish->id, 'name' => $dish->name, 'techmup_number' => $dish->techmup_number, 'recipes_collections' => $short_title_recipes_collections, 'yield' => $dish->yield);
            }
        }
        $result = array("field" => $field);
        return json_encode($result);
    }

    //МЕТОД ДДЯ ДОБАВЛЕНИЯ БЛЮДА В ЦИКЛИЧЕСКОЕ И ФАКТИЧЕСКОЕ МЕНЮ
    public function actionSaving()
    {
        Yii::$app->controller->enableCsrfValidation = false;
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $post = Yii::$app->request->post();

        $dishes = Dishes::findOne($post['dishes_id']);
        if (empty($post['yield']))
        {
            return 'error1';
        }

        if (empty($dishes))
        {
            return 'error2';
        }
        /*СОЗДАЛИ ЧАСТЬ НОВОГО БЛЮДА, НО МЫ ЕЩЕ НЕ ЗНАЕМ ДЛЯ КАКОГО ОНО МЕНЮ(ФАКТИЧ ИЛИ ЦИКЛИЧ)*/
        $menu = new MenusDishes();
        $menu->menu_id = $post['menu_id'];
        $menu->cycle = $post['cycle'];
        $menu->days_id = $post['days_id'];
        $menu->nutrition_id = $post['nutrition_id'];
        $menu->dishes_id = $post['dishes_id'];
        $menu->yield = $post['yield'];
        /*КОНЕЦ ЧАСТИ СОЗД*/
        /*ЕСЛИ ДАТА БОЛЬШЕ НУЛЯ, ТО БЛЮДО ДЛЯ ФАКТИЧЕСКОГО МЕНЮ*/
        if ($post['date'] > 0)
        {
            /*ПОИСК ФАКТИЧЕСКОГО МЕНЮ*/
            $m_dish_fact = MenusDishes::find()->where(['date_fact_menu' => $post['date'], 'menu_id' => $post['menu_id']])->all();
            /*ЕСЛИ НЕ НАШЛОСЬ, ТОГДА ПЕРЕБИРАЕМ ЦИКЛИЧЕСКОЕ И ПО ЕГО ПОДОБИЮ СОЗДАЕМ ФАКТИЧЕСКОГО*/
            if (empty($m_dish_fact))
            {
                $menus_dishes = MenusDishes::find()->where(['date_fact_menu' => 0, 'menu_id' => $post['menu_id'], 'cycle' => $post['cycle'], 'days_id' => $post['days_id']])->all();
                foreach ($menus_dishes as $m_dish)
                {
                    $model = new MenusDishes();
                    $model->menu_id = $post['menu_id'];
                    $model->cycle = $post['cycle'];
                    $model->days_id = $post['days_id'];
                    $model->nutrition_id = $m_dish->nutrition_id;
                    $model->dishes_id = $m_dish->dishes_id;
                    $model->yield = $m_dish->yield;
                    $model->date_fact_menu = $post['date'];
                    $model->save();
                }
            }
        }
    }

    /*МЕТОД ДЛЯ УДАЛЕНИЕ БЛЮД ИЗ ЦИКЛИЧЕСКОГО МЕНЮ*/
    public function actionDel($id)
    {
 
        //$this->findModel($id)->delete();
        return 123;
    }
}
