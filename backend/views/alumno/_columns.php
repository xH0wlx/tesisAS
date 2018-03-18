<?php
use yii\helpers\Url;

return [
    [
        'class' => 'kartik\grid\CheckboxColumn',
        'width' => '20px',
    ],
    [
        'class' => 'kartik\grid\SerialColumn',
        'width' => '30px',
    ],
        [
        'class'=>'\kartik\grid\DataColumn',
        'format'=> 'rut',
        'attribute'=>'rut_alumno',
            'filterInputOptions'=>['placeholder' => 'Buscar sin puntos ni guión'],
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'nombre',
    ],
/*    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'telefono',
    ],*/
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'email',
    ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'vAlign'=>'middle',
        'urlCreator' => function($action, $model, $key, $index) { 
                return Url::to([$action,'id'=>$key]);
        },
        'template' => "{view} {update} {delete}",
        'viewOptions'=>['role'=>'modal-remote','title'=>'Ver','data-toggle'=>'tooltip'],
        'updateOptions'=>['role'=>'modal-remote','title'=>'Modificar', 'data-toggle'=>'tooltip'],
        'deleteOptions'=>['role'=>'modal-remote','title'=>'Eliminar',
                          'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
                          'data-request-method'=>'post',
                          'data-toggle'=>'tooltip',
                          'data-confirm-title'=>'Confirmación',
                          'data-confirm-message'=>'Está seguro que desea eliminar este registro?'],
    ],

];   