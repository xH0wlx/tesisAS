<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\servicio */
//http://backend.tesisas.com/servicio/view?id=20
if($model->sinMatch == 1){
    $url = 'index-servicios-no-asignados';
}else{
    $url = 'index';
}

$this->title = 'Detalle del Servicio';
$this->params['breadcrumbs'][] = ['label' => 'Servicios', 'url' => [$url]];
$this->params['breadcrumbs'][] = ['label' => 'Crear Servicio (Sin Match)', 'url' => ['create']];
$this->params['breadcrumbs'][] = $this->title;

?>

<?= $this->render('//servicio/_view',['model'=>$model]) ?>
