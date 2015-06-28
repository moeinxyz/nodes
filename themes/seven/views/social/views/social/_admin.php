<?php
use yii\grid\GridView;
use yii\helpers\Html;
use app\modules\social\Module;
use app\modules\social\models\Social;
use yii\widgets\Pjax;

$username   =   Yii::$app->user->getIdentity()->getUsername();
$pjax       =   Pjax::begin(['enablePushState'=>FALSE,'clientOptions' => ['method' => 'POST']]);
echo GridView::widget([
    'tableOptions' => [
        'class' => 'table table-striped table-hover fill-head'
    ],
    'headerRowOptions' => [
        'role' => 'row'
    ],
    'pager' => [
        'activePageCssClass' => 'active',
        'options' => [
            'class' => 'pagination pagination-colory'
        ]
    ],
    'dataProvider'      =>  $dataProvider,
    'rowOptions'        =>  function ($model, $key, $index, $grid){
        if ($model->auth === Social::AUTH_DEATUH){
            return [
                'style'=> 'background-color:#d2d6de;'
            ];
        }
    },
    'columns'   =>  [
        [
            'attribute'     =>  'type',
            'content'       => function ($model, $key, $index, $column) {
                return Module::t('social','TYPE.'.$model->type);
            }
        ],
        [
            'attribute'     =>  'name',
            'content'       => function ($model, $key, $index, $column) {
                return Html::a($model->name,$model->url);
                return var_dump(unserialize($model->token));
                return Yii::$app->jdate->date("l jS F Y h:i A", strtotime($model->created_at));
            }    
        ],
        [
            'attribute'     =>  'share',
            'content'       => function ($model, $key, $index, $column) {
                return Module::t('social','SHARE.'.$model->share);
            }
        ],
        [
            'attribute'     =>  'status',
            'content'       => function ($model, $key, $index, $column) {
                return Module::t('social','STATUS.'.$model->status);
            }
        ],
        [
            'attribute'     =>  'auth',
            'content'       => function ($model, $key, $index, $column) {
                return Module::t('social','AUTH.'.$model->auth);
            }
        ],
        [
            'attribute'     => 'created_at',
            'content' => function ($model, $key, $index, $column) {
                return Yii::$app->jdate->date("l jS F Y h:i A", strtotime($model->created_at));
            }            
        ],
        [
            'class'     => 'yii\grid\ActionColumn',
            'template'  => '{share}{active}{delete}',
            'buttons'   =>  [
                'share'     => function ($url, $model) use($pjax) {
                    if ($model->share === Social::SHARE_POST) {
                        return Html::a('<span class="glyphicon glyphicon-resize-full"></span>', ['share','id' => $model->id], [
                                    'title' => Module::t('social', '_admin.btn.share.all.title'),
                                    'data-pjax' => $pjax->getId(),
                                    'style' => 'font-size:22px;padding-right:10px;'
                        ]);
                    } else {
                        return Html::a('<span class="glyphicon glyphicon-resize-small"></span>', ['share','id' => $model->id], [
                                    'title' => Module::t('social', '_admin.btn.share.post.title'),
                                    'data-pjax' => $pjax->getId(),
                                    'style' => 'font-size:22px;padding-right:10px;'
                        ]);
                    }
                },                
                'active'     => function ($url, $model) use($pjax) {
                    if ($model->status === Social::STATUS_DEACTIVE) {
                        return Html::a('<span class="glyphicon glyphicon-thumbs-up"></span>', ['status','id' => $model->id], [
                                    'title' => Module::t('social', '_admin.btn.active.title'),
                                    'data-pjax' => $pjax->getId(),
                                    'style' => 'font-size:22px;padding-right:10px;'
                        ]);
                    } else {
                        return Html::a('<span class="glyphicon glyphicon-thumbs-down"></span>', ['status','id' => $model->id], [
                                    'title' => Module::t('social', '_admin.btn.deactive.title'),
                                    'data-pjax' => $pjax->getId(),
                                    'style' => 'font-size:22px;padding-right:10px;'
                        ]);
                    }
                },
                'delete'    =>  function($url, $model) {
                    return Html::a('<span class="glyphicon glyphicon-trash"></span>', ['delete','id' => $model->id], [
                            'title'         =>  Module::t('social', '_admin.btn.delete.title'),
                            'data-pjax'     =>  0,
                            'data-confirm'  =>  Module::t('social', '_admin.btn.delete.dataconfirm'),
                            'data-method'   => 'post',
                            'style' => 'font-size:22px;padding-right:10px;'
                        ]);                                                            
                }                        
                
            ]
            
        ]
    ]]);
    Pjax::end();
$js         =   <<<JS
var pjaxDiv =   $("#{$pjax->getId()}");
pjaxDiv.on('pjax:send',function(){
    pjaxDiv.append('<div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>');
});
pjaxDiv.on('pjax:complete',function(){
    pjaxDiv.find('.overlay').remove();
});
JS;
$this->registerJs($js);