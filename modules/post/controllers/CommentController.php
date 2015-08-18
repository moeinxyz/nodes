<?php

namespace app\modules\post\controllers;

use Yii;
use app\modules\post\models\Comment;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use app\modules\user\models\User;
use app\modules\post\models\Post;
use app\modules\post\models\Abuse;
/**
 * CommentController implements the CRUD actions for Comment model.
 */
class CommentController extends Controller
{
    public function behaviors()
    {
        return [
            'access'    =>  [
                'class' =>  AccessControl::className(),
                'only'  =>  ['write','trash','abuse','comments','delte'],
                'rules' =>  [
                    [
                        'actions'   =>  ['write','trash','abuse','delete'],
                        'roles'     =>  ['@'],
                        'verbs'     =>  ['POST'],       
                        'allow'     =>  true
                    ],
                    [
                        'actions'   =>  ['comments'],
                        'roles'     =>  ['@'],
                        'allow'     =>  true
                    ]
                ],
                'denyCallback'  =>  function($rule, $action){
                    if ($action->getUniqueId() != 'post/comment/comments')
                        return $this->redirect(Yii::$app->request->getReferrer());
                    return $this->redirect(Yii::$app->urlManager->createUrl(Yii::$app->user->loginUrl[0]));
                }
            ]
        ];
    }

    
    public function actionWrite($username,$url,$timestamp)
    {
        $user               =   $this->findUser($username);
        $post               =   $this->findPost($user->id, $url);
        $comment = new Comment();
        $comment->load(Yii::$app->request->post());
        $comment->user_id   =   Yii::$app->user->getId();
        $comment->post_id   =   $post->id;
        $comment->text      =   nl2br($comment->text);
        $comment->text      =   strip_tags($comment->text, '<br><br\>');
        if ($comment->save()){
            $post->updateCounters(['comments_count'=>1]);
            $newComment =   new Comment;
            $comments   =   Comment::getCommentsOfPost($post->id,$timestamp);
            return $this->renderPartial('_comments',[
                'newComment'    =>  $newComment,
                'comments'      =>  $comments,
                'username'      =>  $username,
                'url'           =>  $url,
                'timestamp'     =>  $timestamp,
                'post'          =>  $post
            ]);            
        } else {
            throw new \yii\web\HttpException(500);
        }
    }
    
    public function actionComments($pid = NULL)
    {
        Comment::setCommentsAsSeen($pid);
        $dataProvider = $this->getDataProvider($pid);
        return $this->render('admin', [
            'dataProvider'  =>  $dataProvider,
            'postId'        =>  $pid
        ]);        
    }

    public function actionTrash($id,$pid = NULL)
    {
        if (Yii::$app->request->isAjax){
            $id     =   base_convert($id, 36, 10);
            $comment= $this->findCommentForPostAuthor($id);   
            $comment->toggleTrash();
            $comment->save();       
            $dataProvider = $this->getDataProvider($pid);
            return $this->renderAjax('_admin',[
                'dataProvider'  =>  $dataProvider,
                'postId'        =>  $pid
            ]);    
        } else {
            return $this->redirect(Yii::$app->request->getReferrer());
        }                
    }    
    

    public function actionAbuse($id,$pid = NULL)
    {
        if (Yii::$app->request->isAjax){
            $id     =   base_convert($id, 36, 10);
            $comment= $this->findCommentForPostAuthor($id);   
            if (!Abuse::isCommentAbuseExist($comment->id)){
                $abuse = new Abuse();
                $abuse->comment_id  =  $comment->id;
                $abuse->save();
            }
            $dataProvider = $this->getDataProvider($pid);
            return $this->renderAjax('_admin',[
                'dataProvider'  =>  $dataProvider,
                'postId'        =>  $pid
            ]);    
        } else {
            return $this->redirect(Yii::$app->request->getReferrer());
        }        
    }        
    
    public function actionDelete($id)
    {
        if (Yii::$app->request->isAjax){
            $id         =   base_convert($id, 36, 10);
            $comment    =   $this->findCommentForPostAuthorOrCommentAuthor($id);
            if ($comment->post->user_id === Yii::$app->user->getId()){
                $comment->status    = Comment::STATUS_TRASH;
            } else {
                $comment->status    = Comment::STATUS_USER_DELETE;
                $comment->post->updateCounters(['comments_count'=>-1]);
            }
            $comment->save();                       
            return ' ';
        } else {
            return $this->redirect(Yii::$app->request->getReferrer());
        }
    }

    /**
     * 
     * @param string $pid
     * @return ActiveDataProvider
     */
    private function getDataProvider($pid = NULL)
    {
        if ($pid != NULL){
            $PostId     =   base_convert($pid, 36, 10);
            $post   =   $this->findPostById($PostId);
            $dataProvider = new ActiveDataProvider([
                'query' => Comment::find()->where('post_id=:post_id AND status!=:status',[':post_id'=>$post->id,':status'=>  Comment::STATUS_USER_DELETE]),
                'sort'  => ['defaultOrder' => ['created_at'=>SORT_DESC]]
            ]);            
        } else {
            $dataProvider = new ActiveDataProvider([
                'query' => Comment::find()->join('JOIN',  Post::tableName(),  
                        Comment::tableName().'.post_id = '.Post::tableName().'.id')->where(Post::tableName().'.user_id=:user_id AND '.Post::tableName().'.status!=:post_status AND '.Comment::tableName().'.status!=:comment_status',
                        [':user_id'=>Yii::$app->user->getId(),':post_status'=>Post::STATUS_DELETE,':comment_status'=>Comment::STATUS_USER_DELETE]),
                'sort'  => ['defaultOrder' => ['created_at'=>SORT_DESC]]
            ]);            
        }
        $dataProvider->sort->route = 'post/comments';
        return $dataProvider;
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
        if (($post = Post::findOne(['user_id'=>$userId,'url'=>  urlencode($url)])) != NULL && $post->status === Post::STATUS_PUBLISH){
            return $post;
        } else {
            throw new NotFoundHttpException(Yii::t('yii','Page not found.'));
        }        
    }    
    
    protected function findPostById($id)
    {
        if (($post = Post::findOne($id)) && $post != NULL && $post->status != Post::STATUS_DELETE && $post->user_id === Yii::$app->user->getId()){
            return $post;
        } else {
            throw new NotFoundHttpException(Yii::t('yii','Page not found.'));
        } 
    }
    
    protected function findCommentForPostAuthor($id)
    {
        if (($comment = Comment::findOne($id)) && $comment != NULL && $comment->post->user_id === Yii::$app->user->getId()){
            return $comment;
        } else {
            throw new NotFoundHttpException(Yii::t('yii','Page not found.'));
        }
    }
    
    protected function findCommentForPostAuthorOrCommentAuthor($id)
    {
        $comment = Comment::findOne($id);
        if ($comment !== NULL && $comment->status === Comment::STATUS_PUBLISH &&($comment->user_id === Yii::$app->user->getId() || $comment->post->user_id === Yii::$app->user->getId())){
            return $comment;
        } else {
            throw new NotFoundHttpException(Yii::t('yii','Page not found.'));
        }        
    }
}
