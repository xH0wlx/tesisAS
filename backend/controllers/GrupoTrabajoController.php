<?php

namespace backend\controllers;

use backend\models\GrupoTrabajo;
use backend\models\Implementacion;
use Yii;
use backend\models\facultad;
use backend\models\search\FacultadSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;

class GrupoTrabajoController extends \yii\web\Controller
{

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                    'bulk-delete' => ['post'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['coordinador general'],
                    ],
                    [
                        'actions' => ['ver-grupo-trabajo', 'seleccion-asignatura'],
                        'allow' => true,
                        'roles' => ['alumno'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionVerGrupoTrabajo()
    {
        if(Yii::$app->user->can("alumno")){
            $session = Yii::$app->session;
            $session->open();
            if($session->has('idGrupoBitacora')){
                $idGrupo = $session->get('idGrupoBitacora');
                $modeloGrupo = GrupoTrabajo::findOne(intval($idGrupo));
                $alumnos = $modeloGrupo->alumnoInscritoSeccionIdAlumnoInscritoSeccions;
                $session->close();

                return $this->render('vistaGrupoTrabajo',[
                    'alumnos' => $alumnos,
                ]);
            }else{
                echo "Error al obtener el registro del Grupo de Trabajo";
                die;
            }

        }
    }

    public function actionSeleccionAsignatura(){
        $request = Yii::$app->request;
        if ($request->isPost) {
            $idGrupoTrabajo = intval($request->post("grupo"));
            if($idGrupoTrabajo == 0){
                Yii::$app->getSession()->setFlash('success', [
                    'type' => 'error',
                    'duration' => 5000,
                    //'icon' => 'fa fa-users',
                    'message' => 'Debe seleccionar una sección.',
                    'title' => 'Error',
                    'positonY' => 'top',
                    //'positonX' => 'left'
                ]);
                return $this->refresh();
            }
            $session = Yii::$app->session;
            $session->open();
            $session->set('idGrupoBitacora', $idGrupoTrabajo);
            $session->close();
            return $this->redirect(['ver-grupo-trabajo']);
        } else {
            $modelos = Implementacion::find()
                ->leftJoin('seccion', 'id_implementacion = implementacion_id_implementacion')
                ->leftJoin('alumno_inscrito_seccion', 'id_seccion = seccion_id_seccion')
                ->where(['implementacion.estado' => 1])
                ->andWhere(['alumno_inscrito_seccion.alumno_rut_alumno' => Yii::$app->user->identity->username])
                ->all();

            $modelos2 = Yii::$app->db->createCommand('SELECT * FROM asignatura JOIN `implementacion` ON cod_asignatura = asignatura_cod_asignatura 
            JOIN seccion on id_implementacion = seccion.implementacion_id_implementacion JOIN
             alumno_inscrito_seccion ON seccion.id_seccion = alumno_inscrito_seccion.seccion_id_seccion
             JOIN alumno_inscrito_has_grupo_trabajo ON alumno_inscrito_seccion.id_alumno_inscrito_seccion = alumno_inscrito_has_grupo_trabajo.alumno_inscrito_seccion_id_alumno_inscrito_seccion
              WHERE implementacion.estado = 1 AND alumno_inscrito_seccion.alumno_rut_alumno = :rut ORDER BY numero_seccion',
                [":rut" => Yii::$app->user->identity->username])->queryAll();
            return $this->render('seleccionGrupo', [
                'modelos' => $modelos,
                'modelos2' => $modelos2,
            ]);
        }
    }

}
