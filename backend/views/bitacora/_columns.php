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
        'label' => 'Asignatura',
        'attribute'=>'grupoTrabajoIdGrupoTrabajo.seccionIdSeccion.implementacionIdImplementacion.asignaturaCodAsignatura.codigoNombre',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'label' => 'Sede Asignatura',
        'attribute'=>'grupoTrabajoIdGrupoTrabajo.seccionIdSeccion.implementacionIdImplementacion.asignaturaCodAsignatura.carreraCodCarrera.facultadIdFacultad.sedeIdSede.nombre_sede',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'label' => 'N° Grupo',
        'attribute'=>'grupoTrabajoIdGrupoTrabajo.numero_grupo_trabajo',
    ],
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
        'contentOptions' => [
            'style'=>'max-width:250px; min-height:100px; overflow: auto; word-wrap: break-word;'
        ],
        /*'contentOptions' =>
        [
            'style'=>'width: 300px; overflow: auto; word-wrap: break-word; white-space: pre-wrap;'
        ],
        'contentOptions' => [
                'style'=>'max-width:250px; min-height:100px; overflow: auto; word-wrap: break-word;'
            ],*/
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'resultados',
        'contentOptions' => [
            'style'=>'max-width:250px; min-height:100px; overflow: auto; word-wrap: break-word;'
        ],
    ],
     [
         'class'=>'\kartik\grid\DataColumn',
         'attribute'=>'observaciones',
         'contentOptions' => [
             'style'=>'max-width:250px; min-height:100px; overflow: auto; word-wrap: break-word;'
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
        'dropdown' => false,
        'header' => Yii::$app->user->can("docente")? 'Aprobar Bitácora' : 'Acciones',
        'vAlign'=>'middle',
        'urlCreator' => function($action, $model, $key, $index) {
                if($action == "update"){
                    return Url::to(["/bitacora/modificar-bitacora-alumno",'id'=>$key]);
                }
                return Url::to([$action,'id'=>$key]);
        },
        'template' => Yii::$app->user->can("docente")? '{view}' : '{view} {delete}',
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