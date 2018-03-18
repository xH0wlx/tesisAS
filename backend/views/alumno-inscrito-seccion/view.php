<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\alumnoInscritoSeccion */
?>
<div class="alumno-inscrito-seccion-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id_alumno_inscrito_seccion',
            'alumno_rut_alumno',
            'seccion_id_seccion',
        ],
    ]) ?>

</div>
