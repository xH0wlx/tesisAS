<?php

namespace backend\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "oferta".
 *
 * @property integer $id_oferta
 * @property integer $asignatura_cod_asignatura
 * @property integer $periodo_id_periodo
 * @property string $creado_en
 * @property string $modificado_en
 *
 * @property DocenteHasOferta[] $docenteHasOfertas
 * @property Docente[] $docenteRutDocentes
 * @property Asignatura $asignaturaCodAsignatura
 * @property Periodo $periodoIdPeriodo
 * @property Servicio[] $servicios
 */
class Oferta extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'oferta';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['asignatura_cod_asignatura', 'periodo_id_periodo'], 'required'],
            [['asignatura_cod_asignatura', 'periodo_id_periodo'], 'integer'],
            [['creado_en', 'modificado_en'], 'safe'],
            [['asignatura_cod_asignatura'], 'exist', 'skipOnError' => true, 'targetClass' => Asignatura::className(), 'targetAttribute' => ['asignatura_cod_asignatura' => 'cod_asignatura']],
            [['periodo_id_periodo'], 'exist', 'skipOnError' => true, 'targetClass' => Periodo::className(), 'targetAttribute' => ['periodo_id_periodo' => 'id_periodo']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_oferta' => 'Id Oferta',
            'asignatura_cod_asignatura' => 'Código de Asignatura',
            'periodo_id_periodo' => 'Periodo',
            'creado_en' => 'Creado En',
            'modificado_en' => 'Modificado En',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDocenteHasOfertas()
    {
        return $this->hasMany(DocenteHasOferta::className(), ['oferta_id_oferta' => 'id_oferta']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDocenteRutDocentes()
    {
        return $this->hasMany(Docente::className(), ['rut_docente' => 'docente_rut_docente'])->viaTable('docente_has_oferta', ['oferta_id_oferta' => 'id_oferta']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAsignaturaCodAsignatura()
    {
        return $this->hasOne(Asignatura::className(), ['cod_asignatura' => 'asignatura_cod_asignatura']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPeriodoIdPeriodo()
    {
        return $this->hasOne(Periodo::className(), ['id_periodo' => 'periodo_id_periodo']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getServicios()
    {
        return $this->hasMany(Servicio::className(), ['oferta_id_oferta' => 'id_oferta']);
    }

    public function getSedeLista(){
        $droptions = Sede::find()->asArray()->all();
        return ArrayHelper::map($droptions, 'id_sede', 'nombre_sede');
    }

    public function getPeriodoLista(){
        $droptions = Periodo::find()->all();
        return ArrayHelper::map($droptions, 'id_periodo', 'anioSemestre');
    }
}
