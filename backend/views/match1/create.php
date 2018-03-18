<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\match1 */
$this->title = 'Match: Selección de Periodo';
$this->params['breadcrumbs'][] = ['label' => 'Principal', 'url' => ['/match1/principal']];
$this->params['breadcrumbs'][] = $this->title;

$rutaPeriodo = \yii\helpers\Url::to(['/match1/verificar-periodo-iniciado']);
$rutaSeleccion = \yii\helpers\Url::to(['/match1/seleccion']);

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
                \'Para continuar en este periodo, presione el siguiente enlace <a href="'.$rutaSeleccion.'?anio=\'+data.anio+\'&semestre=\'+data.semestre+\'" class="btn btn-primary">\'+data.anio+\'-\'+data.semestre+\' <i class="fa fa-chevron-circle-right"></i></a></div>\');
                    
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
    Recuerde que el match consiste en asociar Asignaturas a un Requerimiento,
    por lo tanto el periodo que ingrese es el periodo en el que planea darle respuesta a esos Requerimientos.
</div>

<div id="ultimo-registro">


</div>


<div class="match1-create">
    <?= $this->render('seleccion', [
        'modeloPeriodo' => $modeloPeriodo,
    ]) ?>
</div>
