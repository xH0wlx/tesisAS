<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;

?>
<div class="site-error">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="alert alert-danger">
        <!--//nl2br(Html::encode('HOLA HOLA PIRINOLA')) ?>-->
        
    </div>

    <p>
        Hola <?= Html::encode(Yii::$app->user->identity->username)?> tu correo es <?= Html::encode(Yii::$app->user->identity->email)?>
    </p>


</div>