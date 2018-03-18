<?php

use yii\helpers\Html;
use backend\models\contactoscb;

/* @var $this yii\web\View */
/* @var $model backend\models\scb */
$this->title = 'Crear Socio Comunitario Beneficiario';
$this->params['breadcrumbs'][] = ['label' => 'Socio Comunitario Beneficiario', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="scb-create">
    <?= $this->render('_form', [
        'model' => $model,
        'modelsContactoScb' => (empty($modelsContactoScb)) ? [new contactoScb] : $modelsContactoScb,
    ]) ?>
</div>
