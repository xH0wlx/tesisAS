<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
//use kartik\detail\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\asignatura */

$this->title = $model->nombre_asignatura;
$this->params['breadcrumbs'][] = ['label' => 'Asignaturas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="asignatura-view">

    <p>
        <?= Html::a('Lista', ['/asignatura/index'], ['class' => 'btn btn-info']) ?>
        <?= Html::a('Modificar', ['/asignatura/update', 'id' => $model->cod_asignatura], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Eliminar', ['/asignatura/delete', 'id' => $model->cod_asignatura], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Está seguro que desea eliminar esta Asignatura?',
                    'method' => 'post',
                ],
            ]) ?>
    </p>
    <div class="box box-primary">
   <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'cod_asignatura',
            'nombre_asignatura',
            'semestre_dicta',
            'semestre_malla',
            'resultado_aprendizaje',
            [
                'attribute'=>'carrera_cod_carrera',
                'format'=>'raw',
                'value'=>$model->carreraCodCarrera->nombre_carrera,
            ],
            [
                'label'=>'Sede',
                'format'=>'raw',
                'value'=>$model->carreraCodCarrera->facultadIdFacultad->sedeIdSede->nombre_sede,
            ],
            //'carreraCodCarrera.nombre_carrera',
        ],
    ]) ?>
    </div>

    <!--<?/*=DetailView::widget([
        'model'=>$model,
        'condensed'=>true,
        'hover'=>true,
        'mode'=>DetailView::MODE_VIEW,
        'panel'=>[
            'heading'=>'Asignatura: ' . $model->nombre_asignatura,
            'type'=>DetailView::TYPE_INFO,
        ],
        'attributes'=>[
            'cod_asignatura',
            //'nombre_asignatura',
            'semestre_dicta',
            'semestre_malla',
            [
                'attribute'=>'carrera_cod_carrera',
                'format'=>'raw',
                'value'=>$model->carreraCodCarrera->nombre_carrera,
            ],
            'resultado_aprendizaje',
            //'carreraCodCarrera.nombre_carrera',
            //['attribute'=>'publish_date', 'type'=>DetailView::INPUT_DATE],
        ]
    ]);*/?>-->

</div>
