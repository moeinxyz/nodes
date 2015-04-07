<?php

namespace app\modules\daemon;

class Module extends \yii\base\Module
{
    const CHECK_INTERVAL            =   86400;
    const ADDITIONAL_SLEEP_SECS     =   5;
    public $controllerNamespace = 'app\modules\daemon\controllers';
    
    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }
}
