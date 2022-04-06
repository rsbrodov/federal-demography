<?php

namespace backend\controllers;

use common\models\DateForm;
use common\models\Menus;
use common\models\Municipality;
use common\models\Organization;
use common\models\Region;
use common\models\User;
use Yii;
use common\models\AnketParentControl;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\widgets\Menu;
use function GuzzleHttp\Psr7\str;

/**
 * AnketParentControlController implements the CRUD actions for AnketParentControl model.
 */
class AnketParentControlController extends Controller
{

    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => AnketParentControl::find()->where(['organization_id' => Yii::$app->user->identity->organization_id]),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }


    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionResult($id)
    {
        return $this->render('result', [
            'model' => $this->findModel($id),
        ]);
    }


    public function actionCreate()
    {
        $model = new AnketParentControl();
        //print_r(Yii::$app->request->pathInfo);exit;

        if (Yii::$app->request->post()){
            $post = Yii::$app->request->post()['AnketParentControl'];

            if(($post['masa_othodov']*1000) > ($post['count'] * $post['masa_porcii'])){
                Yii::$app->session->setFlash('error', "Введены некорректные данные. Масса несъеденной пищи не может превышать массу всех блюд. Обратите внимание, что массу несъеденной пищи нужно указывать в килограммах!");
                return $this->redirect(['create']);
            }

        }
        if ($model->load(Yii::$app->request->post())) {
            $model->date = strtotime($model->date);
            $model->save(false);
            //Расчет процентов и их сохранение
            $model->procent = round((($model->masa_othodov * 1000)/($model->masa_porcii * $model->count)) * 100,2);
            $model->test = $model->get_result_test($model->id);
            $model->test_food = $model->get_result_food($model->id, 'ball');
            $model->itog_ball = $model->test+$model->test_food;
            $model->save(false);
            return $this->redirect(['result', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionInside()
    {
        $model = new AnketParentControl();

        if (Yii::$app->request->post()){
            $post = Yii::$app->request->post()['AnketParentControl'];

            if(($post['masa_othodov']*1000) > ($post['count'] * $post['masa_porcii'])){
                //print_r( $post['masa_othodov']*1000 . ' >' . $post['count'] * $post['masa_porcii']);exit;
                Yii::$app->session->setFlash('error', "Введены некорректные данные. Масса несъеденной пищи не может превышать массу всех блюд. Обратите внимание, что массу несъеденной пищи нужно указывать в килограммах!");
                return $this->redirect(['inside']);
            }

        }

        if ($model->load(Yii::$app->request->post())) {
            $model->date = strtotime($model->date);
            $model->save(false);
            //Расчет процентов и их сохранение
            $model->procent = round((($model->masa_othodov * 1000)/($model->masa_porcii * $model->count)) * 100,2);
            $model->test = $model->get_result_test($model->id);
            $model->test_food = $model->get_result_food($model->id, 'ball');
            $model->itog_ball = $model->test+$model->test_food;
            $model->save(false);
            return $this->redirect(['result', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionSocial()
    {
        $model = new AnketParentControl();

        if ($model->load(Yii::$app->request->post())) {
            $model->date = strtotime($model->date);
            $model->save(false);
            //Расчет процентов и их сохранение
            $model->procent = round((($model->masa_othodov * 1000)/($model->masa_porcii * $model->count)) * 100,2);
            $model->test = $model->get_result_test($model->id);
            $model->test_food = $model->get_result_food($model->id, 'ball');
            $model->itog_ball = $model->test+$model->test_food;
            $model->save(false);
            return $this->redirect(['result', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }


    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }


    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }


    protected function findModel($id)
    {
        if (($model = AnketParentControl::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionReport()
    {
        $model = new DateForm();
        if(Yii::$app->request->post()){
            $post = Yii::$app->request->post()['DateForm'];

            return $this->render('report', [
                'model' => $model,
                'post' => $post,
            ]);
        }

        return $this->render('report', [
            'model' => $model,
        ]);
    }

    public function actionScript()
    {
        /*$count = 0;
        $organizations = Organization::find()->where(['type_org' => 3])->all();
        foreach($organizations as $organization){
            if(empty($organization->anket_parent_control_link)){
                $organization->anket_parent_control_link = 'RK-'.$organization->id.'-'.User::find()->where(['organization_id' => $organization->id])->one()->id.'-'.strtotime($organization->created_at);
                $organization->save();$count++;
                print_r($organization->anket_parent_control_link.'<br>');
            }
        }
        print_r($count);*/
        $anket_parent_control = AnketParentControl::find()->all();
        foreach ($anket_parent_control as $control){
            //if(empty($control->procent)){
                if($control->masa_porcii == 0 || $control->count == 0){
                    $control->delete();
                }else{
                    $control->procent = round((($control->masa_othodov * 1000)/($control->masa_porcii * $control->count)) * 100,2);
                    $control->test = $control->get_result_test($control->id);
                    $control->test_food = $control->get_result_food($control->id, 'ball');
                    $control->itog_ball = $control->test+$control->test_food;
                    $control->save();
                }
            //}
        }exit;
        /*$anket_parent_control = AnketParentControl::find()->where(['date' => 0])->all();
        foreach ($anket_parent_control as $control)
        {
            $control->delete();
        }*/
    }

    public function actionParentOutsideLink($id)
    {
        $model = new AnketParentControl();
        $explode_mas = explode('-', $id);
        $organization = Organization::find()->where(['id' => $explode_mas[1]])->one();
        if(!empty($organization)){
            if(Yii::$app->request->post()){
                $post = Yii::$app->request->post()['AnketParentControl'];
                if(($post['masa_othodov']*1000) > ($post['count'] * $post['masa_porcii'])){
                    Yii::$app->session->setFlash('error', "Введены некорректные данные. Масса несъеденной пищи не может превышать массу всех блюд. Обратите внимание, что массу несъеденной пищи нужно указывать в килограммах!");
                    return $this->redirect(['outside-link']);
                }
                if ($model->load(Yii::$app->request->post())) {
                    $model->date = strtotime($model->date);
                    $model->save(false);
                    //Расчет процентов и их сохранение
                    $model->procent = round((($model->masa_othodov * 1000)/($model->masa_porcii * $model->count)) * 100,2);
                    $model->test = $model->get_result_test($model->id);
                    $model->test_food = $model->get_result_food($model->id, 'ball');
                    $model->itog_ball = $model->test+$model->test_food;
                    $model->save(false);
                    return $this->redirect(['result', 'id' => $model->id]);
                }else{
                    Yii::$app->session->setFlash('error', "Введены некорректные данные! Не сохранено.");
                    return $this->redirect(['site/index']);
                }
            }else{
                return $this->render('outside-link', [
                    'model' => $model,
                    'organization' => $organization,
                    'id' => $id,
                ]);
            }
        }else{
            Yii::$app->session->setFlash('error', "Введена несуществующая ссылка! Проверьте правильность ввода");
            return $this->redirect(['site/index']);
        }

    }

    public function actionGeneratorList()
    {
        $regions = Region::find()->all();
        foreach($regions as $region){
            $organization_school = Organization::find()->where(['region_id' => $region->id, 'type_org' => 3])->count();
            $organization_kinder = Organization::find()->where(['region_id' => $region->id, 'type_org' => 5])->count();
            if($organization_school > 0 || $organization_kinder > 0){
                print_r($region->name.' '.$organization_school.' '.$organization_kinder.'<br>');
            }
        }
        /*return $this->render('generator-list', [
        ]);*/
    }


    public function actionScriptTop()
    {
        //$organizations = Organization::find()->where(['region_id' => 49])->all();
        //$organizations = Organization::find()->where(['id' => [299,402,425,3173,3652]])->all();
        $organizations = Organization::find()->where(['id' => [10255,10254,10256,2300,908]])->all();
        foreach($organizations as $organization){
            $anket_parent_control = AnketParentControl::find()->where(['organization_id' => $organization->id])->andWhere([ '>=', 'date', (strtotime('01.09.2021'))])->count();
            $menu = Menus::find()->where(['organization_id' => $organization->id])->count();
            $mas[$organization->id] = $anket_parent_control;
            //print_r($organization->id.'_'.$anket_parent_control.'_'.$menu.'<pre>');
            print_r($organization->title.'_'.Municipality::findOne($organization->municipality_id)->name.'_'.$anket_parent_control.'_'.$menu.'<pre>');
        }
        exit;
    }
}
