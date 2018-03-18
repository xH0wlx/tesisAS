<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\ArrayHelper;
use backend\models\ContactoSci;
use backend\models\Sci;
use yii\widgets\ListView;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset;
use johnitvn\ajaxcrud\BulkButtonWidget;
use yii\data\SqlDataProvider;
use yii\widgets\Pjax;
use backend\models\Requerimiento;
use kartik\dialog\Dialog;
use kartik\growl\Growl;
use kartik\growl\GrowlAsset;
use kartik\base\AnimateAsset;
GrowlAsset::register($this);
AnimateAsset::register($this);


/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\RequerimientoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$session = Yii::$app->session;
$session->open();
if($session->has('fechaMatch1')){
    $arregloFecha = $session->get('fechaMatch1');
}else{
    return Yii::$app->response->redirect(Url::to(['/match1/seleccion']));

}
/*
if($session['seleccion3']['seleccionados']){
    var_dump($session['seleccion3']['seleccionados']);
}
if(!isset($_SESSION['seleccion3']['seleccionados'])){
    return Yii::$app->response->redirect(Url::to(['/match1/seleccion']));
}*/
$session->close();

$this->title = 'Asignación de Asignaturas';
$this->params['breadcrumbs'][] = ['label' => 'Nuevo Match', 'url' => ['/match1/seleccion']];
$this->params['breadcrumbs'][] = ['label' => 'Selección Socio C. Inst.', 'url' => ['/match1/seleccion-socio']];
$this->params['breadcrumbs'][] = ['label' => 'Selección Requerimiento', 'url' => ['/match1/seleccion2', "id"=>$modelRequerimiento->sciIdSci->id_sci]];
$this->params['breadcrumbs'][] = $this->title;

CrudAsset::register($this);
$js = '
//INICIALIZAR

// Javascript to enable link to tab
var url = document.location.toString();
if (url.match(\'#\')) {
    $(\'.nav-tabs a[href="#\' + url.split(\'#\')[1] + \'"]\').tab(\'show\');
}

function onAvisoSalida(){
    window.onbeforeunload = function(){
      return \'Puede perder las asignaturas que haya seleccionado y no hayan sido guardadas\';
    };
}

function offAvisoSalida(){
    window.onbeforeunload = null;
}

function setBotonesSistemaGrid(){
    $(\'#pjax-resultados-sistema\').on(\'pjax:success\', function() {
        setEventoChangeCheckbox(); 
        setEventoChangeAllCheckbox();
        setEventoBotonesToggleAsignaturas();
    });
}

function setBotonesManualGrid(){
    $(\'#pjax-crud-datatable\').on(\'pjax:success\', function() {     
        setEventoChangeCheckbox(); 
        setEventoChangeAllCheckbox();
        setEventoBotonesToggleAsignaturas();
    });
}

function setBotonesSessionGrid(){
    $(\'#pjax-session-grid-view\').on(\'pjax:success\', function() {
         setCantidadSeleccionados(); //CONTADOR             (NO NECESITA PJAX)
         setEliminarSeleccionado(); //BOTON MINUS           (INVOCA EL PJAX PARA REFRESCAR LA TABLA DE SESSION)
         setBotonGuardar();         //BOTON GUARDAR         (NO INVOCA PJAX)
         setEventoLimpiar();        //BOTON LIMPIAR TODO    (NO INVOCA PJAX)
    });
}

setBotonesSessionGrid(); //INICIALIZA TODOS LOS BOTONES DE LA SESSION GRID EN PJAX SUCCESS (3 botones 1 evento contador)
setBotonesSistemaGrid(); //INICIALIZA TODOS LOS BOTONES DE LA SISTEMA GRID EN PJAX SUCCESS (1 boton 2 eventos change checkbox y checkbox all)
setBotonesManualGrid();  //INICIALIZA TODOS LOS BOTONES DE LA MANUAL GRID EN PJAX SUCCESS (1 boton 2 eventos change checkbox y checkbox all)

   
//FUNCIONES
function accionEliminarSeleccionado(selector){
         var codAsignatura = selector.val();
         $.ajax({
               url: \''. Yii::$app->request->baseUrl.'/match1/eliminar-seleccionado'.'\',
               type: \'post\',
               data: {
                         codAsignatura: codAsignatura,
                         _csrf : \''.  Yii::$app->request->getCsrfToken().'\'
                     },
               success: function (data) {
                    if(data.texto == "exito"){
                        refrescarTodasLasTablas();
                        $.notify({message: "Eliminado."},{type: "danger", delay:"100", timer:"700"});
                    }
               },
               error: function (data){
                   alert(data);
               },
        });//FIN AJAX
}

