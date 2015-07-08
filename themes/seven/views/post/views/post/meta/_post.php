<?php
use yii\helpers\StringHelper;
use app\modules\post\models\Post;
use app\modules\post\Module;
use yii\helpers\HtmlPurifier;
/* @var $this \yii\web\View */
/* @var $post app\modules\post\models\Post */
/* @var $author app\modules\user\models\User */
$author         =   $post->user;

$title          =   HtmlPurifier::process($post->title);
$description    =   HtmlPurifier::process(StringHelper::truncate($post->pure_text, 200));
$authorName     =   HtmlPurifier::process($author->getName());

$this->registerLinkTag(['rel'=>'alternate','type'=>'application/rss+xm','title'=>$authorName,'href'=>Yii::$app->urlManager->createAbsoluteUrl(["{$author->getUsername()}/rss"])]);

$this->registerMetaTag(['name'=>'description','content'=>$title]);
$this->registerMetaTag(['name'=>'author','content'=>$authorName]);

// Twitter Card Data
$this->registerMetaTag(['name'=>'twitter:card','content'=>'summary']);
$this->registerMetaTag(['name'=>'twitter:site','content'=>Yii::$app->params['twitterHandler']]);
$this->registerMetaTag(['name'=>'twitter:title','content'=>$title]);
$this->registerMetaTag(['name'=>'twitter:description','content'=>  $description]);
//@todo add author twitter handler

// Open Graph Data
$this->registerMetaTag(['name'=>'og:type','content'=>'article']);
$this->registerMetaTag(['name'=>'og:title','content'=>$title]);
$this->registerMetaTag(['name'=>'og:description','content'=>$description]);
$this->registerMetaTag(['name'=>'og:url','content'=>Yii::$app->urlManager->createAbsoluteUrl(["{$author->getUsername()}/{$post->url}"])]);

if ($post->cover === Post::COVER_BYCOVER){
    $cover    =   Post::getCoverUrl($post->id);
    $this->registerMetaTag(['name'=>'twitter:image','content'=>$cover]);
    $this->registerMetaTag(['name'=>'og:image','content'=>$cover]);
}