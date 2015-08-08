<?php
namespace app\modules\user\controllers;

use app\modules\user\Module;
use app\modules\user\models\User;

class WeeklyEmailPostSuggestionDigestDaemonController extends EmailPostSuggestionBase
{    
    public function actionIndex()
    {
        do
        {
            $timestamp      =   User::find()->where('status=:status AND reading_list=:reading_list',
                                            [':status'=>User::STATUS_ACTIVE,':reading_list'=>User::READING_LIST_WEEKLY])
                                            ->min('last_digest_mail');
            
            $farthestTime   =   strtotime($timestamp);
            $diffTime       =   time()  -   $farthestTime;
            
            if ($timestamp === NULL || $diffTime < Module::WEEK_SECONDS){
                sleep(Module::WEEK_SECONDS - $diffTime + Module::ADDITIONAL_SLEEP_SECS);
                continue;
            }
            
            $users  = $this->getUsers($timestamp, User::READING_LIST_WEEKLY);
            
            foreach ($users as $user)
            {
                $posts  =   $this->getPosts($user->id);
                if (count($posts) >= 3)
                {
                    $this->sendDigestMail($user, $posts);
                }
                $this->updateUserDigestTime($user);
            }
        } while(true);
    }    
}