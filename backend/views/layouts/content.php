<?php
/**
 * Created by PhpStorm.
 * User: Luis
 * Date: 11-09-2016
 * Time: 13:43
 */
//VARIABLE $LINKS viene del breadcrumb del main
use yii\widgets\Breadcrumbs;
use yii\helpers\Html;
use common\widgets\Alert;
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <?= Html::encode($this->title) ?>
        </h1>
        <?php
        echo Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] :[],
        ]);
        ?>
        <!--<ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
            <li class="active">Here</li>
        </ol>-->
    </section>

    <!-- Main content -->
    <section class="content">
        <!-- Your Page Content Here -->
        <?= $content ?>
    </section>
    <!-- /.content -->
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
<!-- /.content-wrapper -->