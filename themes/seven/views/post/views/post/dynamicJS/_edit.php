<?php
/* @var $this \yii\web\View */
/* @var $model app\modules\post\models\Post */
use app\modules\post\Module;
$id                 =   base_convert($model->id, 10, 36);
$saveUrl            =   Yii::$app->urlManager->createUrl(["post/edit","id"=>  base_convert($model->id, 10, 36)]);
$publishUrl         =   Yii::$app->urlManager->createUrl(["post/publish","id"=>  base_convert($model->id, 10, 36)]);
$autoSave           =   Yii::$app->urlManager->createUrl(["post/autosave/{$id}"]);
$uploadUrl          =   Yii::$app->urlManager->createUrl(["post/upload","id"=>$id]);   
$oembedUrl          =   Yii::$app->urlManager->createUrl(["embed",'type'=>'oembed']).'&url=';
$extractUrl         =   Yii::$app->urlManager->createUrl(["embed",'type'=>'extract']).'&url=';
$tagsUrl            =   Yii::$app->urlManager->createUrl(["post/tags", "id"=>$id]);
$titlePlaceholder   =   Module::t('post','write.title.placeholder');
$bodyPlaceholder    =   Module::t('post','write.body.placeholder');
$embedPlaceholder   =   Module::t('post','write.embed.placeholder');
$extractPlaceholder =   Module::t('post','write.extract.placeholder');
$errorTitle         =   Module::t('post','write.save.error.title');
$js=<<<JS
var status      =   $("i#status");
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

$("#tags").tagsinput({
  trimValue: true
});

editor.start();

$(document).ajaxStart(function(){
    status.attr('class','glyphicon glyphicon-repeat glyphicon-refresh-animate');
    editorElm.find('.fa-warning').remove();
});
$(document).ajaxSuccess(function(){
    status.attr('class','glyphicon glyphicon-floppy-saved');
    editorElm.find('.fa-warning').remove();
});
$(document).ajaxError(function(data){
    status.attr('class','glyphicon glyphicon-floppy-remove');
    editorElm.find('.overlay').remove();        
    editorElm.append('<i class="fa fa-warning" title="{$errorTitle}"></i>');
}); 
    
var saveTags    =   function(){
    return $.post("{$tagsUrl}", {tags: $("#tags").tagsinput('items')});
}    

var savePost    =   function(){
    var def =   $.Deferred();
    var store = editor.checkforStore();
    if ((typeof store) === "number") {
        def.resolve();
    } else if ((typeof store) === "object") {
        store.success(function() {
            def.resolve();  
        });
    }
    return def;
}

var save      =   function(redirect) {
    editorElm.append('<div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>');
    $.when(savePost() , saveTags()).done(function() {
        jQuery.post("{$saveUrl}").done(function() {
          if (redirect === true) {
            jQuery.post("{$publishUrl}").done(function(data) {
                editorElm.find('.overlay').remove(); 
                if (data.url)   window.location = data.url;
            });
          } else {
            editorElm.find('.overlay').remove();
          }
        });  
    });
}
    
$("a#save").on('click',function(){
    save(false);
    return false;
});
    
$("a#publish").on('click',function(){
    save(true);            
    return false;
});

JS;
$this->registerJs($js);