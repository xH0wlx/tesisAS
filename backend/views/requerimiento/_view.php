<?php

use kartik\detail\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\requerimiento */
?>
<div class="requerimiento-view">

    <?=DetailView::widget([
        'model'=>$model,
        'condensed'=>true,
        'hover'=>true,
        'buttons1'=> '',
        'buttons2'=> '',
        'mode'=>DetailView::MODE_VIEW,
        'panel'=>[
            'heading'=>'Requerimiento: ' . $model->titulo,
            'type'=>DetailView::TYPE_PRIMARY,
        ],
        'attributes'=>[
            //'id_requerimiento',
            'titulo',
            'descripcion',
            'perfil_estudiante',
            'apoyo_brindado',
            'observacion',
            'cantidad_aprox_beneficiarios',
            [
                //'class'=>'\kartik\grid\DataColumn',
                'attribute'=>'estado_ejecucion_id_estado',
                'format' => 'html',
                'value'=>function ($form, $widget) {
                    $model = $widget->model;
                    $color = 'info';
                    if(strcmp($model->estadoEjecucionIdEstado->nombre_estado, "No Asignado") == 0){
                        $color = 'danger';
                    }
                    if(strcmp($model->estadoEjecucionIdEstado->nombre_estado, "Asignado") == 0){
                        $color = 'warning';
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
                'value'=>function ($form, $widget) {
                    $model = $widget->model;
                    $arreglo = explode(",", $model->tagValues);
                    $cadena = '';
                    foreach ($arreglo as $tag){
                        $cadena = $cadena.'<span class="tag label label-info">'.$tag.'</span> ';
                    }

                    return $cadena;
                },
            ],
            //'creado_en',
            //'modificado_en',
        ]
    ]);?>

</div>
