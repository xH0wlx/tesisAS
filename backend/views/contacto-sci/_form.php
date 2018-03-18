<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model backend\models\ContactoSci */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="contacto-sci-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="panel panel-primary">
        <div class="panel-heading"><i class="glyphicon glyphicon-pencil"></i> Datos del Contacto</div>
        <div class="panel-body">

                <?= $form->field($model, 'sci_id_sci')->widget(Select2::classname(), [
                    'data' => $model->socioILista,
                    'language' => 'es',
                    'theme' => 'default',
                    'options' => ['placeholder' => 'Seleccione Socio Comunitario Institucional ...'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]);?>

                <div class="row">
                    <div class="col-sm-6">
                        <?= $form->field($model, 'nombres')->textInput(['maxlength' => true]) ?>
                    </div>
                    <div class="col-sm-6">
                        <?= $form->field($model, 'apellidos')->textInput(['maxlength' => true]) ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <?= $form->field($model, 'telefono')->textInput(['maxlength' => true]) ?>
                    </div>
                    <div class="col-sm-6">
                        <?= $form->field($model, 'cargo')->textInput(['maxlength' => true]) ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
                    </div>
                    <div class="col-sm-6">
                        <?= $form->field($model, 'observacion')->textInput(['maxlength' => true]) ?>
                    </div>
                </div>

        </div>
    </div>

  
	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Crear' : 'Modificar', ['class' => $model->isNewRecord ? 'btn btn-lg btn-success' : 'btn btn-lg btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>
