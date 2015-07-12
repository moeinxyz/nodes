<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\modules\post\Module;
use yii\widgets\Pjax;
use app\modules\post\models\Post;
app\assets\JasnyBootstrapAssets::register($this);
$pjax       =   Pjax::begin(['enablePushState'=>false]);
?>
<div class="row">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <hr style="margin-top: 15px;margin-bottom: 15px;">
                    <div class="row text-center center-block central">
                        <div class="col-md-12 text-center center-block central"><?= Module::t('post', 'write.cover.title'); ?></div>
                    </div>
                
                    <?php if (Yii::$app->session->getFlash('post.cover.add_or_change.successful')): ?>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="alert alert-success">
                                    <h4><i class="icon fa fa-check"></i><?= Module::t('post','cover.add_or_change.successful'); ?></h4> 
                                </div>
                            </div>
                        </div>
                    <?php elseif (Yii::$app->session->getFlash('post.cover.remove.successful')): ?>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="alert alert-success">
                                    <h4><i class="icon fa fa-check"></i><?= Module::t('post','cover.remove.successful'); ?></h4> 
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>                  <?php
                    if ($cover->coverStatus === Post::COVER_BYCOVER){
                        $imageSrc   =   Post::getCoverUrl($cover->postId, true);
                    } else {
                        $imageSrc   =   Yii::getAlias("@placeHold/300x169/EFEEEF/AAAAAA&text=No+Post+Cover");
                    }
                    $form = ActiveForm::begin([
                        'id'        =>  'public-form',
                        'action'    =>  Yii::$app->urlManager->createUrl(["post/cover","id"=>  base_convert($cover->postId, 10, 36)]),
                        'options'   =>  ['class'   =>  'form-horizontal','enctype' => 'multipart/form-data','data-pjax'=>TRUE],
                        'fieldConfig' => [
                            'template'  =>  '{input}{error}'
                        ],
                    ]); 
                    ?>                
                    <div class="row">  
                        <div class="col-md-4 col-lg-4 hidden-sm hidden-xs">
                            <div class="form-group">
                                <div class="controls">
                                    <div class="col-md-12 controls">
                                        <?= Html::submitButton(Module::t('post','post.cover.add'), ['class' => 'btn btn-primary btn-block', 'name' => 'submit-type','value'=>'add-cover']) ?>
                                    </div>
                                    <?php if ($cover->coverStatus === Post::COVER_BYCOVER): ?>
                                        <div class="col-md-12 controls">
                                            <?= Html::submitButton(Module::t('post','post.cover.remove'), ['class' => 'btn btn-primary btn-block', 'name' => 'submit-type','value'=>'remove-cover']) ?>
                                        </div>                                    
                                    <?php endif; ?>                                 
                                </div>            
                            </div>                                                        
                        </div>                        
                        <div class="col-lg-8 col-md-8 col-sm-12">
                            <div class="form-group">
                                <div class="col-md-12 controls">
                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                      <div class="fileinput-new thumbnail" style="width: 300px; height: 169px;">
                                          <img src="<?= $imageSrc; ?>" data-src="<?= $imageSrc; ?>">
                                      </div>
                                      <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;"></div>
                                      <div>
                                        <span class="btn btn-default btn-file">
                                            <span class="fileinput-new" style="z-index: 0"><?= Module::t('post', 'write.cover.image.new'); ?></span>
                                            <span class="fileinput-exists" style="z-index: 0"><?= Module::t('post', 'write.cover.image.change'); ?></span>
                                            <?= Html::activeFileInput($cover,'coverImage');?>
                                        </span>
                                        <a href="#" class="btn btn-default fileinput-exists" data-dismiss="fileinput"  style="z-index: 0" ><?= Module::t('post', 'write.cover.image.remove'); ?></a>
                                        <?= Html::error($cover, 'coverImage',['class'=>'help-block help-block-error small-font-size','style'=>'color:#da4453;']); ?>
                                      </div>
                                    </div>                    
                                </div>             
                            </div>  
                        </div>
                        <div class="col-sm-12 hidden-lg hidden-md">
                            <div class="form-group">
                                <div class="controls">
                                    <div class="col-md-12 controls">
                                        <?= Html::submitButton(Module::t('post','post.cover.add'), ['class' => 'btn btn-primary btn-block', 'name' => 'submit-type','value'=>'add-cover']) ?>
                                    </div>
                                    <?php if ($cover->coverStatus === Post::COVER_BYCOVER): ?>
                                        <div class="col-md-12 controls">
                                            <?= Html::submitButton(Module::t('post','post.cover.remove'), ['class' => 'btn btn-primary btn-block', 'name' => 'submit-type','value'=>'remove-cover']) ?>
                                        </div>                                    
                                    <?php endif; ?>
                                </div>            
                            </div>                                                        
                        </div>                                                
                    </div>
                <?php  ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>

<?php
Pjax::end();
$js=<<<JS
$('.fileinput').fileinput()     
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