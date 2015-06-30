<?php 
use app\modules\post\models\Post;
use app\modules\post\Module; 
use yii\helpers\StringHelper;
use app\modules\post\models\Userrecommend;

$author =   $post->getUser()->one();
$bold   =   ($user->id === $author->id && $post->pin === Post::PIN_ON)?true:false;
?>
<div class="box <?= ($bold===true)?'box-info':''; ?>">
    <div class="box-header">
        <?php
            $content    =   '<a href="'.Yii::$app->urlManager->createUrl("{$author->getUsername()}").'">';   
            $content    .=  '<img src="'.$author->getProfilePicture(36).'" alt="'.$author->getName().'" class="img-circle" style="width: 36px;height: 36px;margin-top: auto;">';
            $content    .=  '</a>';
            $content    .=  '<span class="small-font-size">';
            $content    .=  Module::t('post','_show_post_abstract.written_by',['time'=>Yii::$app->jdate->date("l jS F Y",strtotime($post->published_at))]);
            $content    .=  '<a href="'.Yii::$app->urlManager->createUrl(["{$author->getUsername()}"]).'">';
            $content    .=  $author->getName();
            $content    .=  '</a></span>';
            
            if (($counter = Userrecommend::countPostRecommends($post->id)) && $counter >= 1){
                $content    .=  '<span class="small-font-size">';
                $content    .=  Module::t('post','_show_post_abstract.recommended_by');
                // user couldn't recommend his own post
                if ($author->id != $user->id){
                    //show user recommeneded posts
                    $content    .=  '<a href="'.Yii::$app->urlManager->createUrl(["{$user->getUsername()}"]).'">';
                    $content    .=  $user->getName();
                    $content    .=  '</a>';
                    $content    .=  Module::t('post','_show_post_abstract.comma');
                    $counter--;//this user recommend it
                }
                $recommenders   =   Userrecommend::getPostRecommenders($post->id,3,$user->id);
                foreach ($recommenders as $index => $recommender){
                    if ($index >= 1){
                        //don't use comma for first one
                        $content    .=  Module::t('post','_show_post_abstract.comma');    
                    }
                    $content    .=  '<a href="'.Yii::$app->urlManager->createUrl(["{$recommender->getUsername()}"]).'">';
                    $content    .=  $recommender->getName();
                    $content    .=  '</a>';
                    $counter--;
                }                
                if ($counter >= 1){
                    $content    .=  Module::t('post','_show_post_abstract.recommended_by_some_more',['counter'=>$counter]);
                }                
                $content    .=  '</span>';
            }
            echo $content;
        ?>
    </div>
    <div class="box-body">
        <?php if ($post->cover === Post::COVER_BYCOVER):?>
            <div class="row">
                <div class="col-md-8">
                    <a href="<?= Yii::$app->urlManager->createUrl(["{$user->getUsername()}/{$post->url}"])?>">
                        <?= $post->title;?>
                    </a><br>
                    <span class="medium-font-size">
                        <?= StringHelper::truncateWords($post->pure_text, ($bold===true)?60:30); ?>
                    </span>
                </div>
                <div class="col-md-4">
                    <a href="<?= Yii::$app->urlManager->createUrl(["{$user->getUsername()}/{$post->url}"])?>">
                        <img src="<?= Post::getCoverUrl($post->id, true) ?>" alt="<?= $post->title;?>">
                    </a>
                </div>
            </div>
        <?php else : ?>
            <div class="row">
                <div class="col-md-12">
                    <a href="<?= Yii::$app->urlManager->createUrl(["{$user->getUsername()}/{$post->url}"])?>">
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