<?php
/**
 * This file is generated by the "yii asset" command.
 * DO NOT MODIFY THIS FILE DIRECTLY.
 * @version 2016-06-01 12:22:39
 */
return [
    'main' => [
        'class' => 'yii\\web\\AssetBundle',
        'basePath' => '@webroot/min',
        'baseUrl' => '@web/min',
        'js' => [
            'a6a39575f4f8afdca0b0bc41626d62b1a.js',
        ],
        'css' => [
            'acd6b03b3ba0bfc5a314af142f26d2fce.css',
        ],
    ],
    'editor' => [
        'class' => 'yii\\web\\AssetBundle',
        'basePath' => '@webroot/min',
        'baseUrl' => '@web/min',
        'js' => [
            'bdb8b65ddfeda05739b2299b4ed439af0.js',
        ],
        'css' => [
            'b9ed659cb9db5981660122eda4314dd96.css',
        ],
    ],
    'show' => [
        'class' => 'yii\\web\\AssetBundle',
        'basePath' => '@webroot/min',
        'baseUrl' => '@web/min',
        'js' => [],
        'css' => [
            'c9ed659cb9db5981660122eda4314dd96.css',
        ],
    ],
    'icheck' => [
        'class' => 'yii\\web\\AssetBundle',
        'basePath' => '@webroot/min',
        'baseUrl' => '@web/min',
        'js' => [
            'ddfaf0fe44769e1510387ad9fc6001482.js',
        ],
        'css' => [
            'df95991fcb1d14aaf4550d4279b6041d2.css',
        ],
    ],
    'jasny' => [
        'class' => 'yii\\web\\AssetBundle',
        'basePath' => '@webroot/min',
        'baseUrl' => '@web/min',
        'js' => [
            'e76bd16160c8217aff32ce45337a8f681.js',
        ],
        'css' => [
            'eb314a5d8b4926f0cdaf025b8885089c3.css',
        ],
    ],
    'switch' => [
        'class' => 'yii\\web\\AssetBundle',
        'basePath' => '@webroot/min',
        'baseUrl' => '@web/min',
        'js' => [
            'f1f57416a664b5bcd046985a37a689900.js',
        ],
        'css' => [
            'f2e43b2e878a58bda78b62816afe7baa3.css',
        ],
    ],
    'yii\\web\\JqueryAsset' => [
        'sourcePath' => null,
        'js' => [],
        'css' => [],
        'depends' => [
            'main',
        ],
    ],
    'yii\\web\\YiiAsset' => [
        'sourcePath' => null,
        'js' => [],
        'css' => [],
        'depends' => [
            'yii\\web\\JqueryAsset',
            'main',
        ],
    ],
    'yii\\bootstrap\\BootstrapAsset' => [
        'sourcePath' => null,
        'js' => [],
        'css' => [],
        'depends' => [
            'main',
        ],
    ],
    'yii\\bootstrap\\BootstrapPluginAsset' => [
        'sourcePath' => null,
        'js' => [],
        'css' => [],
        'depends' => [
            'yii\\web\\JqueryAsset',
            'yii\\bootstrap\\BootstrapAsset',
            'main',
        ],
    ],
    'app\\assets\\FontAwesomeAssets' => [
        'sourcePath' => null,
        'js' => [],
        'css' => [],
        'depends' => [
            'main',
        ],
    ],
    'app\\assets\\InitAssets' => [
        'sourcePath' => null,
        'js' => [],
        'css' => [],
        'depends' => [
            'yii\\web\\YiiAsset',
            'yii\\bootstrap\\BootstrapPluginAsset',
            'app\\assets\\FontAwesomeAssets',
            'main',
        ],
    ],
    'app\\assets\\WowAssets' => [
        'sourcePath' => null,
        'js' => [],
        'css' => [],
        'depends' => [
            'main',
        ],
    ],
    'app\\assets\\MainAssets' => [
        'sourcePath' => null,
        'js' => [],
        'css' => [],
        'depends' => [
            'app\\assets\\InitAssets',
            'app\\assets\\WowAssets',
            'main',
        ],
    ],
    'app\\assets\\UnderscoreAssets' => [
        'sourcePath' => null,
        'js' => [],
        'css' => [],
        'depends' => [
            'editor',
        ],
    ],
    'app\\assets\\SanitizeAssets' => [
        'sourcePath' => null,
        'js' => [],
        'css' => [],
        'depends' => [
            'editor',
        ],
    ],
    'app\\assets\\DanteAssets' => [
        'sourcePath' => null,
        'js' => [],
        'css' => [],
        'depends' => [
            'app\\assets\\UnderscoreAssets',
            'app\\assets\\SanitizeAssets',
            'app\\assets\\MainAssets',
            'editor',
        ],
    ],
    'app\\assets\\CustomEditorAssets' => [
        'sourcePath' => null,
        'js' => [],
        'css' => [],
        'depends' => [
            'app\\assets\\MainAssets',
            'app\\assets\\DanteAssets',
            'editor',
        ],
    ],
    'app\\assets\\ShowPostAssets' => [
        'sourcePath' => null,
        'js' => [],
        'css' => [],
        'depends' => [
            'app\\assets\\MainAssets',
            'show',
        ],
    ],
    'app\\assets\\ICheckAssets' => [
        'sourcePath' => null,
        'js' => [],
        'css' => [],
        'depends' => [
            'app\\assets\\MainAssets',
            'icheck',
        ],
    ],
    'app\\assets\\JasnyBootstrapAssets' => [
        'sourcePath' => null,
        'js' => [],
        'css' => [],
        'depends' => [
            'app\\assets\\MainAssets',
            'jasny',
        ],
    ],
    'app\\assets\\BootstrapSwitchAssets' => [
        'sourcePath' => null,
        'js' => [],
        'css' => [],
        'depends' => [
            'app\\assets\\MainAssets',
            'switch',
        ],
    ],
];