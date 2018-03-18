<?php

use yii\helpers\Html;
use yii\widgets\DetailView;


/* @var $this yii\web\View */
/* @var $model backend\models\servicio */
$session = Yii::$app->session;
$session->open();
if(isset($session["comprobacionReglaServicio"])){

}else{
    Yii::$app->response->redirect(\yii\helpers\Url::to(['/servicio/seleccion-requerimientos',
        'anio'=> Yii::$app->request->get('anio'), 'semestre' => Yii::$app->request->get('semestre')]));
}
$session->close();


$this->title = 'Asociar Servicio';
$this->params['breadcrumbs'][] = ['label' => 'Selección de periodo', 'url' => ['/servicio/match-asociado']];
$this->params['breadcrumbs'][] = ['label' => 'Selección de Requerimientos', 'url' => ['/servicio/seleccion-requerimientos',
    'anio'=> Yii::$app->request->get('anio'), 'semestre' => Yii::$app->request->get('semestre')]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="servicio-create">

    <div class="box box-primary">
        <div class="box-header with-border"><h3 class="box-title">Asignatura Seleccionada:</h3>
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
                        'asignaturaCodAsignatura.resultado_aprendizaje',
                    ],
                ]) ?>
        </div>
    </div>

    <div class="box box-primary collapsed-box">
        <div class="box-header with-border"><h3 class="box-title">Requerimiento/s a Satisfacer:</h3>
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


    <?= $this->render('//servicio/_form', [
        'model' => $model,
        'docentesHasServicio' => $docentesHasServicio,
    ]) ?>
</div>
