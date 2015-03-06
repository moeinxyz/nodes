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
class ChangeUsernameForm extends Model
{
    
    public $username;
    public $_user;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['username'],'required'],
            [['username'], 'string', 'max' => 64],            
            [['username'],'match','pattern' => '/^[A-Za-z0-9_]+$/u'],
            [['username'],'validateUniqueUsername'],
        ];
    }
    public function attributeLabels()
    {
        return [
            'username'              =>  Module::t('token','chgUsername.attr.username'),
        ];
    }    

    public function validateUniqueUsername($attribute,$params)
    {
        if (!$this->hasErrors()){
            $user = User::findOne(['username'=>  $this->username]);
            if ($user != NULL && $user->id != Yii::$app->user->id){
                $this->addError($attribute, Module::t('user','chgUsername.vld.uniqueUsername',['username'=>  $this->username]));
            } else if ($user != NULL && $user->id === Yii::$app->user->id){
                $this->addError($attribute, Module::t('user','chgUsername.vld.yourUsername',['username'=>  $this->username]));
            }
        }
    }
    
    public function save()
    {
        if ($this->validate()){
            $user = $this->getUser();
            $user->username = $this->username;
            return $user->save();
        }
        return false;
    }

    public function getUser()
    {
        if (!$this->_user){
            $this->_user    =   User::findOne(Yii::$app->user->id);
        }
        return $this->_user;
    }
}
