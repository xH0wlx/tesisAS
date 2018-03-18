<?php

namespace backend\controllers;

use Yii;
use backend\models\asignatura;
use backend\models\search\AsignaturaSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use backend\models\Facultad;
use backend\models\Carrera;
use yii\helpers\Json;
use \yii\web\Response;
use yii\helpers\Html;


/**
 * AsignaturaController implements the CRUD actions for asignatura model.
 */
class AsignaturaController extends Controller
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

    /**
     * Lists all asignatura models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AsignaturaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
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
     * Displays a single asignatura model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $request = Yii::$app->request;
        if($request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title'=> "asignatura #".$id,
                'content'=>$this->renderAjax('viewLimpio', [
                    'model' => $this->findModel($id),
                ]),
                'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                    Html::a('Editar',['update','id'=>$id],['class'=>'btn btn-primary','role'=>'modal-remoteX'])
            ];
        }else{
            return $this->render('view', [
                'model' => $this->findModel($id),
            ]);
        }
    }

    /**
     * Creates a new asignatura model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $request = Yii::$app->request;
        $model = new asignatura();

        if(false/*$request->isAjax*/){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet){
                return [
                    'title'=> "Create new carrera",
                    'content'=>$this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                        Html::button('Save',['class'=>'btn btn-primary','type'=>"submit"])

                ];
            }else if($model->load($request->post()) && $model->save()){
                return [
                    'forceReload'=>'#crud-datatable-pjax',
                    'title'=> "Create new carrera",
                    'content'=>'<span class="text-success">Create carrera success</span>',
                    'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                        Html::a('Create More',['create'],['class'=>'btn btn-primary','role'=>'modal-remote'])

                ];
            }else{
                return [
                    'title'=> "Create new carrera",
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
                return $this->redirect(['view', 'id' => $model->cod_asignatura]);
            } else {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        }
    }

    /**
     * Updates an existing asignatura model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);

        if(false/*$request->isAjax*/){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet){
                return [
                    'title'=> "Update carrera #".$id,
                    'content'=>$this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                        Html::button('Save',['class'=>'btn btn-primary','type'=>"submit"])
                ];
            }else if($model->load($request->post()) && $model->save()){
                return [
                    'forceReload'=>'#crud-datatable-pjax',
                    'title'=> "carrera #".$id,
                    'content'=>$this->renderAjax('view', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                        Html::a('Edit',['update','id'=>$id],['class'=>'btn btn-primary','role'=>'modal-remote'])
                ];
            }else{
                return [
                    'title'=> "Update carrera #".$id,
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
                return $this->redirect(['view', 'id' => $model->cod_asignatura]);
            } else {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        }
    }

    /**
     * Deletes an existing asignatura model.
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
     * Finds the asignatura model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return asignatura the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = asignatura::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionSubfacultades(){
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if ($parents != null) {
                if(!empty($_POST['depdrop_params'])){
                    $inputFacultad = $parents[0];
                    $params = $_POST['depdrop_params'];
                    $param1 = $params[0]; //id FACULTAD
                    $out = Facultad::find()->select(['id_facultad as id', 'nombre_facultad as name'])->where(['sede_id_sede' => $inputFacultad])->asArray()->all();
                    $selected = $param1;
                    echo Json::encode(['output'=>$out, 'selected'=>$selected]);
                    return;
                }else{
                    $inputFacultad = $parents[0]; //ID SEDE
                    $out = Facultad::find()->select(['id_facultad as id', 'nombre_facultad as name'])->where(['sede_id_sede' => $inputFacultad])->asArray()->all();
                    echo Json::encode(['output'=>$out, 'selected'=>'']);
                    return;
                }
            }
        }
        echo Json::encode(['output'=>'', 'selected'=>'']);
    }

    public function actionSubcarreras(){
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if ($parents != null) {
                if (!empty($_POST['depdrop_params'])) {
                    $inputCarrera = $parents[0];
                    $params = $_POST['depdrop_params'];
                    $param1 = $params[0]; //ID CARRERA
                    $out = Carrera::find()->select(['cod_carrera as id', 'nombre_carrera as name'])->where(['facultad_id_facultad' => $inputCarrera])->asArray()->all();
                    $selected = $param1;
                    echo Json::encode(['output'=>$out, 'selected'=>$selected]);
                    return;
                }else{
                    $inputCarrera = $parents[0]; //ID FACULTAD
                    $out = Carrera::find()->select(['cod_carrera as id', 'nombre_carrera as name'])->where(['facultad_id_facultad' => $inputCarrera])->asArray()->all();
                    echo Json::encode(['output'=>$out, 'selected'=>'']);
                }
                return;
            }
        }
        echo Json::encode(['output'=>'', 'selected'=>'']);
    }

    public function actionSubasignaturas(){
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if ($parents != null) {
                if (!empty($_POST['depdrop_params'])) {
                    $inputAsignatura = $parents[0];
                    $params = $_POST['depdrop_params'];
                    $param1 = $params[0]; //ID ASIGNATURA
                    $out = Asignatura::find()->select(['cod_asignatura as id', 'nombre_asignatura as name'])->where(['carrera_cod_carrera' => $inputAsignatura])->asArray()->all();
                    $selected = $param1;
                    echo Json::encode(['output'=>$out, 'selected'=>$selected]);
                    return;
                }else{
                    $inputAsignatura = $parents[0]; //ID ASIGNATURA
                    $out = Asignatura::find()->select(['cod_asignatura as id', 'nombre_asignatura as name'])->where(['carrera_cod_carrera' => $inputAsignatura])->asArray()->all();
                    echo Json::encode(['output'=>$out, 'selected'=>'']);
                }
                return;
            }
        }
        echo Json::encode(['output'=>'', 'selected'=>'']);
    }
}
