<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "sede".
 *
 * @property integer $id_sede
 * @property string $nombre_sede
 *
 * @property Facultad[] $facultads
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
            [['nombre_sede'], 'required'],
            [['nombre_sede'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_sede' => 'Id',
            'nombre_sede' => 'Sede',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFacultads()
    {
        return $this->hasMany(Facultad::className(), ['sede_id_sede' => 'id_sede']);
    }
}
