<?php

namespace backend\controllers;

use Yii;
use backend\models\oferta;
use backend\models\servicio;
use backend\models\search\OfertaSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;

use backend\models\Model;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\widgets\ActiveForm;
use yii\data\ActiveDataProvider;

/**
 * OfertaController implements the CRUD actions for oferta model.
 */
class OfertaController extends Controller
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
     * Lists all oferta models.
     * @return mixed
     */
    public function actionIndex()
    {    
        $searchModel = new OfertaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Displays a single oferta model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {   
        $request = Yii::$app->request;
        if(false/*$request->isAjax*/){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                    'title'=> "oferta #".$id,
                    'content'=>$this->renderAjax('view', [
                        'model' => $this->findModel($id),
                    ]),
                    'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                            Html::a('Edit',['update','id'=>$id],['class'=>'btn btn-primary','role'=>'modal-remote'])
                ];    
        }else{
            $model = $this->findModel($id);
            $modelServicio = $model->getServicios();

            $dataProviderServicio = new ActiveDataProvider([
                'query' => $modelServicio,
                'pagination' => [
                    'pageSize' => 20,
                ],
            ]);
            return $this->render('view', [
                'model' => $model,
                'dataProviderServicio' => $dataProviderServicio,
            ]);
        }
    }

    /**
     * Creates a new oferta model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new oferta();
        $modelsServicio = [new servicio];

        if (false/*Yii::$app->request->isAjax*/) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return //ArrayHelper::merge(
                ActiveForm::validateMultiple($modelsServicio);
            //ActiveForm::validate($model)
            //);
        }else{

            if ($model->load(Yii::$app->request->post())) {
                $modelsServicio = Model::createMultiple(servicio::classname());
                Model::loadMultiple($modelsServicio, Yii::$app->request->post());

                // ajax validation
                /*if (Yii::$app->request->isAjax) {
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    return ArrayHelper::merge(
                        ActiveForm::validateMultiple($modelsRequerimiento),
                        ActiveForm::validate($model)
                    );
                }*/

                // validate all models
                $valid = $model->validate();
                $valid = Model::validateMultiple($modelsServicio) && $valid;

                if ($valid) {
                    $transaction = \Yii::$app->db->beginTransaction();
                    try {
                        if ($flag = $model->save(false)) {
                            foreach ($modelsServicio as $modelServicio) {
                                $modelServicio->oferta_id_oferta = $model->id_oferta;
                                $modelServicio->estado_ejecucion_id_estado = 1;
                                if (! ($flag = $modelServicio->save(false))) {
                                    $transaction->rollBack();
                                    break;
                                }
                            }
                        }
                        if ($flag) {
                            $transaction->commit();
                            return $this->redirect(['view', 'id' => $model->id_oferta]);
                        }
                    } catch (Exception $e) {
                        $transaction->rollBack();
                    }
                }
            }

            return $this->render('create', [
                'model' => $model,
                'modelsServicio' => (empty($modelsServicio)) ? [new servicio] : $modelsServicio,
            ]);
        }//ELSE DE LA VERIFICACION POR AJAX
       
    }

    /**
     * Updates an existing oferta model.
     * For ajax request will return json object
     * and for non-ajax request if update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $modelsServicio = $model->servicios;

        if ($model->load(Yii::$app->request->post())) {

            $oldIDs = ArrayHelper::map($modelsServicio, 'id_servicio', 'id_servicio');

            $modelsServicio = Model::createMultiple(Servicio::classname(), $modelsServicio);
            Model::loadMultiple($modelsServicio, Yii::$app->request->post());

            $deletedIDs = array_diff($oldIDs, array_filter(ArrayHelper::map($modelsServicio, 'id_servicio', 'id_servicio')));

            // ajax validation
            /*if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ArrayHelper::merge(
                    ActiveForm::validateMultiple($modelsAddress),
                    ActiveForm::validate($modelCustomer)
                );
            }*/

            // validate all models
            $valid = $model->validate();
            $valid = Model::validateMultiple($modelsServicio) && $valid;

            if ($valid) {
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    if ($flag = $model->save(false)) {
                        if (! empty($deletedIDs)) {
                            servicio::deleteAll(['id_servicio' => $deletedIDs]);
                        }
                        foreach ($modelsServicio as $modelServicio) {
                            $modelServicio->oferta_id_oferta = $model->id_oferta;
                            $modelServicio->estado_ejecucion_id_estado = 1;
                            if (! ($flag = $modelServicio->save(false))) {
                                $transaction->rollBack();
                                break;
                            }
                        }
                    }
                    if ($flag) {
                        $transaction->commit();
                        return $this->redirect(['view', 'id' => $model->id_oferta]);
                    }
                } catch (Exception $e) {
                    $transaction->rollBack();
                }
            }
        }

        return $this->render('update', [
            'model' => $model,
            'modelsServicio' => (empty($modelsServicio)) ? [new servicio] : $modelsServicio,
        ]);
    }

    /**
     * Delete an existing oferta model.
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
     * Delete multiple existing oferta model.
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
     * Finds the oferta model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return oferta the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = oferta::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
