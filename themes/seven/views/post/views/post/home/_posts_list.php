<div class="top-buffer"></div>
<?php if (Yii::$app->user->isGuest): ?>

<?php endif; ?>
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-8 col-md-offset-2 col-lg-8 col-lg-offset-2">
        <?php foreach($posts as $post):?>
            <?= $this->render('_show_post_abstract',['post'=>$post]);?>
        <?php endforeach;?>
    </div>
</div>