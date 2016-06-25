<?php
namespace app\modules\post\controllers;

use Yii;
use app\modules\post\models\Post;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

use yii\filters\AccessControl;
use app\modules\user\models\User;
use yii\web\Response;
use yii\bootstrap\ActiveForm;
use app\components\Helper\Extract;
use app\modules\post\models\Comment;
use app\modules\post\models\Userrecommend;
use app\modules\post\models\Guestread;
use app\modules\post\models\Userread;
use app\modules\post\models\UserToRead;
use app\modules\post\models\CoverPhotoForm;
use yii\data\Pagination;
use yii\web\UploadedFile;
use app\modules\post\Module;
use yii\helpers\HtmlPurifier;

/**
 * PostController implements the CRUD actions for Post model.
 */
class PostController extends Controller
{
    public function behaviors()
    {
        return [
            'access'    =>  [
                'class' =>  AccessControl::className(),
                'only'  =>  ['admin','autosave','delete','edit','pin','preview','publish','recommend','trash','write'],
                'rules' =>  [
                    [
                        'actions'   =>  ['admin'],
                        'roles'     =>  ['@'],
                        'allow'     =>  true
                    ],
                    [
                        'actions'   =>  ['preview','write'],
                        'roles'     =>  ['@'],
                        'verbs'     =>  ['GET'],
                        'allow'     =>  true
                    ],
                    [
                        'actions'   =>  ['autosave','delete','publish','cover','pin','trash'],
                        'roles'     =>  ['@'],
                        'verbs'     =>  ['POST'],
                        'allow'     =>  TRUE
                    ],
                    [
                        'actions'   =>  ['edit'],
                        'roles'     =>  ['@'],
                        'allow'     =>  TRUE
                    ],
                    [
                        'actions'   =>  ['recommend'],
                        'verbs'     =>  ['POST'],
                        'allow'     =>  TRUE
                        
                    ]
                ]
            ]            
        ];
    }
    
    public function actionUser($username)
    {
        $user   =   $this->findUser($username);
        $query  =   Post::find()->leftJoin(Userrecommend::tableName(),Post::tableName().'.id='.Userrecommend::tableName().'.post_id')
                    ->where(Post::tableName().'.status=:status AND ('.Userrecommend::tableName().'.user_id=:user_id OR '.Post::tableName().'.user_id=:user_id)',['user_id'=>$user->id,'status'=>Post::STATUS_PUBLISH]);
        
        $countQuery =   clone $query;
        $count      =   $countQuery->count();
        $pages = new Pagination(['totalCount' => $count,'defaultPageSize'=>7,'params'=>array_merge($_GET, ['#' => 'details'])]);
        $posts = $query->offset($pages->offset)
            ->limit(7)
            ->orderBy('published_at desc')
            ->all();
        
        return $this->render('user',[
            'user'  =>  $user,
            'posts' =>  $posts,
            'pages' =>  $pages
        ]);
    }
    
    public function actionPosts($username)
    {
        $user       =   $this->findUser($username);
        $query      =   Post::find()->where(['user_id'=>$user->id,'status'=>Post::STATUS_PUBLISH]);
        $countQuery =   clone $query;
        $count      =   $countQuery->count();
        if ($count == 0){
            return $this->redirect(Yii::$app->urlManager->createUrl("@{$username}"));
        }
        $pages = new Pagination(['totalCount' => $count,'defaultPageSize'=>7,'params'=>array_merge($_GET, ['#' => 'details'])]);
        $posts = $query->offset($pages->offset)
            ->limit(7)
            ->orderBy('published_at desc')
            ->all();
        
        return $this->render('user',[
            'user'  =>  $user,
            'posts' =>  $posts,
            'pages' =>  $pages
        ]);
    }

    public function actionRecommends($username)
    {
        $user       =   $this->findUser($username);
        $query      =   Post::find()->leftJoin(Userrecommend::tableName(),Post::tableName().'.id='.Userrecommend::tableName().'.post_id')
                            ->where(Post::tableName().'.status=:status AND '.Userrecommend::tableName().'.user_id=:user_id',['user_id'=>$user->id,'status'=>Post::STATUS_PUBLISH]);
        $countQuery =   clone $query;
        $count      =   $countQuery->count();
        if ($count == 0){
            return $this->redirect(Yii::$app->urlManager->createUrl("@{$username}"));
        }
        $pages = new Pagination(['totalCount' => $count,'defaultPageSize'=>7,'params'=>array_merge($_GET, ['#' => 'details'])]);
        $posts = $query->offset($pages->offset)
            ->limit(7)
            ->orderBy('published_at desc')
            ->all();
        
        return $this->render('user',[
            'user'  =>  $user,
            'posts' =>  $posts,
            'pages' =>  $pages
        ]);        
    }

