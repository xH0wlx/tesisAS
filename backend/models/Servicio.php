<?php

namespace backend\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "servicio".
 *
 * @property integer $id_servicio
 * @property integer $asignatura_cod_asignatura
 * @property string $titulo
 * @property string $descripcion
 * @property string $perfil_scb
 * @property string $observacion
 * @property integer $estado_ejecucion_id_estado
 * @property integer $duracion
 * @property integer $unidad_duracion_id_unidad_duracion
 * @property string $creado_en
 * @property string $modificado_en
 *
 * @property DocenteHasServicio[] $docenteHasServicios
 * @property Docente[] $docenteRutDocentes
 * @property Match1[] $match1s
 * @property Asignatura $asignaturaCodAsignatura
 * @property EstadoEjecucion $estadoEjecucionIdEstado
 * @property UnidadDuracion $unidadDuracionIdUnidadDuracion
 */
class Servicio extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'servicio';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['titulo', 'descripcion', 'duracion', 'unidad_duracion_id_unidad_duracion', 'asignatura_cod_asignatura'], 'required'],
            [['estado_ejecucion_id_estado', 'duracion', 'unidad_duracion_id_unidad_duracion', 'asignatura_cod_asignatura'], 'integer'],
            [['creado_en', 'modificado_en', 'sinMatch'], 'safe'],
            [['titulo'], 'string', 'max' => 255],
            [['descripcion', 'perfil_scb', 'observacion'], 'string', 'max' => 500],
            [['asignatura_cod_asignatura'], 'exist', 'skipOnError' => true, 'targetClass' => Asignatura::className(), 'targetAttribute' => ['asignatura_cod_asignatura' => 'cod_asignatura']],
            [['estado_ejecucion_id_estado'], 'exist', 'skipOnError' => true, 'targetClass' => EstadoEjecucion::className(), 'targetAttribute' => ['estado_ejecucion_id_estado' => 'id_estado']],
            [['unidad_duracion_id_unidad_duracion'], 'exist', 'skipOnError' => true, 'targetClass' => UnidadDuracion::className(), 'targetAttribute' => ['unidad_duracion_id_unidad_duracion' => 'id_unidad_duracion']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_servicio' => 'Id Servicio',
            'asignatura_cod_asignatura' => 'Asignatura',
            'titulo' => 'Título',
            'descripcion' => 'Descripción',
            'perfil_scb' => 'Perfil Socio Comunitario Beneficiario',
            'observacion' => 'Observación',
            'estado_ejecucion_id_estado' => 'Estado de Ejecución',
            'duracion' => 'Duración Actividad',
            'unidad_duracion_id_unidad_duracion' => 'Unidad Duración',
            'creado_en' => 'Creado En',
            'modificado_en' => 'Modificado En',
            'duracionUnidad' => 'Duración',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDocenteHasServicios()
    {
        return $this->hasMany(DocenteHasServicio::className(), ['servicio_id_servicio' => 'id_servicio']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDocenteRutDocentes()
    {
        return $this->hasMany(Docente::className(), ['rut_docente' => 'docente_rut_docente'])->viaTable('docente_has_servicio', ['servicio_id_servicio' => 'id_servicio']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMatch1s()
    {
        return $this->hasMany(Match1::className(), ['servicio_id_servicio' => 'id_servicio']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAsignaturaCodAsignatura()
    {
        return $this->hasOne(Asignatura::className(), ['cod_asignatura' => 'asignatura_cod_asignatura']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEstadoEjecucionIdEstado()
    {
        return $this->hasOne(EstadoEjecucion::className(), ['id_estado' => 'estado_ejecucion_id_estado']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUnidadDuracionIdUnidadDuracion()
    {
        return $this->hasOne(UnidadDuracion::className(), ['id_unidad_duracion' => 'unidad_duracion_id_unidad_duracion']);
    }

    public function getUnidadDuracionLista(){
        $droptions = UnidadDuracion::find()->all();
        return ArrayHelper::map($droptions, 'id_unidad_duracion', 'nombre_unidad');
    }

    public function getSedeLista(){
        $droptions = Sede::find()->asArray()->all();
        return ArrayHelper::map($droptions, 'id_sede', 'nombre_sede');
    }

    public function getAsignaturaLista(){
        $droptions = Asignatura::find()->all();
        return ArrayHelper::map($droptions, 'cod_asignatura', 'codigoNombre');
    }
    public function getDocenteLista(){
        $droptions = Docente::find()->asArray()->all();
        return ArrayHelper::map($droptions, 'rut_docente', 'nombre_completo');
    }

    public function getduracionUnidad()
    {
        return $this->duracion.' ['.$this->unidadDuracionIdUnidadDuracion->nombre_unidad.']';
    }
}
