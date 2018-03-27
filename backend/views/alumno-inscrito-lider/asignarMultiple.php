<?php
use yii\helpers\Html;
use yii\bootstrap\Modal;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use kartik\depdrop\DepDrop;
use yii\helpers\Url;
use kartik\switchinput\SwitchInput;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model backend\models\alumnoInscritoLider */
/* @var $form yii\widgets\ActiveForm */

$request = Yii::$app->request;

$this->title = 'Asignar Líder y Socio Beneficiario';
$this->params['breadcrumbs'][] = ['label' => 'Panel de Implementación',
    'url' => ['/implementacion/panel-implementacion', 'idImplementacion'=> $request->get('idImplementacion')]];
$this->params['breadcrumbs'][] = $this->title;

$this->registerJsFile('@web/js/implementacion/funciones.js', ['depends' => [\yii\web\JqueryAsset::className()] ] );
$this->registerJsFile('@web/js/implementacion/asignar-lider/asignar-lider.js', ['depends' => [\yii\web\JqueryAsset::className()]] );

Modal::begin([
    'header' => '',
    'id' => 'modalSCBPrincipal',
    'size' => 'modal-lg',
]);
echo "<div id='modalContentSCBPrincipal'></div>";
Modal::end();
?>

<div class="alumno-inscrito-lider-form">
    <?php $form = ActiveForm::begin(); ?>
    <div class="box box-solid">
        <div class="box-header with-border">
            <h3 class="box-title">Datos de los Grupos</h3>
            <?php
            foreach ($gruposTrabajo as $index => $grupoTrabajo) {
                $bandera=true;
                ?>
                <div class="panel box box-primary">
                    <div class="box-header with-border">
                        <h4 class="box-title">Grupo N°<?= $grupoTrabajo->numero_grupo_trabajo ?></h4>
                        <table class="table table-bordered table-striped table-hover">
                            <tbody>
                                <tr>
                                    <th style="width: 15%">Rut Alumno</th>
                                    <th style="width: 40%">Nombre Alumno</th>
                                    <th style="width: 10%">Líder</th>
                                    <th style="width: 35%">Socios Comunitarios Beneficiarios</th>
                                </tr>
                            <?php
                                $alumnosGrupo = $grupoTrabajo->alumnoInscritoSeccionIdAlumnoInscritoSeccions;
                                foreach ($alumnosGrupo as $index2 => $alumnoGrupo) {
                            ?>
                                    <tr>
                                        <td>
                                            <?= Yii::$app->formatter->asRut($alumnoGrupo->alumnoRutAlumno->rut_alumno) ?>
                                        </td>
                                        <td><?=$alumnoGrupo->alumnoRutAlumno->nombre?></td>

                                        <td><?=$form->field($modelosLideres[$index], "[$index]id_alumno_lider")->widget(SwitchInput::classname(),
                                                [
                                                        'name' => 'radio-'.$index.'-'.$index2,
                                                        //'inlineLabel' => false,
                                                        'type' => SwitchInput::RADIO,
                                                        'items'=>[
                                                            [
                                                                    'label'=>false,
                                                                    'value'=> $alumnoGrupo->id_alumno_inscrito_seccion,
                                                                    'options' => [],
                                                                    'labelOptions' => [],
                                                            ],
                                                        ],
                                                        'containerOptions'=>[],
                                                        'options' => [
                                                                'uncheck'=> null, 'label'=>'',
                                                        ],
                                                        'pluginOptions' => [
                                                            'onText' => '<i class="glyphicon glyphicon-ok"></i>',
                                                            'offText' => '<i class="glyphicon glyphicon-remove"></i>',
                                                        ],
                                                ])->label(false);?>
                                        </td>

                                        <?php if($bandera){

                                            echo "<td rowspan='".count($alumnosGrupo)."'>";


                                            echo "<div id='vista-parcial-grupo-".$grupoTrabajo->id_grupo_trabajo."'>";
                                            echo $this->render('_modificarAsignaciones', [
                                                'grupoTrabajo' => $grupoTrabajo,
                                            ]);
                                            echo "</div>";

                                            echo "</td>";
                                            $bandera=false;
                                            }//Sólo imprime 1 vez los socios


                                            ?>


                                    </tr>
                                <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
             <?php
            }
            ?>
        </div>
    </div>

    <?= Html::button('<i class="fa fa-chevron-circle-left"></i> Volver sin guardar', ['id' => 'botonVolverSinGuardar',
        'value'=> Url::to(['/implementacion/panel-implementacion', 'idImplementacion' => $id_implementacion_activa ]),
        'class' =>'btn btn-danger']) ?>

    <?= Html::submitButton('<i class="fa fa-floppy-o" aria-hidden="true"></i> Guardar Líderes y Volver', ['name'=>'guardarYSalir', 'value'=>'true','class' =>'btn btn-primary']) ?>
    <?php ActiveForm::end(); ?>

</div>
