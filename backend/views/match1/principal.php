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

$this->title = 'Match 1 (Panel)';
$this->params['breadcrumbs'][] = $this->title;

CrudAsset::register($this);

?>
<div class="sci-principal">

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
                                    <h3>Nuevo Match 1</h3>
                                    <p>Crear nueva relación entre Requerimientos y Asignaturas para un periodo.</p>
                                    <br>
                                </div>
                                <div class="icon">
                                    <i class="fa fa-handshake-o"></i>
                                </div>
                                <a  href="<?= Html::encode(Url::toRoute('/match1/seleccion', true))?>" class="small-box-footer">
                                    Ir <i class="fa fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div>

                        <div class="col-lg-6 col-xs-6">
                            <!-- small box -->
                            <div class="small-box bg-red">
                                <div class="inner">
                                    <h3>Gestionar Match 1's</h3>
                                    <p>Si ya ha creado un Match 1 para un periodo, podrá revisarlo, modificarlo o eliminarlo.</p>
                                </div>
                                <div class="icon">
                                    <i class="fa fa-list" aria-hidden="true"></i>
                                </div>
                                <a  href="<?= Html::encode(Url::toRoute('/match1/index', true))?>" class="small-box-footer">
                                    Ir <i class="fa fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div>

                </div>
        </div>

    </div>

</div>
