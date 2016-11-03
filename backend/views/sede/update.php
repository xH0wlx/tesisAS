<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Sede */

$this->title = 'Update Sede: ' . $model->id_sede;
$this->params['breadcrumbs'][] = ['label' => 'Sedes', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_sede, 'url' => ['view', 'id' => $model->id_sede]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="sede-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
