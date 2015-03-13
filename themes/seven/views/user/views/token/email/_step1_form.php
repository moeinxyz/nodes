<?php 
use app\modules\user\Module;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\modules\user\models\Token */


$pjax   = Pjax::begin(['enablePushState'=>FALSE]);
$form = ActiveForm::begin([
    'id'                    => 'email-form',
    'options'               => ['class'   =>  'form-horizontal','data-pjax'=>true],
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
                    <?= $form->field($model,'email')
                            ->textInput(['placeholder'=> Module::t('token', 'chgEmail.attr.email'),'style'=>'direction: ltr;']); ?>
                    <span class="glyphicon glyphicon-envelope form-control-feedback" style="right: 5px;"></span>                        
                </div>
            </div>
        </div>        
        <div class="form-group">
            <div class="controls">
                <div class="col-md-12 controls">
                    <?= Html::submitButton(Module::t('token','email.changeEmailBtn'), ['class' => 'btn btn-primary btn-block','name'=>'type','value'=>'email']) ?>
                </div>
            </div>            
        </div>
    </div>
</div>
<?php 
ActiveForm::end();
Pjax::end();
$js         =   <<<JS
var pjaxDiv =   $("#{$pjax->getId()}");
pjaxDiv.on('pjax:send',function(){
    pjaxDiv.append('<div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>');
});
pjaxDiv.on('pjax:complete',function(){
    pjaxDiv.find('.overlay').remove();
});
JS;
$this->registerJs($js);
?>

