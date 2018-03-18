<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use wbraganca\dynamicform\DynamicFormWidget;
use kartik\select2\Select2;
use kartik\depdrop\DepDrop;

/* @var $this yii\web\View */
/* @var $model backend\models\demanda */
/* @var $form yii\widgets\ActiveForm */

$js = '
jQuery(".dynamicform_wrapper").on("afterInsert", function(e, item) {
    jQuery(".dynamicform_wrapper .panel-title").each(function(i) {
        jQuery(this).html("Requerimiento N°" + (i + 1))
    });
});

jQuery(".dynamicform_wrapper").on("afterDelete", function(e) {
    jQuery(".dynamicform_wrapper .panel-title").each(function(i) {
        jQuery(this).html("Requerimiento N°" + (i + 1))
    });
});
';

$this->registerJs($js);

?>

<div class="demanda-form">

    <?php $form = ActiveForm::begin(['id' => 'dynamic-form']); ?>

    <div class="panel panel-default">
        <div class="panel-heading"><h4><i class="glyphicon glyphicon-pencil"></i> Datos generales de la Demanda</h4></div>
        <div class="panel-body">
            <?= $form->field($model, 'perfil_estudiante')->textarea(['rows' => 4]) ?>

            <?= $form->field($model, 'apoyos_brindados')->textarea(['rows' => 4]) ?>

            <?= $form->field($model, 'observaciones')->textarea(['rows' => 4]) ?>

            <?= $form->field($model, 'fecha_creacion')->textInput() ?>

            <?= $form->field($model, 'sci_id_sci')->widget(Select2::classname(), [
                'data' => $model->socioInstitucionalLista,
                'language' => 'es',
                'theme' => 'default',
                'options' => ['id' => 'sciField', 'placeholder' => 'Seleccione un Socio ...'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ])->label('Socio Comunitario Institucional'); ?>
        </div>
    </div>

    <div class="rowX">
        <div class="panel panel-default">
            <div class="panel-heading"><h4><i class="glyphicon glyphicon-pencil"></i> Agregar Requerimientos</h4></div>
            <div class="panel-body">
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
                        'estado',
                    ],
                ]); ?>

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
                                <?= $form->field($modelRequerimiento, "[{$i}]titulo")->textInput(['maxlength' => true]) ?>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <?= $form->field($modelRequerimiento, "[{$i}]descripcion")->textarea(['rows' => 6]) ?>
                                    </div>
                                    <div class="col-sm-6">
                                        <?= $form->field($modelRequerimiento, "[{$i}]estado")->textInput(['maxlength' => true]) ?>
                                    </div>
                                </div><!-- .row -->
<!--                                <div class="row">
                                    <div class="col-sm-4">
                                        <?/*= $form->field($modelRequerimiento, "[{$i}]city")->textInput(['maxlength' => true]) */?>
                                    </div>
                                    <div class="col-sm-4">
                                        <?/*= $form->field($modelRequerimiento, "[{$i}]state")->textInput(['maxlength' => true]) */?>
                                    </div>
                                    <div class="col-sm-4">
                                        <?/*= $form->field($modelRequerimiento, "[{$i}]postal_code")->textInput(['maxlength' => true]) */?>
                                    </div>
                                </div><!-- .row -->
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <?php DynamicFormWidget::end(); ?>
            </div>
        </div>
    </div>


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Crear' : 'Modificar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
