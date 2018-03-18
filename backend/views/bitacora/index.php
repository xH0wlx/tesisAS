<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset; 
use johnitvn\ajaxcrud\BulkButtonWidget;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\BitacoraSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Bitácoras';
$this->params['breadcrumbs'][] = $this->title;

CrudAsset::register($this);
if(Yii::$app->user->can('docente')){
    $boton = '';
}else{
    $boton = Html::a('<i class="glyphicon glyphicon-plus"></i>', ['create'],
        ['role'=>'modal-remote','title'=> 'Crear nueva Bitacora','class'=>'btn btn-default']);
    $boton = "";
}
?>

<?php
    if(Yii::$app->user->can('coordinador general')){
        echo $this->render('/bitacora/busquedaBitacoras/_searchBitacora', [
            'searchModel' => $searchModel,
        ]);
    }

?>

<div class="bitacora-index">
    <div id="ajaxCrudDatatable">
        <?=GridView::widget([
            'id'=>'crud-datatable',
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'pjax'=>true,
            'columns' => require(__DIR__.'/_columns.php'),
            'toolbar'=> [
                ['content'=>
                    $boton.
                    Html::a('<i class="glyphicon glyphicon-repeat"></i>', [''],
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
                'heading' => '<i class="glyphicon glyphicon-list"></i> Lista de Bitácoras',
                //'before'=>'<em>* Resize table columns just like a spreadsheet by dragging the column edges.</em>',
                'after'=>'',
            ]
        ])?>
    </div>
</div>
<?php Modal::begin([
    "id"=>"ajaxCrudModal",
    "footer"=>"",// always need it for jquery plugin
])?>
<?php Modal::end(); ?>
