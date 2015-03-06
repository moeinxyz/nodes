<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\modules\user\Module;
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\User */
$form = ActiveForm::begin([
    'id' => 'public-form',
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
                    <?= $form->field($model,'name')->textInput(['placeholder'=> Module::t('user', 'user.attr.name')]); ?>
                    <span class="glyphicon glyphicon-envelope form-control-feedback" style="right: 5px;"></span>                        
                </div>
            </div>
        </div>
        <div class="form-group has-feedback">
            <div class="controls">
                <div class="col-md-12 controls">
                    <?= $form->field($model,'tagline')->textInput(['placeholder'=> Module::t('user', 'user.attr.tagline')]); ?>
                    <span class="glyphicon glyphicon-envelope form-control-feedback" style="right: 5px;"></span>                        
                </div>
            </div>
        </div>        
        <div class="form-group">
            <div class="controls">
                <div class="col-md-12 controls">
                    <?= Html::submitButton(Module::t('user','join.joinBtn'), ['class' => 'btn btn-primary btn-block', 'name' => 'public-button']) ?>
                </div>
            </div>            
        </div>
    </div>
</div>

<?php ActiveForm::end();?>