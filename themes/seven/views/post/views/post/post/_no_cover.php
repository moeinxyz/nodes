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
                    <?= Module::t('post','post.written_by',['author'=>$model->getUser()->one()->getName(),'time'=>Yii::$app->jdate->date("l Y/m/d",strtotime($model->created_at))]);?>                     
                </a>
             </div>
        </div>
    </div>
</div>