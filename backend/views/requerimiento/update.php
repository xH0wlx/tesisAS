<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\requerimiento */

$this->title = 'Modificar Requerimiento';
$this->params['breadcrumbs'][] = ['label' => 'Requerimientos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="requerimiento-update">

    <?= $this->render('_formUpdate', [
        'model' => $model,
    ]) ?>

</div>
