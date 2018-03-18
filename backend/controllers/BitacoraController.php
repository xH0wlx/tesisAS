<?php

namespace backend\controllers;

use backend\models\AlumnoInscritoSeccion;
use backend\models\AnioSemestre;
use backend\models\Evidencia;
use backend\models\GrupoTrabajo;
use backend\models\Implementacion;
use backend\models\search\ImplementacionSearch;
use backend\models\Seccion;
use backend\models\Sede;
use backend\models\SeleccionBitacorasReporte;
use Yii;
use backend\models\bitacora;
use backend\models\search\BitacoraSearch;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use yii\web\UploadedFile;

/**
 * BitacoraController implements the CRUD actions for bitacora model.
 */
class BitacoraController extends Controller
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
                    [
                        'actions' => ['download', 'modificar-bitacora-alumno','crear-bitacora-alumno', 'seleccion-asignatura', 'modificar-bitacora', 'seleccion-asignatura-i', 'bitacoras-alumno'],
                        'allow' => true,
                        'roles' => ['alumno'],
                    ],
                    [
                        'actions' => ['marcar-aprobada','view','ver-bitacoras-docente','seleccion-asignatura', 'index-docente'],
                        'allow' => true,
                        'roles' => ['docente'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all bitacora models.
     * @return mixed
     */
    public function actionIndex()
    {    
        $searchModel = new BitacoraSearch();
        $dataProvider = $searchModel->searchFormExterno(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Displays a single bitacora model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {   
        $request = Yii::$app->request;
        if($request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            if(Yii::$app->user->can("coordinador general")){
                $boton = Html::a('Editar',['update','id'=>$id],['class'=>'btn btn-primary','role'=>'modal-remote']);
            }else{
                $boton = "";
            }
            return [
                    'title'=> "bitacora #".$id,
                    'content'=>$this->renderAjax('view', [
                        'model' => $this->findModel($id),
                    ]),
                    'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).$boton
                ];    
        }else{
            return $this->render('view', [
                'model' => $this->findModel($id),
            ]);
        }
    }

    /**
     * Creates a new bitacora model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */

    public function actionCrearBitacoraAlumno()
    {
        $request = Yii::$app->request;
        $model = new bitacora();
        $modelEvidencia = new Evidencia();

            /*
            *   Process for non-ajax request
            */
            if ($request->isPost) {
                if(Yii::$app->user->can('alumno')){
                    if($model->load($request->post())){
                        $session = Yii::$app->session;
                        $session->open();
                        if($session->has('idGrupoBitacora')){
                            $idGrupoTrabajo = $session->get('idGrupoBitacora');
                            $session->close();

                            $model->grupo_trabajo_id_grupo_trabajo = $idGrupoTrabajo;

                            $transaction = Yii::$app->db->beginTransaction();

                            if($model->save()){
                                if($modelEvidencia->load($request->post())){
                                    $rar = UploadedFile::getInstance($modelEvidencia, 'instancia_archivo');
                                    if($rar != null){
                                        //NAME, tempNAME, type, size, error;

                                        $modelEvidencia->nombre_archivo = $rar->name;

                                        $ext = end((explode(".", $rar->name)));

                                        $modelEvidencia->ruta_archivo = $model->fecha_bitacora.Yii::$app->security->generateRandomString().".{$ext}";
                                        $modelEvidencia->bitacora_id_bitacora = $model->id_bitacora;

                                        $path = Yii::$app->basePath . '/web/uploads/archivosBitacoras/' . $modelEvidencia->ruta_archivo;

                                        $rar->saveAs($path);

                                        if($modelEvidencia->save()){
                                            $rar->saveAs($path);
                                            $transaction->commit();
                                            Yii::$app->mensaje->mensajeGrowl('success', 'Bitácora guardada exitosamente');

                                            return $this->redirect(['seleccion-asignatura']);
                                        } else {
                                            $transaction->rollBack();
                                            Yii::$app->mensaje->mensajeGrowl('error', 'Error al guardar el archivo de la bitácora.');

                                            return $this->render('create', [
                                                'model' => $model,
                                                'modelEvidencia' => $modelEvidencia,
                                            ]);
                                            //return $this->redirect(['seleccion-asignatura']);
                                        }
                                    }else{
                                        $transaction->commit();
                                        Yii::$app->mensaje->mensajeGrowl('success', 'Bitácora guardada exitosamente');
                                        Yii::$app->correos->enviarNotificacionBitacora($model, Yii::$app->user->identity->username, $idGrupoTrabajo);

                                        return $this->redirect(['seleccion-asignatura']);
                                    }

                                }else{
                                    $transaction->commit();
                                    Yii::$app->mensaje->mensajeGrowl('success', 'Bitácora guardada exitosamente');
                                    //ENVIAR CORREO DE NOTIFICACIÓN
                                    //$model posee los datos de la bitácora
                                    Yii::$app->correos->enviarNotificacionBitacora($model, Yii::$app->user->identity->username, $idGrupoTrabajo);


                                    return $this->redirect(['seleccion-asignatura']);
                                }
                            }else{
                                $transaction->rollBack();
                                Yii::$app->mensaje->mensajeGrowl('error', 'Error al guardar la bitácora.');
                                return $this->render('create', [
                                    'model' => $model,
                                    'modelEvidencia' => $modelEvidencia,
                                ]);
                            }
                        }else{
                            echo "Error al guardar bitácora, registro de grupo no encontrado.";
                            die;
                        }

                    }else{
                        return $this->render('create', [
                            'model' => $model,
                            'modelEvidencia' => $modelEvidencia,
                        ]);
                    }



                }//FIN ALUMNO
            } else {
                if(Yii::$app->user->can('alumno')){
                    return
                        $this->render('create', [
                            'model' => $model,
                            'modelEvidencia' => new Evidencia(),
                        ]);

                }
                return $this->redirect(['seleccion-asignatura']);
            }

    }


    /**
     * Updates an existing bitacora model.
     * For ajax request will return json object
     * and for non-ajax request if update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);       

        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet){
                return [
                    'title'=> "Modificar bitacora #".$id,
                    'content'=>$this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Guardar',['class'=>'btn btn-primary','type'=>"submit"])
                ];         
            }else if($model->load($request->post()) && $model->save()){
                return [
                    'forceReload'=>'#crud-datatable-pjax',
                    'title'=> "bitacora #".$id,
                    'content'=>$this->renderAjax('view', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                            Html::a('Editar',['update','id'=>$id],['class'=>'btn btn-primary','role'=>'modal-remote'])
                ];    
            }else{
                 return [
                    'title'=> "Modificar bitacora #".$id,
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
            if ($request->isPost) {
                $model->load($request->post());
                $model->save();
                return $this->redirect(['view', 'id' => $model->id_bitacora]);
            } else {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        }
    }

    /**
     * Delete an existing bitacora model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $request = Yii::$app->request;
        $this->findModel($id)->delete();

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
     * Delete multiple existing bitacora model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
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
            /*$rutAlumnoLogueado = Yii::$app->user->identity->username; //STRING NO PUEDE SER INT POR LA RAYA K

            $idAlumnoInscrito = AlumnoInscritoSeccion::find()->where(['alumno_rut_alumno'=> $rutAlumnoLogueado, 'seccion_id_seccion'=>$idSeccionSeleccionada])->one();
            if($idAlumnoInscrito != null){
                $idAlumnoInscrito = $idAlumnoInscrito->id_alumno_inscrito_seccion;
                var_dump($idAlumnoInscrito);
                die;
            }else{
                echo "Error al conseguir el registro de inscripción.";
                die;
            }*/

            $session = Yii::$app->session;
            $session->open();
            $session->set('idGrupoBitacora', $idGrupoTrabajo);
            $session->close();
            return $this->redirect(['crear-bitacora-alumno']);
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
            return $this->render('seleccionImplementacion', [
                'modelos' => $modelos,
                'modelos2' => $modelos2,
            ]);
        }
    }

    public function actionSeleccionAsignaturaM(){
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
            return $this->redirect(['modificar-bitacora-alumno']);
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
            return $this->render('seleccionImplementacion', [
                'modelos' => $modelos,
                'modelos2' => $modelos2,
            ]);
        }
    }

    public function actionSeleccionAsignaturaI(){
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
            return $this->redirect(['bitacoras-alumno']);
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
            return $this->render('seleccionImplementacion', [
                'modelos' => $modelos,
                'modelos2' => $modelos2,
            ]);
        }
    }

    public function actionDownload($id)
    {
        $download = Evidencia::findOne($id);
        $path=Yii::getAlias('@webroot').'/uploads/archivosBitacoras/'.$download->ruta_archivo;

        if (file_exists($path)) {
            return Yii::$app->response->sendFile($path);
        }else{
            Yii::$app->getSession()->setFlash('success', [
                'type' => 'error',
                'duration' => 5000,
                //'icon' => 'fa fa-users',
                'message' => 'No se encuentra el archivo.',
                'title' => 'Error',
                'positonY' => 'top',
                //'positonX' => 'left'
            ]);
            return $this->redirect(['modificar-bitacora-alumno', 'id'=> $download->bitacora_id_bitacora]);
        }
    }

    public function actionModificarBitacoraAlumno($id){
        Yii::$app->session->open();
        if(Yii::$app->session->has('idGrupoBitacora')) {
            $idGrupoTrabajo = Yii::$app->session->get('idGrupoBitacora');
            if(!$this->comprobarIdModificar($idGrupoTrabajo, $id)){
                echo "Bitácora no pertenece al grupo del usuario.";
                die;
            }
            Yii::$app->session->close();
        }else{
            Yii::$app->session->close();
        }

        $request = Yii::$app->request;
        $model = Bitacora::findOne($id);
        $modelEvidencia = $model->evidencia;

        if($modelEvidencia == null){
            $modelEvidencia = new Evidencia();
        }
        /*
        *   Process for non-ajax request
        */
        if ($request->isPost) {
            if(Yii::$app->user->can('alumno')){
                if($model->load($request->post())){
                    $session = Yii::$app->session;
                    $session->open();
                    if($session->has('idGrupoBitacora')){
                        $idGrupoTrabajo = $session->get('idGrupoBitacora');
                        $session->close();

                        $model->grupo_trabajo_id_grupo_trabajo = $idGrupoTrabajo;

                        $transaction = Yii::$app->db->beginTransaction();

                        if($model->save()){ //SE GUARDA LA BITÁCORA ACTUALIZADA

                            if($modelEvidencia->load($request->post())){

                                $rar = UploadedFile::getInstance($modelEvidencia, 'instancia_archivo');
                                //NAME, tempNAME, type, size, error;
                                if($rar != NULL){ //EN CASO DE MODIFICACIÓN CON ARCHIVO VACÍO

                                    $modelEvidencia->nombre_archivo = $rar->name;

                                    $ext = end((explode(".", $rar->name)));

                                    if($modelEvidencia->ruta_archivo != null){
                                        //$modelEvidencia->ruta_archivo;
                                    }else{
                                        $modelEvidencia->ruta_archivo = $model->fecha_bitacora.Yii::$app->security->generateRandomString().".{$ext}";
                                    }
                                    $modelEvidencia->bitacora_id_bitacora = $model->id_bitacora;

                                    $path = Yii::$app->basePath . '/web/uploads/archivosBitacoras/' . $modelEvidencia->ruta_archivo;

                                    $rar->saveAs($path);

                                    if($modelEvidencia->save()){
                                        $rar->saveAs($path);
                                        $transaction->commit();

                                        Yii::$app->mensaje->mensajeGrowl('success', 'Bitácora guardada exitosamente.');
                                        Yii::$app->correos->enviarNotificacionBitacora($model, Yii::$app->user->identity->username, $idGrupoTrabajo);

                                        return $this->redirect(['seleccion-asignatura-i']);
                                    } else {
                                        $transaction->rollBack();

                                        Yii::$app->mensaje->mensajeGrowl('error', 'Error al guardar la bitácora.');

                                        return $this->render('update', [
                                            'model' => $model,
                                            'modelEvidencia' => $modelEvidencia,
                                        ]);
                                    }
                                }else{
                                    if($modelEvidencia->ruta_archivo != null){
                                        $path = Yii::$app->basePath . '/web/uploads/archivosBitacoras/' . $modelEvidencia->ruta_archivo;
                                        if(unlink($path) && $modelEvidencia->delete()) {
                                            $transaction->commit();
                                            Yii::$app->mensaje->mensajeGrowl('success', 'Bitácora guardada exitosamente.');
                                            Yii::$app->correos->enviarNotificacionBitacora($model, Yii::$app->user->identity->username, $idGrupoTrabajo);

                                            return $this->redirect(['seleccion-asignatura-i']);
                                        }else{
                                            $transaction->rollBack();
                                            Yii::$app->mensaje->mensajeGrowl('error', 'Error al eliminar la bitácora/archivo.');

                                            return $this->render('update', [
                                                'model' => $model,
                                                'modelEvidencia' => $modelEvidencia,
                                            ]);
                                        }
                                    }else{
                                        //SIGNIFICA QUE VIENE SIN ARCHIVO Y SE GUARDA SIN ARCHIVO
                                        $transaction->commit();
                                        Yii::$app->mensaje->mensajeGrowl('success', 'Bitácora guardada exitosamente.');
                                        Yii::$app->correos->enviarNotificacionBitacora($model, Yii::$app->user->identity->username, $idGrupoTrabajo);

                                        return $this->redirect(['seleccion-asignatura-i']);
                                    }
                                }//FIN IF RAR NULL
                            }else{
                                $transaction->commit();
                                Yii::$app->mensaje->mensajeGrowl('success', 'Bitácora guardada exitosamente.');
                                Yii::$app->correos->enviarNotificacionBitacora($model, Yii::$app->user->identity->username, $idGrupoTrabajo);
                                return $this->redirect(['seleccion-asignatura-i']);
                            }
                        }else{
                            $transaction->rollBack();
                            Yii::$app->mensaje->mensajeGrowl('error', 'Error al guardar la bitácora.');

                            return $this->render('update', [
                                'model' => $model,
                                'modelEvidencia' => $modelEvidencia,
                            ]);
                        }
                    }else{
                        echo "Error al guardar bitácora, registro de grupo no encontrado.";
                        die;
                    }
                }else{
                    return $this->render('update', [
                        'model' => $model,
                        'modelEvidencia' => $modelEvidencia,
                    ]);
                }



            }//FIN ALUMNO
        } else {
            if(Yii::$app->user->can('alumno')){
                return
                    $this->render('update', [
                        'model' => $model,
                        'modelEvidencia' => $modelEvidencia,
                    ]);

            }
        }

    }//FIN FUNCION

    public function actionBitacorasAlumno(){
        $request = Yii::$app->request;
        $model = new bitacora();
        $modelEvidencia = new Evidencia();

        if(Yii::$app->user->can('alumno')){
            $session = Yii::$app->session;
            $session->open();
            if($session->has('idGrupoBitacora')) {
                $idGrupoTrabajo = $session->get('idGrupoBitacora');
                $session->close();

                $searchModel = new BitacoraSearch();
                $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
                $dataProvider->query->andFilterWhere(['grupo_trabajo_id_grupo_trabajo'=>$idGrupoTrabajo]);

                return $this->render('//bitacora/grillaAlumno/index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                ]);
            }else{
                echo "Error en obtener el registro del grupo de trabajo.";
                die;
            }
        }
    }

    public function actionModificarBitacora(){
        //if ($model->load($request->post()) && $model->save()) {
        //     return $this->redirect(['view', 'id' => $model->id_bitacora]);
        //} else {
        $modelos = Implementacion::find()
            ->leftJoin('seccion', 'id_implementacion = implementacion_id_implementacion')
            ->leftJoin('alumno_inscrito_seccion', 'id_seccion = seccion_id_seccion')
            ->where(['implementacion.estado' => 0])
            ->andWhere(['alumno_inscrito_seccion.alumno_rut_alumno' => Yii::$app->user->identity->username])
            ->all();

        //ESTA CONSULTA DEBE TENER LA ID DEL ALUMNO LA ID DEL ALUMNO QUË SECCIÓN Y QUÉ ASIGNATURA
        $id_grupo = Yii::$app->db->createCommand('SELECT grupo_trabajo_id_grupo_trabajo FROM asignatura JOIN `implementacion` ON cod_asignatura = asignatura_cod_asignatura 
            JOIN seccion on id_implementacion = seccion.implementacion_id_implementacion JOIN
             alumno_inscrito_seccion ON seccion.id_seccion = alumno_inscrito_seccion.seccion_id_seccion
             JOIN alumno_inscrito_has_grupo_trabajo ON alumno_inscrito_seccion.id_alumno_inscrito_seccion = alumno_inscrito_has_grupo_trabajo.alumno_inscrito_seccion_id_alumno_inscrito_seccion
              WHERE implementacion.estado = 0 AND alumno_inscrito_seccion.alumno_rut_alumno = :rut ORDER BY numero_seccion',
            [":rut" => Yii::$app->user->identity->username])->queryScalar();
        $query = Bitacora::find()->where(['grupo_trabajo_id_grupo_trabajo' => $id_grupo]);

        $provider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        $searchModel = new BitacoraSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('modificarBitacora', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
        //}
    }

    public function actionVerBitacorasDocente(){
        $request = Yii::$app->request;
        if($request->isPost){
            $id_seccion_seleccionada = intval($request->post("id_seccion"));
            if($id_seccion_seleccionada == 0){
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

            return $this->redirect(['index-docente', 'idSeccion' => $id_seccion_seleccionada]);

        }else{
            //ESTADO 1 es cuando está en CURSO
            $seccionesDocente = Yii::$app->db->createCommand('SELECT * FROM asignatura 
          JOIN `implementacion` ON cod_asignatura = asignatura_cod_asignatura 
          JOIN seccion on id_implementacion = seccion.implementacion_id_implementacion  
          WHERE implementacion.estado = 1 AND docente_rut_docente = :rut ORDER BY seccion.numero_seccion',
                [":rut" => Yii::$app->user->identity->username])->queryAll();

            return $this->render('seleccionImplementacionDocente', [
                'modelos2' => $seccionesDocente,
            ]);
        }

    }

    public function actionIndexDocente($idSeccion)
    {
        $searchModel = new BitacoraSearch();
        $dataProvider = $searchModel->searchFormExterno(Yii::$app->request->queryParams);
        $dataProvider->query->andFilterWhere(['seccion_id_seccion' => $idSeccion]);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Finds the bitacora model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return bitacora the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = bitacora::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('La página solicitada no existe.');
        }
    }

    //REPORTES
    public function actionReporteResumen()
    {
        $request = Yii::$app->request;
        if(false/*$request->isPost*/){
            $modeloPeriodo = new SeleccionBitacorasReporte();
            $modeloPeriodo->load($request->post());

            //GRUPOS DE TRABAJO
            //NÚMERO DE BITACORAS HECHAS POR EL GRUPO
            //DETALLE DE CADA BITÁCORA
            //-	Totales (Cantidad de grupos de trabajo, sumatoria de bitácoras de la asignatura).

            //CON LO DATOS DE FILTRADO
            $seccion = $modeloPeriodo->seccion;

            //ESTA ESTÁ BUENA PARA DESPUÉS PONERLE LOS RANGOS DE PERIODOS PARA OBTENER TODO
            $todo = Implementacion::find()->with(['asignaturaCodAsignatura','seccions.grupoTrabajos.bitacoras', 'seccions.grupoTrabajos.alumnoInscritoHasGrupoTrabajos'])->all();

            //ESTA ESTÁ BUENA PARA PONERLE CON EL FORMULARIO DE FILTRADO
            $filtrado = Implementacion::find()->with(['asignaturaCodAsignatura','seccions.grupoTrabajos.bitacoras', 'seccions.grupoTrabajos.alumnoInscritoHasGrupoTrabajos'])->all();


            $query = Implementacion::find()->where(['anio_implementacion'=> $modeloPeriodo->anio])->andWhere(['semestre_implementacion'=>$modeloPeriodo->semestre])/*->groupBy(['asignatura_cod_asignatura'])*/;
            //$ids = Match1::find()->select('asignatura_cod_asignatura')->where(['anio_match1'=> $modeloPeriodo->anio])->andWhere(['semestre_match1'=>$modeloPeriodo->semestre])->groupBy(['asignatura_cod_asignatura']);
            //$query = Asignatura::find()->where(['in', 'cod_asignatura', $ids]);

            $query = Bitacora::find()->with(['grupoTrabajoIdGrupoTrabajo.seccionIdSeccion.implementacionIdImplementacion.asignaturaCodAsignatura',
                'grupoTrabajoIdGrupoTrabajo.alumnoInscritoHasGrupoTrabajos.alumnoInscritoSeccionIdAlumnoInscritoSeccion.alumnoRutAlumno']);

            $cantidad = $query->count();

            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => [
                    'pageSize' => $cantidad,
                ],
            ]);

            return $this->render('bitacorasReporte', [
                'dataProvider' => $dataProvider,
                'modeloPeriodo' => $modeloPeriodo,
                'todo' => $todo,
            ]);


        }else{
            $searchModel = new BitacoraSearch();
            $dataProvider = $searchModel->searchReporteResumen(Yii::$app->request->queryParams);
            $dataProvider->sort =false;

            return $this->render('reporte-resumen/reporteResumen', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        }

    }

    public function actionReporteEstadistica()
    {
        $request = Yii::$app->request;
        if(false/*$request->isPost*/){
            $modeloPeriodo = new SeleccionBitacorasReporte();
            $modeloPeriodo->load($request->post());

            //GRUPOS DE TRABAJO
            //NÚMERO DE BITACORAS HECHAS POR EL GRUPO
            //DETALLE DE CADA BITÁCORA
            //-	Totales (Cantidad de grupos de trabajo, sumatoria de bitácoras de la asignatura).

            //CON LO DATOS DE FILTRADO
            $seccion = $modeloPeriodo->seccion;

            //ESTA ESTÁ BUENA PARA DESPUÉS PONERLE LOS RANGOS DE PERIODOS PARA OBTENER TODO
            $todo = Implementacion::find()->with(['asignaturaCodAsignatura','seccions.grupoTrabajos.bitacoras', 'seccions.grupoTrabajos.alumnoInscritoHasGrupoTrabajos'])->all();

            //ESTA ESTÁ BUENA PARA PONERLE CON EL FORMULARIO DE FILTRADO
            $filtrado = Implementacion::find()->with(['asignaturaCodAsignatura','seccions.grupoTrabajos.bitacoras', 'seccions.grupoTrabajos.alumnoInscritoHasGrupoTrabajos'])->all();


            $query = Implementacion::find()->where(['anio_implementacion'=> $modeloPeriodo->anio])->andWhere(['semestre_implementacion'=>$modeloPeriodo->semestre])/*->groupBy(['asignatura_cod_asignatura'])*/;
            //$ids = Match1::find()->select('asignatura_cod_asignatura')->where(['anio_match1'=> $modeloPeriodo->anio])->andWhere(['semestre_match1'=>$modeloPeriodo->semestre])->groupBy(['asignatura_cod_asignatura']);
            //$query = Asignatura::find()->where(['in', 'cod_asignatura', $ids]);

            $query = Bitacora::find()->with(['grupoTrabajoIdGrupoTrabajo.seccionIdSeccion.implementacionIdImplementacion.asignaturaCodAsignatura',
                'grupoTrabajoIdGrupoTrabajo.alumnoInscritoHasGrupoTrabajos.alumnoInscritoSeccionIdAlumnoInscritoSeccion.alumnoRutAlumno']);

            $cantidad = $query->count();

            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => [
                    'pageSize' => $cantidad,
                ],
            ]);

            return $this->render('bitacorasReporte', [
                'dataProvider' => $dataProvider,
                'modeloPeriodo' => $modeloPeriodo,
                'todo' => $todo,
            ]);


        }else{
            $searchModel = new ImplementacionSearch();
            $dataProvider = $searchModel->searchReporteEstadisticaBitacora(Yii::$app->request->queryParams);
            $dataProvider->sort =false;

            $modeloPeriodo = new SeleccionBitacorasReporte();

            return $this->render('reporte-estadistica/reporteEstadistica', [
                'modeloPeriodo' => $modeloPeriodo,
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        }

    }

    public function actionSubsemestres(){
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if ($parents != null) {
                if(!empty($_POST['depdrop_params'])){
                    $inputAnio = $parents[0];
                    //$params = $_POST['depdrop_params'];
                    //$param1 = $params[0]; //id FACULTAD
                    $out = Implementacion::find()
                        ->select(['id_implementacion as id', 'semestre_implementacion as name'])
                        ->where(['anio_implementacion' => $inputAnio])->asArray()->all();
                    //$selected = $param1;
                    echo Json::encode(['output'=>$out, 'selected'=>'']);
                    return;
                }else{
                    $inputAnio = $parents[0];
                    $out = Implementacion::find()
                        ->select(['semestre_implementacion as id', 'semestre_implementacion as name'])
                        ->where(['anio_implementacion' => $inputAnio])->asArray()->all();
                    echo Json::encode(['output'=>$out, 'selected'=>'']);
                    return;
                }
            }
        }
        echo Json::encode(['output'=>'', 'selected'=>'']);
    }

    public function actionSubimplementaciones(){
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if ($parents != null) {
                if(!empty($_POST['depdrop_params'])){
                    //$params = $_POST['depdrop_params'];
                    //$param1 = $params[0]; //id FACULTAD
                    $inputAnio = $parents[0];
                    $inputSemestre = $parents[1];
                    $out = Implementacion::find()
                        ->select(['id_implementacion as id', 'asignatura_cod_asignatura as name'])
                        ->where(['anio_implementacion' => $inputAnio, 'semestre_implementacion' => $inputSemestre])->asArray()->all();

                    //$selected = $param1;
                    echo Json::encode(['output'=>$out, 'selected'=>'']);
                    return;
                }else{
                    $inputAnio = $parents[0];
                    $inputSemestre = $parents[1];
                    $out = ArrayHelper::map(Implementacion::find()
                        ->where(['anio_implementacion' => $inputAnio, 'semestre_implementacion' => $inputSemestre])
                        ->orderBy('asignatura_cod_asignatura')
                        ->all(),'id_implementacion','codigonombre');
                    $nuevo = [];
                    foreach ($out as $key => $value){
                        $fila = array('id' => $key, 'name' => $value);
                        $nuevo[] = $fila;
                    }
                    echo Json::encode(['output'=>$nuevo, 'selected'=>'']);
                    return;
                }
            }
        }
        echo Json::encode(['output'=>'', 'selected'=>'']);
    }

    public function actionSubsecciones(){
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if ($parents != null) {
                if(!empty($_POST['depdrop_params'])){
                    $inputAnio = $parents[0];
                    //$params = $_POST['depdrop_params'];
                    //$param1 = $params[0]; //id FACULTAD
                    $out = Implementacion::find()
                        ->select(['id_implementacion as id', 'semestre_implementacion as name'])
                        ->where(['anio_implementacion' => $inputAnio])->asArray()->all();
                    //$selected = $param1;
                    echo Json::encode(['output'=>$out, 'selected'=>'']);
                    return;
                }else{
                    $inputImplementacion = $parents[0];
                    $out = ArrayHelper::map(Seccion::find()
                        ->where(['implementacion_id_implementacion' => $inputImplementacion])
                        ->orderBy('numero_seccion')
                        ->all(),'id_seccion','secciondocente');
                    $nuevo = [];
                    foreach ($out as $key => $value){
                        $fila = array('id' => $key, 'name' => $value);
                        $nuevo[] = $fila;
                    }
                    echo Json::encode(['output'=>$nuevo, 'selected'=>'']);
                    return;
                }
            }
        }
        echo Json::encode(['output'=>'', 'selected'=>'']);
    }

    public function actionMarcarLeida(){
        $request = Yii::$app->request;
        if($request->isPost){
            $data = Yii::$app->request->post();
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            if(isset($data["idBitacora"])){
                $bitacora = $this->findModel($data["idBitacora"]);
                if($bitacora != null){
                    $bitacora->fecha_lectura = date('yyyy-mm-dd h-m-s');

                    if($bitacora->save(false)){
                        return [
                            'texto' => 'exito',
                            'code' => 100,
                        ];
                    }else{
                        return [
                            'texto' => 'fracaso',
                            'code' => 200,
                        ];
                    }

                }else{
                    return [
                        'texto' => 'fracaso',
                        'code' => 200,
                    ];
                }


            }else{
                return [
                    'texto' => 'fracaso',
                    'code' => 200,
                ];
            }
        }
    }

    public function actionMarcarAprobada(){
        $request = Yii::$app->request;
        if($request->isPost){
            $data = Yii::$app->request->post();
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            if(isset($data["idBitacora"])){
                $bitacora = $this->findModel($data["idBitacora"]);
                if($bitacora != null){
                    $bitacora->aprobacion_docente = 1; //1aprobado 0 default

                    if($bitacora->save(false)){
                        return [
                            'texto' => 'exito',
                            'code' => 100,
                        ];
                    }else{
                        return [
                            'texto' => 'fracaso',
                            'code' => 200,
                        ];
                    }

                }else{
                    return [
                        'texto' => 'fracaso',
                        'code' => 200,
                    ];
                }


            }else{
                return [
                    'texto' => 'fracaso',
                    'code' => 200,
                ];
            }
        }
    }
    public function actionExportexcel()
    {
        $sedes   = Sede::find()->all();
        $filename  = 'test- '.date('Y-m-d').' - hemant.xls';
        //header("Content-type: application/vnd-ms-excel; charset=utf-8");
        //header("Content-Disposition: attachment; filename=".$filename);
        header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
        header("Content-Disposition: attachment;filename=".$filename);
        header("Cache-Control: max-age=0");
        header("Pragma: no-cache");
        header("Expires: 0");
        echo '
            <table width="100%" border="1">
               <thead>
                <tr>
                 <th>Id Sede</th>
                 <th>Nombre Sede</th>
                </tr>
               </thead>';
                    foreach($sedes as $sede){
                        echo ' 
                 <tr>
                  <td >'.utf8_decode($sede->id_sede).'</td>
                  <td>'.utf8_decode($sede->nombre_sede).'</td>
                  </tr>
                  <tr>
                  <td style="background-color: #00a7d0;" colspan="2">TOTAL   21</td>
                 </tr>
                ';
        }
        echo '</table>';
   }

    private function comprobarIdModificar($idGrupoTrabajo, $id){
        $bitacora = Bitacora::find()->where(['grupo_trabajo_id_grupo_trabajo'=> $idGrupoTrabajo, 'id_bitacora'=> $id])->one();
        if($bitacora != null){
            return true;
        }else{
            return false;
        }

    }
}
