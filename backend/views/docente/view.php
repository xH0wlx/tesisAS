<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\docente */
?>
<div class="docente-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'rut_docente',
            'nombre_completo',
            'email:email',
            'telefono',
        ],
    ]) ?>

</div>
