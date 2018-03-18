<?php

namespace backend\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "alumno".
 *
 * @property string $rut_alumno
 * @property string $nombre
 * @property string $telefono
 * @property string $email
 *
 * @property AlumnoHasCarrera[] $alumnoHasCarreras
 * @property AlumnoInscritoSeccion[] $alumnoInscritoSeccions
 */
class Alumno extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'alumno';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['rut_alumno', \sateler\rut\RutValidator::className()],
            [['rut_alumno', 'nombre', 'email'], 'required'],
            ['rut_alumno', 'unique'],
            [['rut_alumno', 'telefono', 'email'], 'string', 'max' => 45],
            [['nombre'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'rut_alumno' => 'RUT Alumno',
            'nombre' => 'Nombre',
            'telefono' => 'Teléfono',
            'email' => 'Email',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAlumnoHasCarreras()
    {
        return $this->hasMany(AlumnoHasCarrera::className(), ['alumno_rut_alumno' => 'rut_alumno']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAlumnoInscritoSeccions()
    {
        return $this->hasMany(AlumnoInscritoSeccion::className(), ['alumno_rut_alumno' => 'rut_alumno']);
    }

    public function getAlumnoLista(){
        $droptions = Alumno::find()->asArray()->all();
        return ArrayHelper::map($droptions, 'rut_alumno', 'nombre');
    }
}
