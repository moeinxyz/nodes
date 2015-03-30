<?php
/* @var $this \yii\web\View */
/* @var $model app\modules\post\models\Post */
use app\modules\post\Module;
$id             =   base_convert($model->id, 10, 36);
$recommendUrl   =   Yii::$app->urlManager->createUrl(["@{$model->getUser()->one()->username}/{$model->url}/recommend"]);
$recommendedDom     =   Module::t('post','header.view.recommended').'<i class="glyphicon glyphicon-star"></i>';
$notRecommenedDom   =   Module::t('post','header.view.recommend').'<i class="glyphicon glyphicon-star-empty"></i>';

$js=<<<JS
$("a#recommend").on('click',function(){
    jQuery.post("{$recommendUrl}",function(data){
        if (data.status === "RECOMMENDED"){
            $("a#recommend").html('{$recommendedDom}');
        } else if (data.status === "NOTRECOMMENDED"){
            $("a#recommend").html('{$notRecommenedDom}');
        }
    });
    return false;
});
             
JS;
$this->registerJs($js);