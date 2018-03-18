<?php

use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\helpers\Url;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset;
use johnitvn\ajaxcrud\BulkButtonWidget;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\AsignaturaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Asignaturas';
$this->params['breadcrumbs'][] = $this->title;

CrudAsset::register($this);

?>
<div class="asignatura-index">

    <!--<h1><?/*= Html::encode($this->title) */?></h1>
    <?php /*// echo $this->render('_search', ['model' => $searchModel]); */?>

    <p>
        <?/*= Html::a('Create Asignatura', ['create'], ['class' => 'btn btn-success']) */?>
    </p>-->
    <div id="ajaxCrudDatatable">
        <?=GridView::widget([
            'id'=>'crud-datatable',
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'pjax'=>true,
            'columns' => require(__DIR__.'/_columns.php'),
            'toolbar'=> [
                ['content'=>
                    /*Html::a('<i class="glyphicon glyphicon-plus"></i> Crear Asignatura', ['create'],
                        ['role'=>'modal-remoteX','title'=> 'Crear Nueva Asignatura','class'=>'btn btn-success']).*/
                    Html::button('<i class="glyphicon glyphicon-plus"></i> Crear Asignatura',
                        [
                            'type'=>'button',
                            'title'=>'Crear Nueva Asignatura',
                            'class'=>'btn btn-success',
                            'onclick'=>"window.location.href = '" . \Yii::$app->urlManager->createUrl(['asignatura/create']) . "';",
                        ]).
                    Html::a('<i class="glyphicon glyphicon-repeat"></i>', [''],
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
                'heading' => '<i class="glyphicon glyphicon-list"></i> Lista de Asignaturas',
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
                                'data-confirm-message'=>'Está seguro que desea eliminar este/estos registro/s?'
                            ]),
                    ]).
                    '<div class="clearfix"></div>',
            ]
        ])?>
    </div>
<?php /*Pjax::begin(); */?><!--    <?/*= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'cod_asignatura',
            'nombre_asignatura',
            'semestre_dicta',
            'semestre_malla',
            'carrera_id_carrera',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); */?>
<?php /*Pjax::end(); */?></div>-->

<?php Modal::begin([
    "id"=>"ajaxCrudModal",
    "footer"=>"",// always need it for jquery plugin
])?>
<?php Modal::end(); ?>
