<?php
namespace app\gearworker;

use Yii;
use filsh\yii2\gearman\JobBase;
use yii\imagine\Image;
use Imagine\Image\Box;
use Imagine\Image\Point;
use app\modules\post\models\Post;

class SyncPostCover extends JobBase
{    
    public function execute(\GearmanJob $job = null)
    {
        $params     =   unserialize($job->workload())->getParams();
        $this->uploadImage($params['postId'], $params['image']);    
    }


    private function uploadImage($postId,$url)
    {    
        $stream     =   imagecreatefromstring(file_get_contents($url));
        $fileName   =   Post::getCoverFileName($postId);
        $remoteFile =   Yii::getAlias("@ftpPostCovers/{$fileName}.jpg");
        
        $localFile  =   Yii::getAlias("@postcovers/{$fileName}.jpg");
        imagejpeg($stream,$localFile,100);
        imagedestroy($stream);
        
        // create thumbnail from background picture,don't change code order
        // background
        $this->setStandardImageSize($localFile);
        $content = file_get_contents($localFile);
        Yii::$app->ftpFs->put($remoteFile, $content);
        
        //thumbnail
        $this->setStandardImageSize($localFile,true);
        $content    =   file_get_contents($localFile);
        $remoteFile =   Yii::getAlias("@ftpPostCovers/{$fileName}-thumbnail.jpg");
        Yii::$app->ftpFs->put($remoteFile, $content);
        
        unlink($localFile);        
    }    
    
    private function setStandardImageSize($image,$thumbnail = false)
    {
        if ($thumbnail) {
            $this->setThumbnailCoverImageSize($image);
        } else {
            $this->setStandardCoverImageSize($image);
        }
    }
    
    private function setStandardCoverImageSize($file)
    {
        $image          =   Image::getImagine()->open($file);
        $width          =   $image->getSize()->getWidth();
        $height         =   $image->getSize()->getHeight();
        
        if (($width / 1920) > ($height / 1079)) {
            $newWidth   =   ($height / 1079) * 1920;
            $image      =   $image->crop(new Point(($width - $newWidth) / 2,0), new Box($newWidth, $height));
        } else {
            $newHeight  =   ($width / 1920) * 1079;
            $image      =   $image->crop(new Point(0,($height - $newHeight) / 2), new Box($width, $newHeight));
        }
        
        if ($image->getSize()->getWidth() > 1920)
        {
            $image = $image->resize(new Box(1920, 1079));
        }
        
        $image->save($file,['quality'=>80]);        
    }
    
    private function setThumbnailCoverImageSize($file)
    {
        Image::getImagine()->open($file)
                ->resize(new Box(500, 282))
                ->save($file);
    }
}