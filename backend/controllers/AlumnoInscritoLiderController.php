<?php

namespace backend\controllers;

use backend\models\GrupoTrabajo;
use backend\models\GrupoTrabajoHasScb;
use backend\models\Implementacion;
use backend\models\Model;
use backend\models\Scb;
use backend\models\Seccion;
use Yii;
use yii\base\Exception;
use yii\filters\AccessControl;
use yii\helpers\Json;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\helpers\Html;
use yii\web\HttpException;

/**
 * AlumnoInscritoLiderController implements the CRUD actions for alumnoInscritoLider model.
 */
class AlumnoInscritoLiderController extends Controller
{
    /**
     * @inheritdoc
     */
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
                ],
            ],
        ];
    }

    public function actionAsignarLider($idSeccion){
        $seccionSeleccionada = Seccion::findOne($idSeccion);
        if($seccionSeleccionada == null){ throw new HttpException(404, 'La sección no existe.'); }
        $gruposTrabajo = $seccionSeleccionada->grupoTrabajos;
        if($gruposTrabajo == null){ throw new HttpException(404, 'La sección no tiene Grupos De Trabajo.'); }


        $request = Yii::$app->request;
        if ($request->isPost) {
            $modelosLideres = $gruposTrabajo;

            $bandera = true;
            if(Model::loadMultiple($modelosLideres, Yii::$app->request->post()) && Model::validateMultiple($modelosLideres)){

                $transaction = \Yii::$app->db->beginTransaction();
                try{
                    foreach ($modelosLideres as $modeloLider) {
                        if(!$modeloLider->save(false)){
                            $bandera = false;
                            $transaction->rollBack();
                        }
                    }
                    if($bandera){
                        $transaction->commit();
                        Yii::$app->mensaje->mensajeGrowl('success', 'Líderes guardados exitosamente.');
                    }else{
                        Yii::$app->mensaje->mensajeGrowl('error', 'Error al guardar líderes.');
                    }
                } catch (Exception $e) {
                    $transaction->rollBack();
                }
            }

            return $this->redirect(['/implementacion/panel-implementacion', 'idImplementacion' => $seccionSeleccionada->implementacion_id_implementacion ]);
        } else {
            $implementacion = new Implementacion();
            $dataSelect2 = $implementacion->getSociosParticipantesImplementacion(Yii::$app->request->get('idImplementacion'));

            return $this->render('asignarMultiple', [
                'gruposTrabajo' => $gruposTrabajo,
                'modelosLideres' => $gruposTrabajo,
                'dataSelect2' => $dataSelect2,
            ]);
        }//FIN ELSE (GET)
    }

    public function actionSubsociosb(){
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if ($parents != null) {
                if (!empty($_POST['depdrop_params'])) {
                    $inputCarrera = $parents[0];
                    $params = $_POST['depdrop_params'];
                    $param1 = $params[0]; //ID SCB
                    $out = Scb::find()->select(['id_scb as id', 'nombre_negocio as name'])->where(['sci_id_sci' => $inputCarrera])->asArray()->all();
                    $selected = $param1;
                    echo Json::encode(['output'=>$out, 'selected'=>$selected]);
                    return;
                }else{
                    $inputCarrera = $parents[0]; //ID FACULTAD
                    $out = Scb::find()->select(['id_scb as id', 'nombre_negocio as name'])->where(['sci_id_sci' => $inputCarrera])->asArray()->all();
                    echo Json::encode(['output'=>$out, 'selected'=>'']);
                }
                return;
            }
        }
        echo Json::encode(['output'=>'', 'selected'=>'']);
    }

    public function actionCrearScb($idGrupoTrabajo){
        $grupoTrabajo = GrupoTrabajo::findOne($idGrupoTrabajo);
        if( $grupoTrabajo == null ){ die("No existe grupo de trabajo con la ID asociada."); }

        $request = Yii::$app->request;
        if($request->isAjax){
            $modeloAsignacion = new GrupoTrabajoHasScb();
            $modeloAsignacion->grupo_trabajo_id_grupo_trabajo = $idGrupoTrabajo;
            if($request->isGet){
                return $this->renderAjax('modal/crearSCB', [
                    'modeloAsignacion' => $modeloAsignacion,
                ]);
            }else if($request->isPost){
                if ($modeloAsignacion->load(Yii::$app->request->post()) && $modeloAsignacion->validate()) {
                    // Verifica que no se agregue un scb que este vigente (no cambiodo [cambio = 0])
                    if( !GrupoTrabajoHasScb::find()->where([
                        'grupo_trabajo_id_grupo_trabajo' => $modeloAsignacion->grupo_trabajo_id_grupo_trabajo,
                        'scb_id_scb' => $modeloAsignacion->scb_id_scb,
                        'cambio' => GrupoTrabajoHasScb::ESTADO_ACTIVO
                    ])->exists()
                    ){
                        $modeloAsignacion->observacion="Agregado";
                        if($modeloAsignacion->save()){
                            return $this->asJson(['success' => true]);
                        }else{
                            return $this->asJson(['error' => true, 'message' => "Socio no guardado."]);
                        }
                    }else{
                        return $this->asJson(['error' => true, 'message' => "Socio ya ingresado."]);
                    }

                }

                $result = [];
                // The code below comes from ActiveForm::validate(). We do not need to validate the model
                // again, as it was already validated by save(). Just collect the messages.
                foreach ($modeloAsignacion->getErrors() as $attribute => $errors) {
                    $result[Html::getInputId($modeloAsignacion, $attribute)] = $errors;
                }

                return $this->asJson(['validation' => $result]);
            }
        }
        return false;
    }

    public function actionReemplazarScb($idAsignacion){
        $modeloAsignacionReemplazo = GrupoTrabajoHasScb::findOne($idAsignacion);

        if( $modeloAsignacionReemplazo == null ){ die("ID de la asignación incorrecto."); }

        $modeloAsignacionReemplazoVacio = new GrupoTrabajoHasScb(['scenario' => GrupoTrabajoHasScb::SCENARIO_OBSERVACION]);
        $modeloAsignacionReemplazoVacio->id_reemplazo_scb = $modeloAsignacionReemplazo->scb_id_scb;
        $modeloAsignacionReemplazoVacio->grupo_trabajo_id_grupo_trabajo = $modeloAsignacionReemplazo->grupo_trabajo_id_grupo_trabajo;

        $request = Yii::$app->request;
        if($request->isAjax){
            if($request->isGet){
                return $this->renderAjax('modal/reemplazarSCB', [
                    'modeloAsignacion' => $modeloAsignacionReemplazoVacio,
                    'id_registro_reemplazado' => $modeloAsignacionReemplazo->id_grupo_trabajo_has_scb
                ]);
            }else if($request->isPost){
                if ($modeloAsignacionReemplazoVacio->load(Yii::$app->request->post()) && $modeloAsignacionReemplazoVacio->validate() && !empty(Yii::$app->request->post('id_registro_reemplazado')) ) {
                    $modeloAsignacionReemplazo = GrupoTrabajoHasScb::findOne(Yii::$app->request->post('id_registro_reemplazado'));
                    if($modeloAsignacionReemplazo != null){
                        $modeloAsignacionReemplazo->cambio = GrupoTrabajoHasScb::ESTADO_INACTIVO;
                        if( !GrupoTrabajoHasScb::find()->where([
                            'grupo_trabajo_id_grupo_trabajo' => $modeloAsignacionReemplazoVacio->grupo_trabajo_id_grupo_trabajo,
                            'scb_id_scb' => $modeloAsignacionReemplazoVacio->scb_id_scb,
                            'cambio' => GrupoTrabajoHasScb::ESTADO_ACTIVO
                        ])->exists()
                        ){
                            if($modeloAsignacionReemplazo->save() && $modeloAsignacionReemplazoVacio->save()){
                                return $this->asJson(['success' => true]);
                            }
                        }

                    }

                }

                $result = [];
                // The code below comes from ActiveForm::validate(). We do not need to validate the model
                // again, as it was already validated by save(). Just collect the messages.
                foreach ($modeloAsignacionReemplazoVacio->getErrors() as $attribute => $errors) {
                    $result[Html::getInputId($modeloAsignacionReemplazoVacio, $attribute)] = $errors;
                }

                return $this->asJson(['validation' => $result]);
            }
        }
        return false;
    }

    public function actionEliminarScb($idAsignacion){
        $modeloAsignacionReemplazo = GrupoTrabajoHasScb::findOne($idAsignacion);
        if( $modeloAsignacionReemplazo == null ){ die("ID de la asignación incorrecto."); }

        $request = Yii::$app->request;
        if($request->isAjax){
            if($request->isGet){
                return $this->renderAjax('modal/eliminarSCB', [
                    'modeloAsignacion' => $modeloAsignacionReemplazo,
                ]);
            }else if($request->isPost){
                if ($modeloAsignacionReemplazo->load(Yii::$app->request->post())) {

                    $modeloAsignacionReemplazoVacio = new GrupoTrabajoHasScb();
                    $modeloAsignacionReemplazoVacio->grupo_trabajo_id_grupo_trabajo = $modeloAsignacionReemplazo->grupo_trabajo_id_grupo_trabajo;
                    $modeloAsignacionReemplazoVacio->scb_id_scb = $modeloAsignacionReemplazo->scb_id_scb;
                    $modeloAsignacionReemplazoVacio->observacion = "Eliminado";
                    $modeloAsignacionReemplazoVacio->cambio = $modeloAsignacionReemplazo->cambio;

                    if($modeloAsignacionReemplazo->save() && $modeloAsignacionReemplazoVacio->save()){
                        return $this->asJson(['success' => true]);
                    }
                }

                $result = [];
                // The code below comes from ActiveForm::validate(). We do not need to validate the model
                // again, as it was already validated by save(). Just collect the messages.
                foreach ($modeloAsignacionReemplazo->getErrors() as $attribute => $errors) {
                    $result[Html::getInputId($modeloAsignacionReemplazo, $attribute)] = $errors;
                }

                return $this->asJson(['validation' => $result]);
            }
        }
        return false;
    }

    public function actionVerHistorialScb($idGrupoTrabajo){
        $grupoTrabajo = GrupoTrabajo::findOne($idGrupoTrabajo);
        if( $grupoTrabajo == null ){ die("ID del grupo de trabajo no existe."); }
        $request = Yii::$app->request;
        if($request->isAjax && $request->isGet){
            $historialDeAsignaciones = GrupoTrabajoHasScb::obtenerSociosBeneficiariosAsignados($grupoTrabajo->id_grupo_trabajo);

            return $this->renderAjax('modal/historialSCB', [
                'asignacionesActivas' => $historialDeAsignaciones,
            ]);
        }
        return false;
    }
}
