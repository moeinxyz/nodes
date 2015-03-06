<?php

namespace app\modules\user\models;
use Yii;
use yii\base\Model;
use app\modules\user\models\User;
use app\modules\user\Module;

class ChangeSettingForm extends Model{
    public $content_activity;
    public $publisher_activity;
    public $social_activity;
    public $reading_list;
    
    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            //@todo add publisher_activity
            [['content_activity','social_activity'],'in','range'=>[User::ACTIVITY_SETTING_DIGEST,User::ACTIVITY_SETTING_FULL,User::ACTIVITY_SETTING_OFF]],
            [['reading_list'],'in','range'=>[User::READING_LIST_DAILY,User::READING_LIST_OFF,User::READING_LIST_WEEKLY]],        ];
    }

    public function attributeLabels()
    {
        return [
            'content_activity'      =>  Module::t('user','chgSetting.attr.content_activity'),
            'publisher_activity'    =>  Module::t('user','chgSetting.attr.publisher_activity'),            
            'social_activity'       =>  Module::t('user','chgSetting.attr.social_activity'),
            'reading_list'          =>  Module::t('user','chgSetting.attr.reading_list'),
        ];
    }      
    
    public function save()
    {
        if ($this->validate()){
            $user   =   User::findOne(Yii::$app->user->id);    
            $user->content_activity = $this->content_activity;
            $user->social_activity  = $this->social_activity;
            $user->reading_list     = $this->reading_list;
            //@todo add publisher activity
            return $user->save();
        }
        return false;
    }

    public static function getActivitySettingList()
    {
        return [
            User::ACTIVITY_SETTING_DIGEST   =>  Module::t('user','user.activity_setting.digest'),
            User::ACTIVITY_SETTING_FULL     =>  Module::t('user','user.activity_setting.full'),
            User::ACTIVITY_SETTING_OFF      =>  Module::t('user','user.activity_setting.off')
        ];
    }
    public static function getReadingListSettingList()
    {
        return [
            User::READING_LIST_DAILY   =>  Module::t('user','user.reading_list.daily'),
            User::READING_LIST_WEEKLY      =>  Module::t('user','user.reading_list.weekly'),
            User::READING_LIST_OFF     =>  Module::t('user','user.reading_list.off')
        ];
    }
}