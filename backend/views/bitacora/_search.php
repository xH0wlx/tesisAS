<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\search\BitacoraSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="bitacora-search">

    <?php $form = ActiveForm::begin([
        'action' => \yii\helpers\Url::to(['bitacora/seleccion-bitacoras-reporte']),
        'method' => 'get',
    ]); ?>
    <div class="row">
        <div class="col-md-3">
            <?= $form->field($model, 'anio_desde') ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'anio_hasta') ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'semestre_numero') ?>
        </div>
        <div class="col-md-3">
        </div>
    </div>
    <?= $form->field($model, 'implementacion_id') ?>
    <?= $form->field($model, 'seccion_id') ?>
    <?= $form->field($model, 'grupo_id') ?>
    <?= $form->field($model, 'grupo_trabajo_id_grupo_trabajo') ?>

    <?php //$form->field($model, 'fecha_bitacora') ?>

    <?php //$form->field($model, 'hora_inicio') ?>

    <?php //$form->field($model, 'hora_termino') ?>

    <?php // echo $form->field($model, 'actividad_realizada') ?>

    <?php // echo $form->field($model, 'resultados') ?>

    <?php // echo $form->field($model, 'observaciones') ?>

    <?php // echo $form->field($model, 'fecha_lectura') ?>

    <?php // echo $form->field($model, 'creado_en') ?>

    <?php // echo $form->field($model, 'modificado_en') ?>

    <div class="form-group">
        <?= Html::submitButton('Buscar', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Limpiar Formulario', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
