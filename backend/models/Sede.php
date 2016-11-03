<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sede".
 *
 * @property integer $id_sede
 * @property string $nombre
 *
 * @property Carrera[] $carreras
 */
class Sede extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sede';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nombre'], 'string', 'max' => 45],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_sede' => 'Id Sede',
            'nombre' => 'Nombre',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCarreras()
    {
        return $this->hasMany(Carrera::className(), ['id_sede' => 'id_sede']);
    }
}
