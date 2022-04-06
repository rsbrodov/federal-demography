<?php

namespace backend\controllers;

use common\models\AnketPreschoolers;
use common\models\AuthAssignment;
use common\models\AuthItem;
use common\models\Municipality;
use common\models\EverydayClasses;
use common\models\Organization;
use common\models\PasswordChange;
use common\models\SignupForm;
use common\models\StudentsClass;
use common\models\TypeLager;
use Yii;
use common\models\FederalDistrict;
use common\models\Region;
use common\models\User;
use common\models\SignupUserForm;
use common\models\Classes;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\rbac\DbManager;
use yii\web\Request;
use common\models\UserSearch;

/**
 * UsersController implements the CRUD actions for User model.
 */
class UsersController extends Controller
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
        if(Yii::$app->user->can('admin')) {

            $searchModel = new UserSearch();
            $search = Yii::$app->request->queryParams;

            $dataProvider = $searchModel->search($search);


            return $this->render('index', [
                'dataProvider' => $dataProvider,
                'searchModel' => $searchModel,
            ]);
            
        }
        else{
            return $this->goHome();
        }
    }

    public function actionLevel()
    {


            $dataProvider = new ActiveDataProvider([
                'query' => User::find()->where(['organization_id' => Yii::$app->user->identity->organization_id]),
                'pagination' => [
                    'pageSize' => 20,
                ],
                'sort' => [
                    'defaultOrder' => [
                        'created_at' => SORT_DESC,
                        //'title' => SORT_ASC,
                    ]
                ],
            ]);

            return $this->render('level', [
                'dataProvider' => $dataProvider,
            ]);
    }

    public function actionRequest()
    {
        if(Yii::$app->user->can('admin')) {

            $dataProvider = new ActiveDataProvider([
                'query' => User::find()->where(['application' => [1, 2]]),
                'pagination' => [
                    'pageSize' => 20,
                ],
                'sort' => [
                    'defaultOrder' => [
                        'created_at' => SORT_DESC,
                        //'title' => SORT_ASC,
                    ]
                ],
            ]);

            return $this->render('request', [
                'dataProvider' => $dataProvider,
            ]);
        }
        else{
            return $this->goHome();
        }
    }

    public function actionAccept($id)
    {
        if(Yii::$app->user->can('admin')) {
            $user = User::findOne($id);
            $user->application = 0;//активация заявки
            $user->status = 10;//разрешение доступа в программу
            if($user->save()){
                Yii::$app->session->setFlash('success', "Пользователь активирован. Заявка исключена из этого списка");
                //return $this->redirect(['request']);
				return $this->redirect(Yii::$app->request->referrer);
            }
            else{
                Yii::$app->session->setFlash('error', "Произошла ошибка активации заявки. Пользователь не активирован");
                //return $this->redirect(['request']);
				return $this->redirect(Yii::$app->request->referrer);
            }

        }
        else{
            return $this->goHome();
        }
    }

    public function actionReject($id)
    {
        if(Yii::$app->user->can('admin')) {
            $user = User::findOne($id);
            $user->application = 2;//отклонение заявки
            if($user->save()){
                Yii::$app->session->setFlash('success', "Заявка отклонена. Вы в любое время можете изменить статус заявки");
                //return $this->redirect(['request']);
				return $this->redirect(Yii::$app->request->referrer);
            }
        }
        else{
            return $this->goHome();
        }
    }

    public function actionFirst(){
        $user = new User();
        $user->phone = '9237033776';
        $user->name = 'rusln';
        $user->photo = 'image/users/200x200.png';
        $user->created_at = time();
        $user->email = 'rsbrodov@mail.ru';
        $user->status = '10';
        $user->setPassword('24rs03*V');
        $user->generateAuthKey();

        if($user->save()){

            $r = new DbManager();
            $r->init();
            $assign = $r->createRole('user');
            $r->assign($assign, $user['id']);
            return 'ok';
        }
    }


    public function actionView($id)
    {
        if(Yii::$app->user->can('admin')) {
            return $this->render('view', [
                'model' => $this->findModel($id),
            ]);
        }
        else{
            return $this->goHome();
        }
    }


    public function actionCreate()
    {
        $model = new User();
        if(Yii::$app->user->can('admin')) {
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                $r = new DbManager();
                $r->init();
                $assign = $r->createRole('user');
                $r->assign($assign, $model->id);
                //return 'ok';
                return $this->redirect(['view', 'id' => $model->id]);
            }

            return $this->render('create', [
                'model' => $model,
            ]);
        }
        else{
            return $this->goHome();
        }
    }

    public function actionCreateuser()
    {
        $model = new User();
        $model2 = new SignupUserForm();
        $model3 = new Classes();
        $model4 = new Organization();



        if(Yii::$app->request->post())
        {
            $check_email_login = User::find()->where(['like', 'email', Yii::$app->request->post()['SignupUserForm']['email']])->orWhere(['like', 'login', Yii::$app->request->post()['SignupUserForm']['email']])->count();
            if ($check_email_login > 0)
            {
                Yii::$app->session->setFlash('error', "Пользователь с указаным email уже существует.");
                return $this->redirect(['createuser']);
            }

            $director = User::findOne(Yii::$app->user->id);

            $model->name = Yii::$app->request->post()['SignupUserForm']['name'];
            $model->login = Yii::$app->request->post()['SignupUserForm']['email'];
            $model->email = Yii::$app->request->post()['SignupUserForm']['email'];
            $model->organization_id = $director->organization_id;
            $model->parent_id = $director->id;
            $model->setPassword(Yii::$app->request->post()['SignupUserForm']['password']);
            $model->status = '10';
            $model->application = 0;
            $model->generateAuthKey();
            if (Yii::$app->request->post()['SignupUserForm']['post'] == 1)
            {
                $role = 'medic';
            }
            elseif (Yii::$app->request->post()['SignupUserForm']['post'] == 2)
            {
                $role = 'foodworker';
            }
            elseif (Yii::$app->request->post()['SignupUserForm']['post'] == 3)
            {
                $role = 'teacher';
            }

            else{
                Yii::$app->session->setFlash('error', "Произошла ошибка с определением роли. Данные не были сохранены");
                return $this->redirect(['site/index']);
            }
            $model->post = AuthItem::find()->where(['name' => $role])->one()->description;
            if ($model->save())
            {
                $r = new DbManager();
                $r->init();
                $assign = $r->createRole($role);
                $r->assign($assign, $model->id);

            }
            else{
                Yii::$app->session->setFlash('error', "Ошибка сохранения пользователя. Пользователь не был зарегистрирован!");
                return $this->goHome();
            }

            $message = Yii::$app->mailer->compose();
                        $message->setFrom(['1@niig.su' => '1@niig.su']);
                        $message->setTo($model->email)
                            ->setSubject('Программа питания')
                            ->setHtmlBody('<p>Добрый день, ' . $model->name . '!</p><p>Вы были зарегистрированы в программе. <p>Логин:' . $model->email . ' </p> <p>Пароль:' . Yii::$app->request->post()['SignupUserForm']['password'] . ' </p><p>Перейти к программному средству Вы можете по <a href="http://niig.su" >ссылке</a>. Баннер: "Национальный проект «Демография» (дети)"</p>');
                        $message->send();

            Yii::$app->session->setFlash('success', "Регистрация нового пользователя прошла успешно!");
            return $this->goHome();

        }

            return $this->render('create_user', [
                'model' => $model,
                'model2' => $model2,
            ]);
    }


    public function actionUpdate($id)
    {
        if(Yii::$app->user->can('admin')) {
            $model = $this->findModel($id);

            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }

            return $this->render('update', [
                'model' => $model,
            ]);
        }
        else{
            return $this->goHome();
        }
    }
	
	public function actionUpdateuser($id)
    {
        $my_organization = Yii::$app->user->identity->organization_id;
            $my_users = User::find()->where(['organization_id' => $my_organization])->all();
            $my_users_ids = [];
            foreach ($my_users as $m_user)
            {
                $my_users_ids[$m_user->id] = $m_user->id;
            }
			//print_r($my_users_ids);exit;
            if (!array_key_exists($id, $my_users_ids))
            {
                Yii::$app->session->setFlash('error', "Отказ в редактировании!");
                return $this->redirect(['level']);
            }
        $model = $this->findModel($id);
        if(Yii::$app->request->post())
        {
            $my_organization = Yii::$app->user->identity->organization_id;
            $my_users = User::find()->where(['organization_id' => $my_organization])->all();
            $my_users_ids = [];
            foreach ($my_users as $m_user)
            {
                $my_users_ids[$m_user->id] = $m_user->id;
            }
			//print_r($my_users_ids);exit;
            if (!array_key_exists($id, $my_users_ids))
            {
                Yii::$app->session->setFlash('error', "Отказ в редактировании!");
                return $this->redirect(['level']);
            }
            else
            {
                $model->name = Yii::$app->request->post()['User']['name'];
                $model->login = Yii::$app->request->post()['User']['email'];
                $model->email = Yii::$app->request->post()['User']['email'];
                if (Yii::$app->user->id != $id)
                {
                    if (Yii::$app->request->post()['User']['post'] == 1)
                    {
                        $role = 'medic';
                    }
                    elseif (Yii::$app->request->post()['User']['post'] == 2)
                    {
                        $role = 'foodworker';
                    }
                    elseif (Yii::$app->request->post()['User']['post'] == 3)
                    {
                        $role = 'teacher';
                    }

                    else
                    {
                        Yii::$app->session->setFlash('error', "Произошла ошибка с определением роли. Данные не были сохранены");
                        return $this->redirect(['level']);
                    }

                    $model->post = AuthItem::find()->where(['name' => $role])->one()->description;
                    if ($model->save())
                    {
                        $auth = AuthAssignment::find()->where(['user_id' => $id])->one();
                        $auth->item_name = $role;
                        $auth->save();
                    }
                }else{
                    $model->save();
                }


                Yii::$app->session->setFlash('success', "Данные успешно сохранены");
                return $this->redirect(['level']);
            }
        }
        return $this->render('updateuser', [
            'model' => $model,
        ]);
    }

    public function actionUpdateRequest($id)
    {
        if(Yii::$app->user->can('admin')) {
            $model = $this->findModel($id);
            $model_organization = Organization::findOne($model->organization_id);

            $me = User::find()->where(['id' => Yii::$app->user->id])->one();
            $my_organization = Organization::findOne($me->organization_id);

            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                Yii::$app->session->setFlash('success', "Обновлено!");
                return $this->redirect(['request']);
            }

            return $this->render('update-request', [
                'model' => $model,
                'model_organization' => $model_organization,
                'my_organization' => $my_organization,
            ]);
        }
        else{
            return $this->goHome();
        }
    }


    public function actionDelete($id)
    {
        if(Yii::$app->user->can('admin')) {
            $this->findModel($id)->delete();
            $role = AuthAssignment::find()->where(['user_id' => $id])->one();
            $role->delete();
            Yii::$app->session->setFlash('success', "Пользователь удален.");
            return $this->redirect(['index']);
        }

        if(Yii::$app->user->can('school_director')) {
            $users = User::find()->where(['organization_id' => Yii::$app->user->identity->organization_id])->all();
            foreach ($users as $user){
                $ids[] = $user->id;
            }
            if(!array_key_exists($id, $ids)){
                if(StudentsClass::find()->where(['user_id' => $id])->count() == 0)
                {
                    $this->findModel($id)->delete();
                    $role = AuthAssignment::find()->where(['user_id' => $id])->one();
                    $role->delete();
                    Yii::$app->session->setFlash('success', "Пользователь удален.");
                    return $this->redirect(['level']);
                }else{
                    Yii::$app->session->setFlash('error', "Вы не можете удалить пользователя, так как за ним закреплены классы. Открепите классы от этого пользователя, и после попробуйте удалить его снова.");
                    return $this->redirect(['level']);
                }
            }
            else{
                Yii::$app->session->setFlash('error', "Запрещено удаление этого пользователя. Пользователь не удален!");
                return $this->redirect(['level']);
            }
        }
        else{
            return $this->goHome();
        }
    }

    public function actionDeleteRequest($id)
    {
        $role = AuthAssignment::find()->where(['user_id' => $id])->one();
        if($role->item_name == 'rospotrebnadzor_camp' || $role->item_name == 'rospotrebnadzor_nutrition'){
            $this->findModel($id)->delete();
            $role->delete();
            Yii::$app->session->setFlash('success', "Заявка пользователя роспотребнадзора удалена.");
            //return $this->redirect(['request']);
			return $this->redirect(Yii::$app->request->referrer);
        }
        if($role->item_name != 'rospotrebnadzor_camp' || $role->item_name != 'rospotrebnadzor_nutrition'){
            $user = $this->findModel($id);
            $org = $user->organization_id;
            $users_from_this_org = User::find()->where(['organization_id' => $org])->count();
            /*print_r($users_from_this_org);
            exit;*/
            if($users_from_this_org == 1){
                $this->findModel($id)->delete();
                $role->delete();
                $organization = Organization::findOne($org);
                $organization->delete();
                Yii::$app->session->setFlash('success', "Заявка пользователя удалена. Созданная им организация также была удалена, так как в ней не было пользователей");
                //return $this->redirect(['request']);
				return $this->redirect(Yii::$app->request->referrer);
            }
            else{
                $this->findModel($id)->delete();
                $role->delete();
                Yii::$app->session->setFlash('success', "Заявка пользователя удалена. Организация прикрепленная к этому пользователю не была удалена, так как к ней прикреплены другие пользователи");
                //return $this->redirect(['request']);
				return $this->redirect(Yii::$app->request->referrer);
            }

        }
        Yii::$app->session->setFlash('error', "Неизвестная ошибка! Заявка не удалена");
        return $this->redirect(['request']);

    }


    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionLogin($id){
        $model = User::findOne($id);

        Yii::$app->user->login($model);

        return $this->redirect(['site/index']);
    }

    public function actionReport()
    {
        $model = new Organization();
        if (Yii::$app->request->post()) {
            /*print_r(Yii::$app->request->post());
            exit;*/
            $district = Yii::$app->request->post()['Organization']['federal_district_id'];
            //$district_for_district = $district;
            $region_for_district = Yii::$app->request->post()['Organization']['region_id'];
            $municipality_for_region = Yii::$app->request->post()['Organization']['municipality_id'];
            $type_lager = Yii::$app->request->post()['Organization']['type_lager_id'];
            if($district == 0) {
                $districts = FederalDistrict::find()->all();
            } else {
                $districts = FederalDistrict::find()->where(['id'=>$district])->all();
                $regions = Region::find()->where(['district_id'=>$district])->all();
                $region_item = ArrayHelper::map($regions, 'id', 'name');
                $municipality = Municipality::find()->where(['region_id'=>$region_for_district])->all();
                $municipality_item = ArrayHelper::map($municipality, 'id', 'name');
            }

            return $this->render('report', [
                'districts' => $districts, //ФО
                'region_item' => $region_item,
                'municipality_item' => $municipality_item,
                'model' => $model,
                'district_for_district' => $district,
                'region_for_district' => $region_for_district, //РЕГИОН
                'municipality_for_region' => $municipality_for_region,
                'type_lager_key' => $type_lager
            ]);
        }

        return $this->render('report',
            [
                'model' => $model
            ]);
    }
    public function actionNutritionReport()
    {
        $model = new Organization();
        if (Yii::$app->request->post()) {

            $district = Yii::$app->request->post()['Organization']['federal_district_id'];

            $region_for_district = Yii::$app->request->post()['Organization']['region_id'];
            $municipality_for_region = Yii::$app->request->post()['Organization']['municipality_id'];
            $type_org = Yii::$app->request->post()['Organization']['type_org'];
            if($district == 0) {
                $districts = FederalDistrict::find()->all();
            } else {
                $districts = FederalDistrict::find()->where(['id'=>$district])->all();
                $regions = Region::find()->where(['district_id'=>$district])->all();
                $region_item = ArrayHelper::map($regions, 'id', 'name');
                $municipality = Municipality::find()->where(['region_id'=>$region_for_district])->all();
                $municipality_item = ArrayHelper::map($municipality, 'id', 'name');
            }

            return $this->render('nutrition-report', [
                'districts' => $districts, //ФО
                'region_item' => $region_item,
                'municipality_item' => $municipality_item,
                'model' => $model,
                'district_for_district' => $district,
                'region_for_district' => $region_for_district, //РЕГИОН
                'municipality_for_region' => $municipality_for_region,
                'type_org_key' => $type_org
            ]);
        }

        return $this->render('nutrition-report',
            [
                'model' => $model
            ]);
    }
	public function actionNutritionReportRegion()
    {
        $model = new Organization();
        if (Yii::$app->request->post()) {

            $district = Yii::$app->request->post()['Organization']['federal_district_id'];

            $region_for_district = Yii::$app->request->post()['Organization']['region_id'];
            $municipality_for_region = Yii::$app->request->post()['Organization']['municipality_id'];
            $type_org = Yii::$app->request->post()['Organization']['type_org'];
            if($district == 0) {
                $districts = FederalDistrict::find()->all();
            } else {
                $districts = FederalDistrict::find()->where(['id'=>$district])->all();
                $regions = Region::find()->where(['district_id'=>$district])->all();
                $region_item = ArrayHelper::map($regions, 'id', 'name');
                $municipality = Municipality::find()->where(['region_id'=>$region_for_district])->all();
                $municipality_item = ArrayHelper::map($municipality, 'id', 'name');
            }

            return $this->render('nutrition-report-region', [
                'districts' => $districts, //ФО
                'region_item' => $region_item,
                'municipality_item' => $municipality_item,
                'model' => $model,
                'district_for_district' => $district,
                'region_for_district' => $region_for_district, //РЕГИОН
                'municipality_for_region' => $municipality_for_region,
                'type_org_key' => $type_org
            ]);
        }

        return $this->render('nutrition-report-region',
            [
                'model' => $model
            ]);
    }
	public function actionNutritionReportAllLvls()
    {
        $model = new Organization();
        if (Yii::$app->request->post()) {

            $district = Yii::$app->request->post()['Organization']['federal_district_id'];

            $region_for_district = Yii::$app->request->post()['Organization']['region_id'];
            $municipality_for_region = Yii::$app->request->post()['Organization']['municipality_id'];
            $type_org = Yii::$app->request->post()['Organization']['type_org'];
            if($district == 0) {
                $districts = FederalDistrict::find()->all();
            } else {
                $districts = FederalDistrict::find()->where(['id'=>$district])->all();
                $regions = Region::find()->where(['district_id'=>$district])->all();
                $region_item = ArrayHelper::map($regions, 'id', 'name');
                $municipality = Municipality::find()->where(['region_id'=>$region_for_district])->all();
                $municipality_item = ArrayHelper::map($municipality, 'id', 'name');
            }

            return $this->render('nutrition-report-all-lvls', [
                'districts' => $districts, //ФО
                'region_item' => $region_item,
                'municipality_item' => $municipality_item,
                'model' => $model,
                'district_for_district' => $district,
                'region_for_district' => $region_for_district, //РЕГИОН
                'municipality_for_region' => $municipality_for_region,
                'type_org_key' => $type_org
            ]);
        }

        return $this->render('nutrition-report-all-lvls',
            [
                'model' => $model
            ]);
    }

    public function actionProfile()
    {
        $signup_form = new SignupForm();
        if (Yii::$app->request->post()){
            $post = Yii::$app->request->post()['SignupForm'];
            //Проверка на то что пришедший емайл уникален, но он может быть равен моему старому
            $check_email = User::find()->where(['email' => $post['email']])->all();
            /*foreach($check_email as $c_email){
                if($c_email->email == User::findOne(Yii::$app->user->id)->email){
                    Yii::$app->session->setFlash('error', "EMAIL, который Вы указали, уже существует в системе! Используйте другой email.");
                    return $this->redirect(['profile']);
                }
            }*/
            $user = User::findOne(Yii::$app->user->id);
            $user->name = $post['name'];
            $user->post = $post['post'];
            $user->phone = $post['phone'];
            $user->email = $post['email'];
            $user->login = $post['email'];
            $user->save();

            Yii::$app->session->setFlash('success', "Личные данные успешно изменены");
            return $this->redirect(['profile']);
        }
        return $this->render('profile', [
            'signup_form' => $signup_form
        ]);
    }

    public function actionChangePassword() // смена пароля
    {
        $passChange = new PasswordChange();

        if ($passChange->load(Yii::$app->request->post()) && $passChange->validate())
        {
            $user = User::findOne(Yii::$app->user->id);
            $user->setPassword(Yii::$app->request->post()['PasswordChange']['password_new']);
            if ($user->save())
            {
                Yii::$app->session->setFlash('success', "ПАРОЛЬ ИЗМЕНЕН");
                return $this->redirect(['change-password']);
            }
        }

        return $this->render('change-password', [
            'model' => $passChange,
        ]);

        //return $this->goHome();
    }
}
