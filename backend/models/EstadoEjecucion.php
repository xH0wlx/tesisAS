<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "estado_ejecucion".
 *
 * @property integer $id_estado
 * @property string $nombre_estado
 *
 * @property Requerimiento[] $requerimientos
 * @property Servicio[] $servicios
 */
class EstadoEjecucion extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'estado_ejecucion';
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
            'id_estado' => 'Id Estado',
            'nombre_estado' => 'Estado',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRequerimientos()
    {
        return $this->hasMany(Requerimiento::className(), ['estado_ejecucion_id_estado' => 'id_estado']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getServicios()
    {
        return $this->hasMany(Servicio::className(), ['estado_ejecucion_id_estado' => 'id_estado']);
    }
}
