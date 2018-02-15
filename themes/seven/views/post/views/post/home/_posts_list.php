<?php 
use app\modules\post\Module;
?>
<div class="top-buffer"></div>
<?php if (Yii::$app->user->isGuest): ?>
    <?= $this->render('_signup_suggestion'); ?>
<?php endif; ?>
<div class="row">
    <?php if (count($posts) >= 1): ?>
        <div class="col-xs-12 col-sm-12 col-md-10 col-md-offset-1 col-lg-10 col-lg-offset-1">
            <?php foreach($posts as $post):?>
                <?= $this->render('_show_post_abstract',['post'=>$post]);?>
            <?php endforeach;?>
        </div>    
    <?php else:?>
        <div class="col-xs-12 col-sm-12 col-md-10 col-md-offset-1 col-lg-10 col-lg-offset-1">
            <div class="box box-info">
                <div class="box-header">
                    <?= Module::t('post','home._posts_list.no_post');?>
                </div>
            </div>
        </div>
    <?php endif;?>
</div>