<?php
namespace app\modules\post\controllers;

use Yii;
use app\modules\post\Module;
use app\modules\post\models\Post;
use app\components\Broker;

class PostSuggestionForGuestDaemonController extends \yii\console\Controller{
    
    public function init() {
        parent::init();
        set_time_limit(0);
        register_shutdown_function(function (){
            Broker::close();
        });
    }

    public function actionIndex()
    {
        do
        {
            $timestamp      =   Post::find()->where('status=:status',[':status'=>  Post::STATUS_PUBLISH])
                                    ->min('score_update_requested_at');
            
            if ($timestamp === NULL){
                sleep(Module::CHECK_INTERVAL);
                Yii::$app->db->close();
                Yii::$app->db->open();                  
                continue;
            }

            $diff   =   time() - strtotime($timestamp);
            
            if ($diff < Module::CHECK_INTERVAL){
                sleep(Module::CHECK_INTERVAL + Module::ADDITIONAL_SLEEP_SECS - $diff);
                Yii::$app->db->close();
                Yii::$app->db->open();                  
                continue;
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
            Broker::publishMessage(['postId'    =>  $postId], 'PostSuggestionForGuest');
            return true;
        } catch (\Exception $ex){
            // log exception
            return false;
        }                  
    }
}
