<?php

namespace app\modules\user\models;

use Yii;
use yii\base\Model;
use app\modules\user\models\User;
use app\components\BruteForce;
use app\modules\user\Module;
/**
 * LoginForm is the model behind the login form.
 */
class LoginForm extends Model
{
    public $email;
    public $password;
    public $rememberMe  = true;
    private $_user      = false;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['email', 'password'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            ['email','validateBruteForce'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
        ];
    }
    public function attributeLabels()
    {
        return [
            'email'         =>  Module::t('user','login.attr.email'),
            'password'      =>  Module::t('user','login.attr.password'),
            'rememberMe'    =>  Module::t('user','login.attr.rememberMe'),
        ];
    }    

    public function validateBruteForce($attribute, $params)
    {
        if (BruteForce::isBlocked($this->email,15)){
            $this->addError($attribute, Module::t('user', 'login.muchLoginTry',['ttl'=>BruteForce::getTTL($this->email)]));
        }
    }

        /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                BruteForce::addInvalidQuery($this->email);
                $this->addError($attribute, Module::t('user', 'login.invalidUsernameOrPassword'));
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     * @return boolean whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600*24*30 : 0);
        } else {
            return false;
        }
    }

    /**
     * Finds user by [[username]]
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
