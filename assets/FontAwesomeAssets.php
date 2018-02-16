<?php

namespace app\assets;

use yii\web\AssetBundle;

class FontAwesomeAssets extends AssetBundle
{
    public $sourcePath =   '@bower/components-font-awesome';
    public $css = [
        'css/font-awesome.min.css',
    ];
    public $js = [];
    public $depends = [];
}
