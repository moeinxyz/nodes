<?php
namespace app\modules\user\models;

use yii\base\Model;
use app\modules\user\models\Url;

class Attributes extends Model{
    private $username;
    private $email;
    private $name;
    private $tagline;
    private $profile_pic;
    private $profile_cover;
    private $urls   =   [];
    
    public function __set($name, $value) {
        if ($name != 'urls'){
            $this->{$name}  =   $value;
        }
    }
    
    public function __get($name) {
        if ($name === 'username' && $this->username === NULL){
            $parts  =   explode('@',  $this->email);
            $this->username =   $parts[0];
        }
        
        return $this->{$name};
    }
    
    public function addUrl($type,$url){
        $this->urls[]   =   [$type,$url];
    }
    
    public function getUrls(){
        return $this->urls;
    }
}