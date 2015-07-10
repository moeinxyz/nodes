<?php

namespace app\modules\social\controllers;

use Yii;
use app\modules\social\models\Social;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
/**
 * SocialController implements the CRUD actions for Social model.
 */
class SocialController extends Controller
{
    public function behaviors()
    {
        return [
            'access'    =>  [
                'class' =>  AccessControl::className(),
                'only'  =>  ['admin','delete','share','status','auth'],
                'rules' =>  [
                    [
                        'actions'   =>  ['admin','auth'],
                        'roles'     =>  ['@'], 
                        'allow'     =>  true
                    ],
                    [
                        'actions'   =>  ['delete','share','status'],
                        'roles'     =>  ['@'],
                        'verbs'     =>  ['POST'],
                        'allow'     =>  true
                    ]
                ]
                
            ]
        ];
    }
    
    public function actions()
    {
        return [
            'auth' => [
                'class'             =>  'yii\authclient\AuthAction',
                'clientCollection'  =>  'socialClientCollection',
                'successCallback'   =>  [$this, 'authCallback'],
                'successUrl'        =>  Yii::$app->request->referrer,
            ]
        ];
    }
    
    /**
     * 
     * @param \yii\authclient\BaseClient $client
     */
    public function authCallback($client)
    {   
        $attributes =   $client->getUserAttributes();
        if ($client->getId() === 'linkedin'){
            $this->linkedinAuth($client,$attributes);
        } else if ($client->getId() === 'twitter'){
            $this->twitterAuth($client, $attributes);
        }
        // add facebook later
        return $this->redirect('social/admin');
    }
    
    
    private function linkedinAuth(\yii\authclient\clients\LinkedIn $client,array $attributes)
    {
        $social         =   $this->getSocial(Social::TYPE_LINKEDIN,$attributes['id']);
        $social->name   =   $attributes['first-name'].' '.$attributes['last-name'];
        $social->url    =   $attributes['public-profile-url'];
        $social->auth   =   Social::AUTH_AUTH;
        $social->status =   Social::STATUS_ACTIVE;
        $social->token  =   serialize($client);
        if ($social->save()){
            Yii::$app->session->setFlash('social.add',  Social::TYPE_LINKEDIN);
        } else {
            Yii::$app->session->setFlash('social.wrong',  Social::TYPE_LINKEDIN);
        }
    }

    
    private function twitterAuth(\yii\authclient\clients\Twitter $client,array $attributes)
    {
        $social         =   $this->getSocial(Social::TYPE_TWITTER, $attributes['id_str']);
        $social->name   =   $attributes['name'].' ( '.$attributes['screen_name'].' ) ';
        $social->url    =   'https://twitter.com/'.$attributes['screen_name'];
        $social->auth   =   Social::AUTH_AUTH;
        $social->status =   Social::STATUS_ACTIVE;
        $social->token  =   serialize($client);
        if ($social->save()){
            Yii::$app->session->setFlash('social.add',  Social::TYPE_TWITTER);
        } else {
            Yii::$app->session->setFlash('social.wrong',  Social::TYPE_TWITTER);
        }
    }

    /**
     * 
     * @param string $type
     * @param string $mediaId
     * @return Social
     */
    private function getSocial($type,$mediaId)
    {
        $params     =   [
            'media_id'      =>  $mediaId,
            'user_id'       =>  Yii::$app->user->getId(),
            'type'          =>  $type
        ];
        $social =   Social::findOne($params);
        if ($social === NULL){
            $social = new Social;
            $social->type       =   $type;
            $social->media_id   =   $mediaId;
        }
        return $social;
    }

    public function actionAdmin()
    {
        $dataProvider = $this->getDataProvider();
        
        return $this->render('admin', [
            'dataProvider' => $dataProvider,
        ]);        
    }
    
    public function actionShare($id)
    {
        if (Yii::$app->request->isAjax){
            $social = $this->findModel($id);
            if ($social->share === Social::SHARE_ALL){
                $social->share = Social::SHARE_POST;
            } else {
                $social->share = Social::SHARE_ALL;
            }
            $social->save();
            
            $dataProvider = $this->getDataProvider();
            return $this->renderAjax('_admin', [
                'dataProvider' => $dataProvider,
            ]);             
        } else {
            return $this->redirect('social/admin');
        }
    }
    
    public function actionStatus($id)
    {
        if (Yii::$app->request->isAjax){
            $social = $this->findModel($id);
            if ($social->status === Social::STATUS_ACTIVE){
                $social->status = Social::STATUS_DEACTIVE;
            } else {
                $social->status = Social::STATUS_ACTIVE;
            }
            $social->save();
            
            $dataProvider = $this->getDataProvider();
            return $this->renderAjax('_admin', [
                'dataProvider' => $dataProvider,
            ]);             
        } else {
            return $this->redirect('social/admin');
        }
    }
    
    /**
     * 
     * @return ActiveDataProvider
     */
    protected function getDataProvider()
    {
        $dataProvider   =   new ActiveDataProvider([
            'query'     =>  Social::find()->where('user_id=:user_id',['user_id'=>Yii::$app->user->getId()]),
            'sort'      =>  ['defaultOrder' => ['created_at'=>SORT_DESC]]
        ]);   
        $dataProvider->sort->route  =   'social/admin';
        return $dataProvider;
    }

    /**
     * Deletes an existing Social model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['admin']);
    }

    /**
     * Finds the Social model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Social the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Social::findOne($id)) !== null && $model->user_id === Yii::$app->user->getId()) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
