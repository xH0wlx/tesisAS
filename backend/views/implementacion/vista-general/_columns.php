<?php
use yii\helpers\Url;

return [
/*    [
        'class' => 'kartik\grid\CheckboxColumn',
        'width' => '20px',
    ],*/
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
        'label'=>'N° Grupo',
        'attribute' => 'grupo_numero',
        'value'=>function ($model, $index, $widget) {
            $grupo = $model->grupoTrabajoIdGrupoTrabajo;
            $numero = "Aún no asignado";
            if($grupo != null){
                $numero = $grupo->numero_grupo_trabajo;
            }
            return $numero;
        },
        'vAlign' => 'middle',
        'hAlign' => 'center',
        'group' => true,
    ],
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
/*    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'alumno_email',
        'label' => 'Email',
        'value'=>function ($model, $index, $widget) { return $model->alumnoRutAlumno->email; },
        'contentOptions' =>
            [
                'style'=>'width: 300px; overflow: auto; word-wrap: break-word; white-space: pre-wrap;'
            ],
    ],*/
    [
        'class'=>'\kartik\grid\BooleanColumn',
        'label'=>'Es Líder',
        'value'=>function ($model, $index, $widget) {
            $lider = $model->esLider;
            $respuesta = 0;
            if($lider != null){
                $respuesta = 1;
            }
            return $respuesta;
        },
        'trueLabel' => 'Yes',
        'falseLabel' => 'No'
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'label'=>'Socio Comunitario Beneficiario',
        'attribute' => 'socio_beneficiario_nombre',
        'value'=>function ($model, $index, $widget) {
            $grupo = $model->grupoTrabajoIdGrupoTrabajo;
            $respuesta = "Aún no asignado";
            if($grupo != null){
                $scb = $grupo->ultimoSocioBeneficiario;
                if($scb != null){
                    $respuesta = $scb->nombre_negocio;
                }
            }
            return $respuesta;
        },
        'vAlign' => 'middle',
        'group'=>true,
    ],
/*    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'seccion_id_seccion',
    ],*/
    /*[
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
    ],*/

];   