<?php

namespace app\modules\user\controllers;

use Yii;
use app\modules\user\models\Following;
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
    public function behaviors()
    {
        return [];
    }

    public function actionTest()
    {
        return "1";
    }

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

    /**
     * Finds the Following model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Following the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Following::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
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
