<?= $this->render('header/_write',['model'=>$model,'type'=>$type]) ?>   
<div id="wrapper" class="page-content">
    <?= $this->render('_edit',['model'=>$model,'type'=>$type]) ?>  
    <?= $this->render('_post_cover',['cover'=>$cover]); ?>
</div>