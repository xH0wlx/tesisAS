<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use wbraganca\dynamicform\DynamicFormWidget;
use kartik\select2\Select2;
use kartik\growl\Growl;
use kartik\growl\GrowlAsset;
use kartik\base\AnimateAsset;
use backend\models\Sci;
use yii\helpers\ArrayHelper;
use kartik\dialog\Dialog;


GrowlAsset::register($this);
AnimateAsset::register($this);
/* @var $this yii\web\View */
/* @var $model backend\models\scb */
/* @var $form yii\widgets\ActiveForm */

$js = '
$(".dynamicform_wrapper").on("beforeDelete", function(e, item) {
    if (! confirm("Esta seguro que quiere eliminar este item?")) {
        return false;
    }
    return true;
});

$(".dynamicform_wrapper").on("limitReached", function(e, item) {
    alert("Limit reached");
});

$(".dynamicform_wrapper").on("beforeInsertPropio", function(e, item) {
    var $form = $("#dynamic-form"), 
    data = $form.data("yiiActiveForm");
    $.each(data.attributes, function() {
        this.status = 3;
    });
    $form.yiiActiveForm("validate");
    console.log($($form).find("div.has-error").length);
    if ($($form).find("div.has-error").length !== 0) {
        $.notify({message: "No puede agregar otro Contacto si no ha completado todos los datos requeridos"},{type: "danger"});
        return false;
    }else{
        return true;
    }
});

jQuery(".dynamicform_wrapper").on("afterInsert", function(e, item) {
    jQuery(".dynamicform_wrapper .panel-title").each(function(i) {
        jQuery(this).html("Contacto N°" + (i + 1))
    });
});

jQuery(".dynamicform_wrapper").on("afterDelete", function(e) {
    jQuery(".dynamicform_wrapper .panel-title").each(function(i) {
        jQuery(this).html("Contacto N°" + (i + 1))
    });
});

