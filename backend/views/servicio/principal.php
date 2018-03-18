<?php

use yii\helpers\Html;
use yii\helpers\Url;
//use yii\widgets\Pjax;
use kartik\grid\GridView;
use backend\models\search\RequerimientoSearch;

use yii\bootstrap\Modal;
use johnitvn\ajaxcrud\CrudAsset;
use johnitvn\ajaxcrud\BulkButtonWidget;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\SciSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Servicio (Panel)';
$this->params['breadcrumbs'][] = $this->title;

CrudAsset::register($this);

?>
<div class="servicio-principal">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

<!--    <p>
        <?/*= Html::a('Crear Socio Comunitario Institucional', ['create'], ['class' => 'btn btn-success']) */?>
    </p>-->

    <div class="box box-primary">
        <div class="box-header">
            <i class="fa fa-gears"></i>
            <h3 class="box-title">Panel de Administración</h3>
        </div>
        <div class="box-body">

            <div class="row">
                        <div class="col-lg-6 col-xs-6">
                            <!-- small box -->
                            <div class="small-box bg-green">
                                <div class="inner">
                                    <h3>Con Match Asociado</h3>
                                    <p>Crea un servicio para un periodo en específico próximo a implementación.</p>
                                    <br>
                                </div>
                                <div class="icon">
                                    <i class="fa fa-handshake-o"></i>
                                </div>
                                <a  href="<?= Html::encode(Url::toRoute('/servicio/match-asociado', true))?>" class="small-box-footer">
                                    Ir <i class="fa fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div>

                        <div class="col-lg-6 col-xs-6">
                            <!-- small box -->
                            <div class="small-box bg-red">
                                <div class="inner">
                                    <h3>Sin Match Asociado</h3>
                                    <p>Crea un servicio sin periodo determinado de implementación.</p>
                                </div>
                                <div class="icon">
                                    <i class="fa fa-list" aria-hidden="true"></i>
                                </div>
                                <a  href="<?= Html::encode(Url::toRoute('/servicio/create', true))?>" class="small-box-footer">
                                    Ir <i class="fa fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div>

                </div>
        </div>

    </div>

</div>
