<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use kartik\grid\GridView;
//use yii\widgets\DetailView;
use kartik\detail\DetailView;
use yii\helpers\ArrayHelper;
use backend\models\ContactoSci;
use backend\models\Sci;
use yii\widgets\ListView;
use yii\data\ActiveDataProvider;
use johnitvn\ajaxcrud\CrudAsset;
//use kartik\dialog\Dialog;

use yii\bootstrap\Modal;
CrudAsset::register($this);
/* @var $this yii\web\View */
/* @var $model backend\models\sci */

$this->title = 'Selección de Socio Comunitario Institucional';
$this->params['breadcrumbs'][] = ['label' => 'Nuevo Match', 'url' => ['/match1/seleccion']];
$this->params['breadcrumbs'][] = "Selección Socio C. Inst.";


$session = Yii::$app->session;
$session->open();
if($session->has('fechaMatch1')){
    $arregloFecha = $session->get('fechaMatch1');
}else{
    Yii::$app->getSession()->setFlash('success', [
                'type' => 'error',
                'duration' => 5000,
                //'icon' => 'fa fa-users',
                'message' => 'No ha seleccionado un periodo.',
                'title' => 'Error',
                'positonY' => 'top',
                //'positonX' => 'left'
            ]);
    return Yii::$app->response->redirect(Url::to(['/match1/seleccion']));

}
$session->close();


//echo Dialog::widget([]);


