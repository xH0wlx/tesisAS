<?php
use yii\helpers\Url;
use kartik\helpers\Html;
use yii\widgets\ActiveForm;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset;
use johnitvn\ajaxcrud\BulkButtonWidget;
use kartik\date\DatePicker;
use kartik\growl\GrowlAsset;
use kartik\base\AnimateAsset;
use kartik\depdrop\DepDrop;
use kartik\export\ExportMenu;
use backend\models\match1;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\ImplementacionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Reporte Resumen Requerimientos/Servicios';
$this->params['breadcrumbs'][] = $this->title;

CrudAsset::register($this);
GrowlAsset::register($this);
AnimateAsset::register($this);

$js = '
$(\'.kv-row-checkbox\').change(function(e) {
    asignaturaSeleccionada = $(\'#crud-datatable\').yiiGridView(\'getSelectedRows\');
    alert(asignaturaSeleccionada);
});

var $grid = $(\'#crud-datatable\');
 
$grid.on(\'grid.radiochecked\', function(ev, key, val) {
    //if(confirm("Confirma que desea modificar la implementacion código: "+key)){
    //    window.location.replace("/implementacion/panel-implementacion?idImplementacion="+key);
    //}
   //$.notify({message: "Asignatura Seleccionada"}, {});
   //krajeeDialog.alert("Alert traducido ;D");
});

';
$this->registerJs($js);
?>

