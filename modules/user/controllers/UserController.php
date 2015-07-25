<?php

namespace app\modules\user\controllers;

use Yii;
use yii\base\Model;
use app\modules\user\models\User;
use app\modules\user\models\Attributes;
use app\modules\user\models\Url;
use app\modules\user\models\LoginForm;
use app\modules\user\models\ResetForm;
use app\modules\user\models\ActivationForm;
use app\modules\user\models\Token;
use app\modules\user\models\ChangePasswordForm;
use app\modules\user\models\ChangeSettingForm;
use app\modules\user\models\ChangeUsernameForm;
use app\modules\user\models\PublicProfileForm;
use app\modules\user\Module;
use yii\web\UploadedFile;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\filters\AccessControl;
use yii\bootstrap\ActiveForm;
use yii\helpers\BaseUrl;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
{
    public function actions()
    {
        return [
            'auth' => [
                'class'             =>  'yii\authclient\AuthAction',
                'successCallback'   =>  [$this, 'authCallback'],
                'successUrl'        =>  Yii::$app->request->referrer,
            ]
        ];
    }
    
    public function behaviors()
    {
        return [
            'access'    =>  [
                'class' =>  AccessControl::className(),
                
                'only'  =>  ['activation','join','login','logout','profile','reset','setting','auth'],
                'rules' =>  [
                    [
                        'actions'       =>  ['join','auth','activation','login','reset'],
                        'roles'         =>  ['?'],
                        'allow'         =>  true,
                    ],
                    [
                        'actions'   =>  ['setting','profile'],
                        'roles'     =>  ['@'],
                        'allow'     =>  true
                    ],
                    [
                        'actions'   =>  ['logout'],
                        'roles'     =>  ['@'],
                        'verbs'     =>  ['GET'],                        
                        'allow'     =>  true,
                    ]
                ]
            ]
        ];
    }

    public function beforeAction($action) {
        parent::beforeAction($action);
        /**
         * Github change referrer for unknown reason,so use returnUrl for auth client
         */
        if ($action->id === 'auth' && Yii::$app->session->get('returnUrl') == NULL){
            Yii::$app->session->set('returnUrl', Yii::$app->request->referrer);    
        }
        return true;
    }

    /**
     * 
     * @param \yii\authclient\BaseClient $client
     */
    public function authCallback($client)
    {   
        $attributes = $client->getUserAttributes();
        
        if ($client->getId() === 'google'){
            $model  =   $this->googleAuth($attributes);
        } else if ($client->getId() === 'facebook'){
            $model  =   $this->facebookAuth($attributes);
        } else if ($client->getId() === 'linkedin'){
            $model  =   $this->linkedinAuth($attributes);
        } else if ($client->getId()  === 'github'){
            $model  =   $this->githubAuth($attributes);
        }else {
            throw new \yii\web\HttpException(404);
        }

        $user = User::oauthLogin($model);
        
        if ($user != NULL){
            Yii::$app->user->login($user, 3600*24*7);
            return $this->goBack($this->getLoginReturnUrl());
        } else {
            return $this->goBack(Yii::$app->request->referrer);
        }
    }
 
    /**
     * 
     * @return type
     */
    public function actionLogin()
    {
        if (!Yii::$app->request->isAjax){
            return $this->goBack(Yii::$app->request->referrer);
        } else {
            $model = new LoginForm();    
            if (Yii::$app->request->isPjax && $model->load(Yii::$app->request->post()) && $model->login()) {
                return $this->goBack($this->getLoginReturnUrl());
            } else {
                return $this->renderAjax('login',['model'=>$model]);   
            }            
        }
    }

    
    private function getLoginReturnUrl()
    {
        $default    =   Yii::$app->session->get('returnUrl',Yii::$app->request->referrer);
        $parts      =   parse_url($default);
        foreach (Yii::$app->params['notValidLoginRedirectPath'] as $path){
            if (strpos($path, $parts['path']) == 0){
                return BaseUrl::home();
            }
        }        
        return $default;
    }
    
    /**
     * 
     * @return type
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->goBack($this->getLogoutReturnUrl());
    }    

    private function getLogoutReturnUrl()
    {
        $default    =   strtolower(Yii::$app->request->referrer);
        $parts      =   parse_url($default);
        if ($parts['path'] == '/'){
            return Yii::$app->request->referrer;
        }
        foreach (Yii::$app->params['validLogoutRedirectPath'] as $path){
            if (strpos($path, $parts['path']) == 0){
                return Yii::$app->request->referrer;        
            }
        }
        return BaseUrl::home();
    }

    
    /**
     * 
     * @return type
     */
    public function actionJoin()
    {
        $model  =   new User();
        $model->load(Yii::$app->request->post());
        if (Yii::$app->request->isAjax){
            $model->setScenario('join_ajax');
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        } else if (Yii::$app->request->isPost && ($model->setScenario('join_post') || true) && $model->save()){
            $token  =   Token::getNewActivationToken($model->id);
            $url    =   Yii::$app->urlManager->createAbsoluteUrl(['token/activation','id'=>$token->id,'code'=>urlencode($token->token)]);
            
            \Yii::$app->mailer
                ->compose('@mail/join', ['url' => $url,'email'=>$model->email])
                ->setSubject(Module::t('mail','user.join.title'))
                ->setFrom([Yii::$app->params['noreply-email']  =>  Module::t('mail','sender.name')])                    
                ->setTags(['activation',  Yii::$app->name])
                ->setTo($model->email)
                ->send();
                Yii::$app->session->setFlash('user.join.successful');            
        }
        return $this->render('join',['model'=>$model]);
    }


    /**
     * 
     * @return type
     */
    public function actionReset()
    {
        $model  =   new ResetForm();
        
        if ($model->load(Yii::$app->request->post()) && $model->validate()){
            $token  =   Token::getNewResetToken($model->getUser()->id);
            $url    =   Yii::$app->urlManager->createAbsoluteUrl(['token/reset','id'=>$token->id,'code'=>urlencode($token->token)]);
            
            \Yii::$app->mailer
                ->compose('@mail/reset', ['url' => $url,'email'=>$model->email])
                ->setSubject(Module::t('mail','user.reset.title'))
                ->setFrom([Yii::$app->params['noreply-email']  =>  Module::t('mail','sender.name')])
                ->setTags(['reset',  Yii::$app->name])
                ->setTo($model->email)
                ->send();                
            Yii::$app->session->setFlash('reset_sent');
            Yii::$app->session->setFlash('user.reset.successful');
        }

        return $this->render('reset',['model'=>$model]);
    }
    
    /**
     * 
     * @return type
     */
    public function actionActivation()
    {
        $model  =   new ActivationForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()){
            $token  =   Token::getNewActivationToken($model->getUser()->id);
            $url    =   Yii::$app->urlManager->createAbsoluteUrl(['token/activation','id'=>$token->id,'code'=>urlencode($token->token)]);

            \Yii::$app->mailer
                ->compose('@mail/activation', ['url' => $url,'email'=>$model->email])
                ->setSubject(Module::t('mail','user.activation.title'))
                ->setFrom([Yii::$app->params['noreply-email']  =>  Module::t('mail','sender.name')])                    
                ->setTags(['activation',  Yii::$app->name])
                ->setTo($model->email)
                ->send();                
            Yii::$app->session->setFlash('user.activation.successful');
        }
        
        return $this->render('activation',['model'=>$model]);
    }
    
    /**
     * 
     */
    public function actionSetting()
    {
        $user           =   User::findOne(Yii::$app->user->id);
        $setting        =   $user->getSetting();
        
        $passwordModel  =   new ChangePasswordForm();
        $usernameModel  =   new ChangeUsernameForm(['username'=>$user->username]);
        $settingModel   =   new ChangeSettingForm($setting);
        if ($user->password != NULL){
            $passwordModel->setScenario('notOauthUser');
        }
        
        $type   =   Yii::$app->request->post('type');
        
        if (Yii::$app->request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($type === 'password'){
                $passwordModel->load(Yii::$app->request->post());    
                return ActiveForm::validate($passwordModel);
            } else if ($type === 'username'){
                $usernameModel->load(Yii::$app->request->post());    
                return ActiveForm::validate($usernameModel);
            } else if ($type === 'setting'){
                $settingModel->load(Yii::$app->request->post());
                return ActiveForm::validate($settingModel);
            } else {
                throw new \yii\web\HttpException(404);
            }
        } else if (Yii::$app->request->isPost){
            if($type === 'password' && $passwordModel->load(Yii::$app->request->post()) && $passwordModel->changePassword()){
                $this->notifyPasswordChanged($user->email);
            } else if ($type === 'email') {
                $this->requestEmailChange($user->id, $user->email);
            } else if ($type === 'setting' && $settingModel->load(Yii::$app->request->post()) && $settingModel->save()){
                Yii::$app->session->setFlash('user.setting.setting.successful');
            } else if($type === 'username' && $usernameModel->load(Yii::$app->request->post()) && $usernameModel->save()){
                Yii::$app->session->setFlash('user.setting.username.successful');
            }
        }

        return $this->render('setting',[
            'settingModel'      =>  $settingModel,
            'passwordModel'     =>  $passwordModel,
            'usernameModel'     =>  $usernameModel,
            'email'=>$user->email
        ]);
    }
    
    /**
     * 
     * @param type $type
     * @return type
     */
    public function actionProfile($type = 'public')
    {
        $user   =   User::findOne(Yii::$app->user->id);
        $public =   new PublicProfileForm([
            'name'                      =>  $user->name,
            'tagline'                   =>  $user->tagline,
            // has cover ,profile picture or not
            'profileStatus'             =>  $user->profile_pic,
            'coverStatus'               =>  $user->profile_cover,
            // any profile or cover picture uploaded yet?
            'activeProfilePictureStatus'=>  ($user->uploaded_profile_pic === User::UPLOADED_PIC_YES)?true:false,
            'activeCoverPictureStatus'  =>  ($user->uploaded_cover_pic === User::UPLOADED_PIC_YES)?true:false
        ]);
        $urls       =   Url::find()->where('user_id=:user_id',['user_id'=>Yii::$app->user->id])->all();
        $newUrl     =   new Url;
        foreach ($urls as $url) $url->setScenario('update');
        // Ajax Validation
        if (Yii::$app->request->post('ajax') === 'urls-form'){
            Yii::$app->response->format = Response::FORMAT_JSON;
            Model::loadMultiple($urls, Yii::$app->request->post());
            return ActiveForm::validateMultiple($urls);
        } else if (Yii::$app->request->post('ajax') === 'public-form') {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $public->load(Yii::$app->request->post());    
            return ActiveForm::validate($public);
        } else if (Yii::$app->request->post('ajax') === 'url-form') {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $newUrl->load(Yii::$app->request->post());
            return ActiveForm::validate($newUrl);
        // Post Submit or Pjax Request
        } else if (Yii::$app->request->post('public-button') !== NULL){ //Post Request
            $public = $this->updateProfile($public);
        } else if (Yii::$app->request->post('urls-update') !== NULL){ //Post Request
            $urls  = $this->updateUrls($urls);
        } else if (Yii::$app->request->post('url-add') !== NULL) {// Pjax Request
            return $this->addUrl($newUrl);
        }
        $user   = User::findOne(Yii::$app->getUser()->getId());// because need fresh instance of user profile
        return $this->render('profile',['urls'=>$urls,'public'=>$public,'newUrl'=>$newUrl,'user'=>$user]);
    }

    /**
     * 
     * @param Url $newUrl
     * @return type
     */
    private function addUrl(Url $newUrl){
        $newUrl->load(Yii::$app->request->post());
        if ($newUrl->save()){
            $newUrl = new Url;
            Yii::$app->session->setFlash('user.url.add.successful');
        }
        $urls   =   Url::find()->where('user_id=:user_id',['user_id'=>Yii::$app->user->id])->all();
        foreach ($urls as $url) $url->setScenario('update');                
        return $this->renderAjax('profile/_inner_url',['urls'=>$urls,'newUrl'=>$newUrl]);        
    }

    /**
     * 
     * @param PublicProfileForm $profile
     * @return PublicProfileForm
     */
    private function updateProfile(PublicProfileForm $profile){
        $profile->profilePicture     =  UploadedFile::getInstance($profile, 'profilePicture');
        $profile->coverPicture       =  UploadedFile::getInstance($profile, 'coverPicture');
        $profile->load(Yii::$app->request->post());
        if ($profile->validate()){
            $id = md5(Yii::$app->user->id).base_convert(Yii::$app->user->id, 10, 36);
            if ($profile->profilePicture instanceof UploadedFile){
                $file = Yii::getAlias("@webTempPictures/{$id}.{$profile->profilePicture->getExtension()}");
                $profile->profilePicture->saveAs($file);
            }
            if ($profile->coverPicture instanceof UploadedFile){
                $file = Yii::getAlias("@webTempCovers/{$id}.{$profile->coverPicture->getExtension()}");
                $profile->coverPicture->saveAs($file);
            }
            if ($profile->update()){
                Yii::$app->session->setFlash('user.profile.public.successful');
            }
        }
        return $profile;
    }

    /**
     * 
     * @param type $urls
     * @return type
     */
    private function updateUrls($urls){
        Url::loadMultiple($urls, Yii::$app->request->post());
        foreach ($urls as $index=>$url){
            if ($url->url != ''){
                $url->save();
            } else {
                $url->delete();
                unset($url);
                unset($urls[$index]);
            }
        }        
        return $urls;
    }

    /**
     * 
     * @param type $email
     */
    private function notifyPasswordChanged($email)
    {
        \Yii::$app->mailer
            ->compose('@mail/passwordChanged')
            ->setSubject(Module::t('mail','user.pass_changed.title'))
            ->setFrom([Yii::$app->params['noreply-email']  =>  Module::t('mail','sender.name')])                
            ->setTags(['passwordChanged',  Yii::$app->name])
            ->setTo($email)
            ->send();                                
            Yii::$app->session->setFlash('user.setting.password.successful');        
    }
    
    /**
     * 
     * @param type $userid
     * @param type $email
     */
    private function requestEmailChange($userid,$email)
    {
        $token  =   Token::getNewMailChangeToken($userid);
        $url    =   Yii::$app->urlManager->createAbsoluteUrl(['token/change','id'=>$token->id,'code'=>urlencode($token->token)]);
        \Yii::$app->mailer
            ->compose('@mail/confirmEmailChangeStep1',['email'=>$email,'url'=>$url])
            ->setSubject(Module::t('mail','user.emailchange1.title'))
            ->setFrom([Yii::$app->params['noreply-email']  =>  Module::t('mail','sender.name')])                
            ->setTags(['emailchange','step1',  Yii::$app->name])
            ->setTo($email)
            ->send();                                
            Yii::$app->session->setFlash('user.setting.email.successful');                
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    
    /**
     * 
     * @param mixed $attributes
     * @return Attributes 
     */
    protected function googleAuth($attributes){
        $model                  =   new Attributes;
        $model->email           =   $attributes['emails'][0]['value'];
        
        $model->name            =   isset($attributes['displayName'])?$attributes['displayName']:NULL;
        $model->tagline         =   isset($attributes['tagline'])?$attributes['tagline']:NULL;
        
        if (isset($attributes['url'])){
            $model->addUrl(Url::TYPE_GOOGLE_PLUS, $attributes['url']);    
        }
        
        $urls   =   isset($attributes['urls'])?$attributes['urls']:[];
        foreach ($urls as $url){
            $type   = Url::getServiceByUrl($url['value']);
            $model->addUrl($type, $url['value']);
        }
        
        /**
         * @todo use it when worker become ready
         * $model->profile_pic     =   isset($attributes['image']['url'])?str_replace("?sz=50", "?sz=200", $attributes['image']['url']):NULL;
         * $model->profile_cover   =   isset($attributes['cover']['coverPhoto']['url'])?$attributes['cover']['coverPhoto']['url']:NULL;
         */
                
        return $model;
    }
    
    /**
     * 
     * @param mixed $attributes
     * @return Attributes 
     */    
    protected function facebookAuth($attributes){
        $model                  =   new Attributes;
        $model->email           =   $attributes['email'];
        $model->name            =   isset($attributes['name'])?$attributes['name']:NULL;
        
        $model->addUrl(Url::TYPE_FACEBOOK, "https://facebook.com/{$attributes['id']}");
        return $model;
    }
    
    
    /**
     * 
     * @param mixed $attributes
     * @return Attributes 
     */    
    protected function linkedinAuth($attributes){
        $model              =   new Attributes;
        $model->email       =   $attributes['email-address'];
        
        if (isset($attributes['first-name']) && $attributes['last-name']){
            $model->name    =   $attributes['first-name']." ".$attributes['last-name'];
        }
        
        if (isset($attributes['public-profile-url'])){
            $model->addUrl(Url::TYPE_LINKEDIN,$attributes['public-profile-url']);    
        }
        
        return $model;        
    }
    
    /**
     * 
     * @param mixed $attributes
     * @return Attributes 
     */    
    protected function githubAuth($attributes){
        $model              =   new Attributes;
        $model->email       =   $attributes['email'];
        
        $model->name        =   isset($attributes['name'])?$attributes['name']:NULL;
        $model->username    =   isset($attributes['login'])?$attributes['name']:NULL;
        $model->tagline     =   isset($attributes['bio'])?$attributes['bio']:NULL;
        
        if (isset($attributes['html_url'])){
            $model->addUrl(Url::TYPE_GITHUB,$attributes['html_url']);    
        }
        
        /**
         * @todo use it when worker become ready
         * $model->profile_pic =   $attributes['avatar_url'];
         */
        return $model;        
    }
}
