<?php

namespace backend\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "grupo_trabajo_has_scb".
 *
 * @property integer $id_grupo_trabajo_has_scb
 * @property integer $grupo_trabajo_id_grupo_trabajo
 * @property integer $scb_id_scb
 * @property string $creado_en
 * @property string $modificado_en
 * @property string $observacion
 * @property string $cambio
 * @property string $id_reemplazo_scb
 *
 * @property GrupoTrabajo $grupoTrabajoIdGrupoTrabajo
 * @property Scb $scbIdScb
 */
class GrupoTrabajoHasScb extends \yii\db\ActiveRecord
{
    const SCENARIO_OBSERVACION = 'observacion';

    const ESTADO_ACTIVO = 0;
    const ESTADO_INACTIVO = 1;

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
            [['scb_id_scb'], 'required'],
            [['grupo_trabajo_id_grupo_trabajo'], 'required'],
            [['grupo_trabajo_id_grupo_trabajo', 'scb_id_scb', 'cambio'], 'integer'],
            [['creado_en', 'modificado_en'], 'safe'],
            [['observacion'], 'string', 'max' => 200],
            ['scb_id_scb', 'compare', 'compareAttribute' => 'id_reemplazo_scb', 'operator' => '!=', 'message' => 'No se puede reemplazar a sí mismo.'],
            [['grupo_trabajo_id_grupo_trabajo'], 'exist', 'skipOnError' => true, 'targetClass' => GrupoTrabajo::className(), 'targetAttribute' => ['grupo_trabajo_id_grupo_trabajo' => 'id_grupo_trabajo']],
            [['scb_id_scb'], 'exist', 'skipOnError' => true, 'targetClass' => Scb::className(), 'targetAttribute' => ['scb_id_scb' => 'id_scb']],
            [['id_reemplazo_scb'], 'exist', 'skipOnError' => true, 'targetClass' => GrupoTrabajoHasScb::className(), 'targetAttribute' => ['id_reemplazo_scb' => 'scb_id_scb']],
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
            'scb_id_scb' => 'Socio Comunitario Beneficiario',
            'creado_en' => 'Creado En',
            'modificado_en' => 'Modificado En',
            'observacion' => 'Observación',
            'cambio' => 'Lo Cambiaron? 1 sí, 0 no',
            'id_reemplazo_scb' => 'Reemplaza a',
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

    public function getReemplazado()
    {
        return $this->hasOne(Scb::className(), ['id_scb' => 'id_reemplazo_scb']);
    }

    public function isActive(){
        return ($this->cambio == self::ESTADO_ACTIVO);
    }

    public function getScbLista(){
        $droptions = Scb::find()->all();
        return ArrayHelper::map($droptions, 'id_scb', 'nombre_negocio');
    }
}
