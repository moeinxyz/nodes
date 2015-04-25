<?php
namespace app\components\Helper;

use DOMDocument;
use DOMXPath;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\CssSelector\CssSelector;

class Extract {
    public static function extractTitle($html){
        $encode                     =   mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8');
        $crawler = new Crawler();
        $crawler->addContent($encode);
        $titleDom   =   $crawler->filter('.graf--first');
        if ($titleDom->count() >= 1) 
            return $titleDom->text ();
        $firstDom   =   $crawler->filter('.graf');
        if ($firstDom->count() > 1)
            return $firstDom->first()->text();
        return '';
    }
    
    public static function extractContent($html){
        libxml_use_internal_errors(true);
        $encode                     =   mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8');
        $dom                        =   new DOMDocument('1.0','UTF-8');
        $dom->preserveWhiteSpace    =   TRUE;
        @$dom->loadHTML($encode);
        libxml_clear_errors();
        $expression                 =   (new CssSelector())->toXPath('.graf--first');
        $xpath                      =   new DOMXPath($dom);
        $node                       =   $xpath->query($expression)->item(0);
        if ($node){
            $node->parentNode->removeChild($node);    
        }
        $node                       =   $dom->getElementsByTagName('body')->item(0);
        $code                       =   $dom->saveHTML($node);
        if (strpos($code, '<body>') === 0){
            $code = substr($code, 6);
        }
        if (($pos = strpos($code, '</body>')) && $pos != NULL){
            $code = substr($code, 0,$pos);
        }
        return $code;
    }
}