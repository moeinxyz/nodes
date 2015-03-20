<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\modules\post\Module;
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $newComment \app\modules\post\models\Comment*/
/* @var $timestamp integer */
$form = ActiveForm::begin([
    'action'=>Yii::$app->urlManager->createUrl(["comment/write/@{$post->getUser()->one()->username}/{$post->url}/{$timestamp}"]),
    'id' => 'comment-form',
    'enableClientValidation'=>true,
    'options' => ['class'   =>  'form-horizontal','data-pjax'=>TRUE],
    'fieldConfig' => [
        'template'  =>  '{input}{error}'
    ],
]); 
?>

<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <div class="controls">
                <div class="col-md-12 controls">
                    <?= $form->field($newComment, 'text')->textarea();?>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="controls">
                <div class="col-md-12 controls">
                    <?= Html::submitButton(Module::t('post','comment.submitBtn'), ['class' => 'btn btn-primary btn-block', 'name' => 'comment-button']) ?>
                </div>
            </div>            
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>