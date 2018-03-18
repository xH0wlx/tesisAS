<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\requerimiento */

$this->title = 'Crear Requerimiento';
$this->params['breadcrumbs'][] = ['label' => 'Requerimientos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="requerimiento-create">
    <?= $this->render('_form', [
        'modelsRequerimiento' => $modelsRequerimiento,
    ]) ?>
</div>
