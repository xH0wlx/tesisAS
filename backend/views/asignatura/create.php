<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\asignatura */

$this->title = 'Crear Asignatura';
$this->params['breadcrumbs'][] = ['label' => 'Asignaturas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="asignatura-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
