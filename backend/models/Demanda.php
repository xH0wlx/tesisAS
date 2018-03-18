<?php

namespace backend\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "demanda".
 *
 * @property integer $id_demanda
 * @property string $perfil_estudiante
 * @property string $apoyos_brindados
 * @property string $observaciones
 * @property string $fecha_creacion
 * @property integer $sci_id_sci
 *
 * @property Sci $sciIdSci
 * @property Requerimiento[] $requerimientos
 */
class Demanda extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'demanda';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['perfil_estudiante', 'apoyos_brindados', 'observaciones'], 'string'],
            [['fecha_creacion'], 'safe'],
            [['sci_id_sci'], 'required'],
            [['sci_id_sci'], 'integer'],
            [['sci_id_sci'], 'exist', 'skipOnError' => true, 'targetClass' => Sci::className(), 'targetAttribute' => ['sci_id_sci' => 'id_sci']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_demanda' => 'Id Demanda',
            'perfil_estudiante' => 'Perfil del Estudiante',
            'apoyos_brindados' => 'Apoyos Brindados',
            'observaciones' => 'Observaciones',
            'fecha_creacion' => 'Fecha Creacion',
            'sci_id_sci' => 'Socio Comunitario Institucional',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSciIdSci()
    {
        return $this->hasOne(Sci::className(), ['id_sci' => 'sci_id_sci']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRequerimientos()
    {
        return $this->hasMany(Requerimiento::className(), ['demanda_id_demanda' => 'id_demanda']);
    }


    public function getSocioInstitucionalLista(){
        $droptions = Sci::find()->all();
        return ArrayHelper::map($droptions, 'id_sci', 'nombreComuna');
    }
}
