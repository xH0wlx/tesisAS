<?php

use yii\helpers\Html;
//use yii\widgets\DetailView;
use kartik\detail\DetailView;
use yii\helpers\ArrayHelper;
use backend\models\ContactoSci;
use backend\models\Sci;
use yii\widgets\ListView;
use yii\data\ActiveDataProvider;

/* @var $this yii\web\View */
/* @var $model backend\models\sci */

$this->title = $model->nombre;
$this->params['breadcrumbs'][] = ['label' => 'Socio Comunitario Institucional', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sci-view">

    <!--<h1><?/*= Html::encode($this->title) */?></h1>
-->
    <p>
        <?= Html::a('<i class="fa fa-list"></i> Ir a Listado de Socios Inst.', ['index'], ['class' => 'btn btn-info']) ?>
        <?= Html::a('<i class="fa fa-edit"></i> Modificar Socio Inst.', ['update', 'id' => $model->id_sci], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('<i class="fa fa-trash"></i> Eliminar Socio Inst.', ['delete', 'id' => $model->id_sci], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Está seguro que desea eliminar este Socio Comunitario Institucional?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <div class="nav-tabs-custom">

        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active">
                <a href="#sci" aria-controls="sci" role="tab" data-toggle="tab"><i class="glyphicon glyphicon-user"></i> Datos SCI</a>
            </li>
            <li role="presentation">
                <a href="#contacto" aria-controls="contacto" role="tab" data-toggle="tab"><i class="glyphicon glyphicon-phone-alt"></i> Contacto</a>
            </li>
            <li role="presentation">
                <a href="#requerimiento" aria-controls="requerimiento" role="tab" data-toggle="tab"><i class="glyphicon glyphicon-tasks"></i> Requerimiento</a>
            </li>
        </ul>

        <div class="tab-content">
            <div role="tabpanel" class="tab-pane fade in active" id="sci">
                <?=DetailView::widget([
                    'model'=>$model,
                    'condensed'=>true,
                    'hover'=>true,
                    'buttons1' => "",
                    'buttons2' => "",
                    'mode'=>DetailView::MODE_VIEW,
                    'panel'=>[
                        'heading'=>'Socio Institucional: ' . $model->nombreComuna,
                        'type'=>DetailView::TYPE_PRIMARY,
                    ],
                    'attributes'=>[
                        //'id_sci',
                        'nombre',
                        'direccion',
                        'observacion',
                        'departamento_programa',
                        [
                            'attribute'=>'comuna_comuna_id',
                            //'format'=>'raw',
                            'value'=>$model->comunaComuna->comuna_nombre,
                        ],
                        [
                            //'attribute'=>'sede_id_sede',
                            'label' => 'Dirección Web',
                            'format'=>'html',
                            'value'=>function ($form, $widget){
                                $model = $widget->model;
                                $contactos = $model->contactoWebs;
                                $retornar = "";
                                foreach ($contactos as $contacto){
                                    $retornar = $retornar.$contacto->direccion_web."<br>";
                                }
                                return $retornar;
                            },
                        ],
                        [
                            'attribute'=>'sede_id_sede',
                            'label' => 'Sede (U. del Bío Bío)',
                            //'format'=>'raw',
                            'value'=>$model->sedeIdSede->nombre_sede,
                        ],
                    ]
                ]);?>
            </div>
            <div role="tabpanel" class="tab-pane fade" id="contacto">
                <?=
                ListView::widget([
                    'dataProvider' => $dataProviderContactoSci,
                    'itemView' => '//contacto-sci/_view',
                ]);
                ?>
            </div>
            <div role="tabpanel" class="tab-pane fade" id="requerimiento">
                <?php
                /*ListView::widget([
                    'dataProvider' => $dataProviderRequerimiento,
                    'itemView' => '//requerimiento/_view',
                ]);*/
                ?>
                <?php $searchModel = new \backend\models\search\RequerimientoSearch();
                $dataProvider = $searchModel->searchNoEliminados(Yii::$app->request->queryParams);
                $dataProvider->query->andFilterWhere(['sci_id_sci'=>$model->id_sci]);?>
                <?php echo $this->render('//requerimiento/_index', ['searchModel' => $searchModel, 'dataProvider'=>$dataProvider]); ?>

            </div>

            <div role="tabpanel" class="tab-pane fade" id="settings">...</div>

        </div><!-- TAB CONTENT -->
    </div>







</div>
