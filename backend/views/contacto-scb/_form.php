<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\contactoScb */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="contacto-scb-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'rut_beneficiario')->textInput(['data-rut' => 'true','maxlength' => true]) ?>

    <?= $form->field($model, 'scb_id_scb')->textInput() ?>

    <?= $form->field($model, 'nombres')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'apellidos')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'fecha_nacimiento')->textInput() ?>

    <?= $form->field($model, 'estado_civil')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'direccion')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'profesion_ocupacion')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'rubro_giro')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'telefono')->textInput(['maxlength' => true]) ?>

  
	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>
