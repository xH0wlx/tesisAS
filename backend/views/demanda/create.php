<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\demanda */
/* @var $modelRequerimiento backend\models\requerimiento Arreglo que viene del controlador */

$this->title = 'Crear Demanda';
$this->params['breadcrumbs'][] = ['label' => 'Demandas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="demanda-create">

    <?= $this->render('_form', [
        'model' => $model,
        'modelsRequerimiento' => $modelsRequerimiento,
    ]) ?>

</div>
