<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\demanda */
/* $modelRequerimiento viene del controlador actionUpdate*/

$this->title = 'Modificar Demanda: ' . $model->id_demanda;
$this->params['breadcrumbs'][] = ['label' => 'Demandas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_demanda, 'url' => ['view', 'id' => $model->id_demanda]];
$this->params['breadcrumbs'][] = 'Modificar';
?>
<div class="demanda-update">

    <?= $this->render('_form', [
        'model' => $model,
        'modelsRequerimiento' => $modelsRequerimiento,
    ]) ?>

</div>
