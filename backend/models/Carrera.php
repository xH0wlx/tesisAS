<?php

namespace backend\models;

use Yii;
use Yii\helpers\ArrayHelper;

/**
 * This is the model class for table "carrera".
 *
 * @property string $cod_carrera
 * @property string $nombre_carrera
 * @property string $alias_carrera
 * @property string $plan_carrera
 * @property integer $facultad_id_facultad
 *
 * @property Asignatura[] $asignaturas
 * @property Facultad $facultadIdFacultad
 */
class Carrera extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'carrera';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['cod_carrera', 'unique'],
            [['alias_carrera'], 'safe'],
            [['cod_carrera', 'nombre_carrera', 'facultad_id_facultad', 'plan_carrera'], 'required'],
            [['cod_carrera','facultad_id_facultad', 'plan_carrera'], 'integer'],
            [['nombre_carrera'], 'string', 'max' => 255],
            //[['plan_carrera'], 'string', 'max' => 45],
            [['facultad_id_facultad'], 'exist', 'skipOnError' => true, 'targetClass' => Facultad::className(), 'targetAttribute' => ['facultad_id_facultad' => 'id_facultad']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cod_carrera' => 'Código Carrera',
            'nombre_carrera' => 'Nombre',
            'alias_carrera' => 'Alias Carrera',
            'plan_carrera' => 'Plan',
            'facultad_id_facultad' => 'Facultad',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAsignaturas()
    {
        return $this->hasMany(Asignatura::className(), ['carrera_cod_carrera' => 'cod_carrera']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFacultadIdFacultad()
    {
        return $this->hasOne(Facultad::className(), ['id_facultad' => 'facultad_id_facultad']);
    }

    public function getSedeLista(){
        $droptions = Sede::find()->asArray()->all();
        return ArrayHelper::map($droptions, 'id_sede', 'nombre_sede');
    }

    //SE saca el asArray para obtener atributos de métodos GET
    public function getFacultadLista(){
        $droptions = Facultad::find()->all();
        return ArrayHelper::map($droptions, 'id_facultad', 'facultadMasSede');
    }
}
