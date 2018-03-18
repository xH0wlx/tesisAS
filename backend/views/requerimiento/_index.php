<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset; 
use johnitvn\ajaxcrud\BulkButtonWidget;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\RequerimientoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

CrudAsset::register($this);

?>
<div class="requerimiento-index">
    <div id="ajaxCrudDatatable">
        <?=GridView::widget([
            'id'=>'crud-datatable',
            'hover'=> true,
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'pjax'=>true,
            'pjaxSettings'=>[
                'options' => [
                    'id' => 'pjax-grid-requerimiento-_index',
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

            ],
            'toolbar'=> [
                ['content'=>
                    /*Html::a('<i class="glyphicon glyphicon-plus"></i>', ['create'],
                    ['role'=>'modal-remote','title'=> 'Crer nuevo Requerimiento','class'=>'btn btn-default']).*/
                    Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['', 'id'=>Yii::$app->request->get('id')],
                    ['data-pjax'=>1, 'class'=>'btn btn-default', 'title'=>'Reset Grid']).
                    '{toggleData}'
                ],
            ],          
            'striped' => true,
            'condensed' => true,
            'responsive' => true,          
            'panel' => [
                'type' => 'primary', 
                'heading' => '<i class="glyphicon glyphicon-list"></i> Lista de Requerimientos',
                //'before'=>'<em>* Resize table columns just like a spreadsheet by dragging the column edges.</em>',
                'after'=>'',
            ]
        ])?>
    </div>
</div>
<?php Modal::begin([
    "id"=>"ajaxCrudModal",
    "footer"=>"",// always need it for jquery plugin
])?>
<?php Modal::end(); ?>
