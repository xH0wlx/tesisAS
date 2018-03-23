<?php
use yii\helpers\Html;
use yii\bootstrap\modal;
use yii\widgets\ActiveForm;
use wbraganca\dynamicform\DynamicFormWidget;
use kartik\select2\Select2;
use kartik\depdrop\DepDrop;
use yii\helpers\Url;
use kartik\switchinput\SwitchInput;

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

$this->title = 'Eliminar Asignaciones Grupo de Trabajo';

echo Dialog::widget([
    //'libName' => 'krajeeDialogCust', // optional if not set will default to `krajeeDialog`
    //'options' => ['draggable' => true, 'closable' => true], // custom options
]);

?>

<div class="alumno-inscrito-lider-historial">
    <div id="asignaciones-previas-box">

        <?php
        if($asignacionesActivas != null){
        ?>
            <table class="table table-striped table-bordered table-hover">
                <tr>
                    <th>N°</th>
                    <th>Fecha</th>
                    <th>Socio Comunitario Beneficiario</th>
                    <th>Observación</th>
                </tr>
        <?php
            foreach ($asignacionesActivas as $index => $asignacionActiva){
                echo "<tr>";
                echo "<td><span class=\"badge alert-". (($asignacionActiva->cambio == 0)?"info":"default") ."\">". ($index+1) ."</span></td>";
                echo "<td><span class=\"label label-success\">". Yii::$app->formatter->asDate($asignacionActiva->creado_en, 'd/M/Y') ."</span></td>";
                if($asignacionActiva->id_reemplazo_scb != null){
                    ?>
                    <td>
                        <span class="label label-primary"><?= $asignacionActiva->scbIdScb->nombre_negocio ?></span>
                        <span class="badge">reemplaza a</span>
                        <span class="label label-danger"><?= $asignacionActiva->reemplazado->nombre_negocio ?></span>
                    </td>
                    <td><span class="label label-default"><?= $asignacionActiva->observacion ?></span></td>
                    <?php
                }else{
                    ?>
                    <td><span class="label label-<?= ($asignacionActiva->observacion == "Eliminado")? "danger":"primary" ?>">
                                        <?= $asignacionActiva->scbIdScb->nombre_negocio ?>
                                    </span>
                    </td>
                    <td><span class="label label-default"><?= $asignacionActiva->observacion ?></span></td>
                    <?php
                }
                ?>
                <?php
                echo "</tr>";
            }// FIN FOR EACH
            echo "</table>";
        }else{
            echo "<h2>Historial vacío</h2>";
        }
        ?>
        <br>
    </div>
</div>
