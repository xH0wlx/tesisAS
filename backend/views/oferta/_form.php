<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use wbraganca\dynamicform\DynamicFormWidget;
use kartik\daterange\DateRangePicker;
use kartik\select2\Select2;
use kartik\depdrop\DepDrop;
use Yii\helpers\Url;
use yii\helpers\Json;

use kartik\growl\Growl;
use kartik\growl\GrowlAsset;
use kartik\base\AnimateAsset;

GrowlAsset::register($this);
AnimateAsset::register($this);

/* @var $this yii\web\View */
/* @var $model backend\models\oferta */
/* @var $form yii\widgets\ActiveForm */

$addon = <<< HTML
<span class="input-group-addon">
    <i class="glyphicon glyphicon-calendar"></i>
</span>
HTML;


$js = '

//EVENTS
$(".dynamicform_wrapper").on("beforeDelete", function(e, item) {
    if (! confirm("Esta seguro que quiere eliminar este item?")) {
        return false;
    }
    return true;
});

$(".dynamicform_wrapper").on("limitReached", function(e, item) {
    alert("Limit reached");
});
//

$(".dynamicform_wrapper").on("beforeInsertPropio", function(e, item) {
    var $form = $("#dynamic-form"), 
    data = $form.data("yiiActiveForm");
    $.each(data.attributes, function() {
        this.status = 3;
    });
    $form.yiiActiveForm("validate");
    console.log($($form).find("div.has-error").length);
    if ($($form).find("div.has-error").length !== 0) {
        $.notify({message: "No puede agregar otro Servicio si no ha completado todos los datos requeridos"},{type: "danger"});
        return false;
    }else{
        return true;
    }
});


jQuery(".dynamicform_wrapper").on("afterInsert", function(e, item) {
    jQuery(".dynamicform_wrapper .panel-title").each(function(i) {
        jQuery(this).html("Servicio N°" + (i + 1));
    });
});

jQuery(".dynamicform_wrapper").on("afterDelete", function(e) {
    jQuery(".dynamicform_wrapper .panel-title").each(function(i) {
        jQuery(this).html("Servicio N°" + (i + 1))
    });
});


';

$this->registerJs($js);
?>

