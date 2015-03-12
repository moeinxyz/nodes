<?php 
use yii\helpers\HtmlPurifier;
use app\components\Helper\Extract;
$this->registerAssetBundle('show-post');
?>
<section id="start" class="herofade hero cover element-img" style="opacity: 1; height: 302px; background-image: url(http://pixabay.com/get/1d2b7cc3baef30612217/1426148267/ac2ee8a2cc7706534145d0c1_640.jpg);">
    <div class="tagline">
        <img src="<?= Yii::$app->user->getIdentity()->getProfilePicture();?>" class="img-circle wow fadeInUp animated" alt="Columbia Logo" data-wow-delay="0.3s" style="visibility: visible; -webkit-animation-delay: 0.3s;">
        <hr class="mini white central">
        <h1 class="wow fadeInUp animated" data-wow-delay="0.5s" style="visibility: visible; -webkit-animation-delay: 0.5s;">
            
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
                                        <div class="section-inner layoutSingleColumn"> 
                                            <?= $model->autosave_content; ?>
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
