<?php

namespace backend\controllers;

use Yii;
use backend\models\requerimientoHasEtiquetaArea;
use backend\models\search\RequerimientoHasEtiquetaAreaSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;

/**
 * RequerimientoHasEtiquetaAreaController implements the CRUD actions for requerimientoHasEtiquetaArea model.
 */
class RequerimientoHasEtiquetaAreaController extends Controller
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
     * Lists all requerimientoHasEtiquetaArea models.
     * @return mixed
     */
    public function actionIndex()
    {    
        $searchModel = new RequerimientoHasEtiquetaAreaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Displays a single requerimientoHasEtiquetaArea model.
     * @param integer $requerimiento_id_requerimiento
     * @param integer $etiqueta_area_id_etiqueta_area
     * @return mixed
     */
    public function actionView($requerimiento_id_requerimiento, $etiqueta_area_id_etiqueta_area)
    {   
        $request = Yii::$app->request;
        if($request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                    'title'=> "requerimientoHasEtiquetaArea #".$requerimiento_id_requerimiento, $etiqueta_area_id_etiqueta_area,
                    'content'=>$this->renderAjax('view', [
                        'model' => $this->findModel($requerimiento_id_requerimiento, $etiqueta_area_id_etiqueta_area),
                    ]),
                    'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                            Html::a('Edit',['update','requerimiento_id_requerimiento, $etiqueta_area_id_etiqueta_area'=>$requerimiento_id_requerimiento, $etiqueta_area_id_etiqueta_area],['class'=>'btn btn-primary','role'=>'modal-remote'])
                ];    
        }else{
            return $this->render('view', [
                'model' => $this->findModel($requerimiento_id_requerimiento, $etiqueta_area_id_etiqueta_area),
            ]);
        }
    }

    /**
     * Creates a new requerimientoHasEtiquetaArea model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $request = Yii::$app->request;
        $model = new requerimientoHasEtiquetaArea();  

        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet){
                return [
                    'title'=> "Create new requerimientoHasEtiquetaArea",
                    'content'=>$this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Save',['class'=>'btn btn-primary','type'=>"submit"])
        
                ];         
            }else if($model->load($request->post()) && $model->save()){
                return [
                    'forceReload'=>'#crud-datatable-pjax',
                    'title'=> "Create new requerimientoHasEtiquetaArea",
                    'content'=>'<span class="text-success">Create requerimientoHasEtiquetaArea success</span>',
                    'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                            Html::a('Create More',['create'],['class'=>'btn btn-primary','role'=>'modal-remote'])
        
                ];         
            }else{           
                return [
                    'title'=> "Create new requerimientoHasEtiquetaArea",
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
                return $this->redirect(['view', 'requerimiento_id_requerimiento' => $model->requerimiento_id_requerimiento, 'etiqueta_area_id_etiqueta_area' => $model->etiqueta_area_id_etiqueta_area]);
            } else {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        }
       
    }

    /**
     * Updates an existing requerimientoHasEtiquetaArea model.
     * For ajax request will return json object
     * and for non-ajax request if update is successful, the browser will be redirected to the 'view' page.
     * @param integer $requerimiento_id_requerimiento
     * @param integer $etiqueta_area_id_etiqueta_area
     * @return mixed
     */
    public function actionUpdate($requerimiento_id_requerimiento, $etiqueta_area_id_etiqueta_area)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($requerimiento_id_requerimiento, $etiqueta_area_id_etiqueta_area);       

        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet){
                return [
                    'title'=> "Update requerimientoHasEtiquetaArea #".$requerimiento_id_requerimiento, $etiqueta_area_id_etiqueta_area,
                    'content'=>$this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Save',['class'=>'btn btn-primary','type'=>"submit"])
                ];         
            }else if($model->load($request->post()) && $model->save()){
                return [
                    'forceReload'=>'#crud-datatable-pjax',
                    'title'=> "requerimientoHasEtiquetaArea #".$requerimiento_id_requerimiento, $etiqueta_area_id_etiqueta_area,
                    'content'=>$this->renderAjax('view', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                            Html::a('Edit',['update','requerimiento_id_requerimiento, $etiqueta_area_id_etiqueta_area'=>$requerimiento_id_requerimiento, $etiqueta_area_id_etiqueta_area],['class'=>'btn btn-primary','role'=>'modal-remote'])
                ];    
            }else{
                 return [
                    'title'=> "Update requerimientoHasEtiquetaArea #".$requerimiento_id_requerimiento, $etiqueta_area_id_etiqueta_area,
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
                return $this->redirect(['view', 'requerimiento_id_requerimiento' => $model->requerimiento_id_requerimiento, 'etiqueta_area_id_etiqueta_area' => $model->etiqueta_area_id_etiqueta_area]);
            } else {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        }
    }

    /**
     * Delete an existing requerimientoHasEtiquetaArea model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $requerimiento_id_requerimiento
     * @param integer $etiqueta_area_id_etiqueta_area
     * @return mixed
     */
    public function actionDelete($requerimiento_id_requerimiento, $etiqueta_area_id_etiqueta_area)
    {
        $request = Yii::$app->request;
        $this->findModel($requerimiento_id_requerimiento, $etiqueta_area_id_etiqueta_area)->delete();

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
     * Delete multiple existing requerimientoHasEtiquetaArea model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $requerimiento_id_requerimiento
     * @param integer $etiqueta_area_id_etiqueta_area
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
     * Finds the requerimientoHasEtiquetaArea model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $requerimiento_id_requerimiento
     * @param integer $etiqueta_area_id_etiqueta_area
     * @return requerimientoHasEtiquetaArea the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($requerimiento_id_requerimiento, $etiqueta_area_id_etiqueta_area)
    {
        if (($model = requerimientoHasEtiquetaArea::findOne(['requerimiento_id_requerimiento' => $requerimiento_id_requerimiento, 'etiqueta_area_id_etiqueta_area' => $etiqueta_area_id_etiqueta_area])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
