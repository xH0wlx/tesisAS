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

$this->title = 'Reporte Estadísticas Requerimientos/Servicios';
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
            'pageSummary' => 'Total:',
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
            'label'=> 'Cantidad Socios Inst.',
            'attribute'=>'cantidad_sci_reporte',
            'vAlign' => GridView::ALIGN_MIDDLE,
            'hAlign' => GridView::ALIGN_RIGHT,
            'groupEvenCssClass'=>false,
            'pageSummary' => true,
            'pageSummaryFunc'=>GridView::F_SUM,
        ],
        [
            'class'=>'\kartik\grid\DataColumn',
            'label'=> 'Cantidad Requerimientos en Desarrollo o Cumplidos',
            'attribute'=>'cantidad_requerimientos_reporte',
            'vAlign' => GridView::ALIGN_MIDDLE,
            'hAlign' => GridView::ALIGN_RIGHT,
            'groupEvenCssClass'=>false,
            'pageSummary' => true,
            'pageSummaryFunc'=>GridView::F_SUM,
        ],
        [
            'class'=>'\kartik\grid\DataColumn',
            'label'=> 'Cantidad Requerimientos no Asignados',
            //'attribute'=>'cantidad_requerimientos_reporte',
            'value'=>function ($model, $index, $widget) {
                $anio = $model->anio_match1;
                $semestre = $model->semestre_match1;
                $subQuery = match1::find()->select('requerimiento_id_requerimiento')
                    ->joinWith('asignaturaCodAsignatura.carreraCodCarrera.facultadIdFacultad.sedeIdSede') // ensure table junction
                    ->where(['not', ['{{match1}}.implementacion_id_implementacion' => null]])
                    ->andWhere(['{{match1}}.anio_match1' => $anio])
                    ->andWhere(['{{match1}}.semestre_match1' => $semestre])
                    ->andWhere(['{{sede}}.id_sede' => $model->id_sede_reporte_estadistica]);

                $query = match1::find()
                    ->select([
                        '{{match1}}.*',
                        'COUNT(DISTINCT {{match1}}.requerimiento_id_requerimiento) AS cantidad_requerimientos_na_reporte',
                    ])
                    ->joinWith('asignaturaCodAsignatura.carreraCodCarrera.facultadIdFacultad.sedeIdSede') // ensure table junction
                    ->where(['{{match1}}.implementacion_id_implementacion' => NULL])
                    ->andWhere(['not in', 'requerimiento_id_requerimiento', $subQuery])
                    ->andWhere(['{{match1}}.anio_match1' => $anio])
                    ->andWhere(['{{match1}}.semestre_match1' => $semestre])
                    ->andWhere(['{{sede}}.id_sede' => $model->id_sede_reporte_estadistica])
                    ->groupBy(['{{sede}}.id_sede','{{match1}}.anio_match1','{{match1}}.semestre_match1'])->one();
                    //->orderBy(['{{implementacion}}.anio_implementacion' => SORT_ASC,
                    //    '{{implementacion}}.semestre_implementacion'=> SORT_ASC]);
                $valor = 0;
                if($query['cantidad_requerimientos_na_reporte'] == NULL){
                    return $valor;
                }else{
                    return $query['cantidad_requerimientos_na_reporte'];
                }
            },
            'vAlign' => GridView::ALIGN_MIDDLE,
            'hAlign' => GridView::ALIGN_RIGHT,
            'pageSummary' => true,
            'pageSummaryFunc'=>GridView::F_SUM,
            'groupEvenCssClass'=>false,
        ],
        [
            'class' => '\kartik\grid\FormulaColumn',
            'label' => 'Tasa de Respuesta',
            'hAlign' => GridView::ALIGN_RIGHT,

            'value' => function ($model, $key, $index, $widget) {
                $p = compact('model', 'key', 'index');
                // Write your formula below
                $total = ($widget->col(5, $p)+$widget->col(6, $p));
                if($total != 0){
                    $respuesta = number_format ($widget->col(5, $p)/$total,2) * 100;
                    $respuesta = $respuesta." %";
                    return $respuesta;
                }else{
                    return "No Aplica";
                }
            }
        ]
        /*[
            'class'=>'\kartik\grid\DataColumn',
            'label'=> 'Cantidad Docentes Participantes',
            'attribute'=>'cantidad_docentes_reporte',
            'vAlign' => GridView::ALIGN_MIDDLE,
            'groupEvenCssClass'=>false,
        ],
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