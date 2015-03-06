<?php

namespace app\modules\embed;

class Module extends \yii\base\Module
{
    public $controllerNamespace =   'app\modules\embed\controllers';
    public $embedTable          =   '{{%embed}}';
    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }
}
