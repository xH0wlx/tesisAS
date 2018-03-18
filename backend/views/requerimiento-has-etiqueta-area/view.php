<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\requerimientoHasEtiquetaArea */
?>
<div class="requerimiento-has-etiqueta-area-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'requerimiento_id_requerimiento',
            'etiqueta_area_id_etiqueta_area',
        ],
    ]) ?>

</div>
