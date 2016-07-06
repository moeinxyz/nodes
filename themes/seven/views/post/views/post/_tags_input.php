<?php
use yii\helpers\Html;
use app\modules\post\Module;

\app\assets\TagsAssets::register($this);
?>
<div class="row">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <hr style="margin-top: 15px;margin-bottom: 15px;">
                <div class="row text-center center-block central">
                    <div class="col-md-12 text-center center-block central"><?= Module::t('post', 'write.tags.title'); ?></div>
                </div>
                <?php
                    echo Html::beginForm(['post/tag','id'=>  base_convert($model->id, 10, 36)],'post',['id'=>'posttags-form']);
                    echo Html::textInput('tags','asdasd,asdasd,asdasdqwe,asd', ['width'=>'100%', 'id'=>'tags']);
                    echo Html::endForm();
                ?>
            </div>
        </div>
    </div>
</div>