<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use kartik\depdrop\DepDrop;
use Yii\helpers\Url;
use yii\helpers\Json;
use yii\bootstrap\Modal;

/* @var $this yii\web\View */
/* @var $model backend\models\asignatura */
/* @var $form yii\widgets\ActiveForm */

$js = <<<JS
    $('#boton-modal-carrera').click(function(){
        $('#header-modal-formularios').text($(this).attr('title'));
        $('#modal-formularios').modal('show')
        .find('#contenido-modal-formularios')
        .load($(this).attr('value'));
    });
JS;

$this->registerJs($js);


?>
<?php
    Modal::begin([
            'header' => '<h4 id="header-modal-formularios"></h4>',
            'id' => 'modal-formularios',
            'size' => 'modal-lg',
    ]);
    echo '<div id="contenido-modal-formularios"></div>';
    Modal::end();
?>
<div class="asignatura-form">
    <div class="row">
        <div class="col-sm-12">
            <?= Html::a('<i class="fa fa-arrow-circle-left"></i> Volver a Lista de Asignaturas', ['/asignatura/index'], ['class' => 'btn btn-info']) ?>
        </div>
    </div>
    <br>

    <?php $form = ActiveForm::begin(); ?>
    <div class="panel panel-primary">
        <div class="panel-heading"><i class="glyphicon glyphicon-pencil"></i> Datos de la Asignatura</div>
        <div class="panel-body">
            <div class="row">
                <div class="col-sm-12">
                    <label class="control-label" for="sede">Sede</label>
                    <?php
                    if (!$model->isNewRecord) {
                        $inicializar = true;
                        //ID SEDE DE LA ASIGNATURA QUE VIENE PARA UPDATEAR
                        $valueSede = $model->carreraCodCarrera->facultadIdFacultad->sedeIdSede->id_sede;
                        echo Html::hiddenInput('input-id_sede', $valueSede, ['id' => 'input-id_sede']);

                        //ID FACULTAD DE LA ASIGNATURA QUE VIENE PARA UPDATEAR
                        echo Html::hiddenInput('input-id_facultad', $model->carreraCodCarrera->facultadIdFacultad->id_facultad, ['id' => 'input-id_facultad']);

                        //ID CARRERA DE LA ASIGNATURA QUE VIENE PARA UPDATEAR
                        echo Html::hiddenInput('input-id_carrera', $model->carrera_cod_carrera, ['id' => 'input-id_carrera']);
                    }else{
                        $inicializar = false;
                        $valueSede = '';
                    }
                    ?>
<!--                    <div class="input-group">
-->                        <?= Select2::widget([
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
                        <!--<span class="input-group-btn">
                        <?/*=Html::button('<i class="glyphicon glyphicon-plus"></i> ',
                            [
                                'type'=>'button',
                                'title'=>'Crear Sede',
                                //'role' => 'modal-remote',
                                'class'=>'btn btn-success',
                                'style' => 'margin-left:10px; border-radius: 3px !important;',
                                'onclick'=>"window.location.href = '" . \Yii::$app->urlManager->createUrl(['sede/create']) . "';",
                            ]);*/?>

                        </span>
                    </div>-->
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-sm-12">
                    <label class="control-label" for="facultad">Facultad</label>
                    <!--<div class="input-group">-->
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
                        <!--<span class="input-group-btn">
                            <?/*=Html::button('<i class="glyphicon glyphicon-plus"></i> ',
                                [
                                    'type'=>'button',
                                    'title'=>'Crear Facultad',
                                    //'role' => 'modal-remote',
                                    'class'=>'btn btn-success',
                                    'style' => 'margin-left:10px; border-radius: 3px !important;',
                                    'onclick'=>"window.location.href = '" . \Yii::$app->urlManager->createUrl(['facultad/create']) . "';",
                                ]);*/?>
                        </span>
                    </div>-->
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-sm-12">
                    <div class="input-group">
                    <?= $form->field($model, 'carrera_cod_carrera')->widget(DepDrop::classname(), [
                        'type'=>DepDrop::TYPE_SELECT2,
                        //'data' => $dataCarrera,
                        'options' => ['id'=> Html::getInputId($model, "carrera_cod_carrera")],
                        'select2Options'=>[
                            'language' => 'es',
                            'theme' => 'default',
                            'pluginOptions'=>['allowClear'=>true],

                        ],
                        'pluginOptions'=>[
                            'depends'=>['inputFacultad'],
                            'placeholder'=>'Seleccione una carrera ...',
                            'url'=>Url::to(['/asignatura/subcarreras']),
                            'initialize' => $inicializar,
                            'params'=>['input-id_carrera']
                        ],
                    ]);?>
                    <span class="input-group-btn">
                        <?=Html::button('<i class="glyphicon glyphicon-plus"></i> ',
                                [
                                    'type'=>'button',
                                    'title'=>'Crear Nueva Carrera',
                                    //'role' => 'modal-remote',
                                    'class'=>'btn btn-success',
                                    'id' => 'boton-modal-carrera',
                                    'value' => Url::to('/carrera/create-ajax'),
                                    'style' => 'margin-left:10px; border-radius: 3px !important; margin-bottom:-14px;',
                                    //'onclick'=>"window.location.href = '" . \Yii::$app->urlManager->createUrl(['carrera/create']) . "';",
                                ]);?>
                    </span>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <?= $form->field($model, 'cod_asignatura')->textInput() ?>
                </div>
                <div class="col-sm-6">
                    <?= $form->field($model, 'nombre_asignatura')->textInput(['maxlength' => true]) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <?php $var = [ 1 => 'Primer Semestre', 2 => 'Segundo Semestre']; ?>
                    <?= $form->field($model, 'semestre_dicta')->dropDownList($var, ['prompt' => 'Seleccione semestre' ]); ?>
                </div>
                <div class="col-sm-6">
                    <?php $var = [
                        1 => 'I Semestre',
                        2 => 'II Semestre',
                        3 => 'III Semestre',
                        4 => 'IV Semestre',
                        5 => 'V Semestre',
                        6 => 'VI Semestre',
                        7 => 'VII Semestre',
                        8 => 'VIII Semestre',
                        9 => 'IX Semestre',
                        10 => 'X Semestre',
                        11 => 'XI Semestre',
                        12 => 'XII Semestre',
                    ]; ?>
                    <?= $form->field($model, 'semestre_malla')->dropDownList($var, ['prompt' => 'Seleccione semestre' ]); ?>
                </div>
            </div>
            <?= $form->field($model, 'resultado_aprendizaje')->textInput(['maxlength' => true])->hint('Utilice comas para separar cada resultado de aprendizaje'); ?>

        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Crear' : 'Modificar', ['class' => $model->isNewRecord ? 'btn btn-success btn-lg' : 'btn btn-primary btn-lg']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
