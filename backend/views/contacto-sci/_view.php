<?php

use yii\helpers\Html;
use kartik\detail\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\contactoSci */

?>
<div class="contacto-sci-view">
    <?=DetailView::widget([
        'model'=>$model,
        'condensed'=>true,
        'hover'=>true,
        'buttons1' => '',
        'buttons2' => '',
        'mode'=>DetailView::MODE_VIEW,
        'panel'=>[
            'heading'=>'Contacto',
            'type'=>DetailView::TYPE_PRIMARY,
        ],
        'attributes'=>[
            //'id_contacto_sci',
            'nombres',
            'apellidos',
            'telefono',
            'cargo',
            'email:email',
            //'sci_id_sci',
        ]
    ]);?>

</div>
