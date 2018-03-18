<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\sede */

$this->title = 'Crear Sede';
$this->params['breadcrumbs'][] = ['label' => 'Sedes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="sede-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
