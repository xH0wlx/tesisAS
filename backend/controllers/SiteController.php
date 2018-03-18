<?php
namespace backend\controllers;

use backend\models\AnioSemestre;
use backend\models\Match1;
use backend\models\TipoUsuario;
use Yii;
use yii\base\ErrorException;
use yii\base\Exception;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\Session;
use backend\models\FormRecoverPass;
use backend\models\FormResetPass;
use common\models\User;

use yii\helpers\Url;
use yii\rbac\DbManager;

use common\models\LoginForm;


/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        //ESTO PARA EL USUARIO NO REGISTRADO PARA TODOS
                        'actions' => ['login', 'recoverpass', 'resetpass'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        //ESTO ES PARA EL USUARIO REGISTRADO
                        'actions' => ['login','logout', 'error', 'index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        //ESTO ES PARA EL USUARIO REGISTRADO
                        //'actions' => ['usuario'],
                        'allow' => true,
                        'roles' => ['coordinador general'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        //Yii::$app->funcionespropias->welcome();

        if(Yii::$app->user->can("alumno")){
            //return $this->render('error', ['name' => 'prueba', 'message' => 'prueba']);
            return $this->render('indexAlumno');
        }else if(Yii::$app->user->can("coordinador general")){
            $modelPeriodo = new AnioSemestre();

            $arregloResupuesta = $this->VerificarPeriodoIniciado();
            if($arregloResupuesta["codigo"] == "exito"){
                $modelPeriodo->anio = $arregloResupuesta["anio"];
                $modelPeriodo->semestre = $arregloResupuesta["semestre"];
            }else{

            }
            $session = Yii::$app->session;
            $session->open();
            if(!$session->has('statusSaludo')){
                $this->Saludo();
            }
            $session->close();


            $count = Yii::$app->db->createCommand('SELECT COUNT(*) FROM requerimiento WHERE estado_ejecucion_id_estado = 1')->queryScalar();
            $contSociosSinRequerimientos =  Yii::$app->db->createCommand('SELECT COUNT(*) FROM `sci` WHERE id_sci NOT IN (SELECT sci_id_sci FROM requerimiento)')->queryScalar();

            return $this->render('index', [
                'modelPeriodo' => $modelPeriodo,
                'contRequerimientosNoAsignados' => $count,
                'contSociosSinRequerimientos' => $contSociosSinRequerimientos,
            ]);
        }else if(Yii::$app->user->can("docente")){
            return $this->render('indexDocente');
            //return $this->render('error', ['name' => 'prueba', 'message' => 'prueba']);
        }//FIN SITE

    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        try {
            if ($model->load(Yii::$app->request->post()) && $model->login()) {
                return $this->goBack();
            } else {
                return $this->render('login', [
                    'model' => $model,
                ]);
            }
        }catch (Exception $e){
            return $this->render('error', ['name' => 'Error', 'message' => 'Error al conectar a la base de datos.']);
        }
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionUsuario()
    {
        //$r = new DbManager;
        //$r->init();
        //$r->assign($test, 3);


        //$variable = $r->getUserIdsByRole('test');
        //$variable = $r->getAssignments('3');
        $variable = Yii::$app->user->getIdentity();

        return $this->render('usuario', ['variable' => $variable]);
    }

    private function randKey($str='', $long=0){
        $key = null;
        $str = str_split($str);
        $start = 0;
        $limit = count($str)-1;
        for ($x=0;$x<$long;$x++){
            $key .= $str[rand($start, $limit)];
        }
        return $key;

    }

    public function actionRecoverpass()
    {
        //Instancia para validar el formulario
        $model = new FormRecoverPass;

        //Mensaje que será mostrado al usuario en la vista
        $msg = null;

        if ($model->load(Yii::$app->request->post()))
        {
            if ($model->validate())
            {

                //Buscar al usuario a través del email
                $table = User::find()->where("email=:email", [":email" => $model->email])->one();

                //Si el usuario existe
                if ($table)
                {
                    //Crear variables de sesión para limitar el tiempo de restablecido del password
                    //hasta que el navegador se cierre
                    $session = new Session;
                    $session->open();

                    //Esta clave aleatoria se cargará en un campo oculto del formulario de reseteado
                    $session["recover"] = $this->randKey("abcdef0123456789", 200);
                    $recover = $session["recover"];

                    //También almacenaremos el id del usuario en una variable de sesión
                    //El id del usuario es requerido para generar la consulta a la tabla users y
                    //restablecer el password del usuario
                    //$table = User::find()->where("email=:email", [":email" => $model->email])->one();
                    $session["id_recover"] = $table->id;

                    //Esta variable contiene un número hexadecimal que será enviado en el correo al usuario
                    //para que lo introduzca en un campo del formulario de reseteado
                    //Es guardada en el registro correspondiente de la tabla users
                    $verification_code = $this->randKey("abcdef0123456789", 8);
                    //Columna verification_code
                    $table->verification_code = $verification_code;
                    //Guardamos los cambios en la tabla users
                    if($table->save(false)){
                        //Creamos el mensaje que será enviado a la cuenta de correo del usuario
                        $subject = "Recuperar Contraseña";
                        $body = "<p>Copie el siguiente código de verificación para restablecer su contraseña ... ";
                        $body .= "<strong>".$verification_code."</strong></p>";
                        $body .= "<p><a href='http://backend.tesisas.com/site/resetpass'>Recuperar password</a></p>";

                        //Enviamos el correo
                        Yii::$app->mailer->compose()
                            ->setTo($model->email)
                            ->setFrom([Yii::$app->params["adminEmail"] => "Programa Aprendizaje Servicio"])
                            ->setSubject($subject)
                            ->setHtmlBody($body)
                            ->send();

                        //Vaciar el campo del formulario
                        $model->email = null;

                        //Mostrar el mensaje al usuario
                        $msg = "Le hemos enviado un mensaje a su cuenta de correo para que pueda reiniciar su contraseña";
                    }else{
                        $msg = "Hubo un error al guardar la información en la base de datos.";
                    }
                }
                else //El usuario no existe
                {
                    $msg = "Ha ocurrido un error";
                }
            }
            else
            {
                $model->getErrors();
            }
        }
        return $this->render("recoverpass", ["model" => $model, "msg" => $msg]);
    }

    public function actionResetpass()
    {
        //Instancia para validar el formulario
        $model = new FormResetPass;

        //Mensaje que será mostrado al usuario
        $msg = null;

        //Abrimos la sesión
        $session = new Session;
        $session->open();

        //Si no existen las variables de sesión requeridas lo expulsamos a la página de inicio
        if (empty($session["recover"]) || empty($session["id_recover"]))
        {
            Yii::$app->getSession()->setFlash('success', [
                'type' => 'error',
                'duration' => 5000,
                //'icon' => 'fa fa-users',
                'message' => 'Hubo un error al momento de reiniciar tu contraseña, intente nuevamente.',
                'title' => 'Error',
                'positonY' => 'top',
                //'positonX' => 'left'
            ]);
            return $this->redirect(["site/index"]);
        }
        else
        {

            $recover = $session["recover"];
            //El valor de esta variable de sesión la cargamos en el campo recover del formulario
            $model->recover = $recover;

            //Esta variable contiene el id del usuario que solicitó restablecer el password
            //La utilizaremos para realizar la consulta a la tabla users
            $id_recover = $session["id_recover"];

        }

        //Si el formulario es enviado para resetear el password
        if ($model->load(Yii::$app->request->post()))
        {
            if ($model->validate())
            {
                //Si el valor de la variable de sesión recover es correcta
                if ($recover == $model->recover)
                {
                    //Preparamos la consulta para resetear el password, requerimos el email, el id
                    //del usuario que fue guardado en una variable de session y el código de verificación
                    //que fue enviado en el correo al usuario y que fue guardado en el registro
                    $table = User::findOne(["email" => $model->email, "id" => $id_recover, "verification_code" => $model->verification_code]);
                    if($table){
                        //Encriptar el password
                        //$table->password = crypt($model->password, Yii::$app->params["salt"]);
                        $table->setPassword($model->password);

                        //Si la actualización se lleva a cabo correctamente
                        if ($table->save(false))
                        {

                            //Destruir las variables de sesión
                            $session->destroy();

                            //Vaciar los campos del formulario
                            $model->email = null;
                            $model->password = null;
                            $model->password_repeat = null;
                            $model->recover = null;
                            $model->verification_code = null;

                            $msg = "Enhorabuena, contraseña reiniciada correctamente, redireccionando a la página de login ...";
                            $msg .= "<meta http-equiv='refresh' content='3; ".Url::toRoute("site/login")."'>";
                        }
                        else
                        {
                            $msg = "Ha ocurrido un error";
                        }

                    }else{
                        $msg = "Ha ocurrido un error en la validación de sus datos.";
                    }
                }
                else
                {
                    $model->getErrors();
                }
            }
        }

        return $this->render("resetpass", ["model" => $model, "msg" => $msg]);

    }

    private function VerificarPeriodoIniciado(){
            $modelMax = Match1::find()->orderBy('anio_match1 DESC, semestre_match1 DESC')->one();
            $max = 0; //CASO BASE
            $maxSemestre = 0;
            if($modelMax){
                $max = $modelMax->anio_match1;
                $maxSemestre = $modelMax->semestre_match1;
                return [
                    'anio'=> $max,
                    'semestre'=> $maxSemestre,
                    'codigo' => "exito",
                ];
            }else{
                return [
                    'codigo' => "error",
                ];
            }
    }

    private function Saludo(){
        Yii::$app->getSession()->setFlash('success', [
            'type' => 'success',
            'duration' => 5000,
            //'icon' => 'fa fa-users',
            'message' => 'Bienveni@ '.Yii::$app->user->identity->nombre_completo,
            'title' => 'Saludo',
            'positonY' => 'top',
            //'positonX' => 'left'
        ]);
        $session = Yii::$app->session;
        $session->open();
        $session['statusSaludo'] = 'yes';
        $session->close();
    }
}
