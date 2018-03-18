<?php
/**
 * Created by PhpStorm.
 * User: Howl
 * Date: 06-06-2017
 * Time: 21:51
 */
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\sortinput\SortableInput;
use yii\helpers\Url;


//echo $hash = Yii::$app->getSecurity()->generatePasswordHash("17988");
$request = Yii::$app->request;

$this->title = 'Conformación de Grupos';
$this->params['breadcrumbs'][] = ['label' => 'Panel de Implementación',
    'url' => ['/implementacion/panel-implementacion', 'idImplementacion'=> $request->get('idImplementacion')]];
$this->params['breadcrumbs'][] = $this->title;

$this->registerJsFile('@web/js/implementacion/funciones.js', ['depends' => [\yii\web\JqueryAsset::className()] ] );


?>
<div class="box box-primary collapsed-box">
    <div class="box-header with-border"><h3 class="box-title">Cantidad de grupos</h3>
        <div class="box-tools pull-right">
            (Cambiar)<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
            </button>
        </div>
    </div>
    <div class="box-body" style="display: none;">
        <?php $form = \yii\bootstrap\ActiveForm::begin([
            'id' => 'form-cantidad-grupos',
            'action' => Url::to(['/alumno-inscrito-has-grupo-trabajo/cambiar-cantidad-grupos']),
            //'layout' => 'horizontal',
            //'enableAjaxValidation' => true,
            //'validationUrl' => 'validation-rul',
        ]); ?>

        <div class="row">
            <div class="col-md-3">
                <?= $form->field($cantidadModel, 'cantidad')->textInput(); ?>
            </div>
            <?= $form->field($cantidadModel, 'idImplementacion')->hiddenInput()->label(false); ?>
            <?= $form->field($cantidadModel, 'idSeccion')->hiddenInput()->label(false); ?>
        </div>
        <div class="row">
            <div class="col-md-4">
                <?= Html::submitButton('Modificar', ['class'=>'btn btn-primary']); ?>
            </div>
        </div>
        <?php $form->end(); ?>
        <br>
    </div>
</div>

