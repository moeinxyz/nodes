<?php use app\modules\user\Module; ?>
<div class="top-buffer"></div>
<div class="row">   
    <div class="col-xs-12 col-sm-12 col-md-12 col-md-8 col-lg-offset-2">
        <div class="box box-primary">
            <div class="box-header text-center">
                <?= Module::t('token', 'email.header'); ?>
            </div>
            <div class="box-body">
                <div class="box box-solid bg-light-blue small-font-size">
                    <div class="box-body">
                      <?= Module::t('token', 'email.help.body'); ?>
                    </div>
                </div>                
                <hr class="mini central">
                <?= $this->render('_step1_form',['model'=>$model,'error'=>false]); ?>    
            </div>
        </div>
    </div>    
</div>