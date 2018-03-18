<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "etiqueta_area".
 *
 * @property integer $id_etiqueta_area
 * @property string $nombre_etiqueta_area
 * @property string $descripcion_etiqueta
 * @property integer $frecuencia
 *
 * @property AsignaturaHasEtiquetaArea[] $asignaturaHasEtiquetaAreas
 * @property Asignatura[] $asignaturaCodAsignaturas
 * @property RequerimientoHasEtiquetaArea[] $requerimientoHasEtiquetaAreas
 * @property Requerimiento[] $requerimientoIdRequerimientos
 */
class EtiquetaArea extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'etiqueta_area';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nombre_etiqueta_area'], 'required'],
            [['frecuencia'], 'integer'],
            [['nombre_etiqueta_area'], 'string', 'max' => 45],
            [['descripcion_etiqueta'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_etiqueta_area' => 'Id Etiqueta Area',
            'nombre_etiqueta_area' => 'Nombre Etiqueta Area',
            'descripcion_etiqueta' => 'Descripcion Etiqueta',
            'frecuencia' => 'Frecuencia',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAsignaturaHasEtiquetaAreas()
    {
        return $this->hasMany(AsignaturaHasEtiquetaArea::className(), ['etiqueta_area_id_etiqueta_area' => 'id_etiqueta_area']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAsignaturaCodAsignaturas()
    {
        return $this->hasMany(Asignatura::className(), ['cod_asignatura' => 'asignatura_cod_asignatura'])->viaTable('asignatura_has_etiqueta_area', ['etiqueta_area_id_etiqueta_area' => 'id_etiqueta_area']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRequerimientoHasEtiquetaAreas()
    {
        return $this->hasMany(RequerimientoHasEtiquetaArea::className(), ['etiqueta_area_id_etiqueta_area' => 'id_etiqueta_area']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRequerimientoIdRequerimientos()
    {
        return $this->hasMany(Requerimiento::className(), ['id_requerimiento' => 'requerimiento_id_requerimiento'])->viaTable('requerimiento_has_etiqueta_area', ['etiqueta_area_id_etiqueta_area' => 'id_etiqueta_area']);
    }

    //2 AMIGOS
    /*public static function findAllByName($nombre_etiqueta)
    {
        return EtiquetaArea::find()
            ->where(['like', '$nombre_etiqueta', $nombre_etiqueta])->all();
    }*/
}
