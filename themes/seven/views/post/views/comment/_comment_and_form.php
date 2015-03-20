<?php $user = $comment->getUser()->one(); ?>
<li id="<?= $comment->id; ?>">
    <img src="<?= $user->getProfilePicture(60);?>" alt="<?= $user->getName();?>">
    <div>
        <div>
            <a href="<?= Yii::$app->urlManager->createUrl(["@{$user->username}"]) ?>">
                <h5><?= Yii::$app->user->getIdentity()->getName();?></h5>
            </a>
            <span class="time"><i class="fa fa-clock-o"></i>
                <a href="<?= Yii::$app->urlManager->createUrl(["@{$user->username}/{$comment->getPost()->one()->url}/#$comment->id"]) ?>">
                    <?= Yii::$app->jdate->date("l Y/m/d H:i",strtotime($comment->created_at))?>
                </a>
            </span>
        </div>
        <p>
            <?= $comment->text;?>
        </p>
    </div>
</li>
<?= $this->render('_form',['model'=>$model,'username'=>$username,'url'=>$url,'timestamp']); ?>
