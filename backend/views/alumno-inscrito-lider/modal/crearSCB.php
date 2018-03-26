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

$this->title = 'Crear Asignaciones Grupo de Trabajo';

echo Dialog::widget([
    //'libName' => 'krajeeDialogCust', // optional if not set will default to `krajeeDialog`
    //'options' => ['draggable' => true, 'closable' => true], // custom options
]);

$js = '
$("#reemplazar-form").on(\'beforeSubmit\', function (e) {
    var $yiiform = $(this);
    $.ajax({
            type: $yiiform.attr(\'method\'),
            url: $yiiform.attr(\'action\'),
            data: $yiiform.serializeArray(),
        }
    )
        .done(function(data) {
            if(data.success) {
                // data is saved
                alert("Datos guardados exitosamente.");
                $("#modalSCB").modal(\'hide\');
                location.reload();
            } else if (data.validation) {
                // server validation failed
                $yiiform.yiiActiveForm(\'updateMessages\', data.validation, true); // renders validation messages at appropriate places
            } else if(data.error){
                alert(data.message);
            } else {
                // incorrect server response
                alert("Respuesta inesperada del servidor.");
            }
        })
        .fail(function () {
            // request failed
        })

    return false; // prevent default form submission
});
';
$this->registerJs($js);

?>

<div class="alumno-inscrito-lider-modificacion">
    <?php $form = ActiveForm::begin(['id' => "reemplazar-form"]); ?>
        <div class="form-group">
            <div class="row">
                <div class="col-sm-12 col-md-6 col-md-offset-3">
                    <?php //$form->field($modeloAsignacion, "scb_id_scb")->textInput(['maxlength' => true]) ?>
                    <?= $form->field($modeloAsignacion, 'scb_id_scb')->widget(Select2::classname(), [
                        'data' => $modeloAsignacion->scbLista,
                        'language' => 'es',
                        'theme' => 'default',
                        'options' => [/*'id' => 'sciField', 'class' => 'form-control',*/ 'placeholder' => 'Seleccione Socio ...'],
                        'pluginOptions' => [
                            'allowClear' => false
                        ],
                    ])->label('Socio C. Beneficiario'); ?>

                    <?= Html::submitButton('Guardar' , ['class' =>  'btn btn-success btn-md']) ?>
                </div>
            </div><!-- .row -->

        </div>
    <?php ActiveForm::end(); ?>
</div>
