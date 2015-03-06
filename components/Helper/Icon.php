<?php

namespace app\components\Helper;

use app\modules\user\models\Url;

class Icon {
    public static function getBrandIconClass($type)
    {
        if ($type === Url::TYPE_GOOGLE_PLUS){
            return "fa fa-google-plus";
        } else if ($type === Url::TYPE_BITBUCKET){
            return "fa fa-bitbucket";
        } else if ($type === Url::TYPE_FACEBOOK){
            return "fa fa-facebook";
        } else if ($type === Url::TYPE_TWITTER){
            return "fa fa-twitter";
        } else if ($type === Url::TYPE_FLICKR){
            return "fa fa-flickr";
        } else if ($type === Url::TYPE_GITHUB){
            return "fa fa-github";
        } else if ($type === Url::TYPE_INSTAGRAM){
            return "fa fa-instagram";
        } else if ($type === Url::TYPE_LINKEDIN){
            return "fa fa-linkedin";
        } else if ($type === Url::TYPE_PNTEREST){
            return "fa fa-pinterest";
        } else if ($type === Url::TYPE_TUMBLER){
            return "fa fa-tumblr";
        } else if ($type === Url::TYPE_YOUTUBE){
            return "fa fa-youtube";
        } else if ($type === Url::TYPE_VK){
            return "fa fa-vk";
        } else if ($type === Url::TYPE_STACKOVERFLOW){
            return "fa fa-stack-overflow";
        } else {
            return "fa fa-globe";
        }
    }
}