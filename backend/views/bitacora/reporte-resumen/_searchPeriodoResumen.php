<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
/* @var $this yii\web\View */
/* @var $model backend\models\search\BitacoraSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="bitacora-search">

    <div class="panel panel-primary">
        <div class="panel-heading"><span class="glyphicon glyphicon-filter"></span> Filtro</div>
        <div class="panel-body">
            <?php $form = ActiveForm::begin([
                'action' => \yii\helpers\Url::to(['bitacora/reporte-resumen']),
                'method' => 'get',
            ]); ?>
            <div class="row">
                <div class="col-md-3">
                    <?= $form->field($model, 'anio_desde') ?>
                </div>
                <div class="col-md-3">
                    <?= $form->field($model, 'anio_hasta') ?>
                </div>
                <div class="col-md-3">
                    <?= $form->field($model, 'reporte_asignatura')->widget(Select2::classname(), [
                        'data' => $model->asignaturaLista,
                        'language' => 'es',
                        'theme' => 'default',
                        'options' => ['placeholder' => 'Seleccione Asignatura ...'],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]) ?>
                </div>
                <div class="col-md-3">
                    <?= $form->field($model, 'reporte_sede')->widget(Select2::classname(), [
                        'data' => $model->sedeLista,
                        'language' => 'es',
                        'theme' => 'default',
                        'options' => ['placeholder' => 'Seleccione Sede ...'],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]) ?>
                </div>
            </div>

            <div class="form-group">
                <?= Html::submitButton('<i class="fa fa-search" aria-hidden="true"></i> Buscar', ['class' => 'btn btn-primary']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>

</div>
