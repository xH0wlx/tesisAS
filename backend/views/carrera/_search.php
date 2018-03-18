<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\search\CarreraSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="carrera-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id_carrera') ?>

    <?= $form->field($model, 'cod_carrera') ?>

    <?= $form->field($model, 'nombre_carrera') ?>

    <?= $form->field($model, 'plan_carrera') ?>

    <?= $form->field($model, 'facultad_id_facultad') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
