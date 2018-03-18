<?php

namespace backend\controllers;

use backend\models\EstadoEjecucion;
use backend\models\SeleccionBitacorasReporte;
use Yii;
use yii\base\Exception;

use yii\base\DynamicModel;
use backend\models\match1;
use backend\models\sci;
use backend\models\search\Match1Search;
use backend\models\search\SciSearch;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;

//PARA LA ASIGNACIÓN
use backend\models\Requerimiento;
use backend\models\AnioSemestre;
use backend\models\search\RequerimientoSearch;
use backend\models\Asignatura;
use backend\models\search\AsignaturaSearch;
use yii\data\ArrayDataProvider;
use yii\data\ActiveDataProvider;
use yii\db\Query;

/**
 * Match1Controller implements the CRUD actions for match1 model.
 */
class Match1Controller extends Controller
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
     * Lists all match1 models.
     * @return mixed
     */
    public function actionIndex()
    {
        $periodoModel = new AnioSemestre();
        $searchModel = new Match1Search();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        //$dataProvider->setSort(['enableMultiSort' => true, 'defaultOrder' => ['anio_match1'=>SORT_ASC,'semestre_match1'=>SORT_ASC,]]);
        $dataProvider->setSort(['enableMultiSort' => true, 'defaultOrder' => ['semestre_match1'=>SORT_ASC, 'requerimiento_id_requerimiento'=>SORT_ASC, ]]);
        //$dataProvider->setSort(['attributes' => ['semestre_match1', 'requerimiento_id_requerimiento']]);
        $dataProvider->setPagination(['pageSize' => 10]);

        return $this->render('index', [
            'periodoModel' => $periodoModel,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Displays a single match1 model.
     * @param integer $requerimiento_id_requerimiento
     * @param integer $asignatura_cod_asignatura
     * @return mixed
     */
    public function actionView($id)
    {
        $request = Yii::$app->request;
        if($request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title'=> "match1",
                'content'=>$this->renderAjax('view', [
                    'model' => $this->findModel($id),
                ]),
                'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                    Html::a('Edit',['update','id'=>$id],['class'=>'btn btn-primary','role'=>'modal-remote'])
            ];
        }else{
            return $this->render('view', [
                'model' => $this->findModel($id),
            ]);
        }
    }

    /**
     * Creates a new match1 model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $request = Yii::$app->request;
        $model = new match1();

        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet){
                return [
                    'title'=> "Create new match1",
                    'content'=>$this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Save',['class'=>'btn btn-primary','type'=>"submit"])

                ];
            }else if($model->load($request->post()) && $model->save()){
                return [
                    'forceReload'=>'#crud-datatable-pjax',
                    'title'=> "Create new match1",
                    'content'=>'<span class="text-success">Create match1 success</span>',
                    'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                            Html::a('Create More',['create'],['class'=>'btn btn-primary','role'=>'modal-remote'])

                ];
            }else{
                return [
                    'title'=> "Create new match1",
                    'content'=>$this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Save',['class'=>'btn btn-primary','type'=>"submit"])

                ];
            }
        }else{
            /*
            *   Process for non-ajax request
            */
            if ($model->load($request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id_match1]);
            } else {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        }

    }

    /**
     * Updates an existing match1 model.
     * For ajax request will return json object
     * and for non-ajax request if update is successful, the browser will be redirected to the 'view' page.
     * @param integer $requerimiento_id_requerimiento
     * @param integer $asignatura_cod_asignatura
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
                    'title'=> "Update match1 ",
                    'content'=>$this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                        Html::button('Save',['class'=>'btn btn-primary','type'=>"submit"])
                ];
            }else if($model->load($request->post()) && $model->save()){
                return [
                    'forceReload'=>'#crud-datatable-pjax',
                    'title'=> "match1 ",
                    'content'=>$this->renderAjax('view', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                        Html::a('Edit',['update','id'=>$id],['class'=>'btn btn-primary','role'=>'modal-remote'])
                ];
            }else{
                return [
                    'title'=> "Update match1",
                    'content'=>$this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                        Html::button('Save',['class'=>'btn btn-primary','type'=>"submit"])
                ];
            }
        }else{
            /*
            *   Process for non-ajax request
            */
            if ($model->load($request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id_match1]);
            } else {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        }
    }

    /**
     * Delete an existing match1 model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $requerimiento_id_requerimiento
     * @param integer $asignatura_cod_asignatura
     * @return mixed
     */
    public function actionDelete($id)
    {
        $aEliminar = $this->findModel($id);
        $idReq = $aEliminar->requerimiento_id_requerimiento;
        $anio = $aEliminar->anio_match1;
        $semestre = $aEliminar->semestre_match1;

        $request = Yii::$app->request;
        $this->findModel($id)->delete();

        //Verificar si se eliminaron todos los requerimientos para cambiar el estado
        //ID REQ, ANIO SEMESTE
        $siExiste = Match1::find()->where(['requerimiento_id_requerimiento'=> $idReq,'anio_match1'=> $anio,'semestre_match1'=> $semestre])->all();
        if(!$siExiste){
            $modelReq = Requerimiento::findOne($idReq);
            $modelReq->preseleccionado_match1 = 0;
            $modelReq->save(false);
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
     * Delete multiple existing match1 model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $periodo_id_periodo
     * @param integer $requerimiento_id_requerimiento
     * @param integer $asignatura_cod_asignatura
     * @return mixed
     */
    public function actionBulkDelete()
    {
        $request = Yii::$app->request;
        $pks = explode(',', $request->post( 'pks' )); // Array or selected records primary keys

        $aEliminar = $this->findModel($pks[0]);
        $idReq = $aEliminar->requerimiento_id_requerimiento;
        $anio = $aEliminar->anio_match1;
        $semestre = $aEliminar->semestre_match1;

        foreach ( $pks as $pk ) {
            $model = $this->findModel($pk);
            $model->delete();
        }
        //Verificar si se eliminaron todos los requerimientos para cambiar el estado
        //ID REQ, ANIO SEMESTE
        $siExiste = Match1::find()->where(['requerimiento_id_requerimiento'=> $idReq,'anio_match1'=> $anio,'semestre_match1'=> $semestre])->all();
        if(!$siExiste){
            $modelReq = Requerimiento::findOne($idReq);
            $modelReq->preseleccionado_match1 = 0;
            $modelReq->save(false);
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
     * Finds the match1 model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $periodo_id_periodo
     * @param integer $requerimiento_id_requerimiento
     * @param integer $asignatura_cod_asignatura
     * @return match1 the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = match1::findOne(['id_match1' => $id])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('La página solicitada, no existe.');
        }
    }

    public function actionPrincipal()
    {
        $searchModel = new Match1Search();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('principal', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionGuardarSeleccion()
    {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $session = Yii::$app->session;
            $session->open();
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            if(isset($session['seleccion3']['seleccionados']) && isset($data['periodo'])&& isset($data['requerimientoPadre'])){
                $asignaturas = $session['seleccion3']['seleccionados'];
                $transaction = \Yii::$app->db->beginTransaction();
                $ultimaAsignatura = 0;

                $idsAntiguas = Match1::find()->select('asignatura_cod_asignatura')->indexBy('asignatura_cod_asignatura')
                    ->where(["anio_match1" =>$data['periodo']['año'], "semestre_match1" => $data['periodo']['semestre'],
                        "requerimiento_id_requerimiento"=> $data['requerimientoPadre']])->asArray()->all();

                $idsAntiguas = (ArrayHelper::map($idsAntiguas, 'asignatura_cod_asignatura', 'asignatura_cod_asignatura'));

                $idsEliminadas = array_diff($idsAntiguas, $asignaturas);

                try {
                    if (! empty($idsEliminadas)) {
                        foreach ($idsEliminadas as $id => $idEliminada){
                            $aEliminar = Match1::find()
                                ->where(["anio_match1" =>$data['periodo']['año'], "semestre_match1" => $data['periodo']['semestre'],
                                    "asignatura_cod_asignatura"=> $id, "requerimiento_id_requerimiento"=> $data['requerimientoPadre']])->one();

                            if($aEliminar != null){
                                $this->deleteCambioEstadoRequerimiento($aEliminar);
                                unset($asignaturas[$id]);
                            }
                        }// FIN FOR
                    }

                    $flag=null;
                    $i=0;
                    foreach ($asignaturas as $asignatura) {
                        $existe = Match1::find()
                            ->where(["anio_match1" =>$data['periodo']['año'], "semestre_match1" => $data['periodo']['semestre'],
                            "asignatura_cod_asignatura"=> $asignatura, "requerimiento_id_requerimiento"=> $data['requerimientoPadre']])->one();
                        if($existe != null){
                            $model = $existe;
                        }else{
                            $model = new match1();
                        }
                        $model->anio_match1 = $data['periodo']['año'];
                        $model->semestre_match1 = $data['periodo']['semestre'];
                        $model->requerimiento_id_requerimiento = $data['requerimientoPadre'];
                        $model->asignatura_cod_asignatura = $asignatura;
                        $ultimaAsignatura = $asignatura;
                        if (! ($flag = $model->save(false))) {
                            $transaction->rollBack();
                            break;
                        }
                        $i++;
                    }

                    if ($flag) {
                        $transaction->commit();
                        $requerimiento = requerimiento::findOne($data['requerimientoPadre']);
                        $requerimiento->preseleccionado_match1 = 1;
                        $requerimiento->save(false);

                        //$session->remove('seleccion3');
                        $session->close();
                        return [
                            'texto' => 'Exito',
                            'code' => 100,
                        ];
                    }

                    if(empty($asignaturas)){
                        $transaction->commit();
                        $requerimiento = requerimiento::findOne($data['requerimientoPadre']);
                        $requerimiento->preseleccionado_match1 = 0;
                        $requerimiento->save(false);
                        //$session->remove('seleccion3');
                        $session->close();
                        return [
                            'texto' => 'Exito',
                            'code' => 100,
                        ];
                    }
                } catch (\yii\db\Exception $e) {
                    $transaction->rollBack();
                    $session->close();
                    return [
                        'texto' => 'Error al guardar en la base de datos',
                        'code' => 200,
                        'codAsignatura' => $asignaturas[$ultimaAsignatura],
                    ];
                    //throw new \yii\web\HttpException(405, 'Error al guardar selección');
                } catch (Exception $e){
                    $transaction->rollBack();
                }


                return [
                    'texto' => 'exito',
                    'code' => 100,
                    'padre' => $data['requerimientoPadre'],
                    'periodo' => $data['periodo'],
                ];
            }else{
                return [
                    'texto' => 'fracaso',
                    'code' => 200,
                ];
            }


            //$selectedItems = explode(":", $data['selectedItems']);
            /* if(isset($data['selectedItems'])){
                 $selectedItems = $data['selectedItems'];
             }*/
            //$searchname= $searchname[0];
            //$searchby= $searchby[0];
            //$search = // your logic;

        }
    }

    public function actionLimpiarSeleccion()
    {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            \Yii::$app->response->format = Response::FORMAT_JSON;

            $session = Yii::$app->session;
            $session->open();
            if(isset($session['seleccion3']['seleccionados'])){
                $session->remove('seleccion3');
            }
            $session->close();
            return [
              'exito' => 100,
            ];
        }
        return [
            'exito' => 100,
        ];
    }

    public function actionServiceMatch1()
    {
        if (Yii::$app->request->isAjax) {
            //if(Yii::$app->request->post() != null){
            $data = Yii::$app->request->post();
            //}
            $asignaturas = Yii::$app->db->createCommand("SELECT DISTINCT nombre_asignatura, resultado_aprendizaje FROM asignatura WHERE nombre_asignatura LIKE :query OR resultado_aprendizaje LIKE :query");
            $prueba = '%emprendimiento%';
            $asignaturas->bindParam(':query', $prueba);
            $asignaturas = $asignaturas->query(); //query database into Data Reader object
            $asignaturas = $asignaturas->readAll();
            /*foreach($asignaturas as $asignatura){
                var_dump($asignatura);
            }*/


            $searchModel = new Match1Search();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

            return $this->renderPartial('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
            //\Yii::$app->response->format = Response::FORMAT_JSON;
            /*return [
                'texto' => 'exito',
                'code' => 100,
                'asignaturas' => $asignaturas,
                //'asd' => $data['requerimientoPadre'],
            ];*/
        }
    }

    public function actionServiceTagInput($query)
    {
        $asignaturas = Yii::$app->db->createCommand("SELECT DISTINCT nombre_asignatura, resultado_aprendizaje FROM asignatura WHERE nombre_asignatura LIKE :query OR resultado_aprendizaje LIKE :query");
        $prueba = '%'.$query.'%';
        $asignaturas->bindParam(':query', $prueba);
        $asignaturas = $asignaturas->query(); //query database into Data Reader object
        $asignaturas = $asignaturas->readAll();
        /*foreach($asignaturas as $asignatura){
            var_dump($asignatura);
        }*/

        Yii::$app->response->format = Response::FORMAT_JSON;
        return $asignaturas;
    }

    public function actionAsignacion()
    {
        $modeloNoAsignado = EstadoEjecucion::find()->where(['nombre_estado' => 'No Asignado'])->one();
        $requerimientos = Requerimiento::find()->where(['estado_ejecucion_id_estado' => $modeloNoAsignado->id_estado])->asArray()->all();
        $modelosFalsos=[];
        $sugeridos=[];

        foreach ($requerimientos as $requerimiento){
            $paraTags = Requerimiento::findOne($requerimiento["id_requerimiento"]);
            $tags = str_replace(',', '', $paraTags->tagValues);
            $sugeridos = Yii::$app->db->createCommand(
                'SELECT asignatura.cod_asignatura, asignatura.nombre_asignatura, asignatura.resultado_aprendizaje, MATCH(nombre_asignatura, resultado_aprendizaje) AGAINST (:search IN BOOLEAN MODE) as score FROM asignatura WHERE MATCH(asignatura.nombre_asignatura, asignatura.resultado_aprendizaje) AGAINST (:search) ORDER BY score DESC'
            )
                ->bindValue(':search', $tags)
                ->queryAll();
            foreach ($sugeridos as $sugerido){
                $modelosFalsos[] = array_merge($requerimiento, $sugerido);
            }
        }

        $dataProviderMatch1 = new ArrayDataProvider([
            'allModels' => $modelosFalsos,
            'sort' => [
                //PERMITE PRESIONAR SOBRE EL NOMBRE DEL ATRIBUTO Y PONER ASC O DESC
                'attributes' => ['id_requerimiento', 'titulo', 'descripcion'],
            ],
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        $dataProviderRequerimientos = new ActiveDataProvider([
            'query' => Requerimiento::find()->where('estado_ejecucion_id_estado = 1'),
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);


        return $this->render('asignacion', [
            'dataProviderRequerimientos' => $dataProviderRequerimientos,
            'dataProviderMatch1' => $dataProviderMatch1,
            'sugeridos' => $sugeridos,
        ]);
    }//FIN ASIGNACIÓN MATCH1

    public function actionSeleccion($anio=null, $semestre=null)
    {
        $request = Yii::$app->request;

        $modeloPeriodo = new AnioSemestre();

        if($request->isAjax){
            if($request->isPost){
                if($modeloPeriodo->load(Yii::$app->request->post())){
                    \Yii::$app->response->format = Response::FORMAT_JSON;
                    if($modeloPeriodo->validate()){
                        $session = Yii::$app->session;
                        $session->open();
                            $session->set('fechaMatch1', $modeloPeriodo->attributes);
                        $session->close();

                        return [
                            'validacion'=> 1,
                            'modelFecha' =>$modeloPeriodo
                        ];

                    }else{
                        return [
                            'validacion'=> 0,
                        ];
                    }
                }
            }
        }

        if($request->isPost || ($anio != null && $semestre != null)){
            if($modeloPeriodo->load(Yii::$app->request->post()) && $modeloPeriodo->validate() || ($anio != null && $semestre != null)){
                    $session = Yii::$app->session;
                    $session->open();
                    if(isset($modeloPeriodo->anio)){
                        $session->set('fechaMatch1', $modeloPeriodo->attributes);
                    }else{
                        $_SESSION['fechaMatch1']['anio'] = $anio;
                        $_SESSION['fechaMatch1']['semestre'] = $semestre;
                    }
                    $session->close();

                    $searchModel = new SciSearch();
                    $dataProviderSci = $searchModel->searchNoAsignado(Yii::$app->request->queryParams);


                    return $this->redirect(['/match1/seleccion-socio']);
            }else{
                return $this->render('create', [
                    'modelFecha' => $modeloPeriodo,
                ]);
            }
        }

        $searchModel = new SciSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $query = Sci::find()->where('(SELECT COUNT(*) FROM requerimiento WHERE sci_id_sci = sci.id_sci
            AND estado_ejecucion_id_estado = 1) <> 0');

        $dataProviderSci = new ActiveDataProvider([
            //Devuelve los socios que tienen requerimientos no asignados solamente
            'query' => $query,
            'pagination' => [
                'pageSize' => 3,
            ],
        ]);
        $dataProviderSci = $searchModel->searchNoAsignado(Yii::$app->request->queryParams);

        $modeloPeriodo = new AnioSemestre();

        return $this->render('create', [
            'dataProviderSci' => $dataProviderSci,
            'searchModel' => $searchModel,
            'modeloPeriodo' => $modeloPeriodo,
        ]);
    }//FIN ASIGNACIÓN MATCH1

    public function actionVerResultado()
    {
        $request = Yii::$app->request;

        $modeloPeriodo = new AnioSemestre();

        if($request->isPost){
            if($modeloPeriodo->load(Yii::$app->request->post()) && $modeloPeriodo->validate()){
                $session = Yii::$app->session;
                $session->open();
                $session->set('fechaMatch1', $modeloPeriodo->attributes);
                $session->close();

                return $this->redirect(['/match1/resultado-periodo']);

            }else{
                return $this->render('verResultado', [
                    'modelFecha' => $modeloPeriodo,
                ]);
            }
        }


        $modeloPeriodo = new AnioSemestre();

        return $this->render('verResultado', [
            'modeloPeriodo' => $modeloPeriodo,
        ]);
    }//FIN ASIGNACIÓN MATCH1

    public function actionResultadoPeriodo()
    {
        $session = Yii::$app->session;
        $session->open();
        if($session->has('fechaMatch1')){
            $arregloFecha = $session->get('fechaMatch1');
            $session->close();

            $periodoModel = new AnioSemestre();
            $searchModel = new Match1Search();
            $dataProvider = $searchModel->searchResultadoMatch1(Yii::$app->request->queryParams);
            $dataProvider->query->andFilterWhere(['anio_match1'=>$arregloFecha["anio"], 'semestre_match1'=>$arregloFecha["semestre"]]);
            $dataProvider->setSort(['enableMultiSort' => true, 'defaultOrder' => ['semestre_match1'=>SORT_ASC, 'requerimiento_id_requerimiento'=>SORT_ASC, ]]);

            $dataProvider->setPagination(['pageSize' => 10]);

            return $this->render('verResultadoIndex', [
                'periodoModel' => $periodoModel,
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        }else{
            return Yii::$app->response->redirect(Url::to(['/match1/ver-resultado']));

        }


    }//FIN ASIGNACIÓN MATCH1

    public function actionSeleccionSocio()
    {
        $searchModel = new SciSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProviderSci = $searchModel->searchNoAsignado(Yii::$app->request->queryParams);
        //$dataProviderSci = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('seleccionSocio', [
            'dataProviderSci' => $dataProviderSci,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProviderSci,
        ]);
    }

    public function actionCargarVistaSci($id)
    {

        if(true){
            $modelSci = Sci::findOne($id);

            $model = $modelSci;
            $searchModelRequerimiento = new RequerimientoSearch();
            $dataProviderRequerimiento = $searchModelRequerimiento->searchNoEliminados(Yii::$app->request->queryParams);
            $dataProviderRequerimiento->query->andFilterWhere(['sci_id_sci'=>$model->id_sci]);
            $dataProviderRequerimiento->pagination =  [
                'defaultPageSize' => 1,
            ];

            return $this->renderAjax('/match1/vistasExternas/_viewRequerimientos', [
                'model' => $model,
                'searchModelRequerimiento' => $searchModelRequerimiento,
                'dataProviderRequerimiento' => $dataProviderRequerimiento,
            ]);
        }
    }


    public function actionSeleccion2($id)
    {
        $dataProviderRequerimientos = new ActiveDataProvider([
            'query' => Requerimiento::find()->joinWith('estadoEjecucionIdEstado')->where(['estado_ejecucion.nombre_estado'=>'No Asignado'])
                ->andWhere('sci_id_sci = '.$id),
                //->andWhere('preseleccionado_match1 = 0'),
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);
        $socio = Sci::findOne($id);

        $session = Yii::$app->session;
        $session->open();
        if(isset($session['seleccion3']['seleccionados'])){
            $_SESSION['seleccion3']['seleccionados'] = []; //VACIAR LA LISTA
        }else{
            $_SESSION['seleccion3']['seleccionados'] = []; //CREARLA
        }
        $session->close();

        return $this->render('seleccion2', [
            'dataProviderRequerimientos' => $dataProviderRequerimientos,
            'modeloSocio' => $socio,
        ]);
    }//FIN ASIGNACIÓN MATCH1

    public function actionSeleccion3($id)
    {
        if(Yii::$app->request->isGet && !Yii::$app->request->isAjax){
            $_SESSION['seleccion3']['seleccionados'] = [];
        }
        $session = Yii::$app->session;
        $session->open();

        $anio = 0;
        $semestre = 0;
        if($session->has('fechaMatch1')){
            $semestre = $session->get('fechaMatch1');
            $anio = $semestre["anio"];
            $semestre = $semestre["semestre"];
        }
        $session->close();

        $modeloEstadoEjecucion = EstadoEjecucion::find()->where(["nombre_estado" => "No Asignado"])->one();
        if($modeloEstadoEjecucion == null){
            echo "No se han ingresado los datos de estado de ejecución a la BD.";
            die;
        }

        $dataProviderRequerimientos = new ActiveDataProvider([
            'query' => Requerimiento::find()->where(['estado_ejecucion_id_estado' => $modeloEstadoEjecucion->id_estado])->andWhere('sci_id_sci = '.$id),
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        $requerimiento = Requerimiento::findOne($id);
        $sedeRequerimiento = $requerimiento->sciIdSci->sedeIdSede->nombre_sede;
        $tags = str_replace(',', '', $requerimiento->tagValues);

        if($semestre != 0){
            $query = \backend\models\Asignatura::find();//new yii\db\Query();
            $query->joinWith('carreraCodCarrera.facultadIdFacultad.sedeIdSede')
                ->where("MATCH(nombre_asignatura, resultado_aprendizaje) AGAINST (:search IN BOOLEAN MODE)")
                ->andWhere('nombre_sede = :nombreSede')
                ->andWhere('semestre_dicta = :semestre')
                ->addParams([':search' => $tags, ':nombreSede' => $requerimiento->sciIdSci->sedeIdSede->nombre_sede, ':semestre' => $semestre ]);
            //->orderBy(['score' => SORT_DESC]);
        }else{
            $query = \backend\models\Asignatura::find();//new yii\db\Query();
            $query->joinWith('carreraCodCarrera.facultadIdFacultad.sedeIdSede')
                ->where("MATCH(nombre_asignatura, resultado_aprendizaje) AGAINST (:search IN BOOLEAN MODE)")
                ->andWhere('nombre_sede = :nombreSede')
                ->addParams([':search' => $tags, ':nombreSede' => $requerimiento->sciIdSci->sedeIdSede->nombre_sede ]);
            //->orderBy(['score' => SORT_DESC]);
        }

        $provider = new ActiveDataProvider([
            'query' => $query,
            /*'key' => function ($asignatura) {
                return $asignatura['cod_asignatura'];
            },*/
            'pagination' => [
                //'route' => null,
                //'pageParam' => 'page'.$requerimiento->id_requerimiento,
                //'params' => $params,
                'pageSize' => 10,
            ],
        ]);

        $searchModelAsignatura = new AsignaturaSearch();
        $dataProviderAsignatura = $searchModelAsignatura->search(Yii::$app->request->queryParams);
        $dataProviderAsignatura->query->andFilterWhere(['nombre_sede'=>$sedeRequerimiento]);
        $dataProviderAsignatura->setPagination(['pageSize' => 10]);

        $provider->pagination->pageParam = 'asignatura-sistema-page';
        $provider->sort->sortParam = 'asignatura-sistema-sort';

        $dataProviderAsignatura->pagination->pageParam = 'asignatura-manual-page';
        $dataProviderAsignatura->sort->sortParam = 'asignatura-manual-sort';

        //CARGA LAS QUE YA TENÍA
        $tieneAsignaturas = 0;
        $session->open();
        if(!empty($_SESSION['seleccion3']['seleccionados'])){
            if(!Yii::$app->request->isAjax){
                $filasMatchDelRequerimiento = $requerimiento->getMatch1s()->select("asignatura_cod_asignatura")
                    ->indexBy("asignatura_cod_asignatura")->andFilterWhere(["anio_match1"=>$anio, "semestre_match1"=>$semestre])->asArray()->all();
                $modificarAsignaturas = array_keys($filasMatchDelRequerimiento);
                $modificarAsignaturas = array_map('strval', $modificarAsignaturas);
                if(!empty($modificarAsignaturas)){
                    $tieneAsignaturas = 1;
                    $seleccionados = array_keys($_SESSION['seleccion3']['seleccionados']);
                    $seleccionados = array_unique(array_merge($seleccionados,$modificarAsignaturas));
                    $seleccionados = array_combine($seleccionados,$seleccionados);
                    $seleccionados = array_map('strval', $seleccionados);
                    $_SESSION['seleccion3']['seleccionados'] = $seleccionados;
                }
            }

            $idsSeleccionadas = $session['seleccion3']['seleccionados'];
            $idsSeleccioandasEnteras = json_decode('[' . (implode(',', $idsSeleccionadas)) . ']', true);
            $querySession = Asignatura::find()->where(['cod_asignatura' => $idsSeleccioandasEnteras]);
        }else {
            $filasMatchDelRequerimiento = $requerimiento->getMatch1s()->select("asignatura_cod_asignatura")
                ->indexBy("asignatura_cod_asignatura")->andFilterWhere(["anio_match1" => $anio, "semestre_match1" => $semestre])->asArray()->all();
            $modificarAsignaturas = array_keys($filasMatchDelRequerimiento);
            $modificarAsignaturas = array_map('strval', $modificarAsignaturas);
            if (!empty($modificarAsignaturas) && !Yii::$app->request->isAjax) {
                $tieneAsignaturas = 1;
                $seleccionados = array_keys($_SESSION['seleccion3']['seleccionados']);
                $seleccionados = array_unique(array_merge($seleccionados, $modificarAsignaturas));
                $seleccionados = array_combine($seleccionados, $seleccionados);
                $seleccionados = array_map('strval', $seleccionados);
                $_SESSION['seleccion3']['seleccionados'] = $seleccionados;

                $idsSeleccionadas = $session['seleccion3']['seleccionados'];
                $idsSeleccioandasEnteras = json_decode('[' . (implode(',', $idsSeleccionadas)) . ']', true);
                $querySession = Asignatura::find()->where(['cod_asignatura' => $idsSeleccioandasEnteras]);

            }else{
                $querySession = Asignatura::find()->where(['cod_asignatura' => '00000']);
            }
        }
        $session->close();

        $dataProviderSession = new ActiveDataProvider([
            'query' => $querySession,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);
        $dataProviderSession->pagination->pageParam = 'asignatura-session-page';
        $dataProviderSession->sort->sortParam = 'asignatura-session-sort';


        return $this->render('seleccion3', [
            'tieneAsignaturas' => $tieneAsignaturas,
            'modelRequerimiento' => $requerimiento,
            'searchModelAsignatura' => $searchModelAsignatura,
            'asignaturasSistema' => $provider,
            'dataProviderAsignatura' => $dataProviderAsignatura,
            'dataProviderSession'=> $dataProviderSession,
        ]);
    }//FIN ASIGNACIÓN MATCH1


    public function actionVerificaAsignaturaSemestre(){
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $seleccionAjax = [];
            $seleccionAjaxSistema = [];
            if (isset($data["anio"]) && isset($data["semestre"]) && isset($data["idAsignatura"])) {
                $modeloAsignatura = Asignatura::findOne($data["idAsignatura"]);
                if($modeloAsignatura){
                    if($modeloAsignatura->semestre_dicta == $data["semestre"]){
                        return [
                            'codigo' => 'exito',
                        ];
                    }else{
                        return [
                            'texto' => 'El semestre en el que se dicta la asignatura no corresponde con el semestre planeado para la ejecución.<br> ¿Desea continuar de todas formas?',
                            'codigo' => 'error',
                            'codigoNumero' => 0,
                        ];
                    }
                }else{
                    return [
                        'texto' => 'No se encontró la asignatura.',
                        'codigo' => 'error',
                        'codigoNumero' => 1,
                    ];
                }
            }else{
                return [
                    'texto' => 'Problema en parámetros: año, semestre, asignatura.',
                    'codigo' => 'error',
                    'codigoNumero' => 2,
                ];
            }
        }
    }

    public function actionRecordarSeleccionados($idRequerimiento=null, $anio=null, $semestre=null){
        /* USAR ESTO AL SER UN ARREGLO
         * $captcha = $session['captcha'];
            $captcha['number'] = 5;
            $captcha['lifetime'] = 3600;
            $session['captcha'] = $captcha;
         * */

        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $seleccionAjax = [];
            $seleccionAjaxSistema = [];
            if(isset($data["selectedItems"])){
                $seleccionAjax = $data["selectedItems"];
            }
            if(isset($data["selectedItems2"])){
                $seleccionAjaxSistema = $data["selectedItems2"];
            }
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            $session = Yii::$app->session;
            $session->open();
            //$session->remove('seleccion3');

            if ($session->has('seleccion3')) {

                $seleccionados = $session['seleccion3'];

                $arregloViejo = $seleccionados['seleccionados'];
                $arregloTotal = array_unique(array_merge($arregloViejo, $seleccionAjax, $seleccionAjaxSistema));
                $seleccionados['seleccionados'] = $arregloTotal;
                $session['seleccion3'] = $seleccionados;

                $arrayCombinado = array_combine($session['seleccion3']['seleccionados'], $session['seleccion3']['seleccionados']);

                $seleccionados = $session['seleccion3'];
                $seleccionados['seleccionados'] = $arrayCombinado;

                $session['seleccion3'] = $seleccionados;

            } else {

                $session['seleccion3'] = [
                    'seleccionados' => array_unique(array_merge ($seleccionAjax, $seleccionAjaxSistema)),
                ];

                $arrayCombinado = array_combine($session['seleccion3']['seleccionados'], $session['seleccion3']['seleccionados']);

                $seleccionados = $session['seleccion3'];
                $seleccionados['seleccionados'] = $arrayCombinado;

                $session['seleccion3'] = $seleccionados;

            }
            $session->close();

            //$arrayFlip = array_flip($session['seleccion3']['seleccionados']);

            return [
                'texto' => 'exito',
                'code' => 100,
                'seleccionados' => $session["seleccion3"],
            ];
        }
    }// FIN RECORDAR

    public function actionEliminarSeleccionado(){
        /* USAR ESTO AL SER UN ARREGLO
         * $captcha = $session['captcha'];
            $captcha['number'] = 5;
            $captcha['lifetime'] = 3600;
            $session['captcha'] = $captcha;

        $_SESSION['captcha']['number'] = 5;

         * */

        if (Yii::$app->request->isAjax) {
            $codAsignatura = Yii::$app->request->post('codAsignatura');
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            if($codAsignatura != null) {
                $session = Yii::$app->session;
                $session->open();
                if (isset($session['seleccion3']['seleccionados'])) {
                    $idsSeleccionadas = $session['seleccion3']['seleccionados'];
                    unset($idsSeleccionadas[$codAsignatura]);

                    $_SESSION['seleccion3']['seleccionados'] = $idsSeleccionadas;
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
    }// FIN ELIMINAR

    public function actionObtenerContadorSeleccionados(){
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            $session = Yii::$app->session;
            $session->open();
                if(isset($session['seleccion3']['seleccionados'])){
                    $idsSeleccionadas = $session['seleccion3']['seleccionados'];
                    $idsSeleccioandasEnteras = json_decode('[' . (implode(',', $idsSeleccionadas)) . ']', true);
                    $querySession = Asignatura::find()->where(['cod_asignatura' => $idsSeleccioandasEnteras]);
                }else{
                    $querySession = Asignatura::find()->where(['cod_asignatura' => '00000']);
                }
            $session->close();
            $dataProviderSession = new ActiveDataProvider([
                'query' => $querySession,
                'pagination' => [
                    'pageSize' => 10,
                ],
            ]);
            $dataProviderSession->pagination->pageParam = 'asignatura-session-page';
            $dataProviderSession->sort->sortParam = 'asignatura-session-sort';

            return [
                'contadorSeleccionados' => $dataProviderSession->getTotalCount(),
                'texto' => 'exito',
                'code' => 100,
            ];

        }//ES AJAX
    }// FIN RECORDAR

    //REPORTES
    public function actionReporteResumen()
    {
        $request = Yii::$app->request;

        $searchModel = new Match1Search();
        $dataProvider = $searchModel->searchReporteEstadistica(Yii::$app->request->queryParams);

        $modeloPeriodo = new SeleccionBitacorasReporte();

        return $this->render('reporte-resumen/reporteResumen', [
            'modeloPeriodo' => $modeloPeriodo,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionReporteEstadistica()
    {
        $request = Yii::$app->request;

        $searchModel = new Match1Search();
        $dataProvider = $searchModel->searchReporteEstadistica(Yii::$app->request->queryParams);

        $modeloPeriodo = new SeleccionBitacorasReporte();

        return $this->render('reporte-estadistica/reporteEstadistica', [
            'modeloPeriodo' => $modeloPeriodo,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    //Verifica si el año seleccionado en Match1 (primer paso) es inferior al último registrado en la BD
    public function actionVerificarPeriodo(){
        $request = Yii::$app->request;
        if($request->isAjax){
            $dataAjax = $request->post();
            $anio = $request->post("anio");
            $semestre = $request->post("semestre");

            $modelMax = Match1::find()->orderBy('anio_match1 DESC, semestre_match1 DESC')->one();
            $max = 0; //CASO BASE
            $maxSemestre = 0;
            if($modelMax){
                $max = $modelMax->anio_match1;
                $maxSemestre = $modelMax->semestre_match1;
            }
            $codigo = "exito";
            if($anio < $max ){
                $dataAjax = "El periodo que se ha ingresado (".$anio."-".$semestre.") es menor al último registro que se tiene (".$max."-".$maxSemestre.")";
                $codigo = "error";
            }
            if($anio == $max && $semestre < $maxSemestre){
                $dataAjax = "El periodo que se ha ingresado (".$anio."-".$semestre.") es menor al último registro que se tiene (".$max."-".$maxSemestre.")";
                $codigo = "error";
            }
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return [
                'respuesta'=> $dataAjax,
                'codigo' => $codigo,
            ];
        }//FIN IF AJAX
    }

    public function actionVerificarPeriodoIniciado(){
        $request = Yii::$app->request;
        if($request->isAjax){
            $dataAjax = $request->post();

            $modelMax = Match1::find()->orderBy('anio_match1 DESC, semestre_match1 DESC')->one();
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
                    'respuesta'=> "No se ha encontrado un registro de Match 1",
                    'codigo' => "error",
                ];
            }


        }//FIN IF AJAX
    }

    private function deleteCambioEstadoRequerimiento($id)
    {
        $aEliminar = $this->findModel($id);
        $idReq = $aEliminar->requerimiento_id_requerimiento;
        $anio = $aEliminar->anio_match1;
        $semestre = $aEliminar->semestre_match1;

        $request = Yii::$app->request;
        $this->findModel($id)->delete();

        //Verificar si se eliminaron todos los requerimientos para cambiar el estado
        //ID REQ, ANIO SEMESTE
        $siExiste = Match1::find()->where(['requerimiento_id_requerimiento'=> $idReq,'anio_match1'=> $anio,'semestre_match1'=> $semestre])->all();
        if($siExiste == null){
            $modelReq = Requerimiento::findOne($idReq);
            $modelReq->preseleccionado_match1 = 0;
            $modelReq->save(false);
            return true; //SE CAMBIO ESTADO
        }
        return false; //NO SE CAMBIO ESTADO
    }
}
