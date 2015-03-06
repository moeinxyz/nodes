<?php

namespace app\modules\user\models;

use Yii;

/**
 * This is the model class for table "{{%token}}".
 *
 * @property string $id
 * @property integer $user_id
 * @property string $token
 * @property string $created_at
 * @property string $type
 * @property string $status
 * @property string $reCaptcha
 *
 * @property User $user
 */
class Token extends \yii\db\ActiveRecord
{
    public $reCaptcha;

    const STATUS_USED               =   'USED';
    const STATUS_UNUSED             =   'UNUSED';
    const TYPE_ACCOUNT_ACTIVATION   =   'ACCOUNT_ACTIVATION';
    const TYPE_RESET_PASSWORD       =   'RESET_PASSWORD';
    const TYPE_CHANGE_EMAIL_STEP1   =   'CHANGE_EMAIL_STEP1';
    const TYPE_CHANGE_EMAIL_STEP2   =   'CHANGE_EMAIL_STEP2';
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return Yii::$app->getModule('user')->tokenTable;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'token'], 'required'],
            [['user_id'], 'integer'],
            [['created_at'], 'safe'],
            [['type', 'status'], 'string'],
            [['token'], 'string', 'max' => 32],
            [['reCaptcha'], \himiklab\yii2\recaptcha\ReCaptchaValidator::className(), 'secret' => Yii::$app->reCaptcha->secret,'on'=>'reset']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'            => Yii::t('app', 'ID'),
            'user_id'       => Yii::t('app', 'User ID'),
            'token'         => Yii::t('app', 'Token'),
            'created_at'    => Yii::t('app', 'Created At'),
            'type'          => Yii::t('app', 'Type'),
            'status'        => Yii::t('app', 'Status'),
        ];
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
                $this->created_at   =   new \yii\db\Expression('NOW()');    
                $this->status       =   self::STATUS_UNUSED;
                if (!$this->token){
                    $this->token        =   Yii::$app->security->generateRandomString();    
                }
            }
           return true;
        }
        return false;
    }

    public function activeAccount()
    {
        $connection     =   Yii::$app->db;
        $transaction    =   $connection->beginTransaction();
        try{
            $this->status       =   self::STATUS_USED;
            $user               =   $this->user;
            $user->status       =   User::STATUS_ACTIVE;
            if ($this->save() && $user->save()){
                $transaction->commit();
                return TRUE;
            } else {
                throw new Exception();
            }
        } catch (Exception $ex) {
            $transaction->rollBack();
            return FALSE;
        }
    }
    
    public function changeEmail($email)
    {
        $connection     =   Yii::$app->db;
        $transaction    =   $connection->beginTransaction();
        try{
            $this->status       =   self::STATUS_USED;
            $user               =   $this->user;
            $user->email        =   $email;
            if ($this->save() && $user->save()){
                $transaction->commit();
                return TRUE;
            } else {
                throw new Exception();
            }
        } catch (Exception $ex) {
            $transaction->rollBack();
            return FALSE;
        }
    }

    /**
     * 
     * @param Integer $userid
     * @return \app\modules\user\models\Token
     */
    public static function getNewActivationToken($userid)
    {
        $model          =   self::getValidExistedToken($userid,self::TYPE_ACCOUNT_ACTIVATION);
        if ($model == NULL){
            $model          =   new Token();
            $model->user_id =   $userid;
            $model->type    =   self::TYPE_ACCOUNT_ACTIVATION;
            $model->save();            
        }
        return $model;
    }
    
    public static function getNewResetToken($userid)
    {
        $model          =   self::getValidExistedToken($userid,self::TYPE_RESET_PASSWORD);
        if ($model == NULL){
            $model          =   new Token();
            $model->user_id =   $userid;
            $model->type    =   self::TYPE_RESET_PASSWORD;
            $model->save();            
        }
        return $model;        
    }
    
    public static function getNewMailChangeToken($userid)
    {
        $model          =   self::getValidExistedToken($userid,self::TYPE_CHANGE_EMAIL_STEP1);
        if ($model == NULL){
            $model          =   new Token();
            $model->user_id =   $userid;
            $model->type    =   self::TYPE_CHANGE_EMAIL_STEP1;
            $model->save();            
        }
        return $model;        
    }
    /**
     * 
     * @param Token $token
     * @param Integer $userid
     * @param String $email
     * @return boolean
     * @throws Exception
     */
    public static function getNewMailConfirmToken($token,$userid,$email)
    {
        $model = new Token;
        $model->user_id =   $userid;
        $model->type    =   self::TYPE_CHANGE_EMAIL_STEP2;
        $model->token   =   self::generateEmailValidationToken($userid, $email);

        $token->status  =   self::STATUS_USED;
        
        $connection     =   Yii::$app->db;
        $transaction    =   $connection->beginTransaction();
        try{
            if ($token->save() && $model->save()){
                $transaction->commit();
                return $model;
            } else {
                throw new Exception();
            }
        } catch (Exception $ex) {
            $transaction->rollBack();
            return FALSE;
        }        
    }

    private static function getValidExistedToken($userId,$type = self::TYPE_ACCOUNT_ACTIVATION)
    {
        $limit          =   Yii::$app->controller->module->tokenLimitTime;
        
        $conditions     =   [
            ':user_id'      =>  $userId,
            ':type'         =>  $type,
            ':status'       =>  self::STATUS_UNUSED,
            ':time'         =>  new \yii\db\Expression("DATE_SUB(NOW(), INTERVAL {$limit} SECOND)")
        ];
        return Token::find()->where('user_id = :user_id && status = :status && type = :type && created_at < :time',$conditions)->one();        
    }
    
    public static function generateEmailValidationToken($userid,$email)
    {
        $string =   $userid.$email.$userid;
        return md5(md5($string));
    }
}
