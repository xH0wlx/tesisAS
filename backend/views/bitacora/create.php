<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\bitacora */
$this->title = 'Crear Bitácora';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bitacora-create">
    <?= $this->render('_form', [
        'model' => $model,
        'modelEvidencia' => $modelEvidencia,
    ]) ?>
</div>
