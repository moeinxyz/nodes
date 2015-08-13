<?php 
/* @var $this \yii\web\View */
/* @var $post app\modules\post\models\Post */
use app\modules\post\Module; 
use app\modules\post\models\Post;
use Miladr\Jalali\jDateTime;
$user = $post->getUser()->one();
$timestamp  =   strtotime($post->published_at);
if ($timestamp <= 0){
    $timestamp  = strtotime($post->created_at);
}
?>
<section id="start" class="herofade hero cover element-img" style="opacity: 1; height: 302px; background-image: url(<?= Post::getCoverUrl($post->id).(isset($preview)?'?='.time():NULL); ?>);">
    <div class="tagline">
        <a href="<?= Yii::$app->urlManager->createUrl(["@{$user->username}"]) ?>">
            <img src="<?= $user->getProfilePicture();?>" class="img-circle wow fadeInUp animated" alt="<?= $user->getName();?>" data-wow-delay="0.3s" style="visibility: visible; -webkit-animation-delay: 0.3s;">                        
        </a>
        <hr class="mini white central">
        <h1 class="wow fadeInUp animated post-header" data-wow-delay="0.5s" style="visibility: visible; -webkit-animation-delay: 0.5s;">
            <?= $title; ?>
        </h1>        
        <a href="<?= Yii::$app->urlManager->createUrl(["@{$user->username}"]) ?>"  class="wow fadeInUp animated post-header" data-wow-delay="0.5s" style="visibility: visible; -webkit-animation-delay: 0.5s;">
            <?= Module::t('post','post.written_by',['author'=>$user->getName(),'time'=>  jDateTime::date("l jS F Y",$timestamp)]);?>
        </a>                
        <a class="scrollto" href="#content"><span class="icon-arrow-down2"></span></a>
    </div>
</section> 