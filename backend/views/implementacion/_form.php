<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\implementacion */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="implementacion-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'asignatura_cod_asignatura')->textInput() ?>

    <?= $form->field($model, 'anio_implementacion')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'semestre_implementacion')->textInput(['maxlength' => true]) ?>

  
	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>
