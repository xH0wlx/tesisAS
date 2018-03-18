<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "match1".
 *
 * @property integer $id_match1
 * @property integer $requerimiento_id_requerimiento
 * @property integer $asignatura_cod_asignatura
 * @property integer $anio_match1
 * @property integer $semestre_match1
 * @property integer $servicio_id_servicio
 * @property integer $aprobacion_implementacion
 * @property string $creado_en
 * @property string $modificado_en
 *
 * @property Servicio $servicioIdServicio
 * @property Asignatura $asignaturaCodAsignatura
 * @property Requerimiento $requerimientoIdRequerimiento
 */
class Match1 extends \yii\db\ActiveRecord
{
    public $id_sede_reporte_estadistica;
    public $sede_reporte_estadistica;
    public $cantidad_sci_reporte;
    public $cantidad_requerimientos_reporte;
    public $cantidad_requerimientos_na_reporte;
    public $idesAgrupadas;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'match1';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['requerimiento_id_requerimiento', 'asignatura_cod_asignatura', 'anio_match1', 'semestre_match1'], 'unique'
                , 'message' => 'La combinación Requerimiento-Asignatura-Año-Semestre ya ha sido ingresada.',
                'targetAttribute' => ['requerimiento_id_requerimiento', 'asignatura_cod_asignatura', 'anio_match1', 'semestre_match1']],
            [['requerimiento_id_requerimiento', 'asignatura_cod_asignatura', 'anio_match1', 'semestre_match1'], 'required'],
            [['requerimiento_id_requerimiento', 'asignatura_cod_asignatura', 'anio_match1', 'semestre_match1', 'servicio_id_servicio', 'aprobacion_implementacion', 'implementacion_id_implementacion'], 'integer'],
            [['creado_en', 'modificado_en', 'idesAgrupadas'], 'safe'],
            [['servicio_id_servicio'], 'exist', 'skipOnError' => true, 'targetClass' => Servicio::className(), 'targetAttribute' => ['servicio_id_servicio' => 'id_servicio']],
            [['asignatura_cod_asignatura'], 'exist', 'skipOnError' => true, 'targetClass' => Asignatura::className(), 'targetAttribute' => ['asignatura_cod_asignatura' => 'cod_asignatura']],
            [['requerimiento_id_requerimiento'], 'exist', 'skipOnError' => true, 'targetClass' => Requerimiento::className(), 'targetAttribute' => ['requerimiento_id_requerimiento' => 'id_requerimiento']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_match1' => 'Id Match1',
            'requerimiento_id_requerimiento' => 'ID Requerimiento',
            'asignatura_cod_asignatura' => 'Código Asignatura',
            'anio_match1' => 'Año',
            'semestre_match1' => 'Semestre',
            'servicio_id_servicio' => 'Id Servicio',
            'aprobacion_implementacion' => 'Estado Aprobación Implementación',
            'creado_en' => 'Creado En',
            'modificado_en' => 'Modificado En',
            'implementacion_id_implementacion' => 'id implementacion',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getServicioIdServicio()
    {
        return $this->hasOne(Servicio::className(), ['id_servicio' => 'servicio_id_servicio']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAsignaturaCodAsignatura()
    {
        return $this->hasOne(Asignatura::className(), ['cod_asignatura' => 'asignatura_cod_asignatura']);
    }

    public function getImplementacionIdImplementacion()
    {
        return $this->hasOne(Implementacion::className(), ['id_implementacion' => 'implementacion_id_implementacion']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRequerimientoIdRequerimiento()
    {
        return $this->hasOne(Requerimiento::className(), ['id_requerimiento' => 'requerimiento_id_requerimiento']);
    }

    public function getAnioSemestre()
    {
        return $this->anio_match1." - ".$this->semestre_match1;
    }

    public function getSocios($model){
        $arreglo = explode(",",$model->idesAgrupadas);
        $aux = [];
        $string = "";
        foreach ($arreglo as $idMatch){
            $fila = \backend\models\Match1::findOne(intval($idMatch));
            $idSocio = $fila->requerimientoIdRequerimiento->sci_id_sci;
            if($fila != null && !in_array($idSocio,$aux)){
                $string = $string.$fila->requerimientoIdRequerimiento->sciIdSci->nombre."<br>";
                $aux[] = $idSocio;
            }
        }
        return $string;
    }
}
