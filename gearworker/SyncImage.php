<?php
namespace app\gearworker;

use Yii;
use filsh\yii2\gearman\JobBase;
use yii\imagine\Image;
use Imagine\Image\Box;
use Imagine\Image\Point;
//use app\modules\user\models\User;
class SyncImage extends JobBase
{
    const TYPE_COVER    =   'COVER';
    const TYPE_PROFILE  =   'PROFILE';
    
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
            $remoteFile =   Yii::getAlias("@ftpPictures/{$fileName}.jpg");
        } else {
            $localFile  =   Yii::getAlias("@covers/{$fileName}.jpg");
            $remoteFile =   Yii::getAlias("@ftpCovers/{$fileName}.jpg");
        }
        imagejpeg($stream,$localFile,100);
        imagedestroy($stream);
        $this->setStandardImageSize($localFile,$type);
        $content = file_get_contents($localFile);
        Yii::$app->ftpFs->put($remoteFile, $content);
        unlink($localFile);        
    }    
    
    private function setStandardImageSize($image,$type)
    {
        if ($type === self::TYPE_PROFILE){
            $this->setStandardProfileImageSize($image);
        } else {
            $this->setStandardCoverImageSize($image);
        }
    }
    
    private function setStandardProfileImageSize($file)
    {
        $image          =   Image::getImagine()->open($file);
        $width          =   $image->getSize()->getWidth();
        $height         =   $image->getSize()->getHeight();
        if ($width > $height) {
            $start = new Point(($width - $height) / 2,0);
            $size  = new Box($height,$height);
        } else {
            $start = new Point(0,($height - $width) / 2);
            $size  = new Box($width,$width);
        }
        
        $image->crop($start, $size)
                ->resize(new Box(200, 200))
                ->save($file);
    }
    
    private function setStandardCoverImageSize($file)
    {
        $image          =   Image::getImagine()->open($file);
        $width          =   $image->getSize()->getWidth();
        $height         =   $image->getSize()->getHeight();
        
        if (($width / 1400) > ($height / 600)) {
            $newWidth   =   ($height / 600) * 1400;
            $image      =   $image->crop(new Point(($width - $newWidth) / 2,0), new Box($newWidth, $height));
        } else {
            $newHeight  =   ($width / 1400) * 600;
            $image      =   $image->crop(new Point(0,($height - $newHeight) / 2), new Box($width, $newHeight));
        }
        
        if ($image->getSize()->getWidth() > 1400)
        {
            $image = $image->resize(new Box(1400, 600));
        }
        
        $image->save($file,['quality'=>80]);
    }
}