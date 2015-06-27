<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\modules\post\Module;
use yii\widgets\Pjax;
$this->registerAssetBundle('jasny-bootstrap');

?>

<div class="row">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <hr style="margin-top: 15px;margin-bottom: 15px;">
                <?php
                    $form = ActiveForm::begin([
                        'id' => 'public-form',
                        'options' => ['class'   =>  'form-horizontal','enctype' => 'multipart/form-data'],
                        'fieldConfig' => [
                            'template'  =>  '{input}{error}'
                        ],
                    ]); 
                    ?>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="col-sm-8 col-lg-9 controls">
                                    <div class="col-md-12 controls">
                                        <div class="fileinput fileinput-new" data-provides="fileinput">
                                          <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;">
                                              <img src="<?= $cover->image; ?>" data-src="<?= $cover->image; ?>" alt="<?= Yii::$app->user->getIdentity()->getName(); ?>">
                                          </div>
                                          <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;"></div>
                                          <div>
                                            <span class="btn btn-default btn-file">
                                                <span class="fileinput-new" style="z-index: 0"><?= Module::t('post', 'write.cover.image.new'); ?></span>
                                                <span class="fileinput-exists" style="z-index: 0"><?= Module::t('post', 'write.cover.image.change'); ?></span>
                                                <?= Html::activeFileInput($cover,'image');?>
                                            </span>
                                            <a href="#" class="btn btn-default fileinput-exists" data-dismiss="fileinput"  style="z-index: 0" ><?= Module::t('post', 'write.cover.image.remove'); ?></a>
                                            <?= Html::error($cover, 'image',['class'=>'help-block help-block-error small-font-size','style'=>'color:#da4453;']); ?>
                                          </div>
                                        </div>                    
                                    </div>
                                </div>                
                                <label class="col-sm-4 col-lg-3 control-label"><?= Module::t('post', 'write.cover.title'); ?></label>
                            </div>       
                        </div>
                    </div>
                <?php $form->end(); ?>
            </div>
        </div>
    </div>
</div>

<?php
$js=<<<JS
$('.fileinput').fileinput()     
JS;
$this->registerJs($js);

?>