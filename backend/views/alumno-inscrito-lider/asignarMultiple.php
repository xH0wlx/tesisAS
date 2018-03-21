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

$request = Yii::$app->request;

$this->title = 'Asignar Líder y Socio Beneficiario';
$this->params['breadcrumbs'][] = ['label' => 'Panel de Implementación',
    'url' => ['/implementacion/panel-implementacion', 'idImplementacion'=> $request->get('idImplementacion')]];
$this->params['breadcrumbs'][] = $this->title;

$this->registerJsFile('@web/js/implementacion/funciones.js', ['depends' => [\yii\web\JqueryAsset::className()] ] );
$this->registerJsFile('@web/js/implementacion/asignar-lider/asignar-lider.js', ['depends' => [\yii\web\JqueryAsset::className()] ] );

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
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th style="width: 15%">Rut Alumno</th>
                                    <th style="width: 40%">Nombre Alumno</th>
                                    <th style="width: 10%">Líder</th>
                                    <th style="width: 35%">Socio Comunitario Beneficiario</th>
                                </tr>
                            <?php
                                $alumnosGrupo = $grupoTrabajo->alumnoInscritoSeccionIdAlumnoInscritoSeccions;
                                foreach ($alumnosGrupo as $index2 => $alumnoGrupo) {
                            ?>
                                    <tr>
                                        <td>
                                            <?php
                                            $rut = $alumnoGrupo->alumnoRutAlumno->rut_alumno;
                                            $rut = substr_replace($rut, '-', strlen($rut) - 1, 0);
                                            $splittedRut = explode('-', $rut);
                                            $number = number_format($splittedRut[0], 0, ',', '.');
                                            $verifier = strtoupper($splittedRut[1]);
                                            echo $number . '-' . $verifier;
                                            ?>
                                        </td>
                                        <td><?=$alumnoGrupo->alumnoRutAlumno->nombre?></td>
<!--                                        <td><?/*=$form->field($modelosLideres[$index], "[$index]alumno_inscrito_seccion_id_seccion_alumno")->radio(['uncheck'=> null,'label'=>'','value' => $alumnoGrupo->id_alumno_inscrito_seccion])*/?></td>
-->
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
                                            if (!$modelosSCBS[$index]->isNewRecord) {
                                                $inicializar = true;
                                                //ID SEDE DE LA ASIGNATURA QUE VIENE PARA UPDATEAR
                                                $valueSci = $modelosSCBS[$index]->scbIdScb->sciIdSci->id_sci;

                                                //ID CARRERA DE LA ASIGNATURA QUE VIENE PARA UPDATEAR
                                                echo Html::hiddenInput('input-id_scb'.$index, $modelosSCBS[$index]->scb_id_scb, ['id' => 'input-id_scb'.$index]);
                                            }else{
                                                $inicializar = false;
                                                $valueSci = '';
                                            }

                                            echo "<td rowspan='".count($alumnosGrupo)."'>";
                                            echo Select2::widget([
                                                'name' => 'sci'.$index,
                                                'data' =>$dataSelect2,
                                                'value' => $valueSci,
                                                'language' => 'es',
                                                'theme' => 'default',
                                                'options' => ['id' => 'inputSci'.$index, 'placeholder' => 'Seleccione Socio C. Institucional ...'],
                                                'pluginOptions' => [
                                                    'allowClear' => true
                                                ],
                                            ]);

                                            echo $form->field($modelosSCBS[$index], '['.$index.']scb_id_scb')->widget(DepDrop::classname(), [
                                                'type'=>DepDrop::TYPE_SELECT2,
                                                //'data' => $dataCarrera,
                                                'options' => ['id'=> Html::getInputId($modelosSCBS[$index], '['.$index.']scb_id_scb')],
                                                'select2Options'=>[
                                                    'language' => 'es',
                                                    'theme' => 'default',
                                                    'pluginOptions'=>['allowClear'=>true],

                                                ],
                                                'pluginOptions'=>[
                                                    'depends'=>['inputSci'.$index],
                                                    'placeholder'=>'Seleccione Soci@ C. Beneficiari@ ...',
                                                    'url'=>Url::to(['/alumno-inscrito-lider/subsociosb']),
                                                    'initialize' => $inicializar,
                                                    'params'=>['input-id_scb'.$index]
                                                ],
                                            ])->label("Soci@ C. Beneficiari@");

                                            echo $form->field($modelosSCBS[$index], '['.$index.']scb_id_scb')->hiddenInput()->label(false);

                                            if (!$modelosSCBS[$index]->isNewRecord) {
                                              /*  echo $form->field($modelosSCBS[$index], '['.$index.']observacion')->textInput()
                                                ->hint('Este grupo ya tenía un socio beneficiario asignado,<br> si lo modifica, debe agregar una observación')->label('Observación del Cambio');
                                         */   }


                                        echo $form->field($modelosSCBS[$index], '['.$index.']scb_id_scb')->textInput();

                                        ?>

                                        <div class="form-group input_fields_wrap">
                                            <?= Html::activeLabel($modelosSCBS[$index], 'scb_id_scb'); ?>
                                            <div class="input-group mb-3">
                                                <input name="GrupoTrabajoHasScb[<?= $index ?>][scb_id_scb]" type="text" class="form-control profesion_o_grado" value="<?= $modelosSCBS[$index]->scb_id_scb ?>">
                                                <?= Html::error($modelosSCBS[$index], 'scb_id_scb'); ?>
                                                <div class="input-group-append">
                                                    <button class="btn btn-primary add_field_button"> + </button>
                                                </div>
                                            </div>
<!--                                            <?php
/*                                            if(count($profesion) > 1 ){
                                                $largo = count($profesion);
                                                for($i=1; $i < $largo; $i++){
                                                    */?>

                                                    <div class="input-group mb-3">
                                                        <input name="ge_funcionario[profesion][]" type="text" class="form-control profesion_o_grado" value="<?php /*echo esc_attr($profesion[$i]); */?>">
                                                        <div class="input-group-append">
                                                            <button class="btn btn-danger remove_field" type="button"> -&nbsp;  </button>
                                                        </div>
                                                    </div>

                                                    --><?php
/*                                                }
                                            }

                                            */?>
                                            <?= Html::a('<i class="fa fa-chevron-circle-left"></i> Modificar', ['/alumno-inscrito-lider/modificar-asignaciones-grupo', 'idGrupoTrabajo' => $grupoTrabajo->id_grupo_trabajo],
                                                ['class' =>'btn btn-danger']) ?>

                                            <?php
                                            echo "</td>";
                                            $bandera=false;
                                            }//FIN IF BANDERA
                                            ?>
                                        </div>

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
        'value'=> Url::to(['/implementacion/panel-implementacion', 'idImplementacion' => Yii::$app->request->get('idImplementacion')]),
        'class' =>'btn btn-danger']) ?>

    <?= Html::submitButton('<i class="fa fa-floppy-o" aria-hidden="true"></i> Guardar y Volver', ['name'=>'guardarYSalir', 'value'=>'true','class' =>'btn btn-primary']) ?>
    <?php ActiveForm::end(); ?>

</div>
