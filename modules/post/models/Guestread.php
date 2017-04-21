<?php

namespace app\modules\post\models;

use Yii;

/**
 * This is the model class for table "{{%guestread}}".
 *
 * @property string $post_id
 * @property string $uuid
 * @property integer $ip
 * @property string $useragent
 * @property string $created_at
 * @property string $predictionio_status
 *
 * @property Post $post
 */
class Guestread extends \yii\db\ActiveRecord
{
    const PREDICTION_STATUS_NEW  = "NEW";
    const PREDICTION_STATUS_SENT = "SENT";

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%guestread}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['post_id', 'uuid', 'ip', 'useragent'], 'required'],
            [['id','post_id', 'ip'], 'integer'],
            [['created_at', 'predictionio_status'], 'safe'],
            [['uuid', 'useragent'], 'string', 'max' => 32],
            [['predictionio_status'], 'in', 'range'=>[self::PREDICTION_STATUS_NEW, self::PREDICTION_STATUS_SENT]]
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
    
    public function beforeValidate() {
        if (parent::beforeValidate()){
            if ($this->isNewRecord){
                $this->predictionio_status          =   self::PREDICTION_STATUS_NEW;
                $this->created_at   =   new \yii\db\Expression('NOW()');
                if (in_array($this->ip, ['127.0.0.1', '::1'])) {
                    return FALSE;
                }
                $this->ip           =   ip2long($this->ip);
                $this->useragent    =   md5($this->useragent);
            }
            return TRUE;
        }
        return FALSE;
    }
}
