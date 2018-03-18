<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\search\RequerimientoHasEtiquetaAreaSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="requerimiento-has-etiqueta-area-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'requerimiento_id_requerimiento') ?>

    <?= $form->field($model, 'etiqueta_area_id_etiqueta_area') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
