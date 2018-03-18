<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use kartik\depdrop\DepDrop;
use yii\helpers\Url;
use yii\bootstrap\Modal;
use wbraganca\dynamicform\DynamicFormWidget;
use johnitvn\ajaxcrud\CrudAsset;
use johnitvn\ajaxcrud\BulkButtonWidget;
use yii\web\JsExpression;

CrudAsset::register($this);

/* @var $this yii\web\View */
/* @var $model backend\models\servicio */
/* @var $form yii\widgets\ActiveForm */

//ruta select2
$listaDocenteAjax = \yii\helpers\Url::to(['/docente/lista-docente-ajax']);

$js = '
$(\'.clonar\').on("click", function(event){
    $.ajax({
       url: \''. Yii::$app->request->baseUrl.'/servicio/clonar-servicio'.'\',
       type: \'post\',
       data: {
                 requerimientoPadre: event.target.value,
                 _csrf : \''.  Yii::$app->request->getCsrfToken().'\'
             },
       success: function (data) {
        if(data.code == 100){
           
        }
        if(data.code == 200){
            alert("Código de Asignatura: "+data.codAsignatura+"\n Ya fue preseleccionada para este requerimiento en el periodo seleccionado.");
        }
       },
       error: function (data){
           alert("Error al guardar en la base de datos, contacte al creador del sistema para mayor información.");
       }
  });//FIN AJAX
});

