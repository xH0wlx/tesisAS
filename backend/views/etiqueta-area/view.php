<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\etiquetaArea */
?>
<div class="etiqueta-area-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id_etiqueta_area',
            'nombre_etiqueta_area',
            'descripcion_etiqueta',
            'frecuencia',
        ],
    ]) ?>

</div>
