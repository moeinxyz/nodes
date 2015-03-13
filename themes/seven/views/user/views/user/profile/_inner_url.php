<?php 
use app\modules\user\Module;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\modules\user\models\Url;
 /**
  * currently font-awesome doesn't support feedback
  * you should wait for it to fix or bought glyphicons pro version
  */
$this->registerAssetBundle('bootstrap-switch');
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $url app\modules\user\models\Url */
/* @var $newUrl app\modules\user\models\Url */
if (Yii::$app->session->getFlash('user.url.add.successful')): ?>
    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-success">
                <h4><i class="icon fa fa-check"></i><?= Module::t('user','profile._url.add.successful.header'); ?></h4> 
            </div>
        </div>
    </div>
<?php 
endif;
$form = ActiveForm::begin([
    'id'                    => 'url-form',
    'enableAjaxValidation'  => true,
    'enableClientValidation'=> true,
    'options'               => ['class'   =>  'form-horizontal','data-pjax'=>true],
    'fieldConfig' => [
        'template'  =>  '{input}{error}'
    ],
]); 
?>
<hr class="mini central">
<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <div class="controls">
                <div class="col-md-12 controls">
                    <?= $form->field($newUrl, "url")->textInput(['style'=>'direction: ltr;']); ?>        
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="controls">
                <div class="col-md-12 controls">
                    <?= Html::submitButton(Module::t('user','profile.addUrl'), ['class' => 'btn btn-primary btn-block','name'=>'url-add']) ?>
                </div>
            </div>            
        </div>
    </div>
</div>

<?php
ActiveForm::end();
$form = ActiveForm::begin([
    'id'                    => 'urls-form',
    'enableAjaxValidation'  => true,
    'enableClientValidation'=> true,
    'action'                =>  Yii::$app->urlManager->createUrl(['user/user/profile#urls-form']),
    'options'               => ['class'   =>  'form-horizontal'],
    'fieldConfig' => [
        'template'  =>  '{input}{error}'
    ],
]); 
?>
<hr class="mini central">
<div class="box box-solid bg-teal small-font-size">
    <div class="box-body">
        <?= Module::t('user', 'profile.url.help.body'); ?>
    </div>
</div>                
<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <?php foreach ($urls as $index => $url): ?>
                    <div class="controls">
                        <div class="col-xs-12 col-sm-8 col-md-9 controls">
                            <?= $form->field($url, "[$index]url")->textInput(['style'=>'direction: ltr;']); ?>        
                        </div>
                        <div class="col-xs-12 col-sm-4 col-md-3 controls">
                            <?= $form->field($url, "[$index]status")
                                    ->checkbox([
                                        'value'     =>  Url::STATUS_ACTIVE,
                                        'uncheck'   =>  Url::STATUS_DEACTIVE,
                                        'template'  =>  '{input}'
                                    ])->label(FALSE); ?>                        
                        </div>
                    </div>
            <?php endforeach;?>
        </div>        
        <div class="form-group">
            <div class="controls">
                <div class="col-md-12 controls">
                    <?= Html::submitButton(Module::t('user','profile.updateUrls'), ['class' => 'btn btn-primary btn-block','name'=>'urls-update']) ?>
                </div>
            </div>            
        </div>
    </div>
</div>

<?php  ActiveForm::end(); ?>