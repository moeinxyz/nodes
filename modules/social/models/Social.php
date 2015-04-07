<?php

namespace app\modules\social\models;

use Yii;
use app\modules\social\Module;
/**
 * This is the model class for table "{{%social}}".
 *
 * @property string $id
 * @property string $type
 * @property string $media_id
 * @property string $name
 * @property string $url
 * @property string $token
 * @property string $status
 * @property string $auth
 * @property string $share
 * @property string $created_at
 * @property string $last_used
 * @property integer $user_id
 *
 * @property User $user
 */
class Social extends \yii\db\ActiveRecord
{
    
    const TYPE_FACEBOOK     =   'FACEBOOK';
    const TYPE_TWITTER      =   'TWITTER';
    const TYPE_LINKEDIN     =   'LINKEDIN';
    
    const AUTH_AUTH         =   'AUTH';
    const AUTH_DEATUH       =   'DEAUTH';
    
    const STATUS_ACTIVE     =   'ACTIVE';
    const STATUS_DEACTIVE   =   'DEACTIVE';
    
    const SHARE_POST        =   'POST';
    const SHARE_ALL         =   'ALL';
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%social}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'token', 'status', 'auth', 'share'], 'string'],
            [['media_id', 'token', 'user_id'], 'required'],
            [['created_at','last_used'], 'safe'],
            [['user_id'], 'integer'],
            [['media_id'], 'string', 'max' => 64]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'type'      =>  Module::t('social','attr.type'),
            'status'    =>  Module::t('social','attr.status'),
            'auth'      =>  Module::t('social','attr.auth'),
            'share'     =>  Module::t('social','attr.share'),
            'created_at'=>  Module::t('social','attr.created_at'),
            'name'      =>  Module::t('social','attr.name'),
        ];
    }

    public function beforeValidate() {
        if (parent::beforeValidate()){
            if ($this->isNewRecord){
                $this->created_at       =   New \yii\db\Expression('NOW()');
                $this->last_used        =   New \yii\db\Expression('NOW()');
            }
            if ($this->user_id === NULL){
                $this->user_id          =   Yii::$app->user->getId();    
            }
            if ($this->share === NULL){
                $this->share            =   self::SHARE_POST;
            }
            return TRUE;
        }
        return FALSE;
    }

        /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
