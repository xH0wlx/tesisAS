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

$this->title = 'Requerimientos';
$this->params['breadcrumbs'][] = $this->title;

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
            'columns' => [
                [
                    'class' => 'kartik\grid\CheckboxColumn',
                    'width' => '20px',
                ],
                [
                    'class' => 'kartik\grid\SerialColumn',
                    'width' => '30px',
                ],
                [
                    //'class'=>'\kartik\grid\DataColumn',
                    'attribute'=>'requerimiento_socio_institucional',
                    'label' => 'Socio C. Institucional',
                    'value'=>function ($model, $key, $index, $column) { return $model->sciIdSci->nombre; },
                    'group'=>true,
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
                    'attribute'=>'estado_ejecucion_id_estado',
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
                [
                    'class' => 'kartik\grid\ActionColumn',
                    'dropdown' => false,
                    'vAlign'=>'middle',
                    'template' => '{view} {update} {delete} {link}',


                    'urlCreator' => function($action, $model, $key, $index) {
                        return Url::to([$action,'id'=>$key]);
                    },

                    'buttons' => [
                        'delete' => function ($url,$model) {
                            $url = Url::toRoute(['/requerimiento/delete-permanente', 'id' => $model->id_requerimiento], true);
                            return Html::a(
                                '<span class="glyphicon glyphicon-trash" title="Eliminar"></span>',
                                $url, [
                                'class' => '',
                                'data' => [
                                    'confirm' => 'Esta seguro que desea eliminar PERMANENTEMENTE este registro?',
                                    'method' => 'post',
                                ]]);
                        },
                        'link' => function ($url,$model,$key) {
                            $url = Url::toRoute(['/requerimiento/restore', 'id' => $model->id_requerimiento], true);
                            return Html::a('<span class="glyphicon glyphicon-floppy-open" title="Restaurar"></span>', $url);
                        },
                    ],

                    'viewOptions'=>['role'=>'modal-remotex','title'=>'Ver','data-toggle'=>'tooltip'],
                    'updateOptions'=>['role'=>'modal-remotex','title'=>'Modificar', 'data-toggle'=>'tooltip'],
                    'deleteOptions'=>['role'=>'modal-remotex','title'=>'Eliminar',
                        'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
                        'data-request-method'=>'post',
                        'data-toggle'=>'tooltip',
                        'data-confirm-title'=>'Confirmación',
                        'data-confirm-message'=>'Está seguro de eliminar este registro'],

                ],
            ],
            'toolbar'=> [
                ['content'=>
                    /*Html::a('<i class="glyphicon glyphicon-plus"></i>', ['create'],
                    ['role'=>'modal-remote','title'=> 'Crer nuevo Requerimiento','class'=>'btn btn-default']).*/
                    Html::button('<i class="glyphicon glyphicon-plus"></i> Crear Nuevo Requerimiento',
                        [
                            'type'=>'button',
                            'title'=>'Crear Requerimiento',
                            'class'=>'btn btn-success',
                            'onclick'=>"window.location.href = '" . \Yii::$app->urlManager->createUrl(['requerimiento/create']) . "';",
                        ]).
                    Html::a('<i class="glyphicon glyphicon-repeat"></i>', [''],
                    ['data-pjax'=>1, 'class'=>'btn btn-default', 'title'=>'Reset Grid']).
                    '{toggleData}'.
                    '{export}'
                ],
            ],          
            'striped' => true,
            'condensed' => true,
            'responsive' => true,          
            'panel' => [
                'type' => 'primary', 
                'heading' => '<i class="glyphicon glyphicon-list"></i> Lista de Requerimientos',
                //'before'=>'<em>* Resize table columns just like a spreadsheet by dragging the column edges.</em>',
                'after'=>BulkButtonWidget::widget([
                            'buttons'=>Html::a('<i class="glyphicon glyphicon-trash"></i>&nbsp; Borrar Seleccionados',
                                ["bulk-delete"] ,
                                [
                                    "class"=>"btn btn-danger btn-xs",
                                    'role'=>'modal-remote-bulk',
                                    'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
                                    'data-request-method'=>'post',
                                    'data-confirm-title'=>'Está seguro?',
                                    'data-confirm-message'=>'Este seguro de eliminar este item?'
                                ]),
                        ]).                        
                        '<div class="clearfix"></div>',
            ]
        ])?>
    </div>
</div>
<?php Modal::begin([
    "id"=>"ajaxCrudModal",
    "footer"=>"",// always need it for jquery plugin
])?>
<?php Modal::end(); ?>
