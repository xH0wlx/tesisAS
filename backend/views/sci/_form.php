<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use wbraganca\dynamicform\DynamicFormWidget;
use kartik\select2\Select2;
use kartik\growl\Growl;
use kartik\growl\GrowlAsset;
use kartik\base\AnimateAsset;
use wbraganca\tagsinput\TagsinputWidget;

//use kartik\tabs\TabsX;
use kartik\dialog\Dialog;
GrowlAsset::register($this);
AnimateAsset::register($this);


/* @var $this yii\web\View */
/* @var $model backend\models\sci */
/* @var $form yii\widgets\ActiveForm */

$js = '
var citynames = new Bloodhound({
    datumTokenizer: Bloodhound.tokenizers.obj.whitespace(\'name\'),
    queryTokenizer: Bloodhound.tokenizers.whitespace,    
    remote: {
    wildcard: \'consulta\',
    url: \''.\Yii::$app->urlManager->createUrl(['match1/service-tag-input?query=']) .'consulta\',
    transform: function(response) {
        // Map the remote source JSON array to a JavaScript object array
        var arr = [];
        response.forEach(function(asignatura) {
            palabras = asignatura.nombre_asignatura.split(" ");
            palabras.forEach(function(palabra) {
                arr.push(palabra);
            });
        });
          return $.map(arr, function(asignatura) {
            return {
              //name: asignatura.nombre_asignatura
              name: asignatura
            };
          });
        }
    }
});
citynames.initialize();

var elt = $(\'#requerimiento-0-tagvalues\');
elt.tagsinput({
  typeaheadjs: [{
          minLength: 1,
          highlight: true,
    },{
        minlength: 1,
        limit: 6,
        name: \'citynames\',
        displayKey: \'name\',
        valueKey: \'name\',
        source: citynames.ttAdapter()
    }],
    freeInput: true
});


//EVENTS
$(".dynamicform_wrapper").on("beforeDelete", function(e, item) {
    if (! confirm("Esta seguro que quiere eliminar este requerimiento?")) {
        return false;
    }
    return true;
});
$(".dynamicform_wrapper2").on("beforeDelete", function(e, item) {
    if (! confirm("Esta seguro que quiere eliminar este contacto?")) {
        return false;
    }
    return true;
});

$(".dynamicform_wrapper").on("limitReached", function(e, item) {
    alert("Limite máximo alcanzado");
});
//

$(".dynamicform_wrapper").on("beforeInsertPropio", function(e, item) {
    var $form = $("#dynamic-form"), 
    data = $form.data("yiiActiveForm");
    $.each(data.attributes, function() {
        this.status = 3;
    });
    $form.yiiActiveForm("validate");
    if ($($form).find("div.has-error").length !== 0) {
        $.notify({message: "No puede agregar otro Requerimiento si no ha completado todos los datos requeridos"},{type: "danger"});
        return false;
    }else{
        return true;
    }
});

$(".dynamicform_wrapper2").on("beforeInsertPropio", function(e, item) {
    var $form = $("#dynamic-form"), 
    data = $form.data("yiiActiveForm");
    $.each(data.attributes, function() {
        this.status = 3;
    });
    $form.yiiActiveForm("validate");
  
    if ($($form).find("div.has-error").length !== 0) {
        $.notify({message: "No puede agregar otro Contacto si no ha completado todos los datos requeridos"},{type: "danger"});
        return false;
    }else{
        return true;
    }
});

jQuery(".dynamicform_wrapper2").on("afterInsert", function(e, item) {
    jQuery(".dynamicform_wrapper2 .panel-title").each(function(i) {
        jQuery(this).html("Contacto N°" + (i + 1))
    });
});


jQuery(".dynamicform_wrapper").on("afterInsert", function(e, item) {
    jQuery(".dynamicform_wrapper .panel-title").each(function(i) {
        jQuery(this).html("Requerimiento N°" + (i + 1));
        $(\'input[id *= tagvalues]\').tagsinput({
          typeaheadjs: [{
                  minLength: 1,
                  highlight: true,
            },{
                minlength: 1,
                limit: 6,
                name: \'citynames\',
                displayKey: \'name\',
                valueKey: \'name\',
                source: citynames.ttAdapter()
            }],
            freeInput: true
        });
    });
    //document.getElementById("botonGuardarRequerimiento").style.display = "block";
});

jQuery(".dynamicform_wrapper").on("afterDelete", function(e) {
    jQuery(".dynamicform_wrapper .panel-title").each(function(i) {
        jQuery(this).html("Requerimiento N°" + (i + 1))
    });
    //if($(".dynamicform_wrapper .panel-title").length == 0){
    //    document.getElementById("botonGuardarRequerimiento").style.display = "none";
    //}
});


jQuery(".dynamicform_wrapper2").on("afterDelete", function(e) {
    jQuery(".dynamicform_wrapper2 .panel-title").each(function(i) {
        jQuery(this).html("Contacto N°" + (i + 1))
    });
});

$(\'#botonSiguienteSci\').on("click", function(){
   var $form = $("#dynamic-form"), 
    data = $form.data("yiiActiveForm");

    $.each(data.attributes, function() {
        if(this.input.includes("#sci") || this.input.includes("#contactoweb")){
                this.status = 3;
        }
    });
    $form.yiiActiveForm("validate");
    if ($("#sci").find("div.has-error").length !== 0) {
            krajeeDialog.alert(\'Debe completar todos los datos requeridos (*) del Socio Comunitario Institucional para continuar.\');
    }else{
        $(\'#tabsForm a[href="#contacto"]\').tab(\'show\');
    }
});

$(\'#botonSiguienteContacto\').on("click", function(){
   var $form = $("#dynamic-form"), 
    data = $form.data("yiiActiveForm");
    
    $.each(data.attributes, function() {
        if(this.input.includes("#contactosci")){
                this.status = 3;
        }
    });
    $form.yiiActiveForm("validate");
    if ($("#contacto").find("div.has-error").length !== 0) {
            krajeeDialog.alert(\'Debe completar todos los datos requeridos (*) del/los Contacto/s para continuar.\');
    }else{
        $(\'#tabsForm a[href="#requerimiento"]\').tab(\'show\');
    }
});

$(\'#botonGuardarContacto\').on(\'click\',function(e){
    e.preventDefault();
    var $form = $("#dynamic-form"), 
    data = $form.data("yiiActiveForm");
    
    $.each(data.attributes, function() {
        if(this.input.includes("#requerimiento")){
                this.status = 3;
        }
    });
    $form.yiiActiveForm("validate");
    if ($("#requerimiento").find("div.has-error").length !== 0) {
            krajeeDialog.alert(\'Debe completar todos los datos requeridos (*) del Requerimiento o Eliminarlo para Guardar.\');
            $(\'#tabsForm a[href="#requerimiento"]\').tab(\'show\');
    }else{
        if($("#contacto").find("div.has-error").length !== 0){
             krajeeDialog.alert(\'Debe completar todos los datos requeridos (*) del/los Contacto/s para Guardar.\');
        }else{
            if($(".dynamicform_wrapper .panel-title").length == 0){
                krajeeDialog.confirm("Se guardarán los datos del Socio Comunitario Institucional y Contacto. \\n También puede cancelar y agregar Requerimientos en la siguiente pestaña (opcional).", function (result) {
                    if (result) { // ok button was pressed
                         $("#dynamic-form").submit();
                    } else { // confirmation was cancelled
                        // execute your code for cancellation
                    }
                });
            }else{
                krajeeDialog.confirm("Se guardarán los datos del Socio Comunitario Institucional, Contacto y Requerimiento.", function (result) {
                    if (result) { // ok button was pressed
                         $("#dynamic-form").submit();
                    } else { // confirmation was cancelled
                        // execute your code for cancellation
                    }
                });
            }
        }
    }
    
    
});

$(\'#botonGuardarRequerimiento\').on(\'click\',function(e){
    e.preventDefault();
    var $form = $("#dynamic-form"), 
    data = $form.data("yiiActiveForm");
    
    $.each(data.attributes, function() {
        if(this.input.includes("#requerimiento")){
                this.status = 3;
        }
    });
    $form.yiiActiveForm("validate");
    if ($("#requerimiento").find("div.has-error").length !== 0) {
            krajeeDialog.alert(\'Debe completar todos los datos requeridos (*) del Requerimiento o Eliminarlo para guardar.\');
    }else{
        krajeeDialog.confirm("Se guardarán los datos del Socio Comunitario Institucional, Contacto y Requerimiento (Si lo hubiere).", function (result) {
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
    case "#sci":
        $.each(data.attributes, function() {
            if(this.input.includes("#sci") || this.input.includes("#contactoweb")){
                    this.status = 3;
            }
        });
        $form.yiiActiveForm("validate");
        if ($("#sci").find("div.has-error").length !== 0) {
            krajeeDialog.alert(\'Debe completar todos los datos requeridos (*) del Socio Comunitario Institucional para continuar.\');
            e.preventDefault();
        }else{
            if(target == "#requerimiento"){
                $.each(data.attributes, function() {
                if(this.input.includes("#contactosci")){
                        this.status = 3;
                }
                });
                $form.yiiActiveForm("validate");
                if ($("#contacto").find("div.has-error").length !== 0) {
                    krajeeDialog.alert(\'No puede pasar directamente a la pestaña Requerimiento sin completar la de Contacto.\');
                    e.preventDefault();
                }
            }//FIN IF REQUERIMIENTO 
        }
        break;
    case "#contacto":
        if(target == "#sci"){
        }else{
            $.each(data.attributes, function() {
                if(this.input.includes("#contactosci")){
                        this.status = 3;
                }
            });
            $form.yiiActiveForm("validate");
            if ($("#contacto").find("div.has-error").length !== 0) {
                krajeeDialog.alert(\'Debe completar todos los datos requeridos (*) del/los Contacto/s para continuar.\');
                 e.preventDefault();
            }
        }

        break;
    case "requerimiento":
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

<div class="sci-form">
    <!--<div class="row">
        <div class="col-sm-12">
            <?/*= Html::a('<i class="fa fa-arrow-circle-left"></i> Volver a Lista de Socios C. Institucionales', ['/sci/index'], ['class' => 'btn btn-info']) */?>
        </div>
    </div>-->

    <?php $form = ActiveForm::begin(['id' => 'dynamic-form','enableClientValidation' => true]); ?>


    <div class="nav-tabs-custom">

        <!-- Nav tabs -->
        <ul id="tabsForm" class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active">
                <a href="#sci" aria-controls="sci" role="tab" data-toggle="tab"><i class="glyphicon glyphicon-user"></i> Datos SCI</a>
            </li>
            <li role="presentation">
                <a href="#contacto" aria-controls="contacto" role="tab" data-toggle="tab"><i class="glyphicon glyphicon-phone-alt"></i> Contacto</a>
            </li>
            <li role="presentation">
                <a href="#requerimiento" aria-controls="requerimiento" role="tab" data-toggle="tab"><i class="glyphicon glyphicon-tasks"></i> Requerimiento</a>
            </li>
        </ul>

        <div class="tab-content">
            <div role="tabpanel" class="tab-pane fade in active" id="sci">
                <div class="panel panel-primary">
                    <div class="panel-heading"><i class="glyphicon glyphicon-pencil"></i> Datos SCI</div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <?= $form->field($model, 'nombre')->textInput(['maxlength' => true]) ?>
                            </div>
                            <div class="col-sm-6">
                                <?= $form->field($model, 'direccion')->textInput(['maxlength' => true]) ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <?= $form->field($model, 'comuna_comuna_id')->widget(Select2::classname(), [
                                    'data' => $model->comunaLista,
                                    'language' => 'es',
                                    'theme' => 'default',
                                    'options' => [/*'id' => 'sciField', 'class' => 'form-control',*/ 'placeholder' => 'Seleccione Comuna ...'],
                                    'pluginOptions' => [
                                        'allowClear' => true
                                    ],
                                ])->label('Comuna'); ?>
                            </div>
                            <div class="col-sm-6">
                                <?= $form->field($model, 'departamento_programa')->textInput(['maxlength' => true])->label("Departamento o Programa") ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <?= $form->field($model, 'observacion')->textArea(['maxlength' => true])->label('Observación') ?>
                            </div>
                            <div class="col-sm-6">
                                <?= $form->field($model, 'sede_id_sede')->widget(Select2::classname(), [
                                    'data' => $model->sedeLista,
                                    'language' => 'es',
                                    'theme' => 'default',
                                    'options' => [/*'id' => 'sciField', 'class' => 'form-control',*/ 'placeholder' => 'Seleccione Sede ...'],
                                    'pluginOptions' => [
                                        'allowClear' => true
                                    ],
                                ])->label('Sede (Universidad del Bío Bío)')->hint("La sede que trabajará con el Socio C. Institucional"); ?>
                            </div>
                        </div>

                        <?php DynamicFormWidget::begin([
                            'widgetContainer' => 'dynamicform_wrapper3', // required: only alphanumeric characters plus "_" [A-Za-z0-9_]
                            'widgetBody' => '.container-items3', // required: css class selector
                            'widgetItem' => '.item3', // required: css class
                            //'limit' => 4, // the maximum times, an element can be cloned (default 999)
                            'min' => 1, // 0 or 1 (default 1)
                            'insertButton' => '.add-item3', // css class
                            'deleteButton' => '.remove-item3', // css class
                            'model' => $modelsContactoWeb[0],
                            'formId' => 'dynamic-form',
                            'formFields' => [
                                'direccion_web',
                            ],
                        ]); ?>
                        <div class="container-items3"><!-- widgetContainer -->
                            <?php foreach ($modelsContactoWeb as $i => $modelContactoWeb): ?>
                                <div class="item3 panel panel-default"><!-- widgetBody -->
                                    <div class="panel-body">
                                        <?php
                                        // necessary for update action.
                                        if (! $modelContactoWeb->isNewRecord) {
                                            echo Html::activeHiddenInput($modelContactoWeb, "[{$i}]id_contacto_web");
                                        }
                                        ?>
                                        <div class="pull-right">
                                            <button type="button" class="add-item3 btn btn-success btn-xs"><i class="glyphicon glyphicon-plus"></i></button>
                                            <button type="button" class="remove-item3 btn btn-danger btn-xs"><i class="glyphicon glyphicon-minus"></i></button>
                                        </div>
                                        <div class="clearfix"></div>
                                        <?= $form->field($modelContactoWeb, "[{$i}]direccion_web")->textInput(['placeholder'=>'Ej: www.facebook.com/socio-inst','maxlength' => true])->hint("Puede agregar redes sociales, sitios web, etc. Uno por casilla. Utilice el botón + para agregar otra dirección web.") ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                            <?php DynamicFormWidget::end(); ?>
                        </div>

                        <div class="pull-right">
                            <?php
                                if(!$model->isNewRecord){
                                    echo Html::submitButton('<i class="fa fa-floppy-o"></i> Modificar', ['class' => 'btn btn-primary btn-lg']);
                                    echo "<span style=\"width:3em;\"> </span>";
                                }
                            ?>
                            <?= Html::Button('Siguiente <i class="fa fa-chevron-circle-right"></i>', ['id'=> 'botonSiguienteSci','class' => 'btn btn-primary btn-lg']) ?>
                        </div>
                    </div>
                </div>
            </div>
            <div role="tabpanel" class="tab-pane fade" id="contacto">
                <div class="rowX">
                    <?php DynamicFormWidget::begin([
                        'widgetContainer' => 'dynamicform_wrapper2', // required: only alphanumeric characters plus "_" [A-Za-z0-9_]
                        'widgetBody' => '.container-items2', // required: css class selector
                        'widgetItem' => '.item2', // required: css class
                        //'limit' => 4, // the maximum times, an element can be cloned (default 999)
                        'min' => 1, // 0 or 1 (default 1)
                        'insertButton' => '.add-item2', // css class
                        'deleteButton' => '.remove-item2', // css class
                        'model' => $modelsContactoSci[0],
                        'formId' => 'dynamic-form',
                        'formFields' => [
                            'nombres',
                            'apellidos',
                            'telefono',
                            'cargo',
                            'email',
                        ],
                    ]); ?>
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <i class="glyphicon glyphicon-pencil"></i> Datos Contacto/s
                            <button type="button" class="pull-right add-item2 btn btn-success btn-md"><i class="fa fa-plus"></i> Agregar Contacto</button>
                            <div class="clearfix"></div>
                        </div>
                        <div class="panel-body">
                            <div class="container-items2"><!-- widgetContainer -->
                                <?php foreach ($modelsContactoSci as $i => $modelContactoSci): ?>
                                    <div class="item2 panel panel-default"><!-- widgetBody -->
                                        <div class="panel-heading">
                                            <h3 class="panel-title pull-left">Contacto N°<?= ($i + 1) ?></h3>
                                            <div class="pull-right">
                                                <button type="button" class="add-item2 btn btn-success btn-xs"><i class="glyphicon glyphicon-plus"></i></button>
                                                <button type="button" class="remove-item2 btn btn-danger btn-xs"><i class="glyphicon glyphicon-minus"></i></button>
                                            </div>
                                            <div class="clearfix"></div>
                                        </div>
                                        <div class="panel-body">
                                            <?php
                                            // necessary for update action.
                                            if (! $modelContactoSci->isNewRecord) {
                                                echo Html::activeHiddenInput($modelContactoSci, "[{$i}]id_contacto_sci");
                                            }
                                            ?>
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <?= $form->field($modelContactoSci, "[{$i}]nombres")->textInput(['maxlength' => true]) ?>
                                                </div>
                                                <div class="col-sm-6">
                                                    <?= $form->field($modelContactoSci, "[{$i}]apellidos")->textInput(['maxlength' => true]) ?>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <?= $form->field($modelContactoSci, "[{$i}]telefono")->textInput(['maxlength' => true]) ?>
                                                </div>
                                                <div class="col-sm-6">
                                                    <?= $form->field($modelContactoSci, "[{$i}]cargo")->textInput(['maxlength' => true]) ?>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <?= $form->field($modelContactoSci, "[{$i}]email")->textInput(['maxlength' => true]) ?>
                                                </div>
                                                <div class="col-sm-6">
                                                    <?= $form->field($modelContactoSci, "[{$i}]observacion")->textInput(['maxlength' => true]) ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php DynamicFormWidget::end(); ?>
                                <div class="pull-right">
                                    <?= Html::submitButton($model->isNewRecord ? '<i class="fa fa-floppy-o" aria-hidden="true"></i> Guardar' : '<i class="fa fa-floppy-o"></i> Modificar', ['id'=> 'botonGuardarContacto','class' => $model->isNewRecord ? 'btn btn-success btn-lg' : 'btn btn-primary btn-lg']) ?>
                                    <span style="width:3em;"> </span>
                                    <?= Html::Button('Siguiente <i class="fa fa-chevron-circle-right"></i>', ['id'=> 'botonSiguienteContacto','class' => 'btn btn-primary btn-lg']) ?>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div role="tabpanel" class="tab-pane fade" id="requerimiento">
                <div class="rowX">
                    <?php DynamicFormWidget::begin([
                        'widgetContainer' => 'dynamicform_wrapper', // required: only alphanumeric characters plus "_" [A-Za-z0-9_]
                        'widgetBody' => '.container-items', // required: css class selector
                        'widgetItem' => '.item', // required: css class
                        //'limit' => 4, // the maximum times, an element can be cloned (default 999)
                        'min' => 0, // 0 or 1 (default 1)
                        'insertButton' => '.add-item', // css class
                        'deleteButton' => '.remove-item', // css class
                        'model' => $modelsRequerimiento[0],
                        'formId' => 'dynamic-form',
                        'formFields' => [
                            'titulo',
                            'descripcion',
                            'perfil_estudiante',
                            'apoyo_brindado',
                            'observacion',
                            'sci_id_sci',
                            'estado_ejecucion_id_estado',
                            'tagValues'
                        ],
                    ]); ?>
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <i class="glyphicon glyphicon-pencil"></i> Datos Requerimientos (Opcional)
                            <button type="button" class="pull-right add-item btn btn-success btn-md"><i class="fa fa-plus"></i> Agregar Requerimiento</button>
                            <div class="clearfix"></div>
                        </div>
                        <div class="panel-body">

                            <div class="container-items"><!-- widgetContainer -->
                                <?php foreach ($modelsRequerimiento as $i => $modelRequerimiento): ?>
                                    <div class="item panel panel-default"><!-- widgetBody -->
                                        <div class="panel-heading">
                                            <h3 class="panel-title pull-left">Requerimiento N°<?= ($i + 1) ?></h3>
                                            <div class="pull-right">
                                                <button type="button" class="add-item btn btn-success btn-xs"><i class="glyphicon glyphicon-plus"></i></button>
                                                <button type="button" class="remove-item btn btn-danger btn-xs"><i class="glyphicon glyphicon-minus"></i></button>
                                            </div>
                                            <div class="clearfix"></div>
                                        </div>
                                        <div class="panel-body">
                                            <?php
                                            // necessary for update action.
                                            if (! $modelRequerimiento->isNewRecord) {
                                                echo Html::activeHiddenInput($modelRequerimiento, "[{$i}]id_requerimiento");
                                            }
                                            ?>
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <?= $form->field($modelRequerimiento, "[{$i}]titulo")->textInput(['maxlength' => true]) ?>
                                                </div>
                                                <div class="col-sm-6">
                                                    <?= $form->field($modelRequerimiento, "[{$i}]descripcion")->textarea(['rows' => 2]) ?>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <?= $form->field($modelRequerimiento, "[{$i}]apoyo_brindado")->textarea(['rows' => 2]) ?>
                                                </div>
                                                <div class="col-sm-6">
                                                    <?= $form->field($modelRequerimiento, "[{$i}]cantidad_aprox_beneficiarios")->textInput(['maxlength' => true]) ?>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <?= $form->field($modelRequerimiento, "[{$i}]perfil_estudiante")->textInput(['maxlength' => true]) ?>
                                                </div>
                                                <div class="col-sm-6">
                                                    <?= $form->field($modelRequerimiento, "[{$i}]observacion")->textInput(['maxlength' => true]) ?>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12 col-lg-12">
                                                    <?= $form->field($modelRequerimiento, "[{$i}]tagValues")->widget(TagsinputWidget::classname(), [
                                                        'options' => [
                                                            'class' => 'form-control',
                                                        ],
                                                        'clientOptions' => [
                                                            'trimValue' => true,
                                                            'allowDuplicates' => false,
                                                            /* 'onTagExists' => function(item, $tag) {
                                                                 $tag.hide().fadeIn();
                                                             }*/
                                                        ],
                                                    ])->hint('Use comas para separar las Palabras Clave del Requerimiento') ?>
                                                </div>
                                            </div>
                                            <!--CANTIDAD DE SOCIOS BENEFICIARIOS POR REQUERIMIENTO -->
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <?= Html::submitButton($model->isNewRecord ? '<i class="fa fa-floppy-o" aria-hidden="true"></i> Guardar' : '<i class="fa fa-floppy-o"></i> Modificar', [
                                    'id'=> 'botonGuardarRequerimiento',
                                'class' => $model->isNewRecord ? 'btn btn-success btn-lg pull-right' : 'btn btn-primary btn-lg pull-right',
                                'style'=> 'display: block;']) ?>
                        </div><!-- FIN BODY -->
                    </div><!--FIN PANEL-->
                    <?php DynamicFormWidget::end(); ?>

                </div>
            </div>

            <div role="tabpanel" class="tab-pane fade" id="settings">...</div>

        </div><!-- TAB CONTENT -->
    </div><!-- FIN NAV CUSTOM-->

    <?php ActiveForm::end(); ?>

</div>
