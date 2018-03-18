<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "docente".
 *
 * @property string $rut_docente
 * @property string $nombre_completo
 * @property string $email
 * @property integer $telefono
 *
 * @property DocenteHasServicio[] $docenteHasServicios
 * @property Servicio[] $servicioIdServicios
 */
class Docente extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'docente';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['rut_docente', \sateler\rut\RutValidator::className()],
            [['rut_docente', 'nombre_completo', 'email'], 'required'],
            [['telefono'], 'integer'],
            [['email'], 'email'],
            [['rut_docente','email'], 'unique'],
            [['rut_docente'], 'string', 'max' => 12],
            [['nombre_completo', 'email'], 'string', 'max' => 45],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'rut_docente' => 'Rut Docente',
            'nombre_completo' => 'Nombre Completo',
            'email' => 'Email',
            'telefono' => 'Teléfono',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDocenteHasServicios()
    {
        return $this->hasMany(DocenteHasServicio::className(), ['docente_rut_docente' => 'rut_docente']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getServicioIdServicios()
    {
        return $this->hasMany(Servicio::className(), ['id_servicio' => 'servicio_id_servicio'])->viaTable('docente_has_servicio', ['docente_rut_docente' => 'rut_docente']);
    }

    public function getRutNombre(){
        return Yii::$app->formatter->asRut($this->rut_docente)." / ".$this->nombre_completo;
    }
}
