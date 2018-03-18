<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\requerimientoHasEtiquetaArea */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="requerimiento-has-etiqueta-area-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'requerimiento_id_requerimiento')->textInput() ?>

    <?= $form->field($model, 'etiqueta_area_id_etiqueta_area')->textInput() ?>

  
	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>
