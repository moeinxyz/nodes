<?php
namespace app\modules\user\models;

use yii\base\Model;
use app\modules\user\models\User;
use Yii;
use app\modules\user\Module;
use yii\web\UploadedFile;
use app\gearworker\SyncImage;
use filsh\yii2\gearman\JobWorkload;
/**
 * @property string $name
 * @property string $tagline
 * @property UploadedFile $profilePicture
 * @property UploadedFile $coverPicture
 */
class PublicProfileForm extends Model{
    public $name;
    public $tagline;
    
    // to upload file
    public $profilePicture;
    public $coverPicture;

    public $_user;


    public function rules()
    {
        return [
            [['name'], 'string', 'max' => 128],
            [['tagline'], 'string', 'max' => 256],
            [['profilePicture'],'image','skipOnEmpty'=>TRUE,'extensions'=>['jpg','png','jpeg','gif'],'mimeTypes'=>['image/png','image/gif','image/jpeg','image/jpg','image/pjpeg'],'maxSize'=>1048576,'minWidth'=>100,'minHeight'=>100],
            [['coverPicture'],'image','skipOnEmpty'=>TRUE,'extensions'=>['jpg','png','jpeg','gif'],'mimeTypes'=>['image/png','image/gif','image/jpeg','image/jpg','image/pjpeg'],'maxSize'=>1536000,'minWidth'=>300,'minHeight'=>100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name'              =>  Module::t('user', 'user.attr.name'),
            'tagline'           =>  Module::t('user', 'user.attr.tagline'),
            'profilePicture'    =>  Module::t('user', 'publicProfileForm.attr.profilePicture'),
            'coverPicture'      =>  Module::t('user', 'publicProfileForm.attr.coverPicture'),
        ];
    }
    
    public function getUser()
    {
        if (!$this->_user){
            $this->_user    =   User::findOne(Yii::$app->user->id);
        }
        return $this->_user;
    }    
    
    public function update()
    {
        $user           =   $this->getUser();
        $user->name     =   $this->name;
        $user->tagline  =   $this->tagline;
        $user = $this->syncPhotos($user);
        return $user->save();
    }
    
    
    private function syncPhotos(User $user)
    {
        $id = md5(Yii::$app->user->id).base_convert(Yii::$app->user->id, 10, 36);
        if ($this->profilePicture instanceof UploadedFile){
            try {
                Yii::$app->gearman->getDispatcher()->background('SyncImage', new JobWorkload([
                    'params' => [
                        'userId'    =>  $this->getPrimaryKey(),
                        'type'      =>  SyncImage::TYPE_PROFILE,
                        'image'     =>  Yii::$app->urlManager->createAbsoluteUrl(Yii::getAlias("@tempFolder/pictures/{$id}.{$this->profilePicture->getExtension()}"))
                    ]
                ]));
                $user->profile_pic  =   User::PIC_UPLOADED;
            } catch (\Sinergi\Gearman\Exception $ex) {
                $user->profile_pic  = $user->getOldAttribute('profile_pic');
            } catch (\Exception $ex){
                $user->profile_pic  = $user->getOldAttribute('profile_pic');
            }            
        }

        if ($this->coverPicture instanceof UploadedFile){
            try {
                Yii::$app->gearman->getDispatcher()->background('SyncImage', new JobWorkload([
                    'params' => [
                        'userId'    =>  $this->getPrimaryKey(),
                        'type'      =>  SyncImage::TYPE_COVER,
                        'image'     =>  Yii::$app->urlManager->createAbsoluteUrl(Yii::getAlias("@tempFolder/covers/{$id}.{$this->coverPicture->getExtension()}"))
                    ]
                ]));
                $user->profile_cover    = User::COVER_UPLOADED;
            } catch (\Sinergi\Gearman\Exception $ex) {
                $user->profile_cover  = $user->getOldAttribute('profile_cover');
            } catch (\Exception $ex){
                $user->profile_cover  = $user->getOldAttribute('profile_cover');
            }            
        }        
        return $user;
    }
}