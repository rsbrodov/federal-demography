<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php',
    require __DIR__ . '/main-local.php'
);

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'homeUrl' =>'/',
    'language' =>'ru',
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'modules' => [
        'gridview' =>  [
            'class' => '\kartik\grid\Module',
        ],
        'prints' => [
            'class' => 'backend\modules\prints\PrintModules',
        ],
        'visit' => [
            'class' => 'backend\modules\visit\VisitModules',
        ],
        'menus-module' => [
            'class' => 'backend\modules\menus\MenusModules',
        ],
        'dropdown' => [
            'class' => 'backend\modules\dropdown\DropDownModules',
        ],
    ],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-backend',
            'baseUrl' => '',
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ]
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced-backend',
        ],
         'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=',
            'username' => 'root',
            'password' => 'Flvbygtgzrf2020!',
            'charset' => 'utf8',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.yandex.ru',
                'username' => '1@niig.su',//1@niig.su
                'password' => '',
                'port' => 465,
                'encryption' => 'ssl',
            ],
            'useFileTransport' => false,
        ],
		'mailer2' => [
            'class' => 'yii\swiftmailer\Mailer',
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.yandex.ru',
                'username' => 'error@niig.su',
                'password' => '',
                'port' => 465,
                'encryption' => 'ssl',
            ],
            'useFileTransport' => false,
        ],
		'mailer3' => [
            'class' => 'yii\swiftmailer\Mailer',
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.yandex.ru',
                'username' => 'info@webozone.ru',
                'password' => '',
                'port' => 465,
                'encryption' => 'ssl',
            ],
            'useFileTransport' => false,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
                [
                    'class' => 'yii\log\EmailTarget',
                    'mailer' => 'mailer2',
                    'levels' => ['error'],
                    'message' => [
                        'from' => ['error@niig.su'],
                        'to' => ['rsbrodov@mail.ru'/*, 'esperos.nsk@gmail.com'*/],
                        'subject' => 'Ошибка в программе питания',
                    ],
                    'except' => [
                        'yii\web\HttpException:404',
                        'yii\web\HttpException:429',
                        'yii\web\HeadersAlreadySentException',
						'yii\base\InvalidArgumentException'
                    ],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                ['class' => 'yii\rest\UrlRule', 'controller' => 'expenses-food'],
                '/login' => 'site/login',
            ],
        ],

        //myComponents
        'territory' => [
            'class' => 'app\components\TerritoryComponent',
        ],
        'chemical_value' => [
            'class' => 'app\components\ChemicalValueComponent',
        ],
        
    ],

    'params' => $params,
];
