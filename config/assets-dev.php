<?php

return [
//    'Font'  =>  [
//        'class'         =>  'yii\web\AssetBundle',
//        'sourcePath'    =>  '@app/themes/seven/assets',        
//        'css'           =>  ['http://ifont.ir/apicode/17']
//    ],
    'FontAwesome' =>  [
        'class'         =>  'yii\web\AssetBundle',
        'sourcePath'    =>  '@bower/components-font-awesome',
        'css'           =>  ['css/font-awesome.min.css'],
    ],
    'init'  =>  [
        'class'         =>  'yii\web\AssetBundle',        
        'sourcePath'    =>  '@bower',
        'css'           =>  [
                        'animate.css/animate.min.css',
        ],
        'js'            =>  [
                        'jQuery-One-Page-Nav/jquery.nav.js',
                        'jquery.nicescroll/dist/jquery.nicescroll.min.js',
                        'slimscroll/jquery.slimscroll.min.js'
        ],
        'depends'       =>  [
                        'yii\web\YiiAsset',
                        'yii\bootstrap\BootstrapAsset',
                        'yii\bootstrap\BootstrapPluginAsset',
                        'yii\web\JqueryAsset',
                        'yii\jui\JuiAsset',
//                        'Font',
                        'FontAwesome'
                    ]        
    ],
    'wow'               =>  [
        'class'         =>  'yii\web\AssetBundle',
        'sourcePath'    =>  '@bower/wow/dist',        
        'js'            =>  ['wow.min.js']
    ],    
    'main'  =>  [
        'class'         =>  'yii\web\AssetBundle',
        'sourcePath'    =>  '@app/themes/seven/assets/master/',
        'css'           =>  [
                                'css/icomoon.css',
                                'css/style.css',
                                'css/custom.css',
                                'css/AdminLTE.css',
                                'css/skin-blue.css',
                            ],
        'js'            => [
                                'js/main.js',
                                'js/app.min.js',
                            ],        
        'depends'       =>  ['init','wow']        
    ],
    'iCheck'    =>  [
        'class'         =>  'yii\web\AssetBundle',
        'sourcePath'    =>  '@bower/iCheck',
        'css'           =>  ['skins/line/blue.css'],
        'js'            =>  ['icheck.min.js'],
        'depends'       =>  ['main']
    ],
    'bootstrap-switch'  =>  [
        'class'         =>  'yii\web\AssetBundle',
        'sourcePath'    =>  '@bower/bootstrap-switch/dist',
        'css'           =>  ['css/bootstrap3/bootstrap-switch.min.css'],
        'js'            =>  ['js/bootstrap-switch.min.js'],
        'depends'       =>  ['main']        
    ],
    'underscore'        =>  [
        'class'         =>  'yii\web\AssetBundle',
        'sourcePath'    =>  '@bower/underscore',
        'js'            =>  ['underscore-min.js'],
    ],
    'sanitize'          =>  [
        'class'         =>  'yii\web\AssetBundle',
        'sourcePath'    =>  '@bower/sanitize.js/lib',
        'js'            =>  ['sanitize.js'],
    ],
    'dante'             =>  [
        'class'         =>  'yii\web\AssetBundle',
        'sourcePath'    =>  '@bower/dante/dist',        
        'css'           =>  ['css/dante-editor.css'],
        'js'            =>  ['js/dante-editor.js'],
        'depends'       =>  ['yii\web\JqueryAsset','underscore','sanitize','main']
    ],
    'custom-editor'     =>  [
        'class'         =>  'yii\web\AssetBundle',
        'sourcePath'    =>  '@app/themes/seven/assets/editor',
        'js'            =>  ['js/editor.js'],
        'depends'       =>  ['main','dante']
    ],
    'show-post'         =>  [
        'class'         =>  'yii\web\AssetBundle',
        'sourcePath'    =>  '@bower/dante/dist',        
        'css'           =>  ['css/dante-editor.css'],
        'depends'       =>  ['main']
    ],
    'jasny-bootstrap'   =>  [
        'class'         =>  'yii\web\AssetBundle',
        'sourcePath'    =>  '@bower/jasny-bootstrap/dist',
        'js'            =>  ['js/jasny-bootstrap.min.js'],
        'css'           =>  ['css/jasny-bootstrap.min.css'],
        'depends'       =>  ['main']
    ],
];