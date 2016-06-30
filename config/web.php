<?php
$dotenv =   new \Dotenv\Dotenv(__DIR__);
$dotenv->load();

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' =>  ['log'],
    'language'          =>  'fa-IR',
    'sourceLanguage'    =>  'en-US',
    'name'              =>  'Nodes Web',
    'components' => [
        'ftpFs' => require(__DIR__ . '/' . 'ftp.php'),        
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey'       => $_ENV['COOKIE_VALIDATION_KEY'],
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
            'loginUrl'          => ['','login'=>'required'],  
        ],
        'socialClientCollection'    =>  require(__DIR__ . '/social.php'),
        'authClientCollection'      => [
            'class' => 'yii\authclient\Collection',
            'clients' => require(__DIR__ . '/auth-client.php'),
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
        'log' => [
            'traceLevel' => YII_DEBUG ? 30 : 0,
            'targets' => [
                [
                    'class'         => 'yii\log\FileTarget',
                    'logFile'       => '@app/runtime/logs/app.log',                    
                    'levels' => ['error', 'warning'],
                    'categories'    => [],                    
                ],
            ],
        ],
        'mailer'        =>  array_merge(
                                        require(__DIR__ . '/' . 'mailer.php'),
                                        ['htmlLayout'=>  '@app/themes/seven/views/user/views/mail/layout.php']
                            ),
        'db'            => require(__DIR__ . '/db.php'),
        'urlManager'    => require(__DIR__ . '/url.php'),      
        'reCaptcha' => [
            'name'      =>  'reCaptcha',
            'class'     =>  'himiklab\yii2\recaptcha\ReCaptcha',
            'siteKey'   =>  $_ENV['RECAPTCHA_KEY'],
            'secret'    =>  $_ENV['RECAPTCHA_SECRET'],
        ],
        'assetManager' => [
            'linkAssets'        => YII_ENV_PROD ? true : false,
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
        'social' => [
            'class' => 'app\modules\social\Module',
        ],        
    ],    
    'aliases' => [
        '@runtimeTemp'              =>  '@app/runtime/temp/images',
        '@webTemp'                  =>  '@app/web/userassets',
        '@webTempPictures'          =>  '@webTemp/user/pictures',
        '@webTempCovers'            =>  '@webTemp/user/covers',
        '@webTempPostCover'         =>  '@webTemp/post/covers',
        '@webTempFolder'            =>  'userassets',
        '@webTempCoversFolder'      =>  '@webTempFolder/user/covers',
        '@webTempPicturesFolder'    =>  '@webTempFolder/user/pictures',
        '@webTempPostCoverFolder'   =>  '@webTempFolder/post/covers',
        '@ftpImages'                =>  '/post/images',

        '@profile'                  =>  $_ENV['PROFILE_URL'],
        '@cdn'                      =>  $_ENV['CDN_URL'],
        
        '@ppicBaseUrl'              =>  '@cdn/user/pictures',
        '@cpicBaseUrl'              =>  '@cdn/user/covers',
        '@upBaseUrl'                =>  '@cdn/post/images',
        '@postCoverBaseUrl'         =>  '@cdn/post/covers',
        '@gravatar'                 =>  'https://www.gravatar.com/avatar',
        '@placeHold'                =>  'https://www.placehold.it',
        
        '@uploaded'     =>  '@app/runtime/temp/images',
        '@temp'         =>  '@app/web/t',
        '@tempFolder'   =>  't',
        '@pictures'     =>  '@temp/pictures',
        '@covers'       =>  '@temp/covers',        

    ],    
    'params' => require(__DIR__ . '/params.php'),
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = 'yii\debug\Module';

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = 'yii\gii\Module';
}

return $config;