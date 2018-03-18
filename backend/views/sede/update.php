<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\sede */

$this->title = 'Modificar Sede: ' . $model->nombre_sede;
$this->params['breadcrumbs'][] = ['label' => 'Sedes', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->nombre_sede, 'url' => ['view', 'id' => $model->id_sede]];
$this->params['breadcrumbs'][] = 'Modificar';
?>
<div class="sede-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
