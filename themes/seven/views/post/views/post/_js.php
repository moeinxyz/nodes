<?php
/* @var $this \yii\web\View */
/* @var $model app\modules\post\models\Post */
use app\modules\post\Module;
$id                 =   base_convert($model->id, 10, 36);
$saveUrl            =   Yii::$app->urlManager->createUrl(["post/post/edit","id"=>  base_convert($model->id, 10, 36)]);
$autoSave           =   Yii::$app->urlManager->createUrl(["post/autosave/{$id}"]);
$uploadUrl          =   Yii::$app->urlManager->createUrl(["post/image/upload","id"=>$id]);   
$oembedUrl          =   Yii::$app->urlManager->createUrl(["embed/embed/embed",'type'=>'oembed']).'&url=';
$extractUrl         =   Yii::$app->urlManager->createUrl(["embed/embed/embed",'type'=>'extract']).'&url=';
$titlePlaceholder   =   Module::t('post','write.title.placeholder');
$bodyPlaceholder    =   Module::t('post','write.body.placeholder');
$embedPlaceholder   =   Module::t('post','write.embed.placeholder');
$extractPlaceholder =   Module::t('post','write.extract.placeholder');

$js=<<<JS
var status      =   $("i#status");
var setting     =   $("#setting");
var editorElm   =   $("#editor");
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
    status.attr('class','glyphicon glyphicon-repeat glyphicon-refresh-animate');
});
$(document).ajaxSuccess(function(){
    status.attr('class','glyphicon glyphicon-floppy-saved');
});
$(document).ajaxError(function(){
    status.attr('class','glyphicon glyphicon-floppy-remove');
});  
$("a#save").on('click',function(){
    editorElm.append('<div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>');
    var store = editor.checkforStore();
    if ((typeof store) === "number"){
        editorElm.find('.overlay').remove();
        jQuery.post("{$saveUrl}",function(){
            editorElm.find('.overlay').remove();
        });    
    } else if ((typeof store) === "object"){
        store.success(function(){
            jQuery.post("{$saveUrl}",function(){
                editorElm.find('.overlay').remove();        
            });
        });
    }
});
//        
//$(function() {
//  $('a[href*=#]:not([href=#])').click(function() {
//    if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
//      var target = $(this.hash);
//      target = target.length ? target : $('[name=' + this.hash.slice(1) +']');
//      if (target.length) {
//        $('html,body').animate({
//          scrollTop: target.offset().top
//        }, 1000);
//        return false;
//      }
//    }
//  });
//});        
JS;
$this->registerJs($js);