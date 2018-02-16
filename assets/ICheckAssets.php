<?php

namespace app\assets;

use yii\web\AssetBundle;

class ICheckAssets extends AssetBundle
{
    public $sourcePath =   '@bower/icheck';
    public $css = [
        'skins/line/blue.css',
    ];
    public $js = [
        'icheck.min.js'
    ];
    public $depends = [
        'app\assets\MainAssets',
    ];
}
