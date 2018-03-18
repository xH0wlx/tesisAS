<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\sede */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sede-form">

    <?php $form = ActiveForm::begin([
        'options' => [
            'id' => 'sede-form'
        ]
    ]); ?>

    <?= $form->field($model, 'nombre_sede')->textInput(['maxlength' => true]) ?>

  
	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>
