<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\seccion */
?>
<div class="seccion-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id_seccion',
            'numero_seccion',
            'implementacion_id_implementacion',
            'docente_rut_docente',
        ],
    ]) ?>

</div>
