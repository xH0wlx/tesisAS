<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "evidencia".
 *
 * @property integer $id_evidencia
 * @property string $descripcion
 * @property string $ruta_archivo
 * @property integer $bitacora_id_bitacora
 *
 * @property Bitacora $bitacoraIdBitacora
 */
class Evidencia extends \yii\db\ActiveRecord
{
    public $instancia_archivo;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'evidencia';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            //[['descripcion', 'ruta_archivo', 'bitacora_id_bitacora'], 'required'],
            [['instancia_archivo'], 'safe'],
            [['instancia_archivo'], 'file', 'extensions' => ['rar', 'zip'], 'maxSize' => 1024*1024*2],
            [['bitacora_id_bitacora'], 'integer'],
            [['descripcion'], 'string', 'max' => 200],
            [['ruta_archivo', 'nombre_archivo'], 'string', 'max' => 255],
            //[['ruta_archivo'], 'file'],
            [['bitacora_id_bitacora'], 'exist', 'skipOnError' => true, 'targetClass' => Bitacora::className(), 'targetAttribute' => ['bitacora_id_bitacora' => 'id_bitacora']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_evidencia' => 'Id Evidencia',
            'nombre_archivo' => 'Nombre Archivo',
            'descripcion' => 'Descripcion',
            'ruta_archivo' => 'Archivo',
            'bitacora_id_bitacora' => 'Id Bitacora',
            'instancia_archivo' => 'Archivo',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBitacoraIdBitacora()
    {
        return $this->hasOne(Bitacora::className(), ['id_bitacora' => 'bitacora_id_bitacora']);
    }
}
