<?php

namespace app\assets;

use yii\web\AssetBundle;

class UnderscoreAssets extends AssetBundle
{
    public $sourcePath =   '@bower/underscore';
    public $css = [];
    public $js = [
        'underscore-min.js'
    ];
    public $depends = [];
}
