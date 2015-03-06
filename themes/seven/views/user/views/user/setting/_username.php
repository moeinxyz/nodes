<?php 
use app\modules\user\Module;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\modules\user\models\ChangeUsernameForm */
$form = ActiveForm::begin([
    'id'                    => 'username-form',
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
        <div class="form-group has-feedback">
            <div class="controls">
                <div class="col-md-12 controls">
                    <?= $form->field($model,'username')
                            ->textInput(['placeholder'=> Module::t('user', 'chgUsername.attr.username'),'style'=>'direction: ltr;'])
                            ->hint(Module::t('user', 'setting._username.username.hint')); ?>

                    <span class="glyphicon glyphicon-user form-control-feedback" style="right: 5px;"></span>                        
                </div>
            </div>
        </div>        
        <div class="form-group">
            <div class="controls">
                <div class="col-md-12 controls">
                    <?= Html::hiddenInput('type', 'username');?>
                    <?= Html::submitButton(Module::t('user','username.changeUsernameBtn'), ['class' => 'btn btn-primary btn-block']) ?>
                </div>
            </div>            
        </div>
    </div>
</div>

<?php ActiveForm::end();?>
