<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\oferta */
?>
<div class="oferta-update">

    <?= $this->render('_form', [
        'model' => $model,
        'modelsServicio' => $modelsServicio,
    ]) ?>

</div>
