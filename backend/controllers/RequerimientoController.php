<?php

namespace backend\controllers;

use Yii;
use backend\models\requerimiento;
use backend\models\search\RequerimientoSearch;
use yii\base\Exception;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;

use backend\models\Model;

/**
 * RequerimientoController implements the CRUD actions for requerimiento model.
 */
class RequerimientoController extends Controller
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
     * Lists all requerimiento models.
     * @return mixed
     */
    public function actionIndex($estado=null)
    {    
        $searchModel = new RequerimientoSearch();
        $dataProvider = $searchModel->searchNoEliminados(Yii::$app->request->queryParams);
        if($estado != null){
            $dataProvider->query->andFilterWhere(['estado_ejecucion_id_estado'=>$estado]);
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionIndexPapelera()
    {
        $searchModel = new RequerimientoSearch();
        $dataProvider = $searchModel->searchEliminados(Yii::$app->request->queryParams);

        return $this->render('indexPapelera', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    //PARA NO TENER CONFLICTO CON EL DE AJAXCRUD
    public function actionView2($id)
    {
        $request = Yii::$app->request;
        if($request->isAjax){

            return $this->renderAjax('view', [
                    'model' => $this->findModel($id),
                ]);
        }else{
            return $this->render('view', [
                'model' => $this->findModel($id),
            ]);
        }
    }
        /**
     * Displays a single requerimiento model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {   
        $request = Yii::$app->request;
        if($request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                    'title'=> "requerimiento #".$id,
                    'content'=>$this->renderAjax('_view', [
                        'model' => $this->findModel($id),
                    ]),
                    'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                            Html::a('Editar',['update','id'=>$id],['class'=>'btn btn-primary','role'=>'modal-remote'])
                ];    
        }else{
            return $this->render('view', [
                'model' => $this->findModel($id),
            ]);
        }
    }

    /**
     * Creates a new requerimiento model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $request = Yii::$app->request;
        $modelsRequerimiento = [new requerimiento(['scenario' => Requerimiento::SCENARIO_REQUERIMIENTO])];


        if (Yii::$app->request->post()) {
            $modelsRequerimiento = Model::createMultiple(requerimiento::classname());
            Model::loadMultiple($modelsRequerimiento, Yii::$app->request->post());

            // ajax validation
            /*if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ArrayHelper::merge(
                    ActiveForm::validateMultiple($modelsRequerimiento),
                    ActiveForm::validate($model)
                );
            }*/

            // validate all models
            $valid = Model::validateMultiple($modelsRequerimiento);

            if ($valid) {
                $transaction = \Yii::$app->db->beginTransaction();
                try {

                    foreach ($modelsRequerimiento as $modelRequerimiento) {
                        $modelRequerimiento->estado_ejecucion_id_estado = 1;
                        if (! ($flag = $modelRequerimiento->save(false))) {
                            $transaction->rollBack();
                            break;
                        }
                    }

                    if ($flag) {
                        $transaction->commit();
                        return $this->redirect(['view', 'id' => $modelsRequerimiento[0]->id_requerimiento]);
                    }
                } catch (Exception $e) {
                    $transaction->rollBack();
                }
            }
        } else {
            return $this->render('create', [
                'modelsRequerimiento' => (empty($modelsRequerimiento)) ? [new requerimiento] : $modelsRequerimiento,
            ]);
        }

       
    }

    /**
     * Updates an existing requerimiento model.
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
                    'title'=> "Modificar requerimiento #".$id,
                    'content'=>$this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Guardar',['class'=>'btn btn-primary','type'=>"submit"])
                ];
            }else if($model->load($request->post()) && $model->save()){
                return [
                    'forceReload'=>'#crud-datatable-pjax',
                    'title'=> "requerimiento #".$id,
                    'content'=>$this->renderAjax('view', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                            Html::a('Editar',['update','id'=>$id],['class'=>'btn btn-primary','role'=>'modal-remote'])
                ];
            }else{
                 return [
                    'title'=> "Modificar requerimiento #".$id,
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
                return $this->redirect(['view', 'id' => $model->id_requerimiento]);
            } else {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        }
    }

    public function actionRestore($id)
    {
        $request = Yii::$app->request;
        //$this->findModel($id)->delete();
        $this->findModel($id)->restore();
        Yii::$app->mensaje->mensajeGrowl('info', 'Requerimiento restaurado.');
        if(false){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['forceClose'=>true,'forceReload'=>'#crud-datatable-pjax'];
        }else{
            /*
            *   Process for non-ajax request
            */
            return $this->redirect(['index-papelera']);
        }


    }

    /**
     * Delete an existing requerimiento model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $request = Yii::$app->request;
        $requerimiento = $this->findModel($id);
        //SÓLO SI NO ESTÁ ASIGNADO
        if($requerimiento->estado_ejecucion_id_estado == 1){
            $this->findModel($id)->softDelete();
        }else{
            Yii::$app->mensaje->mensajeGrowl('danger', 'Este Requerimiento no puede ser eliminado debido a que está siendo
            utilizado en otro proceso.', 7000);
            return $this->redirect(Yii::$app->request->referrer);
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

    public function actionDeletePermanente($id)
    {
        $request = Yii::$app->request;
        //$this->findModel($id)->delete();
        $this->findModel($id)->delete();
        Yii::$app->mensaje->mensajeGrowl('info', 'Requerimiento eliminado permanentemente.');
        if(false){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['forceClose'=>true,'forceReload'=>'#crud-datatable-pjax'];
        }else{
            /*
            *   Process for non-ajax request
            */
            return $this->redirect(['index-papelera']);
        }


    }
     /**
     * Delete multiple existing requerimiento model.
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

            if($model->estado_ejecucion_id_estado == 1){
                $model->softDelete();
            }else{
                Yii::$app->mensaje->mensajeGrowl('danger', 'Este Requerimiento no puede ser eliminado debido a que está siendo
            utilizado en otro proceso.', 7000);
                return $this->redirect(Yii::$app->request->referrer);
            }

        }
        //Yii::$app->mensaje->mensajeGrowl('info', 'Requerimientos enviados a la papelera.');
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

    public function actionBulkDeletePermanente()
    {
        $request = Yii::$app->request;
        $pks = explode(',', $request->post( 'pks' )); // Array or selected records primary keys
        foreach ( $pks as $pk ) {
            $model = $this->findModel($pk);
            //$model->delete();
            $model->delete();
        }
        Yii::$app->mensaje->mensajeGrowl('info', 'Requerimientos eliminados permanentemente.');
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
     * Finds the requerimiento model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return requerimiento the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = requerimiento::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('La página solicitada no existe.');
        }
    }
}
