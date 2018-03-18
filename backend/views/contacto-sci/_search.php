<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\search\ContactoSciSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="contacto-sci-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id_contacto_sci') ?>

    <?= $form->field($model, 'nombres') ?>

    <?= $form->field($model, 'apellidos') ?>

    <?= $form->field($model, 'telefono') ?>

    <?= $form->field($model, 'cargo') ?>

    <?php // echo $form->field($model, 'email') ?>

    <?php // echo $form->field($model, 'sci_id_sci') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