function setEventoBotonesToggleAsignaturas(){
    $(\'#mostrarTodas\').off("click").on("click", function(){
        $(\'.nav-tabs a[href="#manual"]\').tab(\'show\');
    });
    
    $(\'#mostrarSugerencias\').off("click").on("click", function(){
        $(\'.nav-tabs a[href="#sistema"]\').tab(\'show\');
    });
}

function refrescarTodasLasTablas(){
    $.pjax.reload({container: \'#pjax-session-grid-view\'}).done(function () {
        $.pjax.reload({container: \'#pjax-crud-datatable\'}).done(function () {
            $.pjax.reload({container: \'#pjax-resultados-sistema\'});
        });
    });
}

function refrescarTablasAsignaturas(){
    $.pjax.reload({container: \'#pjax-crud-datatable\'}).done(function () {
        $.pjax.reload({container: \'#pjax-resultados-sistema\'});
    });
}

function refrescarTablaSugerenciasSession(){
    $.pjax.reload({container: \'#pjax-session-grid-view\'}).done(function () {
        $.pjax.reload({container: \'#pjax-resultados-sistema\'});
    });
}

var eventoCheckboxPropio = function eventoClickCheckbox(){
        var cajita = $(this);
        if($(this).is(\':checked\')){
            $.ajax({
               url: \''. Yii::$app->request->baseUrl.'/match1/verifica-asignatura-semestre'.'\',
               type: \'post\',
               data: {
                         anio: \''.$arregloFecha["anio"].'\',
                         semestre: \''.$arregloFecha["semestre"].'\',
                         idAsignatura: $(this).val(),
                         _csrf : \''.  Yii::$app->request->getCsrfToken().'\'
                     },
               success: function (data) {
                    if(data.codigo == "exito"){
                        guardarSeleccionadosSession();
                        $.pjax.reload({container: \'#pjax-session-grid-view\'}); //SOLO ESTA PORQUE LOS OTROS QUEDAN ACTIVOS POR LA NATURALEZA DEL CHECKBOX
                        $.notify({message: "Agregado a la lista."},{type: "success", delay:"100", timer:"700"});
                        
                        onAvisoSalida();
                        
                    }else{
                        if(data.codigo == "error" && data.codigoNumero == 0){
                            krajeeDialog.confirm(data.texto, function (result) {
                                if (result) { // ok button was pressed
                                   guardarSeleccionadosSession();
                                   $.notify({message: "Agregado a la lista."},{type: "success", delay:"100", timer:"700"});
                                   
                                   onAvisoSalida();
                                   
                                   $.pjax.reload({container: \'#pjax-session-grid-view\'});
                                } else { // confirmation was cancelled
                                   cajita.prop(\'checked\', false); 
                                   cajita.closest(\'tr\').removeClass("success");
                                }
                            });
                        }
                    }
               },
               error: function (data){
                   alert(data);
               },
            }); // FIN AJAX
        }else{
            accionEliminarSeleccionado($(this));
            //refrescarTablaSugerenciasSession();
            refrescarTodasLasTablas();
            setCantidadSeleccionados();
        }
};

function setEventoChangeCheckbox(){
    $(\'.kv-row-checkbox\').off("change", eventoCheckboxPropio);
    $(\'.kv-row-checkbox\').on("change", eventoCheckboxPropio);
}//FIN FUNCION

var eventoCheckboxAllPropio = function(){
    //guardarSeleccionadosSession(); //PROVOCA EL ERROR DE GUARDAR TAMBIÉN LAS CANCELADAS
    $.pjax.reload({container: \'#pjax-session-grid-view\'});
};

function setEventoChangeAllCheckbox(){
    $(\'.select-on-check-all\').off("change", eventoCheckboxAllPropio);
    $(\'.kv-row-checkbox\').on("change", eventoCheckboxAllPropio);
}

function setEventoLimpiar(){
    $(\'.limpiar\').on("click", function(){
        limpiarSeleccionados();
    });
}

function setBotonGuardar(){
    $(\'.guardar\').on("click", function(event){
        $.ajax({
           url: \''. Yii::$app->request->baseUrl.'/match1/guardar-seleccion'.'\',
           type: \'post\',
           data: {
                     requerimientoPadre: event.target.value,
                     periodo: {año:'.$arregloFecha["anio"].', semestre: '.$arregloFecha["semestre"].'},
                     _csrf : \''.  Yii::$app->request->getCsrfToken().'\'
                 },
           success: function (data) {
            if(data.code == 100){
                krajeeDialog.alert("guardado");
                
                offAvisoSalida();
                
                //window.location.replace("'.Url::toRoute('/match1/seleccion').'");
            }
            if(data.code == 200){
                alert("Código de Asignatura: "+data.codAsignatura+"\n Ya fue preseleccionada para este requerimiento en el periodo seleccionado.");
            }
           },
           error: function (data){
               alert("Error al guardar en la base de datos.");
           }
      });//FIN AJAX
    });
}//FIN

function setEliminarSeleccionado(){
    $(\'.boton-eliminar-seleccionado\').off("click").on("click", function(event){
        accionEliminarSeleccionado($(this));
    });
}//FIN FUNCION

function setCantidadSeleccionados(){
    //var rowCount = $(\'#session-grid-view-container >.kv-grid-table >tbody >tr[data-key]\').length;
    //$("#contador").text(rowCount);
    $.ajax({
           url: \''. Yii::$app->request->baseUrl.'/match1/obtener-contador-seleccionados'.'\',
           type: \'post\',
           data: {
                     _csrf : \''.  Yii::$app->request->getCsrfToken().'\'
                 },
           success: function (data) {
                $("#contador").text(data.contadorSeleccionados);
           },
           error: function (data){
               alert("Error al limpiar los datos");
           }
    });//FIN AJAX  
}

function limpiarSeleccionados(){
    var texto = "Está seguro de borrar todos los registros de la lista de seleccionados?";
    krajeeDialog.confirm(texto, function (result) {
        if (result) { // ok button was pressed
           $.ajax({
               url: \''. Yii::$app->request->baseUrl.'/match1/limpiar-seleccion'.'\',
               type: \'post\',
               data: {
                         _csrf : \''.  Yii::$app->request->getCsrfToken().'\'
                     },
               success: function (data) {
                    refrescarTodasLasTablas(); //ACTUALIZAR DATOS
               },
               error: function (data){
                   alert("Error al limpiar los datos.");
               }
          });//FIN AJAX  
        } else {
        }
    });    
}//FIN FUNCION DE LIMPIEZA

function guardarSeleccionadosSession(){
    var seleccionadasPaginacion = $(\'#crud-datatable\').yiiGridView(\'getSelectedRows\');
    var seleccionadasPaginacion2 = $(\'#resultados-sistema\').yiiGridView(\'getSelectedRows\'); 
    
    $.ajax({
           url: \''. Yii::$app->request->baseUrl.'/match1/recordar-seleccionados'.'\',
           type: \'post\',
           data: {
                     selectedItems: seleccionadasPaginacion,
                     selectedItems2: seleccionadasPaginacion2,
                     _csrf : \''.  Yii::$app->request->getCsrfToken().'\'
                 },
           success: function (data) {
           },
           error: function (data){
               alert("Error al guardar.");
           },
    });
}
/////////////TERMINO DE FUNCIONES////////////////////////////////////////////////////

//AL CARGAR LA PÁGINA
setEventoBotonesToggleAsignaturas();
setEventoChangeAllCheckbox(); //SETEA EL EVENTO PARA SELECCIONAR TODO LOS CHECKBOX
setEventoChangeCheckbox(); //SETEA EL EVENTO PARA SELECCIONAR 1 CHECKBOX
setEventoLimpiar(); //SETEA EL EVENTO DEL BOTON LIMPIAR LISTA E INVOCA limpiarSeleccionados()
setCantidadSeleccionados(); //SETEA EL NÚMERO DE SELECCIONADOS EN EL CONTADOR
setEliminarSeleccionado();//SETEA EL EVENTO CLICK PARA EL BOTON ELIMINAR UNA ASIGNATURA DE LA LISTA
setBotonGuardar(); // SETEA EL EVENTO EN EL BOTÓN PARA GUARDAR EN BD LAS ASIGNATURAS SELECCIONADAS


//limpiarSeleccionados(); LIMPIA TODA LA LISTA DE SELECCIONADOS Y RECARGA LA PÁGINA
//guardarSeleccionadosSession();FUNCIÓN ENCARGADA DE GUARDAR EN SESSION AMBAS TABLAS(SISTEMA Y MANUAL)


$(\'#pjax-crud-datatable\').on(\'pjax:error\', function (event) {
    console.log("Error PJAX ASIGNATURA CRUD");
    //alert(\'Error al cargar tabla de Asignaturas.\');
    //event.preventDefault();
});

';
$this->registerJs($js);

echo Dialog::widget([]);

?>



<?php
Modal::begin([
    'header'=>'<h4>Requerimiento</h4>',
    'id'=>'modal',
    'size'=>'modal-lg',
]);

echo "<div id='modalContent'></div>";
Modal::end();
?>


    <div class="box box-primary collapsed-box">
        <div class="box-header with-border"><h3 class="box-title">Periodo Seleccionado: <b><?=$arregloFecha["anio"]?> - <?=$arregloFecha["semestre"]?></b></h3>
        </div>
        <div class="box-body">
        </div>
    </div>

    <div class="box box-primary collapsed-box">
        <div class="box-header with-border"><h3 class="box-title">Requerimiento Seleccionado: <?=$modelRequerimiento->titulo ?></h3>
            <div class="box-tools pull-right">
                (Ver Detalle)<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                </button>
            </div>
        </div>
        <div class="box-body">
            <?= DetailView::widget([
                'model' => $modelRequerimiento,
                'attributes' => [
                    'titulo',
                    'descripcion',
                    'apoyo_brindado',
                    'observacion',
                    [
                        'label' => 'Socio Comunitario Institucional',
                        'value' => $modelRequerimiento->sciIdSci->nombreComuna,
                    ],
                ],
            ]) ?>
        </div>
    </div>

    <div class="alert alert-info alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h4><i class="icon fa fa-info"></i> Información</h4>
        A continuación seleccione la asignatura o conjunto de ellas que serán candidatas para satisfacer el Requerimiento.<br>
        Puede alternar entre las sugerencias del sistema y todas las asignaturas en la primera pestaña (Selección de Asignaturas).
    </div>

<div class="row">
    <div class="col-lg-12 col-md-12">
    <div class="nav-tabs-custom">
        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="dropdown active">
                <a href="#" class="dropdown-toggle" id="sistemaDropdown" data-toggle="dropdown" aria-controls="sistemaDropdown-contents" aria-expanded="false"><i class="glyphicon glyphicon-book"></i> Selección de Asignaturas <span class="caret"></span></a>
                <ul class="dropdown-menu" aria-labelledby="sistemaDropdown" id="sistemaDropdown-contents">
                    <li class="active"><a href="#sistema" role="tab" id="dropdown1-tab" data-toggle="tab" aria-controls="dropdown1" aria-expanded="false">Sugerencias del Sistema</a></li>
                    <li class=""><a href="#manual" role="tab" id="dropdown2-tab" data-toggle="tab" aria-controls="dropdown2" aria-expanded="false">Todas las Asignaturas</a></li>
                </ul>
            </li>
            <li role="presentation">
                <a href="#seleccionados" aria-controls="seleccionados" role="tab" data-toggle="tab"><i class="glyphicon glyphicon-list"></i> Asignaturas seleccionadas <span id="contador" class="label label-success"
                    style="position: absolute;
                            top: 1px;
                            right: 1px;
                            text-align: center;
                            font-size: 15px;
                            padding: 2px 3px;
                            line-height: .9;">-</span></a>
            </li>
        </ul>
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane fade in active" id="sistema">
                <?php $session->open(); ?>
                <?= GridView::widget([
                    'id' => 'resultados-sistema',
                    'pjax' => true,
                    'pjaxSettings'=>[
                        'options' => [
                            'id' => 'pjax-resultados-sistema',
                            //'neverTimeout'=>true,
                            //'linkSelector' => '#'.$modelRequerimiento->id_requerimiento.' a:not([rel=fancybox], .item-link)',
                            'enablePushState' => false
                        ],
                    ],
                    'dataProvider' => $asignaturasSistema,
                    'filterModel' => $searchModelAsignatura,
                    //'filterModel' => $searchModel,
                    'panel'=>[
                        'type'=>GridView::TYPE_PRIMARY,
                        'heading'=>"<i class=\"glyphicon glyphicon-list\"></i> Sugerencias en base a las palabras clave: \"".$modelRequerimiento->tagValues."\"
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Con sede en: ".$modelRequerimiento->sciIdSci->sedeIdSede->nombre_sede,

                        'after'=> '',
                    ],
                    'toolbar'=> [
                        ['content'=>
                            Html::button('<i class="fa fa-window-restore"></i> Mostrar todas las Asignaturas', ['id'=>'mostrarTodas','class' => 'btn btn-success']).
                            Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['/match1/seleccion3?id='.$modelRequerimiento->id_requerimiento],
                                ['data-pjax'=>1, 'class'=>'btn btn-default', 'title'=>'Reset Grid']).
                            '{toggleData}'.
                            '{export}'
                        ],
                    ],
                    'columns' => [
                        [
                            'class' => '\kartik\grid\CheckboxColumn',
                            'checkboxOptions' => function($model, $key, $index, $column) use ($session) {
                                if($session->has('seleccion3')){
                                    $filasGuardadas = $session['seleccion3']['seleccionados'];
                                }else{
                                    $filasGuardadas = [];
                                }
                                if(array_key_exists((string)$key,$filasGuardadas)){
                                    return ['checked' => true];
                                }else{
                                    return ['checked' => false];
                                }

                            },
                            //'name' => $modelRequerimiento->id_requerimiento,
                            'rowSelectedClass' => GridView::TYPE_SUCCESS,
                        ],
                        [
                            'class' => 'kartik\grid\SerialColumn',
                            'width' => '30px',
                        ],
                        [
                            'class'=>'\kartik\grid\DataColumn',
                            'attribute'=>'cod_asignatura',
                        ],
                        [
                            'class'=>'\kartik\grid\DataColumn',
                            'attribute'=>'nombre_asignatura',
                        ],
                        [
                            'class'=>'\kartik\grid\DataColumn',
                            'attribute'=>'semestre_dicta',
                        ],
                        /*[
                            'class'=>'\kartik\grid\DataColumn',
                            'attribute'=>'semestre_malla',
                        ],*/
                        [
                            'class'=>'\kartik\grid\DataColumn',
                            'attribute'=>'resultado_aprendizaje',
                            'contentOptions' =>
                                [
                                    'style'=>'min-width: 250px; overflow: auto; word-wrap: break-word; white-space: pre-wrap;'
                                ],
                        ],
                        [
                            'class'=>'\kartik\grid\DataColumn',
                            'attribute'=>'asignatura_carrera',
                            'label' => 'Carrera',
                            'value'=>function ($model, $index, $widget) { return $model->carreraCodCarrera->alias_carrera; }
                        ],
                        [
                            'class'=>'\kartik\grid\DataColumn',
                            'attribute'=>'asignatura_sede',
                            'label' => 'Sede',
                            'value'=>function ($model, $index, $widget) { return $model->carreraCodCarrera->facultadIdFacultad->sedeIdSede->nombre_sede; }
                        ],
                    ],
                ]); ?>
                <?php $session->close(); ?>
            </div>
            <div role="tabpanel" class="tab-pane fade" id="manual">
            <?=GridView::widget([
                    'id'=>'crud-datatable',
                    'dataProvider' => $dataProviderAsignatura,
                    'filterModel' => $searchModelAsignatura,
                    'pjax'=>true,
                    'pjaxSettings'=>[
                        'options' => [
                            'id' => 'pjax-crud-datatable',
                            //'neverTimeout'=>true,
                            //'linkSelector' => '#'.$modelRequerimiento->id_requerimiento.' a:not([rel=fancybox], .item-link)',
                            'enablePushState' => false
                        ],
                    ],
                    'columns' => [
                        [
                            'class' => 'kartik\grid\CheckboxColumn',
                            'rowSelectedClass' => GridView::TYPE_SUCCESS,
                            'checkboxOptions' => function($model, $key, $index, $column) use ($session) {
                                if($session->has('seleccion3')){
                                    $filasGuardadas = $session['seleccion3']['seleccionados'];
                                }else{
                                    $filasGuardadas = [];
                                }
                                if(array_key_exists((string)$key,$filasGuardadas)){
                                    return ['checked' => true];
                                }else{
                                    return ['checked' => false];
                                }

                            },

                            'width' => '20px',
                        ],
                        [
                            'class' => 'kartik\grid\SerialColumn',
                            'width' => '30px',
                        ],
                        [
                            'class'=>'\kartik\grid\DataColumn',
                            'attribute'=>'cod_asignatura',
                        ],
                        [
                            'class'=>'\kartik\grid\DataColumn',
                            'attribute'=>'nombre_asignatura',
                        ],
                        [
                            'class'=>'\kartik\grid\DataColumn',
                            'attribute'=>'semestre_dicta',
                        ],
                        /*[
                            'class'=>'\kartik\grid\DataColumn',
                            'attribute'=>'semestre_malla',
                        ],*/
                        [
                            'class'=>'\kartik\grid\DataColumn',
                            'attribute'=>'resultado_aprendizaje',
                            'contentOptions' =>
                                [
                                    'style'=>'min-width: 250px; overflow: auto; word-wrap: break-word; white-space: pre-wrap;'
                                ],
                        ],
                        [
                            'class'=>'\kartik\grid\DataColumn',
                            'attribute'=>'asignatura_carrera',
                            'label' => 'Carrera',
                            'value'=>function ($model, $index, $widget) { return $model->carreraCodCarrera->alias_carrera; }
                        ],
                        [
                            'class'=>'\kartik\grid\DataColumn',
                            'attribute'=>'asignatura_sede',
                            'label' => 'Sede',
                            'value'=>function ($model, $index, $widget) { return $model->carreraCodCarrera->facultadIdFacultad->sedeIdSede->nombre_sede; }
                        ],

                    ],
                    'toolbar'=> [
                        ['content'=>
                            Html::button('<i class="fa fa-window-restore"></i> Mostrar Sugerencias del Sistema', ['id'=>'mostrarSugerencias','class' => 'btn btn-success']).
                            Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['/match1/seleccion3?id='.$modelRequerimiento->id_requerimiento],
                                ['data-pjax'=>1, 'class'=>'btn btn-default', 'title'=>'Reset Grid']).
                            '{toggleData}'.
                            '{export}'
                        ],
                    ],
                    //'striped' => true,
                    'condensed' => true,
                    //'responsive' => true,
                    'panel' => [
                        'type' => 'primary',
                        'heading' => '<i class="glyphicon glyphicon-list"></i> Lista de Asignaturas (Con la misma Sede que el Socio Inst. ['.$modelRequerimiento->sciIdSci->sedeIdSede->nombre_sede.'])',
                        'after'=> "",
                    ]
                ]);?>
            </div>



            <div role="tabpanel" class="tab-pane fade" id="seleccionados">
                <?=GridView::widget([
                    'id'=>'session-grid-view',
                    'dataProvider' => $dataProviderSession,
                    //'filterModel' => $searchModelAsignatura,
                    'pjax'=>true,
                    'pjaxSettings'=>[
                        'options' => [
                            'id' => 'pjax-session-grid-view',
                            //'neverTimeout'=>true,
                            //'linkSelector' => '#'.$modelRequerimiento->id_requerimiento.' a:not([rel=fancybox], .item-link)',
                            'enablePushState' => false
                        ],
                    ],
                    'columns' => [
                        [
                            'class' => 'kartik\grid\SerialColumn',
                            'width' => '30px',
                        ],
                        [
                            'class'=>'\kartik\grid\DataColumn',
                            'attribute'=>'cod_asignatura',
                            'contentOptions' =>
                                [
                                    'style'=>'width: 100px; overflow: auto; word-wrap: break-word; white-space: pre-wrap;'
                                ],
                        ],
                        [
                            'class'=>'\kartik\grid\DataColumn',
                            'attribute'=>'nombre_asignatura',
                        ],
                        [
                            'class'=>'\kartik\grid\DataColumn',
                            'attribute'=>'asignatura_carrera',
                            'label' => 'Carrera',
                            'value'=>function ($model, $index, $widget) { return $model->carreraCodCarrera->alias_carrera; }
                        ],
                        [
                            'class'=>'\kartik\grid\DataColumn',
                            'attribute'=>'asignatura_sede',
                            'label' => 'Sede',
                            'value'=>function ($model, $index, $widget) { return $model->carreraCodCarrera->facultadIdFacultad->sedeIdSede->nombre_sede; }
                        ],
                        [
                            'class' => 'kartik\grid\ActionColumn',
                            'header' => "Eliminar de esta lista",
                            //'headerOptions' => ['style' => 'color:#337ab7'],
                            'dropdown' => false,
                            'vAlign'=>'middle',
                            'buttons' => [
                                'view' => function ($url, $model) {
                                    return Html::button('<i class="fa fa-minus"></i>',
                                        ['class' => 'btn btn-danger boton-eliminar-seleccionado', 'value' => $model->cod_asignatura,
                                            'title' => 'Eliminar', 'data-pjax' => "0", 'data-toggle' => "tooltip"]);
                                }
                            ],
                            /*'urlCreator' => function($action, $model, $key, $index) {
                                return Url::to(['requerimiento/'.$action,'id'=>$key]);
                            },*/
                            'template' => '{view}',
                            'viewOptions'=>['role'=>'modal-remote','title'=>'View','data-toggle'=>'tooltip'],
                            'updateOptions'=>['role'=>'modal-remote','title'=>'Update', 'data-toggle'=>'tooltip'],
                        ],

                    ],
                    'toolbar'=> [
                        ['content'=>
                            Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['/match1/seleccion3?id='.$modelRequerimiento->id_requerimiento],
                                ['data-pjax'=>1, 'class'=>'btn btn-default', 'title'=>'Reset Grid']).
                            '{toggleData}'.
                            '{export}'
                        ],
                    ],
                    //'striped' => true,
                    'condensed' => true,
                    //'responsive' => true,
                    'panel' => [
                        'type' => 'primary',
                        'heading' => '<i class="glyphicon glyphicon-list"></i> Asignaturas Seleccionadas',
                        'before'=> '<em>* Esta lista se precarga con las asignaturas que ya han sido guardadas.</em>',
                        'after'=>Html::button('<i class="glyphicon glyphicon-trash"></i> Limpiar esta Lista', ['class' => 'limpiar btn btn-danger']).
                                Html::button('<i class="glyphicon glyphicon-save"></i> Guardar Selección', ['value'=>$modelRequerimiento->id_requerimiento,'class' => 'guardar btn btn-primary']),
                    ]
                ]);?>
            </div>
        </div><!-- TAB CONTENT -->
    </div>

    </div><!-- col 12 -->
</div>

<?php Modal::begin([
    "id"=>"ajaxCrudModal",
    "footer"=>"",// always need it for jquery plugin
])?>
<?php Modal::end(); ?>