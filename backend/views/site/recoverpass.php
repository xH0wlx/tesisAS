<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>


<div class="login-box">
    <div class="login-logo">
        <div class="row">
            <div class="col-xs-offset-3 col-xs-6 col-md-offset-3 col-md-6">
                <?= Html::img(Yii::getAlias('@web').'/images/logo_header.png', $options = [
                    'title' => 'Logo AS',
                    'alt' => 'Logo Aprendizaje Servicio',
                    'class' => 'img-responsive',
                    //'width' => '150px'
                ]) ?>
            </div>
        </div>
    </div>
    <div class="login-box-body" style="border-top: solid #3F51B5 !important; border-style: solid !important; border-color: #303F9F #3F51B5 !important;">
        <h3><?= $msg ?></h3>

        <h4 class="text-center">Recuperar Contraseña</h4>
        <?php $form = ActiveForm::begin([
            'method' => 'post',
            'enableClientValidation' => true,
        ]);
        ?>
        <div class="form-group">
            <?= $form->field($model, "email")->input("email") ?>
        </div>

        <?= Html::submitButton("Recuperar Contraseña", ["class" => "btn btn-primary"]) ?>

        <?php $form->end() ?>
    </div>
</div>
