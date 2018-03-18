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
/*    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'id_match1',
    ],*/
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'requerimiento_id_requerimiento',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'requerimientoIdRequerimiento.titulo',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'requerimientoIdRequerimiento.descripcion',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'asignatura_cod_asignatura',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'asignaturaCodAsignatura.nombre_asignatura',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'asignaturaCodAsignatura.semestre_dicta',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'asignaturaCodAsignatura.semestre_malla',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'anio_match1',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'semestre_match1',
    ],
    // [
    // 'class'=>'\kartik\grid\DataColumn',
    // 'attribute'=>'servicio_id_servicio',
    // ],
    // [
    // 'class'=>'\kartik\grid\DataColumn',
    // 'attribute'=>'aprobacion_implementacion',
    // ],
    // [
    // 'class'=>'\kartik\grid\DataColumn',
    // 'attribute'=>'creado_en',
    // ],
    // [
    // 'class'=>'\kartik\grid\DataColumn',
    // 'attribute'=>'modificado_en',
    // ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'vAlign'=>'middle',
        'urlCreator' => function($action, $model, $key, $index) {
            return Url::to([$action,'id'=>$key]);
        },
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