<?php
use kartik\helpers\html;
use yii\helpers\Url;
/* @var $this yii\web\View */

$this->title = 'Inicio';

?>
<div class="site-index">

    <div class="box box-primary">
        <div class="box-header with-border"><h3 class="box-title">Proceso en Curso (<?=$modelPeriodo->anio?>-<?=$modelPeriodo->semestre?>)</h3>
            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="box-body">
            <div class="col-lg-4 col-xs-12">
                <!-- small box -->
                <div class="small-box bg-aqua">
                    <div class="inner">
                        <h3>Match</h3>
                        <br>
                    </div>
                    <div class="icon">
                        <i class="fa fa-handshake-o"></i>
                    </div>
                    <a  href="<?= Html::encode(Url::toRoute(['/match1/seleccion', 'anio'=> $modelPeriodo->anio,'semestre'=>$modelPeriodo->semestre]))?>" class="small-box-footer">
                        Ir <i class="fa fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

            <div class="col-lg-4 col-xs-12">
                <!-- small box -->
                <div class="small-box bg-blue">
                    <div class="inner">
                        <h3>Servicio</h3>
                        <br>
                    </div>
                    <div class="icon">
                        <i class="fa fa-exchange" aria-hidden="true"></i>
                    </div>
                    <a  href="<?= Html::encode(Url::toRoute(['/servicio/seleccion-requerimientos', 'anio'=> $modelPeriodo->anio,'semestre'=>$modelPeriodo->semestre]))?>" class="small-box-footer">
                        Ir <i class="fa fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

            <div class="col-lg-4 col-xs-12">
                <!-- small box -->
                <div class="small-box bg-light-blue">
                    <div class="inner">
                        <h3>Implementación</h3>
                        <br>
                    </div>
                    <div class="icon">
                        <i class="fa fa-university" aria-hidden="true"></i>
                    </div>
                    <a  href="<?= Html::encode(Url::toRoute(['/implementacion/seleccion-asignatura', 'anio'=> $modelPeriodo->anio,'semestre'=>$modelPeriodo->semestre]))?>" class="small-box-footer">
                        Ir <i class="fa fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <!--
    <div class="row">
        <?php
        $i=0;
        for($i=0; $i<=0; $i++){
        ?>
        <div class="col-lg-3 col-xs-6">

            <div class="small-box bg-blue">
                <div class="inner">
                    <h3><?= $contRequerimientosNoAsignados ?></h3>

                    <p>Requerimientos No Asignados (con un servicio)</p>
                </div>
                <div class="icon">
                    <i class="fa fa-database"></i>
                </div>
                <a href="#" class="small-box-footer">
                    Más información <i class="fa fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        <?php
        }
        ?>
        <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-blue">
                <div class="inner">
                    <h3><?= $contSociosSinRequerimientos ?></h3>

                    <p>Socios Comunitarios Institucionales</p>
                      <p>Sin Requerimientos Asignados</p>
                </div>
                <div class="icon">
                    <i class="fa fa-database"></i>
                </div>
                <a href="#" class="small-box-footer">
                    Más información <i class="fa fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
    </div>-->

    <br><br>

    <div class="body-content">



    </div>
</div>
