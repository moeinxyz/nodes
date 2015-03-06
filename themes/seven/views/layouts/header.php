<header class="main-header">
    <nav class="navbar navbar-fixed-top navbar-default" role="navigation">
    <?= (Yii::$app->user->isGuest)?$this->render('headers/guest'):$this->render('headers/user'); ?>
  </nav>
</header>    