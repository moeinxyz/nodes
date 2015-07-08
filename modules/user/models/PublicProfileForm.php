<?php
namespace app\modules\user\models;

use yii\base\Model;
use app\modules\user\models\User;
use Yii;
use app\modules\user\Module;
use yii\web\UploadedFile;
use app\gearworker\SyncImage;
use filsh\yii2\gearman\JobWorkload;
//@todo this part is so ugly,so rewrite code again
/**
 * @property string $name
 * @property string $tagline
 * @property UploadedFile $profilePicture
 * @property UploadedFile $coverPicture
 */
class PublicProfileForm extends Model{
    public $name;
    public $tagline;
    
    // status
    public $profileStatus;
    public $coverStatus;
    
    // to upload file
    public $profilePicture;
    public $coverPicture;

    // show radio button or not
    public $activeProfilePictureStatus;
    public $activeCoverPictureStatus;

    public $_user;


    public function rules()
    {
        return [
            [['name'], 'string', 'max' => 128],
            [['tagline'], 'string', 'max' => 256],
            [['profileStatus'],'in','range'=>[User::PIC_GRAVATAR,User::PIC_UPLOADED]],
            [['coverStatus'],'in','range'=>[User::COVER_NOCOVER,User::COVER_UPLOADED]],
            [['profilePicture'],'image','skipOnEmpty'=>TRUE,'extensions'=>['jpg','png','jpeg','gif'],'mimeTypes'=>['image/png','image/gif','image/jpeg','image/jpg','image/pjpeg'],'maxSize'=>2097152,'minWidth'=>100,'minHeight'=>100],
            [['coverPicture'],'image','skipOnEmpty'=>TRUE,'extensions'=>['jpg','png','jpeg','gif'],'mimeTypes'=>['image/png','image/gif','image/jpeg','image/jpg','image/pjpeg'],'maxSize'=>3145728,'minWidth'=>300,'minHeight'=>100],
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
            'profileStatus'     =>  Module::t('user', 'publicProfileForm.attr.profileStatus'),
            'coverStatus'       =>  Module::t('user', 'publicProfileForm.attr.coverStatus')
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
        $user                   =   $this->getUser();
        $user->name             =   $this->name;
        $user->tagline          =   $this->tagline;
        
        if ($this->activeProfilePictureStatus){
            $user->profile_pic      =   $this->profileStatus;    
        }
        
        if ($this->activeCoverPictureStatus){
            $user->profile_cover    =   $this->coverStatus;    
        }
        
        $this->syncPhotos($user);
        return $user->save();
    }
    
    
    private function syncPhotos()
    {
        $user   = $this->getUser();
        $id = md5(Yii::$app->user->id).base_convert(Yii::$app->user->id, 10, 36);
        if ($this->profilePicture instanceof UploadedFile){
            try {
                Yii::$app->gearman->getDispatcher()->background('SyncImage', new JobWorkload([
                    'params' => [
                        'userId'    =>  $user->getPrimaryKey(),
                        'type'      =>  SyncImage::TYPE_PROFILE,
                        'image'     =>  Yii::$app->urlManager->createAbsoluteUrl(Yii::getAlias("@webTempPicturesFolder/{$id}.{$this->profilePicture->getExtension()}"))
                    ]
                ]));
                $user->profile_pic                  =   User::PIC_UPLOADED;
                $user->uploaded_profile_pic         =   User::UPLOADED_PIC_YES;
                $this->profileStatus                =   User::PIC_UPLOADED;
                $this->activeProfilePictureStatus   =   true;
            } catch (\Sinergi\Gearman\Exception $ex) {
                $user->profile_pic          =   $user->getOldAttribute('profile_pic');
                $user->uploaded_profile_pic =   $user->getOldAttribute('uploaded_profile_pic');
            } catch (\Exception $ex){
                $user->profile_pic          =   $user->getOldAttribute('profile_pic');
                $user->uploaded_profile_pic =   $user->getOldAttribute('uploaded_profile_pic');
            }            
        }

        if ($this->coverPicture instanceof UploadedFile){
            try {
                Yii::$app->gearman->getDispatcher()->background('SyncImage', new JobWorkload([
                    'params' => [
                        'userId'    =>  $user->getPrimaryKey(),
                        'type'      =>  SyncImage::TYPE_COVER,
                        'image'     =>  Yii::$app->urlManager->createAbsoluteUrl(Yii::getAlias("@webTempCoversFolder/{$id}.{$this->coverPicture->getExtension()}"))
                    ]
                ]));
                $user->profile_cover            =   User::COVER_UPLOADED;
                $user->uploaded_cover_pic       =   User::UPLOADED_PIC_YES;
                $this->coverStatus              =   User::COVER_UPLOADED;
                $this->activeCoverPictureStatus =   true;
            } catch (\Sinergi\Gearman\Exception $ex) {
                $user->profile_cover        =   $user->getOldAttribute('profile_cover');
                $user->uploaded_cover_pic   =   $user->getOldAttribute('uploaded_cover_pic');
            } catch (\Exception $ex){
                $user->profile_cover        =   $user->getOldAttribute('profile_cover');
                $user->uploaded_cover_pic   =   $user->getOldAttribute('uploaded_cover_pic');
            }            
        }        
        return $user;
    }
    
    public static function getCoverPictureStatus()
    {
        return [
            User::COVER_NOCOVER     =>  Module::t('user', 'publicProfileForm.cover.noCover'),
            User::COVER_UPLOADED    =>  Module::t('user', 'publicProfileForm.cover.uploadedCover')
        ];
    }
    
    public static function getProfilePictureStatus()
    {
        return [
            User::PIC_GRAVATAR      =>  Module::t('user', 'publicProfileForm.profile.gravatar'),
            User::PIC_UPLOADED      =>  Module::t('user', 'publicProfileForm.profile.uploadedProfilePic'),
        ];
    }
}