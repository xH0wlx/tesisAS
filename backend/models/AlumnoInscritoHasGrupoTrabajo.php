<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "alumno_inscrito_has_grupo_trabajo".
 *
 * @property integer $alumno_inscrito_seccion_id_alumno_inscrito_seccion
 * @property integer $grupo_trabajo_id_grupo_trabajo
 * @property string $fecha_creacion
 * @property string $observacion
 *
 * @property AlumnoInscritoSeccion $alumnoInscritoSeccionIdAlumnoInscritoSeccion
 * @property GrupoTrabajo $grupoTrabajoIdGrupoTrabajo
 */
class AlumnoInscritoHasGrupoTrabajo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'alumno_inscrito_has_grupo_trabajo';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['alumno_inscrito_seccion_id_alumno_inscrito_seccion', 'grupo_trabajo_id_grupo_trabajo'], 'required'],
            [['alumno_inscrito_seccion_id_alumno_inscrito_seccion', 'grupo_trabajo_id_grupo_trabajo'], 'integer'],
            [['fecha_creacion'], 'safe'],
            [['observacion'], 'string', 'max' => 200],
            [['alumno_inscrito_seccion_id_alumno_inscrito_seccion'], 'exist', 'skipOnError' => true, 'targetClass' => AlumnoInscritoSeccion::className(), 'targetAttribute' => ['alumno_inscrito_seccion_id_alumno_inscrito_seccion' => 'id_alumno_inscrito_seccion']],
            [['grupo_trabajo_id_grupo_trabajo'], 'exist', 'skipOnError' => true, 'targetClass' => GrupoTrabajo::className(), 'targetAttribute' => ['grupo_trabajo_id_grupo_trabajo' => 'id_grupo_trabajo']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'alumno_inscrito_seccion_id_alumno_inscrito_seccion' => 'Alumno Inscrito Seccion Id Alumno Inscrito Seccion',
            'grupo_trabajo_id_grupo_trabajo' => 'Grupo Trabajo Id Grupo Trabajo',
            'fecha_creacion' => 'Fecha Creacion',
            'observacion' => 'Observacion',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAlumnoInscritoSeccionIdAlumnoInscritoSeccion()
    {
        return $this->hasOne(AlumnoInscritoSeccion::className(), ['id_alumno_inscrito_seccion' => 'alumno_inscrito_seccion_id_alumno_inscrito_seccion']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGrupoTrabajoIdGrupoTrabajo()
    {
        return $this->hasOne(GrupoTrabajo::className(), ['id_grupo_trabajo' => 'grupo_trabajo_id_grupo_trabajo']);
    }
}
