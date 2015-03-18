<?php 
use yii\helpers\HtmlPurifier; 
use app\modules\post\Module;
?>
<div class="row">
    <div class="col-md-8 col-md-offset-2">
         <div class="row">
             <div class="col-md-12 centertext top-buffer">
                <h1 class="wow fadeInUp animated post-header" data-wow-delay="0.5s" style="visibility: visible; -webkit-animation-delay: 0.5s;">
                    <?= HtmlPurifier::process($title); ?>
                </h1>
                <a href="<?= Yii::$app->urlManager->createUrl(["@{$model->getUser()->one()->username}"]) ?>"  class="wow fadeInUp animated post-header" data-wow-delay="0.5s" style="visibility: visible; -webkit-animation-delay: 0.5s;">
                    <?= Module::t('post','post.written_by',['author'=>$model->getUser()->one()->getName(),'time'=>Yii::$app->jdate->date("Y/m/d",strtotime($model->created_at))]);?>                     
                </a>
             </div>
        </div>
    </div>
</div>

<!--<section id="start" class="herofade hero cover element-img" style="opacity: 1; height: 302px; background-image: url(http://pixabay.com/get/34d83a3ef7abe78d2524/1426629548/25026f409649eb72bba49266_640.jpg);">
    <div class="tagline">
        <a href="<?= Yii::$app->urlManager->createUrl(["@{$model->getUser()->one()->username}"]) ?>">
            <img src="<?= Yii::$app->user->getIdentity()->getProfilePicture();?>" class="img-circle wow fadeInUp animated" alt="Columbia Logo" data-wow-delay="0.3s" style="visibility: visible; -webkit-animation-delay: 0.3s;">    
        </a>
        <hr class="mini white central">
        <h1 class="wow fadeInUp animated post-header" data-wow-delay="0.5s" style="visibility: visible; -webkit-animation-delay: 0.5s;">
            <?= HtmlPurifier::process($title); ?>
        </h1>
        <a class="scrollto" href="#content"><span class="icon-arrow-down2"></span></a>
    </div>
</section> -->