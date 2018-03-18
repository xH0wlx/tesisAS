<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\time\TimePicker;
use kartik\date\DatePicker;
use kartik\file\FileInput;


/* @var $this yii\web\View */
/* @var $model backend\models\bitacora */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="bitacora-form">

    <div class="panel panel-primary">
        <div class="panel-heading"><i class="glyphicon glyphicon-pencil"></i> Formulario de la Bitácora</div>
        <div class="panel-body">
            <?php $form = ActiveForm::begin([
                'options'=>['enctype'=>'multipart/form-data']
            ]); ?>

            <?= $form->field($model, 'fecha_bitacora')->widget(DatePicker::classname(), [
                'options' => ['placeholder' => 'Fecha de la visita ...'],
                'pluginOptions' => [
                    'autoclose'=>true,
                    'format' => 'yyyy-mm-dd',
                    'endDate' => "0d"
                ]
            ]); ?>
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <?= $form->field($model, 'hora_inicio')->widget(TimePicker::classname(), [
                        'pluginOptions' => [
                            'showSeconds' => true,
                            'showMeridian' => false,
                            'minuteStep' => 1,
                            'secondStep' => 5,
                        ]
                    ]);?>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <?= $form->field($model, 'hora_termino')->widget(TimePicker::classname(), [
                        'pluginOptions' => [
                            'showSeconds' => true,
                            'showMeridian' => false,
                            'minuteStep' => 1,
                            'secondStep' => 5,
                        ]
                    ]); ?>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12">

                </div>
                <div class="col-lg-6 col-md-6 col-sm-12">

                </div>
            </div>
            <?= $form->field($model, 'actividad_realizada')->textArea(['maxlength' => true]) ?>

            <?= $form->field($model, 'resultados')->textArea(['maxlength' => true]) ?>

            <?= $form->field($model, 'observaciones')->textArea(['maxlength' => true]) ?>

            <!-- //$form->field($modelEvidencia, 'ruta_archivo')->widget(FileInput::classname(), [
                //'options' => ['accept' => 'image/*'],
                //'pluginOptions'=>['allowedFileExtensions'=>['jpg','gif', 'png'],'showUpload' => true,],
            //]);-->
            <?php
                if(!$modelEvidencia->isNewRecord){
                    $nombreArchivo = $modelEvidencia->nombre_archivo;
                    echo Html::activeHiddenInput($modelEvidencia, 'id_evidencia');
            }else{
                    $nombreArchivo = "";
                }
            ?>

            <?= $form->field($modelEvidencia, 'instancia_archivo')->widget(FileInput::classname(), [
                //'options' => ['accept' => 'image/*'],
                'pluginOptions'=>[
                        'initialCaption'=>"",
                        'showPreview' => false,
                        //'allowedFileExtensions'=>['rar','zip'],
                        'showUpload' => false,],
            ])->hint("Suba su archivo comprimido en formato .rar o .zip"); ?>
            <?php
                if(!$modelEvidencia->isNewRecord){
                    echo Html::a('<i class="fa fa-download" aria-hidden="true"></i> Descargar Archivo Bitácora', ['download', 'id' => $modelEvidencia->id_evidencia], ['class' => 'btn btn-success']);
                    echo "<br><br>";
                }//FIN IF
            ?>

            <?= $form->field($modelEvidencia, 'descripcion')->textArea(['maxlength' => true])->label('Descripción de la Evidencia') ?>

            <?php if (!Yii::$app->request->isAjax){ ?>
                <div class="form-group">
                    <?= Html::submitButton($model->isNewRecord ? 'Crear' : 'Modificar', ['class' => $model->isNewRecord ? 'btn btn-lg btn-success' : 'btn btn-lg btn-primary']) ?>
                </div>
            <?php } ?>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
    
</div>
