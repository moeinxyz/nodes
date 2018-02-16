<?php
use app\modules\user\Module;
$title      =   Module::t('following','followers.head.title',['name'=>$user->getName()]);
$description=   Module::t('following','meta._followers.description',['name'=>$user->getName(),'description'=>$user->getTagLine()]);

$this->registerMetaTag(['name'=>'description','content'=>$description]);
$this->registerMetaTag(['name'=>'keywords','content'=>'']);

$this->registerMetaTag(['itemprop'=>'name','content'=>$title]);
$this->registerMetaTag(['itemprop'=>'isFamilyFriendly','content'=>'true']);
$this->registerMetaTag(['itemprop'=>'description','content'=>$description]);

// Twitter Card Data
$this->registerMetaTag(['name'=>'twitter:card','content'=>'summary']);
$this->registerMetaTag(['name'=>'twitter:site','content'=>Yii::$app->params['twitterHandler']]);
$this->registerMetaTag(['name'=>'twitter:title','content'=>$title]);
$this->registerMetaTag(['name'=>'twitter:description','content'=>  $description]);
//@todo add author twitter handler

// Open Graph Data
$this->registerMetaTag(['name'=>'og:type','content'=>'website']);
$this->registerMetaTag(['name'=>'og:title','content'=>$title]);
$this->registerMetaTag(['name'=>'og:description','content'=>$description]);
$this->registerMetaTag(['name'=>'og:url','content'=> Yii::$app->urlManager->createAbsoluteUrl("/{$user->getUsername()}/followers")]);
