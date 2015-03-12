<?php
use yii\bootstrap\ActiveForm;
use app\modules\post\Module;
/* @var $this \yii\web\View */
/* @var $model app\modules\post\models\Post */
$this->registerAssetBundle('custom-editor');
?>
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
                        <?= $form->errorSummary($model); ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <br><br>
                <div id="editor"></div>
            </div>
        </div>
        <div class="row" id="setting" style="display: none;">
            <div class="col-md-12">
                <div class="form-group">
                    <div class="controls">
                        <div class="col-md-12 controls">
                            <?= $form->field($model,'url',[
                                'template'  =>  '<div id="url-block" class="input-group" style="direction: ltr;"><span class="input-group-addon transparent-input">http://nodes.ir/@moein7tl/</span>{input}{error}</div>'
                            ])->textInput(['class'=>'input-sm form-control transparent-input']); ?>
                        </div>                                                
                    </div>
                </div>
                
                <?= $form->field($model, 'content')->hiddenInput(); ?>
            </div>
        </div>        
        <?php ActiveForm::end();?>
    </div>
</div>
<?= $this->render('_js',['model'=>$model]);