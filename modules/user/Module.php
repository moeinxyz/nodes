<?php

namespace app\modules\user;
use Yii;

class Module extends \yii\base\Module
{
    const ADDITIONAL_SLEEP_SECS =   5;
    
    const CHECK_INTERVAL        =   43200;
    
    const MINUTE_SECONDS        =   60;
    const HOUR_SECONDS          =   3600;
    const DAY_SECONDS           =   86400;
    const WEEK_SECONDS          =   604800;
    
    
    public $controllerNamespace =   'app\modules\user\controllers';
    public $userTable           =   '{{%user}}';
    public $urlTable            =   '{{%url}}';
    public $tokenTable          =   '{{%token}}';
    public $followingTable      =   '{{%following}}';
    public $unwantToFollowTable =   '{{%unwanted}}';
    
    public $defaultRoute        =   'user';
    
    public $tokenLimitTime      =   86400;
    public function init()
    {
        parent::init();
        $this->registerTranslations();
        Yii::$app->setAliases(['@mail'=>'@app/themes/seven/views/user/views/mail']);
    }
    public function registerTranslations()
    {
        Yii::$app->i18n->translations['modules/user/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en-US',
            'basePath' => '@app/modules/user/messages',
            'fileMap' => [
                'modules/user/user'     =>  'user.php',
                'modules/user/token'    =>  'token.php',
                'modules/user/following'=>  'following.php',
                'modules/user/mail'     =>  'mail.php'
            ],
        ];
    }

    public static function t($category, $message, $params = [], $language = null)
    {
        return Yii::t('modules/user/' . $category, $message, $params, $language);
    }    
}
