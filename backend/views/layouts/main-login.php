<?php
/**
 * Created by PhpStorm.
 * User: Luis
 * Date: 13-09-2016
 * Time: 21:53
 */
use backend\assets\AppAsset;
use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $content string */
backend\assets\AppAssetLogin::register($this);
?>
<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="shortcut icon" href="http://asface.ubiobio.cl/w/wp-content/uploads/2016/03/cropped-logo1.png" />
        <link rel="apple-touch-icon" href="http://asface.ubiobio.cl/w/wp-content/uploads/2016/03/cropped-logo1.png" />

        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>

    <body class="login-page">

    <?php $this->beginBody() ?>

    <div class="wrapper">
        <?= $content ?>
    </div>

    <?php $this->endBody() ?>
    </body>
    </html>
<?php $this->endPage() ?>