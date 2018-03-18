<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\facultad */
?>
<div class="facultad-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id_facultad',
            'nombre_facultad',
            'sede_id_sede',
        ],
    ]) ?>

</div>
