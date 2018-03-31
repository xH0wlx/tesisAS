<?php
use yii\helpers\Url;

$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'modules' => [
        'gridview' =>  [
            'class' => '\kartik\grid\Module'
            // enter optional module parameters below - only if you need to
            // use your own export download action or custom translation
            // message source
            // 'downloadAction' => 'gridview/export/download',
            // 'i18n' => []
        ]
    ],

    //PUEDE PROVOCAR QUE SI EL USUARIO SE EQUIVOCA MUCHAS VECES, LUEGO LO INTENTE MANDAR AL LOGIN DE NUEVO
/*    'on beforeAction' => function ($event){
        //Si el usuario no esta registrado, ingreso una dirección diferente de url y esa dirección es un error
        if(
            Yii::$app->user->isGuest && Yii::$app->getRequest()->url !== Url::to(Yii::$app->getUser()->loginUrl)
            && (Yii::$app->errorHandler->exception != null)
        ){
            //Yii::$app->getUser()->setReturnUrl(Yii::$app->getRequest()->url);
            //$session = Yii::$app->session;
            //$session->set('urlBefore', Yii::$app->getRequest()->url);
            Yii::$app->getResponse()->redirect(Url::to(\Yii::$app->getUser()->loginUrl))->send();
            return;
        }

    },*/

    'as beforeRequest' => [
        'class' => 'yii\filters\AccessControl',
        'rules' => [
            [
                //TODOS LOS USUARIOS NO REGISTRADOS PUEDEN VER EL LOGIN
                'allow' => true,
                'actions' => ['login', 'recoverpass', 'resetpass'],
                //SIN ROLES
            ],
            [
                //TODOS LOS USUARIOS AUTENTICADOS PUEDEN VER TODO
                //ACTIONS
                'allow' => true,
                'roles' => ['@'],
            ],
        ],
        'denyCallback' => function () {
            return Yii::$app->response->redirect(['site/login']);
        },
    ],

/*    'as beforeRequest' => [
        'class' => \yii\filters\AccessControl::className(),//AccessControl::className(),
        'rules' => [
            [
                'actions' => ['login'],
                'allow' => true,
            ],
            [
                // add all actions to take guest to login page
                'allow' => true,
                'roles' => ['@'],
            ],
        ],
        'denyCallback' => function () {
            $session = Yii::$app->session;
            $session->set('urlBefore', Yii::$app->getRequest()->url);
            //return Yii::$app->response->redirect(['site/login'])->send();
            return Yii::$app->getResponse()->redirect(['site/login'])->send();

        },
    ],*/

    'components' => [
        'funcionespropias' => [
            'class' => 'backend\components\FuncionesPropias',
        ],
        'mensaje' => [
            'class' => 'backend\components\Mensaje',
        ],
        'estados' => [
            'class' => 'backend\components\Estados',
        ],
        'correos' => [
            'class' => 'backend\components\Correos',
        ],
        'assetManager' => [
/*            'bundles' => [
                'yii\web\JqueryAsset' => [
                    'sourcePath' => null,
                    'basePath' => '@webroot',
                    'baseUrl' => '@web',
                    'js' => [
                        'js/jquery.js',
                    ]
                ],
            ],*/
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'useFileTransport' => false,
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.gmail.com',
                'username' => 'asfaceubb@gmail.com',
                'password' => 'asface@2016',
                'port' => '465',
                'encryption' => 'ssl',
                //'port' => '465',
                //'encryption' => 'ssl',
            ],
        ],
        'request' => [
            'csrfParam' => '_csrf-backend',
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
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],

        'authManager' => [
            'class' => 'yii\rbac\DbManager',
            'defaultRoles' => ['guest'],
        ],

        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                //'donpepe/y-sus-globos' => 'bitacora/index',
                /*[
                    'class' => 'backend\components\ImplementacionUrlRule',
                ],*/
            ],
        ],
        
    ],
    'params' => $params,
];
