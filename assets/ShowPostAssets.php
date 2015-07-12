<?php

namespace app\assets;

use yii\web\AssetBundle;

class ShowPostAssets extends AssetBundle
{
    public $sourcePath =   '@bower/dante/dist';
    public $css = [
        'css/dante-editor.css'
    ];
    public $js = [];
    public $depends = [
        'app\assets\MainAssets',
    ];
}
