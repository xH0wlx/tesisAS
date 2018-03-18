<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\carrera */
?>
<div class="carrera-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'cod_carrera',
            'nombre_carrera',
            'plan_carrera',
            'facultadIdFacultad.nombre_facultad',
            'facultadIdFacultad.sedeIdSede.nombre_sede'
        ],
    ]) ?>

</div>
