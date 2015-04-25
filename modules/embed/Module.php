<?php

namespace app\modules\embed;

class Module extends \yii\base\Module
{
    const SITE_APARAT_COM           =   'aparat.com';
    const SITE_APARAT_WWW_COM       =   'www.aparat.com';
    const SITE_APARAT_IR            =   'aparat.ir';
    const SITE_APARAT_WWW_IR        =   'www.aparat.ir';
    
    public $controllerNamespace =   'app\modules\embed\controllers';
    public $embedTable          =   '{{%embed}}';
    public $defaultRoute        =   '{{%embed}}';
    public $timeLimit           =    259200;//3 days
    public $aparatEmbedRange    =   [self::SITE_APARAT_COM,self::SITE_APARAT_IR,self::SITE_APARAT_WWW_COM,self::SITE_APARAT_WWW_IR];
    public $customEmbedRange    =   NULL;
    
    public function init()
    {
        $this->customEmbedRange = array_merge($this->aparatEmbedRange);
        parent::init();
        // custom initialization code goes here
    }
}
