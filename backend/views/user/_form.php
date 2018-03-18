<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model backend\models\user */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'username')->textInput(['data-rut' => 'true','maxlength' => true])->label("RUT (Este será el nombre de usuario)") ?>
    <?= $form->field($model, 'nombre_completo')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?php
    if($model->isNewRecord){
     echo $form->field($model, 'password_hash')->passwordInput(['maxlength' => true])->label("Contraseña");

    } else{
        echo $form->field($model, 'password_hash')->passwordInput(['maxlength' => true])->label("Nueva Contraseña")
            ->hint("Al estar encriptadas las contraseñas, debe ingresar una nueva para modificar la anterior.");
    }
    ?>
    <?= $form->field($model, 'password_repeat')->passwordInput(['maxlength' => true])->label("Repetir Contraseña") ?>

    <?= $form->field($model, 'rol')->widget(Select2::classname(), [
        'data' => $model->rolesLista,
        'language' => 'es',
        'theme' => 'default',
        'options' => ['placeholder' => 'Seleccione Tipo de Usuario ...'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ])->label("Tipo de Usuario");?>


  
	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>
