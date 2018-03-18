<?php
/**
 * Created by PhpStorm.
 * User: Howl
 * Date: 06-06-2017
 * Time: 17:02
 */
use yii\helpers\Html;
use yii\widgets\ActiveForm;

if(isset($datosPost)){
    //var_dump($datosPost);
}
$this->title = "SELECCIÓN DE SECCIÓN";
?>
<div class="row">
    <div class="col-md-12">
        <nav>
            <ol class="cd-breadcrumb triangle"  style="margin-left: 0px; margin-right: 0px;">
                <li>
                    <span>
                         <span>
                            <i class="fa fa-book"></i>
                        </span>
                        Asignatura
                    </span>
                </li>
                <li>
                    <span>
                         <span>
                            <i class="fa fa-book"></i>
                        </span>
                        Sección/Secciones
                    </span>
                </li>
                <li>
                    <span>
                         <span>
                            <i class="fa fa-group"></i>
                        </span>
                        Socios
                    </span>
                </li>
                <li>
                    <span>
                         <span>
                            <i class="fa fa-gg-circle"></i>
                        </span>
                        Asignación Grupos
                    </span>
                </li>
            </ol>
        </nav>
    </div>
</div>
<h1>Con qué sección desea continuar?</h1>
<?php $form = ActiveForm::begin(); ?>
<?php
foreach ($secciones as $seccion){
    echo Html::submitButton('Sección N°'.$seccion->numero_seccion, ['name'=>'seccionSeleccionada', 'value'=>$seccion->id_seccion,'class' =>'btn btn-primary']);
    echo "<br>";

}
?>
<?php ActiveForm::end(); ?>

