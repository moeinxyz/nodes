<?php

Yii::setAlias('@tests', dirname(__DIR__) . '/tests');
Yii::setAlias('tests', __DIR__ . '/../tests');
$params = require(__DIR__ . '/params.php');
$db = require(__DIR__ . '/db.php');

return [
    'id' => 'basic-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log', 'gii'],
    'controllerNamespace' => 'app\commands',
    'modules' => [
        'gii' => 'yii\gii\Module',
        'user' => [
            'class' => 'app\modules\user\Module',
        ],        
        'post'  =>  [
            'class' => 'app\modules\post\Module',
        ],
        'embed' =>  [
            'class' => 'app\modules\embed\Module',
        ]
    ],
    'components' => [
        'ftpFs' => [
            'class' => 'creocoder\flysystem\FtpFilesystem',
            'host'  => 'b101054.parspack.org',
            'port'  => 21,
            'username' => 'b101054',
            'password' => 'DdIlxJvBmSp2yM',
            // 'root' => '/path/to/root',
            // 'passive' => false,
            // 'ssl' => true,
            // 'timeout' => 60,
            // 'permPrivate' => 0700,
            // 'permPublic' => 0744,
            // 'transferMode' => FTP_TEXT,
        ],                   
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
        'gearman' => [
            'class' => 'filsh\yii2\gearman\GearmanComponent',
            'servers' => [
                ['host' => '127.0.0.1', 'port' => 4730],
            ],
            'user' => 'moein7tl',
            'jobs' => [
                'syncImage' =>  [
                    'class' => 'app\gearworker\SyncImage'
                ],
            ]
        ],           
    ],
    'controllerMap' => [
        'gearman' => [
            'class'             => 'filsh\yii2\gearman\GearmanController',
            'gearmanComponent'  => 'gearman'
        ],
        'fixture' => [
            'class' => 'yii\faker\FixtureController',
            'templatePath' => 'tests/unit/fixtures',
        ],        
    ],    
    'aliases' => [
        '@temp'         =>  '@app/temp',
        '@pictures'     =>  '@temp/pictures',
        '@covers'       =>  '@temp/covers',        
        '@ftp'          =>  '/assets',
//        '@tests'        =>   __DIR__ . '/../tests',
    ], 
    'params' => $params,
];
