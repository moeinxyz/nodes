<?php

namespace app\modules\post;

use Yii;

class Module extends \yii\base\Module
{
    const CHECK_INTERVAL        =   3600;
    const ADDITIONAL_SLEEP_SECS =   5;
    
    public $controllerNamespace =   'app\modules\post\controllers';
    public $postTable           =   '{{%post}}';
    public $imageTable          =   '{{%image}}';
    public $commentTable        =   '{{%comment}}';
    public $abuseTable          =   '{{%abuse}}';
    public $userRecommendTable  =   '{{%userrecommend}}';
    public $userReadTable       =   '{{%userread}}';
    public $guestReadTable      =   '{{%guestread}}';
    public $userToReadTable     =   '{{%usertoread}}';

    public function init()
    {
        parent::init();
        $this->registerTranslations();
    }
    public function registerTranslations()
    {
        Yii::$app->i18n->translations['modules/post/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en-US',
            'basePath' => '@app/modules/post/messages',
            'fileMap' => [
                'modules/post/post'         => 'post.php',
                'modules/post/comment'      => 'comment.php',
                'modules/post/userread'     => 'userread.php',
                'modules/post/guestread'    => 'guestread.php',
            ],
        ];
    }

    public static function t($category, $message, $params = [], $language = null)
    {
        return Yii::t('modules/post/' . $category, $message, $params, $language);
    }    
}
