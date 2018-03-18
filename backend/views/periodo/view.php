<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\periodo */
?>
<div class="periodo-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id_periodo',
            'anio',
            'semestre',
        ],
    ]) ?>

</div>
