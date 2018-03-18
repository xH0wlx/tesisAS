<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "estado_implementacion".
 *
 * @property integer $id_estado_implementacion
 * @property string $nombre_estado
 *
 * @property Implementacion[] $implementacions
 */
class EstadoImplementacion extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'estado_implementacion';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nombre_estado'], 'required'],
            [['nombre_estado'], 'string', 'max' => 45],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_estado_implementacion' => 'Id Estado Implementacion',
            'nombre_estado' => 'Nombre Estado',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getImplementacions()
    {
        return $this->hasMany(Implementacion::className(), ['estado' => 'id_estado_implementacion']);
    }
}
