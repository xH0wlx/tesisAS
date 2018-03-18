<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset;
use johnitvn\ajaxcrud\BulkButtonWidget;
use yii\widgets\ActiveForm;
use kartik\dialog\Dialog;
use kartik\growl\GrowlAsset;
use kartik\base\AnimateAsset;
GrowlAsset::register($this);
AnimateAsset::register($this);
/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\ServicioSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Selección de Requerimientos';
$this->params['breadcrumbs'][] = ['label' => 'Selección de periodo', 'url' => ['/servicio/match-asociado']];
$this->params['breadcrumbs'][] = $this->title;

CrudAsset::register($this);

$session = Yii::$app->session;
$session->open();
if(isset($session["comprobacionReglaServicio"]) && Yii::$app->request->isGet){
    $session->remove('comprobacionReglaServicio');
}
$session->close();

$js = '
/////FUNCIONES//////
//FUNCIONES
function accionEliminarSeleccionado(selector){
         var idMatch = selector.val();
         $.ajax({
               url: \''. Yii::$app->request->baseUrl.'/servicio/eliminar-seleccionado'.'\',
               type: \'post\',
               data: {
                         idMatch: idMatch,
                         _csrf : \''.  Yii::$app->request->getCsrfToken().'\'
                     },
               success: function (data) {
                    if(data.texto == "exito"){
                        $.notify({message: "Eliminado."},{type: "danger", delay:"100", timer:"700"});
                    }
               },
               error: function (data){
                   alert(data);
               },
        });//FIN AJAX
}

