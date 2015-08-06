<?php 
use Miladr\Jalali\jDateTime;
use app\modules\post\Module;
/* @var $this \yii\web\View */
/* @var $username string */
/* @var $url string*/
/* @var $newComment \app\modules\post\models\Comment*/
/* @var $comments array */
/* @var $timestamp integer last comment timestamp*/
?>
<?php 
    foreach ($comments as $comment): 
        $user           =   $comment->getUser()->one();
        $uid            =   md5($comment->id);
?>
    <li id="comment<?= $uid;?>">
        <img src="<?= $user->getProfilePicture(60);?>" alt="<?= $user->getName();?>">
        <div>
            <div>
                <a href="<?= Yii::$app->urlManager->createUrl(["@{$user->username}"]) ?>">
                    <h5><?= $user->getName();?></h5>
                </a>
                <span class="time">
                    <i class="fa fa-clock-o"></i>
                    <a href="<?= Yii::$app->urlManager->createUrl(["@{$username}/{$url}#comment{$uid}"]) ?>">
                        <?= jDateTime::date("l jS F Y H:i",strtotime($comment->created_at))?>
                    </a>
                </span>
                <?php if ($comment->user_id === Yii::$app->user->getId() || $post->user_id === Yii::$app->user->getId()): ?>
                <i class="fa fa-bell-o bell" title="<?= Module::t('comment','_comments.delete.confirm'); ?>"></i>
                <?php endif; ?>
            </div>
            <p>
                <?= $comment->text;?>
            </p>
        </div>
    </li>    
<?php
    endforeach;
    echo $this->render('_comment_form',['username'=>$username,'url'=>$url,'newComment'=>$newComment,'timestamp'=>$timestamp]);?>        