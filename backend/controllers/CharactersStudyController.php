<?php

namespace backend\controllers;

use common\models\CharactersStudyAllergy;
use common\models\Menus;
use common\models\MenusDishes;
use common\models\MenusNutrition;
use common\models\NutritionInfo;
use common\models\Organization;
use common\models\StudentForm;
use common\models\Students;
use common\models\StudentsClass;
use common\models\StudentsNutrition;
use Yii;
use common\models\CharactersStudy;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * CharactersStudyController implements the CRUD actions for CharactersStudy model.
 */
class CharactersStudyController extends Controller
{

    public function actionIndex()
    {
       $models = CharactersStudy::find()->where(['organization_id' => Yii::$app->user->identity->organization_id])->orderBy(['class_number' => SORT_ASC, 'class_letter'=> SORT_ASC])->all();


        return $this->render('index', [
            'models' => $models,
        ]);
    }


    public function actionIndexNew()
    {
        if(!Yii::$app->user->can('teacher'))
        {
            $models = StudentsClass::find()->where(['organization_id' => Yii::$app->user->identity->organization_id])->orderBy(['class_number' => SORT_ASC, 'class_letter' => SORT_ASC])->all();
        }else{
            $students_classes = StudentsClass::find()->where(['user_id' => Yii::$app->user->identity->id])->all();
            $students_classes_ids = ArrayHelper::map($students_classes, 'id', 'id');
            $models = StudentsClass::find()->where(['id' => $students_classes_ids])->orderBy(['class_number' => SORT_ASC, 'class_letter' => SORT_ASC])->all();
        }

        return $this->render('index-new', [
            'models' => $models,
        ]);
    }

