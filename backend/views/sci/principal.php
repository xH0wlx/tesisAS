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

$this->title = 'Socio Comunitario Institucional (Menú)';
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
            <h3 class="box-title">Opciones</h3>
        </div>
        <div class="box-body">

            <div class="row">
                        <div class="col-lg-6 col-xs-6">
                            <!-- small box -->
                            <div class="small-box bg-blue">
                                <div class="inner">
                                    <h3>Socios</h3>
                                    <h3>Comunitarios</h3>
                                    <h3>Institucionales</h3>
                                    <br>
                                    <p>Gestión de Todos los Socios Comunitarios Institucionales ingresados en el Sistema</p>
                                    <p>- Datos del Socio Comunitario Institucional</p>
                                    <p>- Contactos</p>
                                    <p>- Requerimientos</p>
                                    <br>
                                </div>
                                <div class="icon">
                                    <i class="fa fa-users"></i>
                                </div>
                                <a  href="<?= Html::encode(Url::toRoute('/sci/index', true))?>" class="small-box-footer">
                                    Ir <i class="fa fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div>

                        <div class="col-lg-6 col-xs-6">
                            <!-- small box -->
                            <div class="small-box bg-aqua">
                                <div class="inner">
                                    <h3>Contactos</h3>
                                    <p>Gestión de Todos los Contactos de los Socios Comunitarios Institucionales ingresados en el Sistema</p>
                                    <p>- Contactos de los Socios Comunitarios Institucionales</p>
                                </div>
                                <div class="icon">
                                    <i class="fa fa-address-book" aria-hidden="true"></i>
                                </div>
                                <a  href="<?= Html::encode(Url::toRoute('/contacto-sci/index', true))?>" class="small-box-footer">
                                    Ir <i class="fa fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div>

                    <div class="col-lg-6 col-xs-6">
                        <!-- small box -->
                        <div class="small-box bg-red">
                            <div class="inner">
                                <h3>Requerimientos</h3>
                                <p>Gestión de Todos los Requerimientos de los Socios Comunitarios Institucionales ingresados en el Sistema</p>
                                <p>- Requerimientos de los Socios Comunitarios Institucionales</p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-list-ul"></i>
                            </div>
                            <a href="<?= Html::encode(Url::toRoute('/requerimiento/index', true))?>" class="small-box-footer">
                                Ir <i class="fa fa-arrow-circle-right"></i>
                            </a>
                        </div>
                    </div>

                </div>
        </div>

    </div>

</div>
