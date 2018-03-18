<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\ArrayHelper;
use backend\models\ContactoSci;
use backend\models\Sci;
use yii\widgets\ListView;
use yii\data\ActiveDataProvider;

/* @var $this yii\web\View */
/* @var $model backend\models\sci */

use yii\helpers\Url;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset;
use johnitvn\ajaxcrud\BulkButtonWidget;
use yii\data\SqlDataProvider;
use yii\widgets\Pjax;
use backend\models\Requerimiento;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\RequerimientoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$session = Yii::$app->session;
$session->open();
if($session->has('fechaMatch1')){
    $arregloFecha = $session->get('fechaMatch1');
}else{
    return Yii::$app->response->redirect(Url::to(['/match1/seleccion']));
}
$session->close();

$this->title = 'Selección de Requerimiento';
$this->params['breadcrumbs'][] = ['label' => 'Nuevo Match', 'url' => ['/match1/seleccion']];
$this->params['breadcrumbs'][] = ['label' => 'Selección Socio C. Inst.', 'url' => ['/match1/seleccion-socio']];
$this->params['breadcrumbs'][] = $this->title;

CrudAsset::register($this);
$js = '
    $(\'.modalButton\').click(function(){
        $(\'#modal\').modal(\'show\')
        .find(\'#modalContent\')
        .load($(this).attr(\'value\'));
    });
    
    var selectedItems = [];

    $(\'.crud-datatable\').click(function (){
    //selectedItems.concat()
        //selectedItems = $(\'#\'+$(this).data(\'key\')+\'\'\').yiiGridView(\'getSelectedRows\');
        // select all rows on page 1, go to page 2 and select all rows.
        // All rows on page 1 and 2 will be selected.
        //console.log(selectedItems);
    });
    
 /*   $(\'h1\').click(function (){
        $.ajax({
            url:\''.\Yii::$app->urlManager->createUrl(['match1/service-match1']).'\',
            data:{},
            type: \'POST\',
            dataType: \'html\',
            success: function(lehtml){
                $(\'#prueba\').html(lehtml);
            },
            error: function(xhr, status){
                alert(\'Existe un problema\');
            },
            complete: function(xhr, status){
                alert(\'Se ejecutó\');
            },
        });
    });*/
    
  
    
';
$this->registerJs($js);


?>

<?php
Modal::begin([
    'header'=>'<h4>Requerimiento</h4>',
    'id'=>'modal',
    'size'=>'modal-lg',
]);

echo "<div id='modalContent'></div>";
Modal::end();
?>
    <div class="box box-primary collapsed-box">
        <div class="box-header with-border"><h3 class="box-title">Periodo Seleccionado: <b><?=$arregloFecha["anio"]?> - <?=$arregloFecha["semestre"]?></b></h3>
        </div>
        <div class="box-body">
        </div>
    </div>


    <div class="box box-primary collapsed-box">
        <div class="box-header with-border"><h3 class="box-title">Socio Seleccionado: <?=$modeloSocio->nombreComuna ?></h3>
            <div class="box-tools pull-right">
                (Ver Detalle)<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                </button>
            </div>
        </div>
        <div class="box-body">
            <?= DetailView::widget([
                'model' => $modeloSocio,
                'attributes' => [
                    'nombre',
                    'direccion',
                    'departamento_programa',
                    'comunaComuna.comuna_nombre',
                    [
                        'label' => 'Sede (Universidad del Bío Bío)',
                        'value' => $modeloSocio->sedeIdSede->nombre_sede,
                    ],
                ],
            ]) ?>
        </div>
    </div>

    <!--<div class="alert alert-info alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h4><i class="icon fa fa-info"></i> Información</h4>
        Las asignaturas propuestas por el sistema se despliegan con el botón <span class="glyphicon glyphicon-expand"></span>.
    </div>-->

    <div id="ajaxCrudDatatable">
        <?= GridView::widget([
            'id'=>'crud-datatable',
            'dataProvider' => $dataProviderRequerimientos,
            //'hover' => true,
            'pjax' => true,
            /*'pjaxSettings'=>[
                'options' => [
                    'id' => 'crud-datatable',
                    //'neverTimeout'=>true,
                    //'linkSelector' => '#'.$modelRequerimiento->id_requerimiento.' a:not([rel=fancybox], .item-link)',
                    'enablePushState' => false
                ],
            ],*/
            //'filterModel' => $searchModel,
            'tableOptions' =>[
                'style'=>' text-align: justify; /*text-justify: inter-word;*/'
            ],
            //'perfectScrollbar' => true,
            'panel'=>[
                'type'=>GridView::TYPE_PRIMARY,
                'heading'=>"Requerimientos No Asignados del Socio Seleccionado",
                'footer'=> false,
            ],
            'headerRowOptions'=>['class'=>'kartik-sheet-style'],
            'filterRowOptions'=>['class'=>'kartik-sheet-style'],
            // set your toolbar
            'toolbar'=> [
                ['content'=>
                    Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['', "id" => $modeloSocio->id_sci],['data-pjax'=>1, 'class'=>'btn btn-default', 'title'=>'Reset Grid'])/*.
            Html::button('<i class="glyphicon glyphicon-plus"></i> Crear Nuevo',
                [
                    'type'=>'button',
                    'title'=>'Crear SCI',
                    'class'=>'btn btn-success',
                    'onclick'=>"window.location.href = '" . \Yii::$app->urlManager->createUrl(['sci/create']) . "';",
                ]) . ' '*/
                ],
                '{toggleData}',
                //'{export}',
            ],
            'condensed' => true,
            'columns' => [
                ['class'=>'kartik\grid\SerialColumn'],
                [
                    'class'=>'kartik\grid\DataColumn',
                    'attribute' => 'titulo',
                    'contentOptions' =>
                        [
                            'style'=>'/*max-width: 450px;*/ overflow: auto; word-wrap: break-word; white-space: pre-wrap;'
                        ],
                ],
                [
                    'attribute' => 'descripcion',
                    'contentOptions' =>
                        [
                            'style'=>'max-width: 450px; max-height: 20px; overflow: auto; word-wrap: break-word; white-space: pre-wrap;'
                        ],
                ],
/*                [
                    'attribute' => 'preseleccionado_match1',
                    'format' => 'html',
                    'value'=>function ($model, $index, $widget) {
                        $color = 'info';
                        $texto = 'No';
                        $texto2 = 'Sí';
                        if($model->preseleccionado_match1== 0){
                            $color = 'warning';
                            return '<span class="label label-'.$color.'">'.$texto.'</span>';
                        }
                        else{
                            $color = 'success';
                            return '<span class="label label-'.$color.'">'.$texto2.'</span>';

                        }
                    },
                ],*/
                [
                    //'class' =>'',
                    'attribute' => 'estado_ejecucion_id_estado',
                    'hAlign' => GridView::ALIGN_CENTER,
                    //'contentOptions' =>['class' => 'label label-success','style'=>'display:block;'],
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
                    'value'=>function ($model, $index, $widget) {
                        $arreglo = explode(",", $model->tagValues);
                        $cadena = '';
                        foreach ($arreglo as $tag){
                            $cadena = $cadena.'<span class="tag label label-info">'.$tag.'</span> ';
                        }

                        return $cadena;
                    },
                    'contentOptions' =>
                        [
                            'style'=>'width: 180px; overflow: auto; word-wrap: break-word; white-space: pre-wrap;'
                        ],
                ],
                // 'creado_en',
                // 'modificado_en',
                [
                    'class' => 'kartik\grid\ActionColumn',
                    'dropdown' => false,
                    'header' => "Detalle Requerimiento",
                    'vAlign'=>'middle',
                    'urlCreator' => function($action, $model, $key, $index) {
                        return Url::to(["/requerimiento/".$action,'id'=>$key]);
                    },
                    'template' => '{view}',
                    'viewOptions'=>['role'=>'modal-remote','title'=>'Ver','data-pjax'=>1,'data-toggle'=>'tooltip'],
                    'updateOptions'=>['role'=>'modal-remotex','title'=>'Modificar', 'data-toggle'=>'tooltip'],
                ],
                [
                    'class' => '\kartik\grid\BooleanColumn',
                    'attribute' => 'preseleccionado_match1',
                    'vAlign' => 'middle',
                    'trueLabel' => 'Yes',
                    'falseLabel' => 'No'
                ],
                [
                    'class' => 'kartik\grid\ActionColumn',
                    'header' => "Siguiente",
                    //'headerOptions' => ['style' => 'color:#337ab7'],
                    'dropdown' => false,
                    'vAlign'=>'middle',
                    'buttons' => [
                        'view' => function ($url, $model) {
                            if($model->preseleccionado_match1 == 1){
                                //ex: modificar-seleccion-asignaturas
                                return Html::a('<i class="fa fa-2x fa-chevron-circle-right"></i>',
                                    ['/match1/seleccion3', 'id' => $model->id_requerimiento] ,
                                    ['class' => 'btn btn-success boton-seleccion-sci', 'name' => $model->titulo,
                                        'title'=>'Seleccionar', 'data-pjax'=>"0", 'data-toggle'=>"tooltip"]);
                            }
                            return Html::a('<i class="fa fa-2x fa-chevron-circle-right"></i>',
                                ['/match1/seleccion3', 'id' => $model->id_requerimiento] ,
                                ['class' => 'btn btn-danger boton-seleccion-sci', 'name' => $model->titulo,
                                    'title'=>'Seleccionar', 'data-pjax'=>"0", 'data-toggle'=>"tooltip"]);
                        },
                    ],
                    /*'urlCreator' => function($action, $model, $key, $index) {
                        return Url::to(['requerimiento/'.$action,'id'=>$key]);
                    },*/
                    'template' => '{view}',
                    'viewOptions'=>['role'=>'modal-remote','title'=>'View','data-toggle'=>'tooltip'],
                    'updateOptions'=>['role'=>'modal-remote','title'=>'Update', 'data-toggle'=>'tooltip'],
                ],

                /*[
                    'class' => 'kartik\grid\ActionColumn',
                    'header' => 'Actions',
                    'headerOptions' => ['style' => 'color:#337ab7'],
                    'template' => '{view}{update}{delete}',
                    'buttons' => [
                        'view' => function ($url, $model) {
                            return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, [
                                'title' => 'Ver',
                            ]);
                        },

                        'update' => function ($url, $model) {
                            return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                                'title' => 'Modificar',
                            ]);
                        },
                        'delete' => function ($url, $model) {
                            return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                                'title' => 'Eliminar',
                            ]);
                        }

                    ],
                    'urlCreator' => function ($action, $model, $key, $index) {
                        if ($action === 'view') {
                            $url =\Yii::$app->urlManager->createUrl(['/requerimiento/view?id=']).$model->id_requerimiento;
                            return $url;
                        }

                        if ($action === 'update') {
                            $url = Url::to(['requerimiento/'.$action,'id'=> $model->id_requerimiento]);
                            return $url;
                        }
                        if ($action === 'delete') {
                            $url ='index.php?r=client-login/lead-delete&id=';
                            return $url;
                        }
                    }
                ],*/
            ],

        ]); ?>
    </div>
<?php Modal::begin([
    "id"=>"ajaxCrudModal",
    "footer"=>"",// always need it for jquery plugin
])?>
<?php Modal::end(); ?>