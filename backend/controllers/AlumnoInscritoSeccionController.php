<?php

namespace backend\controllers;

use backend\models\Alumno;
use backend\models\AlumnoInscritoHasGrupoTrabajo;
use backend\models\ArchivoExcel;
use backend\models\GrupoTrabajo;
use backend\models\TipoUsuario;
use backend\models\User;
use Yii;
use backend\models\alumnoInscritoSeccion;
use backend\models\search\AlumnoInscritoSeccionSearch;
use yii\base\Exception;
use yii\data\ArrayDataProvider;
use yii\db\IntegrityException;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use yii\web\UploadedFile;

/**
 * AlumnoInscritoSeccionController implements the CRUD actions for alumnoInscritoSeccion model.
 */
class AlumnoInscritoSeccionController extends Controller
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
     * Lists all alumnoInscritoSeccion models.
     * @return mixed
     */
    public function actionIndex()
    {    
        $searchModel = new AlumnoInscritoSeccionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionIndexSeccion($idImplementacion, $idSeccion)
    {
        $searchModel = new AlumnoInscritoSeccionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->andFilterWhere(['seccion_id_seccion'=>$idSeccion]);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Displays a single alumnoInscritoSeccion model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {   
        $request = Yii::$app->request;
        if($request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                    'title'=> "alumnoInscritoSeccion #".$id,
                    'content'=>$this->renderAjax('view', [
                        'model' => $this->findModel($id),
                    ]),
                    'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                            Html::a('Editar',['update','id'=>$id],['class'=>'btn btn-primary','role'=>'modal-remote'])
                ];    
        }else{
            return $this->render('view', [
                'model' => $this->findModel($id),
            ]);
        }
    }

    /**
     * Creates a new alumnoInscritoSeccion model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($idImplementacion, $idSeccion)
    {
        $request = Yii::$app->request;
        $model = new alumnoInscritoSeccion();  

        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet){
                return [
                    'title'=> "Inscribir Alumno",
                    'content'=>$this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Guardar',['class'=>'btn btn-primary','type'=>"submit"])
        
                ];         
            }else if($model->load($request->post())){
                $idAlumno = $model->alumno_rut_alumno;
                $existe = AlumnoInscritoSeccion::find()->where(['seccion_id_seccion' => $idSeccion, 'alumno_rut_alumno'=> $idAlumno])->one();
                if($existe != null){
                    return [
                        'forceReload'=>'#crud-datatable-pjax',
                        'title'=> "Error en la inscripción del Alumno",
                        'content'=>'<span class="text-danger">Es posible que el alumno ya se encuentre inscrito en esta sección.</span>',
                        'footer'=> Html::button('Cerrar',['class'=>'btn btn-default ','data-dismiss'=>"modal"])

                    ];
                }

                $model->seccion_id_seccion = intval($idSeccion);
                if($model->save()){
                    return [
                        'forceReload'=>'#crud-datatable-pjax',
                        'title'=> "Estado Alumno",
                        'content'=>'<span class="text-success">Alumno inscrito exitosamente.</span>',
                        'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                            Html::a('Inscribir otro alumno',['create', 'idImplementacion' => $idImplementacion, 'idSeccion' => $idSeccion],['class'=>'btn btn-primary','role'=>'modal-remote'])

                    ];
                }else{
                    return [
                        'forceReload'=>'#crud-datatable-pjax',
                        'title'=> "Error en la inscripción del Alumno",
                        'content'=>'<span class="text-danger">Es posible que el alumno ya se encuentre inscrito en esta sección.</span>',
                        'footer'=> Html::button('Cerrar',['class'=>'btn btn-default ','data-dismiss'=>"modal"])

                    ];
                }



            }else{           
                return [
                    'title'=> "Crear nuevo alumnoInscritoSeccion",
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
            if ($model->load($request->post())) {
                $model->seccion_id_seccion = $idSeccion;
                $model->save();
                return $this->redirect(['view', 'id' => $model->id_alumno_inscrito_seccion]);
            } else {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        }
       
    }

    public function actionDescargaPlantillaInscripcion(){
        $ruta = Yii::$app->basePath . '/web/uploads/plantillaInscripcion/Plantilla-Inscripcion.xlsx';
        if (file_exists($ruta)) {
            return Yii::$app->response->sendFile($ruta);
        }else{
            throw new NotFoundHttpException('Problemas con el archivo solicitado.');
        }
    }

    public function actionCreateExcel($idImplementacion, $idSeccion){
        $model = new alumnoInscritoSeccion();
        $request = Yii::$app->request;

        if($request->isPost){
            set_time_limit (60);
            $modeloArchivoExcel = new ArchivoExcel();
            $modeloArchivoExcel->load($request->post());
            $archivoExcel = UploadedFile::getInstance($modeloArchivoExcel, 'archivoExcel');
            if (!is_null($archivoExcel)) {
                $archivo_src_filename = $archivoExcel->name;
                $ext = end(explode(".", $archivoExcel->name));
                $archivo_web_filename = "prueba.xls";
                Yii::$app->params['uploadPath'] = Yii::$app->basePath . '/web/uploads/temporalExcel/';
                $path = Yii::$app->params['uploadPath'] . $archivo_web_filename;
                $archivoExcel->saveAs($path);
            }

            $inputFile = "uploads/temporalExcel/prueba.xls";

            try{
                $inputFileType = \PHPExcel_IOFactory::identify($inputFile);
                $objectReader = \PHPExcel_IOFactory::createReader($inputFileType);
                $objectPhpExcel = $objectReader->load($inputFile);
            }catch (Exception $e){
                die("Error");
            }

            $sheet = $objectPhpExcel->getSheet(0);
            $highestRow = $sheet->getHighestRow();
            $highestColumn = $sheet->getHighestColumn();

            $resultado=[];
            $resultado2=[];

            for ($row = 1; $row <= $highestRow; $row++){
                $rowData = $sheet->rangeToArray('A'.$row.':'.$highestColumn.$row, NULL, TRUE, FALSE);
                //$rowData = $sheet->rangeToArray('A'.$row.':'.'G'.$row, NULL, TRUE, FALSE);
                if($row == 1){
                    $fila = [];
                    $fila["rut_alumno"] =$rowData[0][1];
                    $fila["nombre_alumno"] =$rowData[0][2];
                    $fila["mail_alumno"] =$rowData[0][6];
                    //array_push($resultado, $fila);
                    continue;
                }
                $fila = [];
                $fila["rut"] = str_replace("-", "", $rowData[0][1]);
                $fila["nombre"] =trim(preg_replace('/\s\s+/', ' ', str_replace("\n", " ", $rowData[0][2])));
                $fila["mail"] =$rowData[0][6];
                array_push($resultado, $fila);

                try {
                    $boolAlumno = Alumno::find()->where(['rut_alumno' => $fila["rut"]])->exists();
                    if(!$boolAlumno){
                        $modelAlumno = new Alumno();
                        $modelAlumno->rut_alumno = $fila["rut"];
                        $modelAlumno->nombre = $fila["nombre"];
                        $modelAlumno->email = $fila["mail"];
                        if($modelAlumno->save()){
                            $alumnoInscrito = new AlumnoInscritoSeccion();
                            $alumnoInscrito->alumno_rut_alumno = $modelAlumno->rut_alumno;
                            $alumnoInscrito->seccion_id_seccion = $request->get('idSeccion');
                            if($alumnoInscrito->save()){
                                $cuenta = new User();
                                $cuenta->username = $modelAlumno->rut_alumno;
                                $cuenta->nombre_completo = $modelAlumno->nombre;
                                $cuenta->email = $modelAlumno->email;
                                $clave = intval(substr($modelAlumno->rut_alumno, 0, 6));
                                $cuenta->password_hash = Yii::$app->security->generatePasswordHash($clave,7);//DEBIDO A QUE ES CONOCIDA POR TODOS
                                if($cuenta->save(false)) {//Se salta la validación por el hecho de que no pasa la regla de repetir contraseña
                                    Yii::$app->funcionespropias->asignarRol("alumno", $cuenta->id);
                                }
                            }
                        }
                    //CASO EN QUE EL ALUMNO YA ESTA INGRESADO EN EL SISTEMA
                    }else{
                        $modelAlumno = Alumno::find()->where(['rut_alumno' => $fila["rut"]])->one();
                        $boolAlumnoInscrito = AlumnoInscritoSeccion::find()->where(['alumno_rut_alumno'=> $modelAlumno->rut_alumno, 'seccion_id_seccion'=>$request->get('idSeccion')])->exists();
                        if(!$boolAlumnoInscrito){
                            $alumnoInscrito = new AlumnoInscritoSeccion();
                            $alumnoInscrito->alumno_rut_alumno = $modelAlumno->rut_alumno;
                            $alumnoInscrito->seccion_id_seccion = $request->get('idSeccion');
                            if($alumnoInscrito->save()){
                                $boolCuentaAlumno = User::find()->where(['username'=> $modelAlumno->rut_alumno])->exists();
                                if(!$boolCuentaAlumno){
                                    $cuenta = new User();
                                    $cuenta->username = $modelAlumno->rut_alumno;
                                    $cuenta->nombre_completo = $modelAlumno->nombre;
                                    $cuenta->email = $modelAlumno->email;
                                    $clave = intval(substr($modelAlumno->rut_alumno, 0, 6));
                                    $cuenta->password_hash = Yii::$app->security->generatePasswordHash($clave,7); //DEBIDO A QUE ES CONOCIDA POR TODOS
                                    if($cuenta->save(false)) {//Se salta la validación por el hecho de que no pasa la regla de repetir contraseña
                                        Yii::$app->funcionespropias->asignarRol("alumno", $cuenta->id);
                                    }
                                }else{
                                    echo "La cuenta ya existe.";
                                }
                            }else{
                                echo "Error en la inscripción";
                            }
                        }
                    }
                } catch (IntegrityException $e) {
                    //$transaction->rollBack();
                    echo "Violación de integridad.";
                }

                //PARA HACER BATCH INSERT
                $aux = $rowData[0][1];
                $sinGuion = str_replace("-", "", $aux);
                $resultado2[]= [$sinGuion,$rowData[0][2],$rowData[0][6]];
            }//FIN FOR EXCEL



            //Yii::$app->db->createCommand()->batchInsert('alumno', ['rut_alumno', 'nombre', 'email'], $resultado2)->execute();

            //die("okay");
            $modeloArchivoExcel = new ArchivoExcel();

            $dataProvider = new ArrayDataProvider([
                'key'=>'rut',
                'allModels' => $resultado,
                'pagination' => [
                    'pageSize' => count($resultado)
                ],
                /*'sort' => [
                    'attributes' => ['rut', 'nombre', 'email'],
                ],*/
            ]);
            Yii::$app->mensaje->mensajeGrowl('success', 'Alumnos Inscritos Exitosamente. Cuentas de alumnos creadas exitosamente.');
            //return $this->redirect(['/implementacion/panel-implementacion', 'idImplementacion' => Yii::$app->request->get('idImplementacion')]);
            return $this->redirect(['/implementacion/modificar-inscripcion', 'idImplementacion'=> $request->get('idImplementacion'), 'idSeccion'=> $request->get('idSeccion')]);

            return $this->render('createExcel', [
                'modelArchivoExcel' => $modeloArchivoExcel,
                'datosPost' => $dataProvider,
            ]);
            //return $this->redirect(['/implementacion/panel-implementacion', 'idImplementacion' => Yii::$app->request->get('idImplementacion')]);
        //FIN DEL IF DEL POST
        }else if($request->isAjax){
            $modeloArchivoExcel = new ArchivoExcel();
            $modeloArchivoExcel->load($request->post());
            $archivoExcel = UploadedFile::getInstance($modeloArchivoExcel, 'archivoExcel');
            if (!is_null($archivoExcel)) {
                $archivo_src_filename = $archivoExcel->name;
                $ext = end(explode(".", $archivoExcel->name));
                $archivo_web_filename = "prueba.xls";
                Yii::$app->params['uploadPath'] = Yii::$app->basePath . '/web/uploads/temporalExcel/';
                $path = Yii::$app->params['uploadPath'] . $archivo_web_filename;
                $archivoExcel->saveAs($path);
            }

            $inputFile = "uploads/temporalExcel/prueba.xls";

            try{
                $inputFileType = \PHPExcel_IOFactory::identify($inputFile);
                $objectReader = \PHPExcel_IOFactory::createReader($inputFileType);
                $objectPhpExcel = $objectReader->load($inputFile);
            }catch (Exception $e){
                die("Error");
            }

            $sheet = $objectPhpExcel->getSheet(0);
            $highestRow = $sheet->getHighestRow();
            $highestColumn = $sheet->getHighestColumn();

            $resultado=[];

            for ($row = 0; $row <= $highestRow; $row++){
                $rowData = $sheet->rangeToArray('A'.$row.':'.$highestColumn.$row, NULL, TRUE, FALSE);
                //$rowData = $sheet->rangeToArray('A'.$row.':'.'G'.$row, NULL, TRUE, FALSE);
                if($row == 0){
                    $fila = [];
                    $fila["rut_alumno"] =$rowData[0][1];
                    $fila["nombre_alumno"] =$rowData[0][2];
                    $fila["mail_alumno"] =$rowData[0][6];
                    //array_push($resultado, $fila);
                    continue;
                }
                $fila = [];
                $fila["rut"] =$rowData[0][1];
                $fila["nombre"] =$rowData[0][2];
                $fila["mail"] =$rowData[0][6];
                array_push($resultado, $fila);

            }

             $dataProvider = new ArrayDataProvider([
                'key'=>'rut',
                'allModels' => $resultado,
                'pagination' => [
                    'pageSize' => count($resultado)
                ],
            ]);

            return $this->renderPartial('_modalConfirmacion', [
                'dataprovider' => $dataProvider,
            ]);
        }else{
            $modeloArchivoExcel = new ArchivoExcel();
            return $this->render('createExcel', [
                'datosPost' => $request->post(),
                'modelArchivoExcel' => $modeloArchivoExcel,
            ]);
        }
    }

    /**
     * Updates an existing alumnoInscritoSeccion model.
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
                    'title'=> "Modificar alumnoInscritoSeccion #".$id,
                    'content'=>$this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Guardar',['class'=>'btn btn-primary','type'=>"submit"])
                ];         
            }else if($model->load($request->post()) && $model->save()){
                return [
                    'forceReload'=>'#crud-datatable-pjax',
                    'title'=> "alumnoInscritoSeccion #".$id,
                    'content'=>$this->renderAjax('view', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                            Html::a('Editar',['update','id'=>$id],['class'=>'btn btn-primary','role'=>'modal-remote'])
                ];    
            }else{
                 return [
                    'title'=> "Modificar alumnoInscritoSeccion #".$id,
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
                return $this->redirect(['view', 'id' => $model->id_alumno_inscrito_seccion]);
            } else {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        }
    }

    /**
     * Delete an existing alumnoInscritoSeccion model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $request = Yii::$app->request;
        $this->findModel($id)->delete();

        $modeloAlumnoGrupo = AlumnoInscritoHasGrupoTrabajo::find()->where(['alumno_inscrito_seccion_id_alumno_inscrito_seccion'=> $id])->one();
        if($modeloAlumnoGrupo != null){
            $idGrupo = $modeloAlumnoGrupo->grupo_trabajo_id_grupo_trabajo;
            $modeloGrupo = GrupoTrabajo::findOne($idGrupo);
            if($modeloGrupo != null){
                if($modeloGrupo->getAlumnoInscritoHasGrupoTrabajos()->count() == 0){
                    $modeloGrupo->delete();
                }
            }
        }//FIN IF

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
     * Delete multiple existing alumnoInscritoSeccion model.
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

            $modeloAlumnoGrupo = AlumnoInscritoHasGrupoTrabajo::find()->where(['alumno_inscrito_seccion_id_alumno_inscrito_seccion'=> $pk])->one();
            if($modeloAlumnoGrupo != null){
                $idGrupo = $modeloAlumnoGrupo->grupo_trabajo_id_grupo_trabajo;
                $modeloGrupo = GrupoTrabajo::findOne($idGrupo);
                if($modeloGrupo != null){
                    if($modeloGrupo->getAlumnoInscritoHasGrupoTrabajos()->count() == 0){
                        $modeloGrupo->delete();
                    }
                }
            }//FIN IF
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
     * Finds the alumnoInscritoSeccion model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return alumnoInscritoSeccion the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = alumnoInscritoSeccion::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('La página solicitada no existe.');
        }
    }
}
