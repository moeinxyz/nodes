<?php

namespace app\modules\post\models;

use Yii;
use app\modules\post\Module;
use app\modules\user\models\User;
use yii\helpers\HtmlPurifier;
use app\components\Helper\Stopwords;
/**
 * This is the model class for table "{{%post}}".
 *
 * @property string $id
 * @property string $url
 * @property string $title
 * @property string $content
 * @property string $autosave_content
 * @property string $cover
 * @property string $pure_text
 * @property string $pin
 * @property string $comments_count
 * @property string $status
 * @property string $created_at
 * @property string $updated_at
 * @property string $published_at
 * @property string $last_update_type
 * @property integer $user_id
 * @property Integer $score
 * @property string $score_updated_at
 * @property string $score_update_requested_at
 *
 * @property User $user
 * @property Posttag[] $posttags
 */
class Post extends \yii\db\ActiveRecord
{
    const STATUS_DRAFT      =   'DRAFT';
    const STATUS_PUBLISH    =   'PUBLISH';
    const STATUS_TRASH      =   'TRASH';
    const STATUS_DELETE     =   'DELETE';
    const STATUS_WRITTING   =  'WRITTING';
    
    const PIN_ON            =   'ON';
    const PIN_OFF           =   'OFF';
    
    const COVER_NOCOVER     =   'NOCOVER';
    const COVER_BYCOVER     =   'BYCOVER';
    
    const LAST_UPDATE_TYPE_MANUAL   =   'MANUAL';
    const LAST_UPDATE_TYPE_AUTOSAVE =   'AUTOSAVE';
    
