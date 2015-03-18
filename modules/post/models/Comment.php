<?php

namespace app\modules\post\models;

use Yii;

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
            'id' => Yii::t('comment', 'ID'),
            'user_id' => Yii::t('comment', 'User ID'),
            'post_id' => Yii::t('comment', 'Post ID'),
            'text' => Yii::t('comment', 'Text'),
            'status' => Yii::t('comment', 'Status'),
            'created_at' => Yii::t('comment', 'Created At'),
        ];
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