$js = '
$(\'.boton-view-sci\').click(function(e){
         e.preventDefault();      
        $(\'#header-modal-sci\').text($(this).attr("name"));
        $(\'#modal-sci\').modal(\'show\')
        .find(\'#contenido-modal-sci\')
        .load($(this).attr(\'href\'));
        return false;
});

';
$this->registerJs($js);




?>

    <div class="box box-primary collapsed-box">
        <div class="box-header with-border"><h3 class="box-title">Periodo Seleccionado: <b><?=$arregloFecha["anio"]?> - <?=$arregloFecha["semestre"]?></b></h3>
        </div>
        <div class="box-body">
        </div>
    </div>

    <div id="caja-socios-no-asignados" class="seleccion">

        <?php
/*            $form = ActiveForm::begin([//'id' => 'my-form',
                'method'=>'get',
                'layout' => 'inline',
                'action' => Url::to('/match1/seleccion'),
                'fieldConfig' => [
                    'labelOptions' => ['class' => ''],
                    'enableError' => true,
                ]
            ]);
        */?><!--
            <?/*= $form->field($searchModel, 'nombre') */?>
            <div class="form-group">
                <?/*= Html::submitButton('Buscar', ['class' => 'btn btn-primary']) */?>
            </div>
        --><?php
/*            ActiveForm::end();
        */?>
        <div id="ajaxCrudDatatable">
            <?= GridView::widget([
                'id'=>'crud-datatable',
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'pjax' => true,
                'pjaxSettings'=>[
                    'options' => [
                        'id' => 'pjax-grid-sci-match1',
                        //'neverTimeout'=>true,
                        //'linkSelector' => '#'.$modelRequerimiento->id_requerimiento.' a:not([rel=fancybox], .item-link)',
                        'enablePushState' => false,
                        //'enableReplaceState' => false,

                    ],
                ],
                'panel'=>[
                    'type'=>GridView::TYPE_PRIMARY,
                    'heading'=>"<i class='glyphicon glyphicon-list'></i> Socios Comunitarios Institucionales",
                    'after'=>"",
                    'footer'=> false,
                ],
                'headerRowOptions'=>['class'=>'kartik-sheet-style'],
                'filterRowOptions'=>['class'=>'kartik-sheet-style'],
                // set your toolbar
                'toolbar'=> [
                    ['content'=>
                       Html::a('<i class="glyphicon glyphicon-repeat"></i>', [''],['data-pjax'=>1, 'class'=>'btn btn-default', 'title'=>'Reset Grid']).
                        '{toggleData}'
                    ],
                    //'{toggleData}',
                    //'{export}',
                ],
                'condensed' => true,
                'columns' => [
                 /*   [
                        'class' => 'kartik\grid\CheckboxColumn',
                        'width' => '20px',
                    ],*/
                    [
                        'class' => 'kartik\grid\SerialColumn',
                        'width' => '30px',
                    ],
                    //'id_sci',
                    'nombre',
                    [
                        'class'=>'\kartik\grid\DataColumn',
                        'attribute'=>'sci_comuna',
                        'label' => 'Comuna',
                        'value'=>function ($model, $index, $widget) { return $model->comunaComuna->comuna_nombre; }
                    ],
                    [
                        'class'=>'\kartik\grid\DataColumn',
                        'attribute'=>'sci_sede',
                        'label' => 'Sede',
                        'value'=>function ($model, $index, $widget) { return $model->sedeIdSede->nombre_sede; }
                    ],
                    [
                        'class' => 'kartik\grid\ActionColumn',
                        'dropdown' => false,
                        'header' => "Detalle Socio Inst.",
                        'vAlign'=>'middle',
                        'urlCreator' => function($action, $model, $key, $index) {
                            return Url::to(["/sci/view-ajax",'id'=>$key]);
                        },
                        'template' => '{view}',
                        'viewOptions'=>['role'=>'modal-remote','title'=>'Ver','data-pjax'=>1,'data-toggle'=>'tooltip'],
                        'updateOptions'=>['role'=>'modal-remotex','title'=>'Modificar', 'data-toggle'=>'tooltip'],
                    ],
              /*      [
                        'class'=>'\kartik\grid\DataColumn',
                        'label' => 'Requerimientos no Asignados (Sin Servicio)',
                        'mergeHeader'=>true,
                        'format'=>'html',
                        'value'=>function ($model, $index, $widget) {
                           return '<span class="label label-danger">'.$model->getRequerimientosNoAsignados()->count().'</span>';
                        },
                        'hAlign' => GridView::ALIGN_CENTER,

                    ],*/
                    //TOTAL REQUERIMIENTOS NO ASIGNADOS
                    [
                        'class'=>'\kartik\grid\DataColumn',
                        'label' => 'Total Requerimientos (Etapa 1)',
                        'mergeHeader'=>true,
                        'format'=>'html',
                        'value'=>function ($model, $index, $widget) {
                            return '<span class="label label-primary">'.$model->requerimientosNoAsignados.'</span>';
                        },
                        'hAlign' => GridView::ALIGN_CENTER,

                    ],
                    [
                        'class'=>'\kartik\grid\DataColumn',
                        'label' => 'Requerimientos Sin Asignaturas (Match 1)',
                        'mergeHeader'=>true,
                        'format'=>'html',
                        'value'=>function ($model, $index, $widget) {
                            return '<span class="label label-success">'.
                                ($model->requerimientosNoAsignadosYNoPreseleccionados).'</span>';
                        },
                        'hAlign' => GridView::ALIGN_CENTER,

                    ],
                    [
                        'class' => 'kartik\grid\ActionColumn',
                        'header' => "Siguiente",
                        'dropdown' => false,
                        'mergeHeader'=>true,
                        'vAlign'=>'middle',
                        'buttons' => [
                            'view' => function ($url, $model) {
                                return Html::a('<i class="fa fa-2x fa-chevron-circle-right"></i>',
                                    ['/match1/seleccion2', 'id' => $model->id_sci] ,
                                    ['class' => 'btn btn-primary boton-seleccion-sci', 'name' => $model->nombreComuna,
                                        'title'=>'Seleccionar', 'data-pjax'=>"0", 'data-toggle'=>"tooltip"]);
                            },
                        ],
                        'template' => '{view}',
                        'viewOptions'=>['class'=>'boton-view-sci','title'=>'Ver','data-toggle'=>'tooltip'],
                        /*'updateOptions'=>['role'=>'modal-remoteX','title'=>'Modificar', 'data-toggle'=>'tooltip'],
                        'deleteOptions'=>['role'=>'modal-remote','title'=>'Eliminar',
                            'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
                            'data-request-method'=>'post',
                            'data-toggle'=>'tooltip',
                            'data-confirm-title'=>'Confirmación',
                            'data-confirm-message'=>'Está seguro que desea eliminar este elemento?'],*/
                    ],
                    // 'comuna_comuna_id',
                    // 'creado_en',
                    // 'modificado_en',


                ],

            ]); ?>
        </div>
        <?php Modal::begin([
            'header' => '<h4 id="header-modal-sci"></h4>',
            'id' => 'modal-sci',
            'size' => 'modal-lg',
        ]);
        echo '<div id="contenido-modal-sci"></div>';
        Modal::end();?>

    </div>

<?php Modal::begin([
    "id"=>"ajaxCrudModal",
    "footer"=>"",// always need it for jquery plugin
])?>
<?php Modal::end(); ?>