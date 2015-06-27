<?php  use app\modules\user\Module; use yii\grid\GridView;?>
<div class="top-buffer"></div>
<!--public form-->
<div class="row">   
    <div class="col-md-10 col-md-offset-1 col-xs-12">
        <div class="box box-primary">
            <div class="box-header text-center">
                <?= Module::t('user', 'profile.public.header'); ?>
            </div>
            
            <div class="box-body">
                <?php if (Yii::$app->session->getFlash('user.profile.public.successful')): ?>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="alert alert-success">
                                <h4><i class="icon fa fa-check"></i><?= Module::t('user','profile._public.successful.header'); ?></h4> 
                            </div>
                        </div>
                    </div>
                <?php endif; ?>                
                <hr class="mini central">
                <?= $this->render('profile/_public',['model'=>$public,'user'=>$user]);?>    
            </div>
        </div>
    </div>    
</div>

<!--url form-->
<div class="row">   
    <div class="col-md-10 col-md-offset-1 col-xs-12">
        <div class="box box-primary">
            <div class="box-header text-center">
                <?= Module::t('user', 'profile.urls.header'); ?>
            </div>
            <div class="box-body">
                <?= $this->render('profile/_url',['urls'=>$urls,'newUrl'=>$newUrl]);?>             
            </div>
        </div>
    </div>    
</div>