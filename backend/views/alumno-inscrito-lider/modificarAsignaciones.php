<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use kartik\depdrop\DepDrop;
use yii\helpers\Url;
use kartik\switchinput\SwitchInput;

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

$this->registerJsFile('@web/js/implementacion/funciones.js', ['depends' => [\yii\web\JqueryAsset::className()] ] );
$this->registerJsFile('@web/js/implementacion/asignar-lider/asignar-lider.js', ['depends' => [\yii\web\JqueryAsset::className()] ] );

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
    <div id="form-prueba">
        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($modeloAsignaciones, 'scb_id_scb')->textInput() ?>

        <?php //$form->field($modeloAsignaciones, 'modificado_en')->textInput() ?>

        <?= $form->field($modeloAsignaciones, 'observacion')->textInput() ?>
        <?= $form->field($modeloAsignaciones, 'cambio')->textInput() ?>

        <?= $form->field($modeloAsignaciones, 'id_reemplazo_scb')->textInput() ?>

        <?php if (!Yii::$app->request->isAjax){ ?>
            <div class="form-group">
                <?= Html::submitButton($modeloAsignaciones->isNewRecord ? 'Crear' : 'Modificar', ['class' => $modeloAsignaciones->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            </div>
        <?php } ?>

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
                    echo "</tr>";
                }// FIN FOR EACH
                echo "</table>";
            }
        ?>
        <br>
    </div>
</div>
