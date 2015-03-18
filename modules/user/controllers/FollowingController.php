<?php

namespace app\modules\user\controllers;

use Yii;
use app\modules\user\models\Following;
use app\modules\user\models\Unwanted;
use app\modules\user\models\User;

use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;

/**
 * FollowingController implements the CRUD actions for Following model.
 */
class FollowingController extends Controller
{
    public function actionFollow()
    {
        $username = Yii::$app->request->post('username');
        $user = $this->findUser($username);
        
        if (Yii::$app->user->isGuest){
            return $this->renderAjax('_follow',['guest'=>true,'username'=>$username]);
        } else if (Yii::$app->user->id != $user->id){
            $model = Following::getModelIfNotExist(Yii::$app->user->id,$user->id);
            $model->toggleFollow();
            return $this->renderAjax('_follow',['guest'=>false,'model'=>$model]);
        } else {
            throw new \yii\web\HttpException(403);
        }            
    }

    
    public function actionSuggest()
    {
        
    }

    protected function findUser($username)
    {
        if ($username == NULL){
            throw new NotFoundHttpException('The requested page does not exist.');
        } else if (($user = User::findOne(['username'=>$username])) != NULL){
            return $user;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }    
}
