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
    <img src="<?=Yii::getAlias('@web').'/images/fondo_sistema.jpg'?>" class="bg">
    <?php $this->beginBody() ?>

    <div class="wrapper">
        <?= $content ?>

        <?php foreach (Yii::$app->session->getAllFlashes() as $message):; ?>
            <?php
            echo \kartik\growl\Growl::widget([
                'type' => (!empty($message['type'])) ? $message['type'] : 'danger',
                'title' => (!empty($message['title'])) ? Html::encode($message['title']) : 'Título no seteado',
                'icon' => (!empty($message['icon'])) ? $message['icon'] : 'fa fa-info',
                'body' => (!empty($message['message'])) ? Html::encode($message['message']) : 'Mensaje no seteado',
                'showSeparator' => true,
                'delay' => 1, //This delay is how long before the message shows
                'pluginOptions' => [
                    'delay' => (!empty($message['duration'])) ? $message['duration'] : 3000, //This delay is how long the message shows for
                    'placement' => [
                        'from' => (!empty($message['positonY'])) ? $message['positonY'] : 'top',
                        'align' => (!empty($message['positonX'])) ? $message['positonX'] : 'right',
                    ]
                ]
            ]);
            ?>
        <?php endforeach; ?>
    </div>

    <?php $this->endBody() ?>
    </body>
    </html>
<?php $this->endPage() ?>