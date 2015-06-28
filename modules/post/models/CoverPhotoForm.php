<?php

namespace app\modules\post\models;

use Yii;
use yii\base\Model;
use app\modules\post\models\Post;
use app\modules\post\Module;
use yii\web\UploadedFile;
use filsh\yii2\gearman\JobWorkload;
class CoverPhotoForm extends Model{
    private $_post;
    public $postId;
    public $coverStatus;
    public $coverImage;
    
    public function rules() {
        return [
          [['!postId'],'number'],
          [['!coverStatus'],'in','range'=>[Post::COVER_BYCOVER,Post::COVER_NOCOVER]],
          [['coverImage'],'image','skipOnEmpty'=>FALSE,'extensions'=>['jpg','png','jpeg','gif'],'mimeTypes'=>['image/png','image/gif','image/jpeg','image/jpg','image/pjpeg'],'maxSize'=>5242880,'minWidth'=>300,'minHeight'=>100],  
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'coverImage'       =>  Module::t('post', 'coverPhotoForm.attr.coverImage')
        ];
    }    
    
    private function getPost()
    {
        if (!isset($this->_post)){
            $this->_post    = Post::findOne($this->postId);
        }
        return $this->_post;
    }
    
    
    public function update()
    {
        if ($this->syncImage())
        {
            return $this->getPost()->save();    
        }
        return false;
    }
    
    
    private function syncImage()
    {
        $post   =   $this->getPost();
        $name   =   Post::getCoverFileName($this->postId);
        if ($this->coverImage instanceof UploadedFile){
            try {
                Yii::$app->gearman->getDispatcher()->background('SyncPostCover', new JobWorkload([
                    'params' => [
                        'postId'    =>  $this->postId,
                        'image'     =>  Yii::$app->urlManager->createAbsoluteUrl(Yii::getAlias("@webTempPostCoverFolder/{$name}.{$this->coverImage->getExtension()}"))
                    ]
                ]));
                $post->cover            =   Post::COVER_BYCOVER;
                $this->coverStatus      =   Post::COVER_BYCOVER;
                return true;
            } catch (\Sinergi\Gearman\Exception $ex) {
                $post->cover            =   $post->getOldAttribute('cover');
                $this->coverStatus      =   $post->getOldAttribute('cover');
                $this->addError('coverImage',  Module::t('post','coverPhotoForm.err.try_later'));
                return false;
            } catch (\Exception $ex){
                $post->cover            =   $post->getOldAttribute('cover');
                $this->coverStatus      =   $post->getOldAttribute('cover');
                $this->addError('coverImage',  Module::t('post','coverPhotoForm.err.try_later'));
                return false;
            }          
        }        
    }
    
}