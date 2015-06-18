<?php use app\modules\user\Module; use yii\helpers\Html; ?>
<div class="top-buffer"></div>
<?php if (Yii::$app->session->getFlash('user.join.successful')): ?>
    <div class="row">
        <div class="col-md-10 col-md-offset-1 col-xs-12">
            <div class="alert alert-success">
                <h4><i class="icon fa fa-check"></i><?= Module::t('user','join.success_join.header'); ?></h4> 
                <p><?= Module::t('user','join.success_join.text'); ?></p>
                <p><?= Html::a(Module::t('user','join.resend_activation.text'),  Yii::$app->urlManager->createUrl(['user/activation']));?></p>
            </div>            
        </div>
    </div>
<?php endif; ?>

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-5 col-lg-offset-1">
        <div class="box box-primary">
            <div class="box-header text-center"><?= Module::t('user', 'join.socialHeader'); ?></div>
            <div class="box-body">
                <hr class="mini central">
                <a href="<?= Yii::$app->urlManager->createUrl(['user/auth','authclient'=>'google']) ?>" target="_blank" class="btn btn-block btn-social btn-google-plus">
                    <i class="fa fa-google-plus"></i><?= Module::t('user', 'join.byGoogle'); ?>
                </a>
                <a href="<?= Yii::$app->urlManager->createUrl(['user/auth','authclient'=>'facebook']) ?>" target="_blank" class="btn btn-block btn-social btn-facebook">
                  <i class="fa fa-facebook"></i><?= Module::t('user', 'join.byFacebook'); ?>
                </a>
                <a href="<?= Yii::$app->urlManager->createUrl(['user/auth','authclient'=>'twitter']) ?>" target="_blank" class="btn btn-block btn-social btn-twitter">
                  <i class="fa fa-twitter"></i><?= Module::t('user', 'join.byTwitter'); ?>
                </a>        
                <a href="<?= Yii::$app->urlManager->createUrl(['user/auth','authclient'=>'linkedin']) ?>" target="_blank" class="btn btn-block btn-social btn-linkedin">
                  <i class="fa fa-linkedin"></i><?= Module::t('user', 'join.byLinkedin'); ?>
                </a>        
                <a  href="<?= Yii::$app->urlManager->createUrl(['user/auth','authclient'=>'github']) ?>" target="_blank" class="btn btn-block btn-social btn-github">
                  <i class="fa fa-github"></i><?= Module::t('user', 'join.byGithub'); ?>
                </a>
                <br>
                <p class="small-font-size">
                    <?= Html::a(Module::t('user','join.terms'),  Yii::$app->urlManager->createUrl(['page/terms']),['target'=>'_blank']);?>
                </p>                
            </div>
        </div>
    </div>    
    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-5">
        <div class="box box-primary">
            <div class="box-header text-center">
                <?= Module::t('user', 'join.commonHeader'); ?>
            </div>
            <div class="box-body">
                <hr class="mini central">
                    <?= $this->render('_join_form',['model'=>$model]); ?>    
            </div>
        </div>
    </div>    
</div>