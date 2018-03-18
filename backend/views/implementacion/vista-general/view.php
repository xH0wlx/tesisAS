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

$request = Yii::$app->request;
$this->title = "Vista General";
$this->params['breadcrumbs'][] = ['label' => 'Implementaciones', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="implementacion-view">

    <p>
        <?= Html::a('<i class="fa fa-list"></i> Ir a Listado de Implementaciones', ['index'], ['class' => 'btn btn-info']) ?>
        <?= Html::a('<i class="fa fa-edit"></i> Modificar Implementación.', ['/implementacion/panel-implementacion',
            'idImplementacion' => $request->get('id')], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('<i class="fa fa-trash"></i> Eliminar Implementación', ['delete', 'id' => $implementacion->id_implementacion], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Está seguro que desea eliminar este Socio Comunitario Institucional?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <div class="box box-default collapsed-box">
        <div class="box-header with-border"><h3 class="box-title">Periodo: &nbsp;<b><?=$implementacion->anio_implementacion?> -
                    <?=$implementacion->semestre_implementacion?></b></h3>
        </div>
    </div>

    <div class="box box-default">
        <div class="box-header with-border"><h3 class="box-title">Asignatura</h3>
            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="box-body" style="display: block;">
            <h4 style="margin-top: 10px !important;"><b><?= $implementacion->asignaturaCodAsignatura->codigoNombre ?></b></h4>
        </div>
    </div>

        <div class="nav-tabs-custom">

        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">

            <?php foreach ($arregloDataProviders as $key => $dataProvider){ ?>
                <li role="presentation" class="<?php if($key == 0){ echo "active"; }else { echo ""; }; ?>">
                    <a href="#<?= $key ?>" aria-controls="<?= $key ?>" role="tab" data-toggle="tab"><i class="glyphicon glyphicon-user"></i> Sección <?= ($key+1) ?></a>
                </li>
            <?php }//FIN FOREACH ?>
        </ul>

        <div class="tab-content">
            <?php foreach ($arregloDataProviders as $key => $dataProvider){ ?>
            <div role="tabpanel" class="tab-pane fade <?php if($key == 0){ echo "in active"; } ?>" id="<?= $key ?>">

                <div class="box box-default">
                    <div class="box-header with-border"><h3 class="box-title">Docente de la sección</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="box-body" style="display: block;">
                        <h4 style="margin-top: 10px !important;"><b><?= $docentes[$key] ?></b></h4>
                    </div>
                </div>

                <?=
                $this->render('_tablaAlumnos', [
                    'searchModel' => $arregloSearchModels[$key],
                    'dataProvider' => $dataProvider,
                    'key' => $key,
                ]);
                ?>
            </div>
            <?php }//FIN FOREACH ?>

            <div role="tabpanel" class="tab-pane fade" id="settings">...</div>

        </div><!-- TAB CONTENT -->
    </div>

</div>