<?php $form = ActiveForm::begin([
    //'action' => Url::to(['implementacion/paso-uno']),
    ]);?>

    <div class="row">
        <div class="col-md-6" style="
                float: left;
                width: 50%;
                /*background: red;*/
                height: 400px;
                overflow: scroll;
                overflow-x: auto;
                ">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Lista de Alumnos Restantes</h3>
                </div>
                <?php
                echo SortableInput::widget([
                    'name'=>'listaAlumnosInscritos',
                    'items' => $listaAlumnosInscritos,
                    'hideInput' => true,
                    'sortableOptions' => [
                        'connected'=>true,
                        'options' => ['style' => 'min-height: 50px; background-color:#EEEEEE;'],
                    ],
                    'options' => ['class'=>'form-control', 'readonly'=>true]
                ]);
                ?>
            </div>

        </div>
        <div class="col-md-6" style="
            float: left;
            width: 50%;
            /*background: blue;*/
            height: 400px;
            overflow: scroll;
            overflow-x: auto;
        ">
        <ul id="lista-sortable">

        </ul>
        <?php
            if($gruposTrabajo != null){
                for($i=1; $i<=$cantidadGrupos;$i++){
                    $bandera = false;
                    foreach ($gruposTrabajo as $grupoTrabajo){
                        if($grupoTrabajo->numero_grupo_trabajo == $i){
                            $bandera = true;
                            $items = [];
                            $alumnosInscritosGrupo = $grupoTrabajo->alumnoInscritoSeccionIdAlumnoInscritoSeccions;
                            foreach ($alumnosInscritosGrupo as $a) {
                                $items[$a->id_alumno_inscrito_seccion] = [
                                    'content' => '<i class="glyphicon glyphicon-move"></i> '.$a->alumnoRutAlumno->nombre,
                                    //'options' => ['data' => ['id'=>$p->id]],
                                ];
                            }
                            echo "<div class=\"box box-primary\">
                                    <div class=\"box-header with-border\">
                                        <h3 class=\"box-title\">Grupo N°".($i)."</h3>
                                    </div>";
                                        echo SortableInput::widget([
                                            'name'=>'grupo-'.$i,
                                            'items' => $items,
                                            'hideInput' => true,
                                            'sortableOptions' => [
                                                'itemOptions'=>['class'=>'alert alert-warning'],
                                                'connected'=>true,
                                                'options' => ['style' => 'min-height: 45px; background-color:#FFFFFF;'],
                                            ],
                                            'options' => ['class'=>'form-control', 'readonly'=>true]
                                        ]);
                            echo"</div>";
                        }
                    }//FIN FOREACH
                    if($bandera != true){
                        $items = [];
                        echo "<div class=\"box box-primary\">
                                    <div class=\"box-header with-border\">
                                        <h3 class=\"box-title\">Grupo N°".($i)."</h3>
                                    </div>";
                                        echo SortableInput::widget([
                                            'name'=>'grupo-'.$i,
                                            'items' => $items,
                                            'hideInput' => true,
                                            'sortableOptions' => [
                                                'itemOptions'=>['class'=>'alert alert-warning'],
                                                'connected'=>true,
                                                'options' => ['style' => 'min-height: 45px; background-color:#FFFFFF;'],
                                            ],
                                            'options' => ['class'=>'form-control', 'readonly'=>true]
                                        ]);
                        echo"</div>";
                        $bandera = false;
                    }

                }//FIN FOR CANTIDAD GRUPOS
            }else{
                for($i=1; $i<=$cantidadGrupos;$i++){
                    echo "<div class=\"box box-primary\">
                                    <div class=\"box-header with-border\">
                                        <h3 class=\"box-title\">Grupo N°".($i)."</h3>
                                    </div>";
                                        echo SortableInput::widget([
                                            'name'=>'grupo-'.$i,
                                            'items' => [

                                            ],
                                            'hideInput' => true,
                                            'sortableOptions' => [
                                                'itemOptions'=>['class'=>'alert alert-warning'],
                                                'connected'=>true,
                                                'options' => ['style' => 'min-height: 45px; background-color:#FFFFFF;'],
                                            ],
                                            'options' => ['class'=>'form-control', 'readonly'=>true]
                                        ]);
                    echo"</div>";
                }
            }

        ?>

            <!--
        <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true" >
            <div class="panel panel-default">
                <div class="panel-heading" role="tab" id="headingOne">
                    <h4 class="panel-title">
                        <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                            Collapsible Group Item #1
                        </a>
                    </h4>
                </div>
                <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
                    <div class="panel-body">

                    </div>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading" role="tab" id="headingTwo">
                    <h4 class="panel-title">
                        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            Collapsible Group Item #2
                        </a>
                    </h4>
                </div>
                <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
                    <div class="panel-body">
                    </div>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading" role="tab" id="headingThree">
                    <h4 class="panel-title">
                        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                            Collapsible Group Item #3
                        </a>
                    </h4>
                </div>
                <div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
                    <div class="panel-body">

                    </div>
                </div>
            </div>
        </div>-->

        </div>
    </div>
    <br><br>
    <?= Html::button('<i class="fa fa-chevron-circle-left"></i> Volver sin guardar', ['id' => 'botonVolverSinGuardar',
    'value'=> Url::to(['/implementacion/panel-implementacion', 'idImplementacion' => Yii::$app->request->get('idImplementacion')]),
    'class' =>'btn btn-danger']) ?>

    <?= Html::submitButton('<i class="fa fa-floppy-o" aria-hidden="true"></i> Guardar y Volver', ['name'=>'guardarYSalir', 'value'=>'true','class' =>'btn btn-primary']) ?>
<?php ActiveForm::end(); ?>

