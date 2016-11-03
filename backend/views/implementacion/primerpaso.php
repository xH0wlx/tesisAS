<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

//Plugins y widgets externos a la instalación de YIi2
use kartik\select2\Select2;
use kartik\depdrop\DepDrop;

/**
 * Created by PhpStorm.
 * User: Luis
 * Date: 01-11-2016
 * Time: 21:08
 */
?>

    <nav>
        <ol class="cd-breadcrumb triangle custom-icons">
            <li class="current"><em>Primer Paso</em></li>
            <li><a href="#0">Segundo Paso</a></li>
            <li><em>Tercer Paso</em></li>
            <li><em>Cuardo Paso</em></li>
        </ol>
    </nav>


    <?php $form = ActiveForm::begin(); ?>
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="panel-title">Nueva Implementación de Asignatura</h3>
        </div>
        <div class="panel-body">
                <?= $form->field($modelSede, 'nombre')->widget(Select2::classname(), [
                    'data' => $sedes,
                    'language' => 'es',
                    'theme' => 'default',
                    'options' => ['id' => 'prueba', 'placeholder' => 'Seleccione una sede ...'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ])->label('Sede'); ?>

                <?= $form->field($modelCarrera, 'nombre')->widget(DepDrop::classname(), [
                    'type'=>DepDrop::TYPE_SELECT2,
                    'options' => ['id'=>'inputCarrera'],
                    'select2Options'=>[
                        'language' => 'es',
                        'theme' => 'default',
                        'pluginOptions'=>['allowClear'=>true]
                    ],
                    'pluginOptions'=>[
                        'depends'=>['prueba'],
                        'placeholder' => 'Seleccione una carrera ...',
                        'url' => Url::to(['/implementacion/carrera'])
                    ]
                ])->label('Carrera');?>

                <?= $form->field($modelAsignatura, 'nombre')->widget(DepDrop::classname(), [
                    'type'=>DepDrop::TYPE_SELECT2,
                    'select2Options'=>[
                        'language' => 'es',
                        'theme' => 'default',
                        'pluginOptions'=>['allowClear'=>true]
                    ],
                    'pluginOptions'=>[
                        'depends'=>['inputCarrera'],
                        'placeholder'=>'Seleccione una asignatura ...',
                        'url'=>Url::to(['/implementacion/asignatura'])
                    ]
                ])->label('Asignatura');?>


                <div class="form-group">
                    <?= Html::submitButton($modelCarrera->isNewRecord ? 'PRUEBA' : 'Update', ['class' => $modelCarrera->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>




<div class="row">
    <div class="col-sm-6">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Ready</h3>
                <span class="label label-primary pull-right">
                <i class="fa fa-html5"></i>
                </span>
            </div>
            <div class="box-body">
                <p>Compiled and ready to use in production. Download this version if you don't want to customize AdminLTE's LESS files.</p>
                <a class="btn btn-primary" href="http://almsaeedstudio.com/download/AdminLTE-dist">
                    <i class="fa fa-download"></i>
                    Download
                </a>
            </div>
        </div>
    </div>
</div>
