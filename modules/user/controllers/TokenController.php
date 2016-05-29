<?php

namespace app\modules\user\controllers;

use Yii;
use app\modules\user\models\Token;
use app\modules\user\models\User;
use app\modules\user\models\ResetPasswordForm;
use app\modules\user\models\ChangeEmailForm;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\HttpException;
use yii\filters\AccessControl;
use app\modules\user\Module;
/**
 * TokenController implements the CRUD actions for Token model.
 */
class TokenController extends Controller
{
    public function behaviors()
    {
        return [
            'access'    =>  [
                'class' =>  AccessControl::className(),
                'only'  =>  ['activation','reset','change'],
                'rules' =>  [
                    [
                        'actions'       =>  ['activation','reset'],
                        'roles'         =>  ['?'],
                        'allow'         =>  true,
                    ],
                    [
                        'actions'   =>  ['change'],
                        'roles'     =>  ['@'],                        
                        'allow'     =>  true,
                    ]
                ]
            ]
        ];
    }

    public function actionActivation($id, $code) {        
        $token  = Token::findOne($id);
        if (       $token === NULL 
                || $token->token        !=  urldecode($code) 
                || $token->status       !=  Token::STATUS_UNUSED
                || $token->type         !=  Token::TYPE_ACCOUNT_ACTIVATION
                || $token->user->status !=  User::STATUS_DEACTIVE
                ){
            throw new HttpException(404);
        } else if(strtotime($token->created_at) < (time() - Yii::$app->controller->module->tokenLimitTime)){
            Yii::$app->session->setFlash('token.activation.expired');
            $this->redirect(['user/activation']);
        } else {
            if ($token->activeAccount()){
                Yii::$app->session->setFlash('token.activation.successful');
                Yii::$app->user->login($token->user);
                return $this->redirect(Yii::$app->urlManager->createUrl(['','activation'=>true]));
            } else {
                throw new HttpException(500);
            }
        }
    }

    public function actionReset($id = null,$code = null){
        $token  = Token::findOne($id);
        if (       $token               === NULL 
                || $token->token        !=  urldecode($code) 
                || $token->status       !=  Token::STATUS_UNUSED
                || $token->type         !=  Token::TYPE_RESET_PASSWORD
                ){
            throw new HttpException(404);
        } else if(strtotime($token->created_at) < (time() - Yii::$app->controller->module->tokenLimitTime)){
            Yii::$app->session->setFlash('token.reset.expired');
            return $this->redirect(['user/reset']);
        } else {
            $model  =   new ResetPasswordForm(['_token'=>$token]);
            if ($model->load(Yii::$app->request->post()) && $model->changePassword()){
                Yii::$app->session->setFlash('token.reset.successful');
                Yii::$app->user->login($token->user);
                return $this->redirect(Yii::$app->urlManager->createUrl(['','reset'=>true]));                
            }
            return $this->render('password',['model'=>$model]);
        }
    }

    public function actionChange($id,$code,$email = NULL)
    {
        $code   = urldecode($code);
        $email  = urldecode($email);
        $token  = Token::findOne($id);
        
        if ($token == NULL){
            throw new HttpException(404);
        } else if ($token->user_id != Yii::$app->user->id){
            throw new HttpException(403);
        }
        
        $isNotValidToken =  $token->token   != $code || 
                            $token->status  != Token::STATUS_UNUSED || 
                            ($token->type == Token::TYPE_CHANGE_EMAIL_STEP1 && $email != NULL) || 
                            ($token->type == Token::TYPE_CHANGE_EMAIL_STEP2 && $email == NULL);
                    
        if ($isNotValidToken){
            throw new HttpException(404);
        } else if(strtotime($token->created_at) < (time() - Yii::$app->controller->module->tokenLimitTime)){
            Yii::$app->session->setFlash('token.email.expired');
            return $this->redirect(['user/setting']);
        } else if ($token->type === Token::TYPE_CHANGE_EMAIL_STEP1 && $email == NULL) {
            $model = new ChangeEmailForm;
            if ($model->load(Yii::$app->request->post()) && $model->validate()){
                if ($this->notifyEmailChangeConfirmation($token, $model->email)){
                    return $this->renderAjax('email/_step1_done',['email'=>$model->email]);
                } else {
                    return $this->renderAjax('email/_step1_form',['model'=>$model,'error'=>true]);
                }
            }
            return $this->render('email/step1',['model'=>$model]);
        } else if ($token->type === Token::TYPE_CHANGE_EMAIL_STEP2 && $email != NULL) {
            $userid =   $token->user_id;
            $hash   =   Token::generateEmailValidationToken($userid, $email);
            if ($hash != $code){
                throw new HttpException(404);
            } else if($token->changeEmail($email)) {
                Yii::$app->session->setFlash('token.email.step2.successful');
            } else {
                Yii::$app->session->setFlash('token.email.step2.failed');
            }
            return $this->redirect(['user/setting']);
        } else {
            throw new HttpException(404);
        }
    }
    
    private function notifyEmailChangeConfirmation($token,$email)
    {
        $newToken = Token::getNewMailConfirmToken($token, $token->user_id, $email);
        
        if ($newToken){
            $url    =   Yii::$app->urlManager->createAbsoluteUrl(['token/change',
                                                                'id'=>$newToken->id,
                                                                'code'=>urlencode($newToken->token),
                                                                'email'=>urlencode($email)]);
            \Yii::$app->mailer
                ->compose('@mail/confirmEmailChangeStep2',['email'=>$email,'url'=>$url])
                ->setSubject(Module::t('mail','token.emailchange2.title'))
                ->setFrom([Yii::$app->params['noreply-email']  =>  Module::t('mail','sender.name')])                    
                // ->setTags(['emailchange','step2',  Yii::$app->name])  // Mailgun doesn't support tags
                ->setTo($email)
                ->send();                             
            return true;
        }
        return false;
    }

    /**
     * Finds the Token model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Token the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Token::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('yii','Page not found.'));
        }
    }
}
