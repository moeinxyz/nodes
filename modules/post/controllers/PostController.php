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
use app\modules\post\models\Comment;
use app\modules\post\models\Userrecommend;
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
        return $this->render('user',['user'=>$user]);
    }
 
    public function actionView($username,$url)
    {
        $user               =   $this->findUser($username);
        $post               =   $this->findPost($user->id, $url);
        $newComment         =   new Comment;
        $newComment->post_id=   $post->id;
        $comments           =   Comment::getCommentsOfPost($post->id);
        
        return $this->render('view',['post'=>$post,'comments'=>$comments,'newComment'=>$newComment]);
    }

    public function actionPreview($id){
        $id     =   base_convert($id, 36, 10);
        $post   =   $this->findModel($id);
        return $this->render('preview',['post'=>$post]);
    }    
   
    public function actionAdmin($status = 'draft')
    {
        $status = $this->normilizeStatus($status);
        $query  = $this->getDataProviderQuery($status);
        $dataProvider = new ActiveDataProvider([
            'query' => $query
        ]);
        $dataProvider->sort->route = 'post/admin';
        return $this->render('admin', [
            'dataProvider'  =>  $dataProvider,
            'status'        =>  $status
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
        if (Yii::$app->request->isAjax){
            Yii::$app->response->format =   Response::FORMAT_JSON;
            $model->title               =   Extract::extractTitle($model->autosave_content);
            $model->content             =   Extract::extractContent($model->autosave_content);
            $model->pure_text           =   strip_tags($model->autosave_content);
            $model->last_update_type    =   Post::LAST_UPDATE_TYPE_MANUAL;
            return $model->save();
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
    
    public function actionPublish($id)
    {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format =   Response::FORMAT_JSON;
            $id                         =   base_convert($id, 36, 10);
            $model                      =   $this->findModel($id);
            $model->status              =   Post::STATUS_PUBLISH;
            if ($model->url === NULL){
                $model->url = Post::suggestUniqueUrl($model->title, $model->id);
            }
            if ($model->published_at === '0000-00-00 00:00:00'){
                $model->published_at    =   new \yii\db\Expression('NOW()');
            }
            $model->save();
            return $this->redirect(Yii::$app->urlManager->createUrl(["@{$model->getUser()->one()->username}/{$model->url}"]));
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }        
    }

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

    public function actionPin($status,$id)
    {
        if (Yii::$app->request->isAjax){
            $id     =   base_convert($id, 36, 10);
            $post   =   $this->findModel($id);   
            $post->togglePin();
            $post->save();
            $status = $this->normilizeStatus($status);
            $query  = $this->getDataProviderQuery($status);
            $dataProvider = new ActiveDataProvider([
                'query' => $query
            ]);            
            $dataProvider->sort->route = 'post/admin';            
            return $this->renderAjax('_admin',[
                'dataProvider'  =>  $dataProvider,
                'status'        =>  $status                
            ]);    
        } else {
            return $this->redirect('admin');
        }
    }
    
    public function actionTrash($status,$id)
    {
        if (Yii::$app->request->isAjax){
            $id     =   base_convert($id, 36, 10);
            $post   =   $this->findModel($id);   
            $post->toggleTrash();
            $post->save();
            $status = $this->normilizeStatus($status);
            $query  = $this->getDataProviderQuery($status);
            $dataProvider = new ActiveDataProvider([
                'query' => $query
            ]);            
            $dataProvider->sort->route = 'post/admin';
            return $this->renderAjax('_admin',[
                'dataProvider'  =>  $dataProvider,
                'status'        =>  $status                
            ]);    
        } else {
            return $this->redirect('admin');
        }        
    }

    public function actionDelete($status,$id)
    {
        $id             =   base_convert($id, 36, 10);
        $post           =   $this->findModel($id);   
        $status         =   $this->normilizeStatus($status);
        if ($status === Post::STATUS_TRASH){
            $post->status   =   Post::STATUS_DELETE;
            $post->save();            
        }           
        return $this->redirect(Yii::$app->urlManager->createUrl(['post/admin','status'=>$status]));
    }
    

    public function actionRecommend($username,$url)
    {
        $user               =   $this->findUser($username);
        $post               =   $this->findPost($user->id, $url);
        if (Yii::$app->request->isAjax){// &&  $post->user_id != Yii::$app->user->getId()){
            Yii::$app->response->format =   Response::FORMAT_JSON;
            $recommend = Userrecommend::getPostRecommended($post->id);
            if ($recommend === NULL){
                $recommend              =   new Userrecommend();
                $recommend->post_id     =   $post->id;
                $recommend->save();
                $result = ['status'    =>  'RECOMMENDED'];
            } else {
                $recommend->delete();
                $result = ['status'    =>  'NOTRECOMMENDED'];
            }
            return $result;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
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
    
    
    protected function findPost($userId,$url)
    {
        if (($post = Post::findOne(['user_id'=>$userId,'url'=>  urlencode($url)])) != NULL && $post->status === Post::STATUS_PUBLISH){
            return $post;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }        
    }
    
    protected function normilizeStatus($status)
    {
        $status = strtoupper($status);
        if ($status != Post::STATUS_TRASH && $status != Post::STATUS_PUBLISH){
            $status = Post::STATUS_DRAFT;
        }
        return $status;
    }
    
    protected function getDataProviderQuery($status)
    {
        $query = Post::find();
        if ($status === Post::STATUS_DRAFT){
            $query = $query->where('(status=:draft OR status=:writting) AND user_id=:user_id',[':draft'=>Post::STATUS_DRAFT,':writting'=>Post::STATUS_WRITTING,'user_id'=>Yii::$app->user->getId()]);
        } else {
            $query = $query->where('status=:status AND user_id=:user_id',[':status'=>$status,'user_id'=>Yii::$app->user->getId()]);
        }
        return $query;
    }
}
