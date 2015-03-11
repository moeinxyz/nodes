<?php

namespace app\modules\embed\models;

use Yii;

/**
 * This is the model class for table "{{%embed}}".
 *
 * @property string $id
 * @property string $hash
 * @property string $url
 * @property string $type
 * @property string $frequency
 * @property string $response
 * @property string $created_at
 * @property string $updated_at
 */
class Embed extends \yii\db\ActiveRecord
{
    const TYPE_OEMBED   =   'OEMBED';
    const TYPE_EXTRACT  =   'EXTRACT';
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return Yii::$app->getModule('embed')->embedTable;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['hash', 'url', 'response'], 'required'],
            [['response'], 'string'],
////            [['url'],'url'],
            [['type'],'in','range'=>[self::TYPE_EXTRACT,self::TYPE_OEMBED]],
            [['frequency'], 'integer'],
////            [['created_at', 'updated_at'], 'safe'],
            [['hash'], 'string', 'max' => 32],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'            => 'ID',
            'hash'          => 'Hash',
            'url'           => 'Url',
            'type'          => 'Type',
            'frequency'     => 'Frequency',
            'reponse'       => 'Response',
            'created_at'    => 'Created At',
            'updated_at'    => 'Updated At',
        ];
    }
    
    public function beforeValidate() {
        if (parent::beforeValidate()){
            $this->hash             =   md5($this->url);
            $this->updated_at       =   new \yii\db\Expression('NOW()');
            if ($this->isNewRecord){
                $this->created_at   =   new \yii\db\Expression('NOW()');    
                $this->frequency    =   1;
            }
            return TRUE;
        }
        return FALSE;
    }
}
