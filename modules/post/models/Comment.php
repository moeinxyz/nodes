<?php

namespace app\modules\post\models;

use Yii;
use app\modules\post\models\Post;
use app\modules\user\models\User;
use app\modules\post\Module;
use yii\helpers\HtmlPurifier;

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
    
    const STATUS_PUBLISH            =   'PUBLISH';
    const STATUS_TRASH              =   'TRASH';
    const STATUS_USER_DELETE        =   'USER_DELETE';
    
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
            'user_id'       => Module::t('comment', 'comment.attr.user_id'),
            'post_id'       => Module::t('comment', 'comment.attr.post_id'),
            'text'          => Module::t('comment', 'comment.attr.text'),
            'created_at'    => Module::t('comment', 'comment.attr.created_at'),
        ];
    }

    public function beforeValidate() {
        if (parent::beforeValidate()){
            if ($this->isNewRecord){
                $this->created_at   =   new \yii\db\Expression('NOW()');    
            }
            if ($this->status === NULL){
                $this->status       =   self::STATUS_PUBLISH;
            }
            $this->text = HtmlPurifier::process($this->text);
            return TRUE;
        }
        return FALSE;
    }

    /**
     * 
     * @param integer $postId
     * @return array
     */
    public static function getCommentsOfPost($postId,$timestamp = null)
    {
        $statement  =   'post_id=:post_id AND status=:status';
        $params     =   [
            ':post_id'  =>  $postId,
            ':status'   =>  self::STATUS_PUBLISH            
        ];
        if ($timestamp != NULL){
            $statement              .=  ' AND created_at > :timestamp';
            $params[':timestamp']   =   date('Y-m-d H:i:s',$timestamp);
        }
        return Comment::find()->where($statement,$params)->orderBy("created_at ASC")->all();
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
    
    public function toggleTrash()
    {
        if ($this->status === self::STATUS_PUBLISH){
            $this->status = self::STATUS_TRASH;
        } else {
            $this->status = self::STATUS_PUBLISH;
        }
    }
}
