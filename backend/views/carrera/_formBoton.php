<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model backend\models\carrera */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="carrera-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'cod_carrera')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'nombre_carrera')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'plan_carrera')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'alias_carrera')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'facultad_id_facultad')->widget(Select2::classname(), [
        'data' => $model->facultadLista,
        'language' => 'es',
        'theme' => 'default',
        'options' => ['placeholder' => 'Seleccione Facultad ...'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]);?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Crear' : 'Modificar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
    
</div>
