<?php

namespace app\modules\post\controllers;

use Yii;
use app\modules\post\models\Post;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\user\models\User;
use yii\web\Response;
use yii\bootstrap\ActiveForm;
use yii\helpers\StringHelper;
use app\components\Helper\Extract;
/**
 * PostController implements the CRUD actions for Post model.
 */
class PostController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }
    
    
    public function actionUser($username)
    {
        $user   = $this->findUser($username);
        
        if (Yii::$app->request->isAjax){
//            return $this->render
        }
        
        return $this->render('user',['user'=>$user]);
    }

    public function actionPost($username,$post = NULL)
    {
        var_dump($username."SALAM");
    }

        /**
     * Lists all Post models.
     * @return mixed
     */
    public function actionIndex()
    {
        var_dump("SALAM");die;
        $dataProvider = new ActiveDataProvider([
            'query' => Post::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Post model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Post model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    
    public function actionEdit($id,$type='content')
    {
        $id     = base_convert($id, 36, 10);
        $model  = $this->findModel($id);
        if (Yii::$app->request->isPost){
            $content = Yii::$app->request->post('content');
            $model->title               =   Extract::extractTitle($content);
            $model->content             =   Extract::extractContent($content);
            $model->last_update_type    =   Post::LAST_UPDATE_TYPE_MANUAL;
            $model->save();
        }
        return $this->render('write', [
            'model' => $model,
            'type'  =>  $type
        ]);        
    }
    
    public function actionAutosave($id)
    {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format =   Response::FORMAT_JSON;
            $id                         =   base_convert($id, 36, 10);
            $model                      =   $this->findModel($id);
            $model->autosave_content    =   \Yii::$app->request->getRawBody();
            $model->last_update_type    =   Post::LAST_UPDATE_TYPE_AUTOSAVE;
            $model->save();
            return ActiveForm::validate($model);
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }   

//    public function actionSuggesturl($id)
//    {
//        if (Yii::$app->request->isAjax){
//            Yii::$app->response->format =   Response::FORMAT_JSON;
//            $title =    Yii::$app->request->post('title');
//            $url   =    Post::suggestUniqueUrl($title, $id);
//            return $url;
//        } else {
//            throw new NotFoundHttpException('The requested page does not exist.');
//        }
//    }

    public function actionWrite($type = 'writting')
    {
        $model = NULL;
        if ($type == 'writting'){
            $model = Post::getLastUserWrittingPost();
        } else {
            Post::draftAllWrittingPost();
        }
        
        if (!$model){
            $model = new Post;
            $model->save();
        }
        return $this->redirect(['edit', 'id' => base_convert($model->id, 10, 36)]);
    }
    
    public function actionPreview($id){
        $id     =   base_convert($id, 36, 10);
        $model  =   $this->findModel($id);
        return $this->render('preview',['model'=>$model]);
    }

    public function actionRss($username)
    {
        $user           = $this->findUser($username);
        if (isset($user->tagline)){
            $description = $user->tagline;
        } else if (isset ($user->name)) {
            $description = $user->name . ' ('.$user->username.')';
        } else {
            $description = $user->username;
        }
        $dataProvider = new ActiveDataProvider([
            'query'         => Post::find(),//->where('user_id=:user_id AND status=:status', [':user_id'=>$user->id,':status'=>  Post::STATUS_PUBLISH]),//->orderBy(['id']),
            'pagination'    => [
                'pageSize' => 15
            ],
        ]);

        $response   = Yii::$app->getResponse();
        $headers    = $response->getHeaders();

        $headers->set('Content-Type', 'application/rss+xml; charset=utf-8');

        $response->content = \Zelenin\yii\extensions\Rss\RssView::widget([
            'dataProvider' => $dataProvider,
            'channel' => [
                'title'         =>  ($user->name==NULL)?$user->username:$user->name,
                'link'          =>  Yii::$app->urlManager->createAbsoluteUrl(["@{$user->username}"]),
                'description'   =>  $description,
                'language'      =>  Yii::$app->language
            ],
            'items' => [
                'title' => function ($model, $widget) {
                        return $model->title;
                    },
                'description' => function ($model, $widget) {
                        return StringHelper::truncateWords(strip_tags($model->content), 50);
                    },
                'link' => function ($model, $widget) use ($user) {
                        return Yii::$app->urlManager->createAbsoluteUrl(["@{$user->username}/{$model->url}"]);
                    },
                'author' => function ($model, $widget) use ($user) {
                        if (isset($user->name)){
                            return $user->name . ' ('.$user->username.')';
                        } else {
                            return $user->username;
                        }
                    },
                'pubDate' => function ($model, $widget) {
                        $date = \DateTime::createFromFormat('Y-m-d H:i:s', $model->updated_at);
                        return $date->format(DATE_RSS);
                    }
            ]
        ]);
    }    

    /**
     * Finds the Post model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Post the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        $model = Post::findOne($id);
        if ($model !== null && $model->status != Post::STATUS_DELETE && $model->user_id === Yii::$app->user->getId()) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
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
        if (($user = User::findOne(['username'=>$username])) != NULL && $user->status != User::STATUS_BLOCK){
            return $user;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
