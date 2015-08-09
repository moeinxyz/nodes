<?php

namespace app\modules\user\controllers;

use app\modules\user\Module;
use app\modules\user\models\User;

class DigestContentActicityDaemonController extends EmailContentActicityBase
{
    public function actionIndex()
    {
        do{
            
            $timestamp      =   User::find()->where('status=:status AND content_activity=:content_activity',
                                            [':status'=>User::STATUS_ACTIVE,':content_activity'=>User::ACTIVITY_SETTING_DIGEST])
                                            ->min('last_content_activity_mail');            
            
            $farthestTime   =   strtotime($timestamp);
            $diffTime       =   time()  -   $farthestTime;
            
            if ($timestamp === NULL || $diffTime < Module::HOUR_SECONDS){
                sleep(Module::HOUR_SECONDS - $diffTime + Module::ADDITIONAL_SLEEP_SECS);
                continue;
            }
            
            $users  = $this->getUsers($timestamp, User::ACTIVITY_SETTING_DIGEST);
            
            foreach ($users as $user)
            {
                $comments   = $this->getComments($user->getId());
                if ($this->isDigestShouldBeSend($user,$comments))
                {
                    if (count($comments) === 1){
                        $this->sendFullContentActivityEmail($user, $comments[0]);
                        $this->setCommentsAsSent($comments);
                    } else {
                        $this->sendDigestContentActivityEmail($user, $comments);
                        $this->setCommentsAsSent($comments);
                    }
                }
                $this->updateUserContentActivityTime($user);
            }
        } while(true);
    }    
}