<?php

namespace app\modules\social\models;

use Yii;

/**
 * This is the model class for table "{{%socialcontent}}".
 *
 * @property string $id
 * @property string $post_id
 * @property string $social_id
 * @property string $verbose
 * @property string $created_at
 *
 * @property Post $post
 * @property Social $social
 */
class Socialcontent extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%socialcontent}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['post_id', 'verbose'], 'required'],
            [['post_id', 'social_id'], 'integer'],
            [['verbose'], 'string'],
            [['created_at'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [];
//        return [
//            'id' => Yii::t('socialcontent', 'ID'),
//            'post_id' => Yii::t('socialcontent', 'Post ID'),
//            'social_id' => Yii::t('socialcontent', 'Social ID'),
//            'verbose' => Yii::t('socialcontent', 'Verbose'),
//            'created_at' => Yii::t('socialcontent', 'Created At'),
//        ];
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
    public function getSocial()
    {
        return $this->hasOne(Social::className(), ['id' => 'social_id']);
    }
    
    public function beforeValidate() {
        if (parent::beforeValidate()){
            if ($this->isNewRecord){
                $this->created_at = new \yii\db\Expression('NOW()');
            }
            return TRUE;
        }
        return FALSE;
    }
}
