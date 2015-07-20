<?php
use app\modules\post\Module;

/* @var $this \yii\web\View */
/* @var $user app\modules\user\models\User */

$title          =   $user->getName();
$description    =   $user->getTagLine();
$profilePicture =   $user->getProfilePicture();

$this->registerLinkTag(['rel'=>'alternate','type'=>'application/rss+xm','title'=>$title,'href'=>Yii::$app->urlManager->createAbsoluteUrl(["{$user->getUsername()}/rss"])]);

$this->registerMetaTag(['name'=>'description','content'=>  Module::t('post','meta._user.description',['title'=>$title,'description'=>$description])]);
$this->registerMetaTag(['name'=>'keywords','content'=>'']);

$this->registerMetaTag(['itemprop'=>'name','content'=>$title]);
$this->registerMetaTag(['itemprop'=>'isFamilyFriendly','content'=>'true']);
$this->registerMetaTag(['itemprop'=>'description','content'=> Module::t('post','meta._user.description',['title'=>$title,'description'=>$description])]);

// Twitter Card Data
$this->registerMetaTag(['name'=>'twitter:card','content'=>'summary']);
$this->registerMetaTag(['name'=>'twitter:site','content'=>Yii::$app->params['twitterHandler']]);
$this->registerMetaTag(['name'=>'twitter:title','content'=>$title]);
$this->registerMetaTag(['name'=>'twitter:description','content'=>  $description]);
$this->registerMetaTag(['name'=>'twitter:image','content'=>$profilePicture]);
//@todo add author twitter handler

// Open Graph Data
$this->registerMetaTag(['name'=>'og:type','content'=>'article']);
$this->registerMetaTag(['name'=>'og:title','content'=>$title]);
$this->registerMetaTag(['name'=>'og:description','content'=>$description]);
$this->registerMetaTag(['name'=>'og:url','content'=>Yii::$app->urlManager->createAbsoluteUrl(["{$user->getUsername()}"])]);
$this->registerMetaTag(['name'=>'og:image','content'=>$profilePicture]);
