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
        'attribute'=>'cod_asignatura',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'nombre_asignatura',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'semestre_dicta',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'semestre_malla',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'resultado_aprendizaje',
        'contentOptions' =>
            [
                'style'=>'min-width: 250px; overflow: auto; word-wrap: break-word; white-space: pre-wrap;'
            ],
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'asignatura_carrera',
        'label' => 'Carrera',
        'value'=>function ($model, $index, $widget) { return $model->carreraCodCarrera->alias_carrera; }
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'asignatura_sede',
        'label' => 'Sede',
        'value'=>function ($model, $index, $widget) { return $model->carreraCodCarrera->facultadIdFacultad->sedeIdSede->nombre_sede; }
    ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'vAlign'=>'middle',
        'urlCreator' => function($action, $model, $key, $index) {
            return Url::to([$action,'id'=>$key]);
        },
        'template' => '{update} {delete}',
        //'viewOptions'=>['role'=>'modal-remote','title'=>'Ver','data-toggle'=>'tooltip'],
        'updateOptions'=>['role'=>'modal-remoteX','title'=>'Modificar', 'data-toggle'=>'tooltip'],
        'deleteOptions'=>['role'=>'modal-remote','title'=>'Eliminar',
            'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
            'data-request-method'=>'post',
            'data-toggle'=>'tooltip',
            'data-confirm-title'=>'Confirmación',
            'data-confirm-message'=>'Está seguro que desea eliminar este elemento?'],
    ],

];   