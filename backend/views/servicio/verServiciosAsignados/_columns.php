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
        'attribute'=>'asignatura_cod_asignatura',
        'vAlign' => 'middle',
        'group' => true,
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'label' => 'Nombre Asignatura',
        'attribute'=>'asignatura_nombre',
        'value'=>function ($model, $key, $index, $column) {
            return $model->asignaturaCodAsignatura->nombre_asignatura;
        },
        'vAlign' => 'middle',
        'group' => true,
        'subGroupOf' => 2,
        'contentOptions' =>
            [
                'style'=>'width: 300px; overflow: auto; word-wrap: break-word; white-space: pre-wrap;'
            ],
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'label' => 'Sede Asignatura',
        'attribute'=>'asignatura_sede',
        'value'=>function ($model, $key, $index, $column) {
            return $model->asignaturaCodAsignatura->carreraCodCarrera->facultadIdFacultad->sedeIdSede->nombre_sede;
        },
        'vAlign' => 'middle',
        'group' => true,
        'subGroupOf' => 2,
        'contentOptions' =>
            [
                'style'=>'width: 300px; overflow: auto; word-wrap: break-word; white-space: pre-wrap;'
            ],
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'label' => 'Título del Servicio Asignado',
        'attribute'=>'servicio_titulo',
        'value'=>function ($model, $key, $index, $column) {
            return $model->servicioIdServicio->titulo;
        },
        'vAlign' => 'middle',
        'group' => true,
        'subGroupOf' => 2,
        'contentOptions' =>
            [
                'style'=>'width: 300px; overflow: auto; word-wrap: break-word; white-space: pre-wrap;'
            ],
    ],
    /*
    [
        'class'=>'\kartik\grid\DataColumn',
        'format' => 'html',
        'label'=>'Requerimiento/s',
        'vAlign' => 'middle',
        'mergeHeader' => true,
        'value'=>function ($model, $key, $index, $column) {
            $ides = explode(",", $key);
            $cadena = "";
            $count = 1;
            foreach ($ides as $id){
                $match = \backend\models\Match1::findOne(intval($id));
                if($match != null){
                    $cadena = $cadena.$count.") Título: ".$match->requerimientoIdRequerimiento->titulo." Descripción: "
                        .$match->requerimientoIdRequerimiento->descripcion."<br>";
                }
                $count++;
            }
            return $cadena;
        },
        'contentOptions' =>
            [
                'style'=>'width: 300px; overflow: auto; word-wrap: break-word; white-space: pre-wrap;'
            ],
    ],*/
    [
        'class'=>'\kartik\grid\DataColumn',
        'format' => 'html',
        'label'=>'Título Requerimiento Asignado',
        'attribute' => 'requerimiento_titulo',
        'vAlign' => 'middle',
        'value'=>function ($model, $key, $index, $column) {
            return $model->requerimientoIdRequerimiento->titulo;
        },
        'contentOptions' =>
            [
                'style'=>'width: 300px; overflow: auto; word-wrap: break-word; white-space: pre-wrap;'
            ],
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'label'=>'Acciones Servicio',
        'mergeHeader' => true,
        'group'=>true,
        'vAlign' => \kartik\grid\GridView::ALIGN_MIDDLE,
        'hAlign' => \kartik\grid\GridView::ALIGN_CENTER,
        'subGroupOf'=>3,
        'format'=> 'raw',
        'value'=>function ($model, $index, $widget) {
            return \kartik\helpers\Html::a('<span class="glyphicon glyphicon-pencil"></span>',
                    Url::to(["/servicio/update",'id'=>$model->servicioIdServicio->id_servicio]),
                    ['role'=>'modal-remoteX','title'=>'Modificar Servicio',
                    'data-toggle'=>'tooltip', 'data-pjax'=>0])."&nbsp;"
                    .\kartik\helpers\Html::a('<span class="glyphicon glyphicon-trash"></span>',
                    Url::to(["/servicio/delete",'id'=>$model->servicioIdServicio->id_servicio]),
                    ['role'=>'modal-remote','title'=>'Eliminar Servicio',
                    'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
                    'data-request-method'=>'post',
                    'data-toggle'=>'tooltip',
                    'data-confirm-title'=>'Confirmación',
                    'data-confirm-message'=>'Está seguro que desea eliminar este servicio?']);
        },

    ],

];   