<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\demanda */

$this->title = $model->id_demanda;
$this->params['breadcrumbs'][] = ['label' => 'Demandas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="demanda-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id_demanda], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id_demanda], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id_demanda',
            'perfil_estudiante:ntext',
            'apoyos_brindados:ntext',
            'observaciones:ntext',
            'fecha_creacion',
            'sci_id_sci',
        ],
    ]) ?>

</div>
