<?php

namespace backend\controllers;

use backend\models\ContactoWeb;
use backend\models\search\RequerimientoSearch;
use kartik\helpers\Html;
use Yii;
use backend\models\sci;
use backend\models\ContactoSci;
use backend\models\Requerimiento;

use backend\models\search\SciSearch;
use yii\base\Exception;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use backend\models\Model;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\Response;
use yii\widgets\ActiveForm;
use yii\data\ActiveDataProvider;

/**
 * SciController implements the CRUD actions for sci model.
 */
class SciController extends Controller
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
                    'delete' => ['POST'],
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
        $searchModel = new SciSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('principal', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Lists all sci models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SciSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionViewAjax($id)
    {
        $request = Yii::$app->request;
        if($request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title'=> "Socio Inst. #".$id,
                'content'=>$this->renderAjax('_viewLimpio', [
                    'model' => $this->findModel($id),
                ]),
                'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"])//.
                    //Html::a('Editar',['update','id'=>$id],['class'=>'btn btn-primary','role'=>'modal-remote'])
            ];
        }
    }
    /**
     * Displays a single sci model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $modelSci = $this->findModel($id);
        $modelContactoSci = $modelSci->getContactoScis();
        $modelRequerimiento = $modelSci->getRequerimientos();

        $dataProviderContactoSci = new ActiveDataProvider([
            'query' => $modelContactoSci,
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        $dataProviderRequerimiento = new ActiveDataProvider([
            'query' => $modelRequerimiento,
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        return $this->render('view', [
            'model' => $this->findModel($id),
            'modelContactoSci' => $modelContactoSci,
            'modelRequerimiento' => $modelRequerimiento,
            'dataProviderContactoSci' => $dataProviderContactoSci,
            'dataProviderRequerimiento' => $dataProviderRequerimiento,
        ]);
    }

    /**
     * Creates a new sci model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new sci();
        $modelsContactoWeb = [new contactoWeb];
        $modelsRequerimiento = [new requerimiento];
        $modelsContactoSci = [new contactoSci];

        if (false/*Yii::$app->request->isAjax*/) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return //ArrayHelper::merge(
                ActiveForm::validateMultiple($modelsRequerimiento);
                //ActiveForm::validate($model)
            //);
        }else{

        if ($model->load(Yii::$app->request->post())) {
            $modelsContactoSci = Model::createMultiple(contactoSci::classname());
            Model::loadMultiple($modelsContactoSci, Yii::$app->request->post());

            $modelsRequerimiento = Model::createMultiple(requerimiento::classname());
            Model::loadMultiple($modelsRequerimiento, Yii::$app->request->post());

            $modelsContactoWeb = Model::createMultiple(contactoWeb::classname());
            Model::loadMultiple($modelsContactoWeb, Yii::$app->request->post());

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
            $valid = Model::validateMultiple($modelsContactoWeb) && Model::validateMultiple($modelsContactoSci)
                && Model::validateMultiple($modelsRequerimiento) && $valid;

            if ($valid) {
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    if ($flag = $model->save(false)) {
                        foreach ($modelsContactoSci as $modelContactoSci) {
                            $modelContactoSci->sci_id_sci = $model->id_sci;
                            if (! ($flag = $modelContactoSci->save(false))) {
                                $transaction->rollBack();
                                break;
                            }
                        }

                        foreach ($modelsContactoWeb as $modelContactoWeb) {
                            $modelContactoWeb->sci_id_sci = $model->id_sci;
                            if (! ($flag = $modelContactoWeb->save(false))) {
                                $transaction->rollBack();
                                break;
                            }
                        }

                        foreach ($modelsRequerimiento as $modelRequerimiento) {
                            $modelRequerimiento->sci_id_sci = $model->id_sci;
                            $modelRequerimiento->estado_ejecucion_id_estado = 1;
                            if (! ($flag = $modelRequerimiento->save(false))) {
                                $transaction->rollBack();
                                break;
                            }
                        }
                    }
                    if ($flag) {
                        $transaction->commit();
                        return $this->redirect(['view', 'id' => $model->id_sci]);
                    }
                } catch (Exception $e) {
                    $transaction->rollBack();
                }
            }
        }

        return $this->render('create', [
            'model' => $model,
            'modelsRequerimiento' => (empty($modelsRequerimiento)) ? [new requerimiento] : $modelsRequerimiento,
            'modelsContactoSci' => (empty($modelsContactoSci)) ? [new contactoSci] : $modelsContactoSci,
            'modelsContactoWeb' => (empty($modelsContactoWeb)) ? [new contactoWeb] : $modelsContactoWeb,
        ]);
        }//ELSE DE LA VERIFICACION POR AJAX
    }

    /**
     * Updates an existing sci model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $modelsContactoSci = $model->contactoScis;
        $modelsContactoWeb = $model->contactoWebs;
        $modelsRequerimiento = $model->requerimientos;

        if ($model->load(Yii::$app->request->post())) {

            $oldIDs = ArrayHelper::map($modelsRequerimiento, 'id_requerimiento', 'id_requerimiento');
            $oldIDs2 = ArrayHelper::map($modelsContactoSci, 'id_contacto_sci', 'id_contacto_sci');
            $oldIDs3 = ArrayHelper::map($modelsContactoWeb, 'id_contacto_web', 'id_contacto_web');


            $modelsRequerimiento = Model::createMultiple(Requerimiento::classname(), $modelsRequerimiento);
            Model::loadMultiple($modelsRequerimiento, Yii::$app->request->post());

            $modelsContactoSci = Model::createMultiple(contactoSci::classname(), $modelsContactoSci);
            Model::loadMultiple($modelsContactoSci, Yii::$app->request->post());

            $modelsContactoWeb = Model::createMultiple(contactoWeb::classname(), $modelsContactoWeb);
            Model::loadMultiple($modelsContactoWeb, Yii::$app->request->post());

            $deletedIDs = array_diff($oldIDs, array_filter(ArrayHelper::map($modelsRequerimiento, 'id_requerimiento', 'id_requerimiento')));
            $deletedIDs2 = array_diff($oldIDs2, array_filter(ArrayHelper::map($modelsContactoSci, 'id_contacto_sci', 'id_contacto_sci')));
            $deletedIDs3 = array_diff($oldIDs3, array_filter(ArrayHelper::map($modelsContactoWeb, 'id_contacto_web', 'id_contacto_web')));

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
            $valid = Model::validateMultiple($modelsContactoWeb) && Model::validateMultiple($modelsContactoSci)
                && Model::validateMultiple($modelsRequerimiento) && $valid;

            if ($valid) {
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    if ($flag = $model->save(false)) {
                        if (! empty($deletedIDs)) {
                            requerimiento::deleteAll(['id_requerimiento' => $deletedIDs]);
                        }
                        foreach ($modelsRequerimiento as $modelRequerimiento) {
                            $modelRequerimiento->sci_id_sci = $model->id_sci;
                            if (! ($flag = $modelRequerimiento->save(false))) {
                                $transaction->rollBack();
                                break;
                            }
                        }

                        if (! empty($deletedIDs2)) {
                            contactoSci::deleteAll(['id_contacto_sci' => $deletedIDs2]);
                        }
                        foreach ($modelsContactoSci as $modelContactoSci) {
                            $modelContactoSci->sci_id_sci = $model->id_sci;
                            if (! ($flag = $modelContactoSci->save(false))) {
                                $transaction->rollBack();
                                break;
                            }
                        }

                        if (! empty($deletedIDs3)) {
                            contactoWeb::deleteAll(['id_contacto_web' => $deletedIDs3]);
                        }
                        foreach ($modelsContactoWeb as $modelContactoWeb) {
                            $modelContactoWeb->sci_id_sci = $model->id_sci;
                            if (! ($flag = $modelContactoWeb->save(false))) {
                                $transaction->rollBack();
                                break;
                            }
                        }
                    }
                    if ($flag) {
                        $transaction->commit();
                        return $this->redirect(['view', 'id' => $model->id_sci]);
                    }
                } catch (Exception $e) {
                    $transaction->rollBack();
                }
            }
        }

        return $this->render('update', [
            'model' => $model,
            'modelsRequerimiento' => (empty($modelsRequerimiento)) ? [new requerimiento()] : $modelsRequerimiento,
            'modelsContactoSci' => (empty($modelsContactoSci)) ? [new contactoSci] : $modelsContactoSci,
            'modelsContactoWeb' => (empty($modelsContactoWeb)) ? [new contactoWeb] : $modelsContactoWeb,
        ]);
    }

    /**
     * Deletes an existing sci model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
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
     * Finds the sci model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return sci the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = sci::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
