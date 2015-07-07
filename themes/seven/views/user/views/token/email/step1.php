<?php 
use app\modules\user\Module;
/* @var $this yii\web\View */
/* @var $model app\modules\user\models\Token */
$this->title = Module::t('token','change.step1.head.title');
?>
<div class="top-buffer"></div>
<div class="row">   
    <div class="col-md-10 col-md-offset-1 col-xs-12">
        <div class="box box-primary">
            <div class="box-header text-center">
                <?= Module::t('token', 'email.header'); ?>
            </div>
            <div class="box-body">
                <div class="box box-solid bg-light-blue small-font-size">
                    <div class="box-body">
                      <?= Module::t('token', 'email.help.body'); ?>
                    </div>
                </div>                
                <hr class="mini central">
                <?= $this->render('_step1_form',['model'=>$model,'error'=>false]); ?>    
            </div>
        </div>
    </div>    
</div>