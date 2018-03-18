<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use wbraganca\dynamicform\DynamicFormWidget;
use kartik\growl\Growl;
use kartik\growl\GrowlAsset;
use kartik\base\AnimateAsset;

GrowlAsset::register($this);
AnimateAsset::register($this);


/* @var $this yii\web\View */
/* @var $model backend\models\seccion */
/* @var $form yii\widgets\ActiveForm */

$js = '
//EVENTS
jQuery(".dynamicform_wrapper").on("afterInsert", function(e, item) {
    jQuery(".dynamicform_wrapper .panel-title").each(function(i) {
        jQuery(this).html("Sección N°" + (i + 1));
    });
    
     jQuery("input[id *= numero_seccion]").each(function(i) {
        jQuery(this).val(i+1);
    });
    
     jQuery("input[id *= implementacion_id_implementacion]").each(function(i) {
        jQuery(this).val(\''.isset(Yii::$app->session['impEnCurso.id_implementacion'])?Yii::$app->session['impEnCurso.id_implementacion']:null.'\');
    });
});

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
    console.log($($form).find("div.has-error").length);
    if ($($form).find("div.has-error").length !== 0) {
        $.notify({message: "No puede agregar otra Sección si no ha completado todos los datos requeridos"},{type: "danger"});
        return false;
    }else{
        return true;
    }
});


jQuery(".dynamicform_wrapper").on("afterDelete", function(e) {
    jQuery(".dynamicform_wrapper .panel-title").each(function(i) {
        jQuery(this).html("Sección N°" + (i + 1))
    });
    
    jQuery("input[id *= numero_seccion]").each(function(i) {
        jQuery(this).val(i+1);
    });
});

';

$this->registerJs($js);

if(isset($datosPost)){
    var_dump($datosPost);
}
?>

<div class="panel panel-primary">
    <div class="panel-heading">Datos de Implementación</div>
    <div class="panel-body">
            <?php $form = ActiveForm::begin(['id' => 'dynamic-form']); ?>
            <?php DynamicFormWidget::begin([
                'widgetContainer' => 'dynamicform_wrapper', // required: only alphanumeric characters plus "_" [A-Za-z0-9_]
                'widgetBody' => '.container-items', // required: css class selector
                'widgetItem' => '.item', // required: css class
                //'limit' => 4, // the maximum times, an element can be cloned (default 999)
                'min' => 1, // 0 or 1 (default 1)
                'insertButton' => '.add-item', // css class
                'deleteButton' => '.remove-item', // css class
                'model' => $modelsSeccion[0],
                'formId' => 'dynamic-form',
                'formFields' => [
                    'numero_seccion',
                    'implementacion_id_implementacion',
                    'docente_rut_docente',
                ],
            ]); ?>
        <div class="panel panel-primary">
            <div class="panel-heading">
                <i class="glyphicon glyphicon-pencil"></i> Datos Sección/es
                <button type="button" class="pull-right add-item btn btn-success btn-xs"><i class="fa fa-plus"></i> Agregar Sección</button>
                <div class="clearfix"></div>
            </div>
            <div class="panel-body">
                <div class="container-items"><!-- widgetContainer -->
                    <?php foreach ($modelsSeccion as $i => $modelSeccion): ?>
                        <div class="item panel panel-default"><!-- widgetBody -->
                            <div class="panel-heading">
                                <h3 class="panel-title pull-left">Sección N°<?= ($i + 1) ?></h3>
                                <div class="pull-right">
                                    <button type="button" class="add-item btn btn-success btn-xs"><i class="glyphicon glyphicon-plus"></i></button>
                                    <button type="button" class="remove-item btn btn-danger btn-xs"><i class="glyphicon glyphicon-minus"></i></button>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <div class="panel-body">
                                <?php
                                // necessary for update action.
                                if (! $modelSeccion->isNewRecord) {
                                    echo Html::activeHiddenInput($modelSeccion, "[{$i}]id_seccion");
                                }
                                ?>
                                <?= $form->field($modelSeccion, "[{$i}]implementacion_id_implementacion")->textInput(['maxlength' => true]) ?>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <?= $form->field($modelSeccion, "[{$i}]numero_seccion")->textInput(['maxlength' => true]) ?>
                                    </div>
                                    <div class="col-sm-6">
                                        <?= $form->field($modelSeccion, "[{$i}]docente_rut_docente")->textInput(['maxlength' => true]) ?>
                                    </div>
                                </div>
                                <!--CANTIDAD DE SOCIOS BENEFICIARIOS POR REQUERIMIENTO -->
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <?php DynamicFormWidget::end(); ?>
            </div>
        </div>
            <?= Html::submitButton('Guardar y Salir', ['name'=>'guardarYSalir', 'value'=>'index','class' =>'btn btn-primary']) ?>
            <?= Html::submitButton('Guardar y Continuar', ['name'=>'guardarYContinuar','value'=> 'decision-seccion','class' => 'btn btn-primary']) ?>
            <?php ActiveForm::end(); ?>


    </div>
</div>