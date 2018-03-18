<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ImplementacionSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="implementacion-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id_implementacion') ?>

    <?= $form->field($model, 'anio') ?>

    <?= $form->field($model, 'periodo') ?>

    <?= $form->field($model, 'numero_seccion') ?>

    <?= $form->field($model, 'cod_asignatura') ?>

    <?php // echo $form->field($model, 'rut_profesor') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
