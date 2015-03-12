<?php 
use app\modules\user\Module;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\components\Helper\Icon;
use app\modules\user\models\Url;
//use dosamigos\switchinput\SwitchRadio;

$this->registerAssetBundle('bootstrap-switch');
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\modules\user\models\ChangePasswordForm */
$form = ActiveForm::begin([
    'id'                    => 'urls-form',
    'enableAjaxValidation'  => true,
    'enableClientValidation'=> false,
    'options'               => ['class'   =>  'form-horizontal'],
    'fieldConfig' => [
        'template'  =>  '{input}{error}'
    ],
]); 
?>
<div class="row">
    <div class="col-md-12">           

        <?php foreach ($urls as $index => $url){ ?>
            <div class="form-group has-feedback">
                <div class="controls">
                    <div class="col-xs-12 col-sm-9 col-md-9 col-lg-9 controls">
                        <?= $form->field($url, "[$index]url")->textInput(['style'=>'direction: ltr;']); ?>
                        <span class="<?= Icon::getBrandIconClass($url->type); ?> form-control-feedback" style="right: 5px;top: 10px;"></span>
                    </div>
                    <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3 controls">
                        <?= $form->field($url, "[$index]status")->checkbox(['value'=>Url::STATUS_ACTIVE,'uncheck'=>  Url::STATUS_DEACTIVE])->label(FALSE); ?>
                    </div>
                </div>
            </div>        
        <?php } ?>
        
        <div class="form-group">
            <div class="controls">
                <div class="col-md-12 controls">
                    <?= Html::submitButton(Module::t('user','profile.updateUrls'), ['class' => 'btn btn-primary btn-block']) ?>
                </div>
            </div>            
        </div>
    </div>
</div>

<?php 
ActiveForm::end();
$onText  = Module::t('user','profile._url.status.on');
$offText = Module::t('user','profile._url.status.off');
$js= <<<JS
$(document).ready(function(){
    $("#urls-form input[type=checkbox]").bootstrapSwitch({
        'onText':"$onText",
        'offText':"$offText"
    });    
});

function makeSwitchButton(){
    
}        
JS;
$this->registerJs($js);
?>