<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use wbraganca\tagsinput\TagsinputWidget;
use yii\helpers\Url;
use wbraganca\dynamicform\DynamicFormWidget;

use kartik\growl\Growl;
use kartik\growl\GrowlAsset;
use kartik\base\AnimateAsset;

GrowlAsset::register($this);
AnimateAsset::register($this);

/* @var $this yii\web\View */
/* @var $model backend\models\requerimiento */
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


//EVENTS
$(".dynamicform_wrapper").on("beforeDelete", function(e, item) {
    if (! confirm("Esta seguro que quiere eliminar este item?")) {
        return false;
    }
    return true;
});

$(".dynamicform_wrapper").on("limitReached", function(e, item) {
    alert("Limit reached");
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

$(".dynamicform_wrapper22").on("beforeInsertPropio", function(e, item) {
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

jQuery(".dynamicform_wrapper").on("afterInsert", function(e, item) {
    var elementos = $(item);
    var creado = $(\'select[id *= sci_id_sci]\', elementos);
    var idCreado = creado.attr(\'id\');    
    var arregloDelItemCreado = idCreado.split("-");
    var numeroDelItemCreado = arregloDelItemCreado[1];
    
    if(numeroDelItemCreado >= 1){
        var numeroDelPadre = (numeroDelItemCreado - 1).toString();
        var concatenada = numeroDelPadre.concat("-sci_id_sci");
        var padreSelector = $(\'select[id *= \'+concatenada+\']\');
        
        creado.val(padreSelector.val()).change();
    }
    
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
});

jQuery(".dynamicform_wrapper").on("afterDelete", function(e) {
    jQuery(".dynamicform_wrapper .panel-title").each(function(i) {
        jQuery(this).html("Requerimiento N°" + (i + 1))
    });
});

jQuery(".dynamicform_wrapper2").on("afterInsert", function(e, item) {
    jQuery(".dynamicform_wrapper2 .panel-title").each(function(i) {
        jQuery(this).html("Contacto N°" + (i + 1))
    });
});

jQuery(".dynamicform_wrapper2").on("afterDelete", function(e) {
    jQuery(".dynamicform_wrapper2 .panel-title").each(function(i) {
        jQuery(this).html("Contacto N°" + (i + 1))
    });
});

';

$this->registerJs($js);

?>

<div class="requerimiento-form">
    <?php $form = ActiveForm::begin(['id' => 'dynamic-form']); ?>

            <?php DynamicFormWidget::begin([
                'widgetContainer' => 'dynamicform_wrapper', // required: only alphanumeric characters plus "_" [A-Za-z0-9_]
                'widgetBody' => '.container-items', // required: css class selector
                'widgetItem' => '.item', // required: css class
                //'limit' => 4, // the maximum times, an element can be cloned (default 999)
                'min' => 1, // 0 or 1 (default 1)
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
                    'cantidad_aprox_beneficiarios',
                    'tagValues'
                ],
            ]); ?>
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <i class="glyphicon glyphicon-pencil"></i> Datos Requerimiento/s
                    <button type="button" class="pull-right add-item btn btn-success btn-xs"><i class="fa fa-plus"></i> Agregar Requerimiento</button>
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
                                    <?= $form->field($modelRequerimiento, "[{$i}]sci_id_sci")->widget(Select2::classname(), [
                                        'data' => $modelRequerimiento->socioILista,
                                        'language' => 'es',
                                        'theme' => 'default',
                                        'options' => ['placeholder' => 'Seleccione Socio Comunitario Institucional ...'],
                                        'pluginOptions' => [
                                            'allowClear' => true
                                        ],
                                    ]);?>
                                    <?= $form->field($modelRequerimiento, "[{$i}]titulo")->textInput(['maxlength' => true]) ?>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <?= $form->field($modelRequerimiento, "[{$i}]descripcion")->textarea(['rows' => 2]) ?>
                                        </div>
                                        <div class="col-sm-6">
                                            <?= $form->field($modelRequerimiento, "[{$i}]apoyo_brindado")->textarea(['rows' => 2]) ?>
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
                                        <div class="col-sm-6 col-lg-6">
                                            <?= $form->field($modelRequerimiento, "[{$i}]cantidad_aprox_beneficiarios")->textInput(['maxlength' => true]) ?>
                                        </div>
                                        <div class="col-sm-6 col-lg-6">
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
                                            ])->hint('Use comas para separar las Palabras Claves.') ?>
                                        </div>
                                    </div>
                                    <!--CANTIDAD DE SOCIOS BENEFICIARIOS POR REQUERIMIENTO -->
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php DynamicFormWidget::end(); ?>
                    </div>
                </div>
            </div>
    <?php if (!Yii::$app->request->isAjax){ ?>
        <div class="form-group">
            <?= Html::submitButton(count($modelsRequerimiento) != 0 ? 'Crear' : 'Modificar',
                ['class' => count($modelsRequerimiento) != 0 ? 'btn btn-lg btn-success' : 'btn btn-lg btn-primary']) ?>
        </div>
    <?php }; ?>
    <?php ActiveForm::end(); ?>
</div>