    private $oldStatus      =   NULL;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return Yii::$app->getModule('post')->postTable;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['url', 'title', 'content'], 'required','on'=>'publish'],
            [['content','autosave_content','pure_text'], 'string'],
            [['cover'],'in','range'=>[self::COVER_BYCOVER,self::COVER_NOCOVER]],
            [['comments_count', 'user_id'], 'integer'],
            [['url', 'title'], 'string', 'max' => 256,'skipOnEmpty'=>TRUE],
            [['status'],'in','range'=>[self::STATUS_DELETE,self::STATUS_DRAFT,self::STATUS_PUBLISH,self::STATUS_TRASH,self::STATUS_WRITTING]],
            [['pin'],'in','range'   =>[self::PIN_ON,self::PIN_OFF]],            
            [['last_update_type'],'in','range'   =>[self::LAST_UPDATE_TYPE_AUTOSAVE,self::LAST_UPDATE_TYPE_MANUAL]],            
            [['url'],'validateUniqueUrl','on'=>'save'],
            [['title','pure_text'],'filter','filter'=>'trim'],
            [['score'],'number','min'=>0,'max'=>4294967295]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'url'               => Module::t('post', 'post.attr.url'),
            'title'             => Module::t('post', 'post.attr.title'),
            'content'           => Module::t('post', 'post.attr.content'),
            'pin'               => Module::t('post', 'post.attr.pin'),
            'comments_count'    => Module::t('post', 'post.attr.comments_count'),
            'status'            => Module::t('post', 'post.attr.status'),
            'created_at'        => Module::t('post', 'post.attr.created_at'),
            'updated_at'        => Module::t('post', 'post.attr.updated_at'),
        ];
    }

    public function beforeValidate() {
        if (parent::beforeValidate()){
            if ($this->isNewRecord){
                $this->user_id                      =   Yii::$app->user->id;
                $this->last_update_type             =   self::LAST_UPDATE_TYPE_MANUAL;
                $this->cover                        =   self::COVER_NOCOVER;
                $this->created_at                   =   new \yii\db\Expression('NOW()');
                $this->published_at                 =   Yii::$app->params['zeroTime'];
                $this->score_updated_at             =   Yii::$app->params['zeroTime'];
                $this->score_update_requested_at    =   Yii::$app->params['zeroTime'];
                $this->score                        =   0;
            }
            if ($this->title != NULL)
            {
                $this->title      = substr($this->title, 0,256);    
            }
            $this->updated_at = new \yii\db\Expression('NOW()');
            return TRUE;
        }
        return FALSE;
    }

    public static function getLastUserWrittingPost()
    {
        return self::find()->where('status=:status AND user_id=:user_id',[':status'=>self::STATUS_WRITTING,'user_id'=>Yii::$app->user->id])
                            ->orderBy('updated_at')
                            ->limit(1)->one();
    }

    public static function draftAllWrittingPost()
    {
        return self::updateAll(['status'=>self::STATUS_DRAFT],'user_id=:user_id AND status=:status',[':user_id'=>Yii::$app->user->id,':status'=>self::STATUS_WRITTING]);
    }
    
    /**
     * 
     * @param string $title
     * @param integer $id 
     * @return string
     */
    public static function suggestUniqueUrl($title,$id)
    {
        $title      =   strtolower($title);
        $title      =   preg_replace('/[[:space:]]+/', '-', $title);
        $title      =   Stopwords::removeUnvalidCharacters($title);
        $words      =   Stopwords::purifierText(explode('-', $title));
        $words      =   Stopwords::purgeLongWord($words);
        $postfix    =   base_convert($id, 10, 36);
        $count      =   count($words);
        $tries      =   0;
        $maxLen     =   40;
        $url        =   '';
        $skipTitle  =   ($count === 0 || $title === '')?TRUE:FALSE;
        do
        {
            if (!$skipTitle){
                $pieces         =   [];
                $random_keys    =   array_rand ($words, rand (min (3,$count), $count));
                if (is_array($random_keys)){
                    foreach ($random_keys as $key){
                        $pieces[]   =   $words[$key];
                    }                    
                } else {
                    $pieces[]   =   $words[$random_keys];
                }
                $url            =   implode ('-', $pieces);
                if (strlen($url) > $maxLen){
                    continue;
                } else if (in_array($url, Yii::$app->params['wildcat_urls'])){
                    continue;
                }
            }
            if (!$skipTitle && $tries >= 25 && $maxLen === 40){
                $tries  =   0;
                $maxLen =   60;
            } elseif ($skipTitle || ($tries >= 25 && $maxLen === 60)) {
                if ($skipTitle){
                    $url    =   $postfix;
                } else {
                    $url    =   $url.'-'.$postfix;
                }
                $number =   base_convert($postfix, 36, 10);
                $number =   rand($number, ($number + $tries));
                $postfix=   base_convert($number + 1, 10, 36);           
            }
            $tries++;
        } while(!self::isUniueUrl($url));
        return urlencode($url);
    }
    
    public static function isUniueUrl($url)
    {
        $model = Post::findOne(['user_id'=>Yii::$app->user->getId(),'url'=> urlencode($url)]);
        if ($model) {
            return false;
        }
        return true;
    }

    public function validateUniqueUrl($attribute,$params)
    {
        $post   =   Post::findOne(['user_id'=>Yii::$app->user->id,'url'=>  $this->url]);
        if ($post && ($this->isNewRecord || ($this->id != $post->id))){
            $this->addError($attribute, Module::t('post','post.vld.uniqueUrl'));
        }
    }

        /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
    
    public function togglePin()
    {
        if ($this->pin === self::PIN_OFF){
            $this->pin  =   self::PIN_ON;
        } else {
            $this->pin  =   self::PIN_OFF;
        }
    }
    
    public function toggleTrash()
    {
        if ($this->status === self::STATUS_TRASH){
            $this->status   =   self::STATUS_DRAFT;
        } else {
            $this->status   =   self::STATUS_TRASH;
        }
    }
    
    public function beforeSave($insert) {
        if (parent::beforeSave($insert)){
            if (isset($this->oldAttributes['status'])){
                $this->oldStatus   =   $this->oldAttributes['status'];    
            }
            return TRUE;
        }
        return FALSE;
    }

    //@todo test it
    public function afterSave($insert, $changedAttributes) {
        parent::afterSave($insert, $changedAttributes);
        if ($this->oldStatus !== NULL && $this->oldStatus !== self::STATUS_PUBLISH && $this->status === self::STATUS_PUBLISH){
            $this->user->updateCounters(['posts_count'=>1]);
            $this->updateUsersRecommendedCounts($this->id, 1);
        } else if ($this->oldStatus !== NULL && $this->oldStatus === self::STATUS_PUBLISH && $this->status !== self::STATUS_PUBLISH) {
            $this->user->updateCounters(['posts_count'=>-1]);
            $this->updateUsersRecommendedCounts($this->id, -1);
        }
        
        // add my post to my reading list
        if ($this->getScenario() === 'publish' && UserToRead::findOne(['user_id'=>  \Yii::$app->user->getId(),'post_id'=>$this->id]) === NULL){
            $model          =   new UserToRead();
            $model->user_id = \Yii::$app->user->getId();
            $model->post_id = $this->id;
            $model->priority=   2;
            $model->score   =   256;
            $model->save();
        }
    }
    
    private function updateUsersRecommendedCounts($postId,$count)
    {        
        $sql    =   'UPDATE '.User::tableName().' SET recommended_count=recommended_count + (:count) '
                .   'WHERE id IN '
                .   '(SELECT `user_id` FROM '.Userrecommend::tableName().' WHERE post_id=:postId)';

        Yii::$app->db->createCommand($sql)
                ->bindValue(':count', $count)
                ->bindValue(':postId', $postId)
                ->execute();        
    }

    public static function getCoverFileName($id)
    {
        return md5($id).base_convert($id, 10, 36);
    }
    
    
    public static function getCoverUrl($id,$thumbnail = false)
    {
        $name   =   self::getCoverFileName($id);
        if ($thumbnail){
            return Yii::getAlias("@postCoverBaseUrl/{$name}-thumbnail.jpg");
        }
        return Yii::getAlias("@postCoverBaseUrl/{$name}.jpg");
    }
    
    public function getPosttags()
    {
        return $this->hasMany(Posttag::className(), ['post_id' => 'id']);
    }
}
