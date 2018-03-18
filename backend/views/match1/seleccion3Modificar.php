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
$session->close();

$this->title = 'Modificar Asignaturas Seleccionadas';
$this->params['breadcrumbs'][] = $this->title;

CrudAsset::register($this);
$js = '
    $(\'.modalButton\').click(function(){
        $(\'#modal\').modal(\'show\')
        .find(\'#modalContent\')
        .load($(this).attr(\'value\'));
    });
        
//});
//INICIALIZAR
setCantidadSelecciondos();
setEliminarSeleccionado();   
   
//FUNCIONES
function setEliminarSeleccionado(){
    $(\'.boton-eliminar-seleccionado\').on("click", function(event){
        var codAsignatura = $(this).val();
         $.ajax({
               url: \''. Yii::$app->request->baseUrl.'/match1/eliminar-seleccionado'.'\',
               type: \'post\',
               data: {
                         codAsignatura: codAsignatura,
                         _csrf : \''.  Yii::$app->request->getCsrfToken().'\'
                     },
               success: function (data) {
                    if(data.texto == "exito"){
                        alert("eliminado");
                        $.pjax.reload({container: \'#pjax-session-grid-view\'});
                        $.pjax.reload({container: \'#pjax-resultados-sistema\'});
                        $.pjax.reload({container: \'#pjax-crud-datatable\'});
                    }
               },
               error: function (data){
                   alert(data);
               },
        });//FIN AJAX
    });
}

function setCantidadSelecciondos(){
    var rowCount = $(\'#session-grid-view-container >.kv-grid-table >tbody >tr[data-key]\').length;
    $("#contador").text(rowCount);
}

function limpiarSeleccionados(){
    if (confirm("Está seguro de borrar todos los registros de la lista de seleccionados?") == true) {
            $.ajax({
               url: \''. Yii::$app->request->baseUrl.'/match1/limpiar-seleccion'.'\',
               type: \'post\',
               data: {
                         _csrf : \''.  Yii::$app->request->getCsrfToken().'\'
                     },
               success: function (data) {
                    //alert("Limpieza exitosa");
                    location.reload();
               },
               error: function (data){
                   alert("Error al limpiar los datos");
               }
          });//FIN AJAX  
    } else {
        txt = "You pressed Cancel!";
    }
            
}//FIN FUNCION DE LIMPIEZA

function guardarSeleccionadosSession(){
    seleccionadasPaginacion = $(\'#crud-datatable\').yiiGridView(\'getSelectedRows\');
    seleccionadasPaginacion2 = $(\'#resultados-sistema\').yiiGridView(\'getSelectedRows\'); 
    
    $.ajax({
           url: \''. Yii::$app->request->baseUrl.'/match1/recordar-seleccionados'.'\',
           type: \'post\',
           data: {
                     selectedItems: seleccionadasPaginacion,
                     selectedItems2: seleccionadasPaginacion2,
                     _csrf : \''.  Yii::$app->request->getCsrfToken().'\'
                 },
           success: function (data) {
            console.log(data.seleccionados.seleccionados);
           },
           error: function (data){
               alert(data);
           },
    });
}
//TERMINO DE FUNCIONES
   
var seleccionadasPaginacion = [];
          
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
            window.location.replace("'.Url::toRoute('/match1/seleccion').'");
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

  
//DEBE SER PARA LOS DOS  
$(\'#pjax-crud-datatable\').on(\'pjax:start\', function() {
    guardarSeleccionadosSession();
});
$(\'#pjax-resultados-sistema\').on(\'pjax:start\', function() {
    guardarSeleccionadosSession();
});
$(\'#pjax-crud-datatable\').on(\'pjax:complete\', function() {     
     $(\'.kv-row-checkbox\').change(function(e) {
        guardarSeleccionadosSession();
        $.pjax.reload({container: \'#pjax-session-grid-view\'});
      });
     $(\'.select-on-check-all\').change(function(e) {
        guardarSeleccionadosSession();
        $.pjax.reload({container: \'#pjax-session-grid-view\'});
     });
});
$(\'#pjax-resultados-sistema\').on(\'pjax:complete\', function() {     
     $(\'.kv-row-checkbox\').change(function(e) {
         guardarSeleccionadosSession();
         $.pjax.reload({container: \'#pjax-session-grid-view\'});
     });
     $(\'.select-on-check-all\').change(function(e) {
        guardarSeleccionadosSession();
        $.pjax.reload({container: \'#pjax-session-grid-view\'});
     });
});

$(\'#pjax-session-grid-view\').on(\'pjax:complete\', function() {
     //ACTUALIZAR CONTADOR
     setCantidadSelecciondos();
     setEliminarSeleccionado();   
     
    $(\'.limpiar\').on("click", function(){
        limpiarSeleccionados();
    });
    
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
                window.location.replace("'.Url::toRoute('/match1/seleccion').'");
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
    
});
  
  
$(\'#pjax-crud-datatable\').on(\'pjax:error\', function (event) {
    alert(\'Failed to load the page\');
    event.preventDefault();
});
  

    
$(\'.kv-row-checkbox\').change(function(e) {
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
                    $.pjax.reload({container: \'#pjax-session-grid-view\'});
                }else{
                    if(data.codigo == "error" && data.codigoNumero == 0){
                        krajeeDialog.confirm(data.texto, function (result) {
                            if (result) { // ok button was pressed
                               guardarSeleccionadosSession();
                               $.pjax.reload({container: \'#pjax-session-grid-view\'});
                            } else { // confirmation was cancelled
                               cajita.prop(\'checked\', false); 
                            }
                        });
                    }
                }
           },
           error: function (data){
               alert(data);
           },
        }); // FIN AJAX
    }

    //guardarSeleccionadosSession();
    //$.pjax.reload({container: \'#pjax-session-grid-view\'});
});

$(\'.select-on-check-all\').change(function(e) {
        guardarSeleccionadosSession();
        $.pjax.reload({container: \'#pjax-session-grid-view\'});
});
  
$(\'.limpiar\').on("click", function(){
    limpiarSeleccionados();
});

$(\'#mostrarTodas\').on("click", function(){
    $(\'.nav-tabs a[href="#manual"]\').tab(\'show\');
});

$(\'#mostrarSugerencias\').on("click", function(){
    $(\'.nav-tabs a[href="#sistema"]\').tab(\'show\');
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
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3>
            Periodo Seleccionado: <?=$arregloFecha["anio"]?> - <?=$arregloFecha["semestre"]?>
            </h3>
        </div>
    </div>


    <div class="box box-primary">
        <div class="box-heading"><strong>&nbsp;Requerimiento Seleccionado</strong></div>
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
                        'value' => $modelRequerimiento->sciIdSci->nombre,
                    ],
                ],
            ]) ?>
        </div>
    </div>

<div class="row">
    <div class="col-lg-6 col-md-6">


    <div class="nav-tabs-custom">
        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active">
                <a href="#sistema" aria-controls="sistema" role="tab" data-toggle="tab"><i class="glyphicon glyphicon-book"></i> Sugerencias del Sistema</a>
            </li>
            <li role="presentation">
                <a href="#manual" aria-controls="manual" role="tab" data-toggle="tab"><i class="glyphicon glyphicon-list"></i> Todas las Asignaturas</a>
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
                    'toolbar'=> [
                        ['content'=>
                        /*Html::a('<i class="glyphicon glyphicon-plus"></i> Crear Asignatura', ['create'],
                            ['role'=>'modal-remoteX','title'=> 'Crear Nueva Asignatura','class'=>'btn btn-success']).*/
                            Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['/match1/seleccion3?id='.$modelRequerimiento->id_requerimiento],
                                ['data-pjax'=>1, 'class'=>'btn btn-default', 'title'=>'Reset Grid']).
                            '{toggleData}'
                        ],
                    ],
                    'dataProvider' => $asignaturasSistema,
                    //'filterModel' => $searchModel,
                    'panel'=>[
                        'type'=>GridView::TYPE_PRIMARY,
                        'heading'=>"<i class='glyphicon glyphicon-search'>
</i> Resultados para \"".$modelRequerimiento->titulo."\".<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;En base a las siguientes palabras claves: \"".$modelRequerimiento->tagValues."\"<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Con sede en: ".$modelRequerimiento->sciIdSci->sedeIdSede->nombre_sede,

                        //'after'=> Html::button('<i class="glyphicon glyphicon-save"></i> Guardar Seleccionados', ['class' => 'guardar-asignaturas btn btn-primary']),
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
                        ['class' => 'yii\grid\SerialColumn'],

                        //'id_requerimiento',
                        'cod_asignatura',
                        'nombre_asignatura',
                        //'carreraCodCarrera.facultadIdFacultad.sedeIdSede.nombre_sede',
                        'semestre_dicta',
                        //'semestre_malla',
                        'carrera_cod_carrera',
                        'carreraCodCarrera.alias_carrera'
                        //['class' => 'yii\grid\ActionColumn'],
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
                        /*Html::a('<i class="glyphicon glyphicon-plus"></i> Crear Asignatura', ['create'],
                            ['role'=>'modal-remoteX','title'=> 'Crear Nueva Asignatura','class'=>'btn btn-success']).*/
                            Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['/match1/seleccion3?id='.$modelRequerimiento->id_requerimiento],
                                ['data-pjax'=>1, 'class'=>'btn btn-default', 'title'=>'Reset Grid']).
                            '{toggleData}'
                        ],
                    ],
                    //'striped' => true,
                    'condensed' => true,
                    //'responsive' => true,
                    'panel' => [
                        'type' => 'primary',
                        'heading' => '<i class="glyphicon glyphicon-list"></i> Lista de Asignaturas',
                        //'after'=> Html::button('<i class="glyphicon glyphicon-save"></i> Guardar Seleccionados', ['class' => 'guardar-asignaturas btn btn-primary']),

                    ]
                ]);?>
            </div>
        </div><!-- TAB CONTENT -->
    </div>

    </div><!-- col 6 -->

    <div class="col-lg-6 col-md-6">
        <div class="row">
            <div class="col-lg-12 col-md-12">
            <?= Html::button('<i class="glyphicon glyphicon-trash"></i> Limpiar Selección', ['class' => 'limpiar btn btn-danger']);?>
            </div>
        </div>

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

            ],
            'toolbar'=> [
                ['content'=>
                /*Html::a('<i class="glyphicon glyphicon-plus"></i> Crear Asignatura', ['create'],
                    ['role'=>'modal-remoteX','title'=> 'Crear Nueva Asignatura','class'=>'btn btn-success']).*/
                    Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['/match1/seleccion3?id='.$modelRequerimiento->id_requerimiento],
                        ['data-pjax'=>1, 'class'=>'btn btn-default', 'title'=>'Reset Grid']).
                    '{toggleData}'
                ],
            ],
            //'striped' => true,
            'condensed' => true,
            //'responsive' => true,
            'panel' => [
                'type' => 'primary',
                'heading' => '<i class="glyphicon glyphicon-list"></i> Asignaturas Seleccionadas',
                //'after'=> Html::button('<i class="glyphicon glyphicon-save"></i> Guardar Seleccionados', ['class' => 'guardar-asignaturas btn btn-primary']),

            ]
        ]);?>


        <?=GridView::widget([
            'id'=>'crud-datatable-match1',
            'dataProvider' => $dataProviderMatch1,
            //'filterModel' => $searchModel,
            'pjax'=>true,
            'pjaxSettings'=>[
                'options' => [
                    'id' => 'pjax-match1-grid-view',
                    //'neverTimeout'=>true,
                    //'linkSelector' => '#'.$modelRequerimiento->id_requerimiento.' a:not([rel=fancybox], .item-link)',
                    'enablePushState' => false
                ],
            ],
            'columns' => require(__DIR__.'/_columns.php'),
            'toolbar'=> [
                ['content'=>
                    Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['', 'id'=>Yii::$app->request->get('id')],
                        ['data-pjax'=>1, 'class'=>'btn btn-default', 'title'=>'Reset Grid']).
                    '{toggleData}'
                ],
            ],
            'striped' => true,
            'condensed' => true,
            'responsive' => true,
            'panel' => [
                'type' => 'primary',
                'heading' => '<i class="glyphicon glyphicon-list"></i> Lista de Asignaturas guardadas',
                //'before'=>'<em>* Resize table columns just like a spreadsheet by dragging the column edges.</em>',
                'after'=>BulkButtonWidget::widget([
                        'buttons'=>Html::a('<i class="glyphicon glyphicon-trash"></i>&nbsp; Borrar Seleccionados',
                            ["bulk-delete"] ,
                            [
                                "class"=>"btn btn-danger btn-xs",
                                'role'=>'modal-remote-bulk',
                                'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
                                'data-request-method'=>'post',
                                'data-confirm-title'=>'Esta seguro?',
                                'data-confirm-message'=>'Está seguro que desea eliminar el/los registro/s?'
                            ]),
                    ]).
                    '<div class="clearfix"></div>',
            ]
        ])?>
    </div>
</div>
    <?= Html::button('<i class="glyphicon glyphicon-save"></i> Guardar Selección', ['value'=>$modelRequerimiento->id_requerimiento,'class' => 'guardar btn btn-primary']);?>

<?php Modal::begin([
    "id"=>"ajaxCrudModal",
    "footer"=>"",// always need it for jquery plugin
])?>
<?php Modal::end(); ?>