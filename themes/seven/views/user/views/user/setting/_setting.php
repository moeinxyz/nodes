<?php 
use app\modules\user\Module;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\modules\user\models\ChangeSettingForm;
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\modules\user\models\ChangeSettingForm */

app\assets\ICheckAssets::register($this);
$form = ActiveForm::begin([
    'id'                    => 'password-form',
    'enableAjaxValidation'  => true,
    'enableClientValidation'=> true,
    'options'               => ['class'   =>  'form-horizontal'],
    'fieldConfig' => [
        'template'  =>  '{label}{input}'
    ],
]);
?>
<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <div  class="col-md-12 controls ichecked medium-font-size">
                <?= $form->field($model,'reading_list')->inline()->radioList(ChangeSettingForm::getReadingListSettingList())->hint(Module::t('user','setting._setting.reading_list.hint'));?>
            </div>
        </div>
        <div class="form-group">
            <div  class="col-md-12 controls ichecked medium-font-size">
                <?= $form->field($model,'content_activity')->inline()->radioList(ChangeSettingForm::getActivitySettingList())->hint(Module::t('user','setting._setting.content_activity.hint'));?>
            </div>
        </div>                    
        <?php
        /**
         * Comming Soon
   
        <div class="form-group">
            <div  class="col-md-12 controls ichecked medium-font-size">
                <?= $form->field($model,'social_activity')->inline()->radioList(ChangeSettingForm::getActivitySettingList())->hint(Module::t('user','setting._setting.social_activity.hint'));?>
            </div>
        </div> 
         * 
         */
        ?>
        <div class="form-group">
            <div class="controls">
                <div class="col-md-12 controls">
                    <?= Html::hiddenInput('type', 'setting');?>
                    <?= Html::submitButton(Module::t('user','setting.changeSettingBtn'), ['class' => 'btn btn-primary btn-block']) ?>
                </div>
            </div>            
        </div>
    </div>
</div>

<?php ActiveForm::end();?>

<?php
$js=<<<JS
$('.ichecked input[type="radio"]').each(function(){
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