    public function actionView($username,$url)
    {
        $user               =   $this->findUser($username);
        $post               =   $this->findPost($user->id, $url);
        $this->addPostRead($post);
        $this->setCommentsAsSeen($post);
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
        $status         = $this->normilizeStatus($status);
        $dataProvider   = $this->getDataProvider($status);
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
        $cover  = new CoverPhotoForm([
            'coverStatus'   =>  $model->cover,
            'postId'        =>  $model->id
        ]);
        $type   = strtolower($type);
        if ($type != 'content'){
            $type = 'autosave';
        }
        if (Yii::$app->request->isAjax){
            //@todo HtmlPurifier should use in model
            Yii::$app->response->format =   Response::FORMAT_JSON;
            $model->title               =   HtmlPurifier::process(Extract::extractTitle($model->autosave_content));
            $model->content             =   HtmlPurifier::process(Extract::extractContent($model->autosave_content),\app\components\Helper\Purifier::getConfig());
            $model->pure_text           =   HtmlPurifier::process(Extract::extractPureText($model->content));
            $model->last_update_type    =   Post::LAST_UPDATE_TYPE_MANUAL;
            return $model->save();
        }
        return $this->render('write', [
            'model' => $model,
            'cover' => $cover,
            'type'  =>  $type
        ]);        
    }
    