var eventoCheckboxPropio = function eventoClickCheckbox(){
        var cajita = $(this);
        if($(this).is(\':checked\')){
            //var idAsignaturasSeleccionadas = $(\'#crud-datatable\').yiiGridView(\'getSelectedRows\');
            $.ajax({
               url: \''. Yii::$app->request->baseUrl.'/servicio/1-asignatura-n-requerimiento'.'\',
               type: \'post\',
               data: {
                         idMatch: cajita.val(),
                         _csrf : \''.  Yii::$app->request->getCsrfToken().'\'
                     },
               success: function (data) {
                    if(data.codigo == "exito"){
                        //SIGNIFICA QUE SE SELECCIONÓ UNO CON LA MISMA ID
                        $.notify({message: "Seleccionado"},{type: "success", delay:"100", timer:"700"});
                    }else{
                        if(data.codigo == "error" && data.codigoNumero == 3){
                            //SIGNIFICA QUE SELECCIONÓ OTRA ID
                            krajeeDialog.confirm(data.texto, function (result) {
                                if (result) { // ok button was pressed
                                   $.ajax({
                                       url: \''. Yii::$app->request->baseUrl.'/servicio/1-asignatura-n-requerimiento'.'\',
                                       type: \'post\',
                                       data: {
                                                 idMatch: cajita.val(),
                                                 reemplazar: 1,
                                                 _csrf : \''.  Yii::$app->request->getCsrfToken().'\'
                                             },
                                       success: function (data) {
                                            if(data.codigo == "exito"){
                                                $( "tr" ).each(function( index ) {
                                                      $("tr > td > .kv-row-checkbox").prop(\'checked\', false); 
                                                      $(this).removeClass("success");
                                                });
                                                cajita.prop(\'checked\', true); 
                                                cajita.addClass("success");
                                            }
                                       },
                                       error: function (data){
                                           alert("Error AJAX");
                                       },
                                    }); // FIN AJAX
                                } else { // confirmation was cancelled
                                   cajita.prop(\'checked\', false); 
                                   cajita.closest(\'tr\').removeClass("success");
                                }
                            });
                        }
                    }
               },
               error: function (data){
                   alert("Error AJAX");
               },
            }); // FIN AJAX
            
        }else{
            accionEliminarSeleccionado($(this));
        }
};

function setEventoChangeCheckbox(){
    $(\'.kv-row-checkbox\').off("change", eventoCheckboxPropio);
    $(\'.kv-row-checkbox\').on("change", eventoCheckboxPropio);
}//FIN FUNCION

function setBotonSiguiente(){
    $(\'.siguiente\').on("click", function(event){
        var boton = $(this);
        event.preventDefault();
            $.ajax({
               url: \''. Yii::$app->request->baseUrl.'/servicio/esta-vacio-seleccion'.'\',
               type: \'post\',
               data: {
                         _csrf : \''.  Yii::$app->request->getCsrfToken().'\'
                     },
               success: function (data) {
                    if(data.texto == "exito"){
                        window.location.replace(boton.attr(\'href\'));
                    }else{
                        krajeeDialog.alert("Debe seleccionar las Asignaturas a las que le asignará el Servicio.");
                    }
               },
               error: function (data){
                   alert("Error AJAX");
               },
            }); // FIN AJAX
        //window.location.replace("'.Url::toRoute('/servicio/create-provisorio?anio&semestre').'");
    });
}

function setBotonesGrid(){
    setBotonSiguiente();
}

$(\'#pjax-grid-asignatura-requerimiento\').on(\'pjax:success\', function() {
    setEventoChangeCheckbox();
    setBotonesGrid(); 
});

setBotonesGrid();
setEventoChangeCheckbox();

////////////////////////////////////////////////////////////////////////////////////////////
';

$this->registerJs($js);

echo Dialog::widget([]);


?>

<?php $form = ActiveForm::begin(); ?>
<!--    <div class="box box-primary">
        <div class="box-heading">Periodo</div>
        <div class="box-body">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <?/*= $form->field($modeloPeriodo, 'anio')->textInput() */?>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <?php /*$var = [ 1 => 'Primer Semestre', 2 => 'Segundo Semestre']; */?>
                    <?/*= $form->field($modeloPeriodo, 'semestre')->dropDownList($var, ['prompt' => 'Seleccione semestre' ]); */?>
                </div>
            </div>
            <div class="form-group">
                <?/*= Html::submitButton('Enviar', ['class' => 'btn btn-success']) */?>
            </div>
        </div>
    </div>-->

<?php ActiveForm::end(); ?>

    <div class="alert alert-info alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h4><i class="icon fa fa-info"></i> Información</h4>
        A continuación se presenta la lista obtenida en el proceso de <b>Match 1</b>.
        <br> Por cada asignatura usted debe agregar un <b>Servicio</b> (según corresponda), seleccionando el
        o los Requerimientos (que pertenezcan a la misma asignatura) y presionar <b>Siguiente</b>.
        <br><br><b>Importante:</b>
        <br> Si no se encontraron resultados, quiere decir que no existen requerimientos que posean asignaturas candidatas.
    </div>

    <div class="servicio-index">
        <div id="ajaxCrudDatatable">
            <input type="hidden" id="modal-id-servicio">

            <?php $session->open(); ?>
            <?=GridView::widget([
                'id'=>'crud-datatable',
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                /*'rowOptions' => function ($model, $index, $widget, $grid) use ($soloIdesOcupadas){
                    if(in_array($model->id_match1, $soloIdesOcupadas) && ($model->servicio_id_servicio == NULL)){
                        return ['class' => 'danger'];
                    }else if(in_array($model->id_match1, $soloIdesOcupadas) && ($model->servicio_id_servicio != NULL)){
                        return ['class' => 'warning'];
                    }else{
                        return [];
                    }
                },*/
                'striped' => false,
                'hover' => true,
                'condensed' => true,
                'responsive' => true,
                'panel' => [
                    'type' => 'primary',
                    'heading' => '<i class="glyphicon glyphicon-list"></i> Listado de Asignaturas y Requerimientos',
                    'footer'=>Html::a('Siguiente <i class="fa fa-arrow-circle-right"></i>', ['/servicio/create-provisorio',
                            'anio' =>Yii::$app->request->get('anio'), 'semestre' => Yii::$app->request->get('semestre')], ['data-pjax'=>0,'class' => 'siguiente pull-right btn btn-lg btn-success']),
                ],
                //'rowOptions' => ['style' => 'border: 10px solid black !important;'],
                'pjax'=>true,
                'pjaxSettings'=>[
                    'options' => [
                        'id' => 'pjax-grid-asignatura-requerimiento',
                        //'neverTimeout'=>true,
                        //'linkSelector' => '#'.$modelRequerimiento->id_requerimiento.' a:not([rel=fancybox], .item-link)',
                        'enablePushState' => false
                    ],
                ],
                'columns' => [
                    [
                        'class' => '\kartik\grid\CheckboxColumn',
                        //DEBIDO A QUE SE REMOVERAN LAS FILAS QUE NO SE PUEDEN SELECCIONAR
                        /*'checkboxOptions' => function($model, $key, $index, $column) use ($soloIdesOcupadas, $session){
                            if(in_array($model->id_match1, $soloIdesOcupadas) && ($model->servicio_id_servicio == NULL)){
                                return ['disabled' => 'true'];
                            }else if(in_array($model->id_match1, $soloIdesOcupadas) && ($model->servicio_id_servicio != NULL)){
                                return ['disabled' => 'true'];
                            }else{
                                return [];
                            }
                        },*/
                        //'name' => $modelRequerimiento->id_requerimiento,
                        'rowSelectedClass' => GridView::TYPE_SUCCESS,
                    ],
                /*    [
                        'class' => 'kartik\grid\CheckboxColumn',
                        'width' => '20px',
                        'rowSelectedClass' => GridView::TYPE_SUCCESS,

                    ],*/
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
                        'attribute'=>'asignatura_nombre',
                        'value'=>function ($model, $key, $index, $column) {
                            return $model->asignaturaCodAsignatura->nombre_asignatura;
                        },
                        'vAlign' => \kartik\grid\GridView::ALIGN_MIDDLE,
                        'contentOptions' =>
                            [
                                'style'=>'width: 300px; overflow: auto; word-wrap: break-word; white-space: pre-wrap;'
                            ],
                        'group' => true,
                        'subGroupOf' => 2,
                    ],
                    [
                        'class'=>'\kartik\grid\DataColumn',
                        'attribute'=>'asignatura_semestre_dicta',
                        'value'=>function ($model, $key, $index, $column) {
                            return $model->asignaturaCodAsignatura->semestre_dicta;
                        },
                        'vAlign' => \kartik\grid\GridView::ALIGN_MIDDLE,
                        'group' => true,
                        'subGroupOf' => 3,
                    ],
                    [
                        'class'=>'\kartik\grid\DataColumn',
                        'attribute'=>'asignatura_sede',
                        'value'=>function ($model, $key, $index, $column) {
                            return $model->asignaturaCodAsignatura->carreraCodCarrera->facultadIdFacultad->sedeIdSede->nombre_sede;
                        },
                        'vAlign' => \kartik\grid\GridView::ALIGN_MIDDLE,
                        'group' => true,
                        'subGroupOf' => 3,
                    ],
                    [
                        'class'=>'\kartik\grid\DataColumn',
                        'label' => 'Título del Requerimiento',
                        'attribute'=>'requerimiento_titulo',
                        'value'=>function ($model, $key, $index, $column) {
                            return $model->requerimientoIdRequerimiento->titulo;
                        },
                        //'group'=>true,
                        'contentOptions' =>
                            [
                                'style'=>'width: 300px; overflow: auto; word-wrap: break-word; white-space: pre-wrap;'
                            ],
                        'vAlign' => \kartik\grid\GridView::ALIGN_MIDDLE,
                        'subGroupOf'=>3,
                    ],
                    [
                        'class'=>'\kartik\grid\DataColumn',
                        'label' => 'Descripción del Requerimiento',
                        'attribute'=>'requerimiento_descripcion',
                        'value'=>function ($model, $key, $index, $column) {
                            return $model->requerimientoIdRequerimiento->descripcion;
                        },
                        //'group'=>true,
                        'contentOptions' =>
                            [
                                'style'=>'width: 300px; overflow: auto; word-wrap: break-word; white-space: pre-wrap;'
                            ],
                        'vAlign' => \kartik\grid\GridView::ALIGN_MIDDLE,
                        'subGroupOf'=>4,
                    ],
                    [
                        'class'=>'\kartik\grid\DataColumn',
                        'label' => 'Socio Institucional',
                        'attribute'=>'socio_institucional_nombre_comuna',
                        'value'=>function ($model, $key, $index, $column) {
                            return $model->requerimientoIdRequerimiento->sciIdSci->nombreComuna;
                        },
                        'vAlign' => \kartik\grid\GridView::ALIGN_MIDDLE,
                        'contentOptions' =>
                            [
                                'style'=>'width: 300px; overflow: auto; word-wrap: break-word; white-space: pre-wrap;'
                            ],
                        'group'=>true,
                        'subGroupOf' => 3,
                    ],
                    [
                        'class'=>'\kartik\grid\DataColumn',
                        'attribute'=>'requerimiento_cantidad_beneficiarios',
                        'value'=>function ($model, $key, $index, $column) {
                            return $model->requerimientoIdRequerimiento->cantidad_aprox_beneficiarios;
                        },
                        //'group'=>true,
                        'vAlign' => \kartik\grid\GridView::ALIGN_MIDDLE,
                        'subGroupOf'=>5,
                    ],
/*                    [
                        'class'=>'\kartik\grid\DataColumn',
                        'attribute'=>'anio_match1',
                    ],
                    [
                        'class'=>'\kartik\grid\DataColumn',
                        'attribute'=>'semestre_match1',
                    ],*/
                   /* [
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
                    ],*/
                    /*[
                        'mergeHeader'=>true,
                        'header' => 'Seleccionar',
                        'vAlign' => \kartik\grid\GridView::ALIGN_MIDDLE,
                        //'headerOptions' => ['style' => 'color:#337ab7'],
                        'content' => function($model) {
                            return Html::a('<i class="fa fa-arrow-circle-right"></i> ', ['/servicio/create-provisorio',
                                'anio' =>Yii::$app->request->get('anio'), 'semestre' => Yii::$app->request->get('semestre')], ['data-pjax'=> 0,'class' => 'btn btn-info']);
                        },
                        'contentOptions' => ['class' => 'text-center'],
                    ],*/
                ],


            ])?>
            <?php $session->close(); ?>
        </div>
    </div>
<?php Modal::begin([
    "id"=>"ajaxCrudModal",
    "footer"=>"",// always need it for jquery plugin
])?>
<?php Modal::end(); ?>