    public function actionReport()
    {
        $model = new Menus();
        if (Yii::$app->request->post()){
            $post = Yii::$app->request->post()['Menus'];
            $students_classes = StudentsClass::find()->where(['organization_id' => $post['organization_id']])->all();
            $students_classes_ids = ArrayHelper::map($students_classes, 'id', 'id');
            $students_classes = StudentsClass::find()->where(['id' => $students_classes_ids])->orderBy(['class_number' => SORT_ASC, 'class_letter' => SORT_ASC])->all();
            return $this->render('report', [
                'model' => $model,
                'post' => $post,
                'students_classes' => $students_classes,
            ]);
        }
        return $this->render('report', [
            'model' => $model,
        ]);
    }


    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }


    public function actionCreate()
    {
        $model = new CharactersStudy();

        if ($model->load(Yii::$app->request->post()) && $model->save(false)) {
            Yii::$app->session->setFlash('success', "Данные успешно сохранены");
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }


    public function actionCreateNew()
    {
        $model = new StudentsClass();

        if (Yii::$app->request->post()) {
            $post = Yii::$app->request->post()['StudentsClass'];
            if(!empty(StudentsClass::find()->where(['organization_id' => Yii::$app->user->identity->organization_id, 'class_number' =>$post['class_number'], 'class_letter' =>$post['class_letter']])->all())){
                Yii::$app->session->setFlash('error', "Класс не создан, так как такой уже есть в орагнизации!");
                return $this->redirect(['characters-study/index-new']);
            }
            $model->organization_id = Yii::$app->user->identity->organization_id;
            $model->user_id = $post['user_id'];
            $model->class_number = $post['class_number'];
            $model->class_letter = $post['class_letter'];
            $model->smena = $post['smena'];
            $model->save();

            Yii::$app->session->setFlash('success', "Данные успешно сохранены");
            return $this->redirect(['characters-study/index-new']);
        }

        return $this->render('create-new', [
            'model' => $model,
        ]);
    }


    public function actionUpdateNew($id)
    {
        $model = StudentsClass::findOne($id);

        if (Yii::$app->request->post()) {
            $post = Yii::$app->request->post()['StudentsClass'];
            /*if(!empty(StudentsClass::find()->where(['organization_id' => Yii::$app->user->identity->organization_id, 'class_number' =>$post['class_number'], 'class_letter' =>$post['class_letter']])->all())){
                Yii::$app->session->setFlash('error', "Команда не выполнена, так как такой уже есть в орагнизации!");
                return $this->redirect(['characters-study/index-new']);
            }*/
            $model->organization_id = Yii::$app->user->identity->organization_id;
            $model->user_id = $post['user_id'];
            $model->class_number = $post['class_number'];
            $model->class_letter = $post['class_letter'];
            $model->smena = $post['smena'];
            $model->save();

            Yii::$app->session->setFlash('success', "Данные успешно сохранены");
            return $this->redirect(['characters-study/index-new']);
        }

        return $this->render('update-new', [
            'model' => $model,
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save(false)) {
            Yii::$app->session->setFlash('success', "Данные успешно сохранены");
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        Yii::$app->session->setFlash('success', "Данные успешно удалены");
        return $this->redirect(['index']);
    }

    public function actionDeleteNew($id)
    {
        $students_class = StudentsClass::findOne($id);
        $students = Students::find()->where(['students_class_id' => $students_class->id])->all();
        //Удаление приемов пищи детей, учеников и их класса
        $students_nutrition = StudentsNutrition::deleteAll('students_id =:students_id', [':students_id' => ArrayHelper::map($students, 'id', 'id')]);
        $students_delete = Students::deleteAll('students_class_id =:students_class_id', [':students_class_id' => $students_class->id]);
        $students_class->delete();
        Yii::$app->session->setFlash('success', "Данные успешно удалены");
        return $this->redirect(['index-new']);
    }


    public function actionCopyStudent($id)
    {
        $student = Students::findOne($id);
        $students_nutrition = StudentsNutrition::find()->where(['students_id' => $student->id])->all();
        $new_student = new Students();
        $new_student->attributes = $student->attributes;
        $new_student->name = $student->name/*.'(К)'*/;
        $new_student->al_arahis = $student->al_arahis/*.'(К)'*/;
        //print_r($new_student);exit;
        if($new_student->save(false)){
            foreach ($students_nutrition as $s_nutrition)
            {
                $new_students_nutrition = new StudentsNutrition();
                $new_students_nutrition->students_id = $new_student->id;
                $new_students_nutrition->organization_id = $s_nutrition->organization_id;
                $new_students_nutrition->nutrition_id = $s_nutrition->nutrition_id;
                $new_students_nutrition->peremena = $s_nutrition->peremena;
                $new_students_nutrition->save();
            }
            Yii::$app->session->setFlash('success', "Создание копии ребенка прошло успешно");
        }else{
            Yii::$app->session->setFlash('error', "Произошла ошибка при создании копии");
        }
        return $this->redirect(['students-list', 'id' => $student->students_class_id]);
    }


    public function actionDeleteStudent($id)
    {
        $student = Students::findOne($id);
        //Удаление приемов пищи детей, учеников и их класса
        $students_nutrition = StudentsNutrition::deleteAll('students_id =:students_id', [':students_id' => $student->id]);
        $student->delete();
        Yii::$app->session->setFlash('success', "Данные успешно удалены");
        return $this->redirect(['students-list', 'id' => $student->students_class_id]);
    }


    protected function findModel($id)
    {
        if (($model = CharactersStudy::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }


    public function actionAddallergy($id)
    {
        $model = new CharactersStudyAllergy();
        $characters = CharactersStudyAllergy::find()->where(['characters_study_id' => $id])->all();

        if (Yii::$app->request->post()) {
            //$model = CharactersStudyAllergy::find()->where(['characters_study_id' => $id])->one();
            //if(empty($model)){
                $model = new CharactersStudyAllergy();
                $model->load(Yii::$app->request->post());
                $model->save(false);
                //print_r(Yii::$app->request->post());exit;
            //}
            /*else{
                $model->load(Yii::$app->request->post());
                $model->save(false);
            }
*/
            Yii::$app->session->setFlash('success', "Данные успешно сохранены");
            return $this->redirect(['addallergy?id='.$id]);
        }

        return $this->render('addallergy', [
            'model' => $model,
            'characters' => $characters,
        ]);
    }


    public function actionReportCharactersStudyDiseases()
    {
        $model = new CharactersStudyAllergy();
        $characters_study = CharactersStudy::find()->where(['organization_id' => Yii::$app->user->identity->organization_id])->orderBy(['class_number' => SORT_ASC, 'class_letter'=> SORT_ASC])->all();
        $characters_study_mas = ArrayHelper::map($characters_study, 'id', 'id');


        return $this->render('report-characters-study-diseases', [
            'model' => $model,
            'characters_study_mas' => $characters_study_mas,
        ]);
    }


    public function actionCreateStudent($id)
    {
        $model_form = new StudentForm();
        if (Yii::$app->request->post()) {
            //print_r(Yii::$app->request->post()['StudentForm']);exit;
            $post = Yii::$app->request->post()['StudentForm'];
            $model_students = new Students();
            $model_students->students_class_id = $id;
            $model_students->organization_id = Yii::$app->user->identity->organization_id;
            $model_students->name = $post['f_name'];
            $model_students->form_study = $post['form_study'];
            //$model_students->form_study = $post['zabolevaniya_est_net'];//1-есть, 0-нет
            //если выбран пункт что заболевания есть
            if($post['zabolevaniya_est_net'] == 1){
                $model_students->dis_sahar = $post['dis_sahar'];
                $model_students->dis_ovz = $post['dis_ovz'];
                $model_students->dis_cialic = $post['dis_cialic'];
                $model_students->dis_fenilketon = $post['dis_fenilketon'];
                $model_students->dis_mukovis = $post['dis_mukovis'];
                $model_students->al_moloko = $post['al_moloko'];
                $model_students->al_yico = $post['al_yico'];
                $model_students->al_pshenica = $post['al_pshenica'];
                $model_students->al_fish = $post['al_fish'];
                $model_students->al_chocolad = $post['al_chocolate'];
                $model_students->al_orehi = $post['al_orehi'];
                $model_students->al_citrus = $post['al_citrus'];
                $model_students->al_med = $post['al_med'];
                $model_students->al_arahis = $post['al_arahis'];
                $model_students->al_inoe = $post['al_inoe'];
            }else{
                $model_students->dis_sahar = 0;
                $model_students->dis_ovz = 0;
                $model_students->dis_cialic = 0;
                $model_students->dis_fenilketon = 0;
                $model_students->dis_mukovis = 0;
                $model_students->al_moloko = 0;
                $model_students->al_yico = 0;
                $model_students->al_pshenica = 0;
                $model_students->al_fish = 0;
                $model_students->al_chocolad = 0;
                $model_students->al_orehi = 0;
                $model_students->al_citrus = 0;
                $model_students->al_med = 0;
                $model_students->al_arahis = 0;
                $model_students->al_inoe = 0;
            }


            //если ребенок НЕ питается
            if($post['pit_nepit'] == 0){
                $model_students->otkaz_pitaniya = $post['pit_nepit'];
                $model_students->prichina_otkaza = $post['prichina_otkaza'];
                $model_students->save(false);
            }


            //если ребенок питается
            if($post['pit_nepit'] == 1){
                $model_students->otkaz_pitaniya = $post['pit_nepit'];
                $model_students->save(false);
                if(empty($post['nutrition1'])){
                    Yii::$app->session->setFlash('error', "Внимание, Вы допустили ошибку при заполнении! Вы не указали приемы пищи у ребенка. Внесите данные снова.");
                    return $this->redirect(['create-student?id='.$id]);
                }
                $count_nutrition = 1;
                while($count_nutrition <=7){
                    if(!empty($post['nutrition'.$count_nutrition])){
                        $model_students_nutrition = new StudentsNutrition();
                        $model_students_nutrition->students_id = $model_students->id;
                        $model_students_nutrition->organization_id = Yii::$app->user->identity->organization_id;
                        $model_students_nutrition->nutrition_id = $post['nutrition'.$count_nutrition];
                        $model_students_nutrition->peremena = $post['peremena'.$count_nutrition];
                        $model_students_nutrition->save();
                    }
                    $count_nutrition++;
                }
            }
            Yii::$app->session->setFlash('success', "Данные сохранены");
            return $this->redirect(['students-list?id='.$id]);

        }

        return $this->render('create-student', [
            'model_form' => $model_form,
        ]);
    }



    public function actionUpdateStudent($id)
    {
        $model_form = new StudentForm();
        if (Yii::$app->request->post()) {
            $post = Yii::$app->request->post()['StudentForm'];

            $model_students = Students::findOne($id);
            //$model_students->students_class_id = $id;
            //$model_students->organization_id = Yii::$app->user->identity->organization_id;
            $model_students->name = $post['f_name'];
            $model_students->form_study = $post['form_study'];
            //$model_students->form_study = $post['zabolevaniya_est_net'];//1-есть, 0-нет
            //если выбран пункт что заболевания есть
            //if($post['zabolevaniya_est_net'] == 1){
            $model_students->dis_sahar = $post['dis_sahar'];
            $model_students->dis_ovz = $post['dis_ovz'];
            $model_students->dis_cialic = $post['dis_cialic'];
            $model_students->dis_fenilketon = $post['dis_fenilketon'];
            $model_students->dis_mukovis = $post['dis_mukovis'];
            $model_students->al_moloko = $post['al_moloko'];
            $model_students->al_yico = $post['al_yico'];
            $model_students->al_pshenica = $post['al_pshenica'];
            $model_students->al_fish = $post['al_fish'];
            $model_students->al_chocolad = $post['al_chocolate'];
            $model_students->al_orehi = $post['al_orehi'];
            $model_students->al_citrus = $post['al_citrus'];
            $model_students->al_med = $post['al_med'];
            $model_students->al_arahis = $post['al_arahis'];
            $model_students->al_inoe = $post['al_inoe'];



            //если ребенок НЕ питается
            if($post['pit_nepit'] == 0){
                $model_students->otkaz_pitaniya = $post['pit_nepit'];
                $model_students->prichina_otkaza = $post['prichina_otkaza'];
                $model_students->save(false);
                //приступаем к удалению его приемов пищи, если таковые были
                StudentsNutrition::deleteAll('students_id =:students_id', [':students_id' => $model_students->id]);
            }


            //если ребенок питается
            if($post['pit_nepit'] == 1){
                $model_students->otkaz_pitaniya = $post['pit_nepit'];
                $model_students->prichina_otkaza = '';
                $model_students->save(false);
                if(empty($post['nutrition1'])){
                    Yii::$app->session->setFlash('error', "Внимание, Вы допустили ошибку при заполнении! Вы не указали приемы пищи у ребенка. Внесите данные снова.");
                    return $this->redirect(['update-student?id='.$id]);
                }
                $count_nutrition = 1;
                $nutrition_info = NutritionInfo::find()->all();
//print_r($post);exit;
                $post_nutrition_mas = [];
                $old_nutrition_mas = [];
                $delete_nutrition_mas = [];
                foreach($nutrition_info as $n_info){
                    //формируем массив старых приемов пищи
                    if (!empty(StudentsNutrition::find()->where(['students_id' => $model_students->id, 'nutrition_id' => $n_info->id])->one())){
                        $old_nutrition_mas[$n_info->id] = $n_info->id;
                    }
                    if(!empty($post['nutrition'.$n_info->id])){
                        $post_nutrition_mas[$post['nutrition'.$n_info->id]] = $post['nutrition'.$n_info->id];
                        $one_nutrition = StudentsNutrition::find()->where(['students_id' => $model_students->id, 'nutrition_id' => $post['nutrition'.$n_info->id]])->one();
                        if (!empty($one_nutrition)){
                            $one_nutrition->peremena = $post['peremena' . $n_info->id];
                            $one_nutrition->save();
                        }
                        else{
                            //print_r($post);exit;
                            $model_students_nutrition = new StudentsNutrition();
                            $model_students_nutrition->students_id = $model_students->id;
                            $model_students_nutrition->organization_id = Yii::$app->user->identity->organization_id;
                            $model_students_nutrition->nutrition_id = $post['nutrition' . $n_info->id];
                            $model_students_nutrition->peremena = $post['peremena' . $n_info->id];
                            $model_students_nutrition->save(false);
                        }
                    }
                }
                //удаление приемов пищи если таковые есть и они не нужны
                /*print_r($post_nutrition_mas);
                print_r('<br>');
                print_r($old_nutrition_mas);exit;*/
                foreach ($old_nutrition_mas as $old_nutrition_m){
                    if(!array_key_exists($old_nutrition_m, $post_nutrition_mas)){
                        $delete_nutrition_mas[$old_nutrition_m] = $old_nutrition_m;
                        $one_nutrition = StudentsNutrition::find()->where(['students_id' => $model_students->id, 'nutrition_id' => $old_nutrition_m])->one();
                        $one_nutrition->delete();
                    }
                }
            }
            Yii::$app->session->setFlash('success', "Данные сохранены");
            return $this->redirect(['students-list?id='.$model_students->students_class_id]);

        }

        return $this->render('update-student', [
            'model_form' => $model_form,
        ]);
    }

    public function actionStudentsList($id)
    {
        $model_form = new StudentsClass();
            $students = Students::find()->where(['students_class_id' => $id])->orderBy(['name' => SORT_ASC])->all();
            return $this->render('students-list', [
                'model_form' => $model_form,
                'students' => $students,
            ]);

    }

    /*public function actionScript()
    {
        $menu_obedi = Menus::findOne(15972);
        $menus_dishes_obedi = MenusDishes::find()->where(['menu_id' => $menu_obedi->id])->all();

        foreach ($menus_dishes_obedi as $m_obed){
            $menus_dishes_new = new MenusDishes();
            $menus_dishes_new->date_fact_menu = 0;
            $menus_dishes_new->menu_id = 16055;
            $menus_dishes_new->cycle = $m_obed->cycle;
            $menus_dishes_new->days_id = $m_obed->days_id;
            $menus_dishes_new->nutrition_id = $m_obed->nutrition_id;
            $menus_dishes_new->dishes_id = $m_obed->dishes_id;
            $menus_dishes_new->yield = $m_obed->yield;
            $menus_dishes_new->save();
        }
    }*/

    public function actionScript()
    {
        /*$menu_obedi1 = Menus::findOne(12881);
        $menus_dishes_obedi1 = MenusDishes::find()->where(['menu_id' => $menu_obedi1->id, 'nutrition_id' => 3])->all();
        $menus_dishes_obedi1_mas = ArrayHelper::map($menus_dishes_obedi1, 'id', 'id');*/

        $menu_obedi2 = Menus::findOne(17983);
        $menus_dishes_obedi2 = MenusDishes::find()->where(['menu_id' => $menu_obedi2->id, 'nutrition_id' => 2])->all();
        $menus_dishes_obedi2_mas = ArrayHelper::map($menus_dishes_obedi2, 'id', 'id');

        foreach ($menus_dishes_obedi2_mas as $m_obed){
            $m_dish = MenusDishes::findOne($m_obed);
            $m_dish->nutrition_id = 1;
            $m_dish->save();
        }
        $menus_nutrition = MenusNutrition::find()->where(['menu_id' => 17983, 'nutrition_id' => 2])->one();
        $menus_nutrition->nutrition_id = 1;
        $menus_nutrition->save();
    }

    public function actionOrganizationFake()
    {
        //$organizations = Organization::find()->where(['type_org' => [2,3,4], 'region_id' =>48])->orderBy(['created_at' => SORT_ASC])->all();
        $organizations = Organization::find()->where(['type_org' => [1,2,3,4,5,6,8]])->orderBy(['created_at' => SORT_ASC])->all();
        //print_r($organizations);exit;
        return $this->render('organization-fake', [
            'organizations' => $organizations,
        ]);
    }
}
