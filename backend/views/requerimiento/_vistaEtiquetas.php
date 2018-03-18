<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\RequerimientoHasEtiquetaAreaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>
<div class="requerimiento-has-etiqueta-area-index">

    <?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'requerimiento_id_requerimiento',
            'etiqueta_area_id_etiqueta_area',
            'requerimientoIdRequerimiento.titulo',
            'etiquetaAreaIdEtiquetaArea.nombre_etiqueta_area',
            //['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
    <?php Pjax::end(); ?></div>