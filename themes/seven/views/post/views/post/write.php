<?= $this->render('header',['model'=>$model,'type'=>$type]) ?>   
<div id="wrapper" class="page-content">
    <?= $this->render('_edit',['model'=>$model,'type'=>$type]) ?>   
</div>