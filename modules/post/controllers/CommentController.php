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
                'only'  =>  ['write'],
                'rules' =>  [
                    [
                        'actions'   =>  ['write'],
                        'roles'     =>  ['@'],
                        'verbs'     =>  ['POST'],       
                        'allow'     =>  true
                    ],
                ],
                'denyCallback'  =>  function($rule, $action){
                    return $this->redirect(Yii::$app->request->getReferrer());
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
            $newComment =   new Comment;
            $comments   =   Comment::getCommentsOfPost($post->id,$timestamp);
            return $this->renderPartial('_comments',[
                'newComment'    =>  $newComment,
                'comments'      =>  $comments,
                'username'      =>  $username,
                'url'           =>  $url,
                'timestamp'     =>  $timestamp
            ]);            
        } else {
            throw new \yii\web\HttpException;
        }
    }

        /**
     * Lists all Comment models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Comment::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Comment model.
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
     * Creates a new Comment model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Comment();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Comment model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Comment model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Comment model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Comment the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Comment::findOne($id)) !== null) {
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
}
