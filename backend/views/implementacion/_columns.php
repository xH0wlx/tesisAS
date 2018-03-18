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
        'attribute'=>'id_implementacion',
    ],*/
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'asignatura_cod_asignatura',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'label'=> 'Nombre Asignatura',
        'attribute'=>'asignatura_nombre',
        'value'=>function ($model, $index, $widget) {
            return $model->asignaturaCodAsignatura->nombre_asignatura;
        },
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'anio_implementacion',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'semestre_implementacion',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'label'=> 'Sede UBB',
        'attribute'=>'asignatura_sede',
        'value'=>function ($model, $index, $widget) {
            return $model->asignaturaCodAsignatura->carreraCodCarrera->facultadIdFacultad->sedeIdSede->nombre_sede;
        },
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        //'attribute'=>'asignatura_sede',
        'format'=> 'html',
        'label' => 'Socio/s Institucional',
        'value'=>function ($model, $index, $widget) {
            return $model->getSocios();
        },
        'contentOptions' =>
            [
                'style'=>'width: 300px; overflow: auto; word-wrap: break-word; white-space: pre-wrap;'
            ],
    ],

    [
        'class'=>'\kartik\grid\EditableColumn',
        'attribute'=>'estado',
        'editableOptions'=> function ($model, $key, $index) {
            return [
                'format' => \kartik\editable\Editable::FORMAT_BUTTON,
                'inputType' => \kartik\editable\Editable::INPUT_DROPDOWN_LIST,
                'data'=>$model->estadoLista, // any list of values
                'options' => ['class'=>'form-control', 'prompt'=>'Seleccione Estado ...'],
                'editableValueOptions'=>['class'=>'text-danger'],
            ];
        },
        'value'=>function ($model, $index, $widget) {
            $aRetornar = "";
            if($model->estado == 0){
                $aRetornar = "Creada";
            }else if($model->estado == 1){
                $aRetornar = "En Curso";
            }else if($model->estado == 2){
                $aRetornar = "Finalizada";
            }
            return $aRetornar;
        }
    ],
/*    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'estado',
        //'label' => 'Nombre Asignatura',
        'value'=>function ($model, $index, $widget) {
            $aRetornar = "";
            if($model->estado == 0){
                $aRetornar = "Creada";
            }else if($model->estado == 1){
                $aRetornar = "En Curso";
            }else if($model->estado == 2){
                $aRetornar = "Finalizada";
            }
            return $aRetornar;
        }
    ],*/
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'vAlign'=>'middle',
        'urlCreator' => function($action, $model, $key, $index) {
                if($action == "view"){
                    return Url::to(["/implementacion/vista-general",'id'=>$key]);
                }
                if($action == "update"){
                    return Url::to(["/implementacion/panel-implementacion",'idImplementacion'=>$key]);
                }

                return Url::to([$action,'id'=>$key]);
        },
        'template' => '{view}{update}{delete}',
        'viewOptions'=>['role'=>'modal-remoteX','title'=>'Ver','data-toggle'=>'tooltip'],
        'updateOptions'=>['role'=>'modal-remoteX','title'=>'Modificar', 'data-toggle'=>'tooltip'],
        'deleteOptions'=>['role'=>'modal-remote','title'=>'Eliminar',
                          'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
                          'data-request-method'=>'post',
                          'data-toggle'=>'tooltip',
                          'data-confirm-title'=>'Confirmación',
                          'data-confirm-message'=>'Está seguro que desea eliminar este registro?'],
    ],

];   