$(\'.boton-modal-servicio\').click(function(){
        $(\'#header-modal-servicio\').text($(this).attr(\'title\'));
        $(\'#modal-servicio\').modal(\'show\')
        .find(\'#contenido-modal-servicio\')
        .load($(this).attr(\'value\'));
});

$(\'#modal-servicio\').on(\'hide.bs.modal\', function () {    
        
  $.ajax({
       url: \''. Yii::$app->request->baseUrl.'/servicio/clonar-servicio'.'\',
       type: \'post\',
       data: {
                 idServicio: $("#modal-id-servicio").val(),
                 _csrf : \''.  Yii::$app->request->getCsrfToken().'\'
             },
       success: function (data) {
        if(data.code == 100){
            $("#servicio-asignatura_cod_asignatura").val(data.modelServicio.asignatura_cod_asignatura).trigger("change");
            $("#servicio-titulo").val(data.modelServicio.titulo);
            $("#servicio-descripcion").val(data.modelServicio.descripcion);
            $("#servicio-perfil_scb").val(data.modelServicio.perfil_scb);
            $("#servicio-observacion").val(data.modelServicio.observacion);
            $("#servicio-duracion").val(data.modelServicio.duracion);
            $("#servicio-unidad_duracion_id_unidad_duracion").val(data.modelServicio.unidad_duracion_id_unidad_duracion).trigger("change");
        }
        if(data.code == 200){
            alert("Error al clonar (controller)");
        }
       },
       error: function (data){
           alert("Error al clonar.");
       }
  });//FIN AJAX    
})

';

$this->registerJs($js);

?>

<?php
Modal::begin([
    'header' => '<h4 id="header-modal-servicio"></h4>',
    'id' => 'modal-servicio',
    'size' => 'modal-lg',
]);
echo '<div id="contenido-modal-servicio"></div>';
Modal::end();
?>
<div class="servicio-form">
    <?=Html::button('<i class="glyphicon glyphicon-copy"></i> Clonar',
        [
            'type'=>'button',
            'title'=>'Seleccione servicio a clonar',
            //'role' => 'modal-remote',
            'class'=>'boton-modal-servicio  btn btn-primary',
            'value' => Url::to('/servicio/grid-view-modal'),
            //'onclick'=>"window.location.href = '" . \Yii::$app->urlManager->createUrl(['carrera/create']) . "';",
        ]);?>
    <br><br>
    <div class="panel panel-primary">
        <div class="panel-heading"><i class="glyphicon glyphicon-pencil"></i> Datos del Servicio</div>
        <div class="panel-body">
            <?php $form = ActiveForm::begin(['id' => 'dynamic-form']); ?>

           <!-- <div class="row">
                <div class="col-sm-12">
                    <label class="control-label" for="sede">Sede</label>
                    <?php
/*                    if (!$model->isNewRecord) {
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
                    */?>
                        <?/*= Select2::widget([
                            'name' => 'sede',
                            'data' => $model->sedeLista,
                            'value' => $valueSede,
                            'language' => 'es',
                            'theme' => 'default',
                            'options' => ['id' => 'inputSede', 'placeholder' => 'Seleccione Sede ...'],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ]); */?>

                </div>
            </div>
            <br>

            <div class="row">
                <div class="col-sm-12">
                    <label class="control-label" for="facultad">Facultad</label>

                        <?/*= DepDrop::widget([
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
                        */?>

                </div>
            </div>
            <br>

            <div class="row">
                <div class="col-sm-12">
                    <label class="control-label" for="carrera">Carrera</label>

                        <?/*= DepDrop::widget([
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
                        */?>

                </div>
            </div>
            <br>

            <div class="row">
                <div class="col-sm-12">

                        <?/*= $form->field($model, 'asignatura_cod_asignatura')->widget(DepDrop::classname(), [
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
                        ]);*/?>

                </div>
            </div>-->

            <?php
            // necessary for update action.
            if (! $model->isNewRecord) {
                echo Html::activeHiddenInput($model, "id_servicio");
            }
            ?>
            <?php if($model->asignatura_cod_asignatura != null){
                echo $form->field($model, 'asignatura_cod_asignatura')->hiddenInput(['maxlength' => true])->label(false);
            }else{
                echo $form->field($model, "asignatura_cod_asignatura")->widget(Select2::classname(), [
                    'data' => $model->asignaturaLista,
                    'language' => 'es',
                    'theme' => 'default',
                    'options' => ['placeholder' => 'Seleccione Asignatura ...'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]);
            } ?>

            <?php DynamicFormWidget::begin([
                'widgetContainer' => 'dynamicform_wrapper3', // required: only alphanumeric characters plus "_" [A-Za-z0-9_]
                'widgetBody' => '.container-items3', // required: css class selector
                'widgetItem' => '.item3', // required: css class
                //'limit' => 4, // the maximum times, an element can be cloned (default 999)
                'min' => 1, // 0 or 1 (default 1)
                'insertButton' => '.add-item3', // css class
                'deleteButton' => '.remove-item3', // css class
                'model' => $docentesHasServicio[0],
                'formId' => 'dynamic-form',
                'formFields' => [
                    'docente_rut_docente',
                ],
            ]); ?>
            <div class="container-items3"><!-- widgetContainer -->
                <?php foreach ($docentesHasServicio as $i => $docenteHasServicio): ?>
                    <div class="item3 panel panel-default"><!-- widgetBody -->
                        <div class="panel-body">
                            <?php
                            $nombreDocente = empty($docenteHasServicio->docente_rut_docente) ? '' : \backend\models\Docente::findOne($docenteHasServicio->docente_rut_docente)->nombre_completo;
                            // necessary for update action.
                            if (! $docenteHasServicio->isNewRecord) {
                                echo Html::activeHiddenInput($docenteHasServicio, "[{$i}]servicio_id_servicio");
                            }
                            ?>
                            <div class="pull-right">
                                <?=  Html::a('<i class="glyphicon glyphicon-plus"></i> Crear Docente si no está en la lista', Url::to(['/docente/crear-en-formulario']),
                                    ['data-pjax'=>1,'role'=>'modal-remote','title'=> 'Crer nuevo Docente','class'=>'btn btn-primary']) ?>
                                <button type="button" class="add-item3 btn btn-success btn-md"><i class="glyphicon glyphicon-plus"></i> Agregar Otro Docente</button>
                                <button type="button" class="remove-item3 btn btn-danger btn-md"><i class="glyphicon glyphicon-minus"></i>  Quitar Docente</button>
                            </div>
                            <div class="clearfix"></div>
                            <?= $form->field($docenteHasServicio, "[{$i}]docente_rut_docente")->widget(Select2::classname(), [
                                //'data' => $model->docenteLista,
                                'initValueText' => $nombreDocente,
                                'language' => 'es',
                                'theme' => 'default',
                                'options' => ['placeholder' => 'Seleccione Docente ...'],
                                'pluginOptions' => [
                                    'allowClear' => true,
                                    'minimumInputLength' => 3,
                                    'language' => 'es',
                                    'ajax' => [
                                        'url' => $listaDocenteAjax,
                                        'dataType' => 'json',
                                        'data' => new JsExpression('function(params) { return {q:params.term}; }'),
                                    ],
                                    'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                                    'templateResult' => new JsExpression('function(docente) { return docente.text; }'),
                                    'templateSelection' => new JsExpression('function (docente) { return docente.text; }'),
                                ],

                            ]);?>
                        </div>
                    </div>
                <?php endforeach; ?>
                <?php DynamicFormWidget::end(); ?>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <?= $form->field($model, 'titulo')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-sm-6">
                    <?= $form->field($model, 'descripcion')->textarea(['maxlength' => true]) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <?= $form->field($model, 'perfil_scb')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-sm-6">
                    <?= $form->field($model, 'observacion')->textInput(['maxlength' => true]) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <?= $form->field($model, 'duracion')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-sm-6">
                    <?= $form->field($model, 'unidad_duracion_id_unidad_duracion')->dropDownList(
                        $model->unidadDuracionLista,           // Flat array ('id'=>'label')
                        ['prompt'=>'Seleccione Unidad de Duración ...']    // options
                    ); ?>
                </div>
            </div>




            <!--<?= $form->field($model, 'estado_ejecucion_id_estado')->textInput(['maxlength' => true]) ?>-->





            <?php if (!Yii::$app->request->isAjax){ ?>
                <div class="form-group">
                    <?= Html::submitButton($model->isNewRecord ? 'Crear' : 'Modificar', ['class' => $model->isNewRecord ? 'btn btn-lg btn-success' : 'btn btn-lg btn-primary']) ?>
                </div>
            <?php } ?>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
    
</div>

<?php Modal::begin([
    "id"=>"ajaxCrudModal",
    "footer"=>"",// always need it for jquery plugin
])?>
<?php Modal::end(); ?>