<?php

namespace app\modules\post\models;

use Yii;

/**
 * This is the model class for table "{{%abuse}}".
 *
 * @property string $id
 * @property integer $user_id
 * @property string $post_id
 * @property string $comment_id
 * @property string $status
 * @property string $created_at
 *
 * @property Comment $comment
 * @property Post $post
 * @property User $user
 */
class Abuse extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%abuse}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['user_id', 'post_id', 'comment_id'], 'integer'],
            [['status'], 'string'],
            [['created_at'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('abuse', 'ID'),
            'user_id' => Yii::t('abuse', 'User ID'),
            'post_id' => Yii::t('abuse', 'Post ID'),
            'comment_id' => Yii::t('abuse', 'Comment ID'),
            'status' => Yii::t('abuse', 'Status'),
            'created_at' => Yii::t('abuse', 'Created At'),
        ];
    }
    
    public function beforeValidate() {
        if (parent::beforeValidate()){
            $this->user_id  =   Yii::$app->user->getId();
            return true;
        }
        return false;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getComment()
    {
        return $this->hasOne(Comment::className(), ['id' => 'comment_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPost()
    {
        return $this->hasOne(Post::className(), ['id' => 'post_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
    
    public static function isCommentAbuseExist($commnetId)
    {
        if (Abuse::find()->where('comment_id=:comment_id AND user_id=:user_id',[
                'comment_id'    =>  $commnetId,
                'user_id'       =>  Yii::$app->user->getId()
            ])->count() >= 1)
            return TRUE;
        return FALSE;
    }
}
