<?php
/**
 * Configuration file for the "yii asset" console command.
 */

// In the console environment, some path aliases may not exist. Please define these:
Yii::setAlias('@webroot', __DIR__ . '/web');
Yii::setAlias('@web', '/');

return [
    // Adjust command/callback for JavaScript files compressing:
    'jsCompressor' => 'java -jar compiler.jar --js {from} --js_output_file {to}',
    // Adjust command/callback for CSS files compressing:
    'cssCompressor' => 'cleancss -o {to} {from}',
    // YUI is not under maintaince,use npm install -g clean-css 
    // The list of asset bundles to compress:
    'bundles' => [
        'app\assets\BootstrapSwitchAssets',
        'app\assets\CustomEditorAssets',
        'app\assets\ICheckAssets',
        'app\assets\JasnyBootstrapAssets',
        'app\assets\MainAssets',
        'app\assets\ShowPostAssets',
    ],
    // Asset bundle for compression output:
    'targets' => [
        'main'  =>  [
            'class'     =>  'yii\web\AssetBundle',
            'basePath'  =>  '@webroot/min',
            'baseUrl'   =>  '@web/min',
            'js'        =>  'main-{hash}.js',
            'css'       =>  'main-{hash}.css',
            'depends'   =>  [
                'yii\web\YiiAsset',
                'yii\web\JqueryAsset',                
                'yii\bootstrap\BootstrapAsset',
                'yii\bootstrap\BootstrapPluginAsset',   
                'app\assets\FontAwesomeAssets',                        
                'app\assets\InitAssets',                
                'app\assets\WowAssets',        
                'app\assets\MainAssets',
            ]
        ],
        'editor'=>  [
            'class'     =>  'yii\web\AssetBundle',
            'basePath'  =>  '@webroot/min',
            'baseUrl'   =>  '@web/min',
            'js'        =>  'editor-{hash}.js',
            'css'       =>  'editor-{hash}.css',
            'depends'   =>  [
                'app\assets\CustomEditorAssets',
                'app\assets\DanteAssets',                
                'app\assets\UnderscoreAssets',
                'app\assets\SanitizeAssets',
            ]            
        ],
        'show'  =>  [
            'class'     =>  'yii\web\AssetBundle',
            'basePath'  =>  '@webroot/min',
            'baseUrl'   =>  '@web/min',
            'js'        =>  'show-{hash}.js',
            'css'       =>  'show-{hash}.css',
            'depends'   =>  [
                'app\assets\ShowPostAssets'
            ]                        
        ],
        'icheck'=>  [
            'class'     =>  'yii\web\AssetBundle',
            'basePath'  =>  '@webroot/min',
            'baseUrl'   =>  '@web/min',
            'js'        =>  'icheck-{hash}.js',
            'css'       =>  'icheck-{hash}.css',
            'depends'   =>  [
                'app\assets\ICheckAssets'
            ]                                    
        ],
        'jasny'=>  [
            'class'     =>  'yii\web\AssetBundle',
            'basePath'  =>  '@webroot/min',
            'baseUrl'   =>  '@web/min',
            'js'        =>  'jasny-{hash}.js',
            'css'       =>  'jasny-{hash}.css',
            'depends'   =>  [
                'app\assets\JasnyBootstrapAssets'
            ]                                    
        ],
        'switch'=>  [
            'class'     =>  'yii\web\AssetBundle',
            'basePath'  =>  '@webroot/min',
            'baseUrl'   =>  '@web/min',
            'js'        =>  'switch-{hash}.js',
            'css'       =>  'switch{hash}.css',
            'depends'   =>  [
                'app\assets\BootstrapSwitchAssets',
            ]                                    
        ]        
    ],
    // Asset manager configuration:
    'assetManager' => [
        'basePath' => '@webroot/assets',
        'baseUrl' => '@web/assets',
    ],
];