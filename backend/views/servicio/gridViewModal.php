<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset; 
use johnitvn\ajaxcrud\BulkButtonWidget;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\ServicioSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Servicios';
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
<div class="servicio-index">
    <div id="ajaxCrudDatatable">
        <input type="hidden" id="modal-id-servicio">
        <?=GridView::widget([
            'id'=>'crud-datatable',
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,

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
            ],
            [
                'class' => 'kartik\grid\SerialColumn',
                'width' => '30px',
            ],
            [
                'class'=>'\kartik\grid\DataColumn',
                'attribute'=>'titulo',
            ],
            [
                'class'=>'\kartik\grid\DataColumn',
                'attribute'=>'descripcion',
            ],
            [
                'class'=>'\kartik\grid\DataColumn',
                'attribute'=>'perfil_scb',
            ],
            [
                'class'=>'\kartik\grid\DataColumn',
                'attribute'=>'observacion',
            ],
            [
                'class' => 'kartik\grid\ActionColumn',
                'dropdown' => false,
                'vAlign'=>'middle',
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
            ]
            ],

            'striped' => true,
            'condensed' => true,
            'responsive' => true,          
            'panel' => [
                'type' => 'primary', 

            ]
        ])?>
    </div>
</div>
<?php Modal::begin([
    "id"=>"ajaxCrudModal",
    "footer"=>"",// always need it for jquery plugin
])?>
<?php Modal::end(); ?>
