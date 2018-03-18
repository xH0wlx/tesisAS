<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "unidad_duracion".
 *
 * @property integer $id_unidad_duracion
 * @property string $nombre_unidad
 *
 * @property Servicio[] $servicios
 */
class UnidadDuracion extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'unidad_duracion';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nombre_unidad'], 'required'],
            [['nombre_unidad'], 'string', 'max' => 45],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_unidad_duracion' => 'Id Unidad Duracion',
            'nombre_unidad' => 'Nombre Unidad',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getServicios()
    {
        return $this->hasMany(Servicio::className(), ['unidad_duracion_id_unidad_duracion' => 'id_unidad_duracion']);
    }
}
