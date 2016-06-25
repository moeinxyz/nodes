<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\modules\user\Module;
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\User */
$form = ActiveForm::begin([
    'id' => 'join-form',
    'enableClientValidation'=>true,
    'enableAjaxValidation'  =>true,
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
                    <?= $form->field($model,'username')
                            ->textInput(['placeholder'=> Module::t('user', 'user.attr.username'),'style'=>'direction: ltr;']); ?>

                    <span class="glyphicon glyphicon-user form-control-feedback" style="right: 5px;"></span>                        
                </div>
            </div>
        </div>        
        <div class="form-group has-feedback">
            <div class="controls">
                <div class="col-md-12 controls">
                    <?= $form->field($model,'email')
                            ->textInput(['placeholder'=> Module::t('user', 'user.attr.email'),'style'=>'direction: ltr;']); ?>
                    <span class="glyphicon glyphicon-envelope form-control-feedback" style="right: 5px;"></span>                        
                </div>
            </div>
        </div>
        <div class="form-group has-feedback">
            <div class="controls">
                <div class="col-md-12 controls">
                    <?= $form->field($model,'password')
                            ->passwordInput(['placeholder'=> Module::t('user', 'user.attr.password'),'style'=>'direction: ltr;']); ?>
                    <span class="glyphicon glyphicon-lock form-control-feedback" style="right: 5px;"></span>                        
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
        <p class="small-font-size">
            <?= Html::a(Module::t('user','join.terms'),  Yii::$app->urlManager->createUrl(['page/terms']),['target'=>'_blank']);?>
        </p>
        <div class="form-group">
            <div class="controls">
                <div class="col-md-12 controls">
                    <?= Html::submitButton(Module::t('user','join.joinBtn'), ['class' => 'btn btn-primary btn-block', 'name' => 'join-button']) ?>
                </div>
            </div>            
        </div>
        <hr class="mini central">
        <p class="small-font-size">
            <?= Html::a(Module::t('user','join.activation'),  Yii::$app->urlManager->createUrl(['user/activation']));?>
            <br>
            <?= Html::a(Module::t('user','join.reset'),  Yii::$app->urlManager->createUrl(['user/reset']));?>
        </p>
    </div>
</div>

<?php ActiveForm::end();?>