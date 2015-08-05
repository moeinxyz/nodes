<?php
/* @var $this \yii\web\View */
/* @var $model app\modules\post\models\Post */
use yii\helpers\Html;
use app\modules\post\Module;
use app\modules\post\models\Post;
use app\modules\post\models\Comment;
use app\components\Helper\Time;
use yii\helpers\StringHelper;
?>
<header class="main-header">
    <nav class="navbar navbar-fixed-top navbar-default" role="navigation">
        <div class="navbar-custom-menu pull-right">
            <ul class="nav navbar-nav flaty-nav pull-right">
                <li>
                    <a href="#" id="save">
                        <span class="hidden-xs"><?php echo Module::t('post','header.save'); ?></span>
                        <i class="glyphicon glyphicon-floppy-save" id="status" title="<?php echo Module::t('post','header.save'); ?>"></i>
                    </a>
                </li>          
            </ul>
            <ul class="nav navbar-nav flaty-nav pull-right">
                <li>
                    <a href="<?= Yii::$app->urlManager->createUrl(['/post/preview','id'=>  base_convert($model->id, 10, 36)]) ?>" target="_blank">
                        <span class="hidden-xs"><?php echo Module::t('post','header.preview'); ?></span>
                        <i class="glyphicon glyphicon-eye-open hidden-lg hidden-md hidden-sm" title="<?php echo Module::t('post','header.preview'); ?>"></i>                
                        <i class="glyphicon glyphicon-eye-open hidden-xs"></i>                
                    </a>
                </li>          
            </ul>

            <?php if ($model->status != Post::STATUS_PUBLISH): ?>
                <ul class="nav navbar-nav flaty-nav pull-right">
                    <li>
                        <a href="#" id="publish">
                            <span class="hidden-xs"><?php echo Module::t('post','header.publish'); ?></span>
                            <i class="glyphicon glyphicon-bullhorn hidden-xs"></i>
                            <i class="glyphicon glyphicon-bullhorn hidden-lg hidden-md hidden-sm" title="<?php echo Module::t('post','header.publish'); ?>"></i>
                        </a>
                    </li>          
                </ul>            
            <?php endif; ?>  
            
            <?php if ($type != 'content'): ?>
                <ul class="nav navbar-nav flaty-nav pull-right">
                    <li>
                        <a href="<?= Yii::$app->urlManager->createUrl(['post/edit','id'=>  base_convert($model->id, 10, 36),'type'=>'content'])?>">
                            <span class="hidden-xs"><?php echo Module::t('post','header.load.saved'); ?></span>
                            <i class="glyphicon glyphicon-backward hidden-xs"></i>
                            <i class="glyphicon glyphicon-backward hidden-lg hidden-md hidden-sm" title="<?php echo Module::t('post','header.load.saved'); ?>"></i>
                        </a>
                    </li>          
                </ul>            
            <?php else: ?>
                <ul class="nav navbar-nav flaty-nav pull-right">
                    <li>
                        <a href="<?= Yii::$app->urlManager->createUrl(['post/edit','id'=>  base_convert($model->id, 10, 36),'type'=>'autosave'])?>">
                            <span class="hidden-xs"><?php echo Module::t('post','header.load.autosave'); ?></span>
                            <i class="glyphicon glyphicon-forward hidden-xs"></i>
                            <i class="glyphicon glyphicon-forward hidden-lg hidden-md hidden-sm" title="<?php echo Module::t('post','header.load.autosave'); ?>"></i>
                        </a>
                    </li>          
                </ul>                   
            <?php endif;?>
        </div>

        <div class="navbar-custom-menu pull-left">
            <ul class="nav navbar-nav flaty-nav pull-left">
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">               
                        <i class="glyphicon hidden-xs"><?= Yii::$app->user->getIdentity()->getUsername();?></i>                
                        <i class="glyphicon hidden-lg hidden-md hidden-sm">@</i>  
                        <img src="<?= Yii::$app->user->getIdentity()->getProfilePicture();?>" class="user-image" alt="<?= Yii::$app->user->getIdentity()->getName();?>"/>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="user-footer">
                            <?= Html::a(Yii::t('app','header.read').'<i class="glyphicon glyphicon-book"></i>',
                                        Yii::$app->homeUrl);?>                            
                            <?= Html::a(Yii::t('app','header.user.newpost').'<i class="glyphicon glyphicon-pencil"></i>',
                                        Yii::$app->urlManager->createUrl(['/post/write','type'=>'new']));?>
                            <hr class="mini central">
                            <?= Html::a(Yii::t('app','header.user.posts').'<i class="glyphicon glyphicon-list"></i>',
                                        Yii::$app->urlManager->createUrl(['/post/admin']));?>
                            <?= Html::a(Yii::t('app','header.user.comments').'<i class="glyphicon glyphicon-comment"></i>',
                                        Yii::$app->urlManager->createUrl(['/post/comments']));?>                            
                            <!--<?= Html::a(Yii::t('app','header.user.stats').'<i class="glyphicon glyphicon-stats"></i>',
                                        Yii::$app->urlManager->createUrl(['/post/stats']));?>                    
                            <?= Html::a(Yii::t('app','header.user.publications').'<i class="glyphicon glyphicon-leaf"></i>',
                                        Yii::$app->urlManager->createUrl(['/post/publication']));?>                                        -->
                            <?= Html::a(Yii::t('app','header.user.socials').'<i class="glyphicon glyphicon-share"></i>',
                                        Yii::$app->urlManager->createUrl(['/social/admin']));?>                                        
                            <?php if (!Yii::$app->user->getIdentity()->isNameAndTaglineSet()): ?>    
                                <?= Html::a(Yii::t('app','header.user.completeYourProfile').'<i class="glyphicon glyphicon-user"></i>', Yii::$app->urlManager->createUrl(['/user/profile'])); ?>  
                            <?php else: ?>
                                <?= Html::a(Yii::t('app','header.user.profile').'<i class="glyphicon glyphicon-user"></i>', Yii::$app->urlManager->createUrl(['/'.Yii::$app->user->getIdentity()->getUsername()])); ?>
                            <?php endif; ?>            
                            <?= Html::a(Yii::t('app','header.user.setting').'<i class="glyphicon glyphicon-wrench"></i>', Yii::$app->urlManager->createUrl(['/user/setting'])); ?>
                            <?= Html::a(Yii::t('app','header.user.logout').'<i class="glyphicon glyphicon-log-out"></i>', Yii::$app->urlManager->createUrl(['/user/logout'])); ?>                                    
                        </li>
                    </ul>            
                </li>
            </ul>
            <ul class="nav navbar-nav flaty-nav pull-left">
                <li class="dropdown messages-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="glyphicon glyphicon-comment"></i>
                        <span class="label label-success"><?= Comment::countNotifications(); ?></span>
                    </a>    
                    <ul class="dropdown-menu">
                        <li>
                            <ul class="menu">
                                <?php foreach (Comment::getLastComments() as $comment): ?>
                                    <li>
                                        <a href="<?= Yii::$app->urlManager->createUrl([Yii::$app->user->getIdentity()->getUsername() . "/{$comment->post->url}#comment-" . md5($comment->id)]); ?>">
                                            <div class="pull-right">
                                                <!-- User Image -->
                                                <img src="<?= $comment->user->getProfilePicture(); ?>" class="img-circle" alt="<?= $comment->user->getName(); ?>" />
                                            </div>
                                            <!-- Message title and timestamp -->
                                            <h4 class="pull-right" dir="rtl">
                                                <?= StringHelper::truncate($comment->user->getName(), 20); ?>
                                            </h4>
                                            <h4>
                                                <small class="pull-left" dir="rtl">
                                                    <i class="fa fa-clock-o"></i>
                                                    <?= Time::humanDiffTime(strtotime($comment->created_at)); ?>
                                                </small>                                    
                                            </h4>
                                            <p class="pull-right" dir="rtl">
                                                <?= StringHelper::truncate($comment->pure_text, 30); ?>
                                            </p>
                                        </a>
                                    </li>                        
                                <?php endforeach; ?>
                            </ul>
                        </li>
                        <li class="footer"><a href="<?= Yii::$app->urlManager->createUrl(['/post/comments']) ?>">مشاهده تمام نظرات</a></li>
                    </ul>          
                </li>                
            </ul>            
        </div>    
  </nav>
</header>    
