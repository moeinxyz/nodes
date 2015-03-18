<?php 
use yii\helpers\HtmlPurifier;
use app\components\Helper\Extract;
use app\modules\user\models\User;
$this->registerAssetBundle('show-post');
$title      =   Extract::extractTitle($model->autosave_content);
$content    =   Extract::extractContent($model->autosave_content);
?>
<section id="start" class="herofade hero cover element-img" style="opacity: 1; height: 302px; background-image: url(http://pixabay.com/get/34d83a3ef7abe78d2524/1426629548/25026f409649eb72bba49266_640.jpg);">
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
</section>      

<div class="row" id="content">
    <div class="col-md-8 col-md-offset-2">    
        <div class="row">
            <div class="col-md-12">
                <article class="postArticle">
                    <div class="postContent">
                        <div class="notesSource">
                            <div id="editor">
                                <section class="section--first section--last">
                                    <div class="section-content"> 
                                        <div class="section-inner layoutSingleColumn" style="padding-top: 35px;"> 
                                            <?php
                                                $config = HTMLPurifier_Config::createDefault();
                                                $config->set('HTML.SafeIframe', true);
                                                $config->set('URI.SafeIframeRegexp', '%^(https?:)?//(www\.aparat\.com/video/video/embed/videohash/|www\.youtube(?:-nocookie)?\.com/embed/|player\.vimeo\.com/video/)%');
                                                echo HtmlPurifier::process($content, $config); 
                                            ?>
                                        </div>
                                    </div>
                                </section>
                            </div>
                        </div>
                    </div>
                </article>
            </div>
        </div>
    </div>
</div>
<?php 
$js=<<<JS
$("#editor a[href^='http://']").attr("target","_blank");
$("#editor a[href^='https://']").attr("target","_blank");
JS;
$this->registerJs($js);
