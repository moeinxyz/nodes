<?php use app\modules\user\Module; ?>
<div class="row">
    <div class="col-md-12">
        <div class="alert alert-success">
            <h4><i class="icon fa fa-check"></i><?= Module::t('token','email.sent.header'); ?></h4> 
            <?= Module::t('token','email.sent.text',['email'=>$email]); ?>
        </div>
    </div>
</div>