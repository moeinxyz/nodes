<?php

$params = require(__DIR__ . '/params.php');

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' =>  ['log'],
    'language'          =>  'fa-IR',
    'sourceLanguage'    =>  'en-US',
    'components' => [
        'jdate' => [
            'class' => 'jDate\DateTime'
        ],        
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
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey'       => 'N9H_hIWERpGoYxMcgZnvFgmwsSVOck8j',
            'enableCsrfValidation'      =>  true,
            'enableCookieValidation'    =>  true,
        ],
        'redis' => [
            'class'     => 'yii\redis\Connection',
            'hostname'  => 'localhost',
            'port'      => 6379,
            'database'  => 0,
        ],
        'session' => [
            'class'     => 'yii\redis\Session',
        ],
        'cache' => [
            'class'     => 'yii\redis\Cache',
        ],          
        'user' => [
            'identityClass'     => 'app\modules\user\models\User',
            'enableAutoLogin'   => true,
        ],
        'authClientCollection' => [
            'class' => 'yii\authclient\Collection',
            'clients' => [
                'google' => [
                    'class'         => 'yii\authclient\clients\GoogleOAuth',
                    'clientId'      => '443205670238-vk10ehfmib8q9aljjad6dij0t1goam01.apps.googleusercontent.com',
                    'clientSecret'  => 'bn9q9cUf2IQP_rWpRacBbvJm',
                    'scope'         =>  implode(' ', ['email'])
                ],
                'github' => [
                    'class'         => 'yii\authclient\clients\GitHub',
                    'clientId'      => '1a1d4e30eab7d1bf87dd',
                    'clientSecret'  => 'e1dd76c0937a358ad51c55203360b222ea8c040a',
                    'scope'         =>  implode(' ', ['user:email'])
                ],                
                'linkedin' => [
                    'class'         => 'yii\authclient\clients\LinkedIn',
                    'clientId'      => '78pbzg810si8wb',
                    'clientSecret'  => 'TfZ4uwfiBgrb5V9w',
                ],                
                'twitter' => [
                    'class'         => 'yii\authclient\clients\Twitter',
                    'consumerKey'   => 'yev9zPwjVEXZEr6t5hXAnTmsT',
                    'consumerSecret'=> 'ZPfzJJMDbj104KYy80ikoAjqOLIm41ECLogLvY9PfKjej4NLDg',
                ]  
            ],
        ],        
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'view' => [
            'theme' => [
               'pathMap' => [
                    '@app/views'    =>  [
                        '@app/themes/seven/views'
                    ],
                    '@app/modules'  =>  [
                        '@app/themes/seven/views',
                    ],            
                ],
            ],
            'renderers' => [
                'twig' => [
                    'class' => 'yii\twig\ViewRenderer',
                    'cachePath' => '@runtime/Twig/cache',
                    // Array of twig options:
                    'options' => [
                        'auto_reload' => true,
                    ],
                    'globals' => ['html' => '\yii\helpers\Html'],
                    'uses' => ['yii\bootstrap'],
                ],
            ],            
        ],
//        'mailer' => [
//            'class' => 'yii\swiftmailer\Mailer',
//            // send all mails to a file by default. You have to set
//            // 'useFileTransport' to false and configure a transport
//            // for the mailer to send real emails.
//            'useFileTransport' => true,
//        ],
        'mailer' => [
            'class'     => 'nickcv\mandrill\Mailer',
            'apikey'    => '6mEUoQyuhDN4itn_O1UlCg',
        ],
        'log' => [
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
        'db' => require(__DIR__ . '/db.php'),
        'urlManager' => [
            'showScriptName'    => false,
            'enablePrettyUrl'   => true,
                'rules' => [
                    '@<username:[\w\-]+>/<post:[\w\-]+>'    =>  'post/post/view',
                    '@<username:[\w\-]+>'                   =>  'post/post/user',
                    '@<username:[\w\-]+>/rss'               =>  'post/post/rss',                    
                    'post/write'                            =>  'post/post/write',
                    'post/autosave/<id:\w+>'                =>  'post/post/autosave',
                ],
        ],      
        'reCaptcha' => [
            'name'      =>  'reCaptcha',
            'class'     =>  'himiklab\yii2\recaptcha\ReCaptcha',
            'siteKey'   =>  '6LfK_gITAAAAAPC21IX_amSnxcR7d7be1aB9so48',
            'secret'    =>  '6LfK_gITAAAAAIqY0FMR1xbk990u2fVJ-BwCZuBn',
        ],
        'assetManager' => [
            'linkAssets'        => true,
            'appendTimestamp'   => true,
            'bundles'           => require(__DIR__ . '/' . (YII_ENV_PROD ? 'assets-prod.php' : 'assets-dev.php')),
        ],        
        'i18n' => [
            'translations' => [
                'app*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'fileMap' => [
                        'app'   => 'app.php',
                    ],
                ],
            ],
        ],
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
        ]        
    ],
    'modules' => [
        'user' => [
            'class' => 'app\modules\user\Module',
        ],
        'post'  =>  [
            'class' => 'app\modules\post\Module',
        ],
        'embed' =>  [
            'class' => 'app\modules\embed\Module',
        ],
    ],    
    'aliases' => [
        '@uploaded'     =>  '@app/uploaded',
        '@temp'         =>  '@app/web/t',
        '@tempFolder'   =>  't',
        '@pictures'     =>  '@temp/pictures',
        '@covers'       =>  '@temp/covers',        
        '@ftp'          =>  '/assets',
        '@ppicBaseUrl'  =>  'http://cdn.nodes.ir/assets/p',
        '@cpicBaseUrl'  =>  'http://cdn.nodes.ir/assets/c',
        '@upBaseUrl'    =>  'http://cdn.nodes.ir/assets/images',
        '@gravatar'     =>  'http://www.gravatar.com/avatar',
        '@placeHold'    =>  'http://www.placehold.it',
        '@profile'      =>  'http://nodes.ir'
    ],    
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = 'yii\debug\Module';

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = 'yii\gii\Module';
}

return $config;