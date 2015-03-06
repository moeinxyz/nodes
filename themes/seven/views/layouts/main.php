<?php
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;

/* @var $this \yii\web\View */
/* @var $content string */

$this->registerAssetBundle('Main');
$lang   = Yii::$app->language;
$js     = <<<JS
var RecaptchaOptions = {
   lang : '{$lang}',
};                
JS;
$this->registerJs($js);

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0"/>
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body class="skin-blue">
        <?php $this->beginBody() ?>
            <div class="loadstack">
                <div class="spinner"></div>
            </div>        
            <?= $this->render('header') ?>   
            <div id="wrapper" class="page-content">
                <?= $content ?>
            </div>
            <?= $this->render('footer') ?>
            
            <?= (Yii::$app->user->isGuest)?$this->render('headers/login'):NULL; ?>
        <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>

