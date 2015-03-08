<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\modules\post\Module;
/* @var $this \yii\web\View */
/* @var $model app\modules\post\models\Post */
$this->registerAssetBundle('custom-editor');
?>
<div class="top-buffer"></div>

<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <?php
            $form = ActiveForm::begin([
                'id' => 'post-form',
                'enableClientValidation'=>  true,
                'enableAjaxValidation'  =>  true,
                'options' => ['class'   =>  'form-horizontal'],
                'fieldConfig' => [
                    'template'  =>  '{input}{error}'
                ],
            ]);        
        ?>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <div class="controls">
                        <div class="col-md-5 controls">
                            <?= $form->field($model,'url',[
                                'template'  =>  '<div id="url-block" class="input-group" style="direction: ltr; display: none;"><span class="input-group-addon transparent-input">http://nodes.ir/@moein7tl/</span>{input}{error}</div>'
                            ])->textInput(['class'=>'input-sm form-control transparent-input']); ?>
                        </div>                                                
                        <div class="col-md-7 controls">
                            <?= $form->field($model,'title')->textInput(['class'=>'input-lg form-control transparent-input','placeholder'=> Module::t('post','post.write.title.placeholder')]); ?>
                        </div>
                    </div>
                </div>
                <?= $form->field($model, 'content')->hiddenInput(); ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div id="editor"></div>
            </div>
        </div>        
        <?php ActiveForm::end();?>
    </div>
</div>

<?php
$id                 =   base_convert($model->id, 10, 36);
$autoSave           =   Yii::$app->urlManager->createUrl(["post/autosave/{$id}"]);
$uploadUrl          =   Yii::$app->urlManager->createUrl(["post/upload/{$id}"]);   
$urlSuggestionUrl   =   Yii::$app->urlManager->createUrl(["post/post/suggesturl",'id'=>$id]);
$oembedUrl          =   Yii::$app->urlManager->createUrl(["embed/embed/embed",'type'=>'oembed']).'&url=';
$extractUrl         =   Yii::$app->urlManager->createUrl(["embed/embed/embed",'type'=>'extract']).'&url=';
$js=<<<JS
var editor=new Dante.Editor({
    el: "#editor",
    upload_url: "{$uploadUrl}",
    store_url:  "{$autoSave}",
    oembed_url: "{$oembedUrl}",
    extract_url: "{$extractUrl}",
    disable_title:true
});
editor.start();
$('#editor').bind("DOMSubtreeModified",function(){
  $("input#post-content").val(editor.getContent());
});
$("input#post-url").on('blur',function(){
    $(this).val(encodeURIComponent($(this).val().trim().replace(/\s+/g, "-")));
});

$("input#post-title").on('blur',function(){
    if ($("input#post-url").val() == ""){
        $.post("{$urlSuggestionUrl}",{'title':$(this).val()})
        .done(function(data){
            if ($("input#post-url").val() == ""){
                $("input#post-url").val(data);
                $("div#url-block").css("display","");
            }
        });
    }
});
if ($("input#post-url").val() != ""){
    $("div#url-block").css("display","");
}
JS;
$this->registerJs($js);