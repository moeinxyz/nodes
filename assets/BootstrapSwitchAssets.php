<?php

namespace app\assets;

use yii\web\AssetBundle;

class BootstrapSwitchAssets extends AssetBundle
{
    public $sourcePath =   '@bower/bootstrap-switch/dist';
    public $css = [
        'css/bootstrap3/bootstrap-switch.min.css',
    ];
    public $js = [
        'js/bootstrap-switch.min.js',
    ];
    public $depends = [
        'app\assets\MainAssets'
    ];
}
