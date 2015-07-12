<?php 
use app\modules\user\Module;
use yii\widgets\Pjax;

\app\assets\BootstrapSwitchAssets::register($this);
$pjax = Pjax::begin(['enablePushState'=>FALSE]);
echo $this->render('_inner_url',['urls'=>$urls,'newUrl'=>$newUrl]);
Pjax::end();
$onText  = Module::t('user','profile._url.status.on');
$offText = Module::t('user','profile._url.status.off');
$js= <<<JS
var pjaxDiv =   $("#{$pjax->getId()}");
pjaxDiv.on('pjax:send',function(){
    pjaxDiv.append('<div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>');
});
pjaxDiv.on('pjax:complete',function(){
    pjaxDiv.find('.overlay').remove();
    $("#urls-form input[type=checkbox]").bootstrapSwitch({
        'onText':"$onText",
        'offText':"$offText"
    });
});
$("#urls-form input[type=checkbox]").bootstrapSwitch({
    'onText':"$onText",
    'offText':"$offText"
});
JS;
$this->registerJs($js);

?>