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

$this->title = 'Reporte Resumen Bitácoras';
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



<?php echo $this->render('_searchPeriodoResumen', ['model' => $searchModel]); ?>
<div id="ajaxCrudDatatable">

<?=GridView::widget([
    'id'=>'crud-datatable',
    'dataProvider' => $dataProvider,
    //'filterModel' => $searchModel,
    //'showPageSummary'=>true,
    //'showFooter'=>true,
    'pjax'=>true,
    'striped' => false,
    'bordered'=>true,
    'hover'=> true,
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
            'value'=>function ($model, $index, $widget) { return $model->grupoTrabajoIdGrupoTrabajo->seccionIdSeccion->implementacionIdImplementacion->anio_implementacion; },
            'vAlign' => GridView::ALIGN_MIDDLE,
            'group'=>true,
            'groupEvenCssClass'=>false,
            'groupFooter'=>function ($model, $key, $index, $widget) { // Closure method
                return [
                    //'mergeColumns'=>[[1,13]], // columns to merge in summary
                    'content'=>[              // content to show in each summary cell
                        16=>"Total:",
                        17=>GridView::F_SUM,
                    ],
                    'contentFormats'=>[      // content reformatting for each summary cell
                        17=>['format'=>'number', 'decimals'=>0],

                    ],
                    'contentOptions'=>[      // content html attributes for each summary cell
                        //14=>['style'=>'text-align:right'],
                    ],
                    // html attributes for group summary row
                    'options'=>['class'=>'warning','style'=>'font-weight:bold;']
                ];
            },
        ],
        [
            'class'=>'\kartik\grid\DataColumn',
            'label' => 'Semestre periodo',
            'attribute'=>'semestre_numero',
            'value'=>function ($model, $index, $widget) { return $model->grupoTrabajoIdGrupoTrabajo->seccionIdSeccion->implementacionIdImplementacion->semestre_implementacion; },
            'vAlign' => GridView::ALIGN_MIDDLE,
            'group'=>true,  // enable grouping
            'subGroupOf'=>1,
            'groupEvenCssClass'=>false,
        ],
        [
            'class'=>'\kartik\grid\DataColumn',
            'attribute'=>'grupoTrabajoIdGrupoTrabajo.seccionIdSeccion.implementacionIdImplementacion.asignaturaCodAsignatura.cod_asignatura',
            'vAlign' => GridView::ALIGN_MIDDLE,
            'group'=>true,  // enable grouping
            'subGroupOf'=>2,
            'groupEvenCssClass'=>false,
        ],
        [
            'class'=>'\kartik\grid\DataColumn',
            'label' => 'Nombre de Asignatura',
            'attribute'=>'grupoTrabajoIdGrupoTrabajo.seccionIdSeccion.implementacionIdImplementacion.asignaturaCodAsignatura.nombre_asignatura',
            'vAlign' => GridView::ALIGN_MIDDLE,
            'group'=>true,  // enable grouping
            'subGroupOf'=>3,
            'groupEvenCssClass'=>false,
        ],
        [
            'class'=>'\kartik\grid\DataColumn',
            'label' => 'Sede Asignatura',
            'attribute'=>'grupoTrabajoIdGrupoTrabajo.seccionIdSeccion.implementacionIdImplementacion.asignaturaCodAsignatura.carreraCodCarrera.facultadIdFacultad.sedeIdSede.nombre_sede',
            'vAlign' => GridView::ALIGN_MIDDLE,
            'group'=>true,  // enable grouping
            'subGroupOf'=>4,
            'groupEvenCssClass'=>false,
        ],
        [
            //'class'=>'\kartik\grid\DataColumn',
            //'label' => 'Sección',
            'attribute'=>'grupoTrabajoIdGrupoTrabajo.seccionIdSeccion.numero_seccion',
            //'footer' => 'my footer',
            'vAlign' => GridView::ALIGN_MIDDLE,
            'group'=>true,  // enable grouping
            'subGroupOf'=>5,
            'groupEvenCssClass'=>false,
        ],
        [
            //'class'=>'\kartik\grid\DataColumn',
            'label' => 'Estado Asignatura',
            'attribute'=>'grupoTrabajoIdGrupoTrabajo.seccionIdSeccion.implementacionIdImplementacion.estado',
            'format' => 'html',
            'value'=>function ($model, $index, $widget) {
                $label = 0;
                switch($model->grupoTrabajoIdGrupoTrabajo->seccionIdSeccion->implementacionIdImplementacion->estado){
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
            'group'=>true,  // enable grouping
            'subGroupOf'=>3,
            'groupEvenCssClass'=>false,
        ],
        [
            'class'=>'\kartik\grid\DataColumn',
            'label' => 'N° Grupo de Trabajo',
            'attribute'=>'grupoTrabajoIdGrupoTrabajo.numero_grupo_trabajo',
            'vAlign' => GridView::ALIGN_MIDDLE,
            'group'=>true,  // enable grouping
            'subGroupOf'=>6,
            'groupEvenCssClass'=>false,
        ],
        [
            'class'=>'\kartik\grid\DataColumn',
            'label' => 'Integrantes Grupo de Trabajo',
            'vAlign' => GridView::ALIGN_MIDDLE,
            'format' => 'html',
            //'attribute'=>'grupoTrabajoIdGrupoTrabajo.alumnoInscritoHasGrupoTrabajos.alumnoInscritoSeccionIdAlumnoInscritoSeccion.alumnoRutAlumno.nombre',
            'value'=>function ($model, $index, $widget) {
                $string = "";
                $alumnos = $model->grupoTrabajoIdGrupoTrabajo->alumnoInscritoHasGrupoTrabajos;
                foreach ($alumnos as $alumno){
                    $string = $string.$alumno->alumnoInscritoSeccionIdAlumnoInscritoSeccion->alumnoRutAlumno->nombre."<br>";
                }
                $string = $string."";
                return $string;
            },
            'group'=>true,  // enable grouping
            'subGroupOf'=>9,
            'groupEvenCssClass'=>false,
        ],
        [
            'class'=>'\kartik\grid\DataColumn',
            'vAlign' => GridView::ALIGN_MIDDLE,
            'attribute'=>'fecha_bitacora',
            'label' => 'Fecha Bitácora',
            //'format' => 'html',
            //'attribute'=>'grupoTrabajoIdGrupoTrabajo.alumnoInscritoHasGrupoTrabajos.alumnoInscritoSeccionIdAlumnoInscritoSeccion.alumnoRutAlumno.nombre',
            'value'=>function ($model, $index, $widget) {
                return \Yii::$app->formatter->asDatetime($model->fecha_bitacora, "php:d-m-Y");
            }
        ],
        [
            'class'=>'\kartik\grid\DataColumn',
            'vAlign' => GridView::ALIGN_MIDDLE,

            'attribute'=>'hora_inicio',
        ],
        [
            'class'=>'\kartik\grid\DataColumn',
            'vAlign' => GridView::ALIGN_MIDDLE,

            'attribute'=>'hora_termino',
        ],
        [
            'class'=>'\kartik\grid\DataColumn',
            'vAlign' => GridView::ALIGN_MIDDLE,

            'attribute'=>'actividad_realizada',
            'contentOptions' => [
                'style'=>'max-width:250px; min-height:100px; overflow: auto; word-wrap: break-word;'
            ],
        ],
        [
            'class'=>'\kartik\grid\DataColumn',
            'vAlign' => GridView::ALIGN_MIDDLE,
            'attribute'=>'resultados',
            'contentOptions' => [
                'style'=>'max-width:250px; min-height:100px; overflow: auto; word-wrap: break-word;'
            ],
        ],
        [
            'class'=>'\kartik\grid\DataColumn',
            'vAlign' => GridView::ALIGN_MIDDLE,
            'attribute'=>'observaciones',
            'contentOptions' => [
                'style'=>'max-width:250px; min-height:100px; overflow: auto; word-wrap: break-word;'
            ],
        ],
        [
            'label'=>'Archivo',
            'mergeHeader' => true,
            'hAlign' => 'center',
            'format'=>'raw',
            'value' => function($model, $key, $index, $column)
            {
                $evidencia = $model->evidencia;
                if($evidencia != null){
                    return
                        \kartik\helpers\Html::a('<i class="fa fa-download" aria-hidden="true"></i> Descargar Archivo',
                            ['download', 'id' => $model->evidencia->id_evidencia], ['class' => 'btn btn-success']);
                }else{
                    return "Bitácora sin Archivo.";
                }
            }
        ],
        [
            'class'=>'\kartik\grid\DataColumn',
            'attribute'=>'cantidadBitacoras',
            //'footer' => \backend\models\Implementacion::getTotal($dataProvider->models, 'cantidadGruposAsignatura'),
            'vAlign' => GridView::ALIGN_MIDDLE,
            'group'=>true,  // enable grouping
            'subGroupOf'=>8,
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
        'heading' => '<i class="glyphicon glyphicon-list"></i> Bitácoras ingresadas al sistema',
        'footer' => false,
    ]
])?>
</div>
<?php Modal::begin([
    "id"=>"ajaxCrudModal",
    "footer"=>"",// always need it for jquery plugin
])?>
<?php Modal::end(); ?>