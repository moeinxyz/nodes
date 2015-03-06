<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use \app\modules\user\Module;
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
$form = ActiveForm::begin([
    'id'        => 'email-step1-form',
    'options'   => ['class'   =>  'form-horizontal'],
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
                    <div class="form-group">
                        <?= Html::textInput('email',$email,['style'=>'direction: ltr;','class'=>'form-control','disabled'=>true]); ?>
                    </div>
                    <span class="glyphicon glyphicon-envelope form-control-feedback" style="right: 5px;"></span>                        
                </div>
            </div>
        </div>        
        <div class="form-group">
            <div class="controls">
                <div class="col-md-12 controls">
                    <?= Html::submitButton(Module::t('user','email.requestBtn'), ['class' => 'btn btn-primary btn-block', 'name' => 'type','value'=>'email']) ?>
                </div>
            </div>            
        </div>        
    </div>
    <!--<div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>-->
</div>
<?php ActiveForm::end();?>