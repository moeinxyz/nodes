<?php
use app\modules\post\Module;
/* @var $this yii\web\View */
?>

<?php if (Yii::$app->request->get('activation') !== NULL && Yii::$app->session->getFlash('token.activation.successful')): ?>    
    <div class="row top-buffer">
        <div class="col-md-10 col-md-offset-1 col-xs-12">
            <div class="alert alert-success">
                <h4><i class="icon fa fa-check"></i><?= Module::t('post','home._alert.activation.header'); ?></h4> 
                <?= Module::t('post','home._alert.activation.text'); ?>
            </div>
        </div>
    </div>
<?php elseif (Yii::$app->request->get('reset') !== NULL && Yii::$app->session->getFlash('token.reset.successful')): ?>
    <div class="row top-buffer">
        <div class="col-md-10 col-md-offset-1 col-xs-12">
            <div class="alert alert-success">
                <h4><i class="icon fa fa-check"></i><?= Module::t('post','home._alert.reset.header'); ?></h4> 
                <?= Module::t('post','home._alert.reset.text'); ?>
            </div>
        </div>
    </div>
<?php endif; ?>