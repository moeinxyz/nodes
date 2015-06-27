<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\modules\user\Module;
use app\modules\user\models\PublicProfileForm;
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\modules\user\models\PublicProfileForm */
$this->registerAssetBundle('iCheck');
$this->registerAssetBundle('jasny-bootstrap');
$form = ActiveForm::begin([
    'id' => 'public-form',
    'options' => ['class'   =>  'form-horizontal','enctype' => 'multipart/form-data'],
    'fieldConfig' => [
        'template'  =>  '{input}{error}'
    ],
]); 
?>
<div class="row">
    <div class="col-md-12">
        <div class="form-group has-feedback">
            <div class="controls">
                <div class="col-md-12 controls">
                    <?= $form->field($model,'name')->textInput(['placeholder'=> Module::t('user', 'user.attr.name')]); ?>
                    <span class="glyphicon glyphicon-user form-control-feedback" style="right: 5px;"></span>                        
                </div>
            </div>
        </div>
        <div class="form-group has-feedback">
            <div class="controls">
                <div class="col-md-12 controls">
                    <?= $form->field($model,'tagline')->textInput(['placeholder'=> Module::t('user', 'user.attr.tagline')]); ?>
                    <span class="glyphicon glyphicon-align-justify form-control-feedback" style="right: 5px;"></span>                        
                </div>
            </div>
        </div>        
        <div class="form-group">
            <label class="col-sm-4 col-lg-3 hidden-lg hidden-md hidden-sm control-label"><?= Module::t('user', 'publicProfileForm.attr.profilePicture'); ?></label>
            <div class="col-sm-8 col-lg-9 controls">
                <div class="col-md-12 controls">
                    <div class="fileinput fileinput-new" data-provides="fileinput">
                      <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;">
                          <img src="<?= $user->getProfilePicture(); ?>" data-src="<?= $user->getProfilePicture(); ?>" alt="<?= Yii::$app->user->getIdentity()->getName(); ?>">
                      </div>
                      <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;"></div>
                      <div>
                        <span class="btn btn-default btn-file">
                            <span class="fileinput-new" style="z-index: 0"><?= Module::t('user', 'profile._public.image.select'); ?></span>
                            <span class="fileinput-exists" style="z-index: 0"><?= Module::t('user', 'profile._public.image.change'); ?></span>
                            <?= Html::activeFileInput($model,'profilePicture');?>
                        </span>
                        <a href="#" class="btn btn-default fileinput-exists" data-dismiss="fileinput"  style="z-index: 0" ><?= Module::t('user', 'profile._public.image.remove'); ?></a>
                        <?= Html::error($model, 'profilePicture',['class'=>'help-block help-block-error small-font-size','style'=>'color:#da4453;']); ?>
                        <?= ($model->activeProfilePictureStatus == true)?$form->field($model,'profileStatus')->inline()->radioList(PublicProfileForm::getProfilePictureStatus())->label(false):'';?>
                      </div>
                    </div>                    
                </div>
            </div>                
            <label class="col-xs-4 col-lg-3 hidden-xs control-label"><?= Module::t('user', 'publicProfileForm.attr.profilePicture'); ?></label>
        </div>
        <div class="form-group">
            <label class="col-sm-4 col-lg-3 hidden-lg hidden-md hidden-sm control-label"><?= Module::t('user', 'publicProfileForm.attr.coverPicture'); ?></label>
            <div class="col-sm-8 col-lg-9 controls">
                <div class="col-md-12 controls">
                    <div class="fileinput fileinput-new" data-provides="fileinput">
                        <div class="fileinput-new thumbnail" style="width: 300px; height: 130px;">
                            <img src="<?= $user->getCoverPicture(); ?>" data-src="<?= $user->getProfilePicture(); ?>" alt="<?= Yii::$app->user->getIdentity()->getName(); ?>">
                        </div>
                        <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;"></div>
                        <div>
                            <span class="btn btn-default btn-file">    
                                <span class="fileinput-new" style="z-index: 0"><?= Module::t('user', 'profile._public.image.select'); ?></span>
                                <span class="fileinput-exists"  style="z-index: 0"><?= Module::t('user', 'profile._public.image.change'); ?></span>
                                <?= Html::activeFileInput($model,'coverPicture');?>
                            </span>
                            <a href="#" class="btn btn-default fileinput-exists" data-dismiss="fileinput"  style="z-index: 0" ><?= Module::t('user', 'profile._public.image.remove'); ?></a>
                            <?= Html::error($model, 'coverPicture',['class'=>'help-block help-block-error small-font-size','style'=>'color:#da4453;']); ?>
                            <?= ($model->activeCoverPictureStatus == true)?$form->field($model,'coverStatus')->inline()->radioList(PublicProfileForm::getCoverPictureStatus())->label(false):'';?>
                        </div>
                    </div>                    
                </div>
            </div>                
            <label class="col-xs-4 col-lg-3 hidden-xs control-label"><?= Module::t('user', 'publicProfileForm.attr.coverPicture'); ?></label>
        </div>        
        <div class="form-group">
            <div class="controls">
                <div class="col-md-12 controls">
                    <?= Html::submitButton(Module::t('user','profile._public.saveBtn'), ['class' => 'btn btn-primary btn-block', 'name' => 'public-button']) ?>
                </div>
            </div>            
        </div>
    </div>
</div>

<?php 
ActiveForm::end();
$js=<<<JS
$('.fileinput').fileinput()     
$('input[type="radio"]').each(function(){
    var labelDom = $(($(this).parent())[0])[0];
    var labelTxt = (labelDom.childNodes[1]);
    labelDom.childNodes[1].remove();
    $(this).iCheck({
        radioClass: 'iradio_line-blue',
        insert: '<div class="icheck_line-icon"></div>' + labelTxt.data                 
    });        
});                
        
JS;
$this->registerJs($js);
?>