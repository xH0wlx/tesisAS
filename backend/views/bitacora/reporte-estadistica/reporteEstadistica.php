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


/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\ImplementacionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Reporte Estadísticas de Bitácoras';
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
            'value'=>function ($model, $index, $widget) { return $model->anio_implementacion; },
            'vAlign' => GridView::ALIGN_MIDDLE,
            //'group'=>true,
            'groupEvenCssClass'=>false,

        ],
        [
            'class'=>'\kartik\grid\DataColumn',
            'label' => 'Semestre periodo',
            'attribute'=>'semestre_numero',
            'value'=>function ($model, $index, $widget) { return $model->semestre_implementacion; },
            'vAlign' => GridView::ALIGN_MIDDLE,
            //'group'=>true,  // enable grouping
            'subGroupOf'=>1,
            'groupEvenCssClass'=>false,
        ],
        [
            'class'=>'\kartik\grid\DataColumn',
            'attribute'=>'asignaturaCodAsignatura.cod_asignatura',
            'vAlign' => GridView::ALIGN_MIDDLE,
            'group'=>true,  // enable grouping
            'subGroupOf'=>2,
            'groupEvenCssClass'=>false,
        ],
        [
            'class'=>'\kartik\grid\DataColumn',
            'label' => 'Nombre de Asignatura',
            'attribute'=>'asignaturaCodAsignatura.nombre_asignatura',
            'vAlign' => GridView::ALIGN_MIDDLE,
            'group'=>true,  // enable grouping
            'subGroupOf'=>3,
            'groupEvenCssClass'=>false,
        ],
        [
            'class'=>'\kartik\grid\DataColumn',
            'label' => 'Sede Asignatura',
            'attribute'=>'asignaturaCodAsignatura.carreraCodCarrera.facultadIdFacultad.sedeIdSede.nombre_sede',
            'vAlign' => GridView::ALIGN_MIDDLE,
            'group'=>true,  // enable grouping
            'subGroupOf'=>3,
            'groupEvenCssClass'=>false,
        ],
        [
            //'class'=>'\kartik\grid\DataColumn',
            //'label' => 'Sección',
            'attribute'=>'estado',
            'format' => 'html',
            'value'=>function ($model, $index, $widget) {
                $label = 0;
                switch($model->estado){
                    case 0:
                        $label = Html::bsLabel('A implementar', Html::TYPE_DEFAULT);
                        break;
                    case 1:
                        $label = Html::bsLabel('En desarrollo', Html::TYPE_PRIMARY);
                        break;
                    case 2:
                        $label = Html::bsLabel('Finalizada', Html::TYPE_SUCCESS);
                        break;
                }
                return $label;
            },

            //'footer' => 'my footer',
            'vAlign' => GridView::ALIGN_MIDDLE,
            'groupEvenCssClass'=>false,
            'pageSummary' => 'Total:',
        ],
        [
            //'class'=>'\kartik\grid\DataColumn',
            //'label' => 'Sección',
            'attribute'=>'cantidadGruposAsignatura',
            'pageSummary' => \backend\models\Implementacion::getTotal($dataProvider->models, 'cantidadGruposAsignatura'),
            //'footer' => 'my footer',
            'vAlign' => GridView::ALIGN_MIDDLE,
            'hAlign' => GridView::ALIGN_RIGHT,
            'group'=>true,  // enable grouping
            'subGroupOf'=>4,
            'groupEvenCssClass'=>false,
        ],
        [
            'class'=>'\kartik\grid\DataColumn',
            'attribute'=>'cantidadBitacorasAsignatura',
            'pageSummary' => \backend\models\Implementacion::getTotal($dataProvider->models, 'cantidadBitacorasAsignatura'),
            'vAlign' => GridView::ALIGN_MIDDLE,
            'hAlign' => GridView::ALIGN_RIGHT,
            'group'=>true,  // enable grouping
            'subGroupOf'=>5,
            'groupEvenCssClass'=>false,
        ],
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
        'heading' => '<i class="glyphicon glyphicon-list"></i> Asignaturas Implementadas Finalizadas',
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