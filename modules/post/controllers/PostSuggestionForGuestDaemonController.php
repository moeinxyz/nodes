<?php
namespace app\modules\post\controllers;

use Yii;


use app\modules\post\Module;
use app\modules\post\models\Post;
use yii\db\Query;
class PostSuggestionForGuestDaemonController extends \yii\console\Controller{
    
    public function init() {
        parent::init();
        set_time_limit(0);
    }

    public function actionIndex()
    {
        do
        {
            $timestamp      =   GuestToRead::find()->max('created_at');
            $nearestTime    =   strtotime($timestamp);
            $diffTime       =   time()  -   $nearestTime;

            if ($timestamp != NULL && $diffTime < Module::CHECK_INTERVAL){
                sleep(Module::CHECK_INTERVAL - $diffTime + Module::ADDITIONAL_SLEEP_SECS);
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

    
    private function getLastWeekTopPost()
    {

        $sql    =   'SELECT id, comments_count, published_at, pure_text'
            .' ,( SELECT COUNT(*) '   //correlated subquery
            . 'FROM '.Userread::tableName()
            . ' WHERE '.  Post::tableName().'.id = '.Userread::tableName().'.post_id'
            . ') AS user_read_count '
            .' ,( SELECT COUNT(*) '   //correlated subquery
            . 'FROM '.Guestread::tableName()
            . ' WHERE '.  Post::tableName().'.id = '.Guestread::tableName().'.post_id'
            . ') AS guest_read_count '                
            . ' FROM '.Post::tableName()
            .       ' WHERE '.Post::tableName().'.status = :status AND '
            .       Post::tableName().'.user_id != :user_id AND '                
            .       Post::tableName().'.published_at > DATE_SUB(now(), INTERVAL 25 DAY) AND '
            .       Post::tableName().'.id NOT IN '                
            .       '(SELECT DISTINCT post_id FROM '.Userread::tableName().' WHERE user_id = :user_id) '
            .       'ORDER BY '.Post::tableName().'.published_at';        
        $query =    Yii::$app->db->createCommand($sql)
                ->bindValue(':status', Post::STATUS_PUBLISH)
                ->bindValue(':user_id', $userId);
        
        return $query->queryAll();
        
        
        $query  =   new Query();
//        $query->select('id, ')
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
