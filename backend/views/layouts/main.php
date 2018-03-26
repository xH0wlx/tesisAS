<?php

/* @var $this \yii\web\View */
/* @var $content string */

use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\helpers\Url;
//use common\widgets\Alert;
use yii\widgets\Breadcrumbs;


if (Yii::$app->controller->action->id === 'login' || Yii::$app->controller->action->id === 'recoverpass' || Yii::$app->controller->action->id === 'resetpass') {
    /**
     * Do not use this code in your template. Remove it.
     * Instead, use the code  $this->layout = '//main-login'; in your controller.
     */
    echo $this->render(
        'main-login',
        ['content' => $content]
    );
}else{

    $asset = backend\assets\AppAsset::register($this);
    $baseUrl = $asset->baseUrl;

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="<?= Url::to(["/favicon.png"]) ?>" />
    <link rel="apple-touch-icon" href="<?= Url::to(["/favicon.png"]) ?>" />

    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>

<body class="hold-transition skin-blue sidebar-miniX">
<noscript>
    <p>Advertencia!</p>
    <p>El sistema hace imperativo el uso de JavaScript para su funcionamiento.
        Si lo has deshabilitado intencionadamente, por favor vuelve a activarlo.</p>
</noscript>
<?php $this->beginBody() ?>
    <div class="wrapper">
                <?= $this->render('header.php', ['baseUrl' => $baseUrl]) ?>
                <?php
                    if(Yii::$app->user->can('alumno')){
                        echo $this->render('leftmenuAlumno.php', ['baseUrl' => $baseUrl]);
                    }

                    if(Yii::$app->user->can('docente')){
                        echo $this->render('leftmenuDocente.php', ['baseUrl' => $baseUrl]);
                    }

                    if(Yii::$app->user->can('coordinador general')){
                        echo $this->render('leftmenu.php', ['baseUrl' => $baseUrl]);
                    }
                ?>

                <?=$this->render('content.php', ['content' => $content])?>
                <?= $this->render('footer.php', ['baseUrl' => $baseUrl]) ?>
                <?= $this->render('rightside.php', ['baseUrl' => $baseUrl]) ?>
                <!-- /.control-sidebar -->
                <!-- Add the sidebar's background. This div must be placed
                     immediately after the control sidebar -->
                <div class="control-sidebar-bg"></div>
    </div>
    <!-- ./wrapper -->
<?php
/*yii\bootstrap\Modal::begin([
    'headerOptions' => ['id' => 'modalHeader'],
    'id' => 'modal',
    'size' => 'modal-lg',
    //keeps from closing modal with esc key or by clicking out of the modal.
    // user must click cancel or X to close
    'clientOptions' => ['backdrop' => 'static', 'keyboard' => FALSE]
]);
echo "<div id='modalContent'></div>";
yii\bootstrap\Modal::end();*/
?>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>

<?php
} //FIN ELSE INICIAL
?>


