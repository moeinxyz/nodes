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
            'js'        =>  'a{hash}.js',
            'css'       =>  'a{hash}.css',
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
            'js'        =>  'b{hash}.js',
            'css'       =>  'b{hash}.css',
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
            'js'        =>  'c{hash}.js',
            'css'       =>  'c{hash}.css',
            'depends'   =>  [
                'app\assets\ShowPostAssets',
                'app\assets\BibblioAssets'
            ]                        
        ],
        'icheck'=>  [
            'class'     =>  'yii\web\AssetBundle',
            'basePath'  =>  '@webroot/min',
            'baseUrl'   =>  '@web/min',
            'js'        =>  'd{hash}.js',
            'css'       =>  'd{hash}.css',
            'depends'   =>  [
                'app\assets\ICheckAssets'
            ]                                    
        ],
        'jasny'=>  [
            'class'     =>  'yii\web\AssetBundle',
            'basePath'  =>  '@webroot/min',
            'baseUrl'   =>  '@web/min',
            'js'        =>  'e{hash}.js',
            'css'       =>  'e{hash}.css',
            'depends'   =>  [
                'app\assets\JasnyBootstrapAssets'
            ]                                    
        ],
        'switch'=>  [
            'class'     =>  'yii\web\AssetBundle',
            'basePath'  =>  '@webroot/min',
            'baseUrl'   =>  '@web/min',
            'js'        =>  'f{hash}.js',
            'css'       =>  'f{hash}.css',
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