<?php

namespace app\modules\post\models;

use Yii;

/**
 * This is the model class for table "{{%userread}}".
 *
 * @property integer $user_id
 * @property string $post_id
 * @property integer $ip
 * @property string $created_at
 *
 * @property Post $post
 * @property User $user
 */
class Userread extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%userread}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'post_id', 'ip'], 'required'],
            [['id','user_id', 'post_id', 'ip'], 'integer'],
            [['created_at'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [];
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
            if ($this->isNewRecord && $this->created_at == ''){//sometimes we moves from guest to user
                $this->created_at   =   new \yii\db\Expression('NOW()');
                if (in_array($this->ip, ['127.0.0.1', '::1'])) {
                    return FALSE;
                }
                $this->ip           =   ip2long($this->ip);
            }
            return TRUE;
        }
        return FALSE;
    }    
}
