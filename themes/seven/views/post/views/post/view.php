<?php 
/* @var $this \yii\web\View */
/* @var $post app\modules\post\models\Post */
/* @var $newComment \app\modules\post\models\Comment*/
/* @var $comments array */
use app\modules\post\models\Post;
use app\modules\post\Module;

// social meta tags
$this->render('meta/_post',['post'=>$post]);

\app\assets\ShowPostAssets::register($this);

$this->title    =   Module::t('post','view.head.title',['title'=>$post->title]);
// Render Header

if (Yii::$app->user->isGuest){
    echo $this->render('header/_view_guest',['model'=>$post]);
} else {
    echo $this->render('header/_view_user',['model'=>$post]);
}
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
    <br>
    <div class="row">
        <a href="http://gholak.net/blog" target="_blank" class="col-md-6 col-md-offset-3 alert alert-success alert-dismissible text-center" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        قلک را دنبال کنید تا از آخرین آموزش‌ها و اخبار دنیای ارزهای دیجیتال آگاه شوید.
        </a>
    </div>
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
                                                <?= $post->content; ?>
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
//var_dump($post->id);die;
//$id = $model->id;
$uniqeId = md5(md5($post->id).$post->id);
$js=<<<JS
$("#editor a[href^='http://']").attr("target","_blank");
$("#editor a[href^='https://']").attr("target","_blank");

(function() {
    Bibblio.initRelatedContent({
        targetElementId: 'bib_related-content',
        recommendationKey: '13e42d76-6cd8-4059-89f6-ceedb3f1e775',
        customUniqueIdentifier: "$uniqeId",
        autoIngestion: true, 
        styleClasses: "bib--hover bib--row-3"
    });
})();
JS;
$this->registerJs($js);

