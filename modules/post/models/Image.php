<?php

namespace app\modules\post\models;

use Yii;
use yii\web\UploadedFile;

/**
 * This is the model class for table "{{%image}}".
 *
 * @property string $id
 * @property string $url
 * @property string $created_at
 * @property string $post_id
 * @property UploadedFile $file
 * @property Post $post
 */
class Image extends \yii\db\ActiveRecord
{
    
    public $file;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%image}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['url', 'post_id'], 'required'],
            [['created_at'], 'safe'],
            [['post_id'], 'integer'],
            [['url'], 'string', 'max' => 255],
            [['url'],'unique'],
            [['file'],'image','skipOnEmpty'=>FALSE,'extensions'=>['jpg','png','jpeg','gif','svg','tif','tiff','webp'],'mimeTypes'=>['image/png','image/gif','image/jpeg','image/jpg','image/pjpeg','image/gif','image/tiff','image/x-tiff','image/svg+xml','image/webp'],'maxSize'=>8388608],            
        ];
    }

    public function beforeValidate() {
        if (parent::beforeValidate()){
            $this->created_at   =   new \yii\db\Expression('NOW()');
            $this->url          =   $this->generateUniqueUrl();
            return TRUE;
        }
        return FALSE;
    }

    private function generateUniqueUrl(){
        do {
            $url = md5($this->post_id).Yii::$app->getSecurity()->generateRandomString(58).".".$this->file->getExtension();
        } while(Image::findOne(['url'=>  $url]));
        return $url;
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPost()
    {
        return $this->hasOne(Post::className(), ['id' => 'post_id']);
    }
}
