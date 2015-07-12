<?php

namespace app\assets;

use yii\web\AssetBundle;

class MainAssets extends AssetBundle
{
    public $sourcePath =   '@app/themes/seven/assets/master/';
    public $css = [
        'css/icomoon.css',
        'css/style.css',
        'css/custom.css',
        'css/AdminLTE.css',
        'css/skin-blue.css'
    ];
    public $js = [
        'js/main.js',
        'js/app.min.js'        
    ];
    public $depends = [
        'app\assets\InitAssets',
        'app\assets\WowAssets',
    ];
}
