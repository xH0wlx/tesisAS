<?php
/**
 * Created by PhpStorm.
 * User: Howl
 * Date: 06-06-2017
 * Time: 17:01
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\implementacion */
/* @var $form yii\widgets\ActiveForm */
$this->title = 'Nueva Implementación: Datos de la Asignatura';

?>

<div class="box box-primary">
    <div class="box-header with-border"><h3 class="box-title">Asignatura</h3>
    </div>
    <div class="box-body">
        <?= DetailView::widget([
            'model' => $asignatura,
            'attributes' => [
                'cod_asignatura',
                'nombre_asignatura',
                'semestre_dicta',
                'semestre_malla',
                'resultado_aprendizaje',
                [
                    'attribute'=>'carrera_cod_carrera',
                    'format'=>'raw',
                    'value'=>$asignatura->carreraCodCarrera->nombre_carrera,
                ],
                [
                    'label'=>'Sede',
                    'format'=>'raw',
                    'value'=>$asignatura->carreraCodCarrera->facultadIdFacultad->sedeIdSede->nombre_sede,
                ],
                //'carreraCodCarrera.nombre_carrera',
            ],
        ]) ?>
    </div>
</div>


<div class="panel panel-primary">
    <div class="panel-heading">Datos de Implementación</div>
    <div class="panel-body">
        <?php $form = ActiveForm::begin([
            //'action' => Url::to(['implementacion/paso-uno']),
        ]); ?>

        <?= $form->field($model, 'asignatura_cod_asignatura')->textInput(['disabled' => false]) ?>

        <?= $form->field($model, 'anio_implementacion')->textInput(['maxlength' => true ,'disabled' => false]) ?>

        <?= $form->field($model, 'semestre_implementacion')->textInput(['maxlength' => true ,  'disabled' => false]) ?>

        <?= Html::submitButton('Guardar y Salir', ['name'=>'guardarYSalir', 'value'=>'index','class' =>'btn btn-primary']) ?>
        <?= Html::submitButton('Guardar y Continuar', ['name'=>'guardarYContinuar','value'=> 'paso-dos','class' => 'btn btn-primary']) ?>
        <?php ActiveForm::end(); ?>
    </div>
</div>
<div class="implementacion-form">


</div>
