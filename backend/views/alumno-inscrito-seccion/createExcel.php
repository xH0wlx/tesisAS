<?php
/**
 * Created by PhpStorm.
 * User: Howl
 * Date: 06-06-2017
 * Time: 17:02
 */
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\file\FileInput;
$request = Yii::$app->request;
$this->title = 'Inscribir alumnos vía Excel';
$this->params['breadcrumbs'][] = ['label' => 'Panel de Implementación',
    'url' => ['/implementacion/panel-implementacion', 'idImplementacion'=> $request->get('idImplementacion')]];
$this->params['breadcrumbs'][] = ['label' => 'Inscribir Alumnos',
    'url' => ['/implementacion/modificar-inscripcion', 'idImplementacion'=> $request->get('idImplementacion'), 'idSeccion'=> $request->get('idSeccion')]];
$this->params['breadcrumbs'][] = $this->title;

//$this->params['breadcrumbs'][] = ['label' => 'Asignaturas', 'url' => ['index']];
//$this->params['breadcrumbs'][] = $this->title;

$ruta = \yii\helpers\Url::to(['/alumno-inscrito-seccion/create-excel']);

$js = '
 $("#input-plantilla-excel").fileinput({
        uploadAsync: false,
        uploadUrl: \''.$ruta.'\',
});

$(\'#input-plantilla-excel\').on(\'fileloaded\', function(event, file, previewId, index, reader) {
    console.log("fileloaded");
});

$(\'#input-plantilla-excel\').on("change", function(){
    $(\'.fileinput-upload\').on(\'click\',function(e){
    e.preventDefault();
    $(\'#input-plantilla-excel\').on(\'filepreupload\', function(event, data, previewId, index) {
        var form = data.form, files = data.files, extra = data.extra,
        response = data.response, reader = data.reader;
        console.log(\'File pre upload triggered\');
    });    
        
   
});     
});


  
';

$this->registerJs($js);

$this->registerJsFile('@web/js/implementacion/funciones.js', ['depends' => [\yii\web\JqueryAsset::className()] ] );

?>
<!--var form = $("#form-excel");
var formData = new FormData($(\'form\')[0]);
console.log(formData);
$.ajax({
url: \''.$ruta.'\',
type: "POST",
data: formData,
dataType: "html",
success: function (vistaParcial) {
alert("llegó");
$("#container-alumnos").html(newPartialView);
},
error: function () {
alert("Error en el formulario de Fecha (Match1)");
}
});//FIN AJAX-->
<div class="alert alert-warning alert-dismissible">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
    <h4><i class="icon fa fa-warning"></i> Atención</h4>
    Para ingresar los datos via Excel se requiere el formato de la siguiente plantilla<br><br>
    <?= Html::a('<i class="fa fa-2x fa-file-excel-o"></i> Descargar', ['/alumno-inscrito-seccion/descarga-plantilla-inscripcion'], ['class' => 'btn btn-success']) ?>
    <br><br>En ella deberá pegar los datos copiados del archivo correspondiente a la asignatura y sección en cuestión (archivo descargado de intranet).
</div>

<div class="panel panel-primary">
    <div class="panel-heading">Subir Archivo</div>
    <div class="panel-body">
        <div class="seccion-form">
            <?php $form = ActiveForm::begin(['id' => 'form-excel',
                    'options'=>['enctype'=>'multipart/form-data']
            ]);?>
            <?= $form->field($modelArchivoExcel, 'archivoExcel')->widget(FileInput::classname(), [
                //'options' => ['accept' => 'image/*'],
                'options' => ['id' => 'input-plantilla-excel'],
                'pluginOptions'=>['allowedFileExtensions'=>['xls','xlsx'],'showUpload' => false,],
            ]);   ?>

            <?= Html::button('<i class="fa fa-chevron-circle-left"></i> Volver sin guardar', ['id' => 'botonVolverSinGuardar',
                'value'=> \yii\helpers\Url::to(['/implementacion/modificar-inscripcion', 'idImplementacion'=> $request->get('idImplementacion'), 'idSeccion'=> $request->get('idSeccion')]),
                'class' =>'btn btn-danger']) ?>

            <?= Html::submitButton('<i class="fa fa-floppy-o" aria-hidden="true"></i> Guardar y Volver', ['name'=>'guardarYSalir', 'value'=>'index','class' =>'btn btn-primary']) ?>
            <!-- //Html::submitButton('Guardar y Continuar', ['name'=>'guardarYContinuar','value'=> 'decision-seccion','class' => 'btn btn-primary']) -->
            <?php ActiveForm::end(); ?>

        </div>
    </div>
</div>

<div id="container-alumnos">

</div>