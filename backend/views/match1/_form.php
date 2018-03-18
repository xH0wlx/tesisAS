<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\match1 */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="match1-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'requerimiento_id_requerimiento')->textInput() ?>

    <?= $form->field($model, 'asignatura_cod_asignatura')->textInput() ?>

    <?= $form->field($model, 'anio_match1')->textInput() ?>

    <?= $form->field($model, 'semestre_match1')->textInput() ?>

    <?= $form->field($model, 'servicio_id_servicio')->textInput() ?>

    <?= $form->field($model, 'aprobacion_implementacion')->textInput() ?>

    <?= $form->field($model, 'creado_en')->textInput() ?>

    <?= $form->field($model, 'modificado_en')->textInput() ?>


    <?php if (!Yii::$app->request->isAjax){ ?>
        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
    <?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>
