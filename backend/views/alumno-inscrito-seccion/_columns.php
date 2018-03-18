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
        'attribute'=>'id_alumno_inscrito_seccion',
    ],*/
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'alumno_rut_alumno',
        'value'=>function ($model, $index, $widget) { return Yii::$app->formatter->asRut($model->alumno_rut_alumno); },
        'label' => 'Rut',
        'filterInputOptions'=>['placeholder' => 'Buscar sin puntos ni guión'],
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'alumno_nombre',
        'label' => 'Nombre',
        'value'=>function ($model, $index, $widget) { return $model->alumnoRutAlumno->nombre; }
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'alumno_email',
        'label' => 'Email',
        'value'=>function ($model, $index, $widget) { return $model->alumnoRutAlumno->email; }
    ],
/*    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'seccion_id_seccion',
    ],*/
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'vAlign'=>'middle',
        'urlCreator' => function($action, $model, $key, $index) { 
                return Url::to([$action,'id'=>$key]);
        },
        'template' => "{delete}",
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