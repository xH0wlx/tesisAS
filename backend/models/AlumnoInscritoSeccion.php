<?php

namespace backend\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "alumno_inscrito_seccion".
 *
 * @property integer $id_alumno_inscrito_seccion
 * @property string $alumno_rut_alumno
 * @property integer $seccion_id_seccion
 *
 * @property AlumnoInscritoAsistente[] $alumnoInscritoAsistentes
 * @property Bitacora[] $bitacoraIdBitacoras
 * @property AlumnoInscritoHasGrupoTrabajo[] $alumnoInscritoHasGrupoTrabajos
 * @property GrupoTrabajo[] $grupoTrabajoIdGrupoTrabajos
 * @property AlumnoInscritoLider[] $alumnoInscritoLiders
 * @property GrupoTrabajo[] $grupoTrabajoIdGrupoTrabajos0
 * @property Alumno $alumnoRutAlumno
 * @property Seccion $seccionIdSeccion
 */
class AlumnoInscritoSeccion extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'alumno_inscrito_seccion';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_alumno_inscrito_seccion'], 'unique'],
            [['alumno_rut_alumno'/*, 'seccion_id_seccion'*/], 'required'],
            [['seccion_id_seccion'], 'integer'],
            [['alumno_rut_alumno'], 'string', 'max' => 45],
            [['alumno_rut_alumno'], 'exist', 'skipOnError' => true, 'targetClass' => Alumno::className(), 'targetAttribute' => ['alumno_rut_alumno' => 'rut_alumno']],
            [['seccion_id_seccion'], 'exist', 'skipOnError' => true, 'targetClass' => Seccion::className(), 'targetAttribute' => ['seccion_id_seccion' => 'id_seccion']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_alumno_inscrito_seccion' => 'Id Alumno Inscrito Seccion',
            'alumno_rut_alumno' => 'Alumno',
            'seccion_id_seccion' => 'Seccion Id Seccion',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAlumnoInscritoAsistentes()
    {
        return $this->hasMany(AlumnoInscritoAsistente::className(), ['alumno_inscrito_seccion_id_alumno_inscrito_seccion' => 'id_alumno_inscrito_seccion']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBitacoraIdBitacoras()
    {
        return $this->hasMany(Bitacora::className(), ['id_bitacora' => 'bitacora_id_bitacora'])->viaTable('alumno_inscrito_asistente', ['alumno_inscrito_seccion_id_alumno_inscrito_seccion' => 'id_alumno_inscrito_seccion']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAlumnoInscritoHasGrupoTrabajos()
    {
        return $this->hasMany(AlumnoInscritoHasGrupoTrabajo::className(), ['alumno_inscrito_seccion_id_alumno_inscrito_seccion' => 'id_alumno_inscrito_seccion']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    //DEVUELVE MUCHOS ?
    public function getGrupoTrabajoIdGrupoTrabajos()
    {
        return $this->hasMany(GrupoTrabajo::className(), ['id_grupo_trabajo' => 'grupo_trabajo_id_grupo_trabajo'])->viaTable('alumno_inscrito_has_grupo_trabajo', ['alumno_inscrito_seccion_id_alumno_inscrito_seccion' => 'id_alumno_inscrito_seccion']);
    }

    //SOLO DEVUELVE UNO
    public function getGrupoTrabajoIdGrupoTrabajo()
    {
        return $this->hasOne(GrupoTrabajo::className(), ['id_grupo_trabajo' => 'grupo_trabajo_id_grupo_trabajo'])->viaTable('alumno_inscrito_has_grupo_trabajo', ['alumno_inscrito_seccion_id_alumno_inscrito_seccion' => 'id_alumno_inscrito_seccion']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAlumnoInscritoLiders()
    {
        return $this->hasMany(AlumnoInscritoLider::className(), ['alumno_inscrito_seccion_id_seccion_alumno' => 'id_alumno_inscrito_seccion']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGrupoTrabajoIdGrupoTrabajos0()
    {
        return $this->hasMany(GrupoTrabajo::className(), ['id_grupo_trabajo' => 'grupo_trabajo_id_grupo_trabajo'])->viaTable('alumno_inscrito_lider', ['alumno_inscrito_seccion_id_seccion_alumno' => 'id_alumno_inscrito_seccion']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAlumnoRutAlumno()
    {
        return $this->hasOne(Alumno::className(), ['rut_alumno' => 'alumno_rut_alumno']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSeccionIdSeccion()
    {
        return $this->hasOne(Seccion::className(), ['id_seccion' => 'seccion_id_seccion']);
    }

    public function getEsLider()
    {
        return $this->hasOne(GrupoTrabajo::className(), ['id_alumno_lider' => 'id_alumno_inscrito_seccion']);
    }

    public function getRutNombre(){
        return $this->rut_alumno.' ('.$this->alumnoRutAlumno->rut_alumno.')';
    }
    public function getAlumnosInscritosLista(){
        $alumnos = AlumnoInscritoSeccion::find()->orderBy('alumnoRutAlumno.nombre')->all();

        $items = [];
        foreach ($alumnos as $a) {
            $items[$a->id_alumno_inscrito_seccion] = [
                'content' => $a->alumnoRutAlumno->nombre,
                //'options' => ['data' => ['id'=>$p->id]],
            ];
        }
        return $items;
    }

    public function getAlumnoLista(){
        $droptions = Alumno::find()->asArray()->all();
        return ArrayHelper::map($droptions, 'rut_alumno', 'nombre');
    }
}
