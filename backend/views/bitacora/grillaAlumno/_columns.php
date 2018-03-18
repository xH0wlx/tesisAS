<?php
use yii\helpers\Url;

return [
    [
        'class' => 'kartik\grid\SerialColumn',
        'width' => '30px',
    ],
 /*       [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'id_bitacora',
    ],*/
/*    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'grupo_trabajo_id_grupo_trabajo',
    ],*/
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'fecha_bitacora',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'hora_inicio',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'hora_termino',
    ],
    [
         'class'=>'\kartik\grid\DataColumn',
         'attribute'=>'actividad_realizada',
        'contentOptions' =>
            [
                'style'=>'width: 300px; overflow: auto; word-wrap: break-word; white-space: pre-wrap;'
            ],
     ],
     [
         'class'=>'\kartik\grid\DataColumn',
         'attribute'=>'resultados',
         'contentOptions' =>
             [
                 'style'=>'width: 300px; overflow: auto; word-wrap: break-word; white-space: pre-wrap;'
             ],
     ],
     [
         'class'=>'\kartik\grid\DataColumn',
         'attribute'=>'observaciones',
         'contentOptions' =>
             [
                 'style'=>'width: 300px; overflow: auto; word-wrap: break-word; white-space: pre-wrap;'
             ],
     ],
    [
        'label'=>'Archivo',
        'mergeHeader' => true,
        'hAlign' => 'center',
        'format'=>'raw',
        'value' => function($model, $key, $index, $column)
        {
            $evidencia = $model->evidencia;
            if($evidencia != null){
                return
                    \kartik\helpers\Html::a('<i class="fa fa-download" aria-hidden="true"></i> Descargar Archivo',
                        ['download', 'id' => $model->evidencia->id_evidencia], ['class' => 'btn btn-success']);
            }else{
                return "Bitácora sin Archivo.";
            }
        }
    ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'fecha_lectura',
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
        'header' => "Modificar",
        'dropdown' => false,
        'vAlign'=>'middle',
        'template' => "{update}",
        'urlCreator' => function($action, $model, $key, $index) { 
                return Url::to(['/bitacora/modificar-bitacora-alumno','id'=>$key]);
        },
        'viewOptions'=>['role'=>'modal-remote','title'=>'Ver','data-toggle'=>'tooltip'],
        'updateOptions'=>['role'=>'modal-remoteX','title'=>'Modificar', 'data-toggle'=>'tooltip'],
        'deleteOptions'=>['role'=>'modal-remote','title'=>'Eliminar',
                          'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
                          'data-request-method'=>'post',
                          'data-toggle'=>'tooltip',
                          'data-confirm-title'=>'Confirmación',
                          'data-confirm-message'=>'Está seguro que desea eliminar este registro?'],
    ],

];   