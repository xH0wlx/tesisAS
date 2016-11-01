<?php
return [
    'language' => 'es',
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',

    'name' => 'Sistema de Gestión AS',

    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
    ],
];
