<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\alumnoInscritoHasGrupoTrabajo */
?>
<div class="alumno-inscrito-has-grupo-trabajo-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'alumno_inscrito_seccion_id_alumno_inscrito_seccion',
            'grupo_trabajo_id_grupo_trabajo',
            'fecha_creacion',
            'observacion',
        ],
    ]) ?>

</div>
