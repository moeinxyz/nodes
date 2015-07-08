<?php
use app\modules\user\Module;
$this->registerMetaTag(['name'=>'description','content'=>Module::t('user','meta._join.description')]);

// Twitter Card Data
$this->registerMetaTag(['name'=>'twitter:card','content'=>'summary']);
$this->registerMetaTag(['name'=>'twitter:site','content'=>Yii::$app->params['twitterHandler']]);
$this->registerMetaTag(['name'=>'twitter:title','content'=>Module::t('user','meta._join.title')]);
$this->registerMetaTag(['name'=>'twitter:description','content'=>  Module::t('user','meta._join.description')]);
//@todo add author twitter handler

// Open Graph Data
$this->registerMetaTag(['name'=>'og:type','content'=>'article']);
$this->registerMetaTag(['name'=>'og:title','content'=>Module::t('user','meta._join.title')]);
$this->registerMetaTag(['name'=>'og:description','content'=>Module::t('user','meta._join.description')]);
$this->registerMetaTag(['name'=>'og:url','content'=> yii\helpers\BaseUrl::home(true)]);
