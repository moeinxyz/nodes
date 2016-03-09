<?php

namespace app\assets;

use yii\web\AssetBundle;

class ShowPostAssets extends AssetBundle
{
    public $sourcePath =   '@app/themes/seven/assets/editor';
    public $css = [
        'css/dante-editor.css'
    ];
    public $js = [];
    public $depends = [
        'app\assets\MainAssets',
    ];
}
