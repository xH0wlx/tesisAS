<?php

namespace backend\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "contacto_sci".
 *
 * @property integer $id_contacto_sci
 * @property string $nombres
 * @property string $apellidos
 * @property string $telefono
 * @property string $cargo
 * @property string $email
 * @property string $observacion
 * @property integer $sci_id_sci
 * @property string $creado_en
 * @property string $modificado_en
 *
 * @property Sci $sciIdSci
 */
class ContactoSci extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'contacto_sci';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nombres', 'apellidos', 'email', 'telefono'], 'required'],
            [['sci_id_sci'], 'integer'],
            [['email'], 'email'],
            [['creado_en', 'modificado_en'], 'safe'],
            [['nombres', 'apellidos', 'telefono', 'cargo', 'email'], 'string', 'max' => 255],
            [['telefono'], 'integer'],
            [['observacion'], 'string', 'max' => 500],
            [['sci_id_sci'], 'exist', 'skipOnError' => true, 'targetClass' => Sci::className(), 'targetAttribute' => ['sci_id_sci' => 'id_sci']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_contacto_sci' => 'Id Contacto Sci',
            'nombres' => 'Nombres',
            'apellidos' => 'Apellidos',
            'telefono' => 'Teléfono',
            'cargo' => 'Cargo',
            'email' => 'Email',
            'observacion' => 'Observación',
            'sci_id_sci' => 'Socio Comunitario Institucional',
            'creado_en' => 'Creado En',
            'modificado_en' => 'Modificado En',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSciIdSci()
    {
        return $this->hasOne(Sci::className(), ['id_sci' => 'sci_id_sci']);
    }

    public function getSocioILista(){
        $droptions = Sci::find()->all();
        return ArrayHelper::map($droptions, 'id_sci', 'nombreComuna');
    }
}
