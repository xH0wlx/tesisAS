<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\match1 */
?>
<div class="match1-view">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id_match1',
            'requerimiento_id_requerimiento',
            'asignatura_cod_asignatura',
            'anio_match1',
            'semestre_match1',
            'servicio_id_servicio',
            'aprobacion_implementacion',
            'creado_en',
            'modificado_en',
        ],
    ]) ?>

</div>