<?php

namespace backend\controllers;

use backend\models\search\AlumnoInscritoSeccionSearch;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Json;

use moonland\phpexcel\Excel;


class ImplementacionAjaxController extends \yii\web\Controller
{
    public function behaviors()
    {
        return [
                'access' => [
                    'class' => AccessControl::className(),
                    'rules' => [
                        [
                            'allow' => true,
                            'roles' => ['coordinador general'],
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
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionCargarIndex()
    {
        if(isset($_GET["codAsignatura"])){
            $codAsignatura=$_GET["codAsignatura"];
            $asignatura = Asignatura::findOne($codAsignatura);
        }else{
            $codAsignatura=-1;
        }
        return $this->renderAjax('index', ['asignatura' => $asignatura]);
    }

    public function actionCargarAlumnosInscritos()
    {
        $searchModel = new AlumnoInscritoSeccionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->renderAjax('//alumno-inscrito-seccion/index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

}//FIN CONTROLADOR