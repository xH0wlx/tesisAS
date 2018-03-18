<?php

namespace backend\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "docente_has_servicio".
 *
 * @property string $docente_rut_docente
 * @property integer $servicio_id_servicio
 *
 * @property Docente $docenteRutDocente
 * @property Servicio $servicioIdServicio
 */
class DocenteHasServicio extends \yii\db\ActiveRecord
{
    const SCENARIO_APARTE = 'aparte';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'docente_has_servicio';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['servicio_id_servicio'], 'required', 'on' => self::SCENARIO_APARTE],
            [['docente_rut_docente'], 'required'],
            [['servicio_id_servicio'], 'integer'],
            [['docente_rut_docente'], 'string', 'max' => 45],
            [['docente_rut_docente'], 'exist', 'skipOnError' => true, 'targetClass' => Docente::className(), 'targetAttribute' => ['docente_rut_docente' => 'rut_docente']],
            [['servicio_id_servicio'], 'exist', 'skipOnError' => true, 'targetClass' => Servicio::className(), 'targetAttribute' => ['servicio_id_servicio' => 'id_servicio']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'docente_rut_docente' => 'Docente',
            'servicio_id_servicio' => 'Servicio Id Servicio',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDocenteRutDocente()
    {
        return $this->hasOne(Docente::className(), ['rut_docente' => 'docente_rut_docente']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getServicioIdServicio()
    {
        return $this->hasOne(Servicio::className(), ['id_servicio' => 'servicio_id_servicio']);
    }

    public function getDocenteLista(){
        $droptions = Docente::find()->asArray()->all();
        return ArrayHelper::map($droptions, 'rut_docente', 'nombre_completo');
    }
}
