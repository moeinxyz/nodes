<?php

namespace app\modules\post\models;

use Yii;

/**
 * This is the model class for table "{{%userrecommend}}".
 *
 * @property integer $user_id
 * @property string $post_id
 * @property string $created_at
 *
 * @property Post $post
 * @property User $user
 */
class Userrecommend extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%userrecommend}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'post_id'], 'required'],
            [['user_id', 'post_id'], 'integer'],
            [['created_at'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => Yii::t('recommend', 'User ID'),
            'post_id' => Yii::t('recommend', 'Post ID'),
            'created_at' => Yii::t('recommend', 'Created At'),
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
    
    public function beforeValidate() {
        if (parent::beforeValidate()){
            if ($this->isNewRecord){
                $this->user_id      =   Yii::$app->user->getId();
                $this->created_at   =   new \yii\db\Expression('NOW()');
            }
            return TRUE;
        }
        return FALSE;
    }
    
    /**
     * 
     * @param integer $postId
     * @return Userrecommend
     */
    public static function getPostRecommended($postId)
    {
        if (($model = self::findOne(['user_id'=>Yii::$app->user->getId(),'post_id'=>$postId])) != NULL){
            return $model;
        }
        return NULL;
    }
    
    public function afterSave($insert, $changedAttributes) {
        parent::afterSave($insert, $changedAttributes);
        $this->getUser()->one()->updateCounters(['recommended_count'=>1]);
    }
    

    public function afterDelete() {
        parent::afterDelete();
        $this->getUser()->one()->updateCounters(['recommended_count'=>-1]);
    }
}
