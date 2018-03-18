<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\alumno */
?>
<div class="alumno-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'rut_alumno',
            'nombre',
            'telefono',
            'email:email',
        ],
    ]) ?>

</div>
