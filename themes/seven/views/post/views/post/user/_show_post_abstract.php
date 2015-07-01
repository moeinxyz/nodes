<?php 

/**
 * For next developer or designer
 * I know it's so bad way for performance to open and close php tag several times,
 * But I respect cleaness more than ugly fast code.
 * So If you need orginal fast code,back to this view before 1,July,2015
 */
use app\modules\post\models\Post;
use app\modules\post\Module; 
use yii\helpers\StringHelper;
use app\modules\post\models\Userrecommend;

$author =   $post->getUser()->one();

// bold on author profile only
$bold   =   ($user->id === $author->id && $post->pin === Post::PIN_ON)?true:false;
?>
<div class="box <?= ($bold===true)?'box-info':''; ?>">
    <div class="box-header">
        <a href="<?= Yii::$app->urlManager->createUrl("{$author->getUsername()}"); ?>">
            <img src="<?= $author->getProfilePicture(36); ?>" alt="<?= $author->getName();?>" class="img-circle" style="width: 36px;height: 36px;margin-top: auto;">
        </a>
        <span class="small-font-size">
            <?= Module::t('post','_show_post_abstract.written_by',['time'=>Yii::$app->jdate->date("l jS F Y",strtotime($post->published_at))]); ?>
            <a href="<?= Yii::$app->urlManager->createUrl(["{$author->getUsername()}"]);?>">
                <?= $author->getName();?>
            </a>
        </span>
        <?php if (($counter = Userrecommend::countPostRecommends($post->id)) && $counter >= 1): ?>
            <span class="small-font-size">
                <?= Module::t('post','_show_post_abstract.recommended_by'); ?>
                <?php if ($author->id != $user->id):$counter--?>
                    <a href="<?= Yii::$app->urlManager->createUrl(["{$user->getUsername()}"]); ?>">
                        <?= $user->getName(); ?>
                    </a>
                    <?= Module::t('post','_show_post_abstract.comma'); ?>
                <?php endif; ?>
                <?php $recommenders   =   Userrecommend::getPostRecommenders($post->id,3,$user->id); ?>
                <?php foreach ($recommenders as $index => $recommender): ?>
                    <?php if($index >= 1):?>
                        <?= Module::t('post','_show_post_abstract.comma'); ?>
                    <?php endif;?>
                    <a href="<?= Yii::$app->urlManager->createUrl(["{$recommender->getUsername()}"]);?>">
                        <?= $recommender->getName(); ?>
                    </a>
                <?php $counter--; endforeach;?>
                <?php if($counter >= 1): ?>
                    <?= Module::t('post','_show_post_abstract.recommended_by_some_more',['counter'=>$counter]);?>
                <?php endif;?>
            </span>
        <?php endif; ?>
    </div>
    <div class="box-body">
        <?php if ($post->cover === Post::COVER_BYCOVER):?>
            <div class="row">
                <div class="col-md-8">
                    <a href="<?= Yii::$app->urlManager->createUrl(["{$author->getUsername()}/{$post->url}"])?>">
                        <?= $post->title;?>
                    </a><br>
                    <span class="medium-font-size">
                        <?= StringHelper::truncateWords($post->pure_text, ($bold===true)?60:30); ?>
                    </span>
                </div>
                <div class="col-md-4">
                    <a href="<?= Yii::$app->urlManager->createUrl(["{$author->getUsername()}/{$post->url}"])?>">
                        <img src="<?= Post::getCoverUrl($post->id, true) ?>" alt="<?= $post->title;?>">
                    </a>
                </div>
            </div>
        <?php else : ?>
            <div class="row">
                <div class="col-md-12">
                    <a href="<?= Yii::$app->urlManager->createUrl(["{$author->getUsername()}/{$post->url}"])?>">
                        <?= $post->title;?>
                    </a><br>
                    <span class="medium-font-size">
                        <?= StringHelper::truncateWords($post->pure_text, ($bold===true)?120:30); ?>
                    </span>
                </div>                    
            </div>
        <?php endif;?>
    </div>
</div>