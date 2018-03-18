<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\search\EtiquetaAreaSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="etiqueta-area-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id_etiqueta_area') ?>

    <?= $form->field($model, 'nombre_etiqueta_area') ?>

    <?= $form->field($model, 'descripcion') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
