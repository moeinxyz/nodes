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

class GuestPost extends JobBase
{
    public function execute(\GearmanJob $job = null)
    {
        $params     =   unserialize($job->workload())->getParams();
        $this->uploadImage($params['userId'], $params['image'],$params['type']);    
    }
    
    private function getRanking()
    {
        
    }

    private function getUnreadedFriendsPost($userId)
    {
        $query  =   new Query;
        $query->select('id, comments_count, published_at')
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
        $sql    =   'SELECT id, user_id, comments_count, published_at FROM :post_table '
                .       'LEFT JOIN :following_table '
                .           'ON :following_table.user_id = :user_id'
                .       'LEFT JOIN :recommended_post '
                .           'ON :post_table.user_id = :recommended_post.followed_user_id '
                .       'WHERE :post_table.status = :status AND '
                .       ':post_table.user_id != :user_id AND '                
                .       ':following_table.user_id = :user_id AND '
                .       ':post_table.published_at > DATE_SUB(now(), INTERVAL 100 DAY) AND'
                .       ':post_table.id NOT IN '                
                .       '(SELECT DISTINCT post_id FROM :user_read_table WHERE user_id = :user_id) '
                .       'ORDER BY :post_table.published_at';        
        
        return R::getAll($sql,[
            ':post_table'       =>  App::config('table.post'),
            ':following_table'  =>  App::config('table.following'),
            ':user_read_table'  =>  App::config('table.user_read'),
            ':recommended_post' =>  App::config('table.recommended_post'),
            ':user_id'          =>  $userId,
            ':status'           =>  'PUBLISH'
        ]);        
    }
    
    private function getLastUnreadedPost($userId)
    {
        $sql    =   'SELECT id, user_id, comments_count, published_at,'
                .' ( SELECT COUNT(*) '   //correlated subquery
                . 'FROM :user_read_table '
                . ' WHERE :post_table.id = :user_read_table.post_id'
                . ') AS user_read_count '
                .' ( SELECT COUNT(*) '   //correlated subquery
                . 'FROM :guest_read_table '
                . ' WHERE :post_table.id = :guest_read_table.post_id'
                . ') AS guest_read_count '                
                . ' FROM :post_table '
                .       'WHERE :post_table.status = :status AND '
                .       ':post_table.user_id != :user_id AND '                
                .       ':post_table.published_at > DATE_SUB(now(), INTERVAL 25 DAY) '
                .       ':post_table.id NOT IN '                
                .       '(SELECT DISTINCT post_id FROM :user_read_table WHERE user_id = :user_id) '
                .       'ORDER BY :post_table.published_at';        
        
        return R::getAll($sql,[
            ':post_table'       =>  App::config('table.post'),
            ':user_read_table'  =>  App::config('table.user_read'),
            ':guest_read_table' =>  App::config('table.guest_read'),
            ':user_id'          =>  $userId,
            ':status'           =>  'PUBLISH'            
        ]);
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