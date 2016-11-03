<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "asignatura".
 *
 * @property integer $cod_asignatura
 * @property string $nombre
 * @property integer $id_carrera
 *
 * @property Carrera $idCarrera
 * @property Implementacion[] $implementacions
 */
class Asignatura extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'asignatura';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cod_asignatura', 'id_carrera'], 'required'],
            [['cod_asignatura', 'id_carrera'], 'integer'],
            [['nombre'], 'string', 'max' => 70],
            [['id_carrera'], 'exist', 'skipOnError' => true, 'targetClass' => Carrera::className(), 'targetAttribute' => ['id_carrera' => 'id_carrera']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cod_asignatura' => 'Cod Asignatura',
            'nombre' => 'Nombre',
            'id_carrera' => 'Id Carrera',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdCarrera()
    {
        return $this->hasOne(Carrera::className(), ['id_carrera' => 'id_carrera']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getImplementacions()
    {
        return $this->hasMany(Implementacion::className(), ['cod_asignatura' => 'cod_asignatura']);
    }
}
