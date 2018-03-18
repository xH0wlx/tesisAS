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
$this->title = 'Confirmación: Detalle de la Asignatura';
$this->params['breadcrumbs'][] = ['label' => 'Selección Periodo', 'url' => ['/implementacion/seleccion-asignatura']];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="box box-primary">
    <div class="box-header with-border"><h3 class="box-title">Asignatura seleccionada</h3>
        <div class="box-tools pull-right">
            (Ver Detalle)<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
            </button>
        </div>
    </div>
    <div class="box-body">
        <?= DetailView::widget([
            'model' => $modelosMatch[0],
            'attributes' => [
                'asignaturaCodAsignatura.cod_asignatura',
                'asignaturaCodAsignatura.nombre_asignatura',
                'asignaturaCodAsignatura.semestre_dicta',
                'asignaturaCodAsignatura.semestre_malla',
                'asignaturaCodAsignatura.resultado_aprendizaje',
                [
                    'attribute'=>'carrera_cod_carrera',
                    'label' => 'Carrera',
                    'format'=>'raw',
                    'value'=>$modelosMatch[0]->asignaturaCodAsignatura->carreraCodCarrera->nombre_carrera,
                ],
                [
                    'label'=>'Sede',
                    'format'=>'raw',
                    'value'=>$modelosMatch[0]->asignaturaCodAsignatura->carreraCodCarrera->facultadIdFacultad->sedeIdSede->nombre_sede,
                ],
                //'carreraCodCarrera.nombre_carrera',
            ],
        ]) ?>
    </div>
</div>

<div class="box box-primary collapsed-box">
    <div class="box-header with-border"><h3 class="box-title">Requerimiento/s a Satisfacer</h3>
        <div class="box-tools pull-right">
            (Ver Detalle)<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
            </button>
        </div>
    </div>
    <div class="box-body">
        <?php
        $counter = 1;
        foreach ($modelosMatch as $modeloMatch){ ?>
            <h4 class="box-title"><b>Requerimiento N°<?= $counter?></b></h4>
            <?= DetailView::widget([
                'model' => $modeloMatch,
                'attributes' => [
                    [
                        'label'=>'Socio Comunitario Institucional',
                        'format'=>'raw',
                        'value'=>$modelosMatch[0]->requerimientoIdRequerimiento->sciIdSci->nombre,
                    ],
                    'requerimientoIdRequerimiento.titulo',
                    'requerimientoIdRequerimiento.descripcion',
                    'requerimientoIdRequerimiento.apoyo_brindado',
                    'requerimientoIdRequerimiento.observacion',
                ],
            ]) ?>
            <?php
            $counter++;
        } ?>
    </div>
</div>

<div class="box box-primary collapsed-box">
    <div class="box-header with-border"><h3 class="box-title">Servicio para satisfacer Requerimientos</h3>
        <div class="box-tools pull-right">
            (Ver Detalle)<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
            </button>
        </div>
    </div>
    <div class="box-body">
        <?= DetailView::widget([
            'model' => $modelosMatch[0],
            'attributes' => [
                'servicioIdServicio.titulo',
                'servicioIdServicio.descripcion',
                'servicioIdServicio.perfil_scb',
                'servicioIdServicio.observacion',
                'servicioIdServicio.duracionUnidad',
                //'carreraCodCarrera.nombre_carrera',
            ],
        ]) ?>
    </div>
</div>
<?=Html::beginForm("",'post', ['id' => 'formulario-seleccion']);?>
<?= Html::hiddenInput('idesAgrupadas', $idesAgrupadas) ?>
<div class="row">
    <div class="col-md-12">
        <?= Html::submitButton('Confirmar Implementación <i class="fa fa-arrow-circle-right"></i>', ['data-pjax'=>0,'class' => 'pull-right btn btn-lg btn-success']) ?>
    </div>
</div>
<?= Html::endForm();?>
