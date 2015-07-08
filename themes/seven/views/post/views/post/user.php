<?php 
use app\modules\user\models\User;
use yii\widgets\LinkPager;
use app\modules\post\Module;
/* @var $this yii\web\View */

// social meta tags
$this->render('meta/_user',['user'=>$user]);

$this->title    = Module::t('post','user.user.head.title',['name'=>$user->getName()]);
if ($user->profile_cover === User::COVER_UPLOADED){
    echo $this->render('user/_with_cover',['user'=>$user]);
} else {
    echo $this->render('user/_without_cover',['user'=>$user]);
}
echo $this->render('user/_posts_list',['posts'=>$posts,'user'=>$user]);
?>
<div class="row">
    <div class="col-md-12 centertext">
        <?= LinkPager::widget(['pagination' => $pages,'nextPageLabel'=>'&laquo;','prevPageLabel'=>'&raquo;']);?>
    </div>
</div>