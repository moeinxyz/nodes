<?php

namespace app\modules\user\models;

use Yii;
use \app\modules\user\models\User;
/**
 * This is the model class for table "{{%following}}".
 *
 * @property integer $user_id
 * @property integer $followed_user_id
 * @property string $status
 *
 * @property User $followedUser
 * @property User $user
 */
class Following extends \yii\db\ActiveRecord
{
    const STATUS_ACTIVE     = 'ACTIVE';
    const STATUS_DEACTIVE   = 'DEACTIVE';
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return Yii::$app->getModule('user')->followingTable;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'followed_user_id'], 'required'],
            [['user_id', 'followed_user_id'], 'integer'],
            
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [];
    }

    public static function getModelIfNotExist($followerId,$followedId)
    {
        $model = Following::findOne(['user_id'=>  $followerId, 'followed_user_id' => $followedId]);
        if ($model == NULL){
            $model = new Following;
            $model->user_id             =   $followerId;
            $model->followed_user_id    =   $followedId;
        }
        return $model;
    }

    //@todo rewrite it by mysql trigger
    public function toggleFollow()
    {
        if ($this->isNewRecord || $this->status == self::STATUS_DEACTIVE){
            $this->status   =   self::STATUS_ACTIVE;
            $number         =   1;
        } else {
            $this->status = self::STATUS_DEACTIVE;
            $number         =   -1; 
        }   
        $this->save();
        $this->getFollowedUser()->one()->updateCounters(['followers_count'=>$number]);
        $this->getUser()->one()->updateCounters(['following_count'=>$number]);
    }
    
    public static function isUserFollowing($userId)
    {
        if (!Yii::$app->user->isGuest && Following::findOne(['user_id'=>Yii::$app->user->id,'followed_user_id'=>$userId,'status'=>self::STATUS_ACTIVE]))
                return true;
        return false;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFollowedUser()
    {
        return $this->hasOne(User::className(), ['id' => 'followed_user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
