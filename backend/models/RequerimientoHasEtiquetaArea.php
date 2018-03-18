<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "requerimiento_has_etiqueta_area".
 *
 * @property integer $requerimiento_id_requerimiento
 * @property integer $etiqueta_area_id_etiqueta_area
 *
 * @property EtiquetaArea $etiquetaAreaIdEtiquetaArea
 * @property Requerimiento $requerimientoIdRequerimiento
 */
class RequerimientoHasEtiquetaArea extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'requerimiento_has_etiqueta_area';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['requerimiento_id_requerimiento', 'etiqueta_area_id_etiqueta_area'], 'required'],
            [['requerimiento_id_requerimiento', 'etiqueta_area_id_etiqueta_area'], 'integer'],
            [['etiqueta_area_id_etiqueta_area'], 'exist', 'skipOnError' => true, 'targetClass' => EtiquetaArea::className(), 'targetAttribute' => ['etiqueta_area_id_etiqueta_area' => 'id_etiqueta_area']],
            [['requerimiento_id_requerimiento'], 'exist', 'skipOnError' => true, 'targetClass' => Requerimiento::className(), 'targetAttribute' => ['requerimiento_id_requerimiento' => 'id_requerimiento']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'requerimiento_id_requerimiento' => 'Requerimiento Id Requerimiento',
            'etiqueta_area_id_etiqueta_area' => 'Etiqueta Area Id Etiqueta Area',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEtiquetaAreaIdEtiquetaArea()
    {
        return $this->hasOne(EtiquetaArea::className(), ['id_etiqueta_area' => 'etiqueta_area_id_etiqueta_area']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRequerimientoIdRequerimiento()
    {
        return $this->hasOne(Requerimiento::className(), ['id_requerimiento' => 'requerimiento_id_requerimiento']);
    }
}
