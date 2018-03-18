<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "contacto_scb".
 *
 * @property integer $id_contacto_scb
 * @property string $rut_beneficiario
 * @property integer $scb_id_scb
 * @property string $nombre_completo
 * @property string $email
 * @property string $direccion
 * @property double $telefono_celular
 * @property double $telefono_fijo
 *
 * @property Scb $scbIdScb
 */
class ContactoScb extends \yii\db\ActiveRecord
{
    const SCENARIO_SCB = 'scb';
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'contacto_scb';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['scb_id_scb'], 'required', 'on' => self::SCENARIO_SCB],
            ['rut_beneficiario', \sateler\rut\RutValidator::className()],
            [['nombre_completo', 'email'], 'required'],
            [['scb_id_scb'], 'integer'],
            [['telefono_celular', 'telefono_fijo'], 'number'],
            ['email', 'email'],
            [['rut_beneficiario', 'email', 'direccion'], 'string', 'max' => 45],
            [['nombre_completo'], 'string', 'max' => 255],
            [['scb_id_scb'], 'exist', 'skipOnError' => true, 'targetClass' => Scb::className(), 'targetAttribute' => ['scb_id_scb' => 'id_scb']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_contacto_scb' => 'Id Contacto Scb',
            'rut_beneficiario' => 'RUT Beneficiario',
            'scb_id_scb' => 'Scb Id Scb',
            'nombre_completo' => 'Nombre Completo',
            'email' => 'Email',
            'direccion' => 'Dirección',
            'telefono_celular' => 'Teléfono Celular',
            'telefono_fijo' => 'Teléfono Fijo',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getScbIdScb()
    {
        return $this->hasOne(Scb::className(), ['id_scb' => 'scb_id_scb']);
    }
}
