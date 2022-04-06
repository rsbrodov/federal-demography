<?php

namespace backend\controllers;

use common\models\AuthAssignment;
use common\models\User;
use Yii;
use common\models\SettingsAdmin;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * SettingsAdminController implements the CRUD actions for SettingsAdmin model.
 */
class SettingsAdminController extends Controller
{
    /**
     * {@inheritdoc}
     */

    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => SettingsAdmin::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single SettingsAdmin model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */

    public function actionCreate()
    {
        $model = new SettingsAdmin();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing SettingsAdmin model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing SettingsAdmin model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the SettingsAdmin model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return SettingsAdmin the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SettingsAdmin::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionEmailSending()
    {
        $users = User::find()->all();
        $user_ids = [];
            foreach($users as $user){
                if(AuthAssignment::find()->where(['user_id' => $user->id])->one()->item_name != 'camp_director' && AuthAssignment::find()->where(['user_id' => $user->id])->one()->item_name != 'subject_minobr' && AuthAssignment::find()->where(['user_id' => $user->id])->one()->item_name != 'rospotrebnadzor_nutrition' && AuthAssignment::find()->where(['user_id' => $user->id])->one()->item_name != 'rospotrebnadzor_camp'){
                    $user_ids[$user->id] = $user->email;
                }
            }
			unset($user_ids[275]);unset($user_ids[276]);unset($user_ids[277]);unset($user_ids[278]);unset($user_ids[279]);
			print_r($user_ids);exit();
        $filtering_users = User::find()->where(['id' => [271, 90]])->all();
  	//print_r($user_ids);exit();
        foreach($filtering_users as $user){
            $message = Yii::$app->mailer->compose();
            $message->setFrom(['help@niig.su' => 'help@niig.su']);
            $message->setTo($user->email)
                ->setSubject('Программа Питание и мониторинг здоровья')
                ->setHtmlBody('<p>Добрый день, ' . $user->name . '!</p><p>Внимание! 11 августа в 11:00 (07:00 по мск) состоится вебинар по работе в программном средстве 
			"Питание и мониторинг здоровья". <br>Конференция будет проводиться через <a href="https://zoom.us/">zoom</a>. Идентификатор конференции: 599 534 2526 Пароль: Fbun54</p></p>');
            $message->send();
            /*Yii::$app->session->setFlash('success', "Заявка на регистрацию в программе отправлена! В течение 24 часов она будет расмотрена и Вы сможете зайти в систему. На Вашу почту были отправлены логин и пароль");
            return $this->goHome();*/
        }
		print_r('ok');exit();
    }
}
