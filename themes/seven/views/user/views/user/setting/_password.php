<?php 
use app\modules\user\Module;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\modules\user\models\ChangePasswordForm */
$form = ActiveForm::begin([
    'id'                    => 'password-form',
    'enableAjaxValidation'  => true,
    'enableClientValidation'=> true,
    'options'               => ['class'   =>  'form-horizontal'],
    'fieldConfig' => [
        'template'  =>  '{input}{error}'
    ],
]); 
?>
<div class="row">
    <div class="col-md-12">
        <?php if ($model->getScenario() === 'notOauthUser'): ?>
            <div class="form-group has-feedback">
                <div class="controls">
                    <div class="col-md-12 controls">
                        <?= $form->field($model,'password')
                                ->passwordInput(['placeholder'=> Module::t('user', 'chgPwd.attr.password'),'style'=>'direction: ltr;']); ?>

                        <span class="glyphicon glyphicon-lock form-control-feedback" style="right: 5px;"></span>                        
                    </div>
                </div>
            </div>                            
        <?php endif;?>                            
        <div class="form-group has-feedback">
            <div class="controls">
                <div class="col-md-12 controls">
                    <?= $form->field($model,'newPassword')
                            ->passwordInput(['placeholder'=> Module::t('user', 'chgPwd.attr.newPassword'),'style'=>'direction: ltr;']); ?>

                    <span class="glyphicon glyphicon-lock form-control-feedback" style="right: 5px;"></span>                        
                </div>
            </div>
        </div>        
        <div class="form-group has-feedback">
            <div class="controls">
                <div class="col-md-12 controls">
                    <?= $form->field($model,'confirmNewPassword')
                            ->passwordInput(['placeholder'=> Module::t('user', 'chgPwd.attr.confirmNewPassword'),'style'=>'direction: ltr;']); ?>

                    <span class="glyphicon glyphicon-lock form-control-feedback" style="right: 5px;"></span>                        
                </div>
            </div>
        </div>                                    
        <div class="form-group">
            <div class="controls">
                <div class="col-md-12 controls">
                    <?= Html::hiddenInput('type', 'password');?>
                    <?= Html::submitButton(Module::t('user','password.changePasswordBtn'), ['class' => 'btn btn-primary btn-block']) ?>
                </div>
            </div>            
        </div>
    </div>
</div>

<?php ActiveForm::end();?>
