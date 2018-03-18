<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\sci */

$this->title = 'Modificar: ' . $model->nombre;
$this->params['breadcrumbs'][] = ['label' => 'Socio Comunitario Institucional', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->nombre, 'url' => ['view', 'id' => $model->id_sci]];
$this->params['breadcrumbs'][] = 'Modificar Socio Comunitario Institucional';
?>
<div class="sci-update">

 <!--   <h1><?/*= Html::encode($this->title) */?></h1>-->

    <?= $this->render('_form', [
        'model' => $model,
        'modelsRequerimiento' => $modelsRequerimiento,
        'modelsContactoSci' => $modelsContactoSci,
        'modelsContactoWeb' => $modelsContactoWeb,
    ]) ?>

</div>
