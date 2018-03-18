<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\sede */
?>
<div class="sede-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id_sede',
            'nombre_sede',
        ],
    ]) ?>

</div>
