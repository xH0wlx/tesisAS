<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "grupo_trabajo".
 *
 * @property integer $id_grupo_trabajo
 * @property integer $numero_grupo_trabajo
 * @property integer $seccion_id_seccion
 *
 * @property AlumnoInscritoHasGrupoTrabajo[] $alumnoInscritoHasGrupoTrabajos
 * @property AlumnoInscritoSeccion[] $alumnoInscritoSeccionIdAlumnoInscritoSeccions
 * @property AlumnoInscritoLider[] $alumnoInscritoLiders
 * @property AlumnoInscritoSeccion[] $alumnoInscritoSeccionIdSeccionAlumnos
 * @property Bitacora[] $bitacoras
 * @property Seccion $seccionIdSeccion
 * @property GrupoTrabajoHasScb[] $grupoTrabajoHasScbs
 */
class GrupoTrabajo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'grupo_trabajo';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['numero_grupo_trabajo', 'seccion_id_seccion'], 'required'],
            [['numero_grupo_trabajo', 'seccion_id_seccion'], 'integer'],
            ['id_alumno_lider', 'safe'],
            ['id_alumno_lider', 'integer'],
            [['seccion_id_seccion'], 'exist', 'skipOnError' => true, 'targetClass' => Seccion::className(), 'targetAttribute' => ['seccion_id_seccion' => 'id_seccion']],
            [['id_alumno_lider'], 'exist', 'skipOnError' => true, 'targetClass' => AlumnoInscritoSeccion::className(), 'targetAttribute' => ['id_alumno_lider' => 'id_alumno_inscrito_seccion']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_grupo_trabajo' => 'Id Grupo Trabajo',
            'numero_grupo_trabajo' => 'Número Grupo Trabajo',
            'seccion_id_seccion' => 'Sección',
            'cantidadBitacoras' => 'Cantidad Bitácoras',
            'id_alumno_lider' => 'Líder',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAlumnoInscritoHasGrupoTrabajos()
    {
        return $this->hasMany(AlumnoInscritoHasGrupoTrabajo::className(), ['grupo_trabajo_id_grupo_trabajo' => 'id_grupo_trabajo']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAlumnoInscritoSeccionIdAlumnoInscritoSeccions()
    {
        return $this->hasMany(AlumnoInscritoSeccion::className(), ['id_alumno_inscrito_seccion' => 'alumno_inscrito_seccion_id_alumno_inscrito_seccion'])->viaTable('alumno_inscrito_has_grupo_trabajo', ['grupo_trabajo_id_grupo_trabajo' => 'id_grupo_trabajo']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAlumnoInscritoLider()
    {
        return $this->hasOne(AlumnoInscritoSeccion::className(), ['id_alumno_inscrito_seccion' => 'id_alumno_lider']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    //NO USAR
    public function getAlumnoInscritoLiders()
    {
        return $this->hasMany(AlumnoInscritoLider::className(), ['grupo_trabajo_id_grupo_trabajo' => 'id_grupo_trabajo']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    //NO USAR RELACIÓN ANTIGUA
    public function getAlumnoInscritoSeccionIdSeccionAlumnos()
    {
        return $this->hasMany(AlumnoInscritoSeccion::className(), ['id_alumno_inscrito_seccion' => 'alumno_inscrito_seccion_id_seccion_alumno'])->viaTable('alumno_inscrito_lider', ['grupo_trabajo_id_grupo_trabajo' => 'id_grupo_trabajo']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBitacoras()
    {
        return $this->hasMany(Bitacora::className(), ['grupo_trabajo_id_grupo_trabajo' => 'id_grupo_trabajo']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSeccionIdSeccion()
    {
        return $this->hasOne(Seccion::className(), ['id_seccion' => 'seccion_id_seccion']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGrupoTrabajoHasScbs()
    {
        return $this->hasMany(GrupoTrabajoHasScb::className(), ['grupo_trabajo_id_grupo_trabajo' => 'id_grupo_trabajo']);
    }

    public function getGrupoTrabajoHasScbsNoCambiados()
    {
        return $this->hasMany(GrupoTrabajoHasScb::className(), ['grupo_trabajo_id_grupo_trabajo' => 'id_grupo_trabajo'])
            ->andOnCondition(['cambio' => '0']);
    }

    public function getUltimoSocioBeneficiario()
    {
        //RETORNA EL ÚLTIMO SCB
        $intermedia = GrupoTrabajoHasScb::find()->orderBy(['creado_en' => SORT_DESC])->where(['grupo_trabajo_id_grupo_trabajo'
        => $this->id_grupo_trabajo])->one();
        if($intermedia != null){
            return $intermedia->scbIdScb;
        }
        return null;

    }

    public function getCantidadBitacoras()
    {
        //NO USAR
        return $this->hasMany(Bitacora::className(), ['grupo_trabajo_id_grupo_trabajo' => 'id_grupo_trabajo'])->sum('cantidad');
    }

}
