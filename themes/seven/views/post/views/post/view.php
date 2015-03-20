<?php 
use yii\helpers\HtmlPurifier;
use app\components\Helper\Extract;
use app\modules\post\models\Post;
$this->registerAssetBundle('show-post');
$title      =   Extract::extractTitle($model->autosave_content);
$content    =   Extract::extractContent($model->autosave_content);
if ($model->cover === Post::COVER_BYCOVER){
    echo $this->render('post/_by_cover',['title'=>$title,'model'=>$model]);
} else {
    echo $this->render('post/_no_cover',['title'=>$title,'model'=>$model]);
}
?>
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
                                                echo HtmlPurifier::process($content, app\components\Helper\Purifier::getConfig()); 
                                            ?>
                                        </div>
                                    </div>
                                </section>
                            </div>
                        </div>
                    </div>
                </article>
                <?= ($view == TRUE)?($this->render('post/_comment',['post'=>$model,'comment'=>$comment,'comments'=>$comments])):NULL;?>
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
