<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\DemandaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Demandas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="demanda-index">


    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Crear Nueva Demanda', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'summary' => '',
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id_demanda',
            'perfil_estudiante:ntext',
            'apoyos_brindados:ntext',
            'observaciones:ntext',
            'fecha_creacion',
            'sciIdSci.nombre',
            'sciIdSci.comuna',

            ['class' => 'yii\grid\ActionColumn'],
            ['class' => 'yii\grid\CheckboxColumn'],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
