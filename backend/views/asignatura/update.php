<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\asignatura */

$this->title = 'Modificar Asignatura: ' . $model->nombre_asignatura;
$this->params['breadcrumbs'][] = ['label' => 'Asignaturas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->nombre_asignatura, 'url' => ['view', 'id' => $model->cod_asignatura]];
$this->params['breadcrumbs'][] = 'Modificar';
?>
<div class="asignatura-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
