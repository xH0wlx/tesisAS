<?php

namespace backend\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "user".
 *
 * @property string $id
 * @property string $username
 * @property string $auth_key
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property integer $estado_id
 * @property integer $rol_id
 * @property integer $tipo_usuario_id
 * @property string $created_at
 * @property string $updated_at
 * @property string $verification_code
 */
class User extends \common\models\User
{
    const SCENARIO_PERFIL_USUARIO = 'perfil_usuario';

    public $rol;
    //public $password;
    public $password_nueva;
    public $password_antigua;
    public $password_repeat;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', \sateler\rut\RutValidator::className()],
            ['rol', 'safe'],
            [['username', /*'auth_key',*/ 'password_hash', 'email', 'nombre_completo','password_repeat', 'rol'], 'required', 'except' => self::SCENARIO_PERFIL_USUARIO ],
            [['password_antigua','password_nueva','password_repeat'], 'safe'],
            [['password_antigua','password_nueva','password_repeat'], 'required', 'on' => self::SCENARIO_PERFIL_USUARIO ],
            [['estado_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            ['password_hash','string','min' => 6],
            ['password_nueva','string','min' => 6,'on' => self::SCENARIO_PERFIL_USUARIO ],
            [['username', 'password_hash', 'password_reset_token', 'email'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32],
            [['verification_code'], 'string', 'max' => 250],
            [['username'], 'unique'],
            [['email'], 'unique'],
            [['email'], 'email'],
            //[['email'], 'unique'],
            [['password_reset_token'], 'unique'],
            ['password_repeat', 'compare', 'compareAttribute' => 'password_hash', 'message' => 'Los passwords no coinciden','except' => self::SCENARIO_PERFIL_USUARIO],
            ['password_repeat', 'compare', 'compareAttribute' => 'password_nueva', 'message' => 'Los passwords no coinciden','on' => self::SCENARIO_PERFIL_USUARIO],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'RUT (Nombre de Usuario)',
            'auth_key' => 'Auth Key',
            'password_hash' => 'Contraseña',//
            'password_reset_token' => 'Password Reset Token',
            'nombre_completo' => "Nombre Completo",
            'email' => 'Email',
            'estado_id' => 'Estado',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'verification_code' => 'Verification Code',
            'password_nueva'=>'Nueva contraseña',
            'password_antigua'=>'Contraseña antigua',
            'password_repeat'=>'Repetir contraseña',
        ];
    }
    /*
        public function beforeSave($insert)
        {
            if (parent::beforeSave($insert)) {
                    if (isset($this->password_hash)) {
                        $this->setPassword($this->password_hash);
                        return true;
                    } else {
                        return false;
                    }

            } else {
                return false;
            }
        }

        public function afterSave($insert, $changedAttributes)
        {
            parent::afterSave($insert, $changedAttributes);
            $this->CompruebaSiSeAsignoRol($insert, $changedAttributes);

        }*/

    public function getRolAsignado()
    {
        return $this->hasOne(AuthAssignment::className(), ['user_id' => 'id']);
    }

    public function getRolesLista(){
        $droptions = AuthItem::find()->asArray()->all();
        return ArrayHelper::map($droptions, 'name', 'name');
    }
    private function CompruebaSiSeAsignoRol($insert, $changedAttributes){
        //COMPRUEBA QUE SE HAYA ASIGNADO CORRECTAMENTE EL ROL
        $modelo = TipoUsuario::findOne($this->tipo_usuario_id);
        //SI ES NUEVO
        if($insert){
            $nombre_rol = $modelo->nombre_rol;
            $auth = \Yii::$app->authManager;
            $auth->revokeAll($this->id);

            $role = $auth->getRole($nombre_rol);
            if($auth->assign($role, $this->id)){
                return true; // do some otherthings
            }else{
                throw new \yii\base\Exception( "Error al asignar Rol." );
            }
        }else{
            if(array_key_exists('tipo_usuario_id', $changedAttributes)){
                $modeloViejo = TipoUsuario::findOne($changedAttributes["tipo_usuario_id"]);
                if($modelo){
                    if($insert) {
                        $nombre_rol = $modelo->nombre_rol;
                        $auth = \Yii::$app->authManager;
                        $role = $auth->getRole($nombre_rol);
                        if($auth->assign($role, $this->id)){
                            return true; // do some otherthings
                        }else{
                            throw new \yii\base\Exception( "Error al asignar Rol." );
                        }
                    }else{
                        if($modeloViejo && !$insert){
                            $nombre_rol = $modeloViejo->nombre_rol;
                            $auth = \Yii::$app->authManager;
                            $role = $auth->getRole($nombre_rol);
                            //$auth->revoke($role, $this->id);
                            $auth->revokeAll($this->id);

                            $nombre_rol = $modelo->nombre_rol;
                            $role = $auth->getRole($nombre_rol);

                            if($auth->assign($role, $this->id)){
                                return true; // do some otherthings
                            }else{
                                throw new \yii\base\Exception( "Error al asignar Rol." );
                            }
                        }else{
                            throw new \yii\base\Exception( "Error al asignar Rol." );
                        }

                    }//FIN ELSE
                }else{
                    throw new \yii\base\Exception( "Error al asignar Rol." );
                }

            }// FIN COMPROBACIÓN  ROL
        }

        throw new \yii\base\Exception( "Excepción al final de la función." );

    }//FIN AFTER SAVE
}
