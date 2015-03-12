<?php

return [
    'Font'  =>  [
        'class'         =>  'yii\web\AssetBundle',
        'sourcePath'    =>  '@app/themes/seven/assets',        
        'css'           =>  ['http://ifont.ir/apicode/17']
    ],
    // Font Awesome
    'FontAwesome' =>  [
        'class'         =>  'yii\web\AssetBundle',
        'sourcePath'    =>  '@bower/components-font-awesome',
        'css'           =>  ['css/font-awesome.min.css'],
    ],
    'Main'  =>  [
        'class'         =>  'yii\web\AssetBundle',
        'sourcePath'    =>  '@app/themes/seven/assets',
        'css'           =>  [
                                'css/animate.css',
                                'css/icomoon.css',
                                'css/style.css',
                                'css/custom.css',
                                'dist/css/AdminLTE.css',
                                'dist/css/skins/skin-blue.css',
                            ],
        'js'            => [
                                'js/wow.min.js',
                                'js/jquery.nav.js',            
                                'js/jquery.nicescroll.min.js',
                                'js/scripts.js',
                                'plugins/slimScroll/jquery.slimscroll.min.js',
                                'dist/js/app.min.js',
                            ],        
        'depends'       =>  [
                                'yii\web\YiiAsset',
                                'yii\bootstrap\BootstrapAsset',
                                'yii\bootstrap\BootstrapPluginAsset',
                                'yii\web\JqueryAsset',
                                'yii\jui\JuiAsset',
                                'Font',
                                'FontAwesome'
                            ]        
    ],
    'iCheck'    =>  [
        'class'         =>  'yii\web\AssetBundle',
        'sourcePath'    =>  '@bower/iCheck',
        'css'           =>  ['skins/line/blue.css'],
        'js'            =>  ['icheck.min.js'],
        'depends'       =>  ['Main']
    ],
    'bootstrap-switch'  =>  [
        'class'         =>  'yii\web\AssetBundle',
        'sourcePath'    =>  '@bower/bootstrap-switch/dist',
        'css'           =>  ['css/bootstrap3/bootstrap-switch.min.css'],
        'js'            =>  ['js/bootstrap-switch.min.js'],
        'depends'       =>  ['Main']        
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
        'sourcePath'    =>  '@bower/dante/dist/0.0.10',        
        'css'           =>  ['css/dante-editor.css'],
        'js'            =>  ['js/dante-editor.js'],
        'depends'       =>  ['yii\web\JqueryAsset','underscore','sanitize','Main']
    ],
    'custom-editor'     =>  [
        'class'         =>  'yii\web\AssetBundle',
        'sourcePath'    =>  '@app/themes/seven/assets/editor',
        'js'            =>  ['js/editor.js'],
        'depends'       =>  ['Main','dante']
    ],
    'show-post'         =>  [
        'class'         =>  'yii\web\AssetBundle',
        'sourcePath'    =>  '@bower/dante/dist/0.0.10',        
        'css'           =>  ['css/dante-editor.css']        
    ],
    'jasny-bootstrap'   =>  [
        'class'         =>  'yii\web\AssetBundle',
        'sourcePath'    =>  '@bower/jasny-bootstrap/dist',
        'js'            =>  ['js/jasny-bootstrap.min.js'],
        'css'           =>  ['css/jasny-bootstrap.min.css'],
        'depends'       =>  ['Main']
    ],
    'Main2' => [
        'class'         =>  'yii\web\AssetBundle',
        'sourcePath'    =>  '@app/themes/seven/assets',
        'css'           =>  [
                                'css/animate.css',
                                'css/icomoon.css',
                                'css/style.css',
                                'css/custom.css',

                                'dist/css/AdminLTE.css',
                                'dist/css/skins/_all-skins.css',
                                'plugins/iCheck/flat/blue.css',
                                'http://code.ionicframework.com/ionicons/2.0.0/css/ionicons.min.css',
                                'plugins/morris/morris.css',
                                'https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css',
                                'plugins/jvectormap/jquery-jvectormap-1.2.2.css',
                                'http://ifont.ir/apicode/17'
                            ],
        'js'            => [
                                'js/bootstrap.min.js',
                                'js/wow.min.js',
                                'js/jquery.nav.js',
                                'js/jquery.nicescroll.min.js',
                                'js/jquery.easing.min.js',
                                'js/scripts.js',

                                'http://code.jquery.com/ui/1.11.2/jquery-ui.min.js',
                                'http://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js',
                                'plugins/morris/morris.min.js',
                                'plugins/sparkline/jquery.sparkline.min.js',
                                'plugins/jvectormap/jquery-jvectormap-1.2.2.min.js',
                                'plugins/jvectormap/jquery-jvectormap-world-mill-en.js',
                                'plugins/jqueryKnob/jquery.knob.js',
                                'plugins/daterangepicker/daterangepicker.js',
                                'plugins/datepicker/bootstrap-datepicker.js',
                                'plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js',
                                'plugins/iCheck/icheck.min.js',
                                'plugins/slimScroll/jquery.slimscroll.min.js',
                                'dist/js/app.min.js',
                                'dist/js/pages/dashboard.js',
                                'dist/js/demo.js'            
                            ],
        'depends'       =>  [
                                'yii\web\YiiAsset',
                                'yii\bootstrap\BootstrapAsset',
                                'yii\bootstrap\BootstrapPluginAsset',
                                'yii\web\JqueryAsset',
                                'yii\jui\JuiAsset'            
                            ]
    ],
];