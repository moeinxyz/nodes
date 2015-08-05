<?php use app\modules\post\Module; ?>
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-10 col-md-offset-1 col-lg-10 col-lg-offset-1">
        <div class="box box-info">
            <div class="box-header">
                <?= Module::t('post', 'home._signup_suggestion.title'); ?>
            </div>
            <div class="box-body">
                <?= Module::t('post', 'home._signup_suggestion.text'); ?>
                <div class="row hidden-xs">
                    <div class="col-sm-6 col-xs-12">
                        <a href="<?= Yii::$app->urlManager->createUrl(['user/join']); ?>" class="btn btn-flat btn-primary btn-block">
                            <?= Module::t('post', 'home._signup_suggestion.btn.register'); ?>
                        </a>
                    </div>                    
                    <div class="col-sm-6 col-xs-12">
                        <a href="#" class="btn btn-flat btn-primary btn-block" id="home-login-button">
                            <?= Module::t('post', 'home._signup_suggestion.btn.login'); ?>
                        </a>
                    </div>
                </div>
                <div class="row hidden-lg hidden-md hidden-sm">
                    <div class="col-xs-12">
                        <a href="#" class="btn btn-flat btn-primary btn-block" id="home-login-button">
                            <?= Module::t('post', 'home._signup_suggestion.btn.login'); ?>
                        </a>
                    </div>                    
                    <div class="col-xs-12 top-buffer-light">
                        <a href="<?= Yii::$app->urlManager->createUrl(['user/join']); ?>" class="btn btn-flat btn-primary btn-block">
                            <?= Module::t('post', 'home._signup_suggestion.btn.register'); ?>
                        </a>
                    </div>                    
                </div>
            </div>
        </div>        
    </div>
</div>
<?php
$js=<<<JS
$("#home-login-button").on('click',function(){
    $("#loginmodal").modal();
});
JS;
$this->registerJs($js);