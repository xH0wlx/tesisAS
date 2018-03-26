<?php

namespace backend\controllers;

use backend\models\Implementacion;
use backend\models\Model;
use Yii;
use backend\models\seccion;
use backend\models\search\SeccionSearch;
use yii\base\Exception;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;

/**
 * SeccionController implements the CRUD actions for seccion model.
 */
class Seccion2Controller extends Controller
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
     * Lists all seccion models.
     * @return mixed
     */
    public function actionIndex()
    {    
        $searchModel = new SeccionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Displays a single seccion model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {   
        $request = Yii::$app->request;
        if($request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                    'title'=> "seccion #".$id,
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
     * Creates a new seccion model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
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

                        return $this->redirect(['/implementacion/panel-implementacion', 'idImplementacion' => Yii::$app->request->get('idImplementacion')]);

                        //return $this->redirect(['seleccion-seccion']);
                    }
                } catch (Exception $e) {
                    $transaction->rollBack();
                    return $this->render('createMultiple', [
                        //'datosPost' => $request->post('Implementacion')['asignatura_cod_asignatura'],
                        'model' => $modeloSeccion,
                        'modelsSeccion' => $modelsSeccion,
                        //'idImplementacion' => $modeloSeccion->implementacion_id_implementacion,
                    ]);
                }
            }else{
                var_dump("ERROR EN VALIDACIÓN DE SECCIONES");
            }

            //return $this->redirect(['seleccion-seccion']);
        }else{
            $modeloSeccion = new Seccion();
            $modelsSeccion = [new seccion];
            $modelsSeccion[0]->implementacion_id_implementacion = Yii::$app->request->get('idImplementacion');
            $modelsSeccion[0]->numero_seccion = 1;

            $session = Yii::$app->session;
            $session->open();
            if($session->has('impEnCurso.id_implementacion')){
                $modelsSeccion[0]->implementacion_id_implementacion = $session['impEnCurso.id_implementacion'];
            }else{
                //die;
            }
            $session->close();
            //SERIA LA ID QUE ARROJARÍA AL CREAR LA IMPLEMENTACIÓN
            //$modeloSeccion->implementacion_id_implementacion = $request->post('Implementacion')['asignatura_cod_asignatura'];

            return $this->render('createMultiple', [
                //'datosPost' => $request->post('Implementacion')['asignatura_cod_asignatura'],
                'model' => $modeloSeccion,
                'modelsSeccion' => $modelsSeccion,
                //'idImplementacion' => $modeloSeccion->implementacion_id_implementacion,
            ]);
        }
    }

    /**
     * Updates an existing seccion model.
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
                    'title'=> "Modificar seccion #".$id,
                    'content'=>$this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Guardar',['class'=>'btn btn-primary','type'=>"submit"])
                ];         
            }else if($model->load($request->post()) && $model->save()){
                return [
                    'forceReload'=>'#crud-datatable-pjax',
                    'title'=> "seccion #".$id,
                    'content'=>$this->renderAjax('view', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                            Html::a('Editar',['update','id'=>$id],['class'=>'btn btn-primary','role'=>'modal-remote'])
                ];    
            }else{
                 return [
                    'title'=> "Modificar seccion #".$id,
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
                return $this->redirect(['view', 'id' => $model->id_seccion]);
            } else {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        }
    }

    public function actionUpdateMultiple($id){
        $model = new Implementacion();
        $model = $model->findOne($id); //IMPLEMENTACIÓN
        if($model == null){
            throw new HttpException(404, 'La implementación no existe.');
        }
        $modelsSeccion = $model->seccions;

        if (Yii::$app->request->post()) {
            $oldIDs = ArrayHelper::map($modelsSeccion, 'id_seccion', 'id_seccion');

            $modelsSeccion = Model::createMultiple(Seccion::classname(), $modelsSeccion);
            Model::loadMultiple($modelsSeccion, Yii::$app->request->post());

            $deletedIDs = array_diff($oldIDs, array_filter(ArrayHelper::map($modelsSeccion, 'id_seccion', 'id_seccion')));

            $valid = $model->validate();
            $valid = Model::validateMultiple($modelsSeccion) && $valid;

            if ($valid) {
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    if ($flag = $model->save(false)) {
                        if (! empty($deletedIDs)) {
                            seccion::deleteAll(['id_seccion' => $deletedIDs]);
                        }
                        foreach ($modelsSeccion as $modelSeccion) {
                            $modelSeccion->implementacion_id_implementacion = $model->id_implementacion;
                            if (! ($flag = $modelSeccion->save(false))) {
                                $transaction->rollBack();
                                break;
                            }
                        }
                    }
                    if ($flag) {
                        $transaction->commit();
                        Yii::$app->mensaje->mensajeGrowl('success', 'Secciones actualizadas exitosamente.');
                        return $this->redirect(['/implementacion/panel-implementacion', 'idImplementacion' => $model->id_implementacion]);
                    }
                } catch (Exception $e) {
                    $transaction->rollBack();
                    Yii::$app->mensaje->mensajeGrowl('error', 'Hubo un error durante la actualización de datos.');
                    return $this->redirect(['/implementacion/panel-implementacion', 'idImplementacion' => $model->id_implementacion]);
                }
            }else{
                Yii::$app->mensaje->mensajeGrowl('error', 'Error en la validación de datos.');
                return $this->redirect(['/implementacion/panel-implementacion', 'idImplementacion' => $model->id_implementacion]);
            }
        }

        return $this->render('createMultiple', [
            'model' => $model,
            'modelsSeccion' => (empty($modelsSeccion)) ? [new seccion()] : $modelsSeccion,
        ]);
    }

    /**
     * Delete an existing seccion model.
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
     * Delete multiple existing seccion model.
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
     * Finds the seccion model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return seccion the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = seccion::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('La página solicitada no existe.');
        }
    }
}
