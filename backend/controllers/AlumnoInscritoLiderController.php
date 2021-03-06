<?php

namespace backend\controllers;

use backend\models\GrupoTrabajo;
use backend\models\GrupoTrabajoHasScb;
use backend\models\Implementacion;
use backend\models\Model;
use backend\models\Scb;
use backend\models\Seccion;
use Yii;
use backend\models\alumnoInscritoLider;
use backend\models\search\AlumnoInscritoLiderSearch;
use yii\filters\AccessControl;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use yii\db\Expression;

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

    /**
     * Lists all alumnoInscritoLider models.
     * @return mixed
     */
    public function actionIndex()
    {    
        $searchModel = new AlumnoInscritoLiderSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Displays a single alumnoInscritoLider model.
     * @param integer $alumno_inscrito_seccion_id_seccion_alumno
     * @param integer $grupo_trabajo_id_grupo_trabajo
     * @return mixed
     */
    public function actionView($alumno_inscrito_seccion_id_seccion_alumno, $grupo_trabajo_id_grupo_trabajo)
    {   
        $request = Yii::$app->request;
        if($request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                    'title'=> "alumnoInscritoLider #".$alumno_inscrito_seccion_id_seccion_alumno, $grupo_trabajo_id_grupo_trabajo,
                    'content'=>$this->renderAjax('view', [
                        'model' => $this->findModel($alumno_inscrito_seccion_id_seccion_alumno, $grupo_trabajo_id_grupo_trabajo),
                    ]),
                    'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                            Html::a('Editar',['update','alumno_inscrito_seccion_id_seccion_alumno, $grupo_trabajo_id_grupo_trabajo'=>$alumno_inscrito_seccion_id_seccion_alumno, $grupo_trabajo_id_grupo_trabajo],['class'=>'btn btn-primary','role'=>'modal-remote'])
                ];    
        }else{
            return $this->render('view', [
                'model' => $this->findModel($alumno_inscrito_seccion_id_seccion_alumno, $grupo_trabajo_id_grupo_trabajo),
            ]);
        }
    }

    /**
     * Creates a new alumnoInscritoLider model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $request = Yii::$app->request;
        $model = new alumnoInscritoLider();  

        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet){
                return [
                    'title'=> "Crear nuevo alumnoInscritoLider",
                    'content'=>$this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Guardar',['class'=>'btn btn-primary','type'=>"submit"])
        
                ];         
            }else if($model->load($request->post()) && $model->save()){
                return [
                    'forceReload'=>'#crud-datatable-pjax',
                    'title'=> "Crear nuevo alumnoInscritoLider",
                    'content'=>'<span class="text-success">Create alumnoInscritoLider success</span>',
                    'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                            Html::a('Crar Más',['create'],['class'=>'btn btn-primary','role'=>'modal-remote'])
        
                ];         
            }else{           
                return [
                    'title'=> "Crear nuevo alumnoInscritoLider",
                    'content'=>$this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Guardar',['class'=>'btn btn-primary','type'=>"submit"])
        
                ];         
            }
        }else{
            /*
            *   Process for non-ajax request
            */
            if ($model->load($request->post()) && $model->save()) {
                return $this->redirect(['view', 'alumno_inscrito_seccion_id_seccion_alumno' => $model->alumno_inscrito_seccion_id_seccion_alumno, 'grupo_trabajo_id_grupo_trabajo' => $model->grupo_trabajo_id_grupo_trabajo]);
            } else {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        }
       
    }

    public function actionAsignarLider($idImplementacion, $idSeccion){
        $request = Yii::$app->request;
        if ($request->isPost) {
            //var_dump($request->post());
            //die;
            $seccionSeleccionada = Seccion::findOne($idSeccion);
            $gruposTrabajo = $seccionSeleccionada->grupoTrabajos;
            $modelosLideres = [];
            $modelosSCBS = [];
            foreach ($gruposTrabajo as $grupoTrabajo) {
                $aux2 = new GrupoTrabajoHasScb();
                $aux2->grupo_trabajo_id_grupo_trabajo = $grupoTrabajo->id_grupo_trabajo;
                $modelosSCBS[] = $aux2;

                $modelosLideres[] = $grupoTrabajo; //ELIMINAR
            }
            //var_dump(Yii::$app->request->post());
            //die;
            if(Model::loadMultiple($modelosLideres, Yii::$app->request->post()) && Model::validateMultiple($modelosLideres)){
                foreach ($modelosLideres as $modeloLider) {
                        $modeloLider->save(false);
                }
            }
            if(Model::loadMultiple($modelosSCBS, Yii::$app->request->post()) && Model::validateMultiple($modelosSCBS)){
                foreach ($modelosSCBS as $modeloSCB) {
                    if($modeloSCB->scb_id_scb == 0){
                        $eliminarTodos = GrupoTrabajoHasScb::find()
                            ->where([
                                'grupo_trabajo_id_grupo_trabajo' => $modeloSCB->grupo_trabajo_id_grupo_trabajo
                            ])->all();
                        if($eliminarTodos != null){
                            foreach ($eliminarTodos as $eliminarUno){
                                $eliminarUno->delete();
                            }
                        }
                        continue;
                    }
                    $esta = GrupoTrabajoHasScb::find()
                        ->where([
                            'scb_id_scb' => $modeloSCB->scb_id_scb,
                            'grupo_trabajo_id_grupo_trabajo' => $modeloSCB->grupo_trabajo_id_grupo_trabajo
                        ])->one();
                    if($esta == null){
                        //SI ES NULL ES NUEVO, SINO ES IGUAL Y NO SE GUARDA (ERROR: DEBE GUARDARSE PARA ACTUALIZAR LA FECHA)
                        $modeloSCB->save(false);
                    }else{
                        $esta->creado_en = new Expression('NOW()');
                        $esta->modificado_en = new Expression('NOW()');
                        $esta->save(false);
                    }
                }
            }

            Yii::$app->mensaje->mensajeGrowl('success', 'Líderes y Socios Beneficiarios guardados exitosamente.');
            return $this->redirect(['/implementacion/panel-implementacion', 'idImplementacion' => Yii::$app->request->get('idImplementacion')]);
        } else {
            $seccionSeleccionada = Seccion::findOne($idSeccion);
            $gruposTrabajo = $seccionSeleccionada->grupoTrabajos;

            $modelosLideres = [];
            $modelosSCBS = [];

            foreach ($gruposTrabajo as $grupoTrabajo) {
                //$lider = GrupoTrabajo::find()->where(['id_grupo_trabajo' => $grupoTrabajo->id_grupo_trabajo])->one();
               // ->andWhere(['not', ['id_alumno_lider' => null]])->one();
                //if($lider != null){
                //    $modelosLideres[] = $lider;
                //}
                $modelosLideres[] = $grupoTrabajo;

                //SABER SI EXISTE YA UN REGISTRO
                $grupoTieneSocioB = GrupoTrabajoHasScb::find()->where([
                    'grupo_trabajo_id_grupo_trabajo' => $grupoTrabajo->id_grupo_trabajo,
                ])->orderBy('creado_en DESC')->one();

                if($grupoTieneSocioB != null){
                    //$grupoTieneSocioB->scenario = GrupoTrabajoHasScb::SCENARIO_OBSERVACION;
                    $modelosSCBS[] = $grupoTieneSocioB;
                }else{
                    $grupoTieneSocioB  = new GrupoTrabajoHasScb();
                    //$grupoTieneSocioB->grupo_trabajo_id_grupo_trabajo = $grupoTrabajo->id_grupo_trabajo;
                    $modelosSCBS[] = $grupoTieneSocioB;
                }
            }//FIN FOREACH

            $implementacion = new Implementacion();
            $dataSelect2 = $implementacion->getSociosParticipantesImplementacion(Yii::$app->request->get('idImplementacion'));

            return $this->render('asignarMultiple', [
                'gruposTrabajo' => $gruposTrabajo,
                'modelosLideres' => $modelosLideres,
                'modelosSCBS' => $modelosSCBS,
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
    /**
     * Updates an existing alumnoInscritoLider model.
     * For ajax request will return json object
     * and for non-ajax request if update is successful, the browser will be redirected to the 'view' page.
     * @param integer $alumno_inscrito_seccion_id_seccion_alumno
     * @param integer $grupo_trabajo_id_grupo_trabajo
     * @return mixed
     */
    public function actionUpdate($alumno_inscrito_seccion_id_seccion_alumno, $grupo_trabajo_id_grupo_trabajo)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($alumno_inscrito_seccion_id_seccion_alumno, $grupo_trabajo_id_grupo_trabajo);       

        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet){
                return [
                    'title'=> "Modificar alumnoInscritoLider #".$alumno_inscrito_seccion_id_seccion_alumno, $grupo_trabajo_id_grupo_trabajo,
                    'content'=>$this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Guardar',['class'=>'btn btn-primary','type'=>"submit"])
                ];         
            }else if($model->load($request->post()) && $model->save()){
                return [
                    'forceReload'=>'#crud-datatable-pjax',
                    'title'=> "alumnoInscritoLider #".$alumno_inscrito_seccion_id_seccion_alumno, $grupo_trabajo_id_grupo_trabajo,
                    'content'=>$this->renderAjax('view', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                            Html::a('Editar',['update','alumno_inscrito_seccion_id_seccion_alumno, $grupo_trabajo_id_grupo_trabajo'=>$alumno_inscrito_seccion_id_seccion_alumno, $grupo_trabajo_id_grupo_trabajo],['class'=>'btn btn-primary','role'=>'modal-remote'])
                ];    
            }else{
                 return [
                    'title'=> "Modificar alumnoInscritoLider #".$alumno_inscrito_seccion_id_seccion_alumno, $grupo_trabajo_id_grupo_trabajo,
                    'content'=>$this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Guardar',['class'=>'btn btn-primary','type'=>"submit"])
                ];        
            }
        }else{
            /*
            *   Process for non-ajax request
            */
            if ($model->load($request->post()) && $model->save()) {
                return $this->redirect(['view', 'alumno_inscrito_seccion_id_seccion_alumno' => $model->alumno_inscrito_seccion_id_seccion_alumno, 'grupo_trabajo_id_grupo_trabajo' => $model->grupo_trabajo_id_grupo_trabajo]);
            } else {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        }
    }

    /**
     * Delete an existing alumnoInscritoLider model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $alumno_inscrito_seccion_id_seccion_alumno
     * @param integer $grupo_trabajo_id_grupo_trabajo
     * @return mixed
     */
    public function actionDelete($alumno_inscrito_seccion_id_seccion_alumno, $grupo_trabajo_id_grupo_trabajo)
    {
        $request = Yii::$app->request;
        $this->findModel($alumno_inscrito_seccion_id_seccion_alumno, $grupo_trabajo_id_grupo_trabajo)->delete();

        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['forceClose'=>true,'forceReload'=>'#crud-datatable-pjax'];
        }else{
            /*
            *   Process for non-ajax request
            */
            return $this->redirect(['index']);
        }


    }

     /**
     * Delete multiple existing alumnoInscritoLider model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $alumno_inscrito_seccion_id_seccion_alumno
     * @param integer $grupo_trabajo_id_grupo_trabajo
     * @return mixed
     */
    public function actionBulkDelete()
    {        
        $request = Yii::$app->request;
        $pks = explode(',', $request->post( 'pks' )); // Array or selected records primary keys
        foreach ( $pks as $pk ) {
            $model = $this->findModel($pk);
            $model->delete();
        }

        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['forceClose'=>true,'forceReload'=>'#crud-datatable-pjax'];
        }else{
            /*
            *   Process for non-ajax request
            */
            return $this->redirect(['index']);
        }
       
    }

    /**
     * Finds the alumnoInscritoLider model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $alumno_inscrito_seccion_id_seccion_alumno
     * @param integer $grupo_trabajo_id_grupo_trabajo
     * @return alumnoInscritoLider the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($alumno_inscrito_seccion_id_seccion_alumno, $grupo_trabajo_id_grupo_trabajo)
    {
        if (($model = alumnoInscritoLider::findOne(['alumno_inscrito_seccion_id_seccion_alumno' => $alumno_inscrito_seccion_id_seccion_alumno, 'grupo_trabajo_id_grupo_trabajo' => $grupo_trabajo_id_grupo_trabajo])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('La página solicitada no existe.');
        }
    }
}
