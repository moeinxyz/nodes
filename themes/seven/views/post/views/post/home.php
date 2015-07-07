<?php 
use yii\widgets\LinkPager;
use app\modules\post\Module;
/* @var $this yii\web\View */
$this->title    = Module::t('post','home.index.head.title');
echo $this->render('home/_posts_list',['posts'=>$posts]);
?>
<div class="row">
    <div class="col-md-12 centertext">
        <?= LinkPager::widget(['pagination' => $pages,'nextPageLabel'=>'&laquo;','prevPageLabel'=>'&raquo;']);?>
    </div>
</div>
<?php
if ($login === true){
    //trigger login modal if request to login user
    $this->registerJs('$("#loginmodal").modal();');    
}
?>