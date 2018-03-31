<?php

namespace backend\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "bitacora".
 *
 * @property integer $id_bitacora
 * @property integer $grupo_trabajo_id_grupo_trabajo
 * @property string $fecha_bitacora
 * @property string $hora_inicio
 * @property string $hora_termino
 * @property string $actividad_realizada
 * @property string $resultados
 * @property string $observaciones
 * @property string $fecha_lectura
 * @property string $creado_en
 * @property string $modificado_en
 *
 * @property AlumnoInscritoAsistente[] $alumnoInscritoAsistentes
 * @property AlumnoInscritoSeccion[] $alumnoInscritoSeccionIdAlumnoInscritoSeccions
 * @property GrupoTrabajo $grupoTrabajoIdGrupoTrabajo
 * @property Evidencia $evidencia
 */
class Bitacora extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */

    public static function tableName()
    {
        return 'bitacora';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            //[['grupo_trabajo_id_grupo_trabajo'], 'required'],
            /*['hora_inicio', 'date', 'timestampAttribute' => 'hora_inicio'],
            ['hora_termino', 'date', 'timestampAttribute' => 'hora_termino'],
            ['hora_inicio', 'compare', 'compareAttribute' => 'hora_termino', 'operator' => '<', 'enableClientValidation' => true],
            ['hora_termino', 'compare', 'compareAttribute' => 'hora_inicio', 'operator' => '>', 'enableClientValidation' => true],*/
            [['fecha_bitacora','hora_inicio', 'hora_termino','actividad_realizada','resultados'], 'required'],
            [['grupo_trabajo_id_grupo_trabajo'], 'integer'],
            [['fecha_bitacora', 'hora_inicio', 'hora_termino', 'fecha_lectura', 'aprobacion_docente', 'creado_en', 'modificado_en'], 'safe'],
            [['actividad_realizada', 'resultados', 'observaciones'], 'string', 'max' => 500],
            [['grupo_trabajo_id_grupo_trabajo'], 'exist', 'skipOnError' => true, 'targetClass' => GrupoTrabajo::className(), 'targetAttribute' => ['grupo_trabajo_id_grupo_trabajo' => 'id_grupo_trabajo']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_bitacora' => 'Id Bitacora',
            'grupo_trabajo_id_grupo_trabajo' => 'Grupo Trabajo Id Grupo Trabajo',
            'fecha_bitacora' => 'Fecha de la Visita',
            'hora_inicio' => 'Hora de Inicio',
            'hora_termino' => 'Hora de Término',
            'actividad_realizada' => 'Actividad Planificada',
            'resultados' => 'Resultados',
            'observaciones' => 'Observaciones',
            'fecha_lectura' => 'Fecha Lectura',
            'aprobacion_docente' => 'Aprobación Docente',
            'creado_en' => 'Creado En',
            'modificado_en' => 'Modificado En',
            'cantidadBitacoras' => 'N° Bitácoras por Grupo',
            'totalBitacorasGrupo' => 'Total Bitácoras Grupo',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAlumnoInscritoAsistentes()
    {
        return $this->hasMany(AlumnoInscritoAsistente::className(), ['bitacora_id_bitacora' => 'id_bitacora']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAlumnoInscritoSeccionIdAlumnoInscritoSeccions()
    {
        return $this->hasMany(AlumnoInscritoSeccion::className(), ['id_alumno_inscrito_seccion' => 'alumno_inscrito_seccion_id_alumno_inscrito_seccion'])->viaTable('alumno_inscrito_asistente', ['bitacora_id_bitacora' => 'id_bitacora']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGrupoTrabajoIdGrupoTrabajo()
    {
        return $this->hasOne(GrupoTrabajo::className(), ['id_grupo_trabajo' => 'grupo_trabajo_id_grupo_trabajo']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEvidencia()
    {
        return $this->hasOne(Evidencia::className(), ['bitacora_id_bitacora' => 'id_bitacora']);
    }

    public function getCantidadBitacoras()
    {
        return $this->hasMany(Bitacora::className(), ['grupo_trabajo_id_grupo_trabajo' => 'grupo_trabajo_id_grupo_trabajo'])->count();
    }

    public function getTotalBitacorasGrupo()
    {
        $grupo = GrupoTrabajo::findOne($this->grupo_trabajo_id_grupo_trabajo);

        $total = count($grupo->bitacoras);

        return $total;
    }

    public function getSedeLista(){
        $droptions = Sede::find()->asArray()->all();
        return ArrayHelper::map($droptions, 'nombre_sede', 'nombre_sede');
    }

    public function getAsignaturaLista(){
        $droptions = Asignatura::find()->all();
        return ArrayHelper::map($droptions, 'nombre_asignatura', 'codigoNombre');
    }
}
