<?php
namespace app\modules\user\controllers;

use Yii;
use app\modules\user\Module;
use app\modules\user\models\User;
use app\modules\post\models\Post;
use app\modules\post\models\UserToRead;

class EmailPostSuggestionBase extends \yii\console\Controller{
    public function init() {
        parent::init();
        set_time_limit(0);
    }    
    
    protected function getPosts($userId,$limit = 7)
    {
        $posts      =   Post::find()
                        ->leftJoin(UserToRead::tableName(),Post::tableName().'.id = '.UserToRead::tableName().'.post_id')
                        ->where(UserToRead::tableName().'.user_id = :user_id AND '.UserToRead::tableName().'.priority=:priority AND '.Post::tableName().'.status = :status',[':user_id' =>  $userId,':status'   =>  Post::STATUS_PUBLISH,':priority'=>1])
                        ->orderBy(UserToRead::tableName().'.created_at DESC,'.UserToRead::tableName().'.score DESC,'.Post::tableName().'.score DESC,'.Post::tableName().'.published_at DESC')
                        ->limit($limit)                
                        ->all();          
        return $posts;
    }
    
    protected function getUsers($timestamp,$period = User::READING_LIST_DAILY,$limit = 10)
    {
        $users  =   User::find()
                    ->where('status=:status AND reading_list=:reading_list AND last_digest_mail=:last_digest_mail',[
                        ':status'               =>      User::STATUS_ACTIVE,
                        ':reading_list'         =>      $period,
                        ':last_digest_mail'     =>      $timestamp
                    ])->limit($limit)->all();
        
        return $users;
    }
    
    protected function sendDigestMail(User $user,array $posts)
    {
        $top        =   $posts[0];
        return \Yii::$app->mailer
                    ->compose('@mail/digest', ['user' => $user,'posts'=>$posts])
                    ->setSubject(Module::t('mail','digest.title',['subject'=>$top->title]))
                    ->setFrom([Yii::$app->params['noreply-email']  =>  Module::t('mail','sender.digest.name')])                    
                    ->setTags(['digest',  Yii::$app->name])
                    ->setTo($user->email)
                    ->send();
    }

    protected function updateUserDigestTime(User $user)
    {
        $user->last_digest_mail =   new \yii\db\Expression('NOW()');
        $user->save(false);
    }
}