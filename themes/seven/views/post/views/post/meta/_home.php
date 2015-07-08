<?php
use app\modules\post\Module;
$this->registerMetaTag(['name'=>'description','content'=>Module::t('post','meta._home.description')]);

// Twitter Card Data
$this->registerMetaTag(['name'=>'twitter:card','content'=>'summary']);
$this->registerMetaTag(['name'=>'twitter:site','content'=>Yii::$app->params['twitterHandler']]);
$this->registerMetaTag(['name'=>'twitter:title','content'=>Module::t('post','meta._home.title')]);
$this->registerMetaTag(['name'=>'twitter:description','content'=>  Module::t('post','meta._home.description')]);
//@todo add author twitter handler

// Open Graph Data
$this->registerMetaTag(['name'=>'og:type','content'=>'article']);
$this->registerMetaTag(['name'=>'og:title','content'=>Module::t('post','meta._home.title')]);
$this->registerMetaTag(['name'=>'og:description','content'=>Module::t('post','meta._home.description')]);
$this->registerMetaTag(['name'=>'og:url','content'=> yii\helpers\BaseUrl::home(true)]);
