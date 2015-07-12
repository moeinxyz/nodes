<?php

namespace app\assets;

use yii\web\AssetBundle;

class CustomEditorAssets extends AssetBundle
{
    public $sourcePath =   '@app/themes/seven/assets/editor';
    public $css = [];
    public $js = [
        'js/editor.js'
    ];
    public $depends = [
        'app\assets\MainAssets',
        'app\assets\DanteAssets'
    ];
}
