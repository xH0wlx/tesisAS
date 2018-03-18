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
/*        [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'id_carrera',
    ],*/
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'cod_carrera',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'nombre_carrera',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'plan_carrera',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'alias_carrera',
    ],
/*    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'facultad_id_facultad',
        'value'=>function ($model, $index, $widget) { return $model->facultadIdFacultad->nombre_facultad; }
    ],*/
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'carrera_sede',
        'label'=> 'Sede',
        'value'=>function ($model, $index, $widget) { return $model->facultadIdFacultad->sedeIdSede->nombre_sede; }
    ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'vAlign'=>'middle',
        'urlCreator' => function($action, $model, $key, $index) { 
                return Url::to([$action,'id'=>$key]);
        },
        'template' => '{update} {delete}',
        //'viewOptions'=>['role'=>'modal-remote','title'=>'View','data-toggle'=>'tooltip'],
        'updateOptions'=>['role'=>'modal-remote','title'=>'Modificar', 'data-toggle'=>'tooltip'],
        'deleteOptions'=>['role'=>'modal-remote','title'=>'Eliminar',
            'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
            'data-request-method'=>'post',
            'data-toggle'=>'tooltip',
            'data-confirm-title'=>'Confirmación',
            'data-confirm-message'=>'Está seguro que desea eliminar este elemento?'],
    ],

];   