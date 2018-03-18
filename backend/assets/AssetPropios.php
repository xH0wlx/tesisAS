<?php
/**
 * Created by PhpStorm.
 * User: Luis
 * Date: 03-11-2016
 * Time: 9:43
 */
namespace backend\assets;

use yii\web\AssetBundle;

class AssetPropios extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'breadcrumbs/css/style.css',
        'css/site.css',
    ];
    public $js = [
        'breadcrumbs/js/modernizr.js',
        'js/main.js',
        //'js/ajax-modal-popup.js',
    ];
    public $depends = [
        'sateler\rut\RutWidgetAsset',
    ];

}