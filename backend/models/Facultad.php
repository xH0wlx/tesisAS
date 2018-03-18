<?php

namespace backend\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "facultad".
 *
 * @property integer $id_facultad
 * @property string $nombre_facultad
 * @property integer $sede_id_sede
 *
 * @property Carrera[] $carreras
 * @property Sede $sedeIdSede
 */
class Facultad extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'facultad';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nombre_facultad', 'sede_id_sede'], 'required'],
            [['sede_id_sede'], 'integer'],
            [['nombre_facultad'], 'string', 'max' => 45],
            [['sede_id_sede'], 'exist', 'skipOnError' => true, 'targetClass' => Sede::className(), 'targetAttribute' => ['sede_id_sede' => 'id_sede']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_facultad' => 'Id',
            'nombre_facultad' => 'Nombre',
            'sede_id_sede' => 'Sede',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCarreras()
    {
        return $this->hasMany(Carrera::className(), ['facultad_id_facultad' => 'id_facultad']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSedeIdSede()
    {
        return $this->hasOne(Sede::className(), ['id_sede' => 'sede_id_sede']);
    }

    public function getNombreSede() {
        return $this->sedeIdSede->nombre_sede;
    }

    public function getSedeLista(){
        $droptions = Sede::find()->asArray()->all();
        return ArrayHelper::map($droptions, 'id_sede', 'nombre_sede');
    }

    public function getFacultadMasSede(){
        return $this->nombre_facultad.' ('.$this->nombreSede.')';
    }
}
