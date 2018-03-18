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
        'attribute'=>'id_servicio',
    ],*/
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'titulo',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'descripcion',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'perfil_scb',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'observacion',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'format' => 'html',
        'label'=>'Docentes',
        'mergeHeader' => true,
        'value'=>function ($model, $key, $index, $column) {
            $docentes = $model->docenteHasServicios;
            $cadena = "";
            foreach ($docentes as $docente){
                $nombre = $docente->docenteRutDocente->nombre_completo;
                $cadena = $cadena.$nombre."<br>";
            }

            return $cadena;
        },
    ],
    // [
    // 'class'=>'\kartik\grid\DataColumn',
    // 'attribute'=>'estado_ejecucion_id_estado',
    // ],
    // [
    // 'class'=>'\kartik\grid\DataColumn',
    // 'attribute'=>'duracion',
    // ],
    // [
    // 'class'=>'\kartik\grid\DataColumn',
    // 'attribute'=>'unidad_duracion_id_unidad_duracion',
    // ],
    // [
    // 'class'=>'\kartik\grid\DataColumn',
    // 'attribute'=>'creado_en',
    // ],
    // [
    // 'class'=>'\kartik\grid\DataColumn',
    // 'attribute'=>'modificado_en',
    // ],
    // [
    // 'class'=>'\kartik\grid\DataColumn',
    // 'attribute'=>'asignatura_cod_asignatura',
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