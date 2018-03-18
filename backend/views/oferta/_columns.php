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
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'vAlign'=>'middle',
        'urlCreator' => function($action, $model, $key, $index) {
            return Url::to([$action,'id'=>$key]);
        },
        'viewOptions'=>['role'=>'modal-remoteX','title'=>'View','data-toggle'=>'tooltip'],
        'updateOptions'=>['role'=>'modal-remoteX','title'=>'Update', 'data-toggle'=>'tooltip'],
        'deleteOptions'=>['role'=>'modal-remoteX','title'=>'Delete',
            'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
            'data-request-method'=>'post',
            'data-toggle'=>'tooltip',
            'data-confirm-title'=>'Are you sure?',
            'data-confirm-message'=>'Are you sure want to delete this item'],
    ],
/*    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'id_oferta',
    ],*/
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'asignatura_cod_asignatura',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'asignatura_nombre',
        'label' => 'Nombre Asignatura',
        'value'=>function ($model, $index, $widget) { return $model->asignaturaCodAsignatura->nombre_asignatura; }
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'periodo_id_periodo',
        'value'=>function ($model, $index, $widget) { return $model->periodoIdPeriodo->anioSemestre; }
    ],
/*    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'creado_en',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'modificado_en',
    ],*/

];   