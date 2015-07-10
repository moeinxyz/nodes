<?php

namespace app\modules\user\models;

use Yii;
use \yii\base\NotSupportedException;
use \yii\db\Expression;
use app\modules\user\models\Attributes;
use yii\helpers\HtmlPurifier;
use app\modules\user\models\User;
use app\modules\user\Module;
use app\gearworker\SyncImage;
use filsh\yii2\gearman\JobWorkload;
/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property string $username
 * @property string $email
 * @property string $password
 * @property string $auth_key
 * @property string $created_at
 * @property string $type
 * @property string $status
 * @property string $name
 * @property string $tagline
 * @property string $profile_pic
 * @property string $profile_cover
 * @property string $uploaded_profile_pic
 * @property string $uploaded_cover_pic
 * @property string $content_activity
 * @property string $publisher_activity
 * @property string $social_activity
 * @property string $reading_list
 * @property Integer $posts_count
 * @property Integer $recommended_count
 * @property Integer $following_count
 * @property Integer $followers_count
 * @property Integer $notifications_count
 * @property string  $last_post_suggestion
 * @property string  $last_digest_mail
 * 
 * @property string $reCaptcha
 * @property mixed $links
 * 
 * @property Following[] $followings
 * @property Post[] $posts
 * @property Token[] $tokens
 * @property Url[] $urls
 */
class User extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{
    const STATUS_ACTIVE                 =   'ACTIVE';
    const STATUS_DEACTIVE               =   'DEACTIVE';
    const STATUS_BLOCK                  =   'BLOCK';    
    
    const TYPE_USER                     =   'USER';
    const TYPE_ADMIN                    =   'ADMIN';
    
    const PIC_GRAVATAR                  =   'GRAVATAR';
    const PIC_UPLOADED                  =   'UPLOADED';
    
    const COVER_NOCOVER                 =   'NOCOVER';
    const COVER_UPLOADED                =   'UPLOADED';
    
    
    // so dummy,change it ASAP
    const UPLOADED_PIC_YES              =   'YES';
    const UPLOADED_PIC_NO               =   'NO';
    
    const ACTIVITY_SETTING_FULL         =   'FULL';
    const ACTIVITY_SETTING_DIGEST       =   'DIGEST';
    const ACTIVITY_SETTING_OFF          =   'OFF';
    
    const READING_LIST_OFF              =   'OFF';
    const READING_LIST_WEEKLY           =   'WEEKLY';    
    const READING_LIST_DAILY            =   'DAILY';
    
    public $reCaptcha;
    public $links;


    public $clearPassword;
    public $pictureUrl;
    public $coverUrl;


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return Yii::$app->getModule('user')->userTable;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'email', 'auth_key'], 'required'],
            [['password'], 'required','on'=>'join'],
            [['email'],'email'],
            [['created_at'], 'safe'],
            [['type', 'status'], 'string'],
            [['username'], 'string', 'max' => 64],
            [['username'],'match','pattern' => '/^[A-Za-z0-9_]+$/u'],
            [['email', 'name'], 'string', 'max' => 128],
            [['password'], 'string', 'min' => 8],
            [['tagline'], 'string', 'max' => 256],
            [['username','email'], 'unique'],
            [['pictureUrl','coverUrl'],'safe','on'=>'oauth_signup'],
//            [['reCaptcha'], \himiklab\yii2\recaptcha\ReCaptchaValidator::className(), 'secret' => Yii::$app->reCaptcha->secret,'on'=>'join'],
            [['content_activity','publisher_activity','social_activity'],'in','range'=>[self::ACTIVITY_SETTING_DIGEST,self::ACTIVITY_SETTING_FULL,self::ACTIVITY_SETTING_OFF]],
            [['reading_list'],'in','range'=>[self::READING_LIST_DAILY,self::READING_LIST_OFF,self::READING_LIST_WEEKLY]],
            [['type'],'in','range'=>[self::TYPE_ADMIN,self::TYPE_USER]],
            [['profile_pic'],'in',  'range'=>[self::PIC_GRAVATAR,self::PIC_UPLOADED]],
            [['profile_cover'],'in','range'=>[self::COVER_NOCOVER,self::COVER_UPLOADED]],
            [['uploaded_profile_pic','uploaded_cover_pic'],'in','range'=>[self::UPLOADED_PIC_NO,self::UPLOADED_PIC_YES]]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'username'      => Module::t('user', 'user.attr.username'),
            'email'         => Module::t('user', 'user.attr.email'),
            'password'      => Module::t('user', 'user.attr.password'),
            'name'          => Module::t('user', 'user.attr.name'),
            'tagline'       => Module::t('user', 'user.attr.tagline'),
        ];
    }
    
    public function isNameAndTaglineSet()
    {
        if ($this->name != NULL && $this->tagline != NULL)
            return true;
        return false;
    }

    public function getUsername($perfix = true)
    {
        if ($perfix)
            return '@'.$this->username;
        return $this->username;
    }

    public function getName()
    {
        if ($this->name){
            return $this->name;
        }
        return $this->getUsername();
    }
    
    public function getTagLine()
    {
        return $this->tagline;
    }

    public function getAuthKey() {
        return $this->auth_key;
    }

    public function getId() {
        return $this->getPrimaryKey();
    }

    public function getProfilePicture($size=200)
    {
        if ($this->profile_pic === self::PIC_UPLOADED){
            $userId     =   $this->getPrimaryKey();
            $image      =   md5($userId).base_convert($userId, 10, 36);
            return Yii::getAlias("@ppicBaseUrl/{$image}.jpg");
        }
        $hash      =   md5( strtolower( trim( $this->email ) ) );
        return Yii::getAlias("@gravatar/{$hash}/?&s={$size}");
    }    

    public function getCoverPicture($size = '800x350')
    {
        if ($this->profile_cover === self::COVER_UPLOADED){
            $userId     =   $this->getPrimaryKey();
            $image      =   md5($userId).base_convert($userId, 10, 36);
            return Yii::getAlias("@cpicBaseUrl/{$image}.jpg");
        }
        return Yii::getAlias("@placeHold/{$size}/EFEEEF/AAAAAA&text=No+Cover+Photo");
    }        
    
    public function validateAuthKey($authKey) {
        return $this->getAuthKey() === $authKey;
    }

    public static function findIdentity($id) {
        return static::findOne($id);
    }
    
    public static function findIdentityByAccessToken($token, $type = null) {
        throw new NotSupportedException("Accsess Token doesn't support");
    }
    
    public static function findIdentityByEmail($email){
        return static::findOne(['email'=>$email]);
    }

    public static function findIdentityByUsername($username){
        return static::findOne(['username'=>$username]);
    }
    
    public function beforeValidate() {
        if (parent::beforeValidate()) {
            if ($this->isNewRecord) {
                $this->auth_key             =   Yii::$app->getSecurity()->generateRandomString();
                $this->created_at           =   new Expression('NOW()');
                $this->last_post_suggestion =   new Expression('NOW()');
                $this->last_digest_mail     =   new Expression('NOW()');
                $this->type                 =   self::TYPE_USER;
                $this->content_activity     =   self::ACTIVITY_SETTING_DIGEST;
                $this->publisher_activity   =   self::ACTIVITY_SETTING_DIGEST;
                $this->social_activity      =   self::ACTIVITY_SETTING_DIGEST;
                $this->reading_list         =   self::READING_LIST_DAILY;
                if ($this->getScenario() === 'oauth_signup'){
                    // generate username for oauth user
                    if (User::findOne(['username'=>  $this->username]) !== NULL){
                        $this->username = $this->email;
                        while(User::findOne(['username'=>  $this->username]) !== NULL){
                            $this->username = Yii::$app->security->generateRandomString();
                        }
                    }
                }
            }
            $this->name     = HtmlPurifier::process($this->name);
            $this->tagline  = HtmlPurifier::process($this->tagline);
            return true;
        }
        return false;        
    }
    
    public function beforeSave($insert) {
        if (parent::beforeSave($insert)){
            if ($this->getScenario() === 'join'){
                $this->clearPassword    = $this->password;
                $this->password         = Yii::$app->security->generatePasswordHash($this->password);
            }            
            return true;
        }
        return false;
    }

    public function afterSave($insert, $changedAttributes) {
        parent::afterSave($insert, $changedAttributes);
        
        if ($this->getScenario() === 'oauth_signup'){
            foreach ($this->links as $link){
                $model          =   new Url();
                $model->type    =   $link[0];
                $model->url     =   $link[1];
                $model->user_id =   $this->getPrimaryKey();
                $model->save();
            }
            $this->syncPhotos();
        } else if ($this->getScenario() === 'join'){
            $this->password = $this->clearPassword;
        }
    }

    /**
     * 
     * @param Attributes $attributes
     * @return User
     */
    public static function oauthLogin(Attributes $attributes){
        $model  =   self::findIdentityByEmail($attributes->email);
        if ($model === NULL){
            $model              =   new User;
            
            $model->scenario    =   'oauth_signup';
            $model->email       =   $attributes->email;
            $model->username    =   $attributes->username;
            $model->name        =   $attributes->name;
            $model->tagline     =   $attributes->tagline;
            $model->links       =   $attributes->getUrls();
            $model->pictureUrl  =   $attributes['profile_pic'];
            $model->coverUrl    =   $attributes['profile_cover'];
            $model->status      =   self::STATUS_ACTIVE;
            $model->save();
        }
        $model->activeAccount();
        return $model;
    }
    
    /**
     * Validates password
     *
     * @param  string  $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return $this->password != NULL && Yii::$app->getSecurity()->validatePassword($password, $this->password);
    }    
    
    public function activeAccount()
    {
        if ($this->status === self::STATUS_DEACTIVE)
        {
            $this->status = self::STATUS_ACTIVE;
            return $this->save();
        }
        return false;
    }

    public function getSetting()
    {
        return [
            'content_activity'      => $this->content_activity,
            'publisher_activity'    => $this->publisher_activity,
            'social_activity'       => $this->social_activity,
            'reading_list'          => $this->reading_list
        ];
    }

    /**
     * 
     * @param Attributes $attributes
     */
    private function syncPhotos()
    {
        $this->profile_pic  =   self::PIC_GRAVATAR;
        $this->profile_cover=   self::COVER_NOCOVER;
        $connection         =   Yii::$app->db;
        if ($this->pictureUrl != NULL){
            try {
                Yii::$app->gearman->getDispatcher()->background('syncImageByOAuth', new JobWorkload([
                    'params' => [
                        'userId'    =>  $this->getPrimaryKey(),
                        'type'      =>  SyncImage::TYPE_PROFILE,
                        'image'     =>  $this->pictureUrl
                    ]
                ]));
                $this->profile_pic      =   self::PIC_UPLOADED;
                // avoid calling after save because of loop
                $connection->createCommand()
                        ->update(User::tableName(), ['profile_pic'=>self::PIC_UPLOADED],'id=:id')
                        ->bindValue(':id', $this->getPrimaryKey())
                        ->execute();
            } catch (\Sinergi\Gearman\Exception $ex) {
                $this->profile_pic  =   self::PIC_GRAVATAR;
            } catch (\Exception $ex){
                $this->profile_pic  =   self::PIC_GRAVATAR;
            }
        }
        
        if ($this->coverUrl != NULL){
            try {
                Yii::$app->gearman->getDispatcher()->background('syncImageByOAuth', new JobWorkload([
                    'params' => [
                        'userId'    =>  $this->getPrimaryKey(),
                        'type'      =>  SyncImage::TYPE_COVER,
                        'image'     =>  $this->coverUrl
                    ]
                ]));
                $this->profile_cover    =   self::COVER_UPLOADED;
                // avoid calling after save because of loop
                $connection->createCommand()
                        ->update(User::tableName(), ['profile_cover'=>self::COVER_UPLOADED],'id=:id')
                        ->bindValue(':id', $this->getPrimaryKey())
                        ->execute();
            } catch (\Sinergi\Gearman\Exception $ex) {
                $this->profile_pic  =   self::PIC_GRAVATAR;
            } catch (\Exception $ex){
                $this->profile_pic  =   self::PIC_GRAVATAR;
            }
        }
    }

    
   /**
    * @return \yii\db\ActiveQuery
    */
   public function getFollowings()
   {
        return $this->hasMany(Following::className(), ['followed_user_id' => 'id']);    
   }    
    
   /**
    * @return \yii\db\ActiveQuery
    */
   public function getPosts()
   {
       return $this->hasMany(Post::className(), ['user_id' => 'id']);
   }
   
   /**
    * @return \yii\db\ActiveQuery
    */
   public function getTokens()
   {
       return $this->hasMany(Token::className(), ['user_id' => 'id']);
   }
   
   /**
    * @return \yii\db\ActiveQuery
    */
   public function getUrls()
   {
       return $this->hasMany(Url::className(), ['user_id' => 'id']);
   }
   
   public function getActiveUrls()
   {
       return $this->getUrls()->where('status=:status',['status'=>  Url::STATUS_ACTIVE])->all();
   }
}
