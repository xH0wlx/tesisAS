<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\oferta */


$this->title = 'Crear Oferta';
$this->params['breadcrumbs'][] = ['label' => 'Oferta', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="oferta-create">
    <?= $this->render('_form', [
        'model' => $model,
        'modelsServicio' => $modelsServicio,
    ]) ?>
</div>
