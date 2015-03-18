<?php 
use app\modules\user\models\User;
if ($user->profile_cover === User::COVER_UPLOADED){
    echo $this->render('user/_with_cover',['user'=>$user]);
} else {
    echo $this->render('user/_without_cover',['user'=>$user]);
}
?>