<?php echo $this->render('_searchPeriodoEstadistica', ['model' => $searchModel]); ?>
<div id="ajaxCrudDatatable">
<?=GridView::widget([
    'id'=>'crud-datatable',
    'dataProvider' => $dataProvider,
    //'filterModel' => $searchModel,
    'showPageSummary'=>true,
    'pjax'=>true,
    'striped' => false,
    'hover'=> true,
    //'showFooter' => true,
        //'condensed' => true,
    'responsive' => true,
    'pjaxSettings' => ['options' => ['id' => 'kv-pjax-container']],
    'columns' => [
        [
            'class' => 'kartik\grid\SerialColumn',
            'width' => '30px',
        ],
        [
            'class'=>'\kartik\grid\DataColumn',
            'label' => 'Año periodo',
            'attribute'=>'anio_desde',
            'value'=>function ($model, $index, $widget) { return $model->anio_match1; },
            'vAlign' => GridView::ALIGN_MIDDLE,
            //'group'=>true,
            'groupEvenCssClass'=>false,

        ],
        [
            'class'=>'\kartik\grid\DataColumn',
            'label' => 'Semestre periodo',
            'attribute'=>'semestre_numero',
            'value'=>function ($model, $index, $widget) { return $model->semestre_match1; },
            'vAlign' => GridView::ALIGN_MIDDLE,
            //'group'=>true,  // enable grouping
            'subGroupOf'=>1,
            'groupEvenCssClass'=>false,
        ],
        [
            'class'=>'\kartik\grid\DataColumn',
            'label'=> 'Sede',
            'attribute'=>'sede_reporte_estadistica',
            'vAlign' => GridView::ALIGN_MIDDLE,
            'groupEvenCssClass'=>false,
        ],
/*        [
            'class'=>'\kartik\grid\DataColumn',
            'label'=> 'ID Sede',
            'attribute'=>'id_sede_reporte_estadistica',
            'vAlign' => GridView::ALIGN_MIDDLE,
            'groupEvenCssClass'=>false,
        ],*/
        [
            'class'=>'\kartik\grid\DataColumn',
            'label'=> 'Socio C. Inst.',
            //'attribute'=>'cantidad_sci_reporte',
            'value' => function ($model, $index, $widget) {
                $anio = $model->anio_match1;
                $semestre = $model->semestre_match1;
                return $model->requerimientoIdRequerimiento->sciIdSci->nombreComuna;

            },
            'vAlign' => GridView::ALIGN_MIDDLE,
            'groupEvenCssClass'=>false,
        ],
        [
            'class'=>'\kartik\grid\DataColumn',
            'label'=> 'Título Requerimiento',
            'value' => function ($model, $index, $widget) {
                $anio = $model->anio_match1;
                $semestre = $model->semestre_match1;
                return $model->requerimientoIdRequerimiento->titulo;

            },
            'vAlign' => GridView::ALIGN_MIDDLE,
            'groupEvenCssClass'=>false,
        ],
        [
            'class'=>'\kartik\grid\DataColumn',
            'label'=> 'Descripción Requerimiento',
            'value' => function ($model, $index, $widget) {
                $anio = $model->anio_match1;
                $semestre = $model->semestre_match1;
                return $model->requerimientoIdRequerimiento->descripcion;

            },
            'vAlign' => GridView::ALIGN_MIDDLE,
            'groupEvenCssClass'=>false,
            'contentOptions' => ['style' => 'white-space:pre-line;'],
        ],
        [
            'class'=>'\kartik\grid\DataColumn',
            'label' => 'Estado Requerimiento',
            //'attribute'=>'estado_ejecucion_id_estado',
            'format' => 'html',
            'value'=>function ($model, $index, $widget) {
                $color = 'info';
                if(strcmp($model->requerimientoIdRequerimiento->estadoEjecucionIdEstado->nombre_estado, "No Asignado") == 0){
                    $color = 'danger';
                }
                if(strcmp($model->requerimientoIdRequerimiento->estadoEjecucionIdEstado->nombre_estado, "Asignado") == 0){
                    $color = 'warning';
                }
                if(strcmp($model->requerimientoIdRequerimiento->estadoEjecucionIdEstado->nombre_estado, "En desarrollo") == 0){
                    $color = 'primary';
                }
                if(strcmp($model->requerimientoIdRequerimiento->estadoEjecucionIdEstado->nombre_estado, "Finalizado") == 0){
                    $color = 'success';
                }
                return '<span class="label label-'.$color.'">'.$model->requerimientoIdRequerimiento->estadoEjecucionIdEstado->nombre_estado.'</span>';
            },
        ],
        [
            'class'=>'\kartik\grid\DataColumn',
            'label'=> 'Asignatura Relacionada',
            'value' => function ($model, $index, $widget) {
                $anio = $model->anio_match1;
                $semestre = $model->semestre_match1;
                return $model->asignaturaCodAsignatura->nombre_asignatura;

            },
            'vAlign' => GridView::ALIGN_MIDDLE,
            'groupEvenCssClass'=>false,
        ],
        /*
    [
        'class'=>'\kartik\grid\DataColumn',
        'label'=> 'Cantidad Socios Inst.',
        'attribute'=>'cantidad_sci_reporte',
        'vAlign' => GridView::ALIGN_MIDDLE,
        'groupEvenCssClass'=>false,
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'label'=> 'Cantidad Socios Benef.',
        'attribute'=>'cantidad_scb_reporte',
        'vAlign' => GridView::ALIGN_MIDDLE,
        'groupEvenCssClass'=>false,
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'label'=> 'Cantidad Alumnos',
        'attribute'=>'cantidad_alumnos_reporte',
        'vAlign' => GridView::ALIGN_MIDDLE,
        'groupEvenCssClass'=>false,
    ],*/
    ],
    'toolbar'=> [
        ['content'=>
            Html::a('<i class="glyphicon glyphicon-repeat"></i>', [''],
                ['data-pjax'=>1, 'class'=>'btn btn-default', 'title'=>'Refrescar Tabla']).
            '{toggleData}'.
            '{export}'
        ],
    ],
    'panel' => [
        'type' => 'primary',
        'heading' => '<i class="glyphicon glyphicon-list"></i> Asignaturas en Desarrollo o Finalizadas',
        //'before'=>'<em>* Resize table columns just like a spreadsheet by dragging the column edges.</em>',
        'after'=>'',
        'footer'=>false,
    ]
])?>
</div>
<?php Modal::begin([
    "id"=>"ajaxCrudModal",
    "footer"=>"",// always need it for jquery plugin
])?>
<?php Modal::end(); ?>