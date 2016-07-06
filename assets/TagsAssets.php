<?php

namespace app\assets;

use yii\web\AssetBundle;

class TagsAssets extends AssetBundle
{
    public $sourcePath =   '@bower/bootstrap-tagsinput/dist';
    public $css = [
        'bootstrap-tagsinput.css'
    ];
    public $js = [
        'bootstrap-tagsinput.min.js'
    ];
    public $depends = [
        'app\assets\UnderscoreAssets',
        'app\assets\SanitizeAssets',
        'app\assets\MainAssets'
    ];
}
