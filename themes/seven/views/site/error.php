<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

$this->title    =   $name.' - '.Yii::t('app','title');
$bundle         =   \app\assets\MainAssets::register($this);
//var_dump($bundle);die;
?>

<section id="start" class="herofade hero cover element-img error-cover">
    <div class="tagline">
        <h1 class="wow fadeInUp animated" data-wow-delay="0.5s" style="visibility: visible; animation-delay: 0.5s; -webkit-animation-delay: 0.5s; animation-name: fadeInUp; -webkit-animation-name: fadeInUp;"><?= nl2br(Html::encode($message)) ?></h1>
        <h2 class="wow fadeInUp animated" data-wow-delay="0.7s" style="visibility: visible; animation-delay: 0.7s; -webkit-animation-delay: 0.7s; animation-name: fadeInUp; -webkit-animation-name: fadeInUp;">
            <a href="<?= Yii::$app->homeUrl; ?>" style="color: #005983"><?= Yii::t('app','errorpage_hint'); ?></a>
        </h2>
    </div>
</section>