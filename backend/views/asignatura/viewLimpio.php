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

   <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'cod_asignatura',
            'nombre_asignatura',
            'semestre_dicta',
            'semestre_malla',
            'carreraCodCarrera.nombre_carrera',
        ],
    ]) ?>

</div>
