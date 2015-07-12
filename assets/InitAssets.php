<?php

namespace app\assets;

use yii\web\AssetBundle;

class InitAssets extends AssetBundle
{
    public $sourcePath =   '@bower';
    public $css = [
        'animate.css/animate.min.css',
    ];
    public $js = [
        'jQuery-One-Page-Nav/jquery.nav.js',
        'jquery.nicescroll/dist/jquery.nicescroll.min.js',
        'slimscroll/jquery.slimscroll.min.js'        
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapPluginAsset',        
        'app\assets\FontAwesomeAssets',
    ];
}
