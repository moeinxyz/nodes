<?php 
use app\modules\post\Module;
use Miladr\Jalali\jDateTime;

/* @var $this \yii\web\View */
/* @var $post app\modules\post\models\Post */
$user       =   $post->getUser()->one();
$timestamp  =   strtotime($post->published_at);
if ($timestamp <= 0){
    $timestamp  = strtotime($post->created_at);
}
?>
<div class="row">
    <div class="col-md-8 col-md-offset-2">
         <div class="row">
             <div class="col-md-12 centertext top-buffer">
                <h1 class="wow fadeInUp animated post-header" data-wow-delay="0.5s" style="visibility: visible; -webkit-animation-delay: 0.5s;">
                    <?= $title; ?>
                </h1>
                <a href="<?= Yii::$app->urlManager->createUrl(["@{$user->username}"]) ?>"  class="wow fadeInUp animated post-header" data-wow-delay="0.5s" style="visibility: visible; -webkit-animation-delay: 0.5s;">
                    <?= Module::t('post','post.written_by',['author'=>$user->getName(),'time'=>  jDateTime::date("l jS F Y",$timestamp)]);?>                     
                </a>
             </div>
        </div>
    </div>
</div>