<?php

namespace backend\controllers;

use backend\models\ContactoScb;
use backend\models\Model;
use Yii;
use backend\models\scb;
use backend\models\search\ScbSearch;
use yii\base\Exception;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;

/**
 * ScbController implements the CRUD actions for scb model.
 */
class ScbController extends Controller
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
     * Lists all scb models.
     * @return mixed
     */
    public function actionIndex()
    {    
        $searchModel = new ScbSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Displays a single scb model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {   
        $request = Yii::$app->request;
        if($request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                    'title'=> "scb #".$id,
                    'content'=>$this->renderAjax('view', [
                        'model' => $this->findModel($id),
                    ]),
                    'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                            Html::a('Editar',['update','id'=>$id],['class'=>'btn btn-primary','role'=>'modal-remote'])
                ];    
        }else{
            $model = $this->findModel($id);
            $modelContactoScb = $model->getContactoScbs();

            $dataProviderContactoScb = new ActiveDataProvider([
                'query' => $modelContactoScb,
                'pagination' => [
                    'pageSize' => 20,
                ],
            ]);

            return $this->render('view', [
                'model' => $model,
                'dataProviderContactoScb' => $dataProviderContactoScb,
            ]);
        }
    }

    /**
     * Creates a new scb model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $request = Yii::$app->request;
        $model = new scb();
        $modelsContactoScb = [new ContactoScb()];

        if ($model->load(Yii::$app->request->post())) {
            $modelsContactoScb = Model::createMultiple(contactoScb::classname());
            Model::loadMultiple($modelsContactoScb, Yii::$app->request->post());

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
            $valid = Model::validateMultiple($modelsContactoScb) && $valid;

            if ($valid) {
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    if ($flag = $model->save(false)) {
                        foreach ($modelsContactoScb as $modelContactoScb) {
                            $modelContactoScb->scb_id_scb = $model->id_scb;
                            if (! ($flag = $modelContactoScb->save(false))) {
                                $transaction->rollBack();
                                break;
                            }
                        }
                    }
                    if ($flag) {
                        $transaction->commit();
                        return $this->redirect(['view', 'id' => $model->id_scb]);
                    }
                } catch (Exception $e) {
                    $transaction->rollBack();
                }
            }
        }//FIN IF POST
        return $this->render('create', [
            'model' => $model,
            'modelsContactoScb' => (empty($modelsContactoScb)) ? [new contactoScb] : $modelsContactoScb,
        ]);

       
    }

    /**
     * Updates an existing scb model.
     * For ajax request will return json object
     * and for non-ajax request if update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $modelsContactoScb = $model->contactoScbs;

        if ($model->load(Yii::$app->request->post())) {

            $oldIDs2 = ArrayHelper::map($modelsContactoScb, 'id_contacto_scb', 'id_contacto_scb');

            $modelsContactoScb = Model::createMultiple(contactoScb::classname(), $modelsContactoScb);
            Model::loadMultiple($modelsContactoScb, Yii::$app->request->post());

            $deletedIDs2 = array_diff($oldIDs2, array_filter(ArrayHelper::map($modelsContactoScb, 'id_contacto_scb', 'id_contacto_scb')));

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
            $valid = Model::validateMultiple($modelsContactoScb) && $valid;

            if ($valid) {
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    if ($flag = $model->save(false)) {
                        if (! empty($deletedIDs2)) {
                            contactoScb::deleteAll(['id_contacto_scb' => $deletedIDs2]);
                        }
                        foreach ($modelsContactoScb as $modelContactoScb) {
                            $modelContactoScb->scb_id_scb = $model->id_scb;
                            if (! ($flag = $modelContactoScb->save(false))) {
                                $transaction->rollBack();
                                break;
                            }
                        }
                    }
                    if ($flag) {
                        $transaction->commit();
                        return $this->redirect(['view', 'id' => $model->id_scb]);
                    }
                } catch (Exception $e) {
                    $transaction->rollBack();
                }
            }
        }

        return $this->render('update', [
            'model' => $model,
            'modelsContactoScb' => (empty($modelsContactoScb)) ? [new contactoScb] : $modelsContactoScb,
        ]);
    }

    /**
     * Delete an existing scb model.
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
     * Delete multiple existing scb model.
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
     * Finds the scb model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return scb the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = scb::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('La página solicitada no existe.');
        }
    }
}
