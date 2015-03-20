<?php 
/* @var $this \yii\web\View */
/* @var $username string */
/* @var $url string*/
/* @var $newComment \app\modules\post\models\Comment*/
/* @var $comments array */
/* @var $timestamp integer last comment timestamp*/
?>
<ul class="messages">
        <?php 
        foreach ($comments as $comment): 
            $user           =   $comment->getUser()->one();
            $uid            =   md5($comment->id);
        ?>
        <li id="comment-<?= $uid;?>">
            <img src="<?= $user->getProfilePicture(60);?>" alt="<?= $user->getName();?>">
            <div>
                <div>
                    <a href="<?= Yii::$app->urlManager->createUrl(["@{$user->username}"]) ?>">
                        <h5><?= $user->getName();?></h5>
                    </a>
                    <span class="time"><i class="fa fa-clock-o"></i>
                        <a href="<?= Yii::$app->urlManager->createUrl(["@{$username}/{$url}#comment-{$uid}"]) ?>">
                            <?= Yii::$app->jdate->date("l Y/m/d H:i",strtotime($comment->created_at))?>
                        </a>
                    </span>
                </div>
                <p>
                    <?= $comment->text;?>
                </p>
            </div>
        </li>    
    <?php  endforeach; ?>
    <?= $this->render('_comment_form',['username'=>$username,'url'=>$url,'newComment'=>$newComment,'timestamp'=>$timestamp]);?>        
</ul>