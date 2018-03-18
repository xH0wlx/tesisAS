<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use wbraganca\tagsinput\TagsinputWidget;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model backend\models\requerimiento */
/* @var $form yii\widgets\ActiveForm */

$js = '
var citynames = new Bloodhound({
    datumTokenizer: Bloodhound.tokenizers.obj.whitespace(\'name\'),
    queryTokenizer: Bloodhound.tokenizers.whitespace,    
    remote: {
    wildcard: \'consulta\',
    url: \''.\Yii::$app->urlManager->createUrl(['match1/service-tag-input?query=']) .'consulta\',
    transform: function(response) {
        // Map the remote source JSON array to a JavaScript object array
        var arr = [];
        response.forEach(function(asignatura) {
            palabras = asignatura.nombre_asignatura.split(" ");
            palabras.forEach(function(palabra) {
                arr.push(palabra);
            });
        });
          return $.map(arr, function(asignatura) {
            return {
              //name: asignatura.nombre_asignatura
              name: asignatura
            };
          });
        }
    }
});
citynames.initialize();

var elt = $(\'#requerimiento-tagvalues\');
elt.tagsinput({
  typeaheadjs: [{
          minLength: 1,
          highlight: true,
    },{
        minlength: 1,
        limit: 6,
        name: \'citynames\',
        displayKey: \'name\',
        valueKey: \'name\',
        source: citynames.ttAdapter()
    }],
    freeInput: true
});
';

$this->registerJs($js);
?>

<div class="requerimiento-form">
    <?php $form = ActiveForm::begin(); ?>
    <div class="panel panel-primary">
        <div class="panel-heading"><i class="glyphicon glyphicon-pencil"></i> Datos del Requerimiento</div>
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

            <?= $form->field($model, 'titulo')->textInput(['maxlength' => true]) ?>
            <div class="row">
                <div class="col-sm-6">
                    <?= $form->field($model, 'descripcion')->textarea(['maxlength' => true]) ?>
                </div>
                <div class="col-sm-6">
                    <?= $form->field($model, 'apoyo_brindado')->textarea(['maxlength' => true]) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <?= $form->field($model, 'perfil_estudiante')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-sm-6">
                    <?= $form->field($model, 'observacion')->textInput(['maxlength' => true]) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <?= $form->field($model, 'cantidad_aprox_beneficiarios')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-sm-6">
                    <?= $form->field($model, 'tagValues')->widget(TagsinputWidget::classname(), [
                        'options' => [
                            'class' => 'form-control',
                        ],
                        'clientOptions' => [
                            'trimValue' => true,
                            'allowDuplicates' => false,
                            /* 'onTagExists' => function(item, $tag) {
                                 $tag.hide().fadeIn();
                             }*/
                        ],
                    ])->hint('Use comas para separar las Palabras Claves')?>                    </div>
            </div>

            <?php if (!Yii::$app->request->isAjax){ ?>
                <div class="form-group">
                    <?= Html::submitButton($model->isNewRecord ? 'Crear' : 'Modificar', ['class' => $model->isNewRecord ? 'btn btn-lg btn-success' : 'btn btn-lg btn-primary']) ?>
                </div>
            <?php } ?>

        </div>
    </div>




    <?php ActiveForm::end(); ?>

</div>
