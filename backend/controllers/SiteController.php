<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

use yii\helpers\Url;
use yii\rbac\DbManager;

use common\models\LoginForm;


/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        //ESTO PARA EL USUARIO NO REGISTRADO PARA TODOS
                        'actions' => ['login'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        //ESTO ES PARA EL USUARIO REGISTRADO
                        'actions' => ['logout', 'usuario', 'error'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        //ESTO ES PRUEBA
                        'actions' => ['logout','index', 'usuario', 'login'],
                        'allow' => true,
                        'roles' => ['test'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
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
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
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
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionUsuario()
    {
        //$r = new DbManager;
        //$r->init();
        //$r->assign($test, 3);


        //$variable = $r->getUserIdsByRole('test');
        //$variable = $r->getAssignments('3');
        $variable = Yii::$app->user->getIdentity();

        return $this->render('usuario', ['variable' => $variable]);
    }
}
