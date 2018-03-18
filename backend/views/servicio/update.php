<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\servicio */
$this->title = 'Modificar Servicio';
$this->params['breadcrumbs'][] = ['label' => 'Servicios', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="servicio-update">

    <?= $this->render('_form', [
        'model' => $model,
        'docentesHasServicio' => $docentesHasServicio,
    ]) ?>

</div>
