<?php

//use yii\widgets\DetailView;
use kartik\detail\DetailView;
use yii\helpers\html;

/* @var $this yii\web\View */
/* @var $model backend\models\requerimiento */

//$this->title = $model->titulo;
$this->title = "Detalle del Requerimiento";
$this->params['breadcrumbs'][] = ['label' => 'Requerimientos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="requerimiento-view">

    <p>
        <?= Html::a('<i class="fa fa-list"></i> Ir a Listado de Requerimientos', ['/requerimiento/index'], ['class' => 'btn btn-info']) ?>
        <?= Html::a('<i class="fa fa-edit"></i> Modificar Requerimiento', ['/requerimiento/update', 'id' => $model->id_requerimiento], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('<i class="fa fa-trash"></i> Eliminar Requerimiento', ['/requerimiento/delete', 'id' => $model->id_requerimiento], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Está seguro que desea eliminar este Socio Comunitario Institucional?',
                'method' => 'post',
            ],
        ]) ?>
    </p>


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
            [
                'label' => 'Socio Comunitario Institucional',
                'value'=>function ($form, $widget) {
                    $model = $widget->model;
                    return $model->sciIdSci->nombre;
                },
            ],
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
