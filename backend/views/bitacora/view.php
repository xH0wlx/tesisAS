<?php

use yii\widgets\DetailView;
use yii\helpers\Url;
use yii\helpers\Html;
use kartik\dialog\Dialog;
use kartik\growl\Growl;
use kartik\growl\GrowlAsset;
use kartik\base\AnimateAsset;
GrowlAsset::register($this);
AnimateAsset::register($this);

/* @var $this yii\web\View */
/* @var $model backend\models\bitacora */

$js = '
$("#boton-lectura").click(function(e) {
    $.ajax({
        type: "POST",
        url: "'.Url::to(['bitacora/marcar-leida']).'",
        data: { 
            idBitacora: $(this).val(), // < note use of \'this\' here
        },
        success: function(result) {
            if(result.code == 100){
                BootstrapDialog.alert("Bitácora marcada como leída");
            }else{
                krajeeDialog.alert("Error al marcar como leída");
            }
        },
        error: function(result) {
                krajeeDialog.alert("Error al marcar como leída");
        }
    });
});

$("#boton-aprobacion").click(function(e) {
    $.ajax({
        type: "POST",
        url: "'.Url::to(['bitacora/marcar-aprobada']).'",
        data: { 
            idBitacora: $(this).val(), // < note use of \'this\' here
        },
        success: function(result) {
            if(result.code == 100){
                BootstrapDialog.alert("Bitácora Aprobada");
            }else{
                krajeeDialog.alert("Error al aprobar bitácora");
            }
        },
        error: function(result) {
                krajeeDialog.alert("Error al aprobar bitácora");
        }
    });
});
';
$this->registerJs($js);
echo Dialog::widget([]);
?>
<div class="bitacora-view">
    <?php if(Yii::$app->user->can("docente")){ ?>
        <?= Html::button('Aprobar Bitácora', ['id'=>'boton-aprobacion','value'=>$model->id_bitacora,'class' => 'pull-right btn btn-success']) ?>
    <?php } ?>
    <?php if(Yii::$app->user->can("coordinador general")){ ?>
    <?= Html::button('Marcar como leída', ['id'=>'boton-lectura','value'=>$model->id_bitacora,'class' => 'pull-right btn btn-success']) ?>
    <?php } ?>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            //'id_bitacora',
            'grupoTrabajoIdGrupoTrabajo.numero_grupo_trabajo',
            'fecha_bitacora',
            'hora_inicio',
            'hora_termino',
            'actividad_realizada',
            'resultados',
            'observaciones',
            //'fecha_lectura',
            //'creado_en',
            //'modificado_en',
        ],
    ]) ?>

</div>
