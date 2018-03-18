<?php
/**
 * Created by PhpStorm.
 * User: Howl
 * Date: 29-05-2017
 * Time: 4:18
 */

namespace backend\models;

use yii\base\model;

class AnioSemestre extends Model
{
    public $anio;
    public $semestre;

    public function rules()
    {
        return [
            // Application Name
            ['anio', 'required'],
            ['anio', 'integer'],
            ['anio', 'match', 'pattern' => '/^\d{4}$/', 'message' => 'El Año debe contener 4 dígitos.'],
            ['semestre', 'required'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'anio' => 'Año',
            'semestre' => 'Semestre',

        ];
    }
}