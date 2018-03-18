<?php

use yii\helpers\Html;
use backend\models\contactoscb;

/* @var $this yii\web\View */
/* @var $model backend\models\scb */
$this->title = 'Modificar: ' . $model->nombre_negocio;
$this->params['breadcrumbs'][] = ['label' => 'Socio Comunitario Beneficiario', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->nombre_negocio, 'url' => ['view', 'id' => $model->id_scb]];
$this->params['breadcrumbs'][] = 'Modificar';
?>
<div class="scb-update">

    <?= $this->render('_form', [
        'model' => $model,
        'modelsContactoScb' => (empty($modelsContactoScb)) ? [new contactoScb] : $modelsContactoScb,
    ]) ?>

</div>
