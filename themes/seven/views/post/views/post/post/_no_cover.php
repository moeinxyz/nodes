<?php 
/* @var $this \yii\web\View */
/* @var $post app\modules\post\models\Post */
use yii\helpers\HtmlPurifier; 
use app\modules\post\Module;
$user = $post->getUser()->one();
?>
<div class="row">
    <div class="col-md-8 col-md-offset-2">
         <div class="row">
             <div class="col-md-12 centertext top-buffer">
                <h1 class="wow fadeInUp animated post-header" data-wow-delay="0.5s" style="visibility: visible; -webkit-animation-delay: 0.5s;">
                    <?= HtmlPurifier::process($title); ?>
                </h1>
                <a href="<?= Yii::$app->urlManager->createUrl(["@{$user->username}"]) ?>"  class="wow fadeInUp animated post-header" data-wow-delay="0.5s" style="visibility: visible; -webkit-animation-delay: 0.5s;">
                    <?= Module::t('post','post.written_by',['author'=>$user->getName(),'time'=>Yii::$app->jdate->date("l jS F Y",strtotime($post->published_at))]);?>                     
                </a>
             </div>
        </div>
    </div>
</div>