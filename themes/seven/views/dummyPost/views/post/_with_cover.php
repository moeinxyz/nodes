<?php
use app\modules\post\Module; 
use yii\helpers\HtmlPurifier;

use app\modules\user\models\User;
use app\modules\user\models\Url;
/* @var $this \yii\web\View */
/* @var $user app\modules\user\models\User */
?>
<section class="container-fluid">
    <div class="row">
        <div class="col-xs-12 element-img postfade center-block centertext central" style="background-image: url(http://nodes.ir/nodesdotir/web/img/20141101_025202.jpg);">
            <img src="<?= $user->getProfilePicture(); ?>" class="img-circle" width="200">    
            <div class="follow">
                <?= $this->render('_follow',['user'=>$user]);?>
            </div>
        </div>
    </div>
</section>
<section class="container-fluid">
    <hr class="title">
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-8 col-md-offset-2 col-lg-8 col-lg-offset-2">
            <div class=" center-block centertext text-center">
                <h1><?= HtmlPurifier::process($user->name); ?></h1>
                <p><?= HtmlPurifier::process($user->tagline); ?></p>
                <div class="top-buffer"></div>
                <div class="row">
                    <div class="col-md-4 col-sm-4 col-xs-12 othermedia">
                        <h3><?= Module::t('post','user._user.othermedia'); ?></h3>
                        <?php foreach ($user->getActiveUrls() as $url): ?>
                            <a href="<?= $url->url; ?>" target="_blank">
                                <i class="<?= \app\components\Helper\Icon::getBrandIconClass($url->type); ?>"></i>
                            </a>
                        <?php endforeach; ?>
                    </div>
                    <a href="<?= Yii::$app->urlManager->createUrl(["@{$user->username}/following"]); ?>">
                        <div class="col-md-2 col-sm-2 col-xs-6">
                            <h3><?= Module::t('post','user._user.following'); ?></h3>
                            <span class="counter"><?= $user->following_count; ?></span>
                        </div>                    
                    </a>
                    <a href="<?= Yii::$app->urlManager->createUrl(["@{$user->username}/followers"]); ?>">
                        <div class="col-md-2 col-sm-2 col-xs-6">
                            <h3><?= Module::t('post','user._user.followers'); ?></h3>
                            <span class="counter"><?= $user->followers_count; ?></span>
                        </div>                        
                    </a>
                    <a href="<?= Yii::$app->urlManager->createUrl(["@{$user->username}/recommended"]); ?>">
                        <div class="col-md-2 col-sm-2 col-xs-6">
                            <h3><?= Module::t('post','user._user.recommended'); ?></h3>
                            <span class="counter"><?= $user->recommended_count; ?></span>
                        </div>                        
                    </a>
                    <a href="<?= Yii::$app->urlManager->createUrl(["@{$user->username}/posts"]); ?>">
                        <div class="col-md-2 col-sm-2 col-xs-6">
                            <h3><?= Module::t('post','user._user.posts'); ?></h3>
                            <span class="counter"><?= $user->posts_count; ?></span>
                        </div>                        
                    </a>                    
                </div>
            </div>
        </div>
    </div>
</section>
