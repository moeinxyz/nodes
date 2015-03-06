<?php

namespace app\modules\user\models;

use Yii;
use yii\base\Model;
use app\modules\user\models\User;
use app\modules\user\Module;

class ProfileForm extends Model
{
    
    public $name;
    public $tagline;
    
    public $_user;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['name','confirmPassword'], 'required'],            
            [['password'], 'string', 'min' => 8],
            //@todo check for equalenc of password with confirmation
        ];
    }
    public function attributeLabels()
    {
        return [
            'password'         =>  Module::t('token','rstPwd.attr.password'),
            'confirmPassword'  =>  Module::t('token','rstPwd.attr.confirmPassword'),
        ];
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
    
    
    public function changePassword()
    {
        if ($this->validate())
        {
            $connection     =   Yii::$app->db;
            $transaction    =   $connection->beginTransaction();
            try{
                $user   =   $this->_token->user;
                $token  =   $this->_token;
                $user->password =   \Yii::$app->security->generatePasswordHash($this->password);
                $token->status  =   Token::STATUS_USED;
                if ($token->save() && $user->save()){
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
        return false;
    }
    
}
