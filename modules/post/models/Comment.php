<?php

namespace app\modules\post\models;

use Yii;
use app\modules\post\models\Post;
use app\modules\user\models\User;

/**
 * This is the model class for table "{{%comment}}".
 *
 * @property string $id
 * @property integer $user_id
 * @property string $post_id
 * @property string $text
 * @property string $status
 * @property string $created_at
 *
 * @property Post $post
 * @property User $user
 */
class Comment extends \yii\db\ActiveRecord
{
    
    const STATUS_PUBLISH    =   'PUBLISH';
    const STATUS_TRASH      =   'TRASH';
    const STATUS_ABUSE      =   'ABUSE';
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%comment}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'post_id', 'text'], 'required'],
            [['user_id', 'post_id'], 'integer'],
            [['text', 'status'], 'string'],
            [['created_at'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
//            'id' => Yii::t('comment', 'ID'),
//            'user_id' => Yii::t('comment', 'User ID'),
//            'post_id' => Yii::t('comment', 'Post ID'),
//            'text' => Yii::t('comment', 'Text'),
//            'status' => Yii::t('comment', 'Status'),
//            'created_at' => Yii::t('comment', 'Created At'),
        ];
    }

    public function beforeValidate() {
        if (parent::beforeValidate()){
            $this->status       =   self::STATUS_PUBLISH;
            $this->created_at   =   new \yii\db\Expression('NOW()');
            return TRUE;
        }
        return FALSE;
    }

    /**
     * 
     * @param integer $postId
     * @return array
     */
    public static function getCommentsOfPost($postId)
    {
        return Comment::find()->where('post_id=:post_id AND status=:status',[
            ':post_id'  =>  $postId,
            ':status'   =>  self::STATUS_PUBLISH
        ])->orderBy("created_at ASC")->all();
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
}
