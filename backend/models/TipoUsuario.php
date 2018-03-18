<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "tipo_usuario".
 *
 * @property integer $id_tipo_usuario
 * @property string $nombre
 *
 * @property User[] $users
 */
class TipoUsuario extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tipo_usuario';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nombre', 'nombre_rol'], 'required'],
            [['nombre'], 'string', 'max' => 45],
            [['nombre_rol'], 'string', 'max' => 45],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_tipo_usuario' => 'Id Tipo Usuario',
            'nombre' => 'Nombre',
            'nombre_rol' => 'Nombre Rol',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['tipo_usuario_id' => 'id_tipo_usuario']);
    }
}
