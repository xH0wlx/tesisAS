<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;

$this->title = $name;
?>
<div class="site-error">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="alert alert-danger">
        <?= nl2br(Html::encode($message)) ?>
    </div>

    <p>
        El error de arriba ocurrió cuando el servidor web estaba procesando su petición</p><br><p>
        The above error occurred while the Web server was processing your request.
    </p>
    <p>
        Por favor, contactenos si usted piensa que esto es un error de servidor. Gracias.</p><br><p>
        Please contact us if you think this is a server error. Thank you.
    </p>

</div>
