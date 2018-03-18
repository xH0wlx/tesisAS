<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "contacto_web".
 *
 * @property integer $id_contacto_web
 * @property string $direccion_web
 * @property integer $sci_id_sci
 *
 * @property Sci $sciIdSci
 */
class ContactoWeb extends \yii\db\ActiveRecord
{
    const SCENARIO_SCI = 'sci';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'contacto_web';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['direccion_web'], 'required', 'on' => self::SCENARIO_SCI],
            //[['direccion_web'], 'required'],
            [['sci_id_sci'], 'integer'],
            [['direccion_web'], 'string', 'max' => 45],
            [['sci_id_sci'], 'exist', 'skipOnError' => true, 'targetClass' => Sci::className(), 'targetAttribute' => ['sci_id_sci' => 'id_sci']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_contacto_web' => 'Id Contacto Web',
            'direccion_web' => 'Dirección Web',
            'sci_id_sci' => 'Sci Id Sci',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSciIdSci()
    {
        return $this->hasOne(Sci::className(), ['id_sci' => 'sci_id_sci']);
    }
}
