<?php

namespace backend\controllers;

use backend\models\Alumno;
use backend\models\AlumnoInscritoHasGrupoTrabajo;
use backend\models\AlumnoInscritoSeccion;
use backend\models\ArchivoExcel;
use backend\models\Asignatura;
use backend\models\EstadoImplementacion;
use backend\models\Requerimiento;
use backend\models\search\AlumnoInscritoSeccionSearch;
use backend\models\Seccion;
use backend\models\Model;

use backend\models\SeleccionBitacorasReporte;
use console\controllers\CronController;
use Yii;
use backend\models\implementacion;
use backend\models\match1;
use backend\models\AnioSemestre;
use backend\models\search\ImplementacionSearch;
use yii\base\Exception;
use yii\filters\AccessControl;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;
use \yii\web\Response;
use yii\helpers\Html;
use yii\web\UploadedFile;
use yii\data\ArrayDataProvider;
use yii\db\IntegrityException;

/**
 * ImplementacionController implements the CRUD actions for implementacion model.
 */
class ImplementacionController extends Controller
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

    public function actionSeleccionAsignatura($anio = null, $semestre= null)
    {
        $request = Yii::$app->request;
        if($request->isGet && ($anio != null) && ($semestre != null)){
            $modeloPeriodo = new AnioSemestre();

            $modeloPeriodo->anio = $anio;
            $modeloPeriodo->semestre = $semestre;

            //LAS CONSULTAS DE ARRIBA SIGUEN EL REGIMEN ANTIGUO
            $query = Match1::find()->select("GROUP_CONCAT(id_match1) idesAgrupadas, match1.*")
                ->where(['anio_match1'=> $modeloPeriodo->anio])
                ->andWhere(['semestre_match1'=>$modeloPeriodo->semestre])
                ->andWhere(['implementacion_id_implementacion' => NULL])
                ->andWhere(['not', ['servicio_id_servicio' => NULL]])
                ->groupBy(['servicio_id_servicio']);

            $cantidad = $query->count();

            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'key' => 'idesAgrupadas',
                'pagination' => [
                    'pageSize' => $cantidad,
                ],
            ]);

            return $this->render('//implementacion/seleccion-periodo/seleccionPeriodoPOST', [
                'dataProvider' => $dataProvider,
                'modeloPeriodo' => $modeloPeriodo,
            ]);
        }else

        if($request->isPost || $request->isAjax){
            $modeloPeriodo = new AnioSemestre();

            $modeloPeriodo->load($request->post());

            //LAS CONSULTAS DE ARRIBA SIGUEN EL REGIMEN ANTIGUO
            $query = Match1::find()->select("GROUP_CONCAT(id_match1) idesAgrupadas, match1.*")
                ->where(['anio_match1'=> $modeloPeriodo->anio])
                ->andWhere(['semestre_match1'=>$modeloPeriodo->semestre])
                ->andWhere(['implementacion_id_implementacion' => NULL])
                ->andWhere(['not', ['servicio_id_servicio' => NULL]])
                ->groupBy(['servicio_id_servicio']);

            $cantidad = $query->count();

            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'key' => 'idesAgrupadas',
                'pagination' => [
                    'pageSize' => $cantidad,
                ],
            ]);

            return $this->render('//implementacion/seleccion-periodo/seleccionPeriodoPOST', [
                'dataProvider' => $dataProvider,
                'modeloPeriodo' => $modeloPeriodo,
            ]);
        }else{
            $modeloPeriodo = new AnioSemestre();

            return $this->render('//implementacion/seleccion-periodo/seleccionPeriodo', [
                'modeloPeriodo' => $modeloPeriodo,
            ]);
        }

    }

    public function actionDetalleSeleccionAsignatura(){
        $request = Yii::$app->request;
        if($request->isPost){
            $data = Yii::$app->request->post();
            if(isset($data["kvradio"])) {
                $idesAgrupadas = $data["kvradio"];
                $idesAgrupadasArreglo = array_map('intval', explode(',', $idesAgrupadas));

                $modelosMatch = Match1::find()->where(["id_match1" => $idesAgrupadasArreglo])->all();

                return $this->render('//implementacion/seleccion-periodo/detalleSeleccionAsignatura', [
                    'modelosMatch' => $modelosMatch,
                    'idesAgrupadas' => $data["kvradio"],
                ]);
            }else if (isset($data["idesAgrupadas"])){
                //CREAMOS LA IMPLEMENTACIÓN Y CONTINUAMOS AL PANEL DE IMPLEMENTACION
                $idesAgrupadas = $data["idesAgrupadas"];
                $idesAgrupadasArreglo = array_map('intval', explode(',', $idesAgrupadas));
                $match0 = Match1::findOne($idesAgrupadasArreglo[0]);
                if($match0 != null){
                    $modeloImplementacion = new Implementacion();
                    $modeloImplementacion->asignatura_cod_asignatura = $match0->asignatura_cod_asignatura;
                    $modeloImplementacion->anio_implementacion = $match0->anio_match1;
                    $modeloImplementacion->semestre_implementacion = $match0->semestre_match1;
                    //!!!!!!!!!!REVISARESTADO DE LA IMPLEMENTACIÓN NO FUNCIONANDO
                    $transaction = \Yii::$app->db->beginTransaction();
                    if($flag = $modeloImplementacion->save(false)){
                        foreach ($idesAgrupadasArreglo as $idMatch){
                            $match = Match1::findOne($idMatch);
                            if($match != null){
                                //SE LE ASIGNA LA ID DE LA IMPLEMENTACIÓN A LAS FILAS MATCH
                                $match->implementacion_id_implementacion = $modeloImplementacion->id_implementacion;

                                //SE CAMBIA EL ESTADO DEL REQUERIMIENTO y SERVICIO INVOLUCRADO
                                Yii::$app->estados->actualizarEstadoRequerimiento($match->requerimiento_id_requerimiento,
                                    "En Desarrollo");
                                Yii::$app->estados->actualizarEstadoServicio($match->servicio_id_servicio,
                                    "En Desarrollo");

                                if(!($flag = $match->save(false))){
                                    $flag = false;
                                    break;
                                }
                            }else{
                                $flag = false;
                                break;
                            }
                        }
                        if($flag){
                            $transaction->commit();
                            Yii::$app->getSession()->setFlash('success', [
                                'type' => 'success',
                                'duration' => 5000,
                                //'icon' => 'fa fa-users',
                                'message' => 'Implementación creada exitosamente.',
                                'title' => 'Información',
                                'positonY' => 'top',
                                //'positonX' => 'left'
                            ]);
                            return $this->redirect(['implementacion/panel-implementacion', 'idImplementacion' => $modeloImplementacion->id_implementacion]);

                        }else{
                            $transaction->rollBack();
                            Yii::$app->getSession()->setFlash('success', [
                                'type' => 'error',
                                'duration' => 5000,
                                //'icon' => 'fa fa-users',
                                'message' => 'Implementación NO creada.',
                                'title' => 'Error',
                                'positonY' => 'top',
                                //'positonX' => 'left'
                            ]);
                            $modelosMatch = Match1::find()->where(["id_match1" => $idesAgrupadasArreglo])->all();

                            return $this->render('//implementacion/seleccion-periodo/detalleSeleccionAsignatura', [
                                'modelosMatch' => $modelosMatch,
                                'idesAgrupadas' => $data["idesAgrupadas"],
                            ]);
                        }

                    }else{
                        $transaction->rollBack();

                        $modelosMatch = Match1::find()->where(["id_match1" => $idesAgrupadasArreglo])->all();

                        return $this->render('//implementacion/seleccion-periodo/detalleSeleccionAsignatura', [
                            'modelosMatch' => $modelosMatch,
                            'idesAgrupadas' => $data["idesAgrupadas"],
                        ]);
                    }
                }else{
                    echo "Error en la segunda llamada de Selección de Asignatura";
                    die;
                }
            }//FIN SEGUNDA LLAMADA
        }//FIN IS POST
    }

    public function actionErrorImplementacion()
    {
        return $this->render('errorImplementacion', [

            'msg' => "Ha ocurrido un error",
        ]);
    }

    public function actionRemover()
    {
        $modeloGrupo = new AlumnoInscritoHasGrupoTrabajo();
        $modeloAlumno = new AlumnoInscritoSeccion();
        return $this->render('pasoCinco', [

            'idImplementacion' => 1,
            'idSeccion' =>1,
            'model' => $modeloGrupo,
            'modeloAlumno' => $modeloAlumno,
        ]);
    }

    public function actionPasoUnox($idAsignatura=null)
    {
        $request = Yii::$app->request;

        if($request->isPost){
            $modeloImplementacion = new Implementacion();
            $modeloImplementacion->load($request->post());

            if($modeloImplementacion->save()){
                $modeloImplementacion->match1Requerimientos;
                foreach ($modeloImplementacion as $requerimiento){
                    $requerimientoFind = Requerimiento::findOne($requerimiento->requerimiento_id_requerimiento);
                    $requerimientoFind->estado_ejecucion_id_estado = 2;
                    $requerimientoFind->save(false);
                }

                return $this->redirect(['implementacion/panel-implementacion', 'idImplementacion' => $modeloImplementacion->id_implementacion]);
            }else{
                $modeloAsignatura = new Asignatura();
                $asignatura = Asignatura::findOne($idAsignatura);
                return $this->render('pasoUno', [
                    'model' => $modeloImplementacion,
                    'asignatura' => $asignatura,
                ]);
            }

        }else{
            $modeloAsignatura = new Asignatura();
            $asignatura = Asignatura::findOne($idAsignatura);
            $asignaturaMatch1 = Match1::find()->where(['asignatura_cod_asignatura'=> $idAsignatura])->one();
            $modeloImplementacion = new Implementacion();
            $modeloImplementacion->asignatura_cod_asignatura = $asignaturaMatch1->asignatura_cod_asignatura;
            $modeloImplementacion->anio_implementacion = $asignaturaMatch1->anio_match1;
            $modeloImplementacion->semestre_implementacion = $asignaturaMatch1->semestre_match1;

            return $this->render('pasoUno', [
                'model' => $modeloImplementacion,
                'asignatura' => $asignatura,
                'code' => 100,
            ]);
        }
    }

    public function actionPasoDos()
    {
        $request = Yii::$app->request;

        if($request->isPost){
            $modeloSeccion = new Seccion();
            $modelsSeccion = [new seccion];
            $modelsSeccion = Model::createMultiple(seccion::classname());
            Model::loadMultiple($modelsSeccion, Yii::$app->request->post());

            $valid = Model::validateMultiple($modelsSeccion);

            if ($valid) {
                $transaction = \Yii::$app->db->beginTransaction();
                try {

                    foreach ($modelsSeccion as $modelSeccion) {
                        $flag = $modelSeccion->save(false);

                        if (! ($flag)) {
                            $transaction->rollBack();
                            break;
                        }
                    }

                    if ($flag) {
                        $transaction->commit();

                        $session = Yii::$app->session;
                        $session->open();
                        $session['impEnCurso.secciones'] = 1;
                        $session->close();

                        return $this->redirect(['seleccion-seccion']);
                    }
                } catch (Exception $e) {
                    $transaction->rollBack();
                    return $this->render('pasoDos', [
                        //'datosPost' => $request->post('Implementacion')['asignatura_cod_asignatura'],
                        'model' => $modeloSeccion,
                        'modelsSeccion' => $modelsSeccion,
                        //'idImplementacion' => $modeloSeccion->implementacion_id_implementacion,
                    ]);
                }
            }else{
                var_dump("CAE EN VALIDACIÓN DE SECCIONES");
            }

            //return $this->redirect(['seleccion-seccion']);
        }else{
            $modeloSeccion = new Seccion();
            $modelsSeccion = [new seccion];
            $modelsSeccion[0]->numero_seccion = 1;

            $session = Yii::$app->session;
            $session->open();
            if($session->has('impEnCurso.id_implementacion')){
                $modelsSeccion[0]->implementacion_id_implementacion = $session['impEnCurso.id_implementacion'];
            }else{
                die;
            }
            $session->close();
            //SERIA LA ID QUE ARROJARÍA AL CREAR LA IMPLEMENTACIÓN
            //$modeloSeccion->implementacion_id_implementacion = $request->post('Implementacion')['asignatura_cod_asignatura'];

            return $this->render('pasoDos', [
                //'datosPost' => $request->post('Implementacion')['asignatura_cod_asignatura'],
                'model' => $modeloSeccion,
                'modelsSeccion' => $modelsSeccion,
                //'idImplementacion' => $modeloSeccion->implementacion_id_implementacion,
            ]);
        }
    }

    public function actionSeleccionSeccion()
    {
        $request = Yii::$app->request;

        if($request->isPost){
            $session = Yii::$app->session;
            $session->open();
            $session['impEnCurso.seccion_seleccionada'] = $request->post('seccionSeleccionada');
            $session->close();
            return $this->redirect(['paso-tres']);
        }else{
            //SE BUSCA LA LISTA DE SECCIONES DISPONIBLES POR LA ID DE IMPLEMENTACION post('id_implementacion')
            //$idImplementacion = $request->post('id_implementacion');
            $session = Yii::$app->session;
            $session->open();
            if($session['impEnCurso.secciones'] == 1){
                $implementacion = implementacion::findOne($session['impEnCurso.id_implementacion']);
            };
            $session->close();
            $seccionesIngresadas = $implementacion->seccions;
            return $this->render('decisionSeccion', [
                'datosPost' => $request->post(),
                'secciones' => $seccionesIngresadas,
            ]);
        }
    }

    public function actionPasoTres()
    {
        $request = Yii::$app->request;

        if($request->isPost){
            if($request->post('procesar') == 'true'){
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

                for ($row = 5; $row <= $highestRow; $row++){
                    $rowData = $sheet->rangeToArray('A'.$row.':'.$highestColumn.$row, NULL, TRUE, FALSE);
                    //$rowData = $sheet->rangeToArray('A'.$row.':'.'G'.$row, NULL, TRUE, FALSE);
                    if($row == 5){
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

                    $modelAlumno = new Alumno();
                    $modelAlumno->rut_alumno = str_replace("-", "", $rowData[0][1]);
                    $modelAlumno->nombre = trim(preg_replace('/\s\s+/', ' ', str_replace("\n", " ", $rowData[0][2])));
                    $modelAlumno->email =$rowData[0][6];
                    try {
                        $exists = Alumno::findOne($modelAlumno->rut_alumno);
                        if($exists != NULL){
                            $flag = true;
                        }else{
                            $flag = $modelAlumno->save();
                        }
                        if ($flag) {
                            $session = Yii::$app->session;
                            $session->open();
                            if($exists != NULL){
                                $alumnoEstaInscrito = AlumnoInscritoSeccion::find()
                                    ->where(["alumno_rut_alumno"=> $exists->rut_alumno, "seccion_id_seccion"=>$session['impEnCurso.seccion_seleccionada']])->one();
                                if($alumnoEstaInscrito == NULL){
                                    $alumnoInscrito = new AlumnoInscritoSeccion();
                                    $alumnoInscrito->alumno_rut_alumno = $exists->rut_alumno;
                                    $alumnoInscrito->seccion_id_seccion = $session['impEnCurso.seccion_seleccionada'];
                                    $session->close();
                                    $alumnoInscrito->save();
                                }
                            }elseif($exists == NULL){
                                $alumnoInscrito = new AlumnoInscritoSeccion();
                                $alumnoInscrito->alumno_rut_alumno = $modelAlumno->rut_alumno;
                                $alumnoInscrito->seccion_id_seccion = $session['impEnCurso.seccion_seleccionada'];
                                $session->close();
                                $alumnoInscrito->save();
                            }
                            //$transaction->commit();
                            //echo "exito</br>";
                        }else{
                            echo "ERROR GRAVE!!!";
                        }
                    } catch (IntegrityException $e) {
                        //$transaction->rollBack();
                       //echo "fracaso</br>";
                    }

                    //PARA HACER BATCH INSERT
                    $aux = $rowData[0][1];
                    $sinGuion = str_replace("-", "", $aux);
                    $resultado2[]= [$sinGuion,$rowData[0][2],$rowData[0][6]];
                }
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
                return $this->render('pasoTres', [
                    'model' => $modeloArchivoExcel,
                    'datosPost' => $request->post(),
                    'resultado' => $dataProvider,
                ]);
            } elseif ($request->post('aceptarDatos') == 'true'){
                echo "HOLA";
                die;
            }
        }else{
            $modeloArchivoExcel = new ArchivoExcel();
            return $this->render('pasoTres', [
                'datosPost' => $request->post(),
                'model' => $modeloArchivoExcel,
            ]);
        }
    }

    public function actionPasoCuatro()
    {
        $request = Yii::$app->request;

        if($request->isPost) {
            if ($request->post('guardarYContinuar') == 'true') {
                //var_dump($request->post("grupo"));
                /*$session = Yii::$app->session;
                $session->open();
                var_dump($session);
                die;*/
                // COMPROBAR SI LA SECCIÓN YA TIENE GRUPOS ASÍ SE HACE UN FIND WHERE SECCIÓN SE IGUAL A LA SELECCIONADA
                // Y EL NÚMERO DEL GRUPO SEA IGUAL A LO QUE VA RECORRIENDO EL FOR
                foreach ($request->post() as $k=>$v){
                    $grupo = explode('-', $k);
                    if($grupo[0] == "grupo"){
                        echo "<br>";
                        echo "Este es el grupo N°".$grupo[1];
                        var_dump($v);
                        $ids = explode(',', $v);
                        foreach ($ids as $id){
                            echo "X".$id."X";
                        }
                        echo "<br>";
                    }
                }
                die;
            }
        }else{
            //DEBO PASARLE LA LISTA DE ALUMNOS SEGÚN SECCIÓN SELECCIONADA
            $modeloGrupo = new AlumnoInscritoHasGrupoTrabajo();
            $modeloAlumno = new AlumnoInscritoSeccion();
            return $this->render('pasoCinco', [

                'cantidadGrupos' => 5,
                'model' => $modeloGrupo,
                'modeloAlumno' => $modeloAlumno,
            ]);
        }
    }
    /**
     * Lists all implementacion models.
     * @return mixed
     */
    public function actionIndex()
    {
        $request = Yii::$app->request;
        if($request->isPost || $request->isAjax){
            if($request->post('hasEditable')){
                $idImplementacion = $request->post('editableKey');
                $implementacion = Implementacion::findOne($idImplementacion);
                if($implementacion != null){
                    if(Yii::$app->estados->tieneDatosCompletos($idImplementacion)){
                        //$post = [];
                        $posted = current($_POST['Implementacion']);
                        //$post['Implementacion'] = $posted;
                        $implementacion->estado = $posted['estado'];
                        if($implementacion->save(false)){
                            $estadoImp = EstadoImplementacion::findOne($implementacion->estado);
                            if($estadoImp != null){
                                $output = $estadoImp->nombre_estado;
                            }else{
                                $output = '';
                            }

                            $out = Json::encode(['output'=> $output, 'message' => '']);
                        }else{
                            $out = Json::encode(['output'=> '', 'message' => 'No se cargaron los datos.']);
                        }
                    }else{
                        $out = Json::encode(['output'=> '', 'message' => 'DEBE COMPLETAR DATOS IMPLEMENTACIÓN.']);
                    }
                    echo $out;
                }else{
                    $out = Json::encode(['output'=> '', 'message' => 'Implementación no encontrada.']);
                    echo $out;
                }
                return;

            }//FIN HAS EDITABLE
        }

        $searchModel = new ImplementacionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single implementacion model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {   
        $request = Yii::$app->request;
        if($request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                    'title'=> "implementacion #".$id,
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

    public function actionVistaGeneral($id)
    {
        $arregloSearchModel = [];
        $arregloDataproviders = [];
        $docentes = [];
        //$ID ES LA ID DE LA IMPLEMENTACIÓN
        $implementacion = Implementacion::findOne($id);
        if($implementacion != null){
            $secciones = $implementacion->seccions;
            if($secciones != null){
                foreach ($secciones as $key => $seccion){
                    //SECCIONES NO AFECTAN LOS DATAPROVIDERS
                    $docentes[] = $seccion->docenteRutDocente->rutNombre;

                    ${"searchModel".$key} = new AlumnoInscritoSeccionSearch();
                    $arregloSearchModel[] =  ${"searchModel".$key};

                    ${"dataProvider".$key} = ${"searchModel".$key}->searchVistaGeneral(Yii::$app->request->queryParams);
                    ${"dataProvider".$key}->query->andFilterWhere(['alumno_inscrito_seccion.seccion_id_seccion' => $seccion->id_seccion]);

                    ${"dataProvider".$key}->pagination->pageParam = $key.'-dp-page';
                    ${"dataProvider".$key}->sort->sortParam = $key.'-dp-sort';
                    $arregloDataproviders[] = ${"dataProvider".$key};
                }
            }
        }

        return $this->render('/implementacion/vista-general/view', [
            'implementacion' => $implementacion,
            'docentes' => $docentes,
            'arregloSearchModels' => $arregloSearchModel,
            'arregloDataProviders' => $arregloDataproviders,
        ]);
    }

    /**
     * Creates a new implementacion model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $request = Yii::$app->request;
        $model = new implementacion();  

        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet){
                return [
                    'title'=> "Crear nuevo implementacion",
                    'content'=>$this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Guardar',['class'=>'btn btn-primary','type'=>"submit"])
        
                ];         
            }else if($model->load($request->post()) && $model->save()){
                return [
                    'forceReload'=>'#crud-datatable-pjax',
                    'title'=> "Crear nuevo implementacion",
                    'content'=>'<span class="text-success">Create implementacion success</span>',
                    'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                            Html::a('Crar Más',['create'],['class'=>'btn btn-primary','role'=>'modal-remote'])
        
                ];         
            }else{           
                return [
                    'title'=> "Crear nuevo implementacion",
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
                return $this->redirect(['view', 'id' => $model->id_implementacion]);
            } else {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        }
       
    }

    public function actionPrueba(){
        $implementacion = Implementacion::findOne(31);
        if($implementacion != null) {
            if (Implementacion::getTieneTodosLosDatos($implementacion)) {
                var_dump(Implementacion::getTieneTodosLosDatos($implementacion));
            }
        }
    }
    //FUNCIONES MODIFICAR
    public function actionSeleccionImplementacion()
    {
        $request = Yii::$app->request;
        if($request->isPost || $request->isAjax){
            if($request->post('hasEditable')){
                $idImplementacion = $request->post('editableKey');
                $implementacion = Implementacion::findOne($idImplementacion);
                if($implementacion != null){
                    if(false){
                        //$post = [];
                        $posted = current($_POST['Implementacion']);
                        //$post['Implementacion'] = $posted;
                        $implementacion->estado = $posted['estado'];
                        if($implementacion->save(false)){
                            $estadoImp = EstadoImplementacion::findOne($implementacion->estado);
                            if($estadoImp != null){
                                $output = $estadoImp->nombre_estado;
                            }else{
                                $output = '';
                            }

                            $out = Json::encode(['output'=> $output, 'message' => '']);
                        }else{
                            $out = Json::encode(['output'=> '', 'message' => 'No se cargaron los datos.']);
                        }
                    }else{
                        $out = Json::encode(['output'=> '', 'message' => 'DEBE COMPLETAR DATOS IMPLEMENTACIÓN.']);
                    }
                    echo $out;
                }else{
                    $out = Json::encode(['output'=> '', 'message' => 'Implementación no encontrada.']);
                    echo $out;
                }
                return;

            }//FIN HAS EDITABLE

            if($request->post('seleccion')){
                if($request->post('kvradio') == null){
                    Yii::$app->getSession()->setFlash('success', [
                        'type' => 'error',
                        'duration' => 5000,
                        //'icon' => 'fa fa-users',
                        'message' => 'Debe seleccionar una asignatura',
                        'title' => 'Error',
                        'positonY' => 'top',
                        //'positonX' => 'left'
                    ]);

                    $modeloPeriodo = new AnioSemestre();

                    $modeloPeriodo->load($request->post());

                    $query = Implementacion::find()->where(['anio_implementacion'=> $modeloPeriodo->anio])->andWhere(['semestre_implementacion'=>$modeloPeriodo->semestre])/*->groupBy(['asignatura_cod_asignatura'])*/;
                    //$ids = Match1::find()->select('asignatura_cod_asignatura')->where(['anio_match1'=> $modeloPeriodo->anio])->andWhere(['semestre_match1'=>$modeloPeriodo->semestre])->groupBy(['asignatura_cod_asignatura']);
                    //$query = Asignatura::find()->where(['in', 'cod_asignatura', $ids]);

                    $cantidad = $query->count();

                    $dataProvider = new ActiveDataProvider([
                        'query' => $query,
                        'pagination' => [
                            'pageSize' => $cantidad,
                        ],
                    ]);

                    return $this->render('modificar/seleccionImplementacion', [
                        'dataProvider' => $dataProvider,
                        'modeloPeriodo' => $modeloPeriodo,
                    ]);
                }else{
                    return $this->redirect(['/implementacion/panel-implementacion', 'idImplementacion' => Yii::$app->request->post('kvradio')]);

                }
            }else{
                $modeloPeriodo = new AnioSemestre();

                $modeloPeriodo->load($request->post());

                $query = Implementacion::find()->where(['anio_implementacion'=> $modeloPeriodo->anio])->andWhere(['semestre_implementacion'=>$modeloPeriodo->semestre])->indexBy('id_implementacion'); /*->groupBy(['asignatura_cod_asignatura'])*/
                //$ids = Match1::find()->select('asignatura_cod_asignatura')->where(['anio_match1'=> $modeloPeriodo->anio])->andWhere(['semestre_match1'=>$modeloPeriodo->semestre])->groupBy(['asignatura_cod_asignatura']);
                //$query = Asignatura::find()->where(['in', 'cod_asignatura', $ids]);

                $cantidad = $query->count();

                $dataProvider = new ActiveDataProvider([
                    'query' => $query,
                    'pagination' => [
                        'pageSize' => $cantidad,
                    ],
                ]);
                $filter = new ImplementacionSearch();
                return $this->render('modificar/seleccionImplementacion', [
                    'dataProvider' => $dataProvider,
                    'modeloPeriodo' => $modeloPeriodo,
                ]);
            }

        }else{
            $modeloPeriodo = new AnioSemestre();

            return $this->render('modificar/seleccionImplementacion', [
                'modeloPeriodo' => $modeloPeriodo,
            ]);
        }

    }

    public function actionPanelImplementacion($idImplementacion, $idSeccion=null)
    {
        $request = Yii::$app->request;
        if($request->isPost || $request->isAjax){
            //CAMBIAR ESTADO
            $estado = $request->post('estadoImplementacion');
            if(isset($estado)){
                $implementacion = Implementacion::findOne($idImplementacion);
                $implementacion->estado = $estado;

                if($implementacion->save(false)){
                        $requerimientos = $implementacion->match1Requerimientos;
                        foreach ($requerimientos as $requerimiento){
                            //EN CURSO
                            if($estado == 1){
                                Yii::$app->mensaje->mensajeGrowl('success', 'Implementación PUBLICADA');
                                Yii::$app->estados->actualizarEstadoRequerimiento($requerimiento->requerimiento_id_requerimiento,
                                    "En Desarrollo");
                                Yii::$app->estados->actualizarEstadoServicio($requerimiento->servicio_id_servicio,
                                    "En Desarrollo");
                            }
                            //FINALIZADA
                            if ($estado == 2){
                                Yii::$app->mensaje->mensajeGrowl('success', 'Implementación FINALIZADA');
                                Yii::$app->estados->actualizarEstadoRequerimiento($requerimiento->requerimiento_id_requerimiento,
                                    "Finalizado");
                                Yii::$app->estados->actualizarEstadoServicio($requerimiento->servicio_id_servicio,
                                    "Finalizado");
                            }
                            //CREADA
                            if ($estado == 0){
                                Yii::$app->mensaje->mensajeGrowl('info', 'Implementación NO PUBLICADA');
                                Yii::$app->estados->actualizarEstadoRequerimiento($requerimiento->requerimiento_id_requerimiento,
                                    "En Desarrollo");
                                Yii::$app->estados->actualizarEstadoServicio($requerimiento->servicio_id_servicio,
                                    "En Desarrollo");
                            }
                        }

                }

            }
            return $this->redirect(['/implementacion/panel-implementacion', 'idImplementacion' => Yii::$app->request->get('idImplementacion'), 'idSeccion' =>Yii::$app->request->get('idSeccion')]);
        }else{
            $request = Yii::$app->request;
            $id_implementacion = $idImplementacion;//$request->get('idImplementacion');
            $id_seccion_seleccionada = $idSeccion;

            //IMPLEMENTACIÓN QUE SE ESTÁ TRABAJANDO
            $implementacion = Implementacion::findOne($id_implementacion);
            if($implementacion == null){
                echo "Implementación no encontrada (eliminada).";
                die;
            }

            //SECCIONES QUE POSEE LA IMPLEMENTACIÓN
            $seccionesXX = Seccion::findAll(['implementacion_id_implementacion'=> $id_implementacion]); //SECCIONES QUE TIENE LA IMPLEMENTACION
            $secciones = Seccion::find()->where(['implementacion_id_implementacion'=> $id_implementacion]);
            $cantidadSecciones = $secciones->count();
            //ids de esas secciones
            $idesSecciones = Seccion::find()->select(["id_seccion"])->where(['implementacion_id_implementacion'=> $id_implementacion])->asArray()->all();

            if($id_seccion_seleccionada != null){
                $idesSeccionesMapeadas = array_map(function($idSeccion) {
                    return $idSeccion['id_seccion'];

                }, $idesSecciones);
                $corresponde = array_search($id_seccion_seleccionada,$idesSeccionesMapeadas);
                if($corresponde === FALSE){
                    echo "Esta sección no corresponde a la implementación";
                    die;
                }
                $seccionSeleccionada = Seccion::findOne($id_seccion_seleccionada);
                if($seccionSeleccionada != null){
                    $alumnosInscritos = $seccionSeleccionada->alumnoInscritoSeccions;
                    $gruposTrabajos = $seccionSeleccionada->grupoTrabajos;
                }else{
                    $alumnosInscritos = null;
                    $gruposTrabajos = null;
                }
            }else{
                $alumnosInscritos = null;
                $gruposTrabajos = null;
            }

            //DEPENDEN DE LA SECCIÓN SELECCIONADA
            //$cantidadAlumnosInscritos;
            //$cantidadGruposDeTrabajo;
            $tieneDatosCompletos = Yii::$app->estados->tieneDatosCompletos($implementacion->id_implementacion);

            return $this->render('modificar/panelImplementacion', [
                'implementacion' => $implementacion,
                'cantidadSecciones' => $cantidadSecciones,
                'arregloIdesSecciones' => $idesSecciones,
                'coincidenciasMatch1' => $implementacion->filasMatch,
                'idSeccionSeleccionada' => $id_seccion_seleccionada,
                'alumnosInscritos' => $alumnosInscritos,
                'gruposTrabajo' => $gruposTrabajos,
                'secciones' => $seccionesXX,
                'tieneDatosCompletos' => $tieneDatosCompletos,
            ]);
        }
    }//FIN FUNCIÓN

    public function actionCrearSeccion($idImplementacion)
    {
        return Yii::$app->runAction('seccion2/update-multiple', ['id' => $idImplementacion]);
    }

    public function actionModificarSeccion($idImplementacion)
    {
        return Yii::$app->runAction('seccion2/update-multiple', ['id' => $idImplementacion]);
    }

    public function actionInscribirAlumnos($idImplementacion, $idSeccion)
    {
        return Yii::$app->runAction('alumno-inscrito-seccion/create-excel', ['idImplementacion' => $idImplementacion, 'idSeccion' => $idSeccion]);
    }

    public function actionModificarInscripcion($idImplementacion, $idSeccion)
    {
        return Yii::$app->runAction('alumno-inscrito-seccion/index-seccion', ['idImplementacion' => $idImplementacion, 'idSeccion' => $idSeccion]);
    }

    public function actionCrearGruposTrabajo($idImplementacion, $idSeccion)
    {
        return Yii::$app->runAction('alumno-inscrito-has-grupo-trabajo/crear-multiple', ['idImplementacion' => $idImplementacion, 'idSeccion' => $idSeccion]);
    }

    public function actionModificarGruposTrabajo($idImplementacion, $idSeccion)
    {
        return Yii::$app->runAction('alumno-inscrito-has-grupo-trabajo/modificar-multiple', ['idImplementacion' => $idImplementacion, 'idSeccion' => $idSeccion]);
    }

    public function actionAsignarLider($idImplementacion, $idSeccion)
    {
        return Yii::$app->runAction('alumno-inscrito-lider/asignar-lider', ['idImplementacion' => $idImplementacion, 'idSeccion' => $idSeccion]);
    }

    //REPORTES
    public function actionReporteResumen()
    {
        $searchModel = new ImplementacionSearch();
        $dataProvider = $searchModel->searchReporteResumen(Yii::$app->request->queryParams);
        $dataProvider->sort = false;
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

        $searchModel = new ImplementacionSearch();
        $dataProvider = $searchModel->searchReporteEstadistica(Yii::$app->request->queryParams);
        $dataProvider->sort = false;

        $modeloPeriodo = new SeleccionBitacorasReporte();

        return $this->render('reporte-estadistica/reporteEstadistica', [
            'modeloPeriodo' => $modeloPeriodo,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }




    /**
     * Updates an existing implementacion model.
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
                    'title'=> "Modificar implementacion #".$id,
                    'content'=>$this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Guardar',['class'=>'btn btn-primary','type'=>"submit"])
                ];         
            }else if($model->load($request->post()) && $model->save()){
                return [
                    'forceReload'=>'#crud-datatable-pjax',
                    'title'=> "implementacion #".$id,
                    'content'=>$this->renderAjax('view', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                            Html::a('Editar',['update','id'=>$id],['class'=>'btn btn-primary','role'=>'modal-remote'])
                ];    
            }else{
                 return [
                    'title'=> "Modificar implementacion #".$id,
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
                return $this->redirect(['view', 'id' => $model->id_implementacion]);
            } else {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        }
    }

    /**
     * Delete an existing implementacion model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $request = Yii::$app->request;
        //CAMBIAR ESTADO DE LOS REQUERIMIENTOS A ASIGNADO (SOLO SE BORRA LA IMPLEMENTACION)
        $implementacionModel = $this->findModel($id);
        $filasMatch = $implementacionModel->filasMatch;
        if($filasMatch != null){
            foreach ($filasMatch as $filaModel){
                Yii::$app->estados->actualizarEstadoRequerimiento($filaModel->requerimiento_id_requerimiento, "Asignado");
                Yii::$app->estados->actualizarEstadoServicio($filaModel->servicio_id_servicio,
                    "Asignado");
            }
        }
        $implementacionModel->delete();


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
     * Delete multiple existing implementacion model.
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

    /**
     * Finds the implementacion model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return implementacion the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = implementacion::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('La página solicitada no existe.');
        }
    }

    public function actionCorreoDocente(){
        $request = Yii::$app->request;
        \Yii::$app->response->format = Response::FORMAT_JSON;
        if($request->isAjax){
            $id = Yii::$app->request->post('id');
            if (isset($id)) {
                if(Yii::$app->correos->enviarNotificacionDocente($id)){
                    return [
                        'codigo'=> 1,
                        'motivo' => "Correos enviados correctamente al/los docente/s."
                    ];
                }else{
                    return [
                        'codigo'=> 0,
                        'motivo' => "Error: Falló el envío de correos."
                    ];
                }
            }else{
                return [
                    'codigo'=> 0,
                    'motivo' => "Error: No se especificó la ID de la implementación."
                ];
            }
        }
        return false;
    }

    public function actionCorreoBeneficiarioAlumno(){
        //$controller = new CronController(Yii::$app->controller->id, Yii::$app);
        //$controller->actionEnviarNotificacionSocioBeneficiario($id);
        $request = Yii::$app->request;
        \Yii::$app->response->format = Response::FORMAT_JSON;
        if($request->isAjax){
            $id = Yii::$app->request->post('id');
            if (isset($id)) {
                if(Yii::$app->correos->enviarNotificacionSocioBeneficiario($id)){
                    return [
                        'codigo'=> 1,
                        'motivo' => "Correos enviados correctamente a los grupos de trabajo y socios comunitarios beneficiarios."
                    ];
                }else{
                    return [
                        'codigo'=> 0,
                        'motivo' => "Error: Falló el envío de correos."
                    ];
                }
            }else{
                return [
                    'codigo'=> 0,
                    'motivo' => "Error: No se especificó la ID de la implementación."
                ];
            }
        }
        return false;
    }
}
