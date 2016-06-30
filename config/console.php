<?php
$dotenv =   new \Dotenv\Dotenv(__DIR__);
$dotenv->load();

Yii::setAlias('@tests', dirname(__DIR__) . '/tests');
Yii::setAlias('tests', __DIR__ . '/../tests');

return [
    'id' => 'basic-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log', 'gii'],
    'language'          =>  'fa-IR',
    'sourceLanguage'    =>  'en-US',    
    'controllerNamespace' => 'app\commands',
    'name'              =>  'Nodes Console',
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
        'ftpFs' => require(__DIR__ . '/' . 'ftp.php'),
        'urlManager' => [
            'baseUrl' => 'http://nodes.ir/',
            'showScriptName'    => false,
            'enablePrettyUrl'   => true,
        ],        
        'mailer'    => require(__DIR__ . '/' . 'mailer.php'),
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
        'db' => require(__DIR__ . '/db.php'),
    ],
    'controllerMap' => [
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
        
        '@cdn'              =>  $_ENV['CDN_URL'],
        
        '@ppicBaseUrl'      =>  '@cdn/user/pictures',
        '@cpicBaseUrl'      =>  '@cdn/user/covers',
        '@upBaseUrl'        =>  '@cdn/post/images',
        '@postCoverBaseUrl' =>  '@cdn/post/covers',
        '@gravatar'         =>  'http://www.gravatar.com/avatar',
        '@placeHold'        =>  'http://www.placehold.it',
        '@homeUrl'          =>  'http://nodes.ir',
    ], 
    'params' => require(__DIR__ . '/params.php'),
];
