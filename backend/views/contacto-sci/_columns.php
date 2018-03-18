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
        'attribute'=>'id_contacto_sci',
    ],*/
    [
        //'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'contacto_socio_institucional',
        'label' => 'Socio C. Institucional',
        'value'=>function ($model, $key, $index, $column) { return $model->sciIdSci->nombre; },
        'group'=>true,
    ],
    [
        //'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'contacto_comuna',
        'label' => 'Comuna Socio',
        'value'=>function ($model, $key, $index, $column) { return $model->sciIdSci->comunaComuna->comuna_nombre; },
        'group'=>true,
        'subGroupOf'=>3,
    ],
    [
        //'class'=>'\kartik\grid\DataColumn',
        'label' => 'Sede Socio',
        'attribute'=>'contacto_sede',
        'value'=>function ($model, $key, $index, $column) { return $model->sciIdSci->sedeIdSede->nombre_sede; },
        'group'=>true,
        'subGroupOf'=>3,
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'nombres',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'apellidos',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'telefono',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'cargo',
    ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'vAlign'=>'middle',
        'urlCreator' => function($action, $model, $key, $index) {
            return Url::to([$action,'id'=>$key]);
        },
        'viewOptions'=>['role'=>'modal-remoteX','title'=>'Ver','data-toggle'=>'tooltip'],
        'updateOptions'=>['role'=>'modal-remoteX','title'=>'Modificar', 'data-toggle'=>'tooltip'],
        'deleteOptions'=>['role'=>'modal-remoteX','title'=>'Eliminar',
            'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
            'data-request-method'=>'post',
            'data-toggle'=>'tooltip',
            'data-confirm-title'=>'Are you sure?',
            'data-confirm-message'=>'Are you sure want to delete this item'],
    ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'email',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'observacion',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'sci_id_sci',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'creado_en',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'modificado_en',
    // ],


];   