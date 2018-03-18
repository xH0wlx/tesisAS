<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset;
use johnitvn\ajaxcrud\BulkButtonWidget;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\ServicioSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Selección de Requerimientos';
$this->params['breadcrumbs'][] = ['label' => 'Principal', 'url' => ['/servicio/match-asociado']];
$this->params['breadcrumbs'][] = $this->title;

CrudAsset::register($this);

$js = '
$(\'.crud-datatable\').on("click", function(event){
    $.ajax({
       url: \''. Yii::$app->request->baseUrl.'/servicio/clonar-servicio'.'\',
       type: \'post\',
       data: {
                 requerimientoPadre: event.target.value,
                 _csrf : \''.  Yii::$app->request->getCsrfToken().'\'
             },
       success: function (data) {
        if(data.code == 100){
            alert("HOLA");
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

$(\'.kv-row-checkbox\').change(function(e) {
    var idServicio = $(this).closest("tr").attr("data-key");
    $("#modal-id-servicio").val(idServicio);
    $(\'#modal-servicio\').modal(\'hide\');
});
';

$this->registerJs($js);
?>

<?php $form = ActiveForm::begin(); ?>
    <div class="box box-primary">
        <div class="box-heading">Periodo</div>
        <div class="box-body">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <?= $form->field($modeloPeriodo, 'anio')->textInput() ?>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <?php $var = [ 1 => 'Primer Semestre', 2 => 'Segundo Semestre']; ?>
                    <?= $form->field($modeloPeriodo, 'semestre')->dropDownList($var, ['prompt' => 'Seleccione semestre' ]); ?>
                </div>
            </div>
            <div class="form-group">
                <?= Html::submitButton('Enviar', ['class' => 'btn btn-success']) ?>
            </div>
        </div>
    </div>

<?php ActiveForm::end(); ?>

    <div class="alert alert-info alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h4><i class="icon fa fa-info"></i> Información</h4>
        A continuación se presenta la lista obtenida en el proceso de <b>Match</b> (Asignación de asignaturas candidatas por Requerimiento),
        ordenada por asignatura.<br> Por lo tanto, por cada asignatura usted debe agregar un <b>Servicio</b> (Si lo tuviere), seleccionando el
        o los Requerimientos (en caso de que un servicio satisfaga 2 o más Requerimientos) y presionar <b>Siguiente</b>.
    </div>

    <div class="servicio-index">
        <div id="ajaxCrudDatatable">
            <input type="hidden" id="modal-id-servicio">
            <?=GridView::widget([
                'id'=>'crud-datatable',
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'striped' => true,
                'hover' => true,
                'condensed' => true,
                'responsive' => true,
                'panel' => [
                    'type' => 'primary',
                    'heading' => '<i class="glyphicon glyphicon-list"></i> Lista',

                ],
                //'rowOptions' => ['style' => 'border: 10px solid black !important;'],
                'pjax'=>true,
                'pjaxSettings'=>[
                    'options' => [
                        'id' => 'pjax-grid-view-modal',
                        //'neverTimeout'=>true,
                        //'linkSelector' => '#'.$modelRequerimiento->id_requerimiento.' a:not([rel=fancybox], .item-link)',
                        'enablePushState' => false
                    ],
                ],
                'columns' => [

                    [
                        'class' => 'kartik\grid\CheckboxColumn',
                        'width' => '20px',
                        'rowSelectedClass' => GridView::TYPE_DANGER,

                    ],
                    [
                        'class' => 'kartik\grid\SerialColumn',
                        'width' => '30px',
                    ],
                    [
                        'class'=>'\kartik\grid\DataColumn',
                        'attribute'=>'asignatura_cod_asignatura',
                        'vAlign' => \kartik\grid\GridView::ALIGN_MIDDLE,
                        'group' => true,
                    ],
                    [
                        'class'=>'\kartik\grid\DataColumn',
                        'label' => 'Nombre de la Asignatura',
                        'attribute'=>'asignaturaCodAsignatura.nombre_asignatura',
                        'vAlign' => \kartik\grid\GridView::ALIGN_MIDDLE,
                        'group' => true,
                        'subGroupOf' => 3,
                    ],
                    [
                        'class'=>'\kartik\grid\DataColumn',
                        'attribute'=>'asignaturaCodAsignatura.semestre_dicta',
                        'vAlign' => \kartik\grid\GridView::ALIGN_MIDDLE,
                        'group' => true,
                        'subGroupOf' => 3,
                    ],
                    [
                        'class'=>'\kartik\grid\DataColumn',
                        'attribute'=>'asignaturaCodAsignatura.carreraCodCarrera.facultadIdFacultad.sedeIdSede.nombre_sede',
                        'vAlign' => \kartik\grid\GridView::ALIGN_MIDDLE,
                        'group' => true,
                        'subGroupOf' => 3,
                    ],
                    [
                        'class'=>'\kartik\grid\DataColumn',
                        'label' => 'Socio Inst.',
                        'attribute'=>'requerimientoIdRequerimiento.sciIdSci.nombreComuna',
                        'vAlign' => \kartik\grid\GridView::ALIGN_MIDDLE,
                        'group'=>true,
                        'subGroupOf' => 3,
                    ],
                    [
                        'class'=>'\kartik\grid\DataColumn',
                        'label' => 'Requerimiento',
                        'attribute'=>'requerimientoIdRequerimiento.titulo',
                        //'group'=>true,
                        'vAlign' => \kartik\grid\GridView::ALIGN_MIDDLE,
                        'subGroupOf'=>3,
                    ],
                    [
                        'class'=>'\kartik\grid\DataColumn',
                        'label' => 'Descripción del Requerimiento',
                        'attribute'=>'requerimientoIdRequerimiento.descripcion',
                        //'group'=>true,
                        'vAlign' => \kartik\grid\GridView::ALIGN_MIDDLE,
                        'subGroupOf'=>4,
                    ],
                    [
                        'class'=>'\kartik\grid\DataColumn',
                        'attribute'=>'requerimientoIdRequerimiento.cantidad_aprox_beneficiarios',
                        //'group'=>true,
                        'vAlign' => \kartik\grid\GridView::ALIGN_MIDDLE,
                        'subGroupOf'=>5,
                    ],
                    [
                        'class'=>'\kartik\grid\DataColumn',
                        'attribute'=>'anio_match1',
                    ],
                    [
                        'class'=>'\kartik\grid\DataColumn',
                        'attribute'=>'semestre_match1',
                    ],
                    [
                        'class' => 'kartik\grid\ActionColumn',
                        'dropdown' => false,
                        //'mergeHeader'=>true,
                        'vAlign' => \kartik\grid\GridView::ALIGN_MIDDLE,
                        'urlCreator' => function($action, $model, $key, $index) {
                            return Url::to([$action,'id'=>$key]);
                        },
                        'template' => '{view}',
                        'viewOptions'=>['role'=>'modal-remote','title'=>'Ver','data-toggle'=>'tooltip'],
                        'updateOptions'=>['role'=>'modal-remote','title'=>'Modificar', 'data-toggle'=>'tooltip'],
                        'deleteOptions'=>['role'=>'modal-remote','title'=>'Eliminar',
                            'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
                            'data-request-method'=>'post',
                            'data-toggle'=>'tooltip',
                            'data-confirm-title'=>'Confirmación',
                            'data-confirm-message'=>'Está seguro que desea eliminar este registro?'],
                    ],
                    [
                        'mergeHeader'=>true,
                        'header' => 'Seleccionar',
                        'vAlign' => \kartik\grid\GridView::ALIGN_MIDDLE,
                        //'headerOptions' => ['style' => 'color:#337ab7'],
                        'content' => function($model) {
                            return Html::a('<i class="fa fa-arrow-circle-right"></i> ', ['/servicio/create-provisorio', ['id'=> $model->id_match1,
                                'anio' =>Yii::$app->request->get('anio'), 'semestre' => Yii::$app->request->get('semestre')]], ['data-pjax'=> 0,'class' => 'btn btn-info']);
                        },
                        'contentOptions' => ['class' => 'text-center'],
                    ],
                ],


            ])?>
        </div>
    </div>
<?php Modal::begin([
    "id"=>"ajaxCrudModal",
    "footer"=>"",// always need it for jquery plugin
])?>
<?php Modal::end(); ?>