<?php
use app\modules\post\Module;
/* @var $this yii\web\View */
if ($model->title == ''){
    $this->title    = Module::t('post','write.head.title.new');
} else {
    $this->title    = Module::t('post','write.head.title.edit',['title'=>$model->title]);
}
?>
<?= $this->render('header/_write',['model'=>$model,'type'=>$type]) ?>   
<div id="wrapper" class="page-content">
    <?= $this->render('_edit',['model'=>$model,'type'=>$type]) ?>  
    <?= $this->render('_post_cover',['cover'=>$cover]); ?>
</div>