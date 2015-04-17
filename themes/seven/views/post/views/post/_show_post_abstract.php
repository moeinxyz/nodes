<?php use app\modules\post\models\Post;use app\modules\post\Module; use yii\helpers\StringHelper;?>
<div class="box">
    <div class="box-header">
        <?php
            $author     =   $post->getUser()->one();
            $content    =   '<a href="'.Yii::$app->urlManager->createUrl("{$author->getUsername()}").'">';   
            $content    .=  '<img src="'.$author->getProfilePicture(36).'" alt="'.$author->getName().'" class="img-circle" style="width: 36px;height: 36px;margin-top: auto;">';
            $content    .=  '</a>';
            $content    .=  '<span class="small-font-size">';
            $content    .=  Module::t('post','_show_post_abstract.written_by',['time'=>Yii::$app->jdate->date("l jS F Y",strtotime($post->published_at))]);
            $content    .=  '<a href="'.Yii::$app->urlManager->createUrl(["{$author->getUsername()}"]).'">';
            $content    .=  $author->getName();
            $content    .=  '</a></span>';
            if ($author->id != $user->id){
                $content    .=  '<span class="small-font-size">';
                $content    .=  Module::t('post','_show_post_abstract.recommended_by');
                $content    .=  '<a href="'.Yii::$app->urlManager->createUrl(["{$user->getUsername()}"]).'">';
                $content    .=  $user->getName();
                $content    .=  '</a></span>';
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
                        <?= StringHelper::truncateWords(strip_tags($post->content), 30); ?>
                    </span>
                </div>
                <div class="col-md-4">
                    <a href="<?= Yii::$app->urlManager->createUrl(["{$user->getUsername()}/{$post->url}"])?>">
                        <img src="http://digiato.com/wp-content/uploads/2015/04/cclj-usw0aepnnd.jpg" alt="<?= $post->title;?>">
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
                        <?= StringHelper::truncateWords(strip_tags($post->content), 60); ?>
                    </span>
                </div>                    
            </div>
        <?php endif;?>
    </div>
</div>