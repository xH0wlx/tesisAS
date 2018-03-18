<?php
namespace common\models;

use backend\models\TipoUsuario;
use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use yii\web\IdentityInterface;
use yii\base\Security;
/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $auth_key
 * @property integer $rol_id
 * @property integer $estado_id
 * @property integer $tipo_usuario_id
 * @property string $verification_code
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 */
class User extends ActiveRecord implements IdentityInterface
{
    const ESTADO_ACTIVO = 1;

    public static function tableName()
    {
        return 'user';
    }

    /**
     * behaviors
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * reglas de validación
     */
    public function rules()
    {
        return [
            ['estado_id', 'default', 'value' => self::ESTADO_ACTIVO],
            //['rol_id', 'default', 'value' => 1],
            ['email', 'filter', 'filter' => 'trim'],
            //['email', 'unique'],

            ['username', \sateler\rut\RutValidator::className()],
        ];
    }

    /* Las etiquetas de los atributos de su modelo */
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
            'estado_id' => 'Estado ID',
            'tipo_usuario_id' => 'Tipo Usuario ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'verification_code' => 'Verification Code',
        ];
    }

    /**
     * @findIdentity
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'estado_id' => self::ESTADO_ACTIVO]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Encuentra usuario por username
     * dividida en dos líneas para evitar ajuste de línea * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'estado_id' => self::ESTADO_ACTIVO]);
    }

    /**
     * Encuentra usuario por clave de restablecimiento de password
     *
     * @param string $token clave de restablecimiento de password
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }
        return static::findOne([
            'password_reset_token' => $token,
            'estado_id' => self::ESTADO_ACTIVO,
        ]);
    }

    /**
     * Determina si la clave de restablecimiento de password es válida
     *
     * @param string $token clave de restablecimiento de password
     * @return boolean
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        $parts = explode('_', $token);
        $timestamp = (int) end($parts);
        return $timestamp + $expire >= time();
    }

    /**
     * @getId
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @getAuthKey
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @validateAuthKey
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Valida password
     *
     * @param string $password password a validar
     * @return boolean si la password provista es válida para el usuario actual
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Genera hash de password a partir de password y la establece en el modelo
     *
     * @param string $password
     */
    public function setPassword($password, $cost=null)
    {
        if($cost != null){
            $this->password_hash = Yii::$app->security->generatePasswordHash($password, $cost);
        }else{
            $this->password_hash = Yii::$app->security->generatePasswordHash($password);
        }
    }

    /**
     * Genera clave de autenticación "recuerdame"
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Genera nueva clave de restablecimiento de password
     * dividida en dos líneas para evitar ajuste de línea
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Remueve clave de restablecimiento de password
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    /**
     * Returns user role name according to RBAC
     * @return string
     */
    public function getRoleName()
    {
        $roles = Yii::$app->authManager->getRolesByUser($this->id);
        if (!$roles) {
            return null;
        }

        reset($roles);
        /* @var $role \yii\rbac\Role */
        $role = current($roles);

        return $role->name;
    }
}