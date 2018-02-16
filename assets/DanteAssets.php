<?php

namespace app\assets;

use yii\web\AssetBundle;

class DanteAssets extends AssetBundle
{
    public $sourcePath =   '@bower/dante/dist';
    public $css = [];
    public $js = [
        'js/dante-editor.js'
    ];
    public $depends = [
        'app\assets\UnderscoreAssets',
        'app\assets\SanitizeAssets',
        'app\assets\MainAssets'
    ];
}
