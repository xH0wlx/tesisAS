<?php

namespace backend\models;

use Yii;
use Yii\helpers\ArrayHelper;

/**
 * This is the model class for table "asignatura".
 *
 * @property integer $cod_asignatura
 * @property string $nombre_asignatura
 * @property string $semestre_dicta
 * @property string $semestre_malla
 * @property string $resultado_aprendizaje
 * @property string $contenido
 * @property integer $carrera_cod_carrera
 *
 * @property Carrera $carreraCodCarrera
 * @property Match1[] $match1s
 * @property Requerimiento[] $requerimientos
 * @property Servicio[] $servicios
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
            ['cod_asignatura', 'unique'],
            [['cod_asignatura', 'nombre_asignatura', 'carrera_cod_carrera', 'semestre_dicta','semestre_malla'], 'required'],
            [['cod_asignatura', 'carrera_cod_carrera', 'semestre_dicta', 'semestre_malla'], 'integer'],
            [['nombre_asignatura'], 'string', 'max' => 255],
            [['resultado_aprendizaje'], 'string', 'max' => 400],
            //[['semestre_dicta', 'semestre_malla'], 'string', 'max' => 45],
            [['carrera_cod_carrera'], 'exist', 'skipOnError' => true, 'targetClass' => Carrera::className(), 'targetAttribute' => ['carrera_cod_carrera' => 'cod_carrera']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cod_asignatura' => 'Código de Asignatura',
            'nombre_asignatura' => 'Nombre',
            'semestre_dicta' => 'Semestre en el que se Dicta',
            'semestre_malla' => 'Semestre Malla Curricular',
            'resultado_aprendizaje' => "Resultado Aprendizaje",
            'carrera_cod_carrera' => 'Carrera',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCarreraCodCarrera()
    {
        return $this->hasOne(Carrera::className(), ['cod_carrera' => 'carrera_cod_carrera']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMatch1s()
    {
        return $this->hasMany(Match1::className(), ['asignatura_cod_asignatura' => 'cod_asignatura']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRequerimientoIdRequerimientos()
    {
        return $this->hasMany(Requerimiento::className(), ['id_requerimiento' => 'requerimiento_id_requerimiento'])->viaTable('match1', ['asignatura_cod_asignatura' => 'cod_asignatura']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getServicios()
    {
        return $this->hasMany(Servicio::className(), ['asignatura_cod_asignatura' => 'cod_asignatura']);
    }

    public function getSedeLista(){
        $droptions = Sede::find()->asArray()->all();
        return ArrayHelper::map($droptions, 'id_sede', 'nombre_sede');
    }

    public function getcodigoNombre()
    {
        return $this->cod_asignatura.' - '.$this->nombre_asignatura;
    }


}
