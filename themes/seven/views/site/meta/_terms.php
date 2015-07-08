<?php
$this->registerMetaTag(['name'=>'description','content'=>Yii::t('app','meta._terms.description')]);

// Twitter Card Data
$this->registerMetaTag(['name'=>'twitter:card','content'=>'summary']);
$this->registerMetaTag(['name'=>'twitter:site','content'=>Yii::$app->params['twitterHandler']]);
$this->registerMetaTag(['name'=>'twitter:title','content'=>Yii::t('app','meta._terms.title')]);
$this->registerMetaTag(['name'=>'twitter:description','content'=>  Yii::t('app','meta._terms.description')]);
//@todo add author twitter handler

// Open Graph Data
$this->registerMetaTag(['name'=>'og:type','content'=>'article']);
$this->registerMetaTag(['name'=>'og:title','content'=>Yii::t('app','meta._terms.title')]);
$this->registerMetaTag(['name'=>'og:description','content'=>Yii::t('app','meta._terms.description')]);
$this->registerMetaTag(['name'=>'og:url','content'=> yii\helpers\BaseUrl::home(true)]);
