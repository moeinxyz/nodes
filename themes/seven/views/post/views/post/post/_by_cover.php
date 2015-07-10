<?php 
/* @var $this \yii\web\View */
/* @var $post app\modules\post\models\Post */
use app\modules\post\Module; 
use app\modules\post\models\Post;
$user = $post->getUser()->one();
?>
<section id="start" class="herofade hero cover element-img" style="opacity: 1; height: 302px; background-image: url(<?= Post::getCoverUrl($post->id); ?>);">
    <div class="tagline">
        <a href="<?= Yii::$app->urlManager->createUrl(["@{$user->username}"]) ?>">
            <img src="<?= Yii::$app->user->getIdentity()->getProfilePicture();?>" class="img-circle wow fadeInUp animated" alt="<?= Yii::$app->user->getIdentity()->getName();?>" data-wow-delay="0.3s" style="visibility: visible; -webkit-animation-delay: 0.3s;">                        
        </a>
        <hr class="mini white central">
        <h1 class="wow fadeInUp animated post-header" data-wow-delay="0.5s" style="visibility: visible; -webkit-animation-delay: 0.5s;">
            <?= $title; ?>
        </h1>        
        <a href="<?= Yii::$app->urlManager->createUrl(["@{$user->username}"]) ?>"  class="wow fadeInUp animated post-header" data-wow-delay="0.5s" style="visibility: visible; -webkit-animation-delay: 0.5s;">
            <?= Module::t('post','post.written_by',['author'=>$user->getName(),'time'=>Yii::$app->jdate->date("l jS F Y",strtotime($post->published_at))]);?>
        </a>                
        <a class="scrollto" href="#content"><span class="icon-arrow-down2"></span></a>
    </div>
</section> 