<?php
namespace app\gearworker;

use Yii;
use filsh\yii2\gearman\JobBase;
use yii\db\Query;
use app\modules\post\models\Post;
use app\modules\user\models\Following;
use app\modules\post\models\Userrecommend;
use app\modules\post\models\Userread;
use app\modules\post\models\Guestread;
use app\modules\post\models\UserToRead;

class PostSuggestionForUser extends JobBase
{
    private $posts;
    
    public function execute(\GearmanJob $job = null)
    {
        $params     =   unserialize($job->workload())->getParams();
        $this->generatePostsRank($params['userId']);
        $rows       =   [];
        $posts      =   [];
        $timestamp  =   date("Y-m-d H:i:s", time());

        
        foreach ($this->posts as $postId => $score){
            $posts[]    =   $postId;
            if ($score < 255){
                $rows[] =   [$params['userId'],$postId,  round($score),$timestamp];    
            } else {
                $rows[] =   [$params['userId'],$postId,  255,$timestamp];
            }
        }
        
        if (count($rows) >= 1){
            \Yii::$app->db->createCommand()
                ->batchInsert(UserToRead::tableName(), ['user_id','post_id','score','created_at'], $rows)
                ->execute();
            UserToRead::deleteAll(['AND','user_id = :user_id AND created_at < :timestamp',['in','post_id',$posts]],[
                ':timestamp'    =>  $timestamp,
                ':user_id'      =>  $params['userId']
            ]);
        }
        
    }
    
    private function generatePostsRank($userId)
    {
        $this->posts                    =   [];
        //id, comments_count, published_at, pure_text
        $getFriendsPost                 =   $this->getUnreadedFriendsPost($userId);
        $getFriendsRecommendedPost      =   $this->getFriendsRecommendedPost($userId);
        $getLastUnreadedPost            =   $this->getLastUnreadedPost($userId);
        
        foreach ($getFriendsPost as $post)
        {
            $score  =   $this->generateWordsCountRank($post['pure_text']);
            $score  +=  50; // My Friend Wrote It
            $score  +=  $this->generateCommentsCountRank($post['comments_count']);
            
            $this->addPostScore($post['id'], $score);
        }
        
        foreach ($getFriendsRecommendedPost as $post)
        {
            $score  =  10; // My Friend Recommend It
            // if not calcuate before
            if (!key_exists($post['id'], $this->posts)){
                $score  +=  $this->generateWordsCountRank($post['pure_text']);
                $score  +=  $this->generateCommentsCountRank($post['comments_count']);
            }

            $this->addPostScore($post['id'], $score);
        }
        
        $current   =   time();
        
        foreach ($getLastUnreadedPost as $post)
        {
            $score  =   $this->generateTimeBasedRank($current, strtotime($post['published_at']));
            // if not calcuate before
            if (!key_exists($post['id'], $this->posts)){
                $score  +=  $this->generateWordsCountRank($post['pure_text']);
                $score  +=  $this->generateCommentsCountRank($post['comments_count']);
            }
            
            $this->addPostScore($post['id'], $score);
        }
    }

    private function getUnreadedFriendsPost($userId)
    {
        $query  =   new Query;
        $query->select('id, comments_count, published_at, pure_text')
                ->from(Post::tableName())
                ->leftJoin(Following::tableName(),  Post::tableName().'.user_id = '. Following::tableName().'.followed_user_id')
                ->where(Post::tableName().'.status = :status',[':status'=>Post::STATUS_PUBLISH])
                ->andWhere(Following::tableName().'.user_id = :user_id', [':user_id'=>$userId])
                ->andWhere(Post::tableName().'.published_at > DATE_SUB(now(), INTERVAL 100 DAY)')
                ->andWhere(Post::tableName().'.id NOT IN (SELECT DISTINCT post_id FROM '.Userread::tableName().' WHERE '.Userread::tableName().'.user_id = :user_id)',[':user_id'=>$userId])
                ->orderBy(Post::tableName().'.published_at');
        return $query->all();    
    }
    
    private function getFriendsRecommendedPost($userId)
    {
        $query  =   new Query;
        $query->select('id, comments_count, published_at, pure_text')
                ->from(Post::tableName())
                ->leftJoin(Userrecommend::tableName(), Post::tableName().'.id = '.Userrecommend::tableName().'.post_id')
                ->where(Post::tableName().'.status = :status',[':status'=>Post::STATUS_PUBLISH])
                ->andWhere(Post::tableName().'.user_id != :user_id', [':user_id'=>$userId])
                ->andWhere(Post::tableName().'.published_at > DATE_SUB(now(), INTERVAL 100 DAY)')
                ->andWhere(Post::tableName().'.id NOT IN (SELECT DISTINCT post_id FROM '.Userread::tableName().' WHERE '.Userread::tableName().'.user_id = :user_id)',[':user_id'=>$userId])
                ->andWhere(Userrecommend::tableName().'.user_id IN (SELECT DISTINCT followed_user_id FROM '.Following::tableName().' WHERE '.Following::tableName().'.user_id = :user_id)',[':user_id'=>$userId])
                ->orderBy(Post::tableName().'.published_at');

        return $query->all();
    }
    
    private function getLastUnreadedPost($userId)
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
            .       '(SELECT DISTINCT post_id FROM '.Userread::tableName().' WHERE user_id = :user_id) ';

        $query =    Yii::$app->db->createCommand($sql)
                ->bindValue(':status', Post::STATUS_PUBLISH)
                ->bindValue(':user_id', $userId);
        
        return $query->queryAll();
    }    
    
    private function addPostScore($postId,$score = 0)
    {
        if (!key_exists($postId, $this->posts)){
            $this->posts[$postId]   =   0;
        }
        $this->posts[$postId]   +=  $score;
    }
    
    private function generateWordsCountRank($pureText)
    {
        /**
         * Function : [0,3200]  => -(x^2 - 3200x) / 51200
         * http://www.wolframalpha.com/input/?i=-%28x%5E2+-+3200x%29+%2F+51200
         * Function : [0,+INF]  =>  0
         */
        $count  =   count(preg_split('~[^\p{L}\p{N}\']+~u',$pureText));
        if ($count < 3200){
            return ((-1) * ($count * $count - 3200 * $count)) / 51200;
        }
        return 0;
    }
    
    private function generateCommentsCountRank($commentsCount)
    {
        /**
         * Absolutely need better determination
         * Function:    [0,10]      =>  x
         * Function:    [11,+INF]   =>  10
         */
        if ($commentsCount <= 10){
            return $commentsCount;
        }
        return 10;
    }
    
    private function generateTimeBasedRank($current,$timestamp)
    {
        if ($current > $timestamp){
            $x      =   $current    -   $timestamp;
            $result =   (-20 * $x) / (2160000) + 20;
            if ($result >= 0){
                return $result;
            }
        }
        return 0;
    }
}