<?php
/* @var $this \yii\web\View */
/* @var $model app\modules\post\models\Post */
use app\modules\post\Module;
$id                 =   base_convert($model->id, 10, 36);
$autoSave           =   Yii::$app->urlManager->createUrl(["post/autosave/{$id}"]);
$uploadUrl          =   Yii::$app->urlManager->createUrl(["post/upload","id"=>$id]);   
$urlSuggestionUrl   =   Yii::$app->urlManager->createUrl(["post/post/suggesturl",'id'=>$id]);
$oembedUrl          =   Yii::$app->urlManager->createUrl(["embed/embed/embed",'type'=>'oembed']).'&url=';
$extractUrl         =   Yii::$app->urlManager->createUrl(["embed/embed/embed",'type'=>'extract']).'&url=';
$titlePlaceholder   =   Module::t('post','write.title.placeholder');
$bodyPlaceholder    =   Module::t('post','write.body.placeholder');
$embedPlaceholder   =   Module::t('post','write.embed.placeholder');
$extractPlaceholder =   Module::t('post','write.extract.placeholder');
$statusSaved        =   Module::t('post','write.autosave.status.saved');
$statusSaving       =   Module::t('post','write.autosave.status.saving');
$statusError        =   Module::t('post','write.autosave.status.error');

$js=<<<JS
var status  = $("#status");
var setting = $("#setting");
var editor=new Dante.Editor({
    el: "#editor",
    upload_url:                 "{$uploadUrl}",
    store_url:                  "{$autoSave}",
    oembed_url:                 "{$oembedUrl}",
    extract_url:                "{$extractUrl}",
    store_interval:             5000,
    title_placeholder:          "{$titlePlaceholder}",
    body_placeholder:           "{$bodyPlaceholder}",
    embed_placeholder:          "{$embedPlaceholder}",
    extract_placeholder:        "{$extractPlaceholder}"
});
editor.start();
$("#editor").bind("DOMSubtreeModified",function(){
  $("input#post-content").val(editor.getContent());
});
$(document).ajaxStart(function(){
    status.html("{$statusSaving}");
});
$(document).ajaxSuccess(function(){
    status.html("{$statusSaved}");
});
$(document).ajaxError(function(){
    status.html("{$statusError}");
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
$("#btnSetting").on('click',function(){
    setting.toggle();
    if (setting.style('display') ==  'none'){
        console.log("NONE");
    } else {
        console.log("NOT NONE");
    }
});  
        
$(function() {
  $('a[href*=#]:not([href=#])').click(function() {
    if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
      var target = $(this.hash);
      target = target.length ? target : $('[name=' + this.hash.slice(1) +']');
      if (target.length) {
        $('html,body').animate({
          scrollTop: target.offset().top
        }, 1000);
        return false;
      }
    }
  });
});        
JS;
$this->registerJs($js);