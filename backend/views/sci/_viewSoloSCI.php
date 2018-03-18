<?php

use yii\widgets\DetailView;
use yii\helpers\Html;
/**
 * Created by PhpStorm.
 * User: Howl
 * Date: 16-05-2017
 * Time: 22:44
 */
?>
    <div class="sci-vista">
        <div class="box box-primary">
            <div class="box-heading">&nbsp;&nbsp;&nbsp;<?=$model->nombre?></div>
            <div class="box-body">
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'nombre',
                'direccion',
                //'observacion',
                'departamento_programa',
                'creado_en:date',
                [
                    'attribute'=>'comuna_comuna_id',
                    //'format'=>'raw',
                    'value'=>$model->comunaComuna->comuna_nombre,
                ],
                [
                    'attribute'=>'sede_id_sede',
                    'label' => 'Sede (U. del Bío Bío)',
                    //'format'=>'raw',
                    'value'=>$model->sedeIdSede->nombre_sede,
                ],
                [
                    //'attribute'=>'sede_id_sede',
                    'label' => '<span class="label label-danger">Cantidad de Requerimientos no Asignados</span>',
                    'format'=>'html',
                    'value'=>
                       '<span class="label label-danger">'.$model->getRequerimientosNoAsignados()->count().'</span>',

                ],
                [
                    //'attribute'=>'sede_id_sede',
                    'label' => '<span class="label label-info">Cantidad de Requerimientos Preseleccionados</span>',
                    'format'=>'html',
                    'value'=>
                        '<span class="label label-info">'.$model->getRequerimientosPreseleccionadosMatch1()->count().'</span>',

                ],
            ],
        ]) ?>
                <div class="row">
                    <div class="col-sm-12">
                        <?= Html::a('<i class="fa fa-arrow-circle-right"></i> Ver Requerimientos No Asignados', ['/match1/seleccion2?id='.$model->id_sci], ['class' => 'pull-right btn btn-info']) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
