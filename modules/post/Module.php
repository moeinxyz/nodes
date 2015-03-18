<?php

namespace app\modules\post;

use Yii;

class Module extends \yii\base\Module
{
    public $controllerNamespace =   'app\modules\post\controllers';
    public $postTable           =   '{{%post}}';
    public $imageTable          =   '{{%image}}';
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
                'modules/post/post'     => 'post.php',
            ],
        ];
    }

    public static function t($category, $message, $params = [], $language = null)
    {
        return Yii::t('modules/post/' . $category, $message, $params, $language);
    }    
}
