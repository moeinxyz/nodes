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
                <div class="spinner-loading"></div>
            </div>        
            <?= $this->render('header') ?>   
            <div id="wrapper" class="page-content">
                <?= $content ?>
            </div>
            <?= $this->render('footer') ?>
            
            <?= (Yii::$app->user->isGuest)?$this->render('headers/login'):NULL; ?>
        <?php $this->endBody() ?>
<!--     Piwik 
    <script type="text/javascript">
      var _paq = _paq || [];
      _paq.push(['trackPageView']);
      _paq.push(['enableLinkTracking']);
      (function() {
        var u="//t.nodes.ir/";
        _paq.push(['setTrackerUrl', u+'piwik.php']);
        _paq.push(['setSiteId', 1]);
        var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
        g.type='text/javascript'; g.async=true; g.defer=true; g.src=u+'piwik.js'; s.parentNode.insertBefore(g,s);
      })();
    </script>
    <noscript><p><img src="//t.nodes.ir/piwik.php?idsite=1" style="border:0;" alt="" /></p></noscript>
     End Piwik Code         -->
    </body>    
</html>
<?php $this->endPage() ?>

