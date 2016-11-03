<?php

namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Json;

//TODOS LOS MODELOS A UTILIZAR POR ESTE CONTROLADOR
use app\models\Sede;
use app\models\Carrera;
use app\models\Asignatura;

class ImplementacionController extends \yii\web\Controller
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
                        'actions' => [],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],/*
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],*/
        ];
    }
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionNueva(){
        $modelSede = new Sede();
        $modelCarrera = new Carrera();
        $modelAsignatura = new Asignatura();

        if ($modelCarrera->load(Yii::$app->request->post()) && $modelCarrera->save()/*($model->load(Yii::$app->request->post()) && $modeloProyecto->load(Yii::$app->request->post()))
            && ($model->save() /*&& $modeloProyecto->save()*/
        ) {
            return $this->redirect(['view', 'id' => $modelCarrera->id_carrera]);
        }else {
            $sedes = Sede::find()->select(['nombre', 'id_sede'])->indexBy('id_sede')->column();

            return $this->render('primerpaso', [
                'modelSede' => $modelSede,
                'sedes' => $sedes,
                'modelCarrera' => $modelCarrera,
                'modelAsignatura' => $modelAsignatura,
            ]);
        }
    }

    public function actionCarrera(){
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if ($parents != null) {
                $inputSede = $parents[0];
                $out = Carrera::find()->select(['id_carrera as id', 'nombre as name'])->where(['id_sede' => $inputSede])->asArray()->all();
                echo Json::encode(['output'=>$out, 'selected'=>'']);
                return;
            }
        }
        echo Json::encode(['output'=>'', 'selected'=>'']);
    }

    public function actionAsignatura(){
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if ($parents != null) {
                $inputCarrera = $parents[0];
                $out = Asignatura::find()->select(['cod_asignatura as id', 'nombre as name'])->where(['id_carrera' => $inputCarrera])->asArray()->all();
                echo Json::encode(['output'=>$out, 'selected'=>'']);
                return;
            }
        }
        echo Json::encode(['output'=>'', 'selected'=>'']);
    }


}//FIN CONTROLADOR
