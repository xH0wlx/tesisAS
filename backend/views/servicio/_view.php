<?php

use yii\widgets\DetailView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\servicio */
if($model->sinMatch == 1){
    $url = 'index-servicios-no-asignados';
    $boton = 'Ir a Listado de Servicios No Asignados';
}else{
    $url = '/servicio/ver-servicios-asignados';
    $boton = 'Ir a Listado de Servicios Asignados';
}
?>
<div class="servicio-view">
    <?php if(!Yii::$app->request->isAjax){ ?>
    <p>
        <?= Html::a('<i class="fa fa-list"></i> '.$boton, [$url], ['class' => 'btn btn-info']) ?>
        <?= Html::a('<i class="fa fa-edit"></i> Modificar Servicio', ['update', 'id' => $model->id_servicio], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('<i class="fa fa-trash"></i> Eliminar Servicio', ['delete', 'id' => $model->id_servicio], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Está seguro que desea eliminar este Servicio?',
                'method' => 'post',
            ],
        ]) ?>
    </p>
    <?php } ?>
    <div class="servicio-view">

        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'titulo',
                'descripcion',
                'perfil_scb',
                'observacion',
                'estadoEjecucionIdEstado.nombre_estado',
                'duracionUnidad',
                'asignaturaCodAsignatura.cod_asignatura',
                [
                    'attribute' => 'asignaturaCodAsignatura.nombre_asignatura',
                    'label'=> 'Nombre de Asignatura',
                ],
            ],
        ]) ?>

    </div>

</div>
