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

class UserPost extends JobBase
{
    public function execute(\GearmanJob $job = null)
    {
        $params     =   unserialize($job->workload())->getParams();
        
        $this->uploadImage($params['userId'], $params['image'],$params['type']);    
    }
    
    private function getPostsRank($userId)
    {
        $posts                          =   [];
        $getFriendsPost                 =   $this->getUnreadedFriendsPost($userId);
        $getFriendsRecommendedPost      =   $this->getFriendsRecommendedPost($userId);
        $getLastUnreadedPost            =   $this->getLastUnreadedPost($userId);
        
        foreach ($getFriendsPost as $post)
        {
            
        }
        
        return $posts;
    }
    

    
    private function getRanking()
    {
        
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
                ->leftJoin(Following::tableName(),  Post::tableName().'.user_id = '. $userId)
                ->leftJoin(Userrecommend::tableName(), Post::tableName().'.user_id = '.Userrecommend::tableName().'.followed_user_id')
                ->where(Post::tableName().'.status = :status',[':status'=>Post::STATUS_PUBLISH])
                ->andWhere(Post::tableName().'.user_id != :user_id', [':user_id'=>$userId])
                ->andWhere(Post::tableName().'.published_at > DATE_SUB(now(), INTERVAL 100 DAY)')
                ->andWhere(Post::tableName().'.id NOT IN (SELECT DISTINCT post_id FROM '.Userread::tableName().' WHERE '.Userread::tableName().'.user_id = :user_id)',[':user_id'=>$userId])
                ->orderBy(Post::tableName().'.published_at');
        return $query->all();
    }
    
    private function getLastUnreadedPost($userId)
    {
        $sql    =   'SELECT id, comments_count, published_at, pure_text'
            .' ( SELECT COUNT(*) '   //correlated subquery
            . 'FROM '.Userread::tableName()
            . ' WHERE '.  Post::tableName().'.id = '.Userread::tableName().'.post_id'
            . ') AS user_read_count '
            .' ( SELECT COUNT(*) '   //correlated subquery
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
    }    


    private function uploadImage($userId,$url,$type = self::TYPE_PROFILE)
    {    
        $stream     =   imagecreatefromstring(file_get_contents($url));
        $fileName   =   md5($userId).base_convert($userId, 10, 36);
        if ($type === self::TYPE_PROFILE){
            $localFile  =   Yii::getAlias("@pictures/{$fileName}.jpg");
            $remoteFile =   Yii::getAlias("@ftp/p/{$fileName}.jpg");
        } else {
            $localFile  =   Yii::getAlias("@covers/{$fileName}.jpg");
            $remoteFile =   Yii::getAlias("@ftp/c/{$fileName}.jpg");
        }
        imagejpeg($stream,$localFile,100);
        imagedestroy($stream);
        $this->setStandardImageSize($localFile,$type);
        $content = file_get_contents($localFile);
        unlink($localFile);
        Yii::$app->ftpFs->put($remoteFile, $content);
    }    
    
    private function setStandardImageSize($image,$type)
    {
        
    }
}