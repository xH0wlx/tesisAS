<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\match1 */
$this->title = 'Modificar Match: Periodo';
$this->params['breadcrumbs'][] = ['label' => 'Principal', 'url' => ['/match1/principal']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="match1-update">
    <?= $this->render('seleccion', [
        'modeloPeriodo' => $modeloPeriodo,
    ]) ?>

</div>
