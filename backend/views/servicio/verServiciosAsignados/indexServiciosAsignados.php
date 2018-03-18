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

$this->title = 'Ver Servicios Asignados';
$this->params['breadcrumbs'][] = $this->title;

CrudAsset::register($this);

?>
<div class="servicio-index">
    <div id="ajaxCrudDatatable">
        <?=GridView::widget([
            'id'=>'crud-datatable',
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'pjax'=>true,
            'columns' => require(__DIR__.'/_columns.php'),
            'toolbar'=> [
                ['content'=>
                    Html::a('<i class="glyphicon glyphicon-repeat"></i>', Url::to(['/servicio/index-servicios-asignados',
                        'anio'=>Yii::$app->request->get('anio'),
                        'semestre'=>Yii::$app->request->get('semestre'),]),
                    ['data-pjax'=>1, 'class'=>'btn btn-default', 'title'=>'Reset Grid']).
                    '{toggleData}'.
                    '{export}'
                ],
            ],          
            'striped' => true,
            'condensed' => true,
            'responsive' => true,          
            'panel' => [
                'type' => 'primary', 
                'heading' => '<i class="glyphicon glyphicon-list"></i> Lista de servicios asignados por asignatura',
                'before'=>'',
                'after'=>BulkButtonWidget::widget([
                            'buttons'=>Html::a('<i class="glyphicon glyphicon-trash"></i>&nbsp; Borrar Asignación',
                                ["bulk-delete"] ,
                                [
                                    "class"=>"btn btn-danger btn-xs",
                                    'role'=>'modal-remote-bulk',
                                    'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
                                    'data-request-method'=>'post',
                                    'data-confirm-title'=>'Confirmación',
                                    'data-confirm-message'=>'Esta seguro que desea eliminar la asignación del servicio a esta asignatura?'
                                ]),
                        ]).                        
                        '<div class="clearfix"></div>',
            ]
        ])?>
    </div>
</div>
<?php Modal::begin([
    "id"=>"ajaxCrudModal",
    "footer"=>"",// always need it for jquery plugin
])?>
<?php Modal::end(); ?>
