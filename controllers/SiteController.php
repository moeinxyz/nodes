<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use filsh\yii2\gearman\JobWorkload;
use app\modules\user\models\User;
class SiteController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
            'auth' => [
                'class'             => 'yii\authclient\AuthAction',
                'successCallback'   => [$this, 'successCallback'],
            ],            
        ];
    }
    
//    private function getValidResource


    private function uploadImage($userId,$url,$type=null)
    {
        $stream     =   imagecreatefromstring(file_get_contents($url));
        $fileName   =   base_convert($userId, 10, 32);
        imagejpeg($stream,Yii::getAlias("@pictures/{$fileName}.jpg"),100);
        imagedestroy($stream);
        $content = file_get_contents(Yii::getAlias("@pictures/{$fileName}.jpg"));
        unlink(Yii::getAlias("@pictures/{$fileName}.jpg"));        
        Yii::$app->ftpFs->put(Yii::getAlias("@ftp/pics/{$fileName}.jpg"), $content);
    }


    public function actionIndex()
    {

        return $this->render('index');
    }
    
    public function successCallback($client)
    {
        $attributes = $client->getUserAttributes();
        if ($client === 'google'){
            
        }
        var_dump($attributes);die;
        // user login or signup comes here
    }

    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        } else {
            return $this->render('contact', [
                'model' => $model,
            ]);
        }
    }

    public function actionAbout()
    {
        return $this->render('about');
    }
    
}
