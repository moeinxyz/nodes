<?php
/* @var $this yii\web\View */
/* @var $user \app\modules\user\models\User */
/* @var $comment \app\modules\post\models\Comment */

echo $this->render('full/header',['user'=>$user]);
echo $this->render('full/comment',['comment'=>$comment]);
echo $this->render('_footer',['user'=>$user]);


