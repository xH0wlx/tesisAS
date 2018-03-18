<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */

//ADMIN LTE
class AppAsset extends AssetBundle
{
    public $sourcePath = '@bower/';
    public $css = [
        'https://fonts.googleapis.com/css?family=Roboto:700,400&amp;subset=cyrillic,latin,greek,vietnamese',
        'adminlte/plugins/font-awesome/css/font-awesome.min.css',
        'https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css',
        'adminlte/plugins/jvectormap/jquery-jvectormap-1.2.2.css',
        'adminlte/dist/css/AdminLTE.css',
        'adminlte/dist/css/skins/_all-skins.min.css',
        //'adminlte/bootstrap/css/bootstrap.min.css',

    ];
    public $js = [
        //'adminlte/plugins/jQuery/jquery-2.2.3.min.js',
        //'adminlte/bootstrap/js/bootstrap.min.js',
        'adminlte/plugins/fastclick/fastclick.js',
        'adminlte/dist/js/app.min.js', //Necesario para el sidebar
        'adminlte/plugins/sparkline/jquery.sparkline.min.js',
        'adminlte/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js',
        'adminlte/plugins/jvectormap/jquery-jvectormap-world-mill-en.js',
        'adminlte/plugins/slimScroll/jquery.slimscroll.min.js',
        'adminlte/plugins/chartjs/Chart.min.js',
        //'adminlte/dist/js/pages/dashboard2.js',
        'adminlte/dist/js/demo.js',

    ];
    public $depends = [
        'yii\web\YiiAsset', //It mainly includes the yii.js file which implements a mechanism of organizing JavaScript
        // code in modules. It also provides special support for data-method and data-confirm attributes and other
        // useful features. More information about yii.js can be found in the Client Scripts Section.
        //Yii.js + Jquery.js

        'yii\bootstrap\BootstrapAsset', // It includes the CSS file from the Twitter Bootstrap framework. 3.3.7
        'yii\bootstrap\BootstrapPluginAsset', // It includes the JavaScript file from the Twitter Bootstrap framework for supporting Bootstrap JavaScript plugins.
        'backend\assets\AssetPropios',
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
