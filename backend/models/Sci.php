<?php

namespace backend\models;

use Yii;
use yii\helpers\ArrayHelper;


/**
 * This is the model class for table "sci".
 *
 * @property integer $id_sci
 * @property string $nombre
 * @property string $direccion
 * @property string $observacion
 * @property string $departamento_programa
 * @property integer $comuna_comuna_id
 * @property string $creado_en
 * @property string $modificado_en
 *
 * @property ContactoSci[] $contactoScis
 * @property ContactoWeb[] $contactoWebs
 * @property Requerimiento[] $requerimientos
 * @property Comuna $comunaComuna
 * @property Sede $sedeIdSede
 */
class Sci extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sci';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nombre', 'direccion', 'comuna_comuna_id', 'sede_id_sede'], 'required'],
            [['comuna_comuna_id'], 'integer'],
            [['creado_en', 'modificado_en'], 'safe'],
            [['nombre', 'direccion', 'departamento_programa'], 'string', 'max' => 255],
            [['observacion'], 'string', 'max' => 500],
            [['comuna_comuna_id'], 'exist', 'skipOnError' => true, 'targetClass' => Comuna::className(), 'targetAttribute' => ['comuna_comuna_id' => 'comuna_id']],
            [['sede_id_sede'], 'exist', 'skipOnError' => true, 'targetClass' => Sede::className(), 'targetAttribute' => ['sede_id_sede' => 'id_sede']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_sci' => 'Id Sci',
            'nombre' => 'Nombre',
            'direccion' => 'Dirección',
            'observacion' => 'Observación',
            'departamento_programa' => 'Departamento/Programa',
            'comuna_comuna_id' => 'Comuna',
            'sede_id_sede' => 'Sede',
            'creado_en' => 'Creado En',
            'modificado_en' => 'Modificado En',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContactoScis()
    {
        return $this->hasMany(ContactoSci::className(), ['sci_id_sci' => 'id_sci']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContactoWebs()
    {
        return $this->hasMany(ContactoWeb::className(), ['sci_id_sci' => 'id_sci']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRequerimientos()
    {
        return $this->hasMany(Requerimiento::className(), ['sci_id_sci' => 'id_sci']);
    }
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getScbs()
    {
        return $this->hasMany(Scb::className(), ['sci_id_sci' => 'id_sci']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getComunaComuna()
    {
        return $this->hasOne(Comuna::className(), ['comuna_id' => 'comuna_comuna_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSedeIdSede()
    {
        return $this->hasOne(Sede::className(), ['id_sede' => 'sede_id_sede']);
    }

    public function getnombreComuna()
    {
        return $this->nombre.' ('.$this->comunaComuna->comuna_nombre.')';
    }
    public function getSciLista(){
        $droptions = Sci::find()->all();
        return ArrayHelper::map($droptions, 'id_sci', 'nombre');
    }

    public function getComunaLista(){
        $droptions = Comuna::find()->all();
        return ArrayHelper::map($droptions, 'comuna_id', 'comuna_nombre');
    }

    public function getSedeLista(){
        $droptions = Sede::find()->all();
        return ArrayHelper::map($droptions, 'id_sede', 'nombre_sede');
    }

    public function getRequerimientosNoAsignados(){
        return Requerimiento::find()->joinWith('estadoEjecucionIdEstado')->where(['sci_id_sci' => $this->id_sci])
            ->andWhere(['estado_ejecucion.nombre_estado' => 'No Asignado'])->count();
    }


    public function getRequerimientosNoAsignadosYPreseleccionados(){
        return Requerimiento::find()->joinWith('estadoEjecucionIdEstado')->where(['sci_id_sci' => $this->id_sci,'preseleccionado_match1' => '1'])
            ->andWhere(['estado_ejecucion.nombre_estado' => 'No Asignado'])->count();
    }

    public function getRequerimientosNoAsignadosYNoPreseleccionados(){
        return Requerimiento::find()->joinWith('estadoEjecucionIdEstado')->where(['sci_id_sci' => $this->id_sci, 'preseleccionado_match1' => '0'])
            ->andWhere(['estado_ejecucion.nombre_estado' => 'No Asignado'])->count();
    }

    public function getRequerimientosPreseleccionadosMatch1(){
        return $this->hasMany(Requerimiento::className(), ['sci_id_sci' => 'id_sci'])
            ->andOnCondition(['preseleccionado_match1' => '1']); //PRESELECCIONADO
    }
}
