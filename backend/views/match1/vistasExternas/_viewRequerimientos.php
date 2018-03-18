<?php

use yii\helpers\Html;
//use yii\grid\GridView;
use kartik\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\RequerimientoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

//VISTAS EXTERNAS MATCH1
$js = '
$(document).on("ready pjax:end", function() {
  jQuery(\'#crud-datatable-requerimientos22\').yiiGridView("init");
})

';
$this->registerJs($js);
?>

        <?=GridView::widget([
            'id'=>'crud-datatable-requerimientos',
            'hover'=> true,
            'dataProvider' => $dataProviderRequerimiento,
            'filterModel' => $searchModelRequerimiento,
            //'filterUrl' => ['match1/cargar-vista-sci?id='.Yii::$app->request->get('id')],
            //'filterSelector' => "#crud-datatable-requerimientos22-filters input, #crud-datatable-requerimientos22-filters select",
            'pjax'=>true,
            'pjaxSettings'=>[
                'options' => [
                    //    'counter'=> 0,
                    'id' => 'pjax-grid-vista-requerimientos'.Yii::$app->request->get('id'),
                    //'neverTimeout'=>true,
                    //'linkSelector' => '#'.$model->id_requerimiento.' a:not([rel=fancybox], .item-link)',
                    'enablePushState' => false,
                        //'container' => '#pjax-grid-vista-requerimientos'.Yii::$app->request->get('id'),

                ],
            ],
            //'filterUrl'=> \yii\helpers\Url::to(["match1/seleccion-socio"]),
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
/*                [
                    'class'=>'\kartik\grid\DataColumn',
                    'attribute'=>'observacion',
                ],*/
                [
                    'class'=>'\kartik\grid\DataColumn',
                    'attribute'=>'cantidad_aprox_beneficiarios',
                ],
                [
                    'attribute' => 'preseleccionado_match1',
                    'format' => 'html',
                    'value'=>function ($model, $index, $widget) {
                        $color = 'info';
                        $texto = 'No Preseleccionado';
                        $texto2 = 'Preseleccionado';
                        if($model->preseleccionado_match1== 0){
                            $color = 'warning';
                            return '<span class="label label-'.$color.'">'.$texto.'</span>';
                        }
                        else{
                            $color = 'success';
                            return '<span class="label label-'.$color.'">'.$texto2.'</span>';

                        }
                    },
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
                'type' => 'primary',
                'heading' => '<i class="glyphicon glyphicon-list"></i> Lista de Requerimientos',

            ]
        ])?>

