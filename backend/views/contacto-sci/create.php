<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\contactoSci */

$this->title = 'Crear Contacto Socio C. Institucional';
$this->params['breadcrumbs'][] = ['label' => 'Contacto Institución', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="contacto-sci-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
