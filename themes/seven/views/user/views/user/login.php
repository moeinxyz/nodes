<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\widgets\Pjax;
use \app\modules\user\Module;
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\modules\user\models\LoginForm */

$form = ActiveForm::begin([
    'id' => 'login-form',
    'options' => ['class'   =>  'form-horizontal','data-pjax' => true ],
    'enableClientValidation'=>false,
    'fieldConfig' => [
        'template'  =>  '{input}{error}'
    ],
]); 
?>

<div class="form-group has-feedback">
    <div class="controls">
        <div class="col-xs-12 col-sm-6 col-sm-offset-3 col-md-4 col-md-offset-4 col-lg-4 col-lg-offset-4 controls">
            <?= $form->field($model,'email')
                    ->textInput(['placeholder'=>Module::t('user','login.attr.email'),'style'=>'direction: ltr;']); ?>
            <span class="glyphicon glyphicon-envelope form-control-feedback" style="right: 5px;"></span>                        
        </div>
    </div>
</div>
<div class="form-group has-feedback">
    <div class="controls">
        <div class="col-xs-12 col-sm-6 col-sm-offset-3 col-md-4 col-md-offset-4 col-lg-4 col-lg-offset-4 controls">
            <?= $form->field($model,'password')
                    ->passwordInput(['placeholder'=>Module::t('user','login.attr.password'),'style'=>'direction: ltr;']); ?>
            <span class="glyphicon glyphicon-lock form-control-feedback" style="right: 5px;"></span>                        
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xs-12 col-sm-3 col-sm-offset-3 col-md-2 col-md-offset-4 col-lg-2 col-lg-offset-4">
        <?= Html::submitButton(Module::t('user','login.loginBtn'), ['class' => 'btn btn-primary btn-block', 'name' => 'login-button']) ?>
    </div>
    <div class="col-xs-12 col-sm-3 col-md-2 col-lg-2" style="direction: ltr;">
        <?= $form->field($model, 'rememberMe')
            ->checkbox();?>        
    </div>
</div>
<?php 
ActiveForm::end(); 
?>