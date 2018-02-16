<?php

namespace app\assets;

use yii\web\AssetBundle;

class JasnyBootstrapAssets extends AssetBundle
{
    public $sourcePath =   '@bower/jasny-bootstrap/dist';
    public $css = [
        'css/jasny-bootstrap.min.css'
    ];
    public $js = [
        'js/jasny-bootstrap.min.js',
    ];
    public $depends = [
        'app\assets\MainAssets',
    ];
}
