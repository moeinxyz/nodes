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
        <?php if (YII_ENV_PROD):?>
            <script type="text/javascript">
                var _paq = _paq || [];
                _paq.push(["setDomains", ["*.nodes.ir","*.nodes.ir"]]);
                _paq.push(['trackPageView']);
                _paq.push(['enableLinkTracking']);
                (function() {
                    var u="//stats.nodes.ir/";
                    _paq.push(['setTrackerUrl', u+'piwik.php']);
                    _paq.push(['setSiteId', 1]);
                    var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
                    g.type='text/javascript'; g.async=true; g.defer=true; g.src=u+'piwik.js'; s.parentNode.insertBefore(g,s);
                })();
            </script>
            <noscript><p><img src="//stats.nodes.ir/piwik.php?idsite=1" style="border:0;" alt="" /></p></noscript>
        <?php endif; ?>
    </body>    
</html>
<?php $this->endPage() ?>

