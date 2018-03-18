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
        'label' => 'Socio Comunitario Institucional',
        'attribute'=>'socio_institucional_nombre_comuna',
        'group'=>true,
        'vAlign' => \kartik\grid\GridView::ALIGN_MIDDLE,
        'value'=>function ($model, $index, $widget) {
            return $model->requerimientoIdRequerimiento->sciIdSci->nombreComuna;
        },
        'contentOptions' =>
            [
                'style'=>'width: 250px; overflow: auto; word-wrap: break-word; white-space: pre-wrap;'
            ],
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'label' => 'Titulo del Requerimiento',
        'attribute'=>'requerimiento_titulo',
        'value'=>function ($model, $index, $widget) {
            return $model->requerimientoIdRequerimiento->titulo;
        },
        'group'=>true,
        'vAlign' => \kartik\grid\GridView::ALIGN_MIDDLE,
        'subGroupOf'=>3,
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'requerimiento_descripcion',
        'value'=>function ($model, $index, $widget) {
            return $model->requerimientoIdRequerimiento->descripcion;
        },
        'group'=>true,
        'vAlign' => \kartik\grid\GridView::ALIGN_MIDDLE,
        'subGroupOf'=>3,
        'contentOptions' =>
            [
                'style'=>'width: 300px; overflow: auto; word-wrap: break-word; white-space: pre-wrap;'
            ],
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'asignatura_cod_asignatura',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'asignatura_nombre',
        'value'=>function ($model, $index, $widget) {
            return $model->asignaturaCodAsignatura->nombre_asignatura;
        },
        'contentOptions' =>
            [
                'style'=>'width: 300px; overflow: auto; word-wrap: break-word; white-space: pre-wrap;'
            ],
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'asignatura_semestre_dicta',
        'value'=>function ($model, $index, $widget) {
            return $model->asignaturaCodAsignatura->semestre_dicta;
        },
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'asignatura_sede',
        'value'=>function ($model, $index, $widget) {
            return $model->asignaturaCodAsignatura->carreraCodCarrera->facultadIdFacultad->sedeIdSede->nombre_sede;
        },
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'label'=>'Acciones',
        'mergeHeader' => true,
        'group'=>true,
        'vAlign' => \kartik\grid\GridView::ALIGN_MIDDLE,
        'hAlign' => \kartik\grid\GridView::ALIGN_CENTER,
        'subGroupOf'=>3,
        'format'=> 'html',
        'value'=>function ($model, $index, $widget) {
            $action = "/match1/seleccion3";
            $url = Url::to([$action,'id'=>$model->requerimiento_id_requerimiento, '#'=> 'seleccionados']);
            return "<a href='".$url."' title='Modificar Asignación' 'role'='modal-remoteX' 'data-pjax'='false'><span class=\"glyphicon glyphicon-pencil\"></span></a>";
        },
        'contentOptions' =>
            [
                'style'=>'width: 300px; overflow: auto; word-wrap: break-word; white-space: pre-wrap;'
            ],
    ],
/*    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'vAlign'=>'middle',
        'urlCreator' => function($action, $model, $key, $index) {
            if($action == "update"){
                $action = "/match1/seleccion3";
                return Url::to([$action,'id'=>$model->requerimiento_id_requerimiento, '#'=> 'seleccionados']);
            }
            return Url::to([$action,'id'=>$key]);
        },
        'template' => '{update}',
        'viewOptions'=>['role'=>'modal-remote','title'=>'Ver','data-toggle'=>'tooltip'],
        'updateOptions'=>['role'=>'modal-remoteX','title'=>'Modificar Asignación', 'data-toggle'=>'tooltip'],
        'deleteOptions'=>['role'=>'modal-remote','title'=>'Eliminar Asignatura',
            'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
            'data-request-method'=>'post',
            'data-toggle'=>'tooltip',
            'data-confirm-title'=>'Confirmación',
            'data-confirm-message'=>'Está seguro que desea eliminar este registro?'],
    ],*/

];   