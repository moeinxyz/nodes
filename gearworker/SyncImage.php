<?php
namespace app\gearworker;

use Yii;
use filsh\yii2\gearman\JobBase;
use app\modules\user\models\User;
class SyncImage extends JobBase
{
    const TYPE_COVER    =   'COVER';
    const TYPE_PROFILE  =   'PROFILE';
//    const TYPE_CONTENT  =   'CONTENT';
    
    public function execute(\GearmanJob $job = null)
    {
        $params     =   unserialize($job->workload())->getParams();
        $this->uploadImage($params['userId'], $params['image'],$params['type']);    
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