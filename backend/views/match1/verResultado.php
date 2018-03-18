<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\match1 */
$this->title = 'Ver Resultado Match1';
$this->params['breadcrumbs'][] = ['label' => 'Principal', 'url' => ['/match1/principal']];
$this->params['breadcrumbs'][] = $this->title;

$rutaPeriodo = \yii\helpers\Url::to(['/match1/verificar-periodo-iniciado']);

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
                \'Para utilizar este mismo periodo presione aquí</div>\');
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
    Según el periodo que eliga, se mostrarán todos los Requerimientos con Asignaturas asignadas hasta el momento.
</div>

<div id="ultimo-registro">


</div>


<div class="match1-create">
    <?= $this->render('//match1/verMatch/seleccionPeriodo', [
        'modeloPeriodo' => $modeloPeriodo,
    ]) ?>
</div>
