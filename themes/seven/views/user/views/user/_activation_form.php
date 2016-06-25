<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\modules\user\Module;
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\User */
$form = ActiveForm::begin([
    'id' => 'activation-form',
    'options' => ['class'   =>  'form-horizontal'],
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
                    <?= $form->field($model,'username_or_email')
                            ->textInput(['placeholder'=> Module::t('user', 'activation.attr.username_or_email'),'style'=>'direction: ltr;']); ?>
                    <span class="glyphicon glyphicon-user form-control-feedback" style="right: 5px;"></span>                        
                </div>
            </div>
        </div>        
        <div class="form-group">
            <div class="controls">
                <div class="col-md-12 controls">
                    <?= $form->field($model, 'reCaptcha')->widget(
                        \himiklab\yii2\recaptcha\ReCaptcha::className(),
                        ['siteKey' => Yii::$app->reCaptcha->siteKey]
                    ) ?>    
                </div>                
            </div>
        </div>
        <div class="form-group">
            <div class="controls">
                <div class="col-md-12 controls">
                    <?= Html::submitButton(Module::t('user','activation.activationBtn'), ['class' => 'btn btn-primary btn-block', 'name' => 'join-button']) ?>
                </div>
            </div>            
        </div>
    </div>
</div>

<?php ActiveForm::end();?>