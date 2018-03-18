<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\contactoSci */

$this->title = 'Modificar Contacto: ' . $model->nombres;
$this->params['breadcrumbs'][] = ['label' => 'Contacto Institución', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->nombres, 'url' => ['view', 'id' => $model->id_contacto_sci]];
$this->params['breadcrumbs'][] = 'Modificar';
?>
<div class="contacto-sci-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
