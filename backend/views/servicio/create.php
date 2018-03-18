<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\servicio */

$this->title = 'Crear Servicio (Sin Match)';
$this->params['breadcrumbs'][] = ['label' => 'Servicios', 'url' => ['index-servicios-no-asignados']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="servicio-create">
    <?= $this->render('_form', [
        'model' => $model,
        'docentesHasServicio' => $docentesHasServicio,
    ]) ?>
</div>
