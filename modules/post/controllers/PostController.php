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
    
    public function actionEdit($id)
    {
        $id     = base_convert($id, 36, 10);
        $model  = $this->findModel($id);
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format =   Response::FORMAT_JSON;
            $model->load(Yii::$app->request->post());
            return ActiveForm::validate($model);
        }
        return $this->render('write', [
            'model' => $model,
        ]);        
    }
    
    public function actionAutosave($id)
    {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format =   Response::FORMAT_JSON;
            $id                         =   base_convert($id, 36, 10);
            $model                      =   $this->findModel($id);
            $model->autosave_content    =   \Yii::$app->request->getRawBody();

            $model->save();
            return ActiveForm::validate($model);
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionSuggesturl($id)
    {
        if (Yii::$app->request->isAjax){
            Yii::$app->response->format =   Response::FORMAT_JSON;
            $title =    Yii::$app->request->post('title');
            $url   =    Post::suggestUniqueUrl($title, $id);
            return $url;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionWrite($type = 'writting')
    {
        if ($type === 'new'){
            Post::draftAllWrttingPost();
        } else {
            $model = Post::getLastUserWrittingPost();
            if (!$model){
                $model = new Post;
                $model->save();
            }
        }
        
        if ($type === 'writting'){
            
        }
        
        return $this->redirect(['edit', 'id' => base_convert($model->id, 10, 36)]);
        $model = new Post();
        
        $model->save();   
        return $this->redirect(['edit', 'id' => base_convert($model->id, 10, 36)]);
//        if ($type === 'new'){
//            $model = new Post();
//            $model->save();
//        } else {
//            $model = Post::getLastUserWrittingPost();
//            
//        }
////        var_dump(Post::getLastUserWrittingPost());die;
////        
//        if ($model = Post::getLastUserWrittingPost()){
//            
//        }
//        Post::findOne([''])
//        Post;
//        $model = new Post();
//        if ($model->load(Yii::$app->request->post()) && $model->save()) {
//            return $this->redirect(['view', 'id' => $model->id]);
//        } else {
//            return $this->render('write', [
//                'model' => $model,
//            ]);
//        }
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
        if ($model !== null && $model->user_id === Yii::$app->user->getId()) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    protected function findUser($username)
    {
        if (($user = User::findOne(['username'=>$username])) != NULL){
            return $user;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
