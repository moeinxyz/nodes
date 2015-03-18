<?php

namespace app\modules\user\models;

use Yii;

/**
 * This is the model class for table "{{%unwanted}}".
 *
 * @property integer $user_id
 * @property integer $unwanted_user_id
 * @property integer $frequency
 *
 * @property User $unwantedUser
 * @property User $user
 */
class Unwanted extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%unwanted}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'unwanted_user_id'], 'required'],
            [['user_id', 'unwanted_user_id', 'frequency'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => Yii::t('unwanted', 'User ID'),
            'unwanted_user_id' => Yii::t('unwanted', 'Unwanted User ID'),
            'frequency' => Yii::t('unwanted', 'Frequency'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUnwantedUser()
    {
        return $this->hasOne(User::className(), ['id' => 'unwanted_user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
