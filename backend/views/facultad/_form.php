<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model backend\models\facultad */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="facultad-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'nombre_facultad')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sede_id_sede')->widget(Select2::classname(), [
        'data' => $model->sedeLista,
        'language' => 'es',
        'theme' => 'default',
        'options' => ['placeholder' => 'Seleccione Sede ...'],
        'pluginOptions' => [
        'allowClear' => true
    ],
    ]);?>

  
	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>
