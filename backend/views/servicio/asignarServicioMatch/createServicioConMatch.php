<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\match1 */
$this->title = 'Selección de periodo (Paso 2: Servicios)';
$this->params['breadcrumbs'][] = 'Selección de periodo';

$rutaPeriodo = \yii\helpers\Url::to(['/match1/verificar-periodo-iniciado']);
$rutaSeleccion = \yii\helpers\Url::to(['/servicio/seleccion-requerimientos']);

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
                \'<h4><i class=\"icon fa fa-warning\"></i> Atención</h4>El último registro de asignación de asignaturas (Match 1) ingresado fue en el periodo \'+data.anio+\'-\'+data.semestre+\'<br>\'+
                \'Para utilizar este mismo periodo presione el siguiente enlace <a href="'.$rutaSeleccion.'?anio=\'+data.anio+\'&semestre=\'+data.semestre+\'" class="btn btn-primary">\'+data.anio+\'-\'+data.semestre+\' <i class="fa fa-chevron-circle-right"></i></a></div>\');
            }else{
         
            }
        },
        error: function () {
            alert("Error al identificar último registro en Match 1");
        }
        });//FIN AJAX
});  
    
';
$this->registerJs($js);


?>
<div class="alert alert-info alert-dismissible">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
    <h4><i class="icon fa fa-info"></i> Información</h4>
    En esta sección se ingresan los <b>Servicios</b> que darán respuesta a los <b>Requerimientos</b>,
    por lo tanto el periodo que ingrese es el periodo utilizado en la asignación de Asignaturas (Match 1).
</div>

<div id="ultimo-registro">


</div>


<div class="match1-create">
    <?= $this->render('/servicio/asignarServicioMatch/_seleccionPeriodo', [
        'modeloPeriodo' => $modeloPeriodo,
    ]) ?>
</div>
