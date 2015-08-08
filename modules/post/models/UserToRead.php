<?php

namespace app\modules\post\models;

use Yii;

/**
 * This is the model class for table "{{%usertoread}}".
 *
 * @property string $id
 * @property integer $user_id
 * @property string $post_id
 * @property integer $score
 * @property integer $priority
 * @property string $notification_mail_status
 * @property string $created_at
 *
 * @property User $user
 * @property Post $post
 */
class UserToRead extends \yii\db\ActiveRecord
{
    const NOTIFICATION_MAIL_STATUS_SENT     =   'SENT';
    const NOTIFICATION_MAIL_STATUS_NOT_SEND =   'NOT_SEND';
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%usertoread}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'post_id', 'score'], 'required'],
            [['user_id', 'post_id', 'score','priority'], 'integer'],
            [['notification_mail_status'],'in','range'=>[self::NOTIFICATION_MAIL_STATUS_NOT_SEND,self::NOTIFICATION_MAIL_STATUS_SENT]],            
            [['created_at'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'post_id' => Yii::t('app', 'Post ID'),
            'score' => Yii::t('app', 'Score'),
            'priority' => Yii::t('app', 'Priority'),
            'created_at' => Yii::t('app', 'Created At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPost()
    {
        return $this->hasOne(Post::className(), ['id' => 'post_id']);
    }
    
    public function beforeValidate() {
        if (parent::beforeValidate()){
            if ($this->isNewRecord){
                if ($this->notification_mail_status === NULL){
                    $this->notification_mail_status =   self::NOTIFICATION_MAIL_STATUS_NOT_SEND;
                }            
            }
            return true;
        }
        return false;
    }
}
