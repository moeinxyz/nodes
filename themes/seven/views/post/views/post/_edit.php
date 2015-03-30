<?php
use yii\helpers\Html;
use app\modules\post\Module;
use yii\helpers\HtmlPurifier;
/* @var $this \yii\web\View */
/* @var $model app\modules\post\models\Post */
$this->registerAssetBundle('custom-editor');
?>
<div class="row">
    <div class="col-md-8 col-md-offset-2">    
        <div class="row">
            <div class="col-md-12">
                <br><br>
                <div id="editor">
                    <?php
                        if ($type === 'auto'){
                            echo $model->autosave_content;
                        } else if ($model->content != NULL){
                            $title   = ($model->title == NULL)?Module::t('post','write.title.placeholder'):$model->title;
                            $content = "<h3 class=\"graf graf--h3 graf--first\">{$title}<br></h3>";
                            $content .= $model->content;
                            echo $content;
                        }
                    ?>
                </div>
            </div>
            <div class="col-md-12">
                <?= $this->render('_cover_form'); ?>
            </div>
        </div>
        <?php
            echo Html::beginForm(['post/edit','id'=>  base_convert($model->id, 10, 36)],'post',['id'=>'post-form']);
            echo Html::hiddenInput('content');
            echo Html::endForm();
        ?>
    </div>
</div>
<?= $this->render('dynamicJS/_edit',['model'=>$model]);