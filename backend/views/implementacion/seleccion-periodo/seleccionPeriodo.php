<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset;
use johnitvn\ajaxcrud\BulkButtonWidget;

CrudAsset::register($this);

/* @var $this yii\web\View */
/* @var $model backend\models\match1 */
$this->title = 'Implementación: Selección de Periodo';
$this->params['breadcrumbs'][] = $this->title;

$rutaPeriodo = \yii\helpers\Url::to(['/servicio/verificar-periodo-iniciado']);
$rutaSeleccion = \yii\helpers\Url::to(['/implementacion/seleccion-asignatura']);

$informacionUltimoPeriodo = "<div class=\"alert alert-warning alert-dismissible\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">×</button><h4><i class=\"icon fa fa-warning\"></i> Atención</h4>.</div>";

$js = '
$( document ).ready(function() {
    $.ajax({
        url: \''.$rutaPeriodo.'\',
        type: "POST",
        data: {},
        success: function (data) {
            if(data.codigo == "exito"){
                $("#ultimo-registro").html(\'<div class=\"alert alert-warning alert-dismissible\">\'+
                \'<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">×</button>\'+
                \'<h4><i class=\"icon fa fa-warning\"></i> Atención</h4>El último registro de Asignación de Servicios ingresado fue en el periodo \'+data.anio+\'-\'+data.semestre+\'<br>\'+
                \'Para continuar en este periodo, presione el siguiente enlace <a href="'.$rutaSeleccion.'?anio=\'+data.anio+\'&semestre=\'+data.semestre+\'" class="btn btn-primary">\'+data.anio+\'-\'+data.semestre+\' <i class="fa fa-chevron-circle-right"></i></a></div>\');
                    
            }else{
         
            }
        },
        error: function () {
            alert("Error al identificar último registro en Match 1");
        }
    });//FIN AJAX
});  

var $grid = $(\'#crud-datatable\');
 
$grid.on(\'grid.radiochecked\', function(ev, key, val) {
    //if(confirm("Confirma que desea implementar la asignatura código: "+key)){
    //    window.location.replace("/implementacion/paso-unox?idAsignatura="+key);
    //}
});

function setBotonSiguiente(){
    $(\'.siguiente\').on("click", function(event){
        event.preventDefault();
        matchSeleccionado = $(\'input[name="kvradio"]:checked\').val();
        if(matchSeleccionado != null){
            $(\'#formulario-seleccion\').submit();
        }else{
            alert("Seleccione una asignatura");
        }
    });
}

function setBotonesGrid(){
    setBotonSiguiente();
}

$(\'#pjax-grid-asignatura-requerimiento\').on(\'pjax:success\', function() {
    setEventoChangeCheckbox();
    setBotonesGrid(); 
});

setBotonesGrid();    
';
$this->registerJs($js);


?>
<div class="alert alert-info alert-dismissible">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
    <h4><i class="icon fa fa-info"></i> Información</h4>
    La <b>Implementación</b> constituye la puesta en marcha de una asignatura para su ejecución con metodología Aprendizaje Serivicio, <br>
    por lo tanto, el periodo de implementación debe corresponderse con la etapa anterior de "Asignación de Servicios".
</div>

<div id="ultimo-registro">


</div>


<div class="match1-create">
    <?= $this->render('_seleccionPeriodo', [
        'modeloPeriodo' => $modeloPeriodo,
    ]) ?>
</div>

<?php
if(isset($dataProvider)){
    ?>
    <h2 style="margin-bottom: 20px;">La lista sólo muestra las asignaturas que tienen un servicio asociado, y no han sido implementadas</h2>
    <div class="implementacion-index">
        <div id="ajaxCrudDatatable">
            <?=Html::beginForm(['implementacion/detalle-seleccion-asignatura'],'post', ['id' => 'formulario-seleccion']);?>
            <?=GridView::widget([
                'id' => 'crud-datatable',
                'dataProvider' => $dataProvider,
                'pjax'=>true,
                'panelBeforeTemplate' => '',
                'columns' => [
                    [
                        'class' => 'kartik\grid\RadioColumn',
                        /*'radioOptions' => function ($model) {
                            return [
                                'value' => $model['idesAgrupadas'],
                            ];
                        }*/
                        //'multiple' => false,
                        //'rowSelectedClass' => GridView::TYPE_SUCCESS,
                        //'width' => '20px',
                    ],
                    [
                        'class' => 'kartik\grid\SerialColumn',
                        'width' => '30px',
                    ],
                    /* [
                         'class'=>'\kartik\grid\DataColumn',
                         'attribute'=>'idesAgrupadas',
                     ],*/
                    [
                        'class'=>'\kartik\grid\DataColumn',
                        'attribute'=>'asignatura_cod_asignatura',
                    ],
                    [
                        'class'=>'\kartik\grid\DataColumn',
                        'attribute'=>'asignaturaCodAsignatura.nombre_asignatura',
                    ],
                    [
                        'class'=>'\kartik\grid\DataColumn',
                        'attribute'=>'asignatura_carrera',
                        'label' => 'Carrera',
                        'value'=>function ($model, $index, $widget) { return $model->asignaturaCodAsignatura->carreraCodCarrera->alias_carrera; }
                    ],
                    [
                        'class'=>'\kartik\grid\DataColumn',
                        'attribute'=>'asignatura_sede',
                        'label' => 'Sede',
                        'value'=>function ($model, $index, $widget) { return $model->asignaturaCodAsignatura->carreraCodCarrera->facultadIdFacultad->sedeIdSede->nombre_sede; }
                    ],

                ],
                'striped' => true,
                'condensed' => true,
                'responsive' => true,
                'panel' => [
                    'type' => 'primary',
                    'heading' => '<i class="glyphicon glyphicon-list"></i> Asignaturas Preseleccionadas para Implementación (Match1 + Servicio)',
                    'footer'=>Html::submitButton('Siguiente <i class="fa fa-arrow-circle-right"></i>', ['data-pjax'=>0,'class' => 'siguiente pull-right btn btn-lg btn-success']),
                ]
            ])?>
            <?= Html::endForm();?>

        </div>
    </div>
    <?php
}//FIN IF DATAPROVIDER['/servicio/create-provisorio',
//'anio' =>Yii::$app->request->get('anio'), 'semestre' => Yii::$app->request->get('semestre')],
?>

<?php Modal::begin([
    "id"=>"ajaxCrudModal",
    "footer"=>"",// always need it for jquery plugin
])?>
<?php Modal::end(); ?>