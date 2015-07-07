<?php
namespace app\modules\post\controllers;

use Yii;
use app\modules\post\Module;
use app\modules\post\models\Post;
use filsh\yii2\gearman\JobWorkload;

class PostSuggestionForGuestDaemonController extends \yii\console\Controller{
    
    public function init() {
        parent::init();
        set_time_limit(0);
    }

    public function actionIndex()
    {
        do
        {
            $timestamp      =   Post::find()->where('status=:status',[':status'=>  Post::STATUS_PUBLISH])
                                    ->min('score_update_requested_at');
            
            $farthestTime   =   strtotime($timestamp);
            $diffTime       =   time()  -   $farthestTime;
            
            if ($timestamp != NULL && $diffTime < Module::CHECK_INTERVAL){
                sleep(Module::CHECK_INTERVAL - $diffTime + Module::ADDITIONAL_SLEEP_SECS);
            }
            
            $posts  =   Post::find()->select('id')->where('status=:status AND score_update_requested_at=:timestamp',[
                ':status'       =>  Post::STATUS_PUBLISH,
                ':timestamp'    =>  $timestamp
            ])->limit(50)->all();
            
            foreach ($posts as $post)
            {
                if ($this->addMessageToBroker($post->id)){
                    $post->score_update_requested_at    =   new \yii\db\Expression('NOW()');
                    $post->save(false);
                } //  else try in next while loop
            }
        } while(true);
    }


    private function addMessageToBroker($postId)
    {
        try {
            Yii::$app->gearman->getDispatcher()->background('PostSuggestionForGuest', new JobWorkload([
                'params' => ['postId'    =>  $postId]
            ]));
            return true;
        } catch (\Sinergi\Gearman\Exception $ex) {
            // log exception
            return false;
        } catch (\Exception $ex){
            // log exception
            return false;
        }                  
    }
}
