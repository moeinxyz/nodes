<?php
namespace app\modules\user\models;

use yii\base\Model;
use app\modules\user\models\Url;

class Attributes extends Model{
    private $email;
    private $name           =   NULL;
    private $username       =   NULL;
    private $tagline        =   NULL;
    private $profile_pic    =   NULL;
    private $profile_cover  =   NULL;
    private $urls   =   [];
    
    public function __set($name, $value) {
        if ($name != 'urls'){
            $this->{$name}  =   $value;
        }
    }
    
    public function __get($name) {
        if ($name === 'username' && $this->username === NULL){
            $parts  =   explode('@',  $this->email);
            $this->username =   str_replace('.', '', $parts[0]);
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