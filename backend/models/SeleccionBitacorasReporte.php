<?php
/**
 * Created by PhpStorm.
 * User: Howl
 * Date: 29-05-2017
 * Time: 4:18
 */

namespace backend\models;

use yii\base\model;

class SeleccionBitacorasReporte extends Model
{
    public $anio;
    public $semestre;
    public $implementacion;
    public $seccion;
    public $grupo;
    public $Bitacora;

    public function rules()
    {
        return [
            // Application Name
            ['anio', 'required'],
            ['semestre', 'required'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'anio'          => 'Año',
            'semestre' => 'Semestre',

        ];
    }
}