<?php
use yii\helpers\HtmlPurifier;
use app\modules\post\Module;

/* @var $this \yii\web\View */
/* @var $user app\modules\user\models\User */

$title          =   HtmlPurifier::process($user->getName());
$description    =   HtmlPurifier::process($user->getTagLine());
$profilePicture =   $user->getProfilePicture();

$this->registerMetaTag(['name'=>'description','content'=>  Module::t('post','meta._user.description',['title'=>$title,'description'=>$description])]);
$this->registerMetaTag(['name'=>'author','content'=>$title]);

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
