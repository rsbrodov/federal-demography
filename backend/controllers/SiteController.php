<?php
namespace backend\controllers;
use common\models\UserAutorizationStatistic;
use common\models\Municipality;
use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use common\models\ChangeForm;
use common\models\SignupForm;
use common\models\User;
use common\models\Organization;
use common\models\Region;
use yii\rbac\DbManager;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'logout', 'error', 'signup', 'subjectslist', 'subjectslistnutrition','municipalitylist', 'mail', 'signup-nutrition', 'orglist2'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['login', 'logout', 'index', 'select-organization', 'session-delete', 'error', 'subjectslist', 'subjectslistnutrition','municipalitylist', 'orglist' , 'orglist2', 'orglist3', 'download-document-index', 'download-document'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            /*'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],*/
        ];
    }


    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
		/*$timess = UserAutorizationStatistic::find()->all();
        foreach($timess as $times){
            /times->user_id = Yii::$app->user->id;
            $times->time_auth = strtotime($times->created_at);
            $times->save();
        }*/
        if (!Yii::$app->user->isGuest) {
            return $this->redirect(['/site/index']);
        }

        $model = new LoginForm();
        $change = new ChangeForm();

        if($change->load(Yii::$app->request->post())){
            if($change->changePassword()){
                Yii::$app->session->setFlash('changePassword', 'Дождитесь письма с новым паролем.', false);
                $this->redirect(['/site/login']);
            }
            else{
                Yii::$app->session->setFlash('changeErrorPassword', 'Дождитесь письма с новым паролем.', false);
                $this->redirect(['/site/login']);
            }
        }

        if ($model->load(Yii::$app->request->post()) && $model->login()) {
			 if(Yii::$app->user->id > 1){
                $stat = new UserAutorizationStatistic();
                $stat->user_id = Yii::$app->user->id;
                $stat->time_auth = time();
                $stat->save();
                //print_r($stat);exit;
            }
            return $this->goBack();
        } else {
            $model->password = '';

            return $this->render('login', [
                'model' => $model,
                'change' => $change,
            ]);
        }
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
		if (Yii::$app->user->isGuest) {
            return $this->redirect(['/site/index']);
        }
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionSelectOrganization()
    {

        if (Yii::$app->request->post())
        {
            if(Yii::$app->user->can('rospotrebnadzor_camp') || Yii::$app->user->can('rospotrebnadzor_nutrition') || Yii::$app->user->can('minobr'))
            {
                $organization = Yii::$app->request->post()['Menus']['organization_id'];
            }
            else{
                $organization = Yii::$app->request->post()['SelectOrgForm']['organization'];
            }
            $session = Yii::$app->session;
            $session['organization_id'] = $organization;
            $organization_id = $session['organization_id'];
        }

        Yii::$app->session->setFlash('success', "Данные организации подгружены");
        return $this->redirect(Yii::$app->request->referrer);
    }	
	
	
	public function actionSessionDelete()
    {

            $session = Yii::$app->session;
            unset($session['organization_id']);

        Yii::$app->session->setFlash('success', "Вы вышли из выбранной организации");
        return $this->redirect(Yii::$app->request->referrer);
    }


    public function actionMail()
    {	
		/*
        $message = Yii::$app->mailer->compose();
                $message->setFrom(['registration@niig.su'=>'#WebZone']);
                $message->setTo('rsbrodov@mail.ru')
                ->setSubject('Тестовое сообщение')
                ->setHtmlBody('<p>Личный кабинет</p>');
                $message->send();
				
        return 'ok';
        //return $this->goHome();*/
    }
    //Регистрация для программного средства "Оценка эффективности оздоровления детей"
    public function actionSignup()
    {
        $model = new SignupForm();

        if (Yii::$app->request->post())
        {
            /*print_r(Yii::$app->request->post());
            exit;*/
            $check_email_login = User::find()->where(['like', 'email', Yii::$app->request->post()['SignupForm']['email']])->orWhere(['like', 'login', Yii::$app->request->post()['SignupForm']['email']])->count();
            if ($check_email_login > 0)
            {
                Yii::$app->session->setFlash('error', "Пользователь с указаным email уже существует.");
                return $this->redirect(['signup']);
            }
            /*Если организация роспотреьнадзор*/
            if (Yii::$app->request->post()['SignupForm']['type_org'] == 7)
            {
                $user = new User();
                $organization = Organization::find()->where(['region_id' => Yii::$app->request->post()['SignupForm']['region_id'], 'type_org' => 7])->one();
                if (empty($organization))
                {
                    Yii::$app->session->setFlash('error', "Ошибка регистрации пользователя в программе. Роспотребнадзор по данному региону не зарегистрирован.");
                    return $this->redirect(['signup']);
                }
                $user->phone = Yii::$app->request->post()['SignupForm']['phone'];
                $user->name = Yii::$app->request->post()['SignupForm']['name'];
                $user->login = Yii::$app->request->post()['SignupForm']['email'];
                $user->post = Yii::$app->request->post()['SignupForm']['post'];
                $user->created_at = time();
                $user->email = Yii::$app->request->post()['SignupForm']['email'];
                $user->application = 1;//статус новой заявкиж
                $user->status = 9;//неактив
                $user->organization_id = $organization->id;
                $user->parent_id = 0;
                $user->setPassword(Yii::$app->request->post()['SignupForm']['password']);
                $user->generateAuthKey();

                $role = 'rospotrebnadzor_camp';

                if ($user->save())
                {
                    $r = new DbManager();
                    $r->init();
                    $assign = $r->createRole($role);
                    $r->assign($assign, $user->id);

                    //ОТКЛЮЧИЛИ НА ГЕГЕМОНЕ ОТПРАВКУ ПИСЕМ
                    //$message = Yii::$app->mailer->compose();
                    //$message->setFrom(['registration@niig.su' => 'registration@niig.su']);
                    /*$message->setTo($user->email)
                        ->setSubject('Оценка эффективности оздоровления детей')
                        ->setHtmlBody('<p>Добрый день, ' . $user->name . '!</p><p>Вы были зарегистрированы в программе. <p>Логин:' . Yii::$app->request->post()['SignupForm']['email'] . ' </p> <p>Пароль:' . Yii::$app->request->post()['SignupForm']['password'] . ' </p><p>Перейти к программному средству Вы можете по <a href="https://demography.site/" >ссылке</a>. <br> Ваша заявка будет не активирована и авторизоваться в системе Вы не сможете.
Доступ для входа в программное средство Вам будет открыт в течении 48 часов, после проверки Вашей заявки.</p>');
$message->send();*/
                    Yii::$app->session->setFlash('success', "Заявка на регистрацию в программе отправлена! В течение 48 часов она будет расмотрена и Вы сможете зайти в систему.");
		    return $this->goHome();

                }
                else
                {
                    Yii::$app->session->setFlash('error', "Ошибка сохранения пользователя. Пользователь не был зарегистрирован");
                    return $this->goHome();
                }

            }
            else
            {
                $user = new User();
                $organization = new Organization();

                $organization->federal_district_id = Yii::$app->request->post()['SignupForm']['federal_district_id'];
                $organization->region_id = Yii::$app->request->post()['SignupForm']['region_id'];
                $organization->type_org = Yii::$app->request->post()['SignupForm']['type_org'];
                $organization->municipality_id = Yii::$app->request->post()['SignupForm']['municipality'];
                $organization->title = Yii::$app->request->post()['SignupForm']['title'];
                $organization->organizator_food = 1;
                if(Yii::$app->request->post()['SignupForm']['type_org'] !=1)
                {
                    $organization->type_lager_id = 0;

                }
                else{
                    $organization->type_lager_id = Yii::$app->request->post()['SignupForm']['type_lager_id'];
                }

                if ($organization->save(false))
                {
                    $user->phone = Yii::$app->request->post()['SignupForm']['phone'];
                    $user->name = Yii::$app->request->post()['SignupForm']['name'];
                    $user->login = Yii::$app->request->post()['SignupForm']['email'];
                    $user->post = Yii::$app->request->post()['SignupForm']['post'];
                    $user->created_at = time();
                    $user->email = Yii::$app->request->post()['SignupForm']['email'];
                    $user->application = 1;//статус новой заявкиж
                    $user->status = 9;//неактив
                    $user->organization_id = $organization->id;
                    $user->parent_id = 0;
                    $user->setPassword(Yii::$app->request->post()['SignupForm']['password']);
                    $user->generateAuthKey();

                    if (Yii::$app->request->post()['SignupForm']['type_org'] == 1)
                    {
                        $role = 'camp_director';
                    }
                    elseif (Yii::$app->request->post()['SignupForm']['type_org'] == 5)
                    {
                        $role = 'kindergarten_director';
                    }
                    elseif (Yii::$app->request->post()['SignupForm']['type_org'] == 3)
                    {
                        $role = 'school_director';
                    }
                    elseif (Yii::$app->request->post()['SignupForm']['type_org'] == 2)
                    {
                        $role = 'subject_minobr';
                    }
                    /*elseif (Yii::$app->request->post()['SignupForm']['type_org'] == 5)
                    {
                        $role = 'food_dire';
                    }
                    elseif (Yii::$app->request->post()['SignupForm']['type_org'] == 6)
                    {
                        $role = 'internat_director';
                    }*/
                    elseif (Yii::$app->request->post()['SignupForm']['type_org'] == 7)
                    {
                        $role = 'rospotrebnadzor_camp';
                    }
                    else
                    {
                        Yii::$app->session->setFlash('error', "Произошла ошибка с определением роли. Данные не были сохранены");
                        return $this->redirect(['signup']);
                    }


                    if ($user->save())
                    {
                        $r = new DbManager();
                        $r->init();
                        $assign = $r->createRole($role);
                        $r->assign($assign, $user->id);

  			/*
                        $message = Yii::$app->mailer->compose();
                        $message->setFrom(['registration@niig.su' => 'registration@niig.su']);
                        $message->setTo($user->email)
                            ->setSubject('Программа оценка эффективности и организации оздоровления детей')
                            ->setHtmlBody('<p>Добрый день, ' . $user->name . '!</p><p>Вы были зарегистрированы в программе. <p>Логин:' . Yii::$app->request->post()['SignupForm']['email'] . ' </p> <p>Пароль:' . Yii::$app->request->post()['SignupForm']['password'] . ' </p><p>Перейти к программному средству Вы можете по <a href="http://niig.su" >ссылке</a>. Баннер: "Пилотный проект оценка эффективности оздоровления детей".  Вход в программное средство будет доступен в течении 48 часов.</p>');
			$message->send();*/

                    }
                    else
                    {
                        $organization->delete();
                        Yii::$app->session->setFlash('error', "Ошибка сохранения пользователя. Пользователь не был зарегистрирован");
                        return $this->goHome();
                    }

                }
                else
                {
                    Yii::$app->session->setFlash('error', "Ошибка при регистрации. Организация и пользователь не были зарегистрированы");
                    return $this->goHome();
                }
                Yii::$app->session->setFlash('success', "Заявка на регистрацию в программе отправлена! В течение 48 часов она будет расмотрена и Вы сможете зайти в систему.");
                return $this->goHome();
            }
        }
        return $this->render('signup', [
            'model' => $model,
        ]);

    }
    //Регистрация для программного средства "Питание и мониторинг здоровья"
    public function actionSignupNutrition()
    {
        $model = new SignupForm();

        if (Yii::$app->request->post())
        {
            /*print_r(Yii::$app->request->post());
            exit;*/
            $check_email_login = User::find()->where(['like', 'email', Yii::$app->request->post()['SignupForm']['email']])->orWhere(['like', 'login', Yii::$app->request->post()['SignupForm']['email']])->count();
            if ($check_email_login > 0)
            {
                Yii::$app->session->setFlash('error', "Пользователь с указаным email уже существует.");
                return $this->redirect(['signup-nutrition']);
            }
            /*Если организация роспотреьнадзор*/
            if (Yii::$app->request->post()['SignupForm']['type_org'] == 7)
            {
                $user = new User();
                $organization = Organization::find()->where(['region_id' => Yii::$app->request->post()['SignupForm']['region_id'], 'type_org' => 7])->one();
                if (empty($organization))
                {
                    Yii::$app->session->setFlash('error', "Ошибка регистрации пользователя в программе. Роспотребнадзор по данному региону не зарегистрирован.");
                    return $this->redirect(['signup']);
                }
                $user->phone = Yii::$app->request->post()['SignupForm']['phone'];
                $user->name = Yii::$app->request->post()['SignupForm']['name'];
                $user->login = Yii::$app->request->post()['SignupForm']['email'];
                $user->post = Yii::$app->request->post()['SignupForm']['post'];
                $user->created_at = time();
                $user->email = Yii::$app->request->post()['SignupForm']['email'];
                $user->application = 1;//статус новой заявкиж
                $user->status = 9;//неактив
                $user->organization_id = $organization->id;
                $user->parent_id = 0;
                $user->setPassword(Yii::$app->request->post()['SignupForm']['password']);
                $user->generateAuthKey();

                $role = 'rospotrebnadzor_nutrition';

                if ($user->save())
                {
                    $r = new DbManager();
                    $r->init();
                    $assign = $r->createRole($role);
                    $r->assign($assign, $user->id);

                    //ОТКЛЮЧИЛИ НА ГЕГЕМОНЕ ОТПРАВКУ ПИСЕМ
					
                    $message = Yii::$app->mailer->compose();
                    $message->setFrom(['57b66227@niig.su' => '1@niig.su']);
                    $message->setTo($user->email)
                        ->setSubject('Программа Питание и мониторинг здоровья')
                        ->setHtmlBody('<p>Здравствуйте, ' . $user->name . '!</p><p>Вы были зарегистрированы в программе. <p>Логин:' . Yii::$app->request->post()['SignupForm']['email'] . ' </p> <p>Пароль:' . Yii::$app->request->post()['SignupForm']['password'] . ' </p><p>Перейти к программному средству Вы можете по <a href="https://demography.site/login" >ссылке</a>. Вход в программное средство будет доступен в течении 48 часов после момента регистрация, так как специалисты проверяют каждую заявку на правильность заполнения.</p>');
                    $message->send();
					
                    Yii::$app->session->setFlash('success', "Заявка на регистрацию в программе отправлена! В течение 48 часов она будет расмотрена и Вы сможете зайти в систему.");
                    return $this->goHome();

                }
                else
                {
                    Yii::$app->session->setFlash('error', "Ошибка сохранения пользователя. Пользователь не был зарегистрирован");
                    return $this->goHome();
                }

            }
            else
            {
                $user = new User();
                $organization = new Organization();

                $organization->federal_district_id = Yii::$app->request->post()['SignupForm']['federal_district_id'];
                $organization->region_id = Yii::$app->request->post()['SignupForm']['region_id'];
                $organization->type_org = Yii::$app->request->post()['SignupForm']['type_org'];
                $organization->municipality_id = Yii::$app->request->post()['SignupForm']['municipality'];
				$organization->naseleni_punkt = Yii::$app->request->post()['SignupForm']['naseleni_punkt'];
                $organization->name_dir = Yii::$app->request->post()['SignupForm']['name_dir'];
                $organization->title = Yii::$app->request->post()['SignupForm']['title'];
                if(Yii::$app->request->post()['SignupForm']['type_org'] !=1)
                {
                    $organization->type_lager_id = 0;

                }
                else{
                    $organization->type_lager_id = Yii::$app->request->post()['SignupForm']['type_lager_id'];
                }

                if ($organization->save(false))
                {
                    $user->phone = Yii::$app->request->post()['SignupForm']['phone'];
                    $user->name = Yii::$app->request->post()['SignupForm']['name'];
                    $user->login = Yii::$app->request->post()['SignupForm']['email'];
                    $user->post = Yii::$app->request->post()['SignupForm']['post'];
                    $user->created_at = time();
                    $user->email = Yii::$app->request->post()['SignupForm']['email'];
                    $user->application = 1;//статус новой заявкиж
                    $user->status = 9;//неактив
                    $user->organization_id = $organization->id;
                    $user->parent_id = 0;
                    $user->setPassword(Yii::$app->request->post()['SignupForm']['password']);
                    $user->generateAuthKey();

                    if (Yii::$app->request->post()['SignupForm']['type_org'] == 1)
                    {
                        $role = 'camp_director';
                    }
                    elseif (Yii::$app->request->post()['SignupForm']['type_org'] == 5)
                    {
                        $role = 'kindergarten_director';
                    }
                    elseif (Yii::$app->request->post()['SignupForm']['type_org'] == 3)
                    {
                        $role = 'school_director';
                    }
                    elseif (Yii::$app->request->post()['SignupForm']['type_org'] == 2)
                    {
                        $role = 'subject_minobr';
                    }
                    elseif (Yii::$app->request->post()['SignupForm']['type_org'] == 4)
                    {
                        $role = 'food_director';
                    }
                    elseif (Yii::$app->request->post()['SignupForm']['type_org'] == 6)
                    {
                        $role = 'internat_director';
                    }
                    elseif (Yii::$app->request->post()['SignupForm']['type_org'] == 7)
                    {
                        $role = 'rospotrebnadzor_nutrition';
                    }
					elseif (Yii::$app->request->post()['SignupForm']['type_org'] == 8)
                    {
                        $role = 'medicine_director';
                    }
                    else
                    {
                        Yii::$app->session->setFlash('error', "Произошла ошибка с определением роли. Данные не были сохранены");
                        return $this->redirect(['signup-nutrition']);
                    }


                    if ($user->save())
                    {
                        $r = new DbManager();
                        $r->init();
                        $assign = $r->createRole($role);
                        $r->assign($assign, $user->id);

		        /*				
                        $message = Yii::$app->mailer->compose();
                        $message->setFrom(['registration@niig.su' => 'registration@niig.su']);
                        $message->setTo($user->email)
                            ->setSubject('Программа оценка эффективности и организации оздоровления детей')
                            ->setHtmlBody('<p>Добрый день, ' . $user->name . '!</p><p>Вы были зарегистрированы в программе. <p>Логин:' . Yii::$app->request->post()['SignupForm']['email'] . ' </p> <p>Пароль:' . Yii::$app->request->post()['SignupForm']['password'] . ' </p><p>Перейти к программному средству Вы можете по <a href="http://niig.su" >ссылке</a>. Баннер: "Пилотный проект оценка эффективности оздоровления детей".  Вход в программное средство будет доступен в течении 48 часов.</p>');
                        $message->send();
			 */			

                    }
                    else
                    {
                        $organization->delete();
                        Yii::$app->session->setFlash('error', "Ошибка сохранения пользователя. Пользователь не был зарегистрирован");
                        return $this->goHome();
                    }

                }
                else
                {
                    Yii::$app->session->setFlash('error', "Ошибка при регистрации. Организация и пользователь не были зарегистрированы");
                    return $this->goHome();
                }
                Yii::$app->session->setFlash('success', "Заявка на регистрацию в программе отправлена! В течение 48 часов она будет расмотрена и Вы сможете зайти в систему.");
                return $this->goHome();
            }
        }
        return $this->render('signup-nutrition', [
            'model' => $model,
        ]);

    }
   /*Подставляет регионы в выпадающий список ПИТАНИЕ ТОЛЬКО ОМСК и НОВОСИБ КЕМЕО*/
    public function actionSubjectslistnutrition($id){

        $groups = Region::find()->where(['district_id'=>$id])->orderby(['name' => SORT_ASC])->all();
        //$groups = Region::find()->where(['id'=>[48, 49, 46]])->orderby(['name' => SORT_ASC])->all();

        if($id == 1){

        }
        echo '<option value=" ">Выберите регион...</option>';
        if(!empty($groups)){
            foreach ($groups as $key => $group) {
                echo '<option value="'.$group->id.'">'.$group->name.'</option>';
            }
        }
    }


    /*Подставляет регионы в выпадающий список ЛАГЕРЯ ВСЕ РЕГИОНЫ*/
    public function actionSubjectslist($id){

        $groups = Region::find()->where(['district_id'=>$id])->orderby(['name' => SORT_ASC])->all();
        //$groups = Region::find()->where(['id'=>[48, 49]])->orderby(['name' => SORT_ASC])->all();

        if($id == 1){

        }
        echo '<option value=" ">Выберите регион...</option>';
        if(!empty($groups)){
            foreach ($groups as $key => $group) {
                echo '<option value="'.$group->id.'">'.$group->name.'</option>';
            }
        }
    }
    /*Подставляет муниципальные образования в выпадающий список*/
    public function actionMunicipalitylist($id){

        $groups = Municipality::find()->where(['region_id'=>$id])->orderby(['name' => SORT_ASC])->all();

        echo '<option value=" ">Выберите муниципальное образование...</option>';
        if(!empty($groups)){
            foreach ($groups as $key => $group) {
                echo '<option value="'.$group->id.'">'.$group->name.'</option>';
            }
        }
    }

    /*Подставляет организации в выпадающий список*/
    public function actionOrglist($id){
        //Если Вы организатор питания
        if(Organization::findOne(Yii::$app->user->identity->organization_id)->type_org == 4){
            $groups = Organization::find()->where(['municipality_id'=>$id, 'type_org' => 3])->orderby(['title' => SORT_ASC])->all();
            echo '<option value=" ">Выберите образовательную организацию...</option>';
        }
        //Если Вы представитель школы
        if(Organization::findOne(Yii::$app->user->identity->organization_id)->type_org == 3){
            $groups = Organization::find()->where(['municipality_id'=>$id, 'type_org' => 4])->orderby(['title' => SORT_ASC])->all();
            echo '<option value=" ">Выберите организатора питания...</option>';
        }


        if(!empty($groups)){
            foreach ($groups as $key => $group) {
                echo '<option value="'.$group->id.'">'.$group->title.'</option>';
            }
        }
    }
	
	
	public function actionOrglist2($id){
        //Если Вы организатор питания

            $groups = Organization::find()->where(['municipality_id'=>$id, 'type_org' => 1])->orderby(['title' => SORT_ASC])->all();
            echo '<option value=" ">Выберите организацию...</option>';

        if(!empty($groups)){
            foreach ($groups as $key => $group) {
                echo '<option value="'.$group->id.'">'.$group->title.'</option>';
            }
        }
    }
	
	/*Подставляет организации в выпадающий список орг пит выбирает орг пита*/
    public function actionOrglist3($id){
        //Если Вы организатор питания
        if(Organization::findOne(Yii::$app->user->identity->organization_id)->type_org == 4){
            $groups = Organization::find()->where(['municipality_id'=>$id, 'type_org' => 4])->orderby(['title' => SORT_ASC])->all();
            echo '<option value=" ">Выберите организатора питания...</option>';
        }

        if(!empty($groups)){
            foreach ($groups as $key => $group) {
                echo '<option value="'.$group->id.'">'.$group->title.'</option>';
            }
        }
    }

    public function actionDownloadDocument($name){
        $file = '../web/files/'.$name;

        if (file_exists($file)) {
            return \Yii::$app->response->sendFile($file);
        }
        thrownew\Exception('Не удалось скачать файл. Обратитесь в чат');
    }

    public function actionDownloadDocumentIndex()
    {
       return $this->render('download-document-index');
    }
}
