<?php

//use yii\widgets\DetailView;
use kartik\detail\DetailView;
use yii\helpers\ArrayHelper;
use backend\models\Servicio;
use yii\widgets\ListView;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\oferta */

$this->title = $model->id_oferta;
$this->params['breadcrumbs'][] = ['label' => 'Oferta', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="oferta-view">

    <p>
        <?= Html::a('Lista', ['index'], ['class' => 'btn btn-info']) ?>
        <?= Html::a('Modificar', ['update', 'id' => $model->id_oferta], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Eliminar', ['delete', 'id' => $model->id_oferta], [
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
                <a href="#oferta" aria-controls="oferta" role="tab" data-toggle="tab"><i class="glyphicon glyphicon glyphicon-education"></i> Oferta</a>
            </li>
            <li role="presentation">
                <a href="#servicio" aria-controls="servicio" role="tab" data-toggle="tab"><i class="glyphicon glyphicon glyphicon-tasks"></i> Servicio</a>
            </li>
        </ul>

        <div class="tab-content">
            <div role="tabpanel" class="tab-pane fade in active" id="oferta">
                <?=DetailView::widget([
                    'model'=>$model,
                    'condensed'=>true,
                    'hover'=>true,
                    'mode'=>DetailView::MODE_VIEW,
                    'panel'=>[
                        'heading'=>'Oferta: ' . $model->id_oferta,
                        'type'=>DetailView::TYPE_INFO,
                    ],
                    'attributes'=>[
                        //'id_oferta',
                        'periodo_id_periodo',
                        'asignatura_cod_asignatura',
                        //'creado_en',
                        //'modificado_en',
                        /*[
                            'attribute'=>'comuna_comuna_id',
                            //'format'=>'raw',
                            'value'=>$model->comunaComuna->comuna_nombre,
                        ],
                        [
                            'attribute'=>'sede_id_sede',
                            'label' => 'Sede (U. del Bío Bío)',
                            //'format'=>'raw',
                            'value'=>$model->sedeIdSede->nombre_sede,
                        ],*/
                    ]
                ]);?>
            </div>
            <div role="tabpanel" class="tab-pane fade" id="servicio">
                <?=
                ListView::widget([
                    'dataProvider' => $dataProviderServicio,
                    'itemView' => '//servicio/_view',
                ]);
                ?>
            </div>

            <div role="tabpanel" class="tab-pane fade" id="settings">...</div>

        </div><!-- TAB CONTENT -->
    </div>


</div>
