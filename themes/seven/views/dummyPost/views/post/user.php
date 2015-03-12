<?php 
use app\modules\user\models\User;
if ($user->profile_cover === User::COVER_UPLOADED){
    echo $this->render('_with_cover',['user'=>$user]);
} else {
    echo $this->render('_without_cover',['user'=>$user]);
}
?>
