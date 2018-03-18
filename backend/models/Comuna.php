<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "comuna".
 *
 * @property integer $comuna_id
 * @property string $comuna_nombre
 *
 * @property Sci[] $scis
 */
class Comuna extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'comuna';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['comuna_id', 'comuna_nombre'], 'required'],
            [['comuna_id'], 'integer'],
            [['comuna_nombre'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'comuna_id' => 'Comuna ID',
            'comuna_nombre' => 'Nombre de la Comuna',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getScis()
    {
        return $this->hasMany(Sci::className(), ['comuna_comuna_id' => 'comuna_id']);
    }
}
