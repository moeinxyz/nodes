<div class="top-buffer"></div>
<?php use app\modules\user\Module; ?>
<?php if (Yii::$app->session->getFlash('user.setting.setting')): ?>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-md-8 col-lg-offset-2">
            <div class="alert alert-success">
                <h4><i class="icon fa fa-check"></i><?= Module::t('user','activation.sent.header'); ?></h4> 
                <?= Module::t('user','activation.sent.text'); ?>
            </div>
        </div>
    </div>
<?php elseif (Yii::$app->session->getFlash('user.setting.password.successful')): ?>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-md-8 col-lg-offset-2">
            <div class="alert alert-success">
                <h4><i class="icon fa fa-check"></i><?= Module::t('user','setting.password.successful'); ?></h4> 
            </div>
        </div>
    </div>
<?php elseif (Yii::$app->session->getFlash('user.setting.email.successful')): ?>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-md-8 col-lg-offset-2">
            <div class="alert alert-success">
                <h4><i class="icon fa fa-check"></i><?= Module::t('user','setting.email.step1.successful.header'); ?></h4> 
                <p><?= Module::t('user','setting.email.step1.successful.text',['email'=>$email]); ?></p>
            </div>
        </div>
    </div>
<?php elseif (Yii::$app->session->getFlash('token.email.step2.successful')): ?>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-md-8 col-lg-offset-2">
            <div class="alert alert-success">
                <h4><i class="icon fa fa-check"></i><?= Module::t('user','setting.email.step2.successful.header'); ?></h4> 
                <p><?= Module::t('user','setting.email.step2.successful.text',['email'=>$email]); ?></p>
            </div>
        </div>
    </div>
<?php elseif (Yii::$app->session->getFlash('token.email.step2.failed')): ?>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-md-8 col-lg-offset-2">
            <div class="alert alert-error">
                <h4><i class="icon fa fa-warning"></i><?= Module::t('user','setting.email.step2.failed.header'); ?></h4> 
                <p><?= Module::t('user','setting.email,step2.failed.text'); ?></p>
            </div>
        </div>
    </div>
<?php elseif (Yii::$app->session->getFlash('token.email.expired')): ?>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-md-8 col-lg-offset-2">
            <div class="alert alert-error">
                <h4><i class="icon fa fa-warning"></i><?= Module::t('user','setting.email.expired.header'); ?></h4> 
                <?= Module::t('user','setting.email.expired.text'); ?>
            </div>
        </div>
    </div>
<?php elseif (Yii::$app->session->getFlash('user.setting.setting.successful')): ?>    
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-md-8 col-lg-offset-2">
            <div class="alert alert-success">
                <h4><i class="icon fa fa-check"></i><?= Module::t('user','setting.setting.successful.header'); ?></h4> 
            </div>
        </div>
    </div>
<?php elseif (Yii::$app->session->getFlash('user.setting.username.successful')): ?>    
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-md-8 col-lg-offset-2">
            <div class="alert alert-success">
                <h4><i class="icon fa fa-check"></i><?= Module::t('user','setting.username.successful.header'); ?></h4> 
                <?= Module::t('user','setting.username.successful.text',[
                    'username'  =>  $usernameModel->username,
                    'link'      =>  Yii::getAlias('@profile')."/@{$usernameModel->username}"]); ?>                
            </div>
        </div>
    </div>
<?php endif; ?>


<!--setting form-->
<div class="row">   
    <div class="col-xs-12 col-sm-12 col-md-12 col-md-8 col-lg-offset-2">
        <div class="box box-primary">
            <div class="box-header text-center">
                <?= Module::t('user', 'setting.setting.header'); ?>
            </div>
            <div class="box-body">
                <hr class="mini central">
                <?= $this->render('setting/_setting',['model'=>$settingModel]); ?>    
            </div>
        </div>
    </div>    
</div>

<!--password form-->
<div class="row">   
    <div class="col-xs-12 col-sm-12 col-md-12 col-md-8 col-lg-offset-2">
        <div class="box box-primary">
            <div class="box-header text-center">
                <?= Module::t('user', 'setting.password.header'); ?>
            </div>
            <div class="box-body">
                <hr class="mini central">
                <?= $this->render('setting/_password',['model'=>$passwordModel]); ?>
            </div>
        </div>
    </div>    
</div>
<!--username form-->

<div class="row">   
    <div class="col-xs-12 col-sm-12 col-md-12 col-md-8 col-lg-offset-2">
        <div class="box box-primary">
            <div class="box-header text-center">
                <?= Module::t('user', 'setting.username.header'); ?>
            </div>
            <div class="box-body">
                <hr class="mini central">
                <?= $this->render('setting/_username',['model'=>$usernameModel]); ?>
            </div>
        </div>
    </div>    
</div>

<!--email form-->
<div class="row">   
    <div class="col-xs-12 col-sm-12 col-md-12 col-md-8 col-lg-offset-2">
        <div class="box box-primary">
            <div class="box-header text-center">
                <?= Module::t('user', 'setting.email.header'); ?>
            </div>
            <div class="box-body">
                <div class="box box-solid bg-teal small-font-size">
                    <div class="box-body">
                      <?= Module::t('user', 'setting.email.help.body'); ?>
                    </div>
                </div>                
                <hr class="mini central">
                <?= $this->render('setting/_email',['email'=>$email]); ?>    
            </div>
        </div>
    </div>    
</div>