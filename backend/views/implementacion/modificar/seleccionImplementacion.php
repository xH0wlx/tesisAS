<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset;
use johnitvn\ajaxcrud\BulkButtonWidget;
use kartik\date\DatePicker;
use kartik\growl\GrowlAsset;
use kartik\base\AnimateAsset;
use kartik\editable\Editable;


/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\ImplementacionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Modificar Implementación';
$this->params['breadcrumbs'][] = $this->title;

CrudAsset::register($this);
GrowlAsset::register($this);
AnimateAsset::register($this);

$js = '
$(\'.kv-row-checkbox\').change(function(e) {
    asignaturaSeleccionada = $(\'#crud-datatable\').yiiGridView(\'getSelectedRows\');
    alert(asignaturaSeleccionada);
});

var $grid = $(\'#crud-datatable\');
 
$grid.on(\'grid.radiochecked\', function(ev, key, val) {
    //if(confirm("Confirma que desea modificar la implementacion código: "+key)){
    //    window.location.replace("/implementacion/panel-implementacion?idImplementacion="+key);
    //}
   //$.notify({message: "Asignatura Seleccionada"}, {});
   //krajeeDialog.alert("Alert traducido ;D");
});

';
$this->registerJs($js);
?>
    <div class="panel panel-primary">
    <div class="panel-heading">Selección de Periodo Implementación</div>
        <div class="panel-body">
            <?php $form = ActiveForm::begin(); ?>

            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($modeloPeriodo, 'anio')->textInput() ?>
                </div>
                <div class="col-md-6">
                    <?php $var = [ 1 => 'Primer Semestre', 2 => 'Segundo Semestre']; ?>
                    <?= $form->field($modeloPeriodo, 'semestre')->dropDownList($var, ['prompt' => 'Seleccione semestre' ]); ?>
                </div>
            </div>

            <div class="form-group">
                <?= Html::submitButton('Buscar', ['class' => 'btn btn-primary']) ?>
            </div>

            <?php
                    ActiveForm::end();
            ?>
        </div>
    </div>

<?php

    if(isset($dataProvider)){
        if($dataProvider->getTotalCount() != 0){
            $boton = true;
        }else{
            $boton = false;
        }
?>
    <h2 style="margin-bottom: 20px;">Lista de implementaciones creadas</h2>
        <div class="implementacion-index">
            <div id="ajaxCrudDatatable">
                <?php $form2 = ActiveForm::begin(); ?>
                <?=GridView::widget([
                    'id' => 'crud-datatable',
                    'dataProvider' => $dataProvider,
                    'pjax'=>true,
                    'panelBeforeTemplate' => '',
                    'columns' => [
                        [
                            'class' => 'kartik\grid\RadioColumn',
                            //'multiple' => false,
                            //'rowSelectedClass' => GridView::TYPE_SUCCESS,
                            //'width' => '20px',
                        ],
                        [
                            'class' => 'kartik\grid\SerialColumn',
                            'width' => '30px',
                        ],
                        [
                            'class'=>'\kartik\grid\DataColumn',
                            'attribute'=>'asignatura_cod_asignatura',
                        ],
                        [
                            'class'=>'\kartik\grid\DataColumn',
                            'attribute'=>'nombre_asignatura',
                            'label' => 'Nombre Asignatura',
                            'value'=>function ($model, $index, $widget) { return $model->asignaturaCodAsignatura->nombre_asignatura; }
                        ],
                        [
                            'class'=>'\kartik\grid\DataColumn',
                            'attribute'=>'anio_implementacion',
                        ],
                        [
                            'class'=>'\kartik\grid\DataColumn',
                            'attribute'=>'semestre_implementacion',
                        ],
                        [
                            'class'=>'\kartik\grid\DataColumn',
                            //'attribute'=>'estado',
                            'label' => 'Sede UBB',
                            'value'=>function ($model, $index, $widget) {
                                return $model->asignaturaCodAsignatura->carreraCodCarrera->facultadIdFacultad->sedeIdSede->nombre_sede;
                            }
                        ],
                        [
                            'class'=>'\kartik\grid\EditableColumn',
                            'attribute'=>'estado',
                            'editableOptions'=> function ($model, $key, $index) {
                                return [
                                    'format' => Editable::FORMAT_BUTTON,
                                    'inputType' => Editable::INPUT_DROPDOWN_LIST,
                                    'data'=>$model->estadoLista, // any list of values
                                    'options' => ['class'=>'form-control', 'prompt'=>'Seleccione Estado ...'],
                                    'editableValueOptions'=>['class'=>'text-danger'],
                                ];
                            },
                            'value'=>function ($model, $index, $widget) {
                                $aRetornar = "";
                                if($model->estado == 0){
                                    $aRetornar = "Creada";
                                }else if($model->estado == 1){
                                    $aRetornar = "En Curso";
                                }else if($model->estado == 2){
                                    $aRetornar = "Finalizada";
                                }
                                return $aRetornar;
                            }
                        ],
                        [
                            'class'=>'\kartik\grid\DataColumn',
                            'attribute'=>'estado',
                            //'label' => 'Nombre Asignatura',
                            'value'=>function ($model, $index, $widget) {
                                $aRetornar = "";
                                if($model->estado == 0){
                                    $aRetornar = "Creada";
                                }else if($model->estado == 1){
                                    $aRetornar = "En Curso";
                                }else if($model->estado == 2){
                                    $aRetornar = "Finalizada";
                                }
                                return $aRetornar;
                            }
                        ],
                        [
                            'class'=>'\kartik\grid\DataColumn',
                            'format'=> 'html',
                            'label' => ' ',
                            'value'=>function ($model, $index, $widget) {
                                return "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                            }
                        ],
                    ],
                    'striped' => true,
                    'condensed' => true,
                    'responsive' => true,
                    'panel' => [
                        'type' => 'primary',
                        'heading' => '<i class="glyphicon glyphicon-list"></i> Lista de implementaciones',
                        'footer' => $boton? Html::submitButton('Siguiente', ['name'=> 'seleccion', 'value' => 'true','class' => 'pull-right btn btn-success']) :'',
                        //'footer' => Html::submitButton('Siguiente', ['name'=> 'seleccion', 'value' => 'true','class' => 'pull-right btn btn-success']),
                    ]
                ])?>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
<?php
    }//FIN IF DATAPROVIDER
?>


<?php Modal::begin([
    "id"=>"ajaxCrudModal",
    "footer"=>"",// always need it for jquery plugin
])?>
<?php Modal::end(); ?>