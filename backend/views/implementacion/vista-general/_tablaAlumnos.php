<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;


/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\AlumnoInscritoSeccionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$request = Yii::$app->request;

//$this->registerJsFile('@web/js/implementacion/funciones.js', ['depends' => [\yii\web\JqueryAsset::className()] ] );

?>
    <div class="alumno-inscrito-seccion-index">
            <?=GridView::widget([
                //'id'=>'crud-datatable',
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'pjax'=>true,
                'pjaxSettings'=>[
                    'options' => [
                        'id' => $key,
                        //'neverTimeout'=>true,
                        //'linkSelector' => '#'.$modelRequerimiento->id_requerimiento.' a:not([rel=fancybox], .item-link)',
                        'enablePushState' => false
                    ],
                ],
                'columns' => require(__DIR__.'/_columns.php'),
                'toolbar'=> [
                    ['content'=>
                        Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['/implementacion/vista-general', 'id'=>Yii::$app->request->get('id')],
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
                    'heading' => '<i class="glyphicon glyphicon-list"></i> Datos de la sección',
                    //'before'=>'<em>* Resize table columns just like a spreadsheet by dragging the column edges.</em>',
                    'after'=>"",
                ]
            ])?>
    </div>