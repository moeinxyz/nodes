<?php  use app\modules\user\Module; use yii\grid\GridView;?>
<div class="top-buffer"></div>
<?php if (Yii::$app->session->getFlash('user.profile.public.successful')): ?>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-md-8 col-lg-offset-2">
            <div class="alert alert-success">
                <h4><i class="icon fa fa-check"></i><?= Module::t('user','profile._public.successful.header'); ?></h4> 
            </div>
        </div>
    </div>
<?php endif; ?>
<!--public form-->
<div class="row">   
    <div class="col-xs-12 col-sm-12 col-md-12 col-md-8 col-lg-offset-2">
        <div class="box box-primary">
            <div class="box-header text-center">
                <?= Module::t('user', 'profile.public.header'); ?>
            </div>
            
            <div class="box-body">
                <hr class="mini central">
                    <?= $this->render('profile/_public',['model'=>$public]);?>    
            </div>
        </div>
    </div>    
</div>

<!--url form-->
<!--<div class="row">   
    <div class="col-xs-12 col-sm-12 col-md-12 col-md-8 col-lg-offset-2">
        <div class="box box-primary">
            <div class="box-header text-center">
                <?= Module::t('user', 'profile.urls.header'); ?>
            </div>
            <div class="box-body">
                <hr class="mini central">
                <?= $this->render('profile/_url',['urls'=>$urls]);?>             
            </div>
        </div>
    </div>    
</div>-->