<?php
use yii\helpers\Html;
/* @var $this \yii\web\View */
/* @var $content string */

\app\assets\MainAssets::register($this);
$lang   = Yii::$app->language;
$js     = <<<JS
var RecaptchaOptions = {
   lang : '{$lang}',
};                
JS;
$this->registerJs($js);
$this->registerMetaTag(['name'=>'og:site_name','content'=>Yii::t('app','title')]);
$this->registerLinkTag(['rel'=>'shortcut icon','href'=>Yii::$app->urlManager->baseUrl.'/favicon.ico','type'=>'image/x-icon']);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0"/>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">        
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body class="skin-blue">
        <?php $this->beginBody() ?>
            <div class="loadstack">
                <div class="spinner-loading"></div>
            </div>
            <?php   
                $actionId   =   Yii::$app->controller->action->getUniqueId();
                if ($actionId === 'post/post/edit' || $actionId === 'post/post/view'){
                    echo $content;
                } else {
                    echo $this->render('header');
                    echo '<div id="wrapper" class="page-content">'.$content.'</div>';
                }
            ?>
            <?= $this->render('footer') ?>

            <?= (Yii::$app->user->isGuest)?$this->render('headers/login'):NULL; ?>
        <?php $this->endBody() ?>
        <script>
//          (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
//          (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
//          m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
//          })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
//          ga('create', 'UA-38759523-10', 'auto');
//          ga('send', 'pageview');
        </script>
    </body>    
</html>
<?php $this->endPage() ?>

