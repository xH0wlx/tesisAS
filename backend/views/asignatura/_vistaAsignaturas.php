<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use yii\bootstrap\Modal;
use yii\helpers\Url;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\RequerimientoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$js = '
//$(document).ready(function() {   
$(\'.modalButton\').click(function(){
        $(\'#modal\').modal(\'show\')
        .find(\'#modalContent\')
        .load($(this).attr(\'value\'));
    });
    
 
 var selectedItems = [];

    $(\'#'.$modelRequerimiento->id_requerimiento.'\').click(function (){
    //selectedItems.concat()
        selectedItems = $(\'#'.$modelRequerimiento->id_requerimiento.'\').yiiGridView(\'getSelectedRows\');
        console.log(selectedItems);
          $.ajax({
               url: \''. Yii::$app->request->baseUrl.'/match1/test'.'\',
               type: \'post\',
               data: {
                         selectedItems: $(\'#'.$modelRequerimiento->id_requerimiento.'\').yiiGridView(\'getSelectedRows\'),
                         requerimientoPadre: '.$modelRequerimiento->id_requerimiento.',
                         _csrf : \''.  Yii::$app->request->getCsrfToken().'\'
                     },
               success: function (data) {
                  console.log(data.texto);
               }
          });
    });
    
//});   
 
  $(\'.guardar-asignaturas\').on("click", function(){
        console.log("HOLA HOLA PERINOLA");
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

<div class="requerimiento-index">
    <?= GridView::widget([
        'id' => $modelRequerimiento->id_requerimiento,
        'pjax' => true,
        'pjaxSettings'=>[
            'options' => [
                'id' => $modelRequerimiento->id_requerimiento,
                //'neverTimeout'=>true,
                //'linkSelector' => '#'.$modelRequerimiento->id_requerimiento.' a:not([rel=fancybox], .item-link)',
                'enablePushState' => true
            ],
        ],
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'panel'=>[
            'type'=>GridView::TYPE_INFO,
            'heading'=>"<i class='glyphicon glyphicon-search'>
</i> Resultados para \"".$modelRequerimiento->titulo."\".<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;En base a las siguientes palabras claves: \"".$modelRequerimiento->tagValues."\"<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Con sede en: ".$modelRequerimiento->sciIdSci->sedeIdSede->nombre_sede,

            'after'=> Html::button('<i class="glyphicon glyphicon-save"></i> Guardar Seleccionados', ['class' => 'guardar-asignaturas btn btn-primary']),
        ],
        'columns' => [
            /*[
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
                                'class'=>'modalButton btn btn-danger',
                                'type'=>'button',
                                'title'=>'Ver',
                                //'onclick'=>"window.location.href = '" . \Yii::$app->urlManager->createUrl(['requerimiento/create']) . "';",
                            ]);
                    },
                ],
                'urlCreator' => function ($action, $model, $key, $index) {
                    if ($action === 'view') {
                        $url =\Yii::$app->urlManager->createUrl(['/asignatura/view?id=']).$model->cod_asignatura;
                        return $url;
                    }
                }
            ],*/
            [
                'class' => '\kartik\grid\CheckboxColumn',
                'name' => $modelRequerimiento->id_requerimiento,
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
            [
                'header' => 'Button',
                'content' => function($model) {
                    return Html::a($text='Test',$url='https://www.google.com');
                }
            ],
            //'score',
            // 'observacion',
            // 'sci_id_sci',
            // 'estado_ejecucion_id_estado',
            // 'creado_en',
            // 'modificado_en',

            //['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
    </div>
