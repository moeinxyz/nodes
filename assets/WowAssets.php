<?php

namespace app\assets;

use yii\web\AssetBundle;

class WowAssets extends AssetBundle
{
    public $sourcePath =   '@bower/wow/dist';
    public $css = [];
    public $js = [
        'wow.min.js',
    ];
    public $depends = [];
}
