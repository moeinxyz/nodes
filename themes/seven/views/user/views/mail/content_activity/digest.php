<?php
/* @var $this yii\web\View */
/* @var $user \app\modules\user\models\User */

echo $this->render('digest/header',['user'=>$user]);

foreach ($comments as $comment)
{
    echo $this->render('digest/comment',['comment'=>$comment]);
}

echo $this->render('_footer',['user'=>$user]);


