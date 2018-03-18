<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\search\RequerimientoSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="requerimiento-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id_requerimiento') ?>

    <?= $form->field($model, 'titulo') ?>

    <?= $form->field($model, 'descripcion') ?>

    <?= $form->field($model, 'perfil_estudiante') ?>

    <?= $form->field($model, 'apoyo_brindado') ?>

    <?php // echo $form->field($model, 'observacion') ?>

    <?php // echo $form->field($model, 'sci_id_sci') ?>

    <?php // echo $form->field($model, 'estado_ejecucion_id_estado') ?>

    <?php // echo $form->field($model, 'creado_en') ?>

    <?php // echo $form->field($model, 'modificado_en') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
