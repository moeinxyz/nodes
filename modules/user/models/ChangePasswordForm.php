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
class ChangePasswordForm extends Model
{
    
    public $password;
    
    public $newPassword;
    public $confirmNewPassword;
    
    public $_user;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['newPassword',   'confirmNewPassword'], 'required'],            
            [['newPassword'],  'string', 'min' => 8],
            [['confirmNewPassword'],  'compare','compareAttribute'=>'newPassword'],
            [['password'],'required','on'=>'notOauthUser'],
            [['password'],'validatePassword','on'=>'notOauthUser'],
        ];
    }
    public function attributeLabels()
    {
        return [
            'password'              =>  Module::t('user','chgPwd.attr.password'),
            'newPassword'           =>  Module::t('user','chgPwd.attr.newPassword'),            
            'confirmNewPassword'    =>  Module::t('user','chgPwd.attr.confirmNewPassword'),
        ];
    }    


    
    public function validatePassword($attribute,$params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, Module::t('user', 'chgPwg.vld.wrongePassword'));
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
    
    
    public function changePassword()
    {
        if ($this->validate())
        {           
            $user =  $this->getUser();
            $user->password = Yii::$app->security->generatePasswordHash($this->newPassword);
            if ($user->save()){
                $this->setScenario('notOauthUser');
                $this->password = $this->newPassword = $this->confirmNewPassword = NULL;
                return true;
            }
            return false;
        }
        return false;
    }
}
