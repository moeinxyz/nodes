<div class="navbar-custom-menu pull-right">
    <ul class="nav flaty-nav pull-right">  
        <li data-toggle="modal" data-target="#loginmodal">
            <a href="#">
                <?php echo Yii::t('app','header.login'); ?>
                <i class="glyphicon glyphicon-log-in"></i>                
            </a> 
        </li>
    </ul>
<!--    <ul class="nav flaty-nav pull-right">
        <li class="hidden-xs">
            <a href="<?= Yii::$app->urlManager->createUrl(['search']) ?>">
                <i class="glyphicon glyphicon-search"></i>                
            </a> 
        </li>
    </ul>    -->
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