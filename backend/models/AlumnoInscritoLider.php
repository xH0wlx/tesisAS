<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "alumno_inscrito_lider".
 *
 * @property integer $alumno_inscrito_seccion_id_seccion_alumno
 * @property integer $grupo_trabajo_id_grupo_trabajo
 * @property string $fecha_creacion
 */
class AlumnoInscritoLider extends \yii\db\ActiveRecord
{
    const SCENARIO_LIDER = 'lider';
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'alumno_inscrito_lider';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['alumno_inscrito_seccion_id_seccion_alumno'], 'required', 'on' => self::SCENARIO_LIDER],
            [['grupo_trabajo_id_grupo_trabajo'], 'required'],
            [['alumno_inscrito_seccion_id_seccion_alumno', 'grupo_trabajo_id_grupo_trabajo'], 'integer'],
            [['fecha_creacion'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'alumno_inscrito_seccion_id_seccion_alumno' => 'Alumno Inscrito Seccion Id Seccion Alumno',
            'grupo_trabajo_id_grupo_trabajo' => 'Grupo Trabajo Id Grupo Trabajo',
            'fecha_creacion' => 'Fecha Creacion',
        ];
    }
}
