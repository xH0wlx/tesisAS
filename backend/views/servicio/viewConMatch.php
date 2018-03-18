<?php

use yii\widgets\DetailView;
use yii\helpers\Html;
/* @var $this yii\web\View */
/* @var $model backend\models\servicio */
//http://backend.tesisas.com/servicio/view?id=20
$this->title = 'Detalle del Servicio';
$this->params['breadcrumbs'][] = ['label' => 'Selección de periodo', 'url' => ['/servicio/match-asociado']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="box box-primary">
    <div class="box-header with-border"><h3 class="box-title">Siguiente Paso: Implementación</h3>
    </div>
    <div class="box-body">
        <?= Html::a('<i class="fa fa-external-link"></i> Ir a Implementación.', ['/implementacion/seleccion-asignatura'], ['class' => 'btn btn-success']) ?>
    </div>
</div>
<hr>
<?= $this->render('//servicio/_view',['model'=>$model]) ?>
