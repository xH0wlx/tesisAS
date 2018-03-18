<?php

use yii\helpers\Html;
//use yii\widgets\DetailView;
use kartik\detail\DetailView;
use yii\helpers\ArrayHelper;
use backend\models\ContactoSci;
use backend\models\Sci;
use yii\widgets\ListView;
use yii\data\ActiveDataProvider;

/* @var $this yii\web\View */
/* @var $model backend\models\sci */

?>
<div class="sci-view">

                <?=DetailView::widget([
                    'model'=>$model,
                    'condensed'=>true,
                    'hover'=>true,
                    'buttons1' => "",
                    'buttons2' => "",
                    'mode'=>DetailView::MODE_VIEW,
                    'panel'=>[
                        'heading'=>'Socio Institucional: ' . $model->nombreComuna,
                        'type'=>DetailView::TYPE_PRIMARY,
                    ],
                    'attributes'=>[
                        //'id_sci',
                        'nombre',
                        'direccion',
                        'observacion',
                        'departamento_programa',
                        [
                            'attribute'=>'comuna_comuna_id',
                            //'format'=>'raw',
                            'value'=>$model->comunaComuna->comuna_nombre,
                        ],
                        [
                            //'attribute'=>'sede_id_sede',
                            'label' => 'Dirección Web',
                            'format'=>'html',
                            'value'=>function ($form, $widget){
                                $model = $widget->model;
                                $contactos = $model->contactoWebs;
                                $retornar = "";
                                foreach ($contactos as $contacto){
                                    $retornar = $retornar.$contacto->direccion_web."<br>";
                                }
                                return $retornar;
                            },
                        ],
                        [
                            'attribute'=>'sede_id_sede',
                            'label' => 'Sede (U. del Bío Bío)',
                            //'format'=>'raw',
                            'value'=>$model->sedeIdSede->nombre_sede,
                        ],
                    ]
                ]);?>

</div>
