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
        'attribute'=>'id_scb',
    ],*/
    [
        'class'=>'\kartik\grid\DataColumn',
        'label' => 'Nombre Socio Comunitario Institucional',
        'attribute'=>'scb_nombre_socio',
        'value'=>function ($model, $index, $widget) {
            return $model->sciIdSci->nombre;
        },
        'vAlign' => 'middle',
        'group' => true,
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'label' => 'Comuna Socio Comunitario Institucional',
        'attribute'=>'scb_comuna_sci',
        'value'=>function ($model, $index, $widget) {
            return $model->sciIdSci->comunaComuna->comuna_nombre;
        },
        'contentOptions' =>
            [
                'style'=>'width: 300px; overflow: auto; word-wrap: break-word; white-space: pre-wrap;'
            ],
        'group' => true,
        'subGroupOf' => 2,
        'vAlign' => 'middle',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'nombre_negocio',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'actividad_rubro_giro',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'numero_trabajadores',
    ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'tiempo_en_la_actividad',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'productos_yo_servicios',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'descripcion_clientes',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'descripcion_proveedores',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'direccion_comercial',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'contabilidad',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'patente',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'sitio_web',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'red_social',
    // ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'vAlign'=>'middle',
        'urlCreator' => function($action, $model, $key, $index) { 
                return Url::to([$action,'id'=>$key]);
        },
        'viewOptions'=>['role'=>'modal-remoteX','title'=>'Ver','data-toggle'=>'tooltip'],
        'updateOptions'=>['role'=>'modal-remoteX','title'=>'Modificar', 'data-toggle'=>'tooltip'],
        'deleteOptions'=>['role'=>'modal-remote','title'=>'Eliminar',
                          'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
                          'data-request-method'=>'post',
                          'data-toggle'=>'tooltip',
                          'data-confirm-title'=>'Confirmación',
                          'data-confirm-message'=>'Está seguro que desea eliminar este registro?'],
    ],

];   