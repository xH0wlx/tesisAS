<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\seccion */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="seccion-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'numero_seccion')->textInput() ?>

    <?= $form->field($model, 'implementacion_id_implementacion')->textInput() ?>

    <?= $form->field($model, 'docente_rut_docente')->textInput(['maxlength' => true]) ?>

  
	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>
