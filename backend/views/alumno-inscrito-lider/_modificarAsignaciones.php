<?php
use yii\helpers\Html;
use yii\bootstrap\modal;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

use kartik\dialog\Dialog;
use kartik\growl\GrowlAsset;
use kartik\base\AnimateAsset;
GrowlAsset::register($this);
AnimateAsset::register($this);
/* @var $this yii\web\View */
/* @var $model backend\models\alumnoInscritoLider */
/* @var $form yii\widgets\ActiveForm */
/* @var $grupoTrabajo viene del controlador */
/* @var $alumnosGrupo viene del controlador */
/* @var $asignacionesActivas viene del controlador */
/* @var $modeloAsignaciones viene del controlador */

echo Dialog::widget([
    //'libName' => 'krajeeDialogCust', // optional if not set will default to `krajeeDialog`
    //'options' => ['draggable' => true, 'closable' => true], // custom options
]);

Modal::begin([
    'header' => '',
    'id' => 'modalSCB',
    'size' => 'modal-lg',
]);
echo "<div id='modalContentSCB'></div>";
Modal::end();


$request = Yii::$app->request;
?>

<div class="modificacion-campos-asignacion">
    <div class="row">
        <div class="col-md-12">
            <div class="pull-right">
                <a class="modalButton btn btn-success" data-title="Asignar" href="<?=Url::to(['alumno-inscrito-lider/crear-scb',
                    'idGrupoTrabajo'=>$grupoTrabajo->id_grupo_trabajo]); ?>"><i class="fa fa-plus"></i> Asignar Nuevo Socio</a>
                <a class="modalButton btn btn-info" data-title="Historial" href="<?=Url::to(['alumno-inscrito-lider/ver-historial-scb',
                    'idGrupoTrabajo'=>$grupoTrabajo->id_grupo_trabajo]); ?>"><i class="fa fa-eye"></i> Ver Historial</a>
            </div>
        </div>
    </div>

    <div id="formulario-asignaciones">
        <table class="table table-striped">
            <tr>
                <th class="col-md-4" style="display:none;"></th>
                <th class="col-md-8" style="display:none;"></th>
            </tr>
            <?php foreach ($grupoTrabajo->grupoTrabajoHasScbsNoCambiados as $i => $arrayModeloAsignaciones): ?>
                <?php if(!$arrayModeloAsignaciones->isNewRecord){ ?>
                <tr>
                    <td><?php echo "<span class=\"badge alert-info\">&nbsp;</span> "?><strong><?= $arrayModeloAsignaciones->scbIdScb->nombre_negocio  ?></strong></td>
                    <td class="pull-right">
                        <a class="modalButton btn btn-primary" data-toggle="tooltip" title="Reemplazar" data-title="Reemplazar" href="<?=Url::to(['alumno-inscrito-lider/reemplazar-scb',
                            'idAsignacion'=>$arrayModeloAsignaciones->id_grupo_trabajo_has_scb]); ?>"><i class="fa fa-exchange"></i></a>

                        <a class="modalButton btn btn-danger" data-toggle="tooltip" title="Eliminar" data-title="Eliminar"
                           href="<?= Url::to(['alumno-inscrito-lider/eliminar-scb',
                               'idAsignacion' => $arrayModeloAsignaciones->id_grupo_trabajo_has_scb]); ?>"><i class="fa fa-minus"></i></a>
                    </td>
                </tr>
            <?php } ?>
            <?php endforeach; ?>
        </table>
    </div>
</div>
