<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\implementacion */
?>
<div class="implementacion-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id_implementacion',
            'asignatura_cod_asignatura',
            'anio_implementacion',
            'semestre_implementacion',
        ],
    ]) ?>

</div>
