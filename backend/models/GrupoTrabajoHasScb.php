<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "grupo_trabajo_has_scb".
 *
 * @property integer $id_grupo_trabajo_has_scb
 * @property integer $grupo_trabajo_id_grupo_trabajo
 * @property integer $scb_id_scb
 * @property string $creado_en
 * @property string $modificado_en
 * @property string $observacion
 *
 * @property GrupoTrabajo $grupoTrabajoIdGrupoTrabajo
 * @property Scb $scbIdScb
 */
class GrupoTrabajoHasScb extends \yii\db\ActiveRecord
{
    const SCENARIO_OBSERVACION = 'observacion';
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'grupo_trabajo_has_scb';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['observacion'], 'required', 'on' => self::SCENARIO_OBSERVACION],
            [['scb_id_scb'], 'required', 'on' => self::SCENARIO_OBSERVACION],
            [['grupo_trabajo_id_grupo_trabajo'], 'required'],
            [['grupo_trabajo_id_grupo_trabajo', 'scb_id_scb', 'cambio'], 'integer'],
            [['creado_en', 'modificado_en'], 'safe'],
            [['observacion'], 'string', 'max' => 200],
            [['grupo_trabajo_id_grupo_trabajo'], 'exist', 'skipOnError' => true, 'targetClass' => GrupoTrabajo::className(), 'targetAttribute' => ['grupo_trabajo_id_grupo_trabajo' => 'id_grupo_trabajo']],
            [['scb_id_scb'], 'exist', 'skipOnError' => true, 'targetClass' => Scb::className(), 'targetAttribute' => ['scb_id_scb' => 'id_scb']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_grupo_trabajo_has_scb' => 'Id Grupo Trabajo Has Scb',
            'grupo_trabajo_id_grupo_trabajo' => 'Grupo Trabajo Id Grupo Trabajo',
            'scb_id_scb' => 'Scb Id Scb',
            'creado_en' => 'Creadon En',
            'modificado_en' => 'Modificado En',
            'observacion' => 'Observacion',
            'cambio' => 'Lo Cambiaron? 1 sí, 0 no',
        ];
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
    public function getScbIdScb()
    {
        return $this->hasOne(Scb::className(), ['id_scb' => 'scb_id_scb']);
    }
}
