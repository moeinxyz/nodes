<?php 
/* @var $this \yii\web\View */
/* @var $post app\modules\post\models\Post */
/* @var $newComment \app\modules\post\models\Comment*/
/* @var $comments array */
use app\modules\post\models\Post;
use yii\helpers\HtmlPurifier;
$this->registerAssetBundle('show-post');
// Render Header
echo $this->render('header/_view',['model'=>$post]);
echo $this->render('dynamicJS/_view',['model'=>$post]);
?>
<div id="wrapper" class="page-content">
    <?php
        if ($post->cover === Post::COVER_BYCOVER){
            echo $this->render('post/_by_cover',['title'=>$post->title,'post'=>$post]);
        } else {
            echo $this->render('post/_no_cover',['title'=>$post->title,'post'=>$post]);
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
                                                <?= HtmlPurifier::process($post->content,app\components\Helper\Purifier::getConfig());?>
                                            </div>
                                        </div>
                                    </section>
                                </div>
                            </div>
                        </div>
                    </article>
                    <?= $this->render('post/_comments',['post'=>$post,'newComment'=>$newComment,'comments'=>$comments]);?>
                </div>
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
