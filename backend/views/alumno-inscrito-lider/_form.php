<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\alumnoInscritoLider */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="alumno-inscrito-lider-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'alumno_inscrito_seccion_id_seccion_alumno')->textInput() ?>

    <?= $form->field($model, 'grupo_trabajo_id_grupo_trabajo')->textInput() ?>

    <?= $form->field($model, 'fecha_creacion')->textInput() ?>

  
	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>
