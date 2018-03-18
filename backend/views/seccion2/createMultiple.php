<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use wbraganca\dynamicform\DynamicFormWidget;
use kartik\growl\Growl;
use kartik\growl\GrowlAsset;
use kartik\base\AnimateAsset;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use backend\models\Docente;

GrowlAsset::register($this);
AnimateAsset::register($this);


/* @var $this yii\web\View */
/* @var $model backend\models\seccion */
/* @var $form yii\widgets\ActiveForm */
$request = Yii::$app->request;
$this->title = 'Modificar Secciones';
$this->params['breadcrumbs'][] = ['label' => 'Panel de Implementación',
    'url' => ['/implementacion/panel-implementacion', 'idImplementacion'=> $request->get('idImplementacion')]];
$this->params['breadcrumbs'][] = $this->title;

$js = '
//EVENTS
jQuery(".dynamicform_wrapper").on("afterInsert", function(e, item) {
    jQuery(".dynamicform_wrapper .panel-title").each(function(i) {
        jQuery(this).html("<b>Sección N°" + (i + 1) + "</b>");
    });
    
    jQuery("input[id *= numero_seccion]").each(function(i) {
        jQuery(this).val(i+1);
    });
    
     jQuery("input[id *= implementacion_id_implementacion]").each(function(i) {
        jQuery(this).val(\''.Yii::$app->request->get('idImplementacion').'\');
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
        jQuery(this).html("<b>Sección N°" + (i + 1) + "</b>")
    });
    
    jQuery("input[id *= numero_seccion]").each(function(i) {
        jQuery(this).val(i+1);
    });
});

';

$this->registerJs($js);

$this->registerJsFile('@web/js/implementacion/funciones.js', ['depends' => [\yii\web\JqueryAsset::className()] ] );

?>
<!--
<div class="panel panel-primary">
    <div class="panel-heading">Datos de Implementación</div>
    <div class="panel-body">
        -->
            <?php $form = ActiveForm::begin(['id' => 'dynamic-form']); ?>
            <?php DynamicFormWidget::begin([
                'widgetContainer' => 'dynamicform_wrapper', // required: only alphanumeric characters plus "_" [A-Za-z0-9_]
                'widgetBody' => '.container-items', // required: css class selector
                'widgetItem' => '.item', // required: css class
                //'limit' => 4, // the maximum times, an element can be cloned (default 999)
                'min' => 0, // 0 or 1 (default 1)
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
                <i class="glyphicon glyphicon-pencil"></i> <?php if($modelsSeccion[0]->isNewRecord){ echo "Datos sección"; }else{echo "Secciones Creadas";} ?>
                <button type="button" class="pull-right add-item btn btn-success btn-md"><i class="fa fa-plus"></i> Agregar Sección</button>
                <div class="clearfix"></div>
            </div>
            <div class="panel-body">
                <div class="container-items"><!-- widgetContainer -->
                    <?php foreach ($modelsSeccion as $i => $modelSeccion): ?>
                        <div class="item panel panel-default"><!-- widgetBody -->
                            <div class="panel-heading">
                                <h3 class="panel-title pull-left"><b>Sección N°<?= ($i + 1) ?></b></h3>
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

                                <div class="row">
                                    <div class="col-sm-2">
                                        <?= $form->field($modelSeccion, "[{$i}]numero_seccion")->textInput(['maxlength' => true, 'readonly' => true])->label("Número de Sección") ?>
                                    </div>
                                    <div class="col-sm-10">
                                        <?= $form->field($modelSeccion, "[{$i}]docente_rut_docente")->widget(Select2::classname(), [
                                            'data' => ArrayHelper::map(Docente::find()->all(), 'rut_docente', 'nombre_completo'),
                                            'language' => 'es',
                                            'theme' => 'default',
                                            'options' => ['placeholder' => 'Seleccione Docente ...'],
                                            'pluginOptions' => [
                                                'allowClear' => true
                                            ],
                                        ]); ?>
                                    </div>
                                </div>
                                <!--CANTIDAD DE SOCIOS BENEFICIARIOS POR REQUERIMIENTO -->
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <?= Html::button('<i class="fa fa-chevron-circle-left"></i> Volver sin guardar', ['id' => 'botonVolverSinGuardar',
                    'value'=> Url::to(['/implementacion/panel-implementacion', 'idImplementacion' => Yii::$app->request->get('idImplementacion')]),
                    'class' =>'btn btn-danger']) ?>
                <?= Html::submitButton('<i class="fa fa-floppy-o" aria-hidden="true"></i> Guardar y volver', ['name'=>'guardarYSalir', 'value'=>'index','class' =>'btn btn-primary']) ?>
            </div>
        </div>
        <?php DynamicFormWidget::end(); ?>

            <?php ActiveForm::end(); ?>

<!--
    </div>
</div>
        ->>