<?php
use app\modules\post\models\Post;
/* @var $this yii\web\View */
/* @var $user \app\modules\user\models\User */
echo $this->render('digest/header',['user'=>$user]);
foreach ($posts as $key=>$post)
{
    if ($post->cover === Post::COVER_BYCOVER){
        echo $this->render('digest/by_cover',['post'=>$post]);
        unset($posts[$key]);
    }
}

foreach ($posts as $key=>$post)
{
    echo $this->render('digest/no_cover',['post'=>$post]);
}
echo $this->render('digest/footer',['user'=>$user]);