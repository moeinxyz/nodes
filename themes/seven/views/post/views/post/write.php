<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\modules\post\Module;
/* @var $this \yii\web\View */
/* @var $model app\modules\post\models\Post */
$this->registerAssetBundle('dante');
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
Dante.Editor.Tooltip.prototype.move = function(coords) {
    var control_spacing, control_width, coord_right, coord_top, pull_size, tooltip;
    tooltip = $(this.el);
    control_width   = tooltip.find(".control").css("width");
    control_spacing = tooltip.find(".inlineTooltip-menu").css("padding-right");
    pull_size = parseInt(control_width.replace(/px/, "")) + parseInt(control_spacing.replace(/px/, ""));
    coord_right = coords.right - pull_size;
    coord_top = coords.top;
    return $(this.el).offset({
      top: coord_top,
      right: coord_right
    });
};
Dante.Editor.Tooltip.prototype.getEmbedFromNode = function(node) {
  this.node = $(node);
  this.node_name = this.node.attr("name");
  this.node.addClass("spinner");
  return $.getJSON("" + this.current_editor.oembed_url + ($(this.node).text().split("").reverse().join(""))).success((function(_this) {
    return function(data) {
      var iframe_src, replaced_node, tmpl, url;
      _this.node = $("[name=" + _this.node_name + "]");
      iframe_src = $(data.html).prop("src");
      tmpl = $(_this.embedTemplate());
      tmpl.attr("name", _this.node.attr("name"));
      $(_this.node).replaceWith(tmpl);
      replaced_node = $(".graf--iframe[name=" + (_this.node.attr("name")) + "]");
      replaced_node.find("iframe").attr("src", iframe_src);
      url = data.url || data.author_url;
      utils.log("URL IS " + url);
      replaced_node.find(".markup--anchor").attr("href", url).text(url);
      return _this.hide();
    };
  })(this));
};        
var editor=new Dante.Editor(
  {
    el: "#editor",
    upload_url: "{$uploadUrl}",
    store_url:  "{$autoSave}",
    oembed_url: "{$oembedUrl}",
    extract_url: "{$extractUrl}",
    disable_title:true
  }
);
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