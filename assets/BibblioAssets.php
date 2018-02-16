<?php
/**
 * Created by IntelliJ IDEA.
 * User: moein
 * Date: 2/15/18
 * Time: 11:03 PM
 */

namespace app\assets;


use yii\web\AssetBundle;

class BibblioAssets extends AssetBundle
{
    public $sourcePath =   '@app/themes/seven/assets/bibblio-related-content-module';
    public $css = [
        'css/bib-related-content.css',
    ];
    public $js = [
        'js/bib-related-content.js',
    ];
}