////////////////////////////////////////////////////////////////////////
$(\'#botonSiguienteScb\').on("click", function(){
   var $form = $("#dynamic-form"), 
    data = $form.data("yiiActiveForm");

    $.each(data.attributes, function() {
        if(this.input.includes("#scb")){
                this.status = 3;
        }
    });
    $form.yiiActiveForm("validate");
    if ($("#scb").find("div.has-error").length !== 0) {
            krajeeDialog.alert(\'Debe completar todos los datos requeridos (*) del Socio Comunitario Beneficiario para continuar.\');
    }else{
        $(\'#tabsForm a[href="#contactoScb"]\').tab(\'show\');
    }
});

$(\'#botonGuardarContacto\').on(\'click\',function(e){
    e.preventDefault();
    var $form = $("#dynamic-form"), 
    data = $form.data("yiiActiveForm");
    
    $.each(data.attributes, function() {
        if(this.input.includes("#contactoscb")){
                this.status = 3;
        }
    });
    $form.yiiActiveForm("validate");

    if($("#contactoScb").find("div.has-error").length !== 0){
         krajeeDialog.alert(\'Debe completar todos los datos requeridos (*) del/los Contacto/s para Guardar.\');
    }else{
        krajeeDialog.confirm("Se guardarán los datos del Socio Comunitario Beneficiario y Contacto.", function (result) {
            if (result) { // ok button was pressed
                 $("#dynamic-form").submit();
            } else { // confirmation was cancelled
                // execute your code for cancellation
            }
        });
    }
    
});

$(\'a[data-toggle="tab"]\').on(\'show.bs.tab\', function (e) {
  var target = $(e.target).attr("href"); // activated tab
  var prevTarget = $(e.relatedTarget).attr("href");
 
    var $form = $("#dynamic-form"), 
    data = $form.data("yiiActiveForm");
    
 
  switch(prevTarget){
    case "#scb":
        $.each(data.attributes, function() {
            if(this.input.includes("#scb")){
                    this.status = 3;
            }
        });
        $form.yiiActiveForm("validate");
        if ($("#scb").find("div.has-error").length !== 0) {
            krajeeDialog.alert(\'Debe completar todos los datos requeridos (*) del Socio Comunitario Beneficiario para continuar.\');
            e.preventDefault();
        }
        break;
    case "#contactoScb":
        break;
  }
});


';

$this->registerJs($js);

echo Dialog::widget([
    //'libName' => 'krajeeDialogCust', // optional if not set will default to `krajeeDialog`
    //'options' => ['draggable' => true, 'closable' => true], // custom options
]);
?>

<div class="scb-form">

    <?php $form = ActiveForm::begin(['id' => 'dynamic-form']); ?>

    <div class="nav-tabs-custom">

        <!-- Nav tabs -->
        <ul  id="tabsForm" class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active">
                <a href="#scb" aria-controls="scb" role="tab" data-toggle="tab"><i class="glyphicon glyphicon-user"></i> Datos SCB</a>
            </li>
            <li role="presentation">
                <a href="#contactoScb" aria-controls="contactoScb" role="tab" data-toggle="tab"><i class="glyphicon glyphicon-phone-alt"></i> Contacto SCB</a>
            </li>
        </ul>

        <div class="tab-content">
            <div role="tabpanel" class="tab-pane fade in active" id="scb">
                <div class="panel panel-primary">
                    <div class="panel-heading"><i class="glyphicon glyphicon-pencil"></i> Datos SCB</div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-12">
                                <?= $form->field($model, 'sci_id_sci')->widget(Select2::classname(), [
                                    'data' => $model->sciLista,
                                    'language' => 'es',
                                    'theme' => 'default',
                                    'options' => [/*'id' => 'sciField', 'class' => 'form-control',*/ 'placeholder' => 'Seleccione Comuna ...'],
                                    'pluginOptions' => [
                                        'allowClear' => true
                                    ],
                                ])->label('Socio C. Institucional'); ?>
                            </div>
                            <div class="col-sm-6">
                                <?= $form->field($model, 'nombre_negocio')->textInput(['maxlength' => true]) ?>
                            </div>
                            <div class="col-sm-6">
                                <?= $form->field($model, 'actividad_rubro_giro')->textInput(['maxlength' => true]) ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4">
                                <?= $form->field($model, 'numero_trabajadores')->textInput() ?>
                            </div>
                            <div class="col-sm-4">
                                <?= $form->field($model, 'tiempo_en_la_actividad')->textInput(['maxlength' => true]) ?>
                            </div>
                            <div class="col-sm-4">
                                <?= $form->field($model, 'direccion_comercial')->textInput(['maxlength' => true]) ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4">
                                <?= $form->field($model, 'productos_yo_servicios')->textArea(['maxlength' => true]) ?>
                            </div>
                            <div class="col-sm-4">
                                <?= $form->field($model, 'descripcion_clientes')->textArea(['maxlength' => true]) ?>
                            </div>
                            <div class="col-sm-4">
                                <?= $form->field($model, 'descripcion_proveedores')->textArea(['maxlength' => true]) ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <?= $form->field($model, 'patente')->dropDownList(
                                    [0 => 'No', 1 => 'Sí'],           // Flat array ('id'=>'label')
                                    ['prompt'=>'¿Tiene Patente?']    // options
                                ); ?>
                            </div>
                            <div class="col-sm-6">
                                <?= $form->field($model, 'contabilidad')->dropDownList(
                                    [0 => 'No', 1 => 'Sí'],           // Flat array ('id'=>'label')
                                    ['prompt'=>'¿Tiene Contabilidad?']    // options
                                ); ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <?= $form->field($model, 'sitio_web')->textInput(['maxlength' => true]) ?>
                            </div>
                            <div class="col-sm-6">
                                <?= $form->field($model, 'red_social')->textInput(['maxlength' => true]) ?>
                            </div>
                        </div>
                        <?= Html::Button('Siguiente <i class="fa fa-chevron-circle-right"></i>', ['id'=> 'botonSiguienteScb','class' => 'btn btn-primary pull-right btn-lg']) ?>

                    </div>
                </div>
            </div>
            <div role="tabpanel" class="tab-pane fade" id="contactoScb">
                <div class="rowX">
                    <?php DynamicFormWidget::begin([
                        'widgetContainer' => 'dynamicform_wrapper', // required: only alphanumeric characters plus "_" [A-Za-z0-9_]
                        'widgetBody' => '.container-items', // required: css class selector
                        'widgetItem' => '.item', // required: css class
                        //'limit' => 4, // the maximum times, an element can be cloned (default 999)
                        'min' => 1, // 0 or 1 (default 1)
                        'insertButton' => '.add-item', // css class
                        'deleteButton' => '.remove-item', // css class
                        'model' => $modelsContactoScb[0],
                        'formId' => 'dynamic-form',
                        'formFields' => [
                            'id_contacto_scb',
                            'rut_beneficiario',
                            'nombre_completo',
                            'direccion',
                            'telefono_fijo',
                            'telefono_celular',
                            'email',
                        ],
                    ]); ?>
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <i class="glyphicon glyphicon-pencil"></i> Agregar Contacto/s
                            <button type="button" class="pull-right add-item btn btn-success btn-md"><i class="fa fa-plus"></i> Agregar Contacto</button>
                            <div class="clearfix"></div>
                        </div>
                        <div class="panel-body">
                            <div class="container-items"><!-- widgetContainer -->
                                <?php foreach ($modelsContactoScb as $i => $modelContactoScb): ?>
                                    <div class="item panel panel-default"><!-- widgetBody -->
                                        <div class="panel-heading">
                                            <h3 class="panel-title pull-left">Contacto N°<?= ($i + 1) ?></h3>
                                            <div class="pull-right">
                                                <button type="button" class="add-item btn btn-success btn-xs"><i class="glyphicon glyphicon-plus"></i></button>
                                                <button type="button" class="remove-item btn btn-danger btn-xs"><i class="glyphicon glyphicon-minus"></i></button>
                                            </div>
                                            <div class="clearfix"></div>
                                        </div>
                                        <div class="panel-body">
                                            <?php
                                            // necessary for update action.
                                            if (! $modelContactoScb->isNewRecord) {
                                                echo Html::activeHiddenInput($modelContactoScb, "[{$i}]id_contacto_scb");
                                            }
                                            ?>
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <?= $form->field($modelContactoScb, "[{$i}]rut_beneficiario")->textInput(['data-rut'=>true,'maxlength' => true]) ?>
                                                </div>
                                                <div class="col-sm-6">
                                                    <?= $form->field($modelContactoScb, "[{$i}]nombre_completo")->textInput(['maxlength' => true]) ?>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <?= $form->field($modelContactoScb, "[{$i}]telefono_fijo")->textInput(['maxlength' => true]) ?>
                                                </div>
                                                <div class="col-sm-6">
                                                    <?= $form->field($modelContactoScb, "[{$i}]telefono_celular")->textInput(['maxlength' => true]) ?>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <?= $form->field($modelContactoScb, "[{$i}]email")->textInput(['maxlength' => true]) ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                                <?php DynamicFormWidget::end(); ?>
                                <div class="pull-right">
                                    <?= Html::submitButton($model->isNewRecord ? '<i class="fa fa-floppy-o" aria-hidden="true"></i> Guardar' : 'Modificar', ['id'=> 'botonGuardarContacto','class' => $model->isNewRecord ? 'btn btn-success btn-lg' : 'btn btn-primary btn-lg']) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div role="tabpanel" class="tab-pane fade" id="settings">...</div>

        </div><!-- TAB CONTENT -->
    </div>

    <?php ActiveForm::end(); ?>
    
</div>
