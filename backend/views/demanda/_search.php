<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\search\DemandaSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="demanda-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id_demanda') ?>

    <?= $form->field($model, 'perfil_estudiante') ?>

    <?= $form->field($model, 'apoyos_brindados') ?>

    <?= $form->field($model, 'observaciones') ?>

    <?= $form->field($model, 'fecha_creacion') ?>

    <?php // echo $form->field($model, 'sci_id_sci') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
