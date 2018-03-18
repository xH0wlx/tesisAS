<?php

use yii\helpers\Html;
//use yii\widgets\DetailView;
use kartik\detail\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\contactoSci */

$this->title = $model->nombres;
$this->params['breadcrumbs'][] = ['label' => 'Contactos Socio C. Institucional', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="contacto-sci-view">


    <p>
        <?= Html::a('<i class="fa fa-list"></i> Ir a Listado de Contactos Inst.', ['/contacto-sci/index'], ['class' => 'btn btn-info']) ?>
        <?= Html::a('<i class="fa fa-edit"></i> Modificar Contacto Inst.', ['update', 'id' => $model->id_contacto_sci], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('<i class="fa fa-trash"></i> Eliminar Contacto Inst.', ['delete', 'id' => $model->id_contacto_sci], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Está seguro que desea eliminar este item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?=DetailView::widget([
        'model'=>$model,
        'condensed'=>true,
        'hover'=>true,
        'buttons1' => "",
        'buttons2' => "",
        'mode'=>DetailView::MODE_VIEW,
        'panel'=>[
            'heading'=>'Contacto: ' . $model->nombres,
            'type'=>DetailView::TYPE_PRIMARY,
        ],
        'attributes'=>[
            //'id_contacto_sci',
            'nombres',
            'apellidos',
            'telefono',
            'cargo',
            'email:email',
            [
                'attribute' => 'sci_id_sci',
                'format' => 'html',
                'value'=>function ($form, $widget) {
                    $model = $widget->model;
                    return $model->sciIdSci->nombre;
                },
            ],
            //'creado_en',
            //'modificado_en',
        ]
    ]);?>

</div>
