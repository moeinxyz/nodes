<?php

namespace app\modules\user\models;
use Yii;
use yii\base\Model;
use app\modules\user\models\User;
use app\modules\user\Module;

/**
 * @property string $password
 * @property string $newPassword
 * @property string $confirmNewPassword
 */
class ChangeEmailForm extends Model
{
    
    public $email;
    public $_user;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['email'],'required'],
            [['email'], 'string', 'max' => 128],            
            [['email'],'email'],            
            [['email'],'validateUniqueEmail'],
        ];
    }
    public function attributeLabels()
    {
        return [
            'email'              =>  Module::t('token','chgEmail.attr.email'),
        ];
    }    

    public function validateUniqueEmail($attribute,$params)
    {
        if (!$this->hasErrors()){
            $user = User::findOne(['email'=>  $this->email]);
            if ($user != NULL && $user->id != Yii::$app->user->id){
                $this->addError($attribute, Module::t('token','chgEmail.vld.uniqueEmail',['email'=>  $this->email]));
            } else if ($user != NULL && $user->id === Yii::$app->user->id){
                $this->addError($attribute, Module::t('token','chgEmail.vld.yourEmail',['email'=>  $this->email]));
            }
        }
    }
    
    public function getUser()
    {
        if (!$this->_user){
            $this->_user    =   User::findOne(Yii::$app->user->id);
        }
        return $this->_user;
    }
}
