<?php

namespace app\modules\user\models;

use yii\base\Model;
use app\modules\user\models\User;
use app\modules\user\Module;


/**
 * @property User $_user
 */
class ResetForm extends Model
{
    public $email;
    public $reCaptcha;
    private $_user      = false;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['email'], 'required'],            
//            [['reCaptcha'], \himiklab\yii2\recaptcha\ReCaptchaValidator::className(), 'secret' => Yii::$app->reCaptcha->secret],
            [['email'],'validateStatus'],
        ];
    }
    public function attributeLabels()
    {
        return [
            'email'         =>  Module::t('user','reset.attr.email'),
        ];
    }    

    
    public function validateStatus($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || $user->status != User::STATUS_ACTIVE) {
                $this->addError($attribute, Module::t('user', 'reset.vld.error'));
            }
        }
    }

    /**
     * Finds user by [[email,username]]
     *
     * @return User|null
     */
    public function getUser()
    {
        if (!$this->_user){
            if (!filter_var($this->email,FILTER_VALIDATE_EMAIL)){
                $this->_user    =   User::findIdentityByUsername($this->email);
            } else {
                $this->_user = User::findIdentityByEmail($this->email);
                if ($this->_user === NULL) {
                    $this->_user    =   User::findIdentityByUsername($this->email);
                }
            }            
        }
        return $this->_user;
    }
}
