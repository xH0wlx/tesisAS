<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "seccion".
 *
 * @property integer $id_seccion
 * @property integer $numero_seccion
 * @property integer $implementacion_id_implementacion
 * @property string $docente_rut_docente
 *
 * @property AlumnoInscritoSeccion[] $alumnoInscritoSeccions
 * @property GrupoTrabajo[] $grupoTrabajos
 * @property Implementacion $implementacionIdImplementacion
 * @property Docente $docenteRutDocente
 */
class Seccion extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'seccion';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['numero_seccion', /*'implementacion_id_implementacion',*/ 'docente_rut_docente'], 'required'],
            [['numero_seccion', 'implementacion_id_implementacion'], 'integer'],
            /*[['numero_seccion'], 'unique',
                'targetAttribute' => ['implementacion_id_implementacion','numero_seccion'],
                'message' => 'Número de Sección ya utilizado en esta implementación.'],*/
            [['docente_rut_docente'], 'string', 'max' => 45],
            [['cantidad_grupos'], 'integer'],
            [['implementacion_id_implementacion'], 'exist', 'skipOnError' => true, 'targetClass' => Implementacion::className(), 'targetAttribute' => ['implementacion_id_implementacion' => 'id_implementacion']],
            [['docente_rut_docente'], 'exist', 'skipOnError' => true, 'targetClass' => Docente::className(), 'targetAttribute' => ['docente_rut_docente' => 'rut_docente']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_seccion' => 'Id Seccion',
            'numero_seccion' => 'Numero Seccion',
            'implementacion_id_implementacion' => 'Implementacion Id Implementacion',
            'docente_rut_docente' => 'Docente',
            'cantidad_grupos' => 'Cantidad Grupos',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAlumnoInscritoSeccions()
    {
        return $this->hasMany(AlumnoInscritoSeccion::className(), ['seccion_id_seccion' => 'id_seccion']);
    }

    public function getAlumnoInscritoSeccionOrdenados()
    {
        return $this->hasMany(AlumnoInscritoSeccion::className(), ['seccion_id_seccion' => 'id_seccion'])->joinWith("alumnoRutAlumno")->orderBy(['alumno.nombre' => SORT_ASC]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGrupoTrabajos()
    {
        return $this->hasMany(GrupoTrabajo::className(), ['seccion_id_seccion' => 'id_seccion'])
            ->orderBy(['numero_grupo_trabajo' => SORT_ASC]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getImplementacionIdImplementacion()
    {
        return $this->hasOne(Implementacion::className(), ['id_implementacion' => 'implementacion_id_implementacion']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDocenteRutDocente()
    {
        return $this->hasOne(Docente::className(), ['rut_docente' => 'docente_rut_docente']);
    }

    public function getSeccionDocente(){

        return $this->numero_seccion.' / '.$this->docenteRutDocente->nombre_completo;
    }
}
