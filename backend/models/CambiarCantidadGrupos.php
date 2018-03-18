<?php
/**
 * Created by PhpStorm.
 * User: Howl
 * Date: 20-09-2017
 * Time: 3:19
 */

namespace backend\models;


class CambiarCantidadGrupos extends Model
{
    public $cantidad;
    public $idSeccion;
    public $idImplementacion;

    public function rules()
    {
        return [
            // Application Name
            ['cantidad', 'required'],
            [['cantidad', 'idSeccion', 'idImplementacion'], 'integer'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'cantidad' => 'Cantidad',
            'idSeccion' => 'Sección',
            'idImplementacion' => 'Implementación',

        ];
    }
}