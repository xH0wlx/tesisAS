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
        // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'id',
    // ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'username',
        'format' => 'rut',
        //'filterInputOptions'=>['placeholder' => 'Buscar sin puntos ni guión'],
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'nombre_completo',

    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'format' => "email",
        'attribute'=>'email',
    ],
/*    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'tipo_usuario_id',
    ],*/
/*    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'tipo_usuario_nombre',
        'label' => 'Rol',
        'value'=>function ($model, $key, $index, $column) { return $model->tipoUsuarioIdTipoUsuario->nombre; },
    ],*/
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'tipo_usuario_nombre',
        'label' => 'Rol',
        'value'=>function ($model, $key, $index, $column) { return $model->rolAsignado->item_name; },
    ],
/*    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'auth_key',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'password_hash',
    ],*/
/*    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'password_reset_token',
    ],*/
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'estado_id',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'rol_id',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'tipo_usuario_id',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'created_at',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'updated_at',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'verification_code',
    // ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'vAlign'=>'middle',
        'urlCreator' => function($action, $model, $key, $index) { 
                return Url::to([$action,'id'=>$key]);
        },
        'template' => "{update} {delete}",
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