<div class="oferta-form">
    <div class="row">
        <div class="col-sm-12">
            <?= Html::a('<i class="fa fa-arrow-circle-left"></i> Volver a Lista de Ofertas', ['/oferta/index'], ['class' => 'btn btn-info']) ?>
        </div>
    </div>
    <br>

    <?php $form = ActiveForm::begin(['id' => 'dynamic-form']); ?>

    <div class="nav-tabs-custom">

        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active">
                <a href="#oferta" aria-controls="oferta" role="tab" data-toggle="tab"><i class="glyphicon glyphicon glyphicon-education"></i> Oferta</a>
            </li>
            <li role="presentation">
                <a href="#servicio" aria-controls="servicio" role="tab" data-toggle="tab"><i class="glyphicon glyphicon glyphicon-tasks"></i> Servicio</a>
            </li>
        </ul>

        <div class="tab-content">
            <div role="tabpanel" class="tab-pane fade in active" id="oferta">
                <div class="panel panel-primary">
                    <div class="panel-heading"><i class="glyphicon glyphicon-pencil"></i> Datos de la Oferta</div>
                    <div class="panel-body">

                        <div class="row">
                            <div class="col-sm-12">
                                <label class="control-label" for="sede">Sede</label>
                                <?php
                                if (!$model->isNewRecord) {
                                    $inicializar = true;
                                    //ID SEDE DE LA ASIGNATURA QUE VIENE PARA UPDATEAR
                                    $valueSede = $model->asignaturaCodAsignatura->carreraCodCarrera->facultadIdFacultad->sedeIdSede->id_sede;
                                    echo Html::hiddenInput('input-id_sede', $valueSede, ['id' => 'input-id_sede']);

                                    //ID FACULTAD DE LA ASIGNATURA QUE VIENE PARA UPDATEAR
                                    echo Html::hiddenInput('input-id_facultad', $model->asignaturaCodAsignatura->carreraCodCarrera->facultadIdFacultad->id_facultad, ['id' => 'input-id_facultad']);

                                    //ID CARRERA DE LA ASIGNATURA QUE VIENE PARA UPDATEAR
                                    echo Html::hiddenInput('input-id_carrera', $model->asignaturaCodAsignatura->carreraCodCarrera->cod_carrera, ['id' => 'input-id_carrera']);

                                    //ID ASIGNATURA DE LA OFERTA QUE VIENE PARA UPDATEAR
                                    echo Html::hiddenInput('input-id_asignatura', $model->asignatura_cod_asignatura, ['id' => 'input-id_asignatura']);
                                }else{
                                    $inicializar = false;
                                    $valueSede = '';
                                }
                                ?>
                                <div class="input-group">
                                    <?= Select2::widget([
                                        'name' => 'sede',
                                        'data' => $model->sedeLista,
                                        'value' => $valueSede,
                                        'language' => 'es',
                                        'theme' => 'default',
                                        'options' => ['id' => 'inputSede', 'placeholder' => 'Seleccione Sede ...'],
                                        'pluginOptions' => [
                                            'allowClear' => true
                                        ],
                                    ]); ?>
                                    <span class="input-group-btn">
                                <?=Html::button('<i class="glyphicon glyphicon-plus"></i> ',
                                    [
                                        'type'=>'button',
                                        'title'=>'Crear Sede',
                                        //'role' => 'modal-remote',
                                        'class'=>'btn btn-success',
                                        'style' => 'margin-left:10px; border-radius: 3px !important;',
                                        'onclick'=>"window.location.href = '" . \Yii::$app->urlManager->createUrl(['sede/create']) . "';",
                                    ]);?>
                                </span>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-sm-12">
                                <label class="control-label" for="facultad">Facultad</label>
                                <div class="input-group">
                                    <?= DepDrop::widget([
                                        'name' => 'facultad',
                                        //'data' => $dataFacultad,
                                        'type'=>DepDrop::TYPE_SELECT2,
                                        'options' => ['id'=>'inputFacultad'],
                                        'select2Options'=>[
                                            'language' => 'es',
                                            'theme' => 'default',
                                            'pluginOptions'=>['allowClear'=>true],

                                        ],
                                        'pluginOptions'=>[
                                            'depends'=>['inputSede'],
                                            'placeholder'=>'Seleccione Facultad ...',
                                            'url'=>Url::to(['/asignatura/subfacultades']),
                                            'initialize' => $inicializar,
                                            'params'=>['input-id_facultad']
                                        ],
                                    ]);
                                    ?>
                                    <span class="input-group-btn">
                                    <?=Html::button('<i class="glyphicon glyphicon-plus"></i> ',
                                        [
                                            'type'=>'button',
                                            'title'=>'Crear Facultad',
                                            //'role' => 'modal-remote',
                                            'class'=>'btn btn-success',
                                            'style' => 'margin-left:10px; border-radius: 3px !important;',
                                            'onclick'=>"window.location.href = '" . \Yii::$app->urlManager->createUrl(['facultad/create']) . "';",
                                        ]);?>
                                </span>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-sm-12">
                                <label class="control-label" for="carrera">Carrera</label>
                                <div class="input-group">
                                    <?= DepDrop::widget([
                                        'name' => 'carrera',
                                        //'data' => $dataFacultad,
                                        'type'=>DepDrop::TYPE_SELECT2,
                                        'options' => ['id'=>'inputCarrera'],
                                        'select2Options'=>[
                                            'language' => 'es',
                                            'theme' => 'default',
                                            'pluginOptions'=>['allowClear'=>true],

                                        ],
                                        'pluginOptions'=>[
                                            'depends'=>['inputFacultad'],
                                            'placeholder'=>'Seleccione Carrera ...',
                                            'url'=>Url::to(['/asignatura/subcarreras']),
                                            'initialize' => $inicializar,
                                            'params'=>['input-id_carrera']
                                        ],
                                    ]);
                                    ?>

                                    <span class="input-group-btn">
                                    <?=Html::button('<i class="glyphicon glyphicon-plus"></i> ',
                                        [
                                            'type'=>'button',
                                            'title'=>'Crear Carrera',
                                            //'role' => 'modal-remote',
                                            'class'=>'btn btn-success',
                                            'style' => 'margin-left:10px; border-radius: 3px !important; margin-bottom:3px;',
                                            'onclick'=>"window.location.href = '" . \Yii::$app->urlManager->createUrl(['carrera/create']) . "';",
                                        ]);?>
                            </span>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="input-group">
                                    <?= $form->field($model, 'asignatura_cod_asignatura')->widget(DepDrop::classname(), [
                                        'type'=>DepDrop::TYPE_SELECT2,
                                        //'data' => $dataCarrera,
                                        'options' => ['id'=> Html::getInputId($model, "asignatura_cod_asignatura")],
                                        'select2Options'=>[
                                            'language' => 'es',
                                            'theme' => 'default',
                                            'pluginOptions'=>['allowClear'=>true],

                                        ],
                                        'pluginOptions'=>[
                                            'depends'=>['inputCarrera'],
                                            'placeholder'=>'Seleccione una Asignatura ...',
                                            'url'=>Url::to(['/asignatura/subasignaturas']),
                                            'initialize' => $inicializar,
                                            'params'=>['input-id_asignatura']
                                        ],
                                    ]);?>
                                    <span class="input-group-btn">
                                    <?=Html::button('<i class="glyphicon glyphicon-plus"></i> ',
                                        [
                                            'type'=>'button',
                                            'title'=>'Crear Carrera',
                                            //'role' => 'modal-remote',
                                            'class'=>'btn btn-success',
                                            'style' => 'margin-left:10px; border-radius: 3px !important; margin-bottom:-14px;',
                                            'onclick'=>"window.location.href = '" . \Yii::$app->urlManager->createUrl(['carrera/create']) . "';",
                                        ]);?>
                            </span>
                                </div>
                            </div>
                        </div>

                        <?php
