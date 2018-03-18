<?php

use yii\helpers\Html;
use yii\helpers\Url;
//use yii\widgets\Pjax;
use kartik\grid\GridView;
use backend\models\search\RequerimientoSearch;

use yii\bootstrap\Modal;
use johnitvn\ajaxcrud\CrudAsset;
use johnitvn\ajaxcrud\BulkButtonWidget;
CrudAsset::register($this);

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\SciSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Socio Comunitario Institucional';
//$this->params['breadcrumbs'][] = ['label' => 'Socio Comunitario Institucional (Panel)', 'url' => ['principal']];
$this->params['breadcrumbs'][] = $this->title;



?>
<div class="sci-index">
   <!-- <div class="row">
        <div class="col-sm-12">
            <?/*= Html::a('<i class="fa fa-arrow-circle-left"></i> Volver a Panel Principal', ['principal'], ['class' => 'btn btn-info']) */?>
        </div>
    </div>
    <br>-->
    <div class="row">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

<!--    <p>
        <?/*= Html::a('Crear Socio Comunitario Institucional', ['create'], ['class' => 'btn btn-success']) */?>
    </p>-->
    <div class="col-md-12">
    <div id="ajaxCrudDatatable">
       <?= GridView::widget([
           'id'=>'crud-datatable',
           'dataProvider' => $dataProvider,
           'filterModel' => $searchModel,
           'pjax' => true,
            'panel'=>[
               'type'=>GridView::TYPE_PRIMARY,
               'heading'=>"<i class='glyphicon glyphicon-list'></i> Socios Comunitarios Institucionales",
               'after'=>BulkButtonWidget::widget([
                    'buttons'=>Html::a('<i class="glyphicon glyphicon-trash"></i>&nbsp; Borrar Seleccionados',
                        ["bulk-delete"] ,
                        [
                            "class"=>"btn btn-danger btn-xs",
                            'role'=>'modal-remote-bulk',
                            'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
                            'data-request-method'=>'post',
                            'data-confirm-title'=>'Confirmación',
                            'data-confirm-message'=>'Está seguro que desea eliminar este/estos registro/s?'
                        ]),
               ]).
               '<div class="clearfix"></div>',
            ],
            'headerRowOptions'=>['class'=>'kartik-sheet-style'],
            'filterRowOptions'=>['class'=>'kartik-sheet-style'],
            // set your toolbar
            'toolbar'=> [
               ['content'=>
                   Html::button('<i class="glyphicon glyphicon-plus"></i> Crear Nuevo Socio Inst.',
                       [
                           'type'=>'button',
                           'title'=>'Crear SCI',
                           'class'=>'btn btn-success',
                           'onclick'=>"window.location.href = '" . \Yii::$app->urlManager->createUrl(['sci/create']) . "';",
                       ]).Html::a('<i class="glyphicon glyphicon-repeat"></i>', [''],['data-pjax'=>1, 'class'=>'btn btn-default', 'title'=>'Reset Grid']).
                   '{toggleData}'.
                   '{export}'
               ],
               //'{toggleData}',
               //'{export}',
            ],
            'condensed' => true,
            'columns' => [
                [
                    'class' => 'kartik\grid\CheckboxColumn',
                    'width' => '20px',
                ],
                [
                    'class' => 'kartik\grid\SerialColumn',
                    'width' => '30px',
                ],
/*               [
                    'class' => '\kartik\grid\ExpandRowColumn',
                    'value' => function($model, $key, $index, $column){
                                    return GridView::ROW_COLLAPSED;
                                },
                    'hiddenFromExport' => false,
                    'detail' => function($model, $key, $index, $column){
                                    $searchModel = new RequerimientoSearch();
                                    $searchModel->sci_id_sci = $model->id_sci;
                                    $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
                                    return Yii::$app->controller->renderPartial('_vistaRequerimientos',[
                                        'searchModel' => $searchModel,
                                        'dataProvider' => $dataProvider,
                                    ]);
                                },
                ],*/

                //'id_sci',
                'nombre',
                //'direccion',
                //'observacion',
                [
                    'class'=>'\kartik\grid\DataColumn',
                    'attribute'=>'departamento_programa',
                    'contentOptions' =>
                        [
                            'style'=>'width: 300px; overflow: auto; word-wrap: break-word; white-space: pre-wrap;'
                        ],
                ],
                [
                    'class'=>'\kartik\grid\DataColumn',
                    'attribute'=>'sci_comuna',
                    'label' => 'Comuna',
                    'value'=>function ($model, $index, $widget) { return $model->comunaComuna->comuna_nombre; }
                ],
                [
                    'class'=>'\kartik\grid\DataColumn',
                    'attribute'=>'sci_sede',
                    'label' => 'Sede',
                    'value'=>function ($model, $index, $widget) { return $model->sedeIdSede->nombre_sede; }
                ],
                [
                    'class' => 'kartik\grid\ActionColumn',
                    'dropdown' => false,
                    'vAlign'=>'middle',
                    'urlCreator' => function($action, $model, $key, $index) {
                        return Url::to([$action,'id'=>$key]);
                    },
                    //'template' => '{update} {delete}',
                    'viewOptions'=>['role'=>'modal-remoteX','title'=>'Ver','data-toggle'=>'tooltip'],
                    'updateOptions'=>['role'=>'modal-remoteX','title'=>'Modificar', 'data-toggle'=>'tooltip'],
                    'deleteOptions'=>['role'=>'modal-remote','title'=>'Eliminar',
                        'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
                        'data-request-method'=>'post',
                        'data-toggle'=>'tooltip',
                        'data-confirm-title'=>'Confirmación',
                        'data-confirm-message'=>'Está seguro que desea eliminar este elemento?'],
                ],
                // 'comuna_comuna_id',
                // 'creado_en',
                // 'modificado_en',


            ],

        ]); ?>
    </div>
    </div><!--COL 10-->
        <!--<div class="col-md-2">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title text-center">Enlaces Relacionados</h3>
                </div>
                <div class="box-body no-padding">
                    <ul class="nav nav-pills nav-stacked">
                        <li><a href="#"><i class="fa fa-circle-o text-light-blue"></i> Contactos</a></li>
                        <li><a href="#"><i class="fa fa-circle-o text-light-blue"></i> Requerimientos</a></li>
                        <li><a href="#"><i class="fa fa-circle-o text-light-blue"></i> Social</a></li>
                    </ul>
                </div>
            </div>
        </div>-->
    </div><!--FIN ROW PRINCIPAL-->
</div>
<?php Modal::begin([
    "id"=>"ajaxCrudModal",
    "footer"=>"",// always need it for jquery plugin
])?>
<?php Modal::end(); ?>
