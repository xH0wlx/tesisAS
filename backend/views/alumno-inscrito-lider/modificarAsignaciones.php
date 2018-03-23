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

$request = Yii::$app->request;

$this->title = 'Modificar Asignaciones Grupo de Trabajo';
//array_push ( $this->params['breadcrumbs'][], $this->title );

echo Dialog::widget([
    //'libName' => 'krajeeDialogCust', // optional if not set will default to `krajeeDialog`
    //'options' => ['draggable' => true, 'closable' => true], // custom options
]);

Modal::begin([
    'header' => 'SCB',
    'id' => 'modalSCB',
    'size' => 'modal-lg',
]);
echo "<div id='modalContentSCB'></div>";
Modal::end();

$this->registerJsFile('@web/js/implementacion/funciones.js', ['depends' => [\yii\web\JqueryAsset::className()] ] );
$this->registerJsFile('@web/js/implementacion/asignar-lider/asignar-lider.js', ['depends' => [\yii\web\JqueryAsset::className()] ] );

$request = Yii::$app->request;
?>

<div class="alumno-inscrito-lider-modificacion">
    <div class="panel box box-primary">
        <div class="box-header with-border">
            <h4 class="box-title">Grupo N°<?= $grupoTrabajo->numero_grupo_trabajo ?></h4>
            <table class="table table-bordered">
                <tbody>
                <tr>
                    <th style="width: 15%">Rut Alumno</th>
                    <th style="width: 40%">Nombre Alumno</th>
                    <th style="width: 10%">Líder</th>
                    <th style="width: 35%">Socio Comunitario Beneficiario</th>
                </tr>
                <?php
                foreach ($alumnosGrupo as $index => $alumnoGrupo) {
                    ?>
                    <tr>
                        <td>
                            <?= Yii::$app->formatter->asRut($alumnoGrupo->alumnoRutAlumno->rut_alumno) ?>
                        </td>
                        <td>
                            <?= $alumnoGrupo->alumnoRutAlumno->nombre ?>
                        </td>
                    </tr>
                    <?php
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>

    <a class="modalButton btn btn-primary" href="<?=Url::to(['alumno-inscrito-lider/crear-scb',
        'idGrupoTrabajo'=>$request->get('idGrupoTrabajo')]); ?>">Asignar Socio</a>
    <a class="modalButton btn btn-primary" href="<?=Url::to(['alumno-inscrito-lider/ver-historial-scb',
        'idGrupoTrabajo'=>$request->get('idGrupoTrabajo')]); ?>">Ver Historial</a>

    <div id="formulario-asignaciones">
        <?php $form = ActiveForm::begin(); ?>
        <?php foreach ($arrayModelosAsignaciones as $i => $arrayModeloAsignaciones): ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title pull-left"></h3>
                    <div class="clearfix"></div>
                </div>
                <div class="panel-body">
                    <?php
                    // necessary for update action.
                    if (! $arrayModeloAsignaciones->isNewRecord) {
                        echo Html::activeHiddenInput($arrayModeloAsignaciones, "[{$i}]id_grupo_trabajo_has_scb");
                    }
                    ?>
                    <div class="row">
                        <div class="col-sm-6 col-md-3">
                            <?= $form->field($arrayModeloAsignaciones, "[{$i}]scb_id_scb")->textInput(['maxlength' => true]) ?>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <?= $form->field($arrayModeloAsignaciones, "[{$i}]observacion")->textInput(['maxlength' => true]) ?>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <?= $form->field($arrayModeloAsignaciones, "[{$i}]cambio")->textInput(['maxlength' => true]) ?>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <?= $form->field($arrayModeloAsignaciones, "[{$i}]id_reemplazo_scb")->textInput(['maxlength' => true]) ?>
                        </div>
                    </div><!-- .row -->
                </div>
            </div>
        <?php endforeach; ?>
        <?php ActiveForm::end(); ?>
    </div>

    <div id="asignaciones-previas-box">

        <?php
            if($asignacionesActivas != null){
                echo "<table>";
                foreach ($asignacionesActivas as $index => $asignacionActiva){
                    echo "<tr>";
                    echo "<td><td><span class=\"badge alert-". (($asignacionActiva->cambio == 0)?"info":"default") ."\">". ($index+1) ."</span></td></td>";
                    echo "<td><span class=\"label label-success\">". Yii::$app->formatter->asDate($asignacionActiva->creado_en, 'd/M/Y') ."</span></td>";
                    if($asignacionActiva->id_reemplazo_scb != null){
                        ?>
                                <td><span class="label label-primary"><?= $asignacionActiva->scb_id_scb ?></span></td>
                                <td><span class="badge">reemplaza a</span></td>
                                <td><span class="label label-danger"><?= $asignacionActiva->reemplazado->nombre_negocio ?></span></td>
                                <td><span class="label label-default">Motivo: <?= $asignacionActiva->observacion ?></span></td>
                        <?php
                    }else{
                        ?>
                                <td><span class="label label-<?= ($asignacionActiva->observacion == "Eliminado")? "danger":"primary" ?>">
                                        <?= $asignacionActiva->scb_id_scb ?>
                                    </span>
                                </td>
                                <td><span class="label label-default"><?= $asignacionActiva->observacion ?></span></td>
                        <?php
                    }
                    ?>
                    <td>
                        <?php
                            if($asignacionActiva->cambio == 0) {
                        ?>
                        <a class="modalButton btn btn-primary" href="<?=Url::to(['alumno-inscrito-lider/reemplazar-scb',
                            'idAsignacion'=>$asignacionActiva->id_grupo_trabajo_has_scb]); ?>">Reemplazar Socio</a>
                        <?php
                            }
                        ?>
                        <?php
                            if($asignacionActiva->cambio == 0) {
                        ?>
                                <a class="modalButton btn btn-primary"
                                   href="<?= Url::to(['alumno-inscrito-lider/eliminar-scb',
                                       'idAsignacion' => $asignacionActiva->id_grupo_trabajo_has_scb]); ?>">Eliminar
                                    Socio</a>
                        <?php
                            }
                        ?>
                    </td>
                    <?php
                    echo "</tr>";
                }// FIN FOR EACH
                echo "</table>";
            }
        ?>
        <br>
    </div>
</div>
