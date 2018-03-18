<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model backend\models\alumnoInscritoSeccion */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="alumno-inscrito-seccion-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'alumno_rut_alumno')->widget(Select2::classname(), [
        'data' => $model->alumnoLista,
        'language' => 'es',
        'theme' => 'default',
        'options' => ['placeholder' => 'Seleccione Alumno ...'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]);?>

   <!-- <?/*= $form->field($model, 'seccion_id_seccion')->textInput() */?>-->

  
	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Crear' : 'Modificar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>
