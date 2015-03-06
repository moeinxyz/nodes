<?php
namespace app\modules\user\models;

use yii\base\Model;

class PublicProfileForm extends Model{
    public $name;
    public $tagline;
    
    // to upload file
    public $profilePicture;
    public $coverPicture;
    
    // free,gravatar or uploaded
    public $profilePic;
    public $coverPic;
    
    public $_user;


    public function rules()
    {
        return [
            [['name'], 'string', 'max' => 128],
            [['tagline'], 'string', 'max' => 256]
        ];
    }
    
    public function getUser()
    {
        if (!$this->_user){
            $this->_user    =   User::findOne(Yii::$app->user->id);
        }
        return $this->_user;
    }    
    
    public function update()
    {
        if ($this->validate()){
            $user = $this->getUser();
            $user->name     = $this->name;
            $user->tagline  = $this->tagline;
            return $user->save();
        }
    }
    
    
}