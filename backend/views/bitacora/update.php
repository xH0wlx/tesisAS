<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\bitacora */
$this->title = 'Modificar Bitácora';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bitacora-update">

    <?= $this->render('_form', [
        'model' => $model,
        'modelEvidencia' => $modelEvidencia,
    ]) ?>

</div>
