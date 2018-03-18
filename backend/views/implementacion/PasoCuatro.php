<?php
/**
 * Created by PhpStorm.
 * User: Howl
 * Date: 06-06-2017
 * Time: 17:02
 */
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\file\FileInput;

if(isset($datosPost)){
    var_dump($datosPost);
}
?>

<div><h1>DATOS CARGADOS</h1></div>
<div class="panel panel-primary">
    <div class="panel-heading">Datos de Implementación</div>
    <div class="panel-body">
        <div class="seccion-form">
            <?php $form = ActiveForm::begin([
                    'options'=>['enctype'=>'multipart/form-data']
            ]);?>
            <?= Html::hiddenInput('id_implementacion', $idImplementacion);?>
            <?= Html::hiddenInput('id_seccion', $idSeccion);?>
            <?= $form->field($model, 'archivoExcel')->widget(FileInput::classname(), [
                'options' => ['accept' => 'image/*'],
                'pluginOptions'=>['allowedFileExtensions'=>['xls','xlsx'],'showUpload' => true,],
            ]);   ?>

            <?= Html::submitButton('Guardar y Salir', ['name'=>'guardarYSalir', 'value'=>'index','class' =>'btn btn-primary']) ?>
            <?= Html::submitButton('Guardar y Continuar', ['name'=>'guardarYContinuar','value'=> 'decision-seccion','class' => 'btn btn-primary']) ?>
            <?php ActiveForm::end(); ?>

        </div>
    </div>
</div>