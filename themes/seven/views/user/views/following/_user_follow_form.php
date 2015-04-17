<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\widgets\Pjax;
use app\modules\user\models\Following;
use app\modules\user\Module;
/* @var $this \yii\web\View */
/* @var $user app\modules\user\models\User */

if (Yii::$app->user->id != $user->id){
    $pjax = Pjax::begin(['enablePushState'=>FALSE,'options'=>['class'=>'follow-form']]);
    $form = ActiveForm::begin([
        'id'                    => 'follow-form-'.$pjax->getId(),
        'action'                =>  Yii::$app->urlManager->createUrl(['user/follow']),
        'options'               => ['class'   =>  'form-horizontal','data-pjax'=>true],
        'fieldConfig' => [
            'template'  =>  '{input}'
        ],
    ]);    
    if (Following::isUserFollowing($user->id)){
        $btnTitle = Module::t('following','_follow.unfollow');
    } else {
        $btnTitle = Module::t('following','_follow.follow');
    }
    echo Html::hiddenInput('username', $user->username);
    echo Html::submitButton($btnTitle, ['class' => 'btn btn-default bg-olive margin']);
    ActiveForm::end();
    Pjax::end();    
}
?>