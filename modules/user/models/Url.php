<?php

namespace app\modules\user\models;

use Yii;
use \yii\db\Expression;
use app\modules\user\Module;

/**
 * This is the model class for table "{{%url}}".
 *
 * @property string $id
 * @property integer $user_id
 * @property string $url
 * @property string $created_at
 * @property string $type
 * @property string $status
 *
 * @property User $user
 */
class Url extends \yii\db\ActiveRecord
{
    
    const TYPE_SITE             =   'SITE';    
    const TYPE_TWITTER          =   'TWITTER';
    const TYPE_FACEBOOK         =   'FACEBOOK';
    const TYPE_GOOGLE_PLUS      =   'GOOGLE_PLUS';
    const TYPE_LINKEDIN         =   'LINKEDIN';
    const TYPE_GITHUB           =   'GITHUB';
    const TYPE_INSTAGRAM        =   'INSTAGRAM';
    const TYPE_YOUTUBE          =   'YOUTUBE';
    const TYPE_BITBUCKET        =   'BITBUCKET';
    const TYPE_TUMBLER          =   'TUMBLER';
    const TYPE_FLICKR           =   'FLICKR';
    const TYPE_VK               =   'VK';
    const TYPE_ASKFM            =   'ASKFM';
    const TYPE_PNTEREST         =   'PNTEREST';
    //@todo add to mysql
    const TYPE_STACKOVERFLOW    =   'STACKOVERFLOW';
    
    const STATUS_ACTIVE         =   'ACTIVE';
    const STATUS_DEACTIVE       =   'DEACTIVE';
  

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return Yii::$app->getModule('user')->urlTable;
    }

    /**
     * @inheritdoc
     */
    
    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['url'],'required','except'=>'update'],
            [['user_id'], 'integer'],
            [['type', 'status'], 'string'],
            [['type'],'in','range'  =>  [
                self::TYPE_ASKFM,
                self::TYPE_BITBUCKET,
                self::TYPE_FACEBOOK,
                self::TYPE_FLICKR,
                self::TYPE_GITHUB,
                self::TYPE_GOOGLE_PLUS,
                self::TYPE_GOOGLE_PLUS,
                self::TYPE_INSTAGRAM,
                self::TYPE_LINKEDIN,
                self::TYPE_PNTEREST,
                self::TYPE_SITE,
                self::TYPE_STACKOVERFLOW,
                self::TYPE_TUMBLER,
                self::TYPE_TWITTER,
                self::TYPE_VK,
                self::TYPE_YOUTUBE
            ]],
            [['status'],'in','range'    =>  [self::STATUS_ACTIVE,  self::STATUS_DEACTIVE]],
            [['url'], 'string', 'max' => 255],
            [['url'],'url'],
            [['url'],'validateUniqueUrl'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'            => Yii::t('app', 'ID'),
            'user_id'       => Yii::t('app', 'User ID'),
            'url'           => Yii::t('app', 'Url'),
            'created_at'    => Yii::t('app', 'Created At'),
            'type'          => Yii::t('app', 'Type'),
            'status'        => Yii::t('app', 'Status'),
        ];
    }

    public function validateUniqueUrl($attribute,$params)
    {
        if (!$this->hasErrors()){
            $url = Url::findOne(['url'=>$this->url,'user_id'=>Yii::$app->user->id]);
            if ($url && $this->id != $url->id){
                $this->addError($attribute, Module::t('user','url.vld.unUniqueUrl',['url'=>  $this->url]));
            }
        }
    }    
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
    
    public function beforeValidate() {
        if (parent::beforeValidate()){
            if ($this->isNewRecord){
                $this->created_at   =   new Expression('NOW()');
                if ($this->status === NULL){
                    $this->status       =   self::STATUS_ACTIVE;    
                }
                if ($this->user_id === NULL){
                    $this->user_id      =   Yii::$app->user->getId();
                }
            }
            return TRUE;
        }
        return FALSE;
    }
    public function beforeSave($insert) {
        if (parent::beforeSave($insert)){
            $this->type = $this->getServiceByUrl($this->url);
            return TRUE;
        }
        return FALSE;
    }

        public function search()
    {
        $query = Url::find()->where('user_id=:user_id',[':user_id'=>Yii::$app->user->id]);

        return new \yii\data\ActiveDataProvider([
            'query' =>  $query
        ]);
    }


    public static function getServiceByUrl($url){
        $params     =   parse_url($url);
        $hosts_name =   explode(".", $params['host']);
        $tld        =   $hosts_name[count($hosts_name)-2] . "." . $hosts_name[count($hosts_name)-1];
        if ($tld === 'facebook.com'){
            return self::TYPE_FACEBOOK;
        } else if ($tld === 'twitter.com'){
            return self::TYPE_TWITTER;
        } else if ($tld === 'github.com'){
            return self::TYPE_GITHUB;
        } else if ($tld === 'linkedin.com'){
            return self::TYPE_LINKEDIN;
        } else if ($tld === 'google.com' && ($params['host'] === 'plus.google.com' || $params['host'] === 'www.plus.google.com')){
            return self::TYPE_GOOGLE_PLUS;
        } else if ($tld === 'youtube.com'){
            return self::TYPE_YOUTUBE;
        } else if ($tld === 'instagram.com'){
            return self::TYPE_INSTAGRAM;
        } else {
            return self::TYPE_SITE;
        }
    }    
    
    public static function getStatusList()
    {
        return [
            self::STATUS_ACTIVE     =>  Module::t('url','status.active'),
            self::STATUS_DEACTIVE   =>  Module::t('url','status.deactive'),
        ];
    }
}
