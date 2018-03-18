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
                'action' => \yii\helpers\Url::to(['bitacora/index']),
                'method' => 'get',
            ]); ?>
            <div class="row">
                <div class="col-md-2">
                    <?= $form->field($searchModel, 'anio_desde')?>
                </div>
                <div class="col-md-2">
                    <?= $form->field($searchModel, 'anio_hasta')?>
                </div>
                <div class="col-md-3">
                    <?= $form->field($searchModel, 'asignatura_sede')?>
                </div>
                <div class="col-md-3">
                    <?= $form->field($searchModel, 'asignatura_nombre')?>
                </div>
                <div class="col-md-2">
                    <?= $form->field($searchModel, 'grupo_numero')?>
                </div>
            </div>

            <div class="form-group">
                <?= Html::submitButton('<i class="fa fa-search" aria-hidden="true"></i> Buscar', ['class' => 'btn btn-primary']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>

</div>
