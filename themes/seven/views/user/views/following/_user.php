<div class="row hidden-xs">
    <div class="col-sm-2">
        <div class="follow">
            <?= $this->render('_user_follow_form',['user'=>$user]);?>
        </div>            
    </div>
    <div class="col-sm-8">
        <a href="<?= Yii::$app->urlManager->createUrl("{$user->getUsername()}")?>">
            <?= $user->getName();?>
        </a>
        <br>
        <span class="small-font-size"><?= $user->tagline; ?></span>
    </div>
    <div class="col-sm-2 centertext">
        <a href="<?= Yii::$app->urlManager->createUrl("{$user->getUsername()}")?>">
            <img src="<?= $user->getProfilePicture(120);?>" class="img-circle profile-list-image">
        </a>
    </div>
</div>
<div class="row hidden-lg hidden-md hidden-sm">
    <div class="col-xs-8 centertext">
        <a href="<?= Yii::$app->urlManager->createUrl("{$user->getUsername()}")?>">
            <?= $user->getName();?>
        </a>
        <div class="follow">
            <?= $this->render('_user_follow_form',['user'=>$user]);?>
        </div>            
    </div>        
    <div class="col-xs-4 centertext">
        <a href="<?= Yii::$app->urlManager->createUrl("{$user->getUsername()}")?>">
            <img src="<?= $user->getProfilePicture(120);?>" class="img-circle profile-list-image">
        </a>
    </div>        
</div>

<?php
$js         =   <<<JS

$(".follow-form").on('pjax:send',function(){
    $(this).append('<div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>');
});
$(".follow-form").on('pjax:complete',function(){
    $(this).find('.overlay').remove();
});
JS;
$this->registerJs($js);    
?>