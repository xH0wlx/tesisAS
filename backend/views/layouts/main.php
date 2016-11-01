<?php

/* @var $this \yii\web\View */
/* @var $content string */

use backend\assets\AppAsset;
use yii\helpers\Html;
use common\widgets\Alert;

if (Yii::$app->controller->action->id === 'login') {
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
    <link rel="shortcut icon" href="http://asface.ubiobio.cl/w/wp-content/uploads/2016/03/cropped-logo1.png" />
    <link rel="apple-touch-icon" href="http://asface.ubiobio.cl/w/wp-content/uploads/2016/03/cropped-logo1.png" />

    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>

<body class="hold-transition skin-blue sidebar-collapse sidebar-mini">
<?php $this->beginBody() ?>
    <div class="wrapper">
                <?= $this->render('header.php', ['baseUrl' => $baseUrl]) ?>
                <?= $this->render('leftmenu.php', ['baseUrl' => $baseUrl]) ?>
                <?= $this->render('content.php', ['content' => $content]) ?>
                <?= $this->render('footer.php', ['baseUrl' => $baseUrl]) ?>
                <?= $this->render('rightside.php', ['baseUrl' => $baseUrl]) ?>
                <!-- /.control-sidebar -->
                <!-- Add the sidebar's background. This div must be placed
                     immediately after the control sidebar -->
                <div class="control-sidebar-bg"></div>
    </div>
    <!-- ./wrapper -->
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
<?php } //FIN ELSE INICIAL?>
