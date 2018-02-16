<?php

namespace app\assets;

use yii\web\AssetBundle;

class SanitizeAssets extends AssetBundle
{
    public $sourcePath =   '@bower/sanitize.js/lib';
    public $css = [];
    public $js = [
        'sanitize.js'
    ];
    public $depends = [];
}
