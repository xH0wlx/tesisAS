<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\search\AsignaturaSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="asignatura-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'cod_asignatura') ?>

    <?= $form->field($model, 'nombre_asignatura') ?>

    <?= $form->field($model, 'semestre_dicta') ?>

    <?= $form->field($model, 'semestre_malla') ?>

    <?= $form->field($model, 'carrera_id_carrera') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
