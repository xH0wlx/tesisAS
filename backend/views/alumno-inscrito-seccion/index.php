<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset; 
use johnitvn\ajaxcrud\BulkButtonWidget;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\AlumnoInscritoSeccionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$request = Yii::$app->request;

$this->title = 'Inscripción de Alumnos';
$this->params['breadcrumbs'][] = ['label' => 'Panel de Implementación',
    'url' => ['/implementacion/panel-implementacion', 'idImplementacion'=> $request->get('idImplementacion')]];
$this->params['breadcrumbs'][] = $this->title;

CrudAsset::register($this);

$this->registerJsFile('@web/js/implementacion/funciones.js', ['depends' => [\yii\web\JqueryAsset::className()] ] );

?>
<div class="alumno-inscrito-seccion-index">
    <div id="ajaxCrudDatatable">
        <?=GridView::widget([
            'id'=>'crud-datatable',
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'pjax'=>true,
            'columns' => require(__DIR__.'/_columns.php'),
            'toolbar'=> [
                ['content'=>
                    Html::a('<i class="glyphicon glyphicon-plus"></i> Inscribir un alumno', Url::toRoute(['/alumno-inscrito-seccion/create',
                        'idImplementacion'=>Yii::$app->request->get('idImplementacion'),
                        'idSeccion'=>Yii::$app->request->get('idSeccion')]),
                        ['role'=>'modal-remote','data-pjax'=>1,'title'=> 'Inscribir nuevo Alumno','class'=>'btn btn-success']).
                    Html::a('<i class="glyphicon glyphicon-plus"></i> Inscribir Alumnos vía Excel', Url::toRoute(['/implementacion/inscribir-alumnos',
                        'idImplementacion'=>Yii::$app->request->get('idImplementacion'),
                        'idSeccion'=>Yii::$app->request->get('idSeccion')]),
                        ['role'=>'modal-remoteX','data-pjax'=>0,'title'=> 'Inscribir Alumnos vía Excel','class'=>'btn btn-primary']).
                    Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['', 'idImplementacion'=>Yii::$app->request->get('idImplementacion'), 'idSeccion'=>Yii::$app->request->get('idSeccion')],
                    ['data-pjax'=>1, 'class'=>'btn btn-default', 'title'=>'Refrescar Tabla']).
                    '{toggleData}'.
                    '{export}'
                ],
            ],          
            'striped' => true,
            'condensed' => true,
            'responsive' => true,          
            'panel' => [
                'type' => 'primary', 
                'heading' => '<i class="glyphicon glyphicon-list"></i> Lista de alumnos inscritos en la sección',
                //'before'=>'<em>* Resize table columns just like a spreadsheet by dragging the column edges.</em>',
                'after'=>BulkButtonWidget::widget([
                            'buttons'=>Html::a('<i class="glyphicon glyphicon-trash"></i>&nbsp; Borrar Seleccionados',
                                ["bulk-delete"] ,
                                [
                                    "class"=>"btn btn-danger btn-xs",
                                    'role'=>'modal-remote-bulk',
                                    'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
                                    'data-request-method'=>'post',
                                    'data-confirm-title'=>'Confirmación',
                                    'data-confirm-message'=>'Está seguro de eliminar este/estos registro/s?'
                                ]),
                        ]).                        
                        '<div class="clearfix"></div>',
            ]
        ])?>
    </div>
    <?= Html::button('<i class="fa fa-chevron-circle-left"></i> Volver al panel de implementación', ['id' => 'botonVolver',
        'value'=> Url::to(['/implementacion/panel-implementacion', 'idImplementacion' => Yii::$app->request->get('idImplementacion')]),
        'class' =>'btn btn-danger']) ?>
</div>
<?php Modal::begin([
    "id"=>"ajaxCrudModal",
    "footer"=>"",// always need it for jquery plugin
])?>
<?php Modal::end(); ?>
