<?php
namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class AppAssetLogin extends AssetBundle
{
    public $sourcePath = '@bower/adminlte/';
    public $css = [
        'dist/css/AdminLTE.min.css',
    ];
    public $js = [
        //'dist/js/app.min.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}