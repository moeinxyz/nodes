<?php 
use app\modules\post\Module;
?>
<div class="top-buffer"></div>
<?php if (Yii::$app->user->isGuest): ?>
    <?= $this->render('_signup_suggestion'); ?>
<?php endif; ?>
<div class="row">
    <?php if (count($posts) >= 1): ?>
        <div class="col-xs-12 col-sm-12 col-md-8 col-md-offset-2 col-lg-8 col-lg-offset-2">
            <?php foreach($posts as $post):?>
                <?= $this->render('_show_post_abstract',['post'=>$post]);?>
            <?php endforeach;?>
        </div>    
    <?php else:?>
        <div class="col-xs-12 col-sm-12 col-md-8 col-md-offset-2 col-lg-8 col-lg-offset-2">
            <div class="box box-info">
                <div class="box-header">
                    <?= Module::t('post','home._posts_list.no_post');?>
                </div>
            </div>
        </div>
    <?php endif;?>
</div>