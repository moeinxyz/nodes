<div class="navbar-custom-menu pull-right">
    <ul class="nav flaty-nav pull-right">
        <li class="hidden-xs" data-toggle="modal" data-target="#loginmodal">
            <a href="#">
                <?php echo Yii::t('app','header.login'); ?>
                <i class="glyphicon glyphicon-log-in"></i>                
            </a> 
        </li>
    </ul>
    <ul class="nav flaty-nav pull-right">
        <li class="hidden-xs">
            <a href="<?= Yii::$app->urlManager->createUrl(['post/write']) ?>">
                <?php echo Yii::t('app','header.write'); ?>
                <i class="glyphicon glyphicon-pencil"></i>                
            </a> 
        </li>                
    </ul>
    <ul class="nav flaty-nav pull-right">
        <li class="hidden-xs">
            <a href="<?= Yii::$app->urlManager->createUrl(['search']) ?>">
                <i class="glyphicon glyphicon-search"></i>                
            </a> 
        </li>
    </ul>    
</div>