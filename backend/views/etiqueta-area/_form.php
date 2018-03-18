<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\etiquetaArea */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="etiqueta-area-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'nombre_etiqueta_area')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'descripcion_etiqueta')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'frecuencia')->textInput() ?>

  
	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>
