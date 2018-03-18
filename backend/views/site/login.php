<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Iniciar sesión';

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
    <!-- /.login-logo -->
    <div class="login-box-body" style="border-top: solid #3F51B5 !important; border-style: solid !important; border-color: #303F9F #3F51B5 !important;">
        <p class="login-box-msg">Ingrese su información</p>
        <?php $form = ActiveForm::begin(['id' => 'login-form', 'enableClientValidation' => false]); ?>
        <div class="form-group has-feedback">
            <?= $form->field($model, 'username')->textInput(['data-rut' => 'true','autofocus' => false/*true provoca carga anticipada*/, 'placeholder' => 'RUT','class' => 'form-control'])->label(false) ?>
            <span class="glyphicon glyphicon-user form-control-feedback"></span>
        </div>
        <div class="form-group has-feedback">
            <?= $form->field($model, 'password')->passwordInput(['placeholder' => 'Contraseña','class' => 'form-control'])->label(false)  ?>
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
        </div>
        <?= Html::a('Ha olvidado su contraseña?', ['/site/recoverpass']) ?>
        <div class="row">
            <div class="col-xs-7">
                <?= $form->field($model, 'rememberMe')->checkbox()->label('Recordarme') ?>
            </div>
            <!-- /.col -->
            <div class="col-xs-5">
                <?= Html::submitButton('Ingresar', ['class' => 'btn btn-primary btn-block btn-flat', 'name' => 'login-button', 'style'=>"margin-top: 5px;"]) ?>
            </div>
            <!-- /.col -->
        </div>

        <?php ActiveForm::end(); ?>


    </div>
    <!-- /.login-box-body -->
</div>
<!-- /.login-box -->
