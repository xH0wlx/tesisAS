<?php
return [
    'language' => 'es',
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',

    'name' => 'Sistema de Gestión AS',

    'components' => [
        'formatter' => [
            'class' => \yii\i18n\Formatter::className(),
            'as rutFormatter' => \sateler\rut\RutFormatBehavior::className(),
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
    ],
];
