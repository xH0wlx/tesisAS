<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
//use yii\widgets\DetailView;
use kartik\detail\DetailView;
use yii\helpers\ArrayHelper;
use backend\models\ContactoSci;
use backend\models\Sci;
use yii\widgets\ListView;
use yii\data\ActiveDataProvider;
use kartik\dialog\Dialog;

/* @var $this yii\web\View */
/* @var $model backend\models\sci */


$rutaPeriodo = Url::to(['/match1/verificar-periodo']);

$js = '
$(\'#botonSeleccionarPeriodo\').on(\'click\',function(e){
    e.preventDefault();
    var $form = $("#periodo"), 
    data = $form.data("yiiActiveForm");
    $.each(data.attributes, function() {
        this.status = 3;
    });
    $form.yiiActiveForm("validate");
    if ($("#periodo").find("div.has-error").length !== 0) {
        krajeeDialog.alert(\'Debe ingresar año y semestre.\');
    }else{
        var anio = $("#aniosemestre-anio").val();
        var semestre = $("#aniosemestre-semestre").val();
    
        $.ajax({
        url: \''.$rutaPeriodo.'\',
        type: "POST",
        data: {"anio":anio, "semestre":semestre},
        success: function (data) {
            if(data.codigo == "error"){
                krajeeDialog.confirm(data.respuesta, function (result) {
                    if (result) { // ok button was pressed
                       
                    } else { // confirmation was cancelled
                        // execute your code for cancellation
                    }
                });
            }else{
                $form.submit();
            }
        },
        error: function () {
            alert("Error en el formulario de Fecha (Match1)");
        }
        });//FIN AJAX
    }
    
 
    
        

    //$(this).submit();
});


';
$this->registerJs($js);
echo Dialog::widget([
]);
?>
    <!--<div>
        <i class="fa fa-window-restore" style="font-size: 4em;"></i><i style="font-size: 2em;"> Match 1</i>
    </div>-->

<div class="panel panel-primary">
    <div class="panel-heading">Periodo</div>
    <div class="panel-body">
        <?php $form = ActiveForm::begin(['id'=>'periodo']); ?>
        <div class="row">
            <div class="col-md-6">
                <?= $form->field($modeloPeriodo, 'anio')->textInput() ?>
            </div>
            <div class="col-md-6">
                <?php $var = [ 1 => 'Primer Semestre', 2 => 'Segundo Semestre']; ?>
                <?= $form->field($modeloPeriodo, 'semestre')->dropDownList($var, ['prompt' => 'Seleccione semestre' ]); ?>
            </div>
        </div>

        <div class="form-group">
            <?= Html::submitButton('<i class="fa fa-check" aria-hidden="true"></i> Aceptar', ['id' => 'botonSeleccionarPeriodo','class' => 'btn btn-success']) ?>
        </div>
        <?php
        ActiveForm::end();
        ?>
    </div>
</div>
    <!--<div class="alert alert-info alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h4><i class="icon fa fa-info"></i> Información</h4>
        A continuación se muestran sólo los Socios Comunitarios Intitucionales que poseen Requerimientos No Asignados.
    </div>-->
