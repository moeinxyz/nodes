<?php

namespace app\assets;

use yii\web\AssetBundle;

class BootstrapSwitchAssets extends AssetBundle
{
    public $sourcePath =   '@bower/components-font-awesome';
    public $css = [
        'css/font-awesome.min.css',
    ];
    public $js = [];
    public $depends = [
        'app\assets\MainAssets'
    ];
}
