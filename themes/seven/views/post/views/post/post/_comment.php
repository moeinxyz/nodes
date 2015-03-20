<?php 
use yii\widgets\Pjax;
?>
<ul class="messages">
    <?php foreach ($comments as $userComment): $user = $userComment->getUser()->one(); ?>
        <li id="comment<?= $userComment->id;?>">
            <img src="<?= $user->getProfilePicture(60);?>" alt="<?= $user->getName();?>">
            <div>
                <div>
                    <a href="<?= Yii::$app->urlManager->createUrl(["@{$user->username}"]) ?>">
                        <h5><?= Yii::$app->user->getIdentity()->getName();?></h5>
                    </a>
                    <span class="time"><i class="fa fa-clock-o"></i>
                        <a href="<?= Yii::$app->urlManager->createUrl(["@{$user->username}/{$post->url}#comment{$userComment->id}"]) ?>">
                            <?= Yii::$app->jdate->date("l Y/m/d H:i",strtotime($userComment->created_at))?>
                        </a>
                    </span>
                </div>
                <p>
                    <?= $userComment->text;?>
                </p>
            </div>
        </li>    
    <?php endforeach;?>
    <?php Pjax::begin(['enablePushState'=>FALSE]);?>
    <li>
        <img src="<?= Yii::$app->user->getIdentity()->getProfilePicture(60);?>" alt="<?= Yii::$app->user->getIdentity()->getName();?>">
        <div>
            <?= $this->render('_comment_form',['post'=>$post,'comment'=>$comment]);?>
        </div>        
    </li>
    <?php Pjax::end(); ?>
</ul>