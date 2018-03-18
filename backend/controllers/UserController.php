<?php

namespace backend\controllers;

use backend\models\TipoUsuario;
use Yii;
use backend\models\user;
use backend\models\search\UserSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;

/**
 * UserController implements the CRUD actions for user model.
 */
class UserController extends Controller
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
                    [
                        'actions' => ['perfil-usuario'],
                        'allow' => true,
                        'roles' => ['alumno'],
                    ],
                    [
                        'actions' => ['perfil-usuario'],
                        'allow' => true,
                        'roles' => ['docente'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all user models.
     * @return mixed
     */
    public function actionIndex()
    {    
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Displays a single user model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {   
        $request = Yii::$app->request;
        if($request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                    'title'=> "user #".$id,
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
     * Creates a new user model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $request = Yii::$app->request;
        $model = new user();  

        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet){
                return [
                    'title'=> "Crear nuevo usuario",
                    'content'=>$this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Guardar',['class'=>'btn btn-primary','type'=>"submit"])
        
                ];         
            }else if($model->load($request->post()) && $model->validate()){
                    $model->setPassword($model->password_hash, 10);
                    $transaction = Yii::$app->db->beginTransaction();
                    try {
                        if ($model->save(false)) { // <- update stock here
                            if(Yii::$app->funcionespropias->asignarRol($model->rol,$model->id)){
                                $transaction->commit();
                                return [
                                    'forceReload' => '#crud-datatable-pjax',
                                    'title' => "Crear nuevo user",
                                    'content' => '<span class="text-success">Usuario creado exitosamente</span>',
                                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                                        Html::a('Crear Más', ['create'], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])

                                ];
                            }else{
                                $transaction->rollBack();
                                return [
                                    'forceReload' => '#crud-datatable-pjax',
                                    'title' => "Crear nuevo user",
                                    'content' => '<span class="text-success">Error al asignar rol.</span>',
                                    'footer' => Html::button('Cerrar', ['class' => 'btn btn-default pull-left', 'data-dismiss' => "modal"]) .
                                        Html::a('Crear Más', ['create'], ['class' => 'btn btn-primary', 'role' => 'modal-remote'])

                                ];
                            }

                        }else{
                            $transaction->rollBack();
                            return [
                                'forceReload'=>'#crud-datatable-pjax',
                                'title'=> "Error",
                                'content'=>'<span class="text-success">Error en crear el usuario</span>',
                                'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                    Html::a('Crear Más',['create'],['class'=>'btn btn-primary','role'=>'modal-remote'])

                            ];
                        }
                    }catch (\Exception $exc) {
                        $transaction->rollBack();
                        return [
                            'forceReload'=>'#crud-datatable-pjax',
                            'title'=> "Error",
                            'content'=>'<span class="text-success">Error en crear el usuario (Rol no Asignado por el Sistema)</span>',
                            'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::a('Crear Más',['create'],['class'=>'btn btn-primary','role'=>'modal-remote'])

                        ];
                    }
                //}
            }else{           
                return [
                    'title'=> "Crear nuevo usuario",
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
            if ($model->load($request->post()) && $model->validate()) {
                $model->setPassword($model->password_hash, 10);
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    if ($model->save(false)) { // <- update stock here
                        if(Yii::$app->funcionespropias->asignarRol($model->rol,$model->id)){
                            $transaction->commit();
                        }else{
                            $transaction->rollBack();
                            echo "Error al asignar rol";
                        }
                        return $this->redirect(['view', 'id' => $model->id]);
                    }else{
                        $transaction->rollBack();
                        return $this->render('create', [
                            'model' => $model,
                        ]);
                    }
                }catch (\Exception $exc) {
                    $transaction->rollBack();
                    return $this->render('create', [
                        'model' => $model,
                    ]);
                }
            } else {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        }
       
    }

    /**
     * Updates an existing user model.
     * For ajax request will return json object
     * and for non-ajax request if update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);
        $model->rol = $model->rolAsignado->item_name;
        $model->password_hash = ""; //Para limpiar el formulario

        if($model) {
            if ($model->username == "179888823" || $model->username == "164455181") {
                Yii::$app->getSession()->setFlash('success-' . $model->username, [
                    'type' => 'error',
                    'duration' => 5000,
                    //'icon' => 'fa fa-users',
                    'message' => 'No puede modificar al administrador RUT: ' . $model->username . '.',
                    'title' => 'Error',
                    'positonY' => 'top',
                    //'positonX' => 'left'
                ]);
                return $this->redirect(['index']);
            }
        }

        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet){
                return [
                    'title'=> "Modificar user #".$id,
                    'content'=>$this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Guardar',['class'=>'btn btn-primary','type'=>"submit"])
                ];         
            }else if($model->load($request->post()) && $model->validate()){
                $model->setPassword($model->password_hash, 10); //AL HACE ESTO, NO PASA LA VALIDACION
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    if ($model->save(false)) { // <- update stock here
                        if(Yii::$app->funcionespropias->asignarRol($model->rol,$model->id)){
                            $transaction->commit();
                            return [
                                'forceReload'=>'#crud-datatable-pjax',
                                'title'=> "user #".$id,
                                'content'=>$this->renderAjax('view', [
                                    'model' => $model,
                                ]),
                                'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                    Html::a('Editar',['update','id'=>$id],['class'=>'btn btn-primary','role'=>'modal-remote'])
                            ];
                        }else{
                            $transaction->rollBack();
                            return [
                                'forceReload'=>'#crud-datatable-pjax',
                                'title'=> "Error",
                                'content'=>'<span class="text-success">Error en crear el usuario (Rol no Asignado por el Sistema)</span>',
                                'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"])
                            ];
                        }

                    }else{
                        $transaction->rollBack();
                        return [
                            'forceReload'=>'#crud-datatable-pjax',
                            'title'=> "Error",
                            'content'=>'<span class="text-success">Error en crear el usuario (Rol no Asignado por el Sistema)</span>',
                            'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"])
                        ];
                    }
                }catch (\Exception $exc) {
                    $transaction->rollBack();
                    return [
                        'forceReload'=>'#crud-datatable-pjax',
                        'title'=> "Error",
                        'content'=>'<span class="text-success">Error en crear el usuario (Rol no Asignado por el Sistema)</span>',
                        'footer'=> Html::button('Cerrar',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"])
                    ];
                }
            }else{
                 return [
                    'title'=> "Modificar user #".$id,
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
            if ($model->load($request->post()) ) {
                $model->setPassword($model->password_hash, 10);
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    if ($model->save(false)) { // <- update stock here
                        if(Yii::$app->funcionespropias->asignarRol($model->rol,$model->id)){
                            $transaction->commit();
                        }else{
                            $transaction->rollBack();
                            echo "Error al asignar rol";
                        }
                        return $this->redirect(['view', 'id' => $model->id]);
                    }else{
                        $transaction->rollBack();
                        return $this->render('update', [
                            'model' => $model,
                        ]);
                    }
                }catch (\Exception $exc) {
                    $transaction->rollBack();
                    return $this->render('update', [
                        'model' => $model,
                    ]);
                }
            } else {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        }//FIN ELSE
    }

    /**
     * Delete an existing user model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $request = Yii::$app->request;
        $modeloABorrar = $this->findModel($id);
        if($modeloABorrar){
            if($modeloABorrar->username == "179888823" || $modeloABorrar->username == "164455181"){
                Yii::$app->getSession()->setFlash('success', [
                    'type' => 'error',
                    'duration' => 5000,
                    //'icon' => 'fa fa-users',
                    'message' => 'No puede eliminar al administrador RUT: '.$modeloABorrar->username.'.',
                    'title' => 'Error',
                    'positonY' => 'top',
                    //'positonX' => 'left'
                ]);
                return $this->redirect(['index']);
            }else{
                $modeloABorrar->delete();
            }
        }
        if($request->isAjax){
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
     * Delete multiple existing user model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionBulkDelete()
    {        
        $request = Yii::$app->request;
        $pks = explode(',', $request->post( 'pks' )); // Array or selected records primary keys
        $huboAdministrador = false;
        foreach ( $pks as $pk ) {
            $model = $this->findModel($pk);
            if($model){
                if($model->username == "179888823" || $model->username == "164455181"){
                    Yii::$app->getSession()->setFlash('success-'.$model->username, [
                        'type' => 'error',
                        'duration' => 5000,
                        //'icon' => 'fa fa-users',
                        'message' => 'No puede eliminar al administrador RUT: '.$model->username.'.',
                        'title' => 'Error',
                        'positonY' => 'top',
                        //'positonX' => 'left'
                    ]);
                    $huboAdministrador = true;
                }else{
                    $model->delete();
                }
            }//FIN IF
        }
        if($huboAdministrador){
            return $this->redirect(['index']);
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
    public function actionPerfilUsuario(){
        $model = $this->findModel(Yii::$app->user->identity->id);
        $model->scenario = User::SCENARIO_PERFIL_USUARIO;

        if ($model->load(Yii::$app->request->post())) {
            if($model->validatePassword($model->password_antigua)){
                $model->setPassword($model->password_nueva, 10);
                if($model->save()){
                    Yii::$app->getSession()->setFlash('success', [
                        'type' => 'success',
                        'duration' => 5000,
                        //'icon' => 'fa fa-users',
                        'message' => 'Contraseña cambiada exitosamente.',
                        'title' => 'Información',
                        'positonY' => 'top',
                        //'positonX' => 'left'
                    ]);
                    return $this->redirect(['/site/index']);
                }else{
                    Yii::$app->getSession()->setFlash('success', [
                        'type' => 'error',
                        'duration' => 5000,
                        //'icon' => 'fa fa-users',
                        'message' => 'Error al guardar la información.',
                        'title' => 'Error',
                        'positonY' => 'top',
                        //'positonX' => 'left'
                    ]);
                    return $this->redirect(['/site/index']);

                }
            }else{
                Yii::$app->getSession()->setFlash('success', [
                    'type' => 'error',
                    'duration' => 5000,
                    //'icon' => 'fa fa-users',
                    'message' => 'La contraseña antigua ingresada no es correcta.',
                    'title' => 'Error',
                    'positonY' => 'top',
                    //'positonX' => 'left'
                ]);


                return $this->render('//user/perfil-usuario/perfilUsuario', [
                    'model' => $model,
                ]);
            }
        }else{
            if($model){
                $model->scenario = User::SCENARIO_PERFIL_USUARIO;

                return $this->render('//user/perfil-usuario/perfilUsuario', [
                    'model' => $model,
                ]);
            }
        }
    }

    /**
     * Finds the user model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return user the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = user::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('La página solicitada no existe.');
        }
    }

    private function lanzarFlash($model){
        Yii::$app->getSession()->setFlash('success-' . $model->username, [
            'type' => 'error',
            'duration' => 5000,
            //'icon' => 'fa fa-users',
            'message' => 'No puede modificar al administrador RUT: ' . $model->username . '.',
            'title' => 'Error',
            'positonY' => 'top',
            //'positonX' => 'left'
        ]);
    }
}
