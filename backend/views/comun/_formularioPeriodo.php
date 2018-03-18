<?php
use yii\helpers\html;
use yii\widgets\ActiveForm;
/**
 * Created by PhpStorm.
 * User: Howl
 * Date: 19-07-2017
 * Time: 1:35
 */
?>
<div class="panel panel-primary">
    <div class="panel-heading"><?= $tituloPanel?></div>
    <div class="panel-body">
        <?php $form = ActiveForm::begin(); ?>
            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($modeloPeriodo, 'anio')->textInput() ?>
                </div>
                <div class="col-md-6">
                    <?php $var = [ 1 => 'Primer Semestre', 2 => 'Segundo Semestre']; ?>
                    <?= $form->field($modeloPeriodo, 'semestre')->dropDownList($var, ['prompt' => 'Seleccione semestre' ]); ?>
                </div>
            </div>

            <div class="form-group">
                <?= Html::submitButton($textoBoton, ['class' => 'btn btn-primary']) ?>
            </div>
        <?php
        ActiveForm::end();
        ?>
    </div>
</div>