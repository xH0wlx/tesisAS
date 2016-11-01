<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */

//ADMIN LTE
class AppAsset extends AssetBundle
{
    public $sourcePath = '@bower/adminlte/';
    public $css = [
        //'bootstrap/css/bootstrap.min.css',
        'https://fonts.googleapis.com/css?family=Roboto:700,400&amp;subset=cyrillic,latin,greek,vietnamese',
        'plugins/font-awesome/css/font-awesome.min.css',
        'https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css',
        'plugins/jvectormap/jquery-jvectormap-1.2.2.css',
        'dist/css/AdminLTE.min.css',
        'dist/css/skins/_all-skins.min.css',
    ];
    public $js = [
        //'plugins/jQuery/jquery-2.2.3.min.js',
        //'bootstrap/js/bootstrap.min.js',
        'plugins/fastclick/fastclick.js',
        'dist/js/app.min.js', //Necesario para el sidebar
        'plugins/sparkline/jquery.sparkline.min.js',
        'plugins/jvectormap/jquery-jvectormap-1.2.2.min.js',
        'plugins/jvectormap/jquery-jvectormap-world-mill-en.js',
        'plugins/slimScroll/jquery.slimscroll.min.js',
        'plugins/chartjs/Chart.min.js',
        //'dist/js/pages/dashboard2.js',
        'dist/js/demo.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
    ];
}

/*class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/site.css',
    ];
    public $js = [
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}*/
