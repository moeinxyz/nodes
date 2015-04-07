<?php

namespace app\modules\daemon\controllers;

use \vyants\daemon\DaemonController;
use app\modules\user\models\User;
use app\modules\post\models\Post;
use app\modules\social\models\Social;
use app\modules\daemon\Module;
/**
 * This command run SocialDaemon which share user content in social medias.
 */
class SocialDaemonController extends DaemonController
{
    /**
     * @return array
     */
    protected function defineJobs()
    {
        Social::find()->one()->delete();
        return [];
        $timestamp      =   Social::find()->where('status=:status',['status'=>  Social::STATUS_ACTIVE])->min('last_used');
        \Yii::error('FUCK YOU MAN'.serialize($timestamp));
        $farthestTime   =   strtotime($timestamp);
        $diffTime       =   time()  -   $farthestTime;
        
        if ($timestamp == NULL || $diffTime < Module::CHECK_INTERVAL){
            sleep(Module::CHECK_INTERVAL - $diffTime + Module::ADDITIONAL_SLEEP_SECS);
        }
        
        $socials = Social::find()->where('status=:status AND last_used=:last_used',[
            'status'    =>  Social::STATUS_ACTIVE,
            'last_used' =>  $timestamp
        ])->limit(10)->all();
        
        return $socials;
    }
    /**
     * @return jobtype
     */
    protected function doJob($job)
    {
        \Yii::error(serialize($job));
    }
    
    private function shareOnLinkedin(User $user,Post $post)
    {
        
    }
}