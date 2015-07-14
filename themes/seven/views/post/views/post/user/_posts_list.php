<hr class="mini central">
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-10 col-md-offset-1 col-lg-10 col-lg-offset-1">
        <?php foreach($posts as $post):?>
            <?= $this->render('_show_post_abstract',['post'=>$post,'user'=>$user]);?>
        <?php endforeach;?>
    </div>
</div>