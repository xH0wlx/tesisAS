<?php

namespace backend\controllers;

use backend\models\AlumnoInscritoLider;
use backend\models\AlumnoInscritoSeccion;
use backend\models\CambiarCantidadGrupos;
use backend\models\GrupoTrabajo;
use backend\models\Implementacion;
use backend\models\Seccion;
use Yii;
use backend\models\alumnoInscritoHasGrupoTrabajo;
use backend\models\search\AlumnoInscritoHasGrupoTrabajoSearch;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;

/**
 * AlumnoInscritoHasGrupoTrabajoController implements the CRUD actions for alumnoInscritoHasGrupoTrabajo model.
 */
class AlumnoInscritoHasGrupoTrabajoController extends Controller
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

    public function actionCambiarCantidadGrupos(){
        $request = Yii::$app->request;
        $cantidadModel = new CambiarCantidadGrupos();
        if($request->isPost){
            if($cantidadModel->load($request->post())){
                $seccion = Seccion::findOne($cantidadModel->idSeccion);
                if($seccion != null){
                    $cantidadGruposExistentes = intval($seccion->getGrupoTrabajos()->count());
                    $cantidadGruposForm = intval($cantidadModel->cantidad);
                    if($cantidadGruposExistentes > $cantidadGruposForm){
                        $diferencia = $cantidadGruposExistentes - $cantidadGruposForm;
                        for ($i = ($cantidadGruposForm+1); $i <= $cantidadGruposExistentes; $i++){
                        $grupo = GrupoTrabajo::findOne(['seccion_id_seccion' => $seccion->id_seccion, 'numero_grupo_trabajo' => $i]);
                        if($grupo != null && $grupo->bitacoras != null){
                            Yii::$app->mensaje->mensajeGrowl('error', 'No se pueden eliminar grupos que posean bitácoras.', 7000);
                            return $this->redirect(['/implementacion/modificar-grupos-trabajo',
                                'idImplementacion' => $cantidadModel->idImplementacion,
                                'idSeccion' => $cantidadModel->idSeccion]);
                        }
                        $grupo->delete();
                        }
                    }
                    $seccion->cantidad_grupos = $cantidadModel->cantidad;
                    $seccion->save(false);
                    return $this->redirect(['/implementacion/modificar-grupos-trabajo',
                        'idImplementacion' => $cantidadModel->idImplementacion,
                        'idSeccion' => $cantidadModel->idSeccion]);
                }
            }
        }
    }
    /**
     * Lists all alumnoInscritoHasGrupoTrabajo models.
     * @return mixed
     */
    public function actionIndex()
    {    
        $searchModel = new AlumnoInscritoHasGrupoTrabajoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Displays a single alumnoInscritoHasGrupoTrabajo model.
     * @param integer $alumno_inscrito_seccion_id_alumno_inscrito_seccion
     * @param integer $grupo_trabajo_id_grupo_trabajo
     * @return mixed
     */
    public function actionView($alumno_inscrito_seccion_id_alumno_inscrito_seccion, $grupo_trabajo_id_grupo_trabajo)
    {   
        $request = Yii::$app->request;
        if($request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                    'title'=> "alumnoInscritoHasGrupoTrabajo #".$alumno_inscrito_seccion_id_alumno_inscrito_seccion, $grupo_trabajo_id_grupo_trabajo,
                    'content'=>$this->renderAjax('view', [
                        'model' => $this->findModel($alumno_inscrito_seccion_id_alumno_inscrito_seccion, $grupo_trabajo_id_grupo_trabajo),
                    ]),
                    'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                            Html::a('Editar',['update','alumno_inscrito_seccion_id_alumno_inscrito_seccion, $grupo_trabajo_id_grupo_trabajo'=>$alumno_inscrito_seccion_id_alumno_inscrito_seccion, $grupo_trabajo_id_grupo_trabajo],['class'=>'btn btn-primary','role'=>'modal-remote'])
                ];    
        }else{
            return $this->render('view', [
                'model' => $this->findModel($alumno_inscrito_seccion_id_alumno_inscrito_seccion, $grupo_trabajo_id_grupo_trabajo),
            ]);
        }
    }

    /**
     * Creates a new alumnoInscritoHasGrupoTrabajo model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $request = Yii::$app->request;
        $model = new alumnoInscritoHasGrupoTrabajo();  

        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet){
                return [
                    'title'=> "Crear nuevo alumnoInscritoHasGrupoTrabajo",
                    'content'=>$this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Guardar',['class'=>'btn btn-primary','type'=>"submit"])
        
                ];         
            }else if($model->load($request->post()) && $model->save()){
                return [
                    'forceReload'=>'#crud-datatable-pjax',
                    'title'=> "Crear nuevo alumnoInscritoHasGrupoTrabajo",
                    'content'=>'<span class="text-success">Create alumnoInscritoHasGrupoTrabajo success</span>',
                    'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                            Html::a('Crar Más',['create'],['class'=>'btn btn-primary','role'=>'modal-remote'])
        
                ];         
            }else{           
                return [
                    'title'=> "Crear nuevo alumnoInscritoHasGrupoTrabajo",
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
                return $this->redirect(['view', 'alumno_inscrito_seccion_id_alumno_inscrito_seccion' => $model->alumno_inscrito_seccion_id_alumno_inscrito_seccion, 'grupo_trabajo_id_grupo_trabajo' => $model->grupo_trabajo_id_grupo_trabajo]);
            } else {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        }
       
    }

    //NOUSAR
    public function actionCrearMultiple($idImplementacion, $idSeccion){
        $request = Yii::$app->request;
        $model = new alumnoInscritoHasGrupoTrabajo();
        $cantidadModel = new CambiarCantidadGrupos();

        if ($request->isPost) {
            $post = $request->post();
            $grupos = array_intersect_key($post, array_flip(preg_grep('/^grupo-/', array_keys($post))));

            foreach ($grupos as $key => $value){
                //$numero = substr($key, -1);
                $grupoNumero = explode("-", $key);
                $numero = intval($grupoNumero[1]);
                if($value == ""){
                    continue;
                }
                $valores = explode(",", $value);
                $valores = array_map('intval',$valores);

                $grupo = new GrupoTrabajo();
                $grupo->numero_grupo_trabajo = $numero;
                $grupo->seccion_id_seccion = $idSeccion;
                $grupo->save();
                foreach ($valores as $idAlumnoInscrito){
                    $alumnoGrupo = new AlumnoInscritoHasGrupoTrabajo();
                    $alumnoGrupo->alumno_inscrito_seccion_id_alumno_inscrito_seccion = $idAlumnoInscrito;
                    $alumnoGrupo->grupo_trabajo_id_grupo_trabajo = $grupo->id_grupo_trabajo;
                    $alumnoGrupo->save();
                }
            }
            Yii::$app->mensaje->mensajeGrowl('success', 'Grupos guardados exitosamente.');
            return $this->redirect(['/implementacion/panel-implementacion', 'idImplementacion' => Yii::$app->request->get('idImplementacion'), 'idSeccion' => Yii::$app->request->get('idSeccion')]);
        } else {
            $implementacion = Implementacion::find()->joinWith([
                'seccions' => function ($query) use ($idImplementacion,$idSeccion){
                    $query->andWhere(['implementacion_id_implementacion' => $idImplementacion,'id_seccion' => $idSeccion]);
                },
            ])->one();

            if($implementacion == null){
                echo "Esta sección no corresponde con la implementación";
                die;
            }

            $seccion = Seccion::findOne($idSeccion);
            $alumnosInscritos = $seccion->alumnoInscritoSeccionOrdenados;
            $gruposTrabajo = $seccion->grupoTrabajos;

            /*$items = [];
            foreach ($alumnosInscritos as $a) {
                $items[$a->id_alumno_inscrito_seccion] = [
                    'content' => '<i class="glyphicon glyphicon-move"></i> '.$a->alumnoRutAlumno->nombre,
                    //'options' => ['data' => ['id'=>$p->id]],
                ];
            }*/
            $items = [];
            foreach ($alumnosInscritos as $a) {
                $items[$a->id_alumno_inscrito_seccion] = [
                    'content' => '<i class="glyphicon glyphicon-move"></i> '.$a->alumnoRutAlumno->nombre,
                    //'options' => ['data' => ['id'=>$p->id]],
                ];
            }

            $cantidadModel->idImplementacion = $idImplementacion;
            $cantidadModel->idSeccion = $idSeccion;

            return $this->render('createMultiple', [
                'cantidadModel' => $cantidadModel,
                'gruposTrabajo' => $gruposTrabajo,
                'cantidadGrupos' => 10,
                'listaAlumnosInscritos' => $items,
            ]);
        }
    }

    public function actionModificarMultiple($idImplementacion, $idSeccion){
        $request = Yii::$app->request;
        $model = new alumnoInscritoHasGrupoTrabajo();
        $cantidadModel = new CambiarCantidadGrupos();

        if ($request->isPost) {
            $post = $request->post();
            $grupos = array_intersect_key($post, array_flip(preg_grep('/^grupo-/', array_keys($post))));

            foreach ($grupos as $key => $value){
                $grupoNumero = explode("-", $key);
                $numero = intval($grupoNumero[1]);

                if($value == ""){
                    //BORRA EL GRUPO SI NO TIENE ALUMNOS EN EL
                    $grupo = GrupoTrabajo::findOne(['seccion_id_seccion' => $idSeccion, 'numero_grupo_trabajo' => $numero]);
                    if($grupo != null){
                        //TENíA LIDER?
                        $teniaLider = AlumnoInscritoLider::find()
                            ->where(['grupo_trabajo_id_grupo_trabajo' => $grupo->id_grupo_trabajo])->one();

                        if($teniaLider != null){
                            $teniaLider->delete();
                        }
                        $grupo->delete();
                    }
                }else{ //VIENEN VALORES
                    $valores = explode(",", $value);
                    $valores = array_map('intval',$valores);

                    $grupo = GrupoTrabajo::findOne(['seccion_id_seccion' => $idSeccion, 'numero_grupo_trabajo' => $numero]);
                    if($grupo == null){
                        $grupo = new GrupoTrabajo();
                        $grupo->numero_grupo_trabajo = $numero;
                        $grupo->seccion_id_seccion = $idSeccion;
                        $grupo->save();
                    }

                    $idesNuevas = $valores;
                    $idesViejas = AlumnoInscritoHasGrupoTrabajo::find()
                        ->select('alumno_inscrito_seccion_id_alumno_inscrito_seccion')
                        ->where(['grupo_trabajo_id_grupo_trabajo' => $grupo->id_grupo_trabajo])->asArray()->all();

                    $idesViejas = ArrayHelper::map($idesViejas, "alumno_inscrito_seccion_id_alumno_inscrito_seccion", "alumno_inscrito_seccion_id_alumno_inscrito_seccion");
                    $idesViejas = array_values($idesViejas);
                    $idesViejas = array_map('intval',$idesViejas);

                    $diferenciasAlRemover = array_diff($idesViejas, $idesNuevas);
                    $diferenciasAlAgregar = array_diff($idesNuevas, $idesViejas);


                    if($diferenciasAlRemover != null){
                        foreach ($diferenciasAlRemover as $idAEliminar){
                            $aEliminar = AlumnoInscritoHasGrupoTrabajo::find()
                                ->where([
                                    'alumno_inscrito_seccion_id_alumno_inscrito_seccion' => $idAEliminar,
                                    'grupo_trabajo_id_grupo_trabajo' => $grupo->id_grupo_trabajo
                                ])->one();
                            if($aEliminar != null){
                                $aEliminar->delete();
                                //CÓDIGO PARA ELIMINAR El GRUPO EN CASO DE QUE QUEDE VACÍO
                            }

                            //ES LIDER?
                            $esLider = AlumnoInscritoLider::find()->where([
                                'alumno_inscrito_seccion_id_seccion_alumno' => $idAEliminar,
                                'grupo_trabajo_id_grupo_trabajo' => $grupo->id_grupo_trabajo
                            ])->one();
                            if($esLider != null){
                                $esLider->delete();
                            }
                        }
                    }//FIN DIFERENCIAS AL REMOVER

                    if($diferenciasAlAgregar != null){
                        $seccion = Seccion::findOne($idSeccion);
                        $gruposTrabajo = $seccion->grupoTrabajos;
                        $idesAlumnos = [];
                        foreach ($gruposTrabajo as $grupoTrabajo){
                            $alumnosGrupo = $grupoTrabajo->alumnoInscritoHasGrupoTrabajos;
                            foreach ($alumnosGrupo as $alumnoGrupo){
                                array_push($idesAlumnos, $alumnoGrupo->alumno_inscrito_seccion_id_alumno_inscrito_seccion);
                            }
                        }

                        foreach ($diferenciasAlAgregar as $idAInsertar){
                            $estaEnUnGrupo = in_array($idAInsertar, $idesAlumnos);

                            if($estaEnUnGrupo){
                                $aEliminar = AlumnoInscritoHasGrupoTrabajo::find()
                                    ->where([
                                        'alumno_inscrito_seccion_id_alumno_inscrito_seccion' => $idAInsertar,
                                        'grupo_trabajo_id_grupo_trabajo' => $grupo->id_grupo_trabajo
                                    ])->one();

                                if($aEliminar != null){
                                    $aEliminar->delete();

                                }

                                //ES LIDER?
                                $esLider = AlumnoInscritoLider::find()->where([
                                    'alumno_inscrito_seccion_id_seccion_alumno' => $idAInsertar,
                                    'grupo_trabajo_id_grupo_trabajo' => $grupo->id_grupo_trabajo
                                ])->one();
                                if($esLider != null){
                                    $esLider->delete();
                                }

                                $alumnoGrupo = new AlumnoInscritoHasGrupoTrabajo();
                                $alumnoGrupo->alumno_inscrito_seccion_id_alumno_inscrito_seccion = $idAInsertar;
                                $alumnoGrupo->grupo_trabajo_id_grupo_trabajo = $grupo->id_grupo_trabajo;
                                $alumnoGrupo->save();
                            }else{
                                $alumnoGrupo = new AlumnoInscritoHasGrupoTrabajo();
                                $alumnoGrupo->alumno_inscrito_seccion_id_alumno_inscrito_seccion = $idAInsertar;
                                $alumnoGrupo->grupo_trabajo_id_grupo_trabajo = $grupo->id_grupo_trabajo;
                                $alumnoGrupo->save();
                            }

                        }
                    }//FIN DIFERENCIAS AL AGREGAR
                    if($grupo->getAlumnoInscritoHasGrupoTrabajos()->count() == 0){
                        $grupo->delete();
                    }
                }//FIN ELSE (SI TIENE VALORES [ALUMNOS] EL GRUPO
            }//FIN FOR

            Yii::$app->mensaje->mensajeGrowl('success', 'Grupos guardados exitosamente.');

            return $this->redirect(['/implementacion/panel-implementacion', 'idImplementacion' => Yii::$app->request->get('idImplementacion'), 'idSeccion' => Yii::$app->request->get('idSeccion')]);
        //FIN POST
        } else {
            $implementacion = Implementacion::find()->joinWith([
                'seccions' => function ($query) use ($idImplementacion,$idSeccion){
                    $query->andWhere(['implementacion_id_implementacion' => $idImplementacion,'id_seccion' => $idSeccion]);
                },
            ])->one();

            if($implementacion == null){
                echo "Esta sección no corresponde con la implementación";
                die;
            }

            $seccion = Seccion::findOne($idSeccion);
            //$alumnosInscritos = $seccion->alumnoInscritoSeccions; SIN USO


            $gruposTrabajo = $seccion->grupoTrabajos;
            //default value 10
            $cantidadGrupos = $seccion->cantidad_grupos;
            $ides = [];
            foreach ($gruposTrabajo as $grupoTrabajo){
                $alumnosGrupo = $grupoTrabajo->alumnoInscritoSeccionIdAlumnoInscritoSeccions;
                foreach ($alumnosGrupo as $alumnoGrupo){
                    array_push($ides, $alumnoGrupo->id_alumno_inscrito_seccion);
                }
            }

            $alumnosInscritos = AlumnoInscritoSeccion::find()
                ->joinWith('alumnoRutAlumno')
                ->where(['not in','id_alumno_inscrito_seccion',$ides])
                ->andWhere(['seccion_id_seccion' => $idSeccion])->orderBy(['alumno.nombre' => SORT_ASC])->all();

            $items = [];
            foreach ($alumnosInscritos as $a) {
                $items[$a->id_alumno_inscrito_seccion] = [
                    'content' => '<i class="glyphicon glyphicon-move"></i> '.$a->alumnoRutAlumno->nombre,
                    //'options' => ['data' => ['id'=>$p->id]],
                ];
            }

            $cantidadModel->idImplementacion = $idImplementacion;
            $cantidadModel->idSeccion = $idSeccion;

            return $this->render('createMultiple', [
                'cantidadModel' => $cantidadModel,
                'cantidadGrupos' => $cantidadGrupos,
                'listaAlumnosInscritos' => $items,
                'gruposTrabajo' => $gruposTrabajo,
            ]);
        }
    }

    /**
     * Updates an existing alumnoInscritoHasGrupoTrabajo model.
     * For ajax request will return json object
     * and for non-ajax request if update is successful, the browser will be redirected to the 'view' page.
     * @param integer $alumno_inscrito_seccion_id_alumno_inscrito_seccion
     * @param integer $grupo_trabajo_id_grupo_trabajo
     * @return mixed
     */
    public function actionUpdate($alumno_inscrito_seccion_id_alumno_inscrito_seccion, $grupo_trabajo_id_grupo_trabajo)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($alumno_inscrito_seccion_id_alumno_inscrito_seccion, $grupo_trabajo_id_grupo_trabajo);       

        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet){
                return [
                    'title'=> "Modificar alumnoInscritoHasGrupoTrabajo #".$alumno_inscrito_seccion_id_alumno_inscrito_seccion, $grupo_trabajo_id_grupo_trabajo,
                    'content'=>$this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Guardar',['class'=>'btn btn-primary','type'=>"submit"])
                ];         
            }else if($model->load($request->post()) && $model->save()){
                return [
                    'forceReload'=>'#crud-datatable-pjax',
                    'title'=> "alumnoInscritoHasGrupoTrabajo #".$alumno_inscrito_seccion_id_alumno_inscrito_seccion, $grupo_trabajo_id_grupo_trabajo,
                    'content'=>$this->renderAjax('view', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                            Html::a('Editar',['update','alumno_inscrito_seccion_id_alumno_inscrito_seccion, $grupo_trabajo_id_grupo_trabajo'=>$alumno_inscrito_seccion_id_alumno_inscrito_seccion, $grupo_trabajo_id_grupo_trabajo],['class'=>'btn btn-primary','role'=>'modal-remote'])
                ];    
            }else{
                 return [
                    'title'=> "Modificar alumnoInscritoHasGrupoTrabajo #".$alumno_inscrito_seccion_id_alumno_inscrito_seccion, $grupo_trabajo_id_grupo_trabajo,
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
                return $this->redirect(['view', 'alumno_inscrito_seccion_id_alumno_inscrito_seccion' => $model->alumno_inscrito_seccion_id_alumno_inscrito_seccion, 'grupo_trabajo_id_grupo_trabajo' => $model->grupo_trabajo_id_grupo_trabajo]);
            } else {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        }
    }

    /**
     * Delete an existing alumnoInscritoHasGrupoTrabajo model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $alumno_inscrito_seccion_id_alumno_inscrito_seccion
     * @param integer $grupo_trabajo_id_grupo_trabajo
     * @return mixed
     */
    public function actionDelete($alumno_inscrito_seccion_id_alumno_inscrito_seccion, $grupo_trabajo_id_grupo_trabajo)
    {
        $request = Yii::$app->request;
        $this->findModel($alumno_inscrito_seccion_id_alumno_inscrito_seccion, $grupo_trabajo_id_grupo_trabajo)->delete();

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
     * Delete multiple existing alumnoInscritoHasGrupoTrabajo model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $alumno_inscrito_seccion_id_alumno_inscrito_seccion
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
     * Finds the alumnoInscritoHasGrupoTrabajo model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $alumno_inscrito_seccion_id_alumno_inscrito_seccion
     * @param integer $grupo_trabajo_id_grupo_trabajo
     * @return alumnoInscritoHasGrupoTrabajo the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($alumno_inscrito_seccion_id_alumno_inscrito_seccion, $grupo_trabajo_id_grupo_trabajo)
    {
        if (($model = alumnoInscritoHasGrupoTrabajo::findOne(['alumno_inscrito_seccion_id_alumno_inscrito_seccion' => $alumno_inscrito_seccion_id_alumno_inscrito_seccion, 'grupo_trabajo_id_grupo_trabajo' => $grupo_trabajo_id_grupo_trabajo])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('La página solicitada no existe.');
        }
    }
}
