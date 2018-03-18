<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\sci */

$this->title = 'Crear Socio Comunitario Institucional';
$this->params['breadcrumbs'][] = ['label' => 'Socio Comunitario Insitucional', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sci-create">


    <?= $this->render('_form', [
        'model' => $model,
        'modelsRequerimiento' => $modelsRequerimiento,
        'modelsContactoSci' => $modelsContactoSci,
        'modelsContactoWeb' => $modelsContactoWeb,
    ]) ?>

</div>