/*                        //$model->fecha_inicio_inicio = '2016-02-11';
                        //$model->fecha_termino_actividad = '2016-03-15';
                        $model->contenedor_fecha = date('Y-m-d');

                        echo '<div class="input-group drp-container">';
                        echo DateRangePicker::widget([
                                'model'=>$model,
                                'attribute' => 'contenedor_fecha',
                                'useWithAddon'=>true,
                                'convertFormat'=>true,
                                'startAttribute' => 'fecha_inicio_inicio',
                                'endAttribute' => 'fecha_termino_actividad',
                                'pluginOptions'=>[
                                    'locale'=>['format' => 'Y-m-d'],
                                ]
                            ]) . $addon;
                        echo '</div>';
                        */?>
                        <?= $form->field($model, 'periodo_id_periodo')->widget(Select2::classname(), [
                            'data' => $model->periodoLista,
                            'language' => 'es',
                            'theme' => 'default',
                            'options' => ['placeholder' => 'Seleccione Periodo ...'],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ]);
                        ?>
                        <br>
                    </div>
                </div>
            </div>
            <div role="tabpanel" class="tab-pane fade" id="servicio">
                <div class="rowX">
                    <?php DynamicFormWidget::begin([
                        'widgetContainer' => 'dynamicform_wrapper', // required: only alphanumeric characters plus "_" [A-Za-z0-9_]
                        'widgetBody' => '.container-items', // required: css class selector
                        'widgetItem' => '.item', // required: css class
                        //'limit' => 4, // the maximum times, an element can be cloned (default 999)
                        'min' => 1, // 0 or 1 (default 1)
                        'insertButton' => '.add-item', // css class
                        'deleteButton' => '.remove-item', // css class
                        'model' => $modelsServicio[0],
                        'formId' => 'dynamic-form',
                        'formFields' => [
                            'titulo',
                            'descripcion',
                            'perfil_scb',
                            'observacion',
                            //'estado_ejecucion_id_estado',
                            'duracion',
                            'unidad_duracion_id_unidad_duracion'

                        ],
                    ]); ?>
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <i class="glyphicon glyphicon-pencil"></i> Agregar Servicio/s
                            <button type="button" class="pull-right add-item btn btn-success btn-xs"><i class="fa fa-plus"></i> Agregar Servicio</button>
                            <div class="clearfix"></div>
                        </div>
                        <div class="panel-body">
                            <div class="container-items"><!-- widgetContainer -->
                                <?php foreach ($modelsServicio as $i => $modelServicio): ?>
                                    <div class="item panel panel-default"><!-- widgetBody -->
                                        <div class="panel-heading">
                                            <h3 class="panel-title pull-left">Servicio N°<?= ($i + 1) ?></h3>
                                            <div class="pull-right">
                                                <button type="button" class="add-item btn btn-success btn-xs"><i class="glyphicon glyphicon-plus"></i></button>
                                                <button type="button" class="remove-item btn btn-danger btn-xs"><i class="glyphicon glyphicon-minus"></i></button>
                                            </div>
                                            <div class="clearfix"></div>
                                        </div>
                                        <div class="panel-body">
                                            <?php
                                            // necessary for update action.
                                            if (! $modelServicio->isNewRecord) {
                                                echo Html::activeHiddenInput($modelServicio, "[{$i}]id_servicio");
                                            }
                                            ?>
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <?= $form->field($modelServicio, "[{$i}]titulo")->textInput(['maxlength' => true]) ?>
                                                </div>
                                                <div class="col-sm-6">
                                                    <?= $form->field($modelServicio, "[{$i}]descripcion")->textInput(['maxlength' => true]) ?>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <?= $form->field($modelServicio, "[{$i}]duracion")->textInput(['maxlength' => true]) ?>
                                                </div>
                                                <div class="col-sm-6">
                                                    <?= $form->field($modelServicio, "[{$i}]unidad_duracion_id_unidad_duracion") ->dropDownList(
                                                        $modelServicio->unidadDuracionLista,           // Flat array ('id'=>'label')
                                                        ['prompt'=>'Seleccione Unidad de Duración ...']    // options
                                                    );?>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <?= $form->field($modelServicio, "[{$i}]perfil_scb")->textInput(['maxlength' => true]) ?>

                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <?= $form->field($modelServicio, "[{$i}]observacion")->textInput(['maxlength' => true]) ?>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <?php DynamicFormWidget::end(); ?>
                        </div>
                    </div>
                </div>
            </div>

            <div role="tabpanel" class="tab-pane fade" id="settings">...</div>

        </div><!-- TAB CONTENT -->
    </div>



	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Crear' : 'Modificar', ['class' => $model->isNewRecord ? 'btn btn-lg btn-success' : 'btn btn-lg btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>
