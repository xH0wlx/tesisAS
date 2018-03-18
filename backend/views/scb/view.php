<?php

use kartik\detail\DetailView;
use yii\helpers\Html;
/* @var $this yii\web\View */
/* @var $model backend\models\scb */

$this->title = $model->nombre_negocio;
$this->params['breadcrumbs'][] = ['label' => 'Socio Comunitario Beneficiario', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="scb-view">
    <p>
        <?= Html::a('<i class="fa fa-list"></i> Ir a Listado de Socios Benef.', ['index'], ['class' => 'btn btn-info']) ?>
        <?= Html::a('<i class="fa fa-edit"></i> Modificar Socio Benef.', ['update', 'id' => $model->id_scb], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('<i class="fa fa-trash"></i> Eliminar Socio Benef.', ['delete', 'id' => $model->id_scb], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Está seguro que desea eliminar este Socio Comunitario Beneficiario?',
                'method' => 'post',
            ],
        ]) ?>
    </p>


    <div class="nav-tabs-custom">

        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active">
                <a href="#scb" aria-controls="scb" role="tab" data-toggle="tab"><i class="glyphicon glyphicon-user"></i> Datos SCB</a>
            </li>
            <li role="presentation">
                <a href="#contactoScb" aria-controls="contactoScb" role="tab" data-toggle="tab"><i class="glyphicon glyphicon-phone-alt"></i> Contacto Socio Benef.</a>
            </li>
        </ul>

        <div class="tab-content">
            <div role="tabpanel" class="tab-pane fade in active" id="scb">
            <?= DetailView::widget([
                'model' => $model,
                'condensed'=>true,
                'hover'=>true,
                'buttons1' => "",
                'buttons2' => "",
                'mode'=>DetailView::MODE_VIEW,
                'panel'=>[
                    'heading'=>'Socio Beneficiario: ' . $model->nombre_negocio,
                    'type'=>DetailView::TYPE_PRIMARY,
                ],
                'attributes' => [
                    //'id_scb',
                    [
                        'label'=> 'Socio Comunitario Institucional',
                        'value'=>$model->sciIdSci->nombre,
                    ],
                    [
                        'label'=> 'Comuna Socio Comunitario Inst.',
                        'value'=>$model->sciIdSci->comunaComuna->comuna_nombre,
                    ],
                    'nombre_negocio',
                    'actividad_rubro_giro',
                    'numero_trabajadores',
                    'tiempo_en_la_actividad',
                    'productos_yo_servicios',
                    'descripcion_clientes',
                    'descripcion_proveedores',
                    'direccion_comercial',
                    'contabilidad:boolean',
                    'patente:boolean',
                    'sitio_web',
                    'red_social',
                ],
            ]) ?>
            </div>
            <div role="tabpanel" class="tab-pane fade" id="contactoScb">
                <?=
                \yii\widgets\ListView::widget([
                    'dataProvider' => $dataProviderContactoScb,
                    'itemView' => '//contacto-scb/view',
                ]);
                ?>
            </div>
            <div role="tabpanel" class="tab-pane fade" id="settings">...</div>

        </div><!-- TAB CONTENT -->
    </div>


</div>
