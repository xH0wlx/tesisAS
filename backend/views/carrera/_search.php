<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\CarreraSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="carrera-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id_carrera') ?>

    <?= $form->field($model, 'cod_carrera') ?>

    <?= $form->field($model, 'plan') ?>

    <?= $form->field($model, 'nombre') ?>

    <?= $form->field($model, 'id_sede') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
