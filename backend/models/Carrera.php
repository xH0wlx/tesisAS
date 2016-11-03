<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "carrera".
 *
 * @property integer $id_carrera
 * @property integer $cod_carrera
 * @property integer $plan
 * @property string $nombre
 * @property integer $id_sede
 *
 * @property Asignatura[] $asignaturas
 * @property Sede $idSede
 */
class Carrera extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'carrera';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cod_carrera', 'plan', 'id_sede'], 'integer'],
            [['id_sede'], 'required'],
            [['nombre'], 'string', 'max' => 70],
            [['id_sede'], 'exist', 'skipOnError' => true, 'targetClass' => Sede::className(), 'targetAttribute' => ['id_sede' => 'id_sede']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_carrera' => 'Id Carrera',
            'cod_carrera' => 'Cod Carrera',
            'plan' => 'Plan',
            'nombre' => 'Nombre',
            'id_sede' => 'Id Sede',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAsignaturas()
    {
        return $this->hasMany(Asignatura::className(), ['id_carrera' => 'id_carrera']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdSede()
    {
        return $this->hasOne(Sede::className(), ['id_sede' => 'id_sede']);
    }
}
