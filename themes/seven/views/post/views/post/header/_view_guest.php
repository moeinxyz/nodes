<?php
/* @var $this \yii\web\View */
/* @var $model app\modules\post\models\Post */
use yii\helpers\Html;
use app\modules\post\Module;
use app\modules\post\models\Userrecommend;
?>
<header class="main-header">
    <nav class="navbar navbar-fixed-top navbar-default" role="navigation">
        <div class="navbar-custom-menu pull-right">            
            <ul class="nav flaty-nav pull-right">
                <li data-toggle="modal" data-target="#loginmodal">
                    <a href="#">
                        <?php echo Yii::t('app','header.login'); ?>
                        <i class="glyphicon glyphicon-log-in"></i>                
                    </a> 
                </li>
            </ul>
            <ul class="nav navbar-nav flaty-nav pull-right">
                <li class="hidden-xs">
                    <a href="#" id="recommend">
                        <?php echo Module::t('post','header.view.recommend'); ?>
                        <i class="glyphicon glyphicon-star-empty"></i>
                    </a>
                </li>          
                <li class="hidden-lg hidden-md hidden-sm">
                    <a href="#" id="recommend">
                        <i class="glyphicon glyphicon-star-empty" title="<?php echo Module::t('post','header.view.recommend'); ?>"></i>
                    </a>
                </li>                
            </ul>            
        </div>
        <div class="nav flaty-nav pull-left">
            <ul class="nav navbar-nav flaty-nav pull-left">
                <li class="hidden-xs">
                    <a href="<?= Yii::$app->homeUrl;?>">
                        <i class="glyphicon glyphicon-home"></i>    
                        <?php echo Yii::t('app','header.home'); ?>                
                    </a>
                </li>          
            </ul>  
            <ul class="nav flaty-nav pull-left">
                <li class="hidden-lg hidden-md hidden-sm">
                    <a href="<?= Yii::$app->homeUrl;?>">
                        <i class="glyphicon glyphicon-home" title="<?php echo Yii::t('app','header.home'); ?>"></i>    
                    </a>
                </li>               
            </ul>    
        </div>
    </nav>
</header>    