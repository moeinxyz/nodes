<?php 
use app\modules\user\models\User;
use yii\widgets\LinkPager;
echo $this->render('_posts_list',['posts'=>$posts]);
?>
<div class="row">
    <div class="col-md-12 centertext">
        <?= LinkPager::widget(['pagination' => $pages,'nextPageLabel'=>'&laquo;','prevPageLabel'=>'&raquo;']);?>
    </div>
</div>