<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\alumnoInscritoLider */
?>
<div class="alumno-inscrito-lider-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'alumno_inscrito_seccion_id_seccion_alumno',
            'grupo_trabajo_id_grupo_trabajo',
            'fecha_creacion',
        ],
    ]) ?>

</div>
