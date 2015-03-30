<?php
use yii\helpers\Html;
use app\modules\social\Module;
?>
<div class="row">
    <div class="col-md-10 col-md-offset-1 col-xs-12">
        <div class="top-buffer"></div>
        <div class="box">
            <div class="box-header">
                <div class="row">
                    <div class="col-xs-12">
                        <?php if (($type = Yii::$app->session->getFlash('social.add')) && $type !== NULL):?>
                            <div class="alert alert-success">
                                <?= Module::t('social','admin.box.'.$type); ?>
                            </div>                                
                        <?php elseif (($type = Yii::$app->session->getFlash('social.wrong')) && $type !== NULL): ?>
                            <div class="alert alert-error">
                                <?= Module::t('social','admin.box.'.$type); ?>
                            </div>                            
                        <?php endif;?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-4">
                        <a href="<?= Yii::$app->urlManager->createUrl(['social/auth','authclient'=>'linkedin']); ?>" class="btn btn-block btn-social btn-linkedin">
                            <i class="fa fa-linkedin"></i><?= Module::t('social','_admin.btn.add.linkedin'); ?>
                        </a>                        
                    </div>
                    <div class="col-xs-4">
                        <a href="<?= Yii::$app->urlManager->createUrl(['social/auth','authclient'=>'twitter']); ?>" class="btn btn-block btn-social btn-twitter">
                            <i class="fa fa-twitter"></i><?= Module::t('social','_admin.btn.add.twitter'); ?>
                        </a>
                    </div>
                    <div class="col-xs-4">
                        <a href="<?= Yii::$app->urlManager->createUrl(['social/auth','authclient'=>'facebook']); ?>" class="btn btn-block btn-social btn-facebook">
                            <i class="fa fa-facebook"></i><?= Module::t('social','_admin.btn.add.facebook'); ?>
                        </a>                 
                    </div>
                </div>                
            </div>            
            <div class="box-body table-responsive no-padding medium-font-size">
                <?= $this->render('_admin',[
                            'dataProvider'  =>  $dataProvider,
                ]);?>
            </div><!-- /.box-body -->
        </div><!-- /.box -->
    </div>
</div>