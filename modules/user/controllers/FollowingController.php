<?php

namespace app\modules\user\controllers;

use Yii;
use app\modules\user\models\Following;
use app\modules\user\models\Unwanted;
use app\modules\user\models\User;
use yii\data\Pagination;
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
        return [
            'access'    =>  [
                'class' =>  AccessControl::className(),
                'only'  =>  ['follow'],
                'rules' =>  [
                    [
                        'actions'   =>  ['follow'],
                        'roles'     =>  ['@','?'],
                        'allow'     =>  true
                    ]
                ],
                
            ]
        ];
    }
    
    
    // Import google contacts to find freinds
//    public function actions()
//    {
//        return [
//            'import' => [
//                'class'             =>  'yii\authclient\AuthAction',
//                'clientCollection'  =>  'contactClientCollection',
//                'successCallback'   =>  [$this, 'importCallback'],
//                'successUrl'        =>  Yii::$app->request->referrer,
//            ]
//        ];
//    }    
//    
    /**
     * 
     * @param \yii\authclient\BaseClient $client
     */
//    public function importCallback($client)
//    {   
//        $attributes =   $client->getUserAttributes();
//        $result     =   $client->api("https://www.google.com/m8/feeds/contacts/default/full?max-results=9999");
//        
//    }    
//    
    public function actionFollowers($username = '')
    {
        $user   =   $this->findUser($username);
        $query  =   Following::find()->where(['followed_user_id' => $user->id,'status'=>  Following::STATUS_ACTIVE]);
        $countQuery= clone $query;
        $count  =   $countQuery->count();
        if ($count == 0){
            return $this->redirect(Yii::$app->urlManager->createUrl("@{$username}"));
        }
        $pages = new Pagination(['totalCount' => $count]);
        $models = $query->offset($pages->offset)
            ->limit($pages->limit)
            ->all();
        return $this->render('followers', [
            'models'   =>  $models,
            'pages'    =>  $pages,
            'user'     =>  $user
        ]);
    }

    public function actionFollowing($username)
    {
        $user   =   $this->findUser($username);
        $query  =   Following::find()->where(['user_id' => $user->id,'status'=>  Following::STATUS_ACTIVE]);
        $countQuery= clone $query;
        $count  =   $countQuery->count();
        if ($count == 0){
            return $this->redirect(Yii::$app->urlManager->createUrl("@{$username}"));
        }
        $pages = new Pagination(['totalCount' => $count]);
        $models = $query->offset($pages->offset)
            ->limit($pages->limit)
            ->all();
        return $this->render('following', [
            'models'    =>  $models,
            'pages'     =>  $pages,
            'user'      =>  $user
        ]);
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
     * 
     * @param string $username
     * @return User
     * @throws NotFoundHttpException
     */
    protected function findUser($username)
    {
        if ($username == NULL){
            throw new NotFoundHttpException('The requested page does not exist.');
        } else if (($user = User::findOne(['username'=>$username])) != NULL && $user->status != User::STATUS_BLOCK){
            return $user;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }    
}
