<?php
namespace app\components\Helper;
use HTMLPurifier_Config;
class Purifier{
    public static function getConfig(){
        return function ($config){
            $config->set('HTML.SafeEmbed',true);
            $config->set('HTML.SafeObject',true);
            $config->set('HTML.SafeIframe', true);
            $config->set('URI.SafeIframeRegexp', '%^(https?:)?//(www\.aparat\.com/video/video/embed/videohash/|www\.youtube(?:-nocookie)?\.com/embed/|player\.vimeo\.com/video/)%');
            $def = $config->getHTMLDefinition(true);
            $def->addElement('figure', 'Block', 'Optional: (figcaption, Flow) | (Flow, figcaption) | Flow', 'Common');
            $def->addElement('figcaption', 'Inline', 'Flow', 'Common');            
        };
    }
}