<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset;
use johnitvn\ajaxcrud\BulkButtonWidget;
use yii\data\SqlDataProvider;
use yii\widgets\Pjax;
use backend\models\Requerimiento;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\RequerimientoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Asignación';
$this->params['breadcrumbs'][] = $this->title;

CrudAsset::register($this);
$js = '
    $(\'.modalButton\').click(function(){
        $(\'#modal\').modal(\'show\')
        .find(\'#modalContent\')
        .load($(this).attr(\'value\'));
    });
    
    var selectedItems = [];

    $(\'.crud-datatable\').click(function (){
    //selectedItems.concat()
        //selectedItems = $(\'#\'+$(this).data(\'key\')+\'\'\').yiiGridView(\'getSelectedRows\');
        // select all rows on page 1, go to page 2 and select all rows.
        // All rows on page 1 and 2 will be selected.
        //console.log(selectedItems);
    });
    
    $(\'h1\').click(function (){
        $.ajax({
            url:\''.\Yii::$app->urlManager->createUrl(['match1/service-match1']).'\',
            data:{},
            type: \'POST\',
            dataType: \'html\',
            success: function(lehtml){
                $(\'#prueba\').html(lehtml);
            },
            error: function(xhr, status){
                alert(\'Existe un problema\');
            },
            complete: function(xhr, status){
                alert(\'Se ejecutó\');
            },
        });
    });
    
';
$this->registerJs($js);


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

<div><?php
    //var_dump($_SESSION);
    /*$session = Yii::$app->session;
    if ($session->has($modelRequerimiento->id_requerimiento)){
        $arregloItems = $session[$modelRequerimiento->id_requerimiento];
        var_dump($arregloItems);
    }*/
    ?></div>

    <?php /*if (Yii::$app->session->hasFlash('success')): */?><!--
        <div class="alert alert-success alert-dismissable">
            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
            <h4><i class="icon fa fa-check"></i>Saved!</h4>
            <?/*= Yii::$app->session->getFlash('success') */?>
        </div>
    --><?php /*endif; */?>
<div class="alert alert-info alert-dismissible">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
    <h4><i class="icon fa fa-info"></i> Información</h4>
    Las asignaturas propuestas por el sistema se despliegan con el botón <span class="glyphicon glyphicon-expand"></span>.
</div>
<div id="ajaxCrudDatatable">
<?= GridView::widget([
    'id'=>'crud-datatable',
    'dataProvider' => $dataProviderRequerimientos,
    //'hover' => true,
    //'pjax' => true,
    /*'pjaxSettings'=>[
        'options' => [
            'id' => 'crud-datatable',
            //'neverTimeout'=>true,
            //'linkSelector' => '#'.$modelRequerimiento->id_requerimiento.' a:not([rel=fancybox], .item-link)',
            'enablePushState' => false
        ],
    ],*/
    //'filterModel' => $searchModel,
    'tableOptions' =>[
        'style'=>' text-align: justify; /*text-justify: inter-word;*/'
    ],
    //'perfectScrollbar' => true,
    'panel'=>[
        'type'=>GridView::TYPE_PRIMARY,
        'heading'=>"<i class='glyphicon glyphicon-book'></i> Listado de Requerimientos No Asignados",
    ],
    'headerRowOptions'=>['class'=>'kartik-sheet-style'],
    'filterRowOptions'=>['class'=>'kartik-sheet-style'],
    // set your toolbar
    'toolbar'=> [
        ['content'=>
            Html::a('<i class="glyphicon glyphicon-repeat"></i>', [''],['data-pjax'=>1, 'class'=>'btn btn-default', 'title'=>'Reset Grid'])/*.
            Html::button('<i class="glyphicon glyphicon-plus"></i> Crear Nuevo',
                [
                    'type'=>'button',
                    'title'=>'Crear SCI',
                    'class'=>'btn btn-success',
                    'onclick'=>"window.location.href = '" . \Yii::$app->urlManager->createUrl(['sci/create']) . "';",
                ]) . ' '*/
        ],
        '{toggleData}',
        //'{export}',
    ],
    'condensed' => true,
    'columns' => [
        //EXPAND ROW 1
        [
            'class' => '\kartik\grid\ExpandRowColumn',
            'detailRowCssClass' => GridView::TYPE_PRIMARY,
            //'expandIcon' => 'Resultados +',
            //'collapseIcon' => 'Resultados -',
            //'header' => 'Resultados',
            //'headerOptions' => ['style' => 'color:#337ab7; font-size:1em;'],
            'expandTitle' => 'Resultados',
            'value' => function($model, $key, $index, $column){
                return GridView::ROW_COLLAPSED;
            },
            'detail' => function($model, $key, $index, $column){
                //$column es la instancia del expand column, ej $column->expandIcon EXTRA DATA
                $u= \yii\helpers\StringHelper::basename(get_class($model));
                //var_dump($column->expandIcon);

                $tags = str_replace(',', '', $model->tagValues);
                $query = \backend\models\Asignatura::find();//new yii\db\Query();
                $query//->addSelect("*, MATCH(nombre_asignatura, resultado_aprendizaje) AGAINST (:search IN BOOLEAN MODE) AS score")
                    //->from('asignatura')
                    ->joinWith('carreraCodCarrera.facultadIdFacultad.sedeIdSede')
                    ->where("MATCH(nombre_asignatura, resultado_aprendizaje) AGAINST (:search IN BOOLEAN MODE)")
                    ->andWhere('nombre_sede = :nombreSede')
                    ->addParams([':search' => $tags, ':nombreSede' => $model->sciIdSci->sedeIdSede->nombre_sede ]);
                    //->orderBy(['score' => SORT_DESC]);

                $provider = new yii\data\ActiveDataProvider([
                    'query' => $query,
                    /*'key' => function ($asignatura) {
                        return $asignatura['cod_asignatura'];
                    },*/
                    'pagination' => [
                        //'route' => null,
                        'pageParam' => 'page'.$model->id_requerimiento,
                        //'params' => $params,
                        'pageSize' => 10,
                    ],
                ]);

                //var_dump($provider->getModels());

                return Yii::$app->controller->renderPartial('//asignatura/_vistaAsignaturas',[
                    // 'searchModel' => $searchModel,
                    'dataProvider' => $provider,
                    'modelRequerimiento' => $model,
                ]);
            },
        ],
        [
            'class' => 'kartik\grid\ActionColumn',
            'header' => '<i class="glyphicon glyphicon-eye-open"></i> Detalle',
            'headerOptions' => ['style' => 'color:#337ab7'],
            'template' => '{view}',
            'buttons' => [
                'view' => function ($url, $model) {
                    return Html::button('Ver',
                        [
                            //'id' => 'modalButton',
                            'value' => $url,
                            'class'=>'modalButton btn btn-success',
                            'type'=>'button',
                            'title'=>'Ver',
                            //'onclick'=>"window.location.href = '" . \Yii::$app->urlManager->createUrl(['requerimiento/create']) . "';",
                        ]);
                },
            ],
            'urlCreator' => function ($action, $model, $key, $index) {
                if ($action === 'view') {
                    $url =\Yii::$app->urlManager->createUrl(['/requerimiento/view2?id=']).$model->id_requerimiento;
                    return $url;
                }
            }
        ],
        [
            'class' => 'yii\grid\CheckboxColumn',
            'name' => 'crud-table'
            //'rowSelectedClass' => GridView::TYPE_SUCCESS,
        ],
        ['class'=>'kartik\grid\SerialColumn'],
        [
            'class'=>'kartik\grid\DataColumn',
            'attribute' => 'sci_id_sci',
            'value' => 'sciIdSci.nombre',
            'label' => 'SCI',
        ],
        [
            'class'=>'kartik\grid\DataColumn',
            'attribute' => 'titulo',
            'contentOptions' =>
                [
                    'style'=>'/*max-width: 450px;*/ overflow: auto; word-wrap: break-word; white-space: pre-wrap;'
                ],
        ],
        [
            'attribute' => 'descripcion',
            'contentOptions' =>
                [
                        'style'=>'max-width: 450px; max-height: 20px; overflow: auto; word-wrap: break-word; white-space: pre-wrap;'
                ],
        ],
        [
            //'class' =>'',
            'attribute' => 'estado_ejecucion_id_estado',
            //'contentOptions' =>['class' => 'label label-success','style'=>'display:block;'],
            'format' => 'html',
            'value'=>function ($model, $index, $widget) {
                $color = 'info';
                if(strcmp($model->estadoEjecucionIdEstado->nombre_estado, "No Asignado") == 0){
                    $color = 'danger';
                }
                if(strcmp($model->estadoEjecucionIdEstado->nombre_estado, "Asignado") == 0){
                    $color = 'warning';
                }
                if(strcmp($model->estadoEjecucionIdEstado->nombre_estado, "Finalizado") == 0){
                    $color = 'success';
                }
                return '<span class="label label-'.$color.'">'.$model->estadoEjecucionIdEstado->nombre_estado.'</span>';
            },
        ],
        [
            'attribute' => 'tagValues',
            'format' => 'html',
            'value'=>function ($model, $index, $widget) {
                $arreglo = explode(",", $model->tagValues);
                $cadena = '';
                foreach ($arreglo as $tag){
                    $cadena = $cadena.'<span class="tag label label-info">'.$tag.'</span> ';
                }

                return $cadena;
            },
        ],
        // 'creado_en',
        // 'modificado_en',

        [
            'class' => 'kartik\grid\ActionColumn',
            'headerOptions' => ['style' => 'color:#337ab7'],
            'dropdown' => false,
            'vAlign'=>'middle',
            'urlCreator' => function($action, $model, $key, $index) {
                return Url::to(['requerimiento/'.$action,'id'=>$key]);
            },
            'viewOptions'=>['role'=>'modal-remote','title'=>'View','data-toggle'=>'tooltip'],
            'updateOptions'=>['role'=>'modal-remote','title'=>'Update', 'data-toggle'=>'tooltip'],
            'deleteOptions'=>['role'=>'modal-remote','title'=>'Delete',
                'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
                'data-request-method'=>'post',
                'data-toggle'=>'tooltip',
                'data-confirm-title'=>'Are you sure?',
                'data-confirm-message'=>'Are you sure want to delete this item'],
        ],

        /*[
            'class' => 'kartik\grid\ActionColumn',
            'header' => 'Actions',
            'headerOptions' => ['style' => 'color:#337ab7'],
            'template' => '{view}{update}{delete}',
            'buttons' => [
                'view' => function ($url, $model) {
                    return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, [
                        'title' => 'Ver',
                    ]);
                },

                'update' => function ($url, $model) {
                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                        'title' => 'Modificar',
                    ]);
                },
                'delete' => function ($url, $model) {
                    return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                        'title' => 'Eliminar',
                    ]);
                }

            ],
            'urlCreator' => function ($action, $model, $key, $index) {
                if ($action === 'view') {
                    $url =\Yii::$app->urlManager->createUrl(['/requerimiento/view?id=']).$model->id_requerimiento;
                    return $url;
                }

                if ($action === 'update') {
                    $url = Url::to(['requerimiento/'.$action,'id'=> $model->id_requerimiento]);
                    return $url;
                }
                if ($action === 'delete') {
                    $url ='index.php?r=client-login/lead-delete&id=';
                    return $url;
                }
            }
        ],*/
    ],

]); ?>
</div>
<?php Modal::begin([
    "id"=>"ajaxCrudModal",
    "footer"=>"",// always need it for jquery plugin
])?>
<?php Modal::end(); ?>

<div id="prueba">
    HOLA :D
</div>