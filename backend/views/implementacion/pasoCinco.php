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
if(isset($datosPost)){
   // var_dump($datosPost);
}
if(!isset($datosPost)){
?>
<h1>Conformación de Grupos</h1>
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
            <?php
            echo SortableInput::widget([
                'name'=>'kv-conn-1',
                'items' => $modeloAlumno->alumnosInscritosLista,
                'hideInput' => false,
                'sortableOptions' => [
                    'connected'=>true,
                ],
                'options' => ['class'=>'form-control', 'readonly'=>true]
            ]);
            ?>
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
            for($i=1; $i<=$cantidadGrupos;$i++){
                echo "<h3>Grupo N°".($i)."</h3>";
                echo SortableInput::widget([
                    'name'=>'grupo-'.$i,
                    'items' => [

                    ],
                    'hideInput' => true,
                    'sortableOptions' => [
                        'itemOptions'=>['class'=>'alert alert-warning'],
                        'connected'=>true,
                        'options' => ['style' => 'min-height: 45px'],
                    ],
                    'options' => ['class'=>'form-control', 'readonly'=>true]
                ]);
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

        <div class="row">

        </div>
        <div class="row">

        </div>
        <div class="row">

        </div>

    </div>

<?= Html::submitButton('Guardar y Salir', ['name'=>'guardarYSalir', 'value'=>'true','class' =>'btn btn-primary']) ?>
<?= Html::submitButton('Guardar y Continuar', ['name'=>'guardarYContinuar','value'=> 'true','class' => 'btn btn-primary']) ?>
<?php ActiveForm::end(); ?>

<?php }?>
