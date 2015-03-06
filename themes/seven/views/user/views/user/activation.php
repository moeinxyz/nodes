<?php use app\modules\user\Module; ?>
<div class="top-buffer"></div>
<?php if (Yii::$app->session->getFlash('token.activation.expired')): ?>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-md-8 col-lg-offset-2">
            <div class="alert alert-error">
                <h4><i class="icon fa fa-warning"></i><?= Module::t('user','activation.expired_token.header'); ?></h4> 
                <?= Module::t('user','activation.expired_token.text'); ?>
            </div>
        </div>
    </div>
<?php elseif (Yii::$app->session->getFlash('user.activation.successful')): ?>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-md-8 col-lg-offset-2">
            <div class="alert alert-success">
                <h4><i class="icon fa fa-check"></i><?= Module::t('user','activation.sent.header'); ?></h4> 
                <?= Module::t('user','activation.sent.text'); ?>
            </div>
        </div>
    </div>
<?php endif; ?>

<div class="row">   
    <div class="col-xs-12 col-sm-12 col-md-12 col-md-8 col-lg-offset-2">
        <div class="box box-primary">
            <div class="box-header text-center">
                <?= Module::t('user', 'activation.header'); ?>
            </div>
            <div class="box-body">
                <hr class="mini central">
                    <?= $this->render('_activation_form',['model'=>$model]); ?>    
            </div>
        </div>
    </div>    
</div>