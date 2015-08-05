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
 * @property strint $pure_text
 * @property string $status
 * @property string $post_author_seen
 * @property string $created_at
 *
 * @property Post $post
 * @property User $user
 */
class Comment extends \yii\db\ActiveRecord
{
    
    const STATUS_PUBLISH                    =   'PUBLISH';
    const STATUS_TRASH                      =   'TRASH';
    const STATUS_USER_DELETE                =   'USER_DELETE';
    
    const POST_AUTHOR_SEEN_SEEN             =   'SEEN';
    const POST_AUTHOR_SEEN_NOT_SEEN         =   'NOT_SEEN';
    
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
            [['text', 'pure_text', 'status','post_author_seen'], 'string'],
            [['text','pure_text'],'filter','filter'=>'trim'],            
            [['status'],'in','range'=>[self::STATUS_PUBLISH,self::STATUS_TRASH,self::STATUS_USER_DELETE]],
            [['post_author_seen'],'in','range'=>[self::POST_AUTHOR_SEEN_NOT_SEEN,self::POST_AUTHOR_SEEN_SEEN]],
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
                $this->pure_text    =   HtmlPurifier::process(strip_tags($this->text));
            }
            if ($this->status === NULL){
                $this->status       =   self::STATUS_PUBLISH;
            }
            if ($this->post_author_seen){
                $this->post_author_seen       =   self::POST_AUTHOR_SEEN_NOT_SEEN;
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
    
    public static function countNotifications()
    {
        return self::find()
                ->leftJoin(Post::tableName(), Post::tableName().'.id = '.self::tableName().'.post_id')
                ->where(Post::tableName().'.user_id=:userId',[':userId'=>Yii::$app->user->getId()])
                ->andWhere(Post::tableName().'.status=:postStatus',[':postStatus'=>Post::STATUS_PUBLISH])
                ->andWhere(self::tableName().'.status=:commentStatus',[':commentStatus'=>self::STATUS_PUBLISH])
                ->andWhere(self::tableName().'.post_author_seen=:postAuthorSeen',[':postAuthorSeen'=>self::POST_AUTHOR_SEEN_NOT_SEEN])
                ->andWhere(self::tableName().'.user_id!=:userId',[':userId'=>Yii::$app->user->getId()])
                ->count();
    }
    
    public static function getLastComments($limit = 8)
    {
        return self::find()
                ->leftJoin(Post::tableName(), Post::tableName().'.id = '.self::tableName().'.post_id')
                ->where(Post::tableName().'.user_id=:userId',[':userId'=>Yii::$app->user->getId()])
                ->andWhere(Post::tableName().'.status=:postStatus',[':postStatus'=>Post::STATUS_PUBLISH])
                ->andWhere(self::tableName().'.status=:commentStatus',[':commentStatus'=>self::STATUS_PUBLISH])                
                ->andWhere(self::tableName().'.user_id!=:userId',[':userId'=>Yii::$app->user->getId()])
                ->orderBy('CAST(post_author_seen AS CHAR),created_at DESC')
                ->limit($limit)
                ->all();
    }
    
    public static function setCommentsAsSeen($pid = null)
    {
        if ($pid === NULL)
        {
            $sql    =   'UPDATE '.self::tableName().' As comment'
                    .   ' JOIN '.Post::tableName().' AS post'
                    .   ' ON comment.post_id = post.id'
                    .   ' SET comment.post_author_seen = :seenStatus'
                    .   ' WHERE post.user_id = :userId'
                    .   ' AND comment.post_author_seen = :notSeenStatus'
                    .   ' AND comment.status = :commentStatus'
                    .   ' AND post.status = :postStatus';                    
            
            \Yii::$app->db->createCommand($sql, [
                ':seenStatus'   =>  self::POST_AUTHOR_SEEN_SEEN,
                ':notSeenStatus'=>  self::POST_AUTHOR_SEEN_NOT_SEEN,
                ':commentStatus'=>  self::STATUS_PUBLISH,                
                ':userId'       =>  Yii::$app->user->getId(),
                ':postStatus'   =>  Post::STATUS_PUBLISH
            ])->execute();
        } else {
            Comment::updateAll(['post_author_seen'=>self::POST_AUTHOR_SEEN_SEEN],'post_id=:postId',[':postId'=>$pid]);
        }
    }
}

