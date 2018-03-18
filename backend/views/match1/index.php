<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset; 
use johnitvn\ajaxcrud\BulkButtonWidget;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\Match1Search */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Match1s';
$this->params['breadcrumbs'][] = $this->title;

CrudAsset::register($this);

?>
<?php $form = ActiveForm::begin(); ?>
    <div class="box box-primary">
        <div class="box-heading">Periodo</div>
        <div class="box-body">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <?= $form->field($periodoModel, 'anio')->textInput() ?>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <?= $form->field($periodoModel, 'semestre')->textInput() ?>
                </div>
            </div>
            <div class="form-group">
                <?= Html::submitButton('Enviar', ['class' => 'btn btn-primary']) ?>
            </div>
        </div>
    </div>
<?php ActiveForm::end(); ?>
<div class="match1-index">
    <div id="ajaxCrudDatatable">
        <?=GridView::widget([
            'id'=>'crud-datatable',
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'pjax'=>true,
            'columns' => require(__DIR__.'/_columns.php'),
            'toolbar'=> [
                ['content'=>
                    /*Html::a('<i class="glyphicon glyphicon-plus"></i>', ['create'],
                    ['role'=>'modal-remote','title'=> 'Create new Match1s','class'=>'btn btn-default']).*/
                    Html::button('<i class="glyphicon glyphicon-plus"></i> Crear Match 1',
                        [
                            'type'=>'button',
                            'title'=>'Crear Match 1',
                            'class'=>'btn btn-success',
                            'onclick'=>"window.location.href = '" . \Yii::$app->urlManager->createUrl(['match1/asignacion']) . "';",
                        ]).
                    Html::a('<i class="glyphicon glyphicon-repeat"></i>', [''],
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
                'heading' => '<i class="glyphicon glyphicon-list"></i> Match1s listing',
                //'before'=>'<em>* Resize table columns just like a spreadsheet by dragging the column edges.</em>',
                'after'=>BulkButtonWidget::widget([
                            'buttons'=>Html::a('<i class="glyphicon glyphicon-trash"></i>&nbsp; Borrar Seleccionados',
                                ["bulk-delete"] ,
                                [
                                    "class"=>"btn btn-danger btn-xs",
                                    'role'=>'modal-remote-bulk',
                                    'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
                                    'data-request-method'=>'post',
                                    'data-confirm-title'=>'Are you sure?',
                                    'data-confirm-message'=>'Are you sure want to delete this item'
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
