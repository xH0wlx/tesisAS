<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

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

<div class="row">
    <div class="col-md-12">
        <nav>
            <ol class="cd-breadcrumb triangle"  style="margin-left: 0px;">
                <li class="current">
                    <span>
                         <span>
                            <i class="fa fa-book"></i>
                        </span>
                        Asignatura
                    </span>
                </li>
                <li>
                    <span>
                         <span>
                            <i class="fa fa-group"></i>
                        </span>
                        Socios
                    </span>
                </li>
                <li>
                    <span>
                         <span>
                            <i class="fa fa-gg-circle"></i>
                        </span>
                        Asignación Grupos
                    </span>
                </li>
            </ol>
        </nav>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <?php $form = ActiveForm::begin(); ?>
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title">Selección de Asignatura</h3>
            </div>
            <div class="panel-body">
                <p>TIP: Si la asignatura no se encuentra en el sistema, agreguela AQUI</p>
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
                        'options' => ['id'=>'inputAsignatura'],
                        'select2Options'=>[
                            'language' => 'es',
                            'theme' => 'default',
                            'pluginOptions'=>['allowClear'=>true],

                        ],
                        'pluginOptions'=>[
                            'depends'=>['inputCarrera'],
                            'placeholder'=>'Seleccione una asignatura ...',
                            'url'=>Url::to(['/implementacion/asignatura']),
                        ],
                    ])->label('Asignatura');?>

            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>

<div class="col-md-6">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="panel-title">Asignatura a implementar</h3>
        </div>
        <div class="panel-body" id="asignaturaPrimerPaso">
            <h2>No seleccionada.</h2>
        </div>
    </div>
</div>


</div>


<div class="panel panel-primary">
    <div class="panel-heading">
        <h3 class="panel-title">Detalle de la implementación</h3>
    </div>
    <div id="panelSeccion" class="panel-body">
        Agregar Sección +<br>
        Año - Periodo (Estos datos se podrían predefinir en base a la época del año en la que estamos.
        Número de Sección
        Profesor (Que está en otra tabla)
    <p>Agregar Sección -- Excel Con alumnos de la sección</p>
        <button id="btnSeccion" class="btn btn-primary" type="button">Agregar Sección +&nbsp;</button>
        <div class="row">
            <div class="col-md-6 col-sm-12">
            </div>
            <div class="col-md-6 col-sm-12">
                <?=
                    Select2::widget([
                        'theme' => 'default',
                        'name' => 'Profesor',
                        'data' => $profesores,
                        'options' => ['placeholder' => 'Seleccione profesor(a) ...'],
                        'pluginOptions'=>['allowClear'=>true]
                    ]);
                ?>
            </div>
        </div>


    </div>
</div>


<div class="box box-default">
    <div class="box-header with-border">
        <h3 class="box-title">Collapsable</h3>
        <div class="box-tools pull-right">
            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
        </div><!-- /.box-tools -->
    </div><!-- /.box-header -->
    <div class="box-body">
        The body of the box
    </div><!-- /.box-body -->
</div><!-- /.box -->


<?php
$js = <<< JS
$(function(){
        $('#inputAsignatura').on('change', function(e) {
        var asignatura =  $('#inputAsignatura').val();
                $.ajax({
                url: "../implementacion-ajax/cargar-index?codAsignatura="+asignatura,
                type: 'post',
                data: {},
                //dataType: 'json'
            }).done(function(data){
                    $('#asignaturaPrimerPaso').html(data);
            })
        });
       
        $("#btnSeccion").click(function(){
            $("#panelSeccion").append("<div class=\"row\">"+
                "<div class=\"col-md-6 col-sm-12\">"+
                    "<h1>HOLA</h1>"+
                "</div>"+
            "</div>");
        });

});	
JS;

$this->registerJs($js);
?>