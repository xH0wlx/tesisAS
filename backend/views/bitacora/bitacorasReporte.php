<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset;
use johnitvn\ajaxcrud\BulkButtonWidget;
use kartik\date\DatePicker;
use kartik\growl\GrowlAsset;
use kartik\base\AnimateAsset;
use kartik\depdrop\DepDrop;


/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\ImplementacionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Reporte Bitácoras';
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
var_dump($todo[0]->seccions[0]->grupoTrabajos[0]->alumnoInscritoHasGrupoTrabajos[0]);

?>

<?php

    if(isset($dataProvider)){
        if($dataProvider->getTotalCount() != 0){
            $boton = true;
        }else{
            $boton = false;
        }
?>
    <h2 style="margin-bottom: 20px;">Lista de implementaciones</h2>
        <div class="implementacion-index">
            <div id="ajaxCrudDatatable">
                <?php $form2 = ActiveForm::begin(); ?>
                <?=GridView::widget([
                    'id' => 'crud-datatable',
                    'dataProvider' => $dataProvider,
                    'pjax'=>true,
                    'panelBeforeTemplate' => '',
                    'columns' => [
                        [
                            'class' => 'kartik\grid\RadioColumn',
                            //'multiple' => false,
                            //'rowSelectedClass' => GridView::TYPE_SUCCESS,
                            //'width' => '20px',
                        ],
                        [
                            'class' => 'kartik\grid\SerialColumn',
                            'width' => '30px',
                        ],
                        [
                            'class'=>'\kartik\grid\DataColumn',
                            'label' => 'Año periodo',
                            'attribute'=>'grupoTrabajoIdGrupoTrabajo.seccionIdSeccion.implementacionIdImplementacion.anio_implementacion',
                        ],
                        [
                            'class'=>'\kartik\grid\DataColumn',
                            'label' => 'Semestre periodo',
                            'attribute'=>'grupoTrabajoIdGrupoTrabajo.seccionIdSeccion.implementacionIdImplementacion.semestre_implementacion',
                        ],
                        [
                            'class'=>'\kartik\grid\DataColumn',
                            'attribute'=>'grupoTrabajoIdGrupoTrabajo.seccionIdSeccion.implementacionIdImplementacion.asignaturaCodAsignatura.cod_asignatura',
                        ],
                        [
                            'class'=>'\kartik\grid\DataColumn',
                            'label' => 'Nombre de Asignatura',
                            'attribute'=>'grupoTrabajoIdGrupoTrabajo.seccionIdSeccion.implementacionIdImplementacion.asignaturaCodAsignatura.nombre_asignatura',
                        ],
                        [
                            'class'=>'\kartik\grid\DataColumn',
                            'label' => 'Sección',
                            'attribute'=>'grupoTrabajoIdGrupoTrabajo.seccionIdSeccion.numero_seccion',
                        ],
                        [
                            'class'=>'\kartik\grid\DataColumn',
                            'label' => 'N° Grupo de Trabajo',
                            'attribute'=>'grupoTrabajoIdGrupoTrabajo.numero_grupo_trabajo',
                        ],
                        [
                            'class'=>'\kartik\grid\DataColumn',
                            'label' => 'Integrantes Grupo de Trabajo',
                            'format' => 'html',
                            //'attribute'=>'grupoTrabajoIdGrupoTrabajo.alumnoInscritoHasGrupoTrabajos.alumnoInscritoSeccionIdAlumnoInscritoSeccion.alumnoRutAlumno.nombre',
                            'value'=>function ($model, $index, $widget) {
                                    $string = "";
                                    $alumnos = $model->grupoTrabajoIdGrupoTrabajo->alumnoInscritoHasGrupoTrabajos;
                                    foreach ($alumnos as $alumno){
                                        $string = $string.$alumno->alumnoInscritoSeccionIdAlumnoInscritoSeccion->alumnoRutAlumno->nombre."<br>";
                                    }
                                return $string;
                                   }
                        ],
                        [
                            'class'=>'\kartik\grid\DataColumn',
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
                            'attribute'=>'hora_inicio',
                        ],
                        [
                            'class'=>'\kartik\grid\DataColumn',
                            'attribute'=>'hora_termino',
                        ],
                        [
                            'class'=>'\kartik\grid\DataColumn',
                            'attribute'=>'actividad_realizada',
                        ],
                        [
                            'class'=>'\kartik\grid\DataColumn',
                            'attribute'=>'resultados',
                        ],
                        [
                            'class'=>'\kartik\grid\DataColumn',
                            'attribute'=>'observaciones',
                        ],
                    ],
                    'striped' => true,
                    'condensed' => true,
                    'responsive' => true,
                    'panel' => [
                        'type' => 'primary',
                        'heading' => '<i class="glyphicon glyphicon-list"></i> Lista de implementaciones',
                        'footer' => $boton? Html::submitButton('Siguiente', ['name'=> 'seleccion', 'value' => 'true','class' => 'pull-right btn btn-success']) :'',
                        //'footer' => Html::submitButton('Siguiente', ['name'=> 'seleccion', 'value' => 'true','class' => 'pull-right btn btn-success']),
                    ]
                ])?>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
<?php
    }//FIN IF DATAPROVIDER
?>


<?php Modal::begin([
    "id"=>"ajaxCrudModal",
    "footer"=>"",// always need it for jquery plugin
])?>
<?php Modal::end(); ?>