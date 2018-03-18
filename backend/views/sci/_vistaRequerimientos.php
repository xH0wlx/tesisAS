<?php

use yii\helpers\Html;
//use yii\grid\GridView;
use kartik\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\RequerimientoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>
<div class="requerimiento-index">

<!--    <h1><?/*= Html::encode($this->title) */?></h1>
-->    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

<!--    <p>
        <?/*= Html::a('Create Requerimiento', ['create'], ['class' => 'btn btn-success']) */?>
    </p>-->
    <?=GridView::widget([
        //'id'=>'crud-datatable',
        'hover'=> true,
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'pjax'=>true,
        'pjaxSettings'=>[
            'options' => [
                'id' => 'pjax-grid-vista-requerimientos',
                //'neverTimeout'=>true,
                //'linkSelector' => '#'.$modelRequerimiento->id_requerimiento.' a:not([rel=fancybox], .item-link)',
                'enablePushState' => false
            ],
        ],
        'columns' => [

            [
                'class' => 'kartik\grid\SerialColumn',
                'width' => '30px',
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

        ],
        'toolbar'=> [

        ],
        'striped' => true,
        'condensed' => true,
        'responsive' => true,
        'panel' => [
            'type' => 'default',
            'heading' => '<i class="glyphicon glyphicon-list"></i> Lista de Requerimientos',

        ]
    ])?>

</div>
