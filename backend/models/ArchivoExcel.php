<?php
/**
 * Created by PhpStorm.
 * User: Howl
 * Date: 06-06-2017
 * Time: 20:25
 */

namespace backend\models;


class ArchivoExcel extends Model
{
    public $archivoExcel;

    public function rules()
    {
        return [
            // Application Name
            ['archivoExcel', 'required'],
            [['archivoExcel'], 'safe'],
            [['archivoExcel'], 'file', 'extensions'=>'xls, xlsx'],
            [['archivoExcel'], 'file', 'maxSize'=>'2000000', 'tooBig' => 'El limite de peso del archivo es 2Mb'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'archivoExcel' => 'Planilla Excel',

        ];
    }

}
