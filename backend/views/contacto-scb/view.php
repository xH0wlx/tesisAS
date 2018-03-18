<?php

use kartik\detail\DetailView;
/* @var $this yii\web\View */
/* @var $model backend\models\contactoScb */
?>
<div class="contacto-scb-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'condensed'=>true,
        'hover'=>true,
        'buttons1' => '',
        'buttons2' => '',
        'mode'=>DetailView::MODE_VIEW,
        'panel'=>[
            'heading'=>'Contacto',
            'type'=>DetailView::TYPE_PRIMARY,
        ],
        'attributes' => [
            'rut_beneficiario:rut',
            'nombre_completo',
            'email:email',
            'direccion',
            'telefono_celular',
            'telefono_fijo',
        ],
    ]) ?>

</div>
