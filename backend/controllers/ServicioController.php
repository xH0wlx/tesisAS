<?php

namespace backend\controllers;

use backend\models\DocenteHasServicio;
use backend\models\Model;
use Yii;
use backend\models\servicio;
use backend\models\match1;
use backend\models\AnioSemestre;
use backend\models\search\ServicioSearch;
use yii\base\Exception;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use backend\models\search\Match1Search;

/**
 * ServicioController implements the CRUD actions for servicio model.
 */
class ServicioController extends Controller
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

    public function actionPrincipal()
    {
        $searchModel = new ServicioSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('principal', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Lists all servicio models.
     * @return mixed
     */
    public function actionIndex()
    {    
        $searchModel = new ServicioSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Displays a single servicio model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {   
        $request = Yii::$app->request;
        if($request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                    'title'=> "servicio #".$id,
                    'content'=>$this->renderAjax('view', [
                        'model' => $this->findModel($id),
                    ]),
                    'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                            Html::a('Modificar',['update','id'=>$id],['class'=>'btn btn-primary','role'=>'modal-remote'])
                ];    
        }else{
            return $this->render('view', [
                'model' => $this->findModel($id),
            ]);
        }
    }

    public function actionViewMatch($id)
    {
        $request = Yii::$app->request;
        if($request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title'=> "servicio #".$id,
                'content'=>$this->renderAjax('view', [
                    'model' => $this->findModel($id),
                ]),
                'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                    Html::a('Modificar',['update','id'=>$id],['class'=>'btn btn-primary','role'=>'modal-remote'])
            ];
        }else{
            return $this->render('viewConMatch', [
                'model' => $this->findModel($id),
            ]);
        }
    }

    /**
     * Creates a new servicio model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $request = Yii::$app->request;
        $model = new servicio();
        $docentesHasServicio = [new DocenteHasServicio()];

        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet){
                return [
                    'title'=> "Crear nuevo Servicio",
                    'content'=>$this->renderAjax('create', [
                        'model' => $model,
                        'docentesHasServicio' => (empty($docentesHasServicio)) ? [new DocenteHasServicio()] : $docentesHasServicio,
                    ]),
                    'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Guardar',['class'=>'btn btn-primary','type'=>"submit"])
        
                ];         
            }else if($model->load($request->post())){
                $docentesHasServicio = Model::createMultiple(docenteHasServicio::classname());
                Model::loadMultiple($docentesHasServicio, Yii::$app->request->post());

                //PASO EL MODEL Y EL DOCENTE HAS SERVICIO [FUNCION CREAR MULTIPLE]
                $this->funcionCrearMultiple($model, $docentesHasServicio, 1);
                return [
                    'forceReload'=>'#crud-datatable-pjax',
                    'title'=> "Crear nuevo servicio",
                    'content'=>'<span class="text-success">Servicio creado exitosamente</span>',
                    'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                            Html::a('Crear Más',['create'],['class'=>'btn btn-primary','role'=>'modal-remote'])
        
                ];         
            }else{           
                return [
                    'title'=> "Crear nuevo servicio",
                    'content'=>$this->renderAjax('create', [
                        'model' => $model,
                        'docentesHasServicio' => (empty($docentesHasServicio)) ? [new DocenteHasServicio()] : $docentesHasServicio,
                    ]),
                    'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Guardar',['class'=>'btn btn-primary','type'=>"submit"])
        
                ];         
            }
        }else{
            /*
            *   Process for non-ajax request
            */
            if ($model->load($request->post())) {
                $docentesHasServicio = Model::createMultiple(docenteHasServicio::classname());
                Model::loadMultiple($docentesHasServicio, Yii::$app->request->post());

                //PASO EL MODEL Y EL DOCENTE HAS SERVICIO [FUNCION CREAR MULTIPLE]
                $this->funcionCrearMultiple($model, $docentesHasServicio, 0);

            } else {
                return $this->render('create', [
                    'model' => $model,
                    'docentesHasServicio' => (empty($docentesHasServicio)) ? [new DocenteHasServicio()] : $docentesHasServicio,
                ]);
            }
        }
       
    }

    public function actionCreateProvisorio($anio, $semestre)
    {
        $model = new servicio();
        $match1 = new match1();
        $docentesHasServicio = [new DocenteHasServicio()];
        $request = Yii::$app->request;
        //$model->asignatura_cod_asignatura = $match1->asignatura_cod_asignatura;
        /*
        *   Process for non-ajax request
        */
        if ($model->load($request->post())) {
            $session = Yii::$app->session;
            $session->open();
            if(isset($session["comprobacionReglaServicio"])){
                $transaction = \Yii::$app->db->beginTransaction();
                if($model->save()){
                    $docentesHasServicio = Model::createMultiple(docenteHasServicio::classname());
                    Model::loadMultiple($docentesHasServicio, Yii::$app->request->post());
                    $valid = $model->validate();
                    $valid = Model::validateMultiple($docentesHasServicio) && $valid;

                    if ($valid) {
                        try {
                            if ($flag = $model->save(false)) {
                                foreach ($docentesHasServicio as $docenteHasServicio) {
                                    $docenteHasServicio->servicio_id_servicio = $model->id_servicio;
                                    if (! ($flag = $docenteHasServicio->save(false))) {
                                        $transaction->rollBack();
                                        break;
                                    }
                                }
                            }
                            if ($flag) {
                                $arreglo = $session["comprobacionReglaServicio"];
                                $banderaMatch = true;
                                foreach ($arreglo as $idMatch => $value){
                                    $modeloMatch = Match1::findOne($idMatch);
                                    if($modeloMatch != null){
                                        $modeloMatch->servicio_id_servicio = $model->id_servicio;
                                        //CAMBIAR ESTADO DEL REQUERIMIENTO Y SERVICIO
                                        Yii::$app->estados->actualizarEstadoRequerimiento($modeloMatch->requerimiento_id_requerimiento
                                            ,"Asignado");
                                        Yii::$app->estados->actualizarEstadoServicio($modeloMatch->servicio_id_servicio
                                            ,"Asignado");

                                        //ESTADO IMPLEMENTACIÓN
                                        $modeloMatch->aprobacion_implementacion = 0;
                                        if (! ($banderaMatch = $modeloMatch->save(false))) {
                                            $transaction->rollBack();
                                            break;
                                        }
                                    }
                                }
                                if($banderaMatch){

                                    $transaction->commit();

                                    $session->close();
                                    return $this->redirect(['view-match', 'id' => $model->id_servicio]);

                                }else{
                                    $transaction->rollBack();
                                }

                            }
                        } catch (Exception $e) {
                            $transaction->rollBack();
                        }
                    }//FIN VALID
                }else{
                    $transaction->rollBack();
                }
                $session->close();

            }else{
                echo "Caducó la selección de Requerimientos.";
                die;
            }
        } else {

            $session = Yii::$app->session;
            $session->open();
            if(isset($session["comprobacionReglaServicio"])){
                $arreglo = $session["comprobacionReglaServicio"];


                reset($arreglo);
                $first_key = key($arreglo);
                //SACA LA PRIMERA COINCIDENCIA MATCH
                $match1 = $match1->findOne($first_key);
                //TODAS LOS REQUERIMIENTOS DE UNA MISMA ASIGNATURA LLEGAN POR EL ARREGLO
                $modelosMatch = Match1::find()->where(["id_match1" => array_keys($arreglo)])->all();

                $model->asignatura_cod_asignatura = $match1->asignatura_cod_asignatura;

                //CODIGO ADICIONAL PARA PRECARGAR EL SERVICIO EN CASO DE ENCONTRARLO
                $verificarMatch = Match1::find()->where(['anio_match1' => $anio, 'semestre_match1' => $semestre,
                    'asignatura_cod_asignatura' => $match1->asignatura_cod_asignatura])
                    ->andWhere(['not', ['servicio_id_servicio' => null]])->all();
                if($verificarMatch != null){
                    $idServicio = $verificarMatch[0]->servicio_id_servicio;
                    $model = Servicio::findOne($idServicio);
                    $docentes = $model->docenteHasServicios;
                    if($docentes != null){
                        $docentesHasServicio = [];
                        foreach ($docentes as $docente){
                            $docentesHasServicio [] = $docente;
                        }
                    }
                    Yii::$app->mensaje->mensajeGrowl('info', 'Esta asignatura ya tiene un servicio asociado
                    , se precargaron sus datos.', 7000);
                    //Model::loadMultiple($docentesHasServicio, $model->docenteRutDocentes);
                }


            }else{
                echo "Error en la selección de Asignaturas";
                die;
            }
            $session->close();

            return $this->render('//servicio/asignarServicioMatch/createMatch', [
                'model' => $model,
                'modelosMatch' => $modelosMatch,
                'docentesHasServicio' => (empty($docentesHasServicio)) ? [new DocenteHasServicio()] : $docentesHasServicio,
            ]);
        }


    }

    /**
     * Updates an existing servicio model.
     * For ajax request will return json object
     * and for non-ajax request if update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);
        $docentesHasServicio = $model->docenteHasServicios;

        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet){
                return [
                    'title'=> "Modificar servicio #".$id,
                    'content'=>$this->renderAjax('update', [
                        'model' => $model,
                        'docentesHasServicio' => (empty($docentesHasServicio)) ? [new DocenteHasServicio()] : $docentesHasServicio,

                    ]),
                    'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Guardar',['class'=>'btn btn-primary','type'=>"submit"])
                ];         
            }else if($model->load($request->post())){
                $this->functionUpdateMultiple($model, $docentesHasServicio, 1);
                return [
                    'forceReload'=>'#crud-datatable-pjax',
                    'title'=> "servicio #".$id,
                    'content'=>$this->renderAjax('view', [
                        'model' => $model,
                        'docentesHasServicio' => (empty($docentesHasServicio)) ? [new DocenteHasServicio()] : $docentesHasServicio,

                    ]),
                    'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                            Html::a('Editar',['update','id'=>$id],['class'=>'btn btn-primary','role'=>'modal-remote'])
                ];    
            }else{
                 return [
                    'title'=> "Modificar servicio #".$id,
                    'content'=>$this->renderAjax('update', [
                        'model' => $model,
                        'docentesHasServicio' => (empty($docentesHasServicio)) ? [new DocenteHasServicio()] : $docentesHasServicio,

                    ]),
                    'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Guardar',['class'=>'btn btn-primary','type'=>"submit"])
                ];        
            }
        }else{
            /*
            *   Process for non-ajax request
            */
            if ($model->load($request->post())) {
                $this->functionUpdateMultiple($model, $docentesHasServicio, 0);

                return $this->redirect(['view-match', 'id' => $model->id_servicio]);
            } else {
                return $this->render('update', [
                    'model' => $model,
                    'docentesHasServicio' => (empty($docentesHasServicio)) ? [new docenteHasServicio] : $docentesHasServicio,
                ]);
            }
        }
    }

    /**
     * Delete an existing servicio model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        //SIRVE PARA ASIGNADOS Y NO ASIGNADOS
        //ELIMINA LA ASIGNACIÓN DE SERVICIO POR CASCADA EN EL MATCH1
        $request = Yii::$app->request;
        $servicioModel = $this->findModel($id);
        if($servicioModel->sinMatch == 1){
            $url = 'index-servicios-no-asignados';
        }else{
            $url = 'index';
        }
        $filasMatch = $servicioModel->match1s;

        if($filasMatch != null){
            if($filasMatch[0]->implementacion_id_implementacion == null){
                foreach ($filasMatch as $filaModel){
                    //NO SE LE CAMBIA EL ESTADO AL SERVICIO PUESTO QUE SE ELIMINA
                    Yii::$app->estados->actualizarEstadoRequerimiento($filaModel->requerimiento_id_requerimiento, "No Asignado");
                }
                $servicioModel->delete();
            }else{
                Yii::$app->mensaje->mensajeGrowl('error', 'No se puede eliminar un servicio que está implementado.');
                return $this->redirect(Yii::$app->request->referrer);
            }
        }else{
            $servicioModel->delete();
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
            return $this->redirect([$url]);
        }


    }

     /**
     * Delete multiple existing servicio model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionBulkDelete()
    {
        //ESTE MÉTODO SE MODIFICARÁ PARA SETEAR A NULL EL SERVICIO
        $request = Yii::$app->request;
        $pks = explode(',', $request->post( 'pks' )); // Array or selected records primary keys
        foreach ( $pks as $pk ) {
            //$model = $this->findModel($pk);
            $filaMatch = Match1::findOne($pk);
            //$filasMatch = $model->match1s;

            if($filaMatch != null){
                // PRE $filasMatch[0]
                if($filaMatch->implementacion_id_implementacion != null){
                    Yii::$app->mensaje->mensajeGrowl('error', 'No se puede eliminar un servicio que está implementado.');
                    return $this->redirect(Yii::$app->request->referrer);
                }
                $filaMatch->servicio_id_servicio = null;
                $filaMatch->save(false);
            }

            //SI VIENE DE SERVICIO NO ASIGNADO
            $servicio = $this->findModel($pk);
            if($servicio != null){
                $servicio->delete();
            }

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
     * Finds the servicio model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return servicio the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = servicio::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionClonarServicio()
    {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            if(isset($data["idServicio"])){
                $modelServicio = $model = servicio::findOne($data["idServicio"]);
                if($modelServicio !== null){
                    return [
                        'modelServicio' => $modelServicio,
                        'texto' => 'exito',
                        'code' => 100,
                    ];
                }else{
                    return [
                        'texto' => 'fracaso',
                        'code' => 200,
                    ];
                }
            }
        }
    }

    public function actionGridViewModal()
    {
        if (Yii::$app->request->isAjax) {
            $searchModel = new ServicioSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

            return $this->renderAjax('gridViewModal', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        }
    }

    public function actionMatchAsociado()
    {
        $periodoModel = new AnioSemestre();
        $searchModel = new Match1Search();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $request = Yii::$app->request;
        //if (Yii::$app->request->isAjax) {
            if($periodoModel->load($request->post()) && $periodoModel->validate()){
                return $this->redirect(['/servicio/seleccion-requerimientos', 'anio'=>$periodoModel->anio, 'semestre'=> $periodoModel->semestre]);
            }//FIN POST

            return $this->render('/servicio/asignarServicioMatch/createServicioConMatch', [
                'modeloPeriodo' => $periodoModel,
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        //}
    }

    public function actionVerServiciosNoAsignados()
    {
        $periodoModel = new AnioSemestre();
        $searchModel = new Match1Search();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->andFilterWhere(['sinMatch' => 1]);

        $request = Yii::$app->request;
        //if (Yii::$app->request->isAjax) {
        if($periodoModel->load($request->post()) && $periodoModel->validate()){
            return $this->redirect(['/servicio/index-servicios-no-asignados', 'anio'=>$periodoModel->anio, 'semestre'=> $periodoModel->semestre]);
        }//FIN POST

        return $this->render('/servicio/verServiciosNoAsignados/seleccionarPeriodo', [
            'modeloPeriodo' => $periodoModel,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
        //}
    }

    public function actionVerServiciosAsignados()
    {
        $periodoModel = new AnioSemestre();
        $searchModel = new Match1Search();
        $dataProvider = $searchModel->searchResultadoAsignacionServicios(Yii::$app->request->queryParams);

        $request = Yii::$app->request;
        //if (Yii::$app->request->isAjax) {
        if($periodoModel->load($request->post()) && $periodoModel->validate()){
            return $this->redirect(['/servicio/index-servicios-asignados', 'anio'=>$periodoModel->anio, 'semestre'=> $periodoModel->semestre]);
        }//FIN POST

        return $this->render('/servicio/verServiciosAsignados/seleccionarPeriodo', [
            'modeloPeriodo' => $periodoModel,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
        //}
    }

    public function actionIndexServiciosNoAsignados($anio=null, $semestre=null){
        $modeloPeriodo = new AnioSemestre();

        $modeloPeriodo->anio = $anio;
        $modeloPeriodo->semestre = $semestre;

        //LAS CONSULTAS DE ARRIBA SIGUEN EL REGIMEN ANTIGUO
        $query = Match1::find()->select("GROUP_CONCAT(id_match1) idesAgrupadas, match1.*")
            ->where(['anio_match1'=> $modeloPeriodo->anio])
            ->andWhere(['semestre_match1'=>$modeloPeriodo->semestre])
            //->andWhere(['implementacion_id_implementacion' => NULL])
            ->andWhere(['not', ['servicio_id_servicio' => NULL]])
            ->groupBy(['servicio_id_servicio']);

        $cantidad = $query->count();

        $searchModel = new ServicioSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->andFilterWhere(['sinMatch' => 1]);

        //$dataProvider->query->andFilterWhere(['estado_ejecucion_id_estado'=>1]);

        return $this->render('//servicio/verServiciosNoAsignados/indexServiciosNoAsignados', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionIndexServiciosAsignados($anio, $semestre){
        $modeloPeriodo = new AnioSemestre();

        $modeloPeriodo->anio = $anio;
        $modeloPeriodo->semestre = $semestre;

/*        $query = Match1::find()
            ->where(['anio_match1'=> $modeloPeriodo->anio])
            ->andWhere(['semestre_match1'=>$modeloPeriodo->semestre])
            //->andWhere(['implementacion_id_implementacion' => NULL])
            ->andWhere(['not', ['servicio_id_servicio' => NULL]]);


        $cantidad = $query->count();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            //'key' => 'servicio_id_servicio',
            'pagination' => [
                'pageSize' => $cantidad,
            ],
        ]);*/

        $searchModel = new Match1Search();
        $dataProvider = $searchModel->searchResultadoAsignacionServicios(Yii::$app->request->queryParams);
        $dataProvider->query->andFilterWhere(['anio_match1'=> $modeloPeriodo->anio]);
        $dataProvider->query->andFilterWhere(['semestre_match1'=> $modeloPeriodo->semestre]);
        $dataProvider->query->andWhere(['not', ['servicio_id_servicio' => null]]);

        return $this->render('//servicio/verServiciosAsignados/indexServiciosAsignados', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionSeleccionAsignatura($anio, $semestre)
    {
        $periodoModel = new AnioSemestre();
        $searchModel = new Match1Search();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $request = Yii::$app->request;

        if($periodoModel->load($request->post()) && $periodoModel->validate()){
            $dataProvider->query->andFilterWhere(['anio_match1'=>$periodoModel->anio, 'semestre_match1'=>$periodoModel->semestre]);
            return $this->render('matchAsociado', [
                'modeloPeriodo' => $periodoModel,
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        }//FIN POST

        $dataProvider->query->andFilterWhere(['anio_match1'=>$anio, 'semestre_match1'=>$semestre]);
        return $this->render('matchAsociado', [
            'modeloPeriodo' => $periodoModel,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);

    }

    public function actionSeleccionRequerimientos($anio, $semestre)
    {
        $periodoModel = new AnioSemestre();
        $searchModel = new Match1Search();
        $dataProvider = $searchModel->searchResultadoMatch1(Yii::$app->request->queryParams);

        $dataProvider->query->andFilterWhere(['anio_match1'=>$anio, 'semestre_match1'=>$semestre]);

        $subQuery = Match1::find()->select('requerimiento_id_requerimiento')->where(['not', ['servicio_id_servicio' => NULL]]);
        //PARA OBTENER SÓLO LOS MODELOS NO OCUPADOS AKA MODELOS DESOCUPADOS IRÁN EN EL DATAPROVIDER
        $dataProvider->query->andFilterWhere(['not in', 'requerimiento_id_requerimiento', $subQuery]);
        $modelosOcupados = Match1::find()->select('id_match1')->indexBy('id_match1')->where(['in', 'requerimiento_id_requerimiento', $subQuery])->asArray()->all();
        $soloIdesOcupadas = array_keys($modelosOcupados);

        return $this->render('matchAsociado', [
            'modeloPeriodo' => $periodoModel,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'soloIdesOcupadas' => $soloIdesOcupadas,
        ]);

    }

    private function funcionCrearMultiple($model, $docentesHasServicio, $esAjax){
        //ES AJAX 0 = NO, 1 = SI
        $model->sinMatch = 1;

        $valid = $model->validate();
        $valid = Model::validateMultiple($docentesHasServicio) && $valid;

        if ($valid) {
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                if ($flag = $model->save(false)) {
                    foreach ($docentesHasServicio as $docenteHasServicio) {
                        $docenteHasServicio->servicio_id_servicio = $model->id_servicio;
                        if (! ($flag = $docenteHasServicio->save(false))) {
                            $transaction->rollBack();
                            break;
                        }
                    }
                }
                if ($flag) {
                    if($esAjax == 0){
                        $transaction->commit();
                        return $this->redirect(['view', 'id' => $model->id_servicio]);
                    }else{
                        $transaction->commit();
                        return true;
                    }
                }
            } catch (Exception $e) {
                $transaction->rollBack();
            }
        }//FIN VALID
    }

    private function functionUpdateMultiple($model, $docentesHasServicio, $esAjax){
        //$esAjax 0 = NO , 1 = SI
        $oldIDs = ArrayHelper::map($docentesHasServicio, 'docente_rut_docente', 'docente_rut_docente');

        $docentesHasServicio = Model::createMultiple(docenteHasServicio::classname(), $docentesHasServicio);
        Model::loadMultiple($docentesHasServicio, Yii::$app->request->post());

        $deletedIDs = array_diff($oldIDs, array_filter(ArrayHelper::map($docentesHasServicio, 'docente_rut_docente', 'docente_rut_docente')));


        // validate all models
        $valid = $model->validate();
        $valid = Model::validateMultiple($docentesHasServicio) && $valid;

        if ($valid) {
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                if ($flag = $model->save(false)) {
                    if (! empty($deletedIDs)) {
                        docenteHasServicio::deleteAll(['docente_rut_docente' => $deletedIDs]);
                    }
                    foreach ($docentesHasServicio as $docenteHasServicio) {
                        $docenteHasServicio->servicio_id_servicio = $model->id_servicio;
                        if (! ($flag = $docenteHasServicio->save(false))) {
                            $transaction->rollBack();
                            break;
                        }
                    }
                }
                if ($flag) {
                    $transaction->commit();
                    if($esAjax == 0){
                        $transaction->commit();
                        return $this->redirect(['view', 'id' => $model->id_servicio]);
                    }else{
                        $transaction->commit();
                        return true;
                    }
                }
            } catch (Exception $e) {
                $transaction->rollBack();
            }
        }
    }


    public function action1AsignaturaNRequerimiento(){
        //ESTA REGLA DE NEGOCIO, VERIFICA QUE LA SELECCIÓN ACTUAL CORRESPONDE CON LA REGLA 1 ASIGNATURA PUEDE SATISFACER
        //1 O MÁS REQUERIMIENTOS, MÁS NO AL REVÉS.
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            if (isset($data["idMatch"])) {
                $modeloMatch = Match1::findOne($data["idMatch"]);
                if($modeloMatch != null){
                    $idAsignatura = $modeloMatch->asignatura_cod_asignatura;
                    $session = Yii::$app->session;
                    if(isset($data["reemplazar"])){
                        $session->remove('comprobacionReglaServicio');
                    }
                    //$session->remove('comprobacionReglaServicio');
                    $session->open();
                    //ES EL PRIMERO?
                    if(isset($session["comprobacionReglaServicio"])){
                        $arreglo = $session["comprobacionReglaServicio"];
                        $flag = false;
                        //TIENE EL MISMO COD DE ASIGNATURA?
                        foreach ($arreglo as $idGuardada){
                            if($idGuardada == $idAsignatura){
                                $flag = true;
                                break;
                            }
                        }
                        //TIENE EL MISMO COD DE ASIGNATURA?
                        if($flag) {
                            $arreglo += array(intval($data["idMatch"]) => intval($idAsignatura));
                            $session["comprobacionReglaServicio"] = $arreglo;
                            return [
                                'codigo' => 'exito',
                            ];
                        }else{
                            //SI NO SE ENCONTRÓ ENTONCES SELECCIONÓ OTRO
                            return [
                                'texto' => "Si selecciona varios Requerimientos, deben pertenecer a la misma asignatura.
                                 Desea cambiar su selección?",
                                'codigo' => 'error',
                                'codigoNumero' => 3,
                            ];

                        }
                    }else{
                        $session['comprobacionReglaServicio'] = array(intval($data["idMatch"]) => intval($idAsignatura));
                        $session->close();
                        return [
                            'texto' => "Se guardó una nueva id",
                            'codigo' => 'exito',
                        ];
                    }


                }else{
                    return [
                        'texto' => 'No se encontró el match asociado.',
                        'codigo' => 'error',
                        'codigoNumero' => 1,
                    ];
                }
            }else{
                return [
                    'texto' => 'Problema en parámetros: ID Match.',
                    'codigo' => 'error',
                    'codigoNumero' => 2,
                ];
            }
        }

    }//FIN VERIFICACION

    public function actionEliminarSeleccionado(){
        //ESTA REGLA DE NEGOCIO, VERIFICA QUE LA SELECCIÓN ACTUAL CORRESPONDE CON LA REGLA 1 ASIGNATURA PUEDE SATISFACER
        //1 O MÁS REQUERIMIENTOS, MÁS NO AL REVÉS.
        if (Yii::$app->request->isAjax) {
            $idMatch = Yii::$app->request->post('idMatch');
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            if($idMatch != null) {
                $session = Yii::$app->session;
                $session->open();
                if (isset($session["comprobacionReglaServicio"])) {
                    $arreglo = $session["comprobacionReglaServicio"];
                    unset($arreglo[$idMatch]);
                    if(count($arreglo) == 0){ //para remover el array
                        $session->remove('comprobacionReglaServicio');
                    }else{
                        $session["comprobacionReglaServicio"] = $arreglo;
                    }
                } else {
                    return [
                        'texto' => 'fracaso',
                        'motivo' => 'Arreglo Seleccionados No inicializado',
                        'code' => 200,
                    ];                }
                $session->close();

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
        }

    }//FIN Eliminación

    public function actionEstaVacioSeleccion(){
        if (Yii::$app->request->isAjax) {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            $session = Yii::$app->session;
            $session->open();
            if(isset($session["comprobacionReglaServicio"])){
                $session->close();
                return [
                    'texto' => 'exito',
                    'code' => 100,
                ];
            }else{
                $session->close();
                return [
                    'texto' => 'fracaso',
                    'code' => 200,
                ];
            }
        }//FIN IF AJAX
    }

    public function actionVerificarPeriodoIniciado(){
        $request = Yii::$app->request;
        if($request->isAjax){
            $dataAjax = $request->post();

            $modelMax = Match1::find()->where(['not', ['servicio_id_servicio' => null]])->orderBy('anio_match1 DESC, semestre_match1 DESC')->one();
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            $max = 0; //CASO BASE
            $maxSemestre = 0;
            if($modelMax){
                $max = $modelMax->anio_match1;
                $maxSemestre = $modelMax->semestre_match1;
                return [
                    'anio'=> $max,
                    'semestre'=> $maxSemestre,
                    'codigo' => "exito",
                ];
            }else{
                return [
                    'respuesta'=> "No se ha encontrado un registro de Asignación de Servicios",
                    'codigo' => "error",
                ];
            }


        }//FIN IF AJAX
    }

}
