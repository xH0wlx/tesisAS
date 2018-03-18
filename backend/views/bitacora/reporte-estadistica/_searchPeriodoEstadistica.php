<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\search\BitacoraSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="bitacora-search">

    <div class="panel panel-primary">
        <div class="panel-heading"><span class="glyphicon glyphicon-filter"></span> Filtro</div>
        <div class="panel-body">
            <?php $form = ActiveForm::begin([
                'action' => \yii\helpers\Url::to(['bitacora/reporte-estadistica']),
                'method' => 'get',
            ]); ?>
            <div class="row">
                <div class="col-md-3">
                    <?= $form->field($model, 'anio_desde')?>
                </div>
                <div class="col-md-3">
                    <?= $form->field($model, 'anio_hasta')?>
                </div>
                <div class="col-md-3">
                </div>
                <div class="col-md-3">
                </div>
            </div>

            <div class="form-group">
                <?= Html::submitButton('<i class="fa fa-search" aria-hidden="true"></i> Buscar', ['class' => 'btn btn-primary']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>

</div>
