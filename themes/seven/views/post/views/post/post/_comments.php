<?php 
use yii\widgets\Pjax;
use Miladr\Jalali\jDateTime;
/* @var $this \yii\web\View */
/* @var $post app\modules\post\models\Post */
/* @var $newComment \app\modules\post\models\Comment*/
/* @var $comments array */
/* @var $lastComment integer last comment timestamp*/
?>
<ul class="messages">
    
        <?php 
        $lastComment = 0;
        foreach ($comments as $comment): 
            $user           =   $comment->getUser()->one();
            $uid            =   md5($comment->id);
            $lastComment    =   strtotime($comment->created_at);
        ?>
        <li id="comment-<?= $uid;?>">
            <img src="<?= $user->getProfilePicture(60);?>" alt="<?= $user->getName();?>">
            <div>
                <div>
                    <a href="<?= Yii::$app->urlManager->createUrl(["@{$user->username}"]) ?>">
                        <h5><?= $user->getName();?></h5>
                    </a>
                    <span class="time"><i class="fa fa-clock-o"></i>
                        <a href="<?= Yii::$app->urlManager->createUrl(["@{$user->username}/{$post->url}#comment-{$uid}"]) ?>">
                            <?= jDateTime::date("l jS F Y H:i",$lastComment)?>
                        </a>
                    </span>
                </div>
                <p>
                    <?= $comment->text;?>
                </p>
            </div>
        </li>    
    <?php 
        endforeach;
        if (Yii::$app->user->isGuest){
            echo $this->render('_login_to_comment');
        } else {
            Pjax::begin(['enablePushState'=>FALSE]);
            ?>
            <li>
                <img src="<?= Yii::$app->user->getIdentity()->getProfilePicture(60);?>" alt="<?= Yii::$app->user->getIdentity()->getName();?>">
                <div>
                    <?= $this->render('_comment_form',['post'=>$post,'newComment'=>$newComment,'timestamp'=>$lastComment]);?>
                </div>        
            </li>        
            <?php
            Pjax::end();
        }
    ?>
</ul>