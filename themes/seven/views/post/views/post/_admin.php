<?php
use yii\grid\GridView;
use yii\helpers\Html;
use app\modules\post\Module;
use app\modules\post\models\Post;
use yii\widgets\Pjax;
use yii\helpers\StringHelper;
use app\components\Helper\PersianNumber;
use Miladr\Jalali\jDateTime;

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
    'dataProvider' => $dataProvider,
    'columns' => [
        [
            'attribute'     => 'title',
            'content' => function ($model, $key, $index, $column) use($username){
                if (($model->title === NULL || $model->title === '')){
                    $title = Module::t('post','_admin.title.null');
                } else {
                    $title = $model->title;
                }
                
                if (($model->content === NULL || $model->content === '')){
                    $text = Module::t('post','_admin.content.null');
                } else {
                    $text = $model->content;
                }                
                if ($model->status === Post::STATUS_PUBLISH) {
                    $content = Html::a($title, Yii::$app->urlManager->createUrl("$username/{$model->url}"), ['target' => '_blank', 'data-pjax' => 0]);    
                } else {
                    $content = Html::a($title, ['edit', 'id' => base_convert($model->id, 10, 36),'type'=>'autosave'], ['target' => '_blank', 'data-pjax' => 0]);    
                }
                
                $content .= '<br><span class="small-font-size">';
                $content .= StringHelper::truncateWords(strip_tags($text), 14);
                $content .= '</span>';
                return $content;
            }
                ],
                [
                    'attribute'     => 'updated_at',
                    'content'       => function ($model, $key, $index, $column) {
                        return '<span class="small-font-size">' . jDateTime::date("l jS F Y h:i A", strtotime($model->updated_at)) . '</span>';
                    }
                ],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{comment}{view}{edit}{pin}{trash}{delete}',
                    'buttons' => [
                        'comment'   => function ($url, $model) use($pjax, $username) {
                            return Html::a('<span class="glyphicon glyphicon-comment"></span>', ['/post/comments', 'pid' => base_convert($model->id, 10, 36)], [
                                        'title' => Module::t('post', '_admin.btn.comment.title',['count'=>  PersianNumber::convertNumberToPersian($model->comments_count)]),
                                        'data-pjax' => 0,
                                        'style' => 'font-size:22px;padding-right:10px;'
                            ]);                                          
                        },
                        'stat'      =>  function($url,$model){
                            return Html::a('<span class="glyphicon glyphicon-stats"></span>', ['stat', 'id' => base_convert($model->id, 10, 36)], [
                                        'title' => Module::t('post', '_admin.btn.stat.title'),
                                        'data-pjax' => 0,
                                        'style' => 'font-size:22px;padding-right:10px;'
                            ]);                                
                        },
                        'view'      => function ($url, $model) use($pjax, $username) {
                            if ($model->status === Post::STATUS_PUBLISH) {
                                return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', Yii::$app->urlManager->createUrl("$username/{$model->url}"), [
                                            'title' => Module::t('post', 'ـadmin.btn.view.title'),
                                            'data-pjax' => 0,
                                            'style' => 'font-size:22px;padding-right:10px;'
                                ]);
                            } else {
                                return Html::a('<span class="glyphicon glyphicon-eye-close"></span>', ['preview', 'id' => base_convert($model->id, 10, 36)], [
                                            'title' => Module::t('post', 'ـadmin.btn.preview.title'),
                                            'data-pjax' => 0,
                                            'style' => 'font-size:22px;padding-right:10px;'
                                ]);
                            }
                        },
                        'edit' => function ($url, $model) use($pjax, $username) {
                            return Html::a('<span class="glyphicon glyphicon-edit"></span>', ['edit', 'id' => base_convert($model->id, 10, 36),'type'=>'autosave'], [
                                        'title' => Module::t('post', 'ـadmin.btn.edit.title'),
                                        'data-pjax' => 0,
                                        'style' => 'font-size:22px;padding-right:10px;'
                            ]);
                        },
                        'pin' => function($url, $model) use ($pjax, $status) {
                            if ($model->status != Post::STATUS_PUBLISH){
                                return;
                            }
                            if ($model->pin === Post::PIN_ON) {
                                return Html::a('<span class="glyphicon glyphicon-star-empty"></span>', ['pin','status'=>$status,'id' => base_convert($model->id, 10, 36)], [
                                            'title' => Module::t('post', 'ـadmin.btn.unpin.title'),
                                            'data-pjax' => $pjax->getId(),
                                            'style' => 'font-size:22px;padding-right:10px;'
                                ]);
                            } else {
                                return Html::a('<span class="glyphicon glyphicon-star"></span>', ['pin','status'=>$status,'id' => base_convert($model->id, 10, 36)], [
                                            'title' => Module::t('post', 'ـadmin.btn.pin.title'),
                                            'data-pjax' => $pjax->getId(),
                                            'style' => 'font-size:22px;padding-right:10px;'
                                ]);
                            }                                
                        },
                        'trash' => function($url, $model) use ($pjax, $status) {
                            if ($model->status != Post::STATUS_TRASH) {
                                return Html::a('<span class="glyphicon glyphicon-trash"></span>', ['trash','status'=>$status,'id' => base_convert($model->id, 10, 36)], [
                                            'title' => Module::t('post', '_admin.btn.trash.title'),
                                            'data-pjax' => $pjax->getId(),
                                            'style' => 'font-size:22px;padding-right:10px;'
                                ]);
                            } else {
                                return Html::a('<span class="glyphicon glyphicon-refresh"></span>', ['trash','status'=>$status,'id' => base_convert($model->id, 10, 36)], [
                                            'title' => Module::t('post', '_admin.btn.untrash.title'),
                                            'data-pjax' => $pjax->getId(),
                                            'style' => 'font-size:22px;padding-right:10px;'
                                ]);
                            }
                        },
                        'delete'    =>  function($url, $model) use ($pjax, $status) {
                            if ($model->status === Post::STATUS_TRASH){
                                return Html::a('<span class="glyphicon glyphicon-trash"></span>', ['delete','status'=>$status,'id' => base_convert($model->id, 10, 36)], [
                                        'title'         =>  Module::t('post', '_admin.btn.delete.title'),
                                        'data-pjax'     =>  0,
                                        'data-confirm'  =>  Module::t('post', '_admin.btn.delete.dataconfirm'),
                                        'data-method'   => 'post',
                                        'style' => 'font-size:22px;padding-right:10px;'
                                    ]);                                                            
                            }
                        }
                            ]
                        ],
                    ],
                ]);
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
//$this->registerJs($js);
?>            