<?php

namespace app\modules\post\models;

use Yii;
use app\modules\user\models\User;
/**
 * This is the model class for table "{{%userrecommend}}".
 *
 * @property integer $user_id
 * @property string $post_id
 * @property string $created_at
 * @property string $predictionio_status
 *
 * @property Post $post
 * @property User $user
 */
class Userrecommend extends \yii\db\ActiveRecord
{
    const PREDICTION_STATUS_NEW  = "NEW";
    const PREDICTION_STATUS_SENT = "SENT";
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
            [['created_at', 'predictionio_status'], 'safe'],
            ['predictionio_status', 'in' => [self::PREDICTION_STATUS_NEW, self::PREDICTION_STATUS_SENT]]
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
                $this->predictionio_status          =   self::PREDICTION_STATUS_NEW;
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
    
    /**
     * 
     * @param integer $postId
     * @return integer
     */
    public static function countPostRecommends($postId)
    {
        return self::find()->leftJoin(User::tableName(),self::tableName().'.user_id='.User::tableName().'.id')
                ->where(self::tableName().'.post_id=:post_id AND '.User::tableName().'.status!=:status',['post_id'=>$postId,'status'=>  User::STATUS_BLOCK])->count();
    }
    
    /**
     * 
     * @param integer $postId
     * @param integer $limit
     * @param integer $exceptedUserId
     * @return array
     */
    public static function getPostRecommenders($postId,$limit = 5,$exceptedUserId = null)
    {
        //@todo show friends recommendation first,then top following users
        $query = User::find()->leftJoin(self::tableName(),self::tableName().'.user_id='.User::tableName().'.id');
        if ($exceptedUserId === NULL){
            $query = $query->where(self::tableName().'.post_id=:post_id AND '.User::tableName().'.status!=:status',['post_id'=>$postId,'status'=>User::STATUS_BLOCK]);
        } else {
            $query = $query->where(self::tableName().'.post_id=:post_id AND '.self::tableName().'.user_id!=:user_id AND '.User::tableName().'.status!=:status',['post_id'=>$postId,'user_id'=>$exceptedUserId,'status'=>User::STATUS_BLOCK]);
        }
        return $query->orderBy('created_at desc')->limit($limit)->all();
    }
}
