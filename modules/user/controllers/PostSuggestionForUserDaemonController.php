<?php
namespace app\modules\user\controllers;

use Yii;
use app\modules\user\models\User;
use app\modules\user\Module;
use filsh\yii2\gearman\JobWorkload;
class PostSuggestionForUserDaemonController extends \yii\console\Controller{
    
    public function init() {
        parent::init();
        set_time_limit(0);
    }

    public function actionIndex()
    {
        do
        {
            $timestamp      =   User::find()->where('status=:status',[':status'=>  User::STATUS_ACTIVE])
                                    ->min('last_post_suggestion');
            
            $farthestTime   =   strtotime($timestamp);
            $diffTime       =   time()  -   $farthestTime;
            
            if ($timestamp != NULL && $diffTime < Module::CHECK_INTERVAL){
                sleep(Module::CHECK_INTERVAL - $diffTime + Module::ADDITIONAL_SLEEP_SECS);
                continue;
            }
            
            $users  =   User::find()->select('id')->where('status=:status AND last_post_suggestion=:timestamp',[
                ':status'    =>  User::STATUS_ACTIVE,
                ':timestamp' =>  $timestamp
            ])->limit(10)->all();
            
            foreach ($users as $user)
            {
                if ($this->addMessageToBroker($user->id)){
                    $user->last_post_suggestion = new \yii\db\Expression('NOW()');
                    $user->save(false);
                } //  else try in next while loop
            }
        } while(true);
    }
    
    
    private function addMessageToBroker($userId)
    {
        try {
            Yii::$app->gearman->getDispatcher()->background('PostSuggestionForUser', new JobWorkload([
                'params' => ['userId'    =>  $userId]
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
