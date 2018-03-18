<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\search\SciSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sci-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id_sci') ?>

    <?= $form->field($model, 'nombre') ?>

    <?= $form->field($model, 'direccion') ?>

    <?= $form->field($model, 'observacion') ?>

    <?= $form->field($model, 'departamento_programa') ?>

    <?php // echo $form->field($model, 'comuna_comuna_id') ?>

    <?php // echo $form->field($model, 'creado_en') ?>

    <?php // echo $form->field($model, 'modificado_en') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
