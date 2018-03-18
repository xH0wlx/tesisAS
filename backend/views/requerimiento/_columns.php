<?php
use yii\helpers\Url;
use kartik\grid\GridView;

use backend\models\search\RequerimientoHasEtiquetaAreaSearch;


return [
    [
        'class' => 'kartik\grid\CheckboxColumn',
        'width' => '20px',
    ],
    [
        'class' => 'kartik\grid\SerialColumn',
        'width' => '30px',
    ],

      /*  [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'id_requerimiento',
    ],*/
    /*[
        'class' => '\kartik\grid\ExpandRowColumn',
        'value' => function($model, $key, $index, $column){
            return GridView::ROW_COLLAPSED;
        },
        'detail' => function($model, $key, $index, $column){
            $searchModel = new RequerimientoHasEtiquetaAreaSearch();
            $searchModel->requerimiento_id_requerimiento = $model->id_requerimiento;
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
            return Yii::$app->controller->renderPartial('//requerimiento/_vistaEtiquetas',[
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        },
    ],*/
    [
        //'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'requerimiento_socio_institucional',
        'label' => 'Socio C. Institucional',
        'value'=>function ($model, $key, $index, $column) { return $model->sciIdSci->nombre; },
        'group'=>true,
        'contentOptions' =>
            [
                'style'=>'width: 300px; overflow: auto; word-wrap: break-word; white-space: pre-wrap;'
            ],
    ],
    [
        //'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'comuna_socio_institucional',
        'label' => 'Comuna Socio',
        'value'=>function ($model, $key, $index, $column) { return $model->sciIdSci->comunaComuna->comuna_nombre; },
        'group'=>true,
        'subGroupOf'=>3,
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'titulo',
        'contentOptions' =>
            [
                'style'=>'width: 300px; overflow: auto; word-wrap: break-word; white-space: pre-wrap;'
            ],
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'descripcion',
        'contentOptions' =>
            [
                'style'=>'width: 450px; overflow: auto; word-wrap: break-word; white-space: pre-wrap;'
            ],
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'perfil_estudiante',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'apoyo_brindado',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'estado_requerimiento',
        'format' => 'html',
        'value'=>function ($model, $index, $widget) {
            $color = 'info';
            if(strcmp($model->estadoEjecucionIdEstado->nombre_estado, "No Asignado") == 0){
                $color = 'danger';
            }
            if(strcmp($model->estadoEjecucionIdEstado->nombre_estado, "Asignado") == 0){
                $color = 'warning';
            }
            if(strcmp($model->estadoEjecucionIdEstado->nombre_estado, "En Desarrollo") == 0){
                $color = 'primary';
            }
            if(strcmp($model->estadoEjecucionIdEstado->nombre_estado, "Finalizado") == 0){
                $color = 'success';
            }
            return '<span class="label label-'.$color.'">'.$model->estadoEjecucionIdEstado->nombre_estado.'</span>';
        },
    ],
    [
        'attribute' => 'tagValues',
        'format' => 'html',
        'contentOptions' =>
            [
                'style'=>'width: 400px; overflow: auto; word-wrap: break-word; white-space: pre-wrap;'
            ],
        'value'=>function ($model, $index, $widget) {
            $arreglo = explode(",", $model->tagValues);
            $cadena = '';
            foreach ($arreglo as $tag){
                $cadena = $cadena.'<span class="tag label label-info">'.$tag.'</span> ';
            }

            return $cadena;
        },
    ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'vAlign'=>'middle',
        'urlCreator' => function($action, $model, $key, $index) {
            return Url::to([$action,'id'=>$key]);
        },
        'viewOptions'=>['role'=>'modal-remotex','title'=>'Ver','data-toggle'=>'tooltip'],
        'updateOptions'=>['role'=>'modal-remotex','title'=>'Modificar', 'data-toggle'=>'tooltip'],
        'deleteOptions'=>['role'=>'modal-remoteX','title'=>'Eliminar',
            'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
            'data-request-method'=>'post',
            'data-pjax'=>0,
            'data-toggle'=>'tooltip',
            'data-confirm-title'=>'Confirmación',
            'data-confirm-message'=>'Está seguro de eliminar este registro'],
    ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'observacion',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'sci_id_sci',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'estado_ejecucion_id_estado',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'creado_en',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'modificado_en',
    // ],


];   