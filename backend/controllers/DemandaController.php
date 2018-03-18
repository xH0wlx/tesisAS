<?php

namespace backend\controllers;

use Yii;
use backend\models\demanda;
use backend\models\requerimiento;
use backend\models\Model;
use backend\models\search\DemandaSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;

/**
 * DemandaController implements the CRUD actions for demanda model.
 */
class DemandaController extends Controller
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
     * Lists all demanda models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DemandaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        //$dataProvider->pagination->pageSize = 1;

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single demanda model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new demanda model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new demanda();
        $modelsRequerimiento = [new requerimiento()];

        if ($model->load(Yii::$app->request->post())) {

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
            $valid = $model->validate();
            $valid = Model::validateMultiple($modelsRequerimiento) && $valid;

            if ($valid) {
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    if ($flag = $model->save(false)) {
                        foreach ($modelsRequerimiento as $modelRequerimiento) {
                            $modelRequerimiento->demanda_id_demanda = $model->id_demanda;
                            if (! ($flag = $modelRequerimiento->save(false))) {
                                $transaction->rollBack();
                                break;
                            }
                        }
                    }
                    if ($flag) {
                        $transaction->commit();
                        return $this->redirect(['view', 'id' => $model->id_demanda]);
                    }
                } catch (Exception $e) {
                    $transaction->rollBack();
                }
            }
        }

        return $this->render('create', [
            'model' => $model,
            'modelsRequerimiento' => (empty($modelsRequerimiento)) ? [new requerimiento()] : $modelsRequerimiento,
        ]);
    }

    /**
     * Updates an existing demanda model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $modelsRequerimiento = $model->requerimientos;

        if ($model->load(Yii::$app->request->post())) {

            $oldIDs = ArrayHelper::map($modelsRequerimiento, 'id_requerimiento', 'id_requerimiento');
            $modelsRequerimiento = Model::createMultiple(Requerimiento::classname(), $modelsRequerimiento);
            Model::loadMultiple($modelsRequerimiento, Yii::$app->request->post());
            $deletedIDs = array_diff($oldIDs, array_filter(ArrayHelper::map($modelsRequerimiento, 'id_requerimiento', 'id_requerimiento')));

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
            $valid = Model::validateMultiple($modelsRequerimiento) && $valid;

            if ($valid) {
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    if ($flag = $model->save(false)) {
                        if (! empty($deletedIDs)) {
                            requerimiento::deleteAll(['id_requerimiento' => $deletedIDs]);
                        }
                        foreach ($modelsRequerimiento as $modelRequerimiento) {
                            $modelRequerimiento->demanda_id_demanda = $model->id_demanda;
                            if (! ($flag = $modelRequerimiento->save(false))) {
                                $transaction->rollBack();
                                break;
                            }
                        }
                    }
                    if ($flag) {
                        $transaction->commit();
                        return $this->redirect(['view', 'id' => $model->id_demanda]);
                    }
                } catch (Exception $e) {
                    $transaction->rollBack();
                }
            }
        }

        return $this->render('update', [
            'model' => $model,
            'modelsRequerimiento' => (empty($modelsRequerimiento)) ? [new requerimiento()] : $modelsRequerimiento,
        ]);

    }

    /**
     * Deletes an existing demanda model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        //$this->findModel($id)->delete();
        $model = $this->findModel($id);
        $name = $model->perfil_estudiante;

        if ($model->delete()) {
            Yii::$app->session->setFlash('success', 'El registro  <strong>"' . $name . '"</strong> se eliminó correctamente.');
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the demanda model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return demanda the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = demanda::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
