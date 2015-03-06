<?php
/* @var $model \app\modules\user\models\Following */
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use app\modules\user\Module;
use app\modules\user\models\Following;
$form = ActiveForm::begin([
    'id'                    => 'follow-form',
    'action'                =>  Yii::$app->urlManager->createUrl(['user/following/follow']),
    'options'               => ['class'   =>  'form-horizontal','data-pjax'=>true],
    'fieldConfig' => [
        'template'  =>  '{input}'
    ],
]);    
echo Html::hiddenInput('username', ($guest)?$username:$model->followedUser->username);
if ($guest || $model->status === Following::STATUS_DEACTIVE){
    $btnTitle = Module::t('following','_follow.follow');
} else {
    $btnTitle = Module::t('following','_follow.unfollow');
}

echo Html::submitButton($btnTitle, ['class' => 'btn btn-default bg-olive margin']);
ActiveForm::end();
if ($guest){
$js=<<<JS
    $("#loginmodal").modal();
JS;
$this->registerJs($js);
}
?>