    public function actionAutosave($id)
    {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format =   Response::FORMAT_JSON;
            $id                         =   base_convert($id, 36, 10);
            $model                      =   $this->findModel($id);
            $model->autosave_content    =   trim(\Yii::$app->request->getBodyParam('body'));
            $model->last_update_type    =   Post::LAST_UPDATE_TYPE_AUTOSAVE;
            $model->save();
            return ActiveForm::validate($model);
        } else {
            throw new NotFoundHttpException(Yii::t('yii','Page not found.'));
        }
    }   
    
    public function actionPublish($id)
    {
        if (Yii::$app->request->isAjax) {
            $id                         =   base_convert($id, 36, 10);
            $model                      =   $this->findModel($id);
            $model->setScenario('publish');
            $model->status              =   Post::STATUS_PUBLISH;
            if ($model->url === NULL){
                $model->url = Post::suggestUniqueUrl($model->title, $model->id);
            }
            if ($model->published_at === '0000-00-00 00:00:00'){
                $model->published_at    =   new \yii\db\Expression('NOW()');
            }
            if ($model->save()){
                Yii::$app->response->format =   Response::FORMAT_JSON;
                return ['url'   =>  Yii::$app->urlManager->createAbsoluteUrl(["@{$model->getUser()->one()->username}/{$model->url}"])];
            } else {
                throw new \yii\web\HttpException(406);
            }
        } else {
            throw new NotFoundHttpException(Yii::t('yii','Page not found.'));
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
        if ($model === NULL){
            $model = new Post;
            $model->save();
        }
        return $this->redirect(['edit', 'id' => base_convert($model->id, 10, 36),'type'=>'autosave']);
    }

    public function actionCover($id) {
        if (Yii::$app->request->isAjax){
            $model          =   $this->findModel(base_convert($id, 36, 10));
            $cover          =   new CoverPhotoForm([
                'coverStatus'   =>  $model->cover,
                'postId'        =>  $model->id
            ]);
            $cover->coverImage  =   UploadedFile::getInstance($cover, 'coverImage');
            if ((Yii::$app->request->post('submit-type')  == 'add-cover') && $cover->validate() && ($cover->coverImage instanceof UploadedFile)){
                $name   =   Post::getCoverFileName(base_convert($id, 36, 10));
                $file   =   Yii::getAlias("@webTempPostCover/{$name}.{$cover->coverImage->getExtension()}");
                $cover->coverImage->saveAs($file);

                if ($cover->update()){
                    Yii::$app->session->setFlash('post.cover.add_or_change.successful');    
                } else if ($cover->getFirstError('coverImage') == null) {
                    $cover->addError('coverImage',  Module::t('post','coverPhotoForm.err.try_later'));
                }
            } else if (Yii::$app->request->post('submit-type') == 'remove-cover') {
                $model->cover       = Post::COVER_NOCOVER;
                if ($model->save()){
                    $cover->coverStatus =   Post::COVER_NOCOVER;
                    Yii::$app->session->setFlash('post.cover.remove.successful');
                } else if ($cover->getFirstError('coverImage') == null) {
                    $cover->addError('coverImage',  Module::t('post','coverPhotoForm.err.try_later'));
                    $cover->coverStatus =   $model->cover;
                }
            }
            return $this->renderAjax('_post_cover',[
                'cover' =>  $cover
            ]);
        }
    }

    public function actionPin($status,$id)
    {
        if (Yii::$app->request->isAjax){
            $id     =   base_convert($id, 36, 10);
            $post   =   $this->findModel($id);   
            $post->togglePin();
            $post->save();
            $status         = $this->normilizeStatus($status);
            $dataProvider   = $this->getDataProvider($status);
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
            $status         = $this->normilizeStatus($status);
            $dataProvider   = $this->getDataProvider($status);
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
        Yii::$app->response->format =   Response::FORMAT_JSON;
        if (Yii::$app->user->isGuest){
            return ['status'    =>  'LOGIN'];
        } else if (Yii::$app->request->isAjax && $post->user_id != Yii::$app->user->getId()){
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
            throw new NotFoundHttpException(Yii::t('yii','Page not found.'));
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
            'query'         => Post::find()
                                ->where('user_id=:user_id AND status=:status', [':user_id'=>$user->id,':status'=>  Post::STATUS_PUBLISH])
                                ->orderBy('published_at desc'),
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
                        return $model->content;
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

    protected function normilizeStatus($status)
    {
        $status = strtoupper($status);
        if ($status != Post::STATUS_TRASH && $status != Post::STATUS_PUBLISH){
            $status = Post::STATUS_DRAFT;
        }
        return $status;
    }
    
    /**
     * 
     * @param string $status
     * @return ActiveDataProvider
     */
    protected function getDataProvider($status)
    {
        $query = Post::find();
        if ($status === Post::STATUS_DRAFT){
            $query = $query->where('(status=:draft OR status=:writting) AND user_id=:user_id',[':draft'=>Post::STATUS_DRAFT,':writting'=>Post::STATUS_WRITTING,'user_id'=>Yii::$app->user->getId()]);
        } else {
            $query = $query->where('status=:status AND user_id=:user_id',[':status'=>$status,'user_id'=>Yii::$app->user->getId()]);
        }
        $dataProvider =  new ActiveDataProvider([
            'query'     =>  $query,
            'sort'      =>  ['defaultOrder' => ['created_at'=>SORT_DESC]]
        ]);
        $dataProvider->sort->route = 'post/admin';
        return $dataProvider;
    }

    private function addPostRead(Post $post)
    {
        if (\Yii::$app->user->isGuest){
            $this->addGuestReadPost($post);
        } else {
            $cookiesRequest =   \Yii::$app->getRequest()->getCookies();
            $cookiesResponse=   \Yii::$app->getResponse()->getCookies();
            if ($cookiesRequest->has('_r_hash')){    
                $readHash   =   $cookiesRequest->getValue('_r_hash');
                $this->moveLastDaysReadToUser($readHash);
                $cookiesResponse->remove('_r_hash');
                unset($_COOKIE['_r_hash']);
            }
            $this->addUserReadPost($post);
        }
    }
    
    
    private function addUserReadPost(Post $post)
    {
        $model              =   new Userread();
        $model->post_id     =   $post->id;
        $model->user_id     =   \Yii::$app->user->getId();
        $model->ip          =   \Yii::$app->request->getUserIp();
        $model->save();
    }

    private function addGuestReadPost(Post $post)
    {
        $cookiesRequest =   \Yii::$app->getRequest()->getCookies();
        $cookiesResponse=   \Yii::$app->getResponse()->getCookies();
        if ($cookiesRequest->has('_r_hash')){
            $readHash   =   $cookiesRequest->getValue('_r_hash');
        } else {
            $readHash   = \Yii::$app->security->generateRandomString(32);
            $cookiesResponse->add(new \yii\web\Cookie([
                                'name'  => '_r_hash',
                                'value' => $readHash,
                                'expire'=> time() + (20 * 365 * 24 * 60 * 60)
                            ]));
        }   
        $model              =   new Guestread();
        $model->uuid        =   $readHash;
        $model->post_id     =   $post->id;
        $model->ip          =   \Yii::$app->request->getUserIp();
        $model->useragent   =   \Yii::$app->request->getUserAgent();
        $model->save();
    }

    //@todo this is so slow
    private function moveLastDaysReadToUser($hash)
    {
        $models = Guestread::find()->where('uuid=:hash AND created_at>=:timestamp',[
            'hash'      =>  $hash,
            'timestamp' =>  date('Y-m-d H:i:s',time() - (3600 * 24 * 3))
        ])->all();
        $userRead   =   new Userread;
        $rows       =   [];
        foreach ($models as $model){
            $userRead->user_id      =   \Yii::$app->user->getId();
            $userRead->post_id      =   $model->post_id;
            $userRead->ip           =   $model->ip;
            $userRead->created_at   =   $model->created_at;
            $rows[]                 =   $userRead->attributes;
        }
        if (count($rows) >= 1)
        {
            Yii::$app->db->createCommand()->batchInsert(Userread::tableName(), $userRead->attributes(), $rows)->execute();    
        }
    }

    private function setCommentsAsSeen(Post $post)
    {
        if (!\Yii::$app->user->isGuest && $post->user_id === Yii::$app->user->getId()){
            Comment::setCommentsAsSeen($post->id);
        }
    }




    /*
     * Home Page
     */
    public function actionHome()
    {
        if (\Yii::$app->user->isGuest){
            return $this->guestHome();
        } else {
            return $this->userHome(Yii::$app->user->getId());
        }
    }
    
    private function guestHome()
    {
        $query  =   Post::find()->where('status=:status',[':status'=>  Post::STATUS_PUBLISH]);
        $count  =   $query->count();
        $pages  =   new Pagination(['totalCount' => $count,'defaultPageSize'=>7,'params'=>array_merge($_GET, ['#' => 'details'])]);
        $posts  =   $query->offset($pages->offset)
                    ->limit(7)
                    ->orderBy('score desc, published_at desc')
                    ->all();
        
        if (strtolower(Yii::$app->request->get('login')) == 'required'){
            $login  =   true;
        } else {
            $login  =   false;    
        }
        
        return $this->render('home',[
            'posts' =>  $posts,
            'pages' =>  $pages,
            'login' =>  $login
        ]);       
    }
    
    private function userHome($userId)
    {    
        $query     =   Post::find()
                            ->leftJoin(UserToRead::tableName(),Post::tableName().'.id = '.UserToRead::tableName().'.post_id')
                            ->where(UserToRead::tableName().'.user_id = :user_id AND '.Post::tableName().'.status = :status',[':user_id' =>  $userId,':status'   =>  Post::STATUS_PUBLISH]);
        $count      =   $query->count();

        if ($count == 0){ // for example new registered user
            return $this->guestHome();
        }

        $pages = new Pagination(['totalCount' => $count,'defaultPageSize'=>7,'params'=>array_merge($_GET, ['#' => 'details'])]);
        $posts = $query->offset($pages->offset)
            ->limit(7)
            ->orderBy(UserToRead::tableName().'.priority ASC,'.UserToRead::tableName().'.created_at desc,'.UserToRead::tableName().'.score desc,'.Post::tableName().'.score desc,'.Post::tableName().'.published_at desc')
            ->all();
        
        return $this->render('home',[
            'posts' =>  $posts,
            'pages' =>  $pages
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
            throw new NotFoundHttpException(Yii::t('yii','Page not found.'));
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
            throw new NotFoundHttpException(Yii::t('yii','Page not found.'));
        }
    }
    
    protected function findPost($userId,$url)
    {
        if (($post = Post::findOne(['user_id'=>$userId,'url'=> urlencode($url)])) != NULL && $post->status === Post::STATUS_PUBLISH){
            return $post;
        } else {
            throw new NotFoundHttpException(Yii::t('yii','Page not found.'));
        }        
    }        
}
