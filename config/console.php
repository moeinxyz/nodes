<?php

Yii::setAlias('@tests', dirname(__DIR__) . '/tests');
Yii::setAlias('tests', __DIR__ . '/../tests');
$params = require(__DIR__ . '/params.php');
$db = require(__DIR__ . '/db.php');

return [
    'id' => 'basic-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log', 'gii'],
    'language'          =>  'fa-IR',
    'sourceLanguage'    =>  'en-US',    
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
        ],
        'social' => [
            'class' => 'app\modules\social\Module',
        ],        
    ],
    'components' => [
        'ftpFs' => [
            'class' => 'creocoder\flysystem\FtpFilesystem',
            'host'  => '91.109.23.155',
            'port'  => 21,
            'username' => 'cdn2@nodes.ir',
            'password' => 'CU9eb8Mk',
            // 'root' => '/path/to/root',
            // 'passive' => false,
            // 'ssl' => true,
            // 'timeout' => 60,
            // 'permPrivate' => 0700,
            // 'permPublic' => 0744,
            // 'transferMode' => FTP_TEXT,
        ],        
        'mailer'    => [
            'class'     =>  'nickcv\mandrill\Mailer',
            'apikey'    =>  '6mEUoQyuhDN4itn_O1UlCg',
        ],
        'cache'     => [
            'class' => 'yii\caching\FileCache',
        ],
        'log'       => [
            'traceLevel' => YII_DEBUG ? 30 : 0,
            'targets' => [
                [
                    'class'         => 'yii\log\FileTarget',
                    'logFile'       => '@app/runtime/logs/app.log',                    
                    'levels'        => 0,//0 means all
                    'categories'    => [],                    
                ],
            ],
        ],
        'socialClientCollection'    =>  require(__DIR__ . '/social.php'),
        'db' => $db,
        'gearman' => [
            'class' => 'filsh\yii2\gearman\GearmanComponent',
            'servers' => [
                ['host' => '127.0.0.1', 'port' => 4730],
            ],
            'user' => 'moein',
            'jobs' => [
                'SyncImage' =>  [
                    'class' => 'app\gearworker\SyncImage'
                ],
                'SyncPostCover' =>  [
                    'class' =>  'app\gearworker\SyncPostCover'
                ],
                'PostSuggestionForUser' =>  [
                    'class' =>  'app\gearworker\PostSuggestionForUser'
                ],
                'PostSuggestionForGuest'=>  [
                    'class' =>  'app\gearworker\PostSuggestionForGuest'
                ]
            ]
        ],           
    ],
    'controllerMap' => [
        'gearman' => [
            'class'             => 'filsh\yii2\gearman\GearmanController',
            'gearmanComponent'  => 'gearman'
        ],
        'fixture' => [
            'class'             => 'yii\faker\FixtureController',
            'templatePath'      => 'tests/unit/fixtures',
        ],
    ],    
    'aliases' => [
        '@temp'         =>  '@app/runtime/temp',
        '@pictures'     =>  '@temp/pictures',
        '@covers'       =>  '@temp/covers',        
        '@postcovers'   =>  '@temp/postcovers',
        '@ftpPictures'  =>  '/user/pictures',
        '@ftpCovers'    =>  '/user/covers',
        '@ftpPostCovers'=>  '/post/covers',

        '@ppicBaseUrl'      =>  'http://cdn.nodes.ir/user/pictures',
        '@cpicBaseUrl'      =>  'http://cdn.nodes.ir/user/covers',
        '@upBaseUrl'        =>  'http://cdn.nodes.ir/post/images',
        '@postCoverBaseUrl' =>  'http://cdn.nodes.ir/post/covers',
        '@gravatar'         =>  'http://www.gravatar.com/avatar',
        '@placeHold'        =>  'http://www.placehold.it',
        '@homeUrl'          =>  'http://nodes.ir',
    ], 
    'params' => $params,
];
