<?php
use yii\helpers\Html;
use app\modules\post\Module;
use app\modules\post\models\Post;

/* @var $this \yii\web\View */
$this->title    = Module::t('post','admin.head.title');
?>
<div class="row">
    <div class="col-md-10 col-md-offset-1 col-xs-12">
        <div class="top-buffer"></div>
        <div class="box">
            <div class="box-header">
                <div class="row">
                    <div class="col-sm-3 hidden-xs">
                        <?= Html::a(Module::t('post','admin.status.draft'),['admin','status'=>'draft'],[
                            'class' =>  ($status === Post::STATUS_DRAFT)?'btn btn-success btn-block btn-flat':'btn btn-info btn-block btn-flat',
                        ]);?>                        
                    </div>
                    <div class="col-sm-3 hidden-xs">
                        <?= Html::a(Module::t('post','admin.status.publish'),['admin','status'=>'publish'],[
                            'class' =>  ($status === Post::STATUS_PUBLISH)?'btn btn-success btn-block btn-flat':'btn btn-info btn-block btn-flat',
                        ]);?>
                    </div>
                    <div class="col-sm-3 hidden-xs">
                        <?= Html::a(Module::t('post','admin.status.trash'),['admin','status'=>'trash'],[
                            'class' =>  ($status === Post::STATUS_TRASH)?'btn btn-success btn-block btn-flat':'btn btn-info btn-block btn-flat',
                        ]);?>
                    </div>
                    <div class="col-sm-3 hidden-xs">
                        <?= Html::a(Module::t('post','admin.new.post'),['write','type'=>'new'],[
                            'class' =>  'btn btn-primary btn-block btn-flat',
                        ]);?>
                    </div>             
                    
                    <div class="col-xs-6 hidden-lg hidden-md hidden-sm">
                        <?= Html::a(Module::t('post','admin.status.draft'),['admin','status'=>'draft'],[
                            'class' =>  ($status === Post::STATUS_DRAFT)?'btn btn-success btn-block btn-flat':'btn btn-info btn-block btn-flat',
                        ]);?>                        
                    </div>
                    <div class="col-xs-6 hidden-lg hidden-md hidden-sm">
                        <?= Html::a(Module::t('post','admin.status.publish'),['admin','status'=>'publish'],[
                            'class' =>  ($status === Post::STATUS_PUBLISH)?'btn btn-success btn-block btn-flat':'btn btn-info btn-block btn-flat',
                        ]);?>
                    </div>
                    <div class="col-xs-6 hidden-lg hidden-md hidden-sm">
                        <?= Html::a(Module::t('post','admin.status.trash'),['admin','status'=>'trash'],[
                            'class' =>  ($status === Post::STATUS_TRASH)?'btn btn-success btn-block btn-flat':'btn btn-info btn-block btn-flat',
                        ]);?>
                    </div>
                    <div class="col-xs-6 hidden-lg hidden-md hidden-sm">
                        <?= Html::a(Module::t('post','admin.new.post'),['write','type'=>'new'],[
                            'class' =>  'btn btn-primary btn-block btn-flat',
                        ]);?>
                    </div>                    
                </div>                
            </div>
            <div class="box-body table-responsive no-padding medium-font-size">
                <?= $this->render('_admin',[
                            'dataProvider'  =>  $dataProvider,
                            'status'        =>  strtolower($status)
                ]);?>
            </div><!-- /.box-body -->
        </div><!-- /.box -->
    </div>
</div>