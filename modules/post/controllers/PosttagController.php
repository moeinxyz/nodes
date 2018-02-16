<?php

namespace app\modules\post\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\web\Response;
use app\modules\post\models\Post;
use app\modules\post\models\Tag;
use app\modules\post\models\Posttag;

/**
 * PosttagController implements the CRUD actions for Posttag model.
 */
class PosttagController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access'    =>  [
                'class' =>  AccessControl::className(),
                'only'  =>  ['tags'],
                'rules' =>  [
                    [
                        'actions'   =>  ['tags'],
                        'roles'     =>  ['@'],
                        'verbs'     =>  ['POST'],
                        'allow'     =>  true
                    ]
                ],

            ]
        ];
    }

    public function actionTags($id)
    {
        Yii::$app->response->format =   Response::FORMAT_JSON;

        $id             =   base_convert($id, 36, 10);
        $post           =   $this->findPost($id);
        $tags           =   Yii::$app->request->post('tags') === NULL? [] : Yii::$app->request->post('tags');
        $tags           =   array_unique($tags);
        $new_tags_ids   =   [];
        $old_tags_ids   =   [];

        foreach ($tags as $tag) {
            $model              =   $this->findOrInsertTag($tag);
            $new_tags_ids[]     =   $model->id;
        }

        foreach ($post->posttags as $post_tag) {
            if (!in_array($post_tag->tag_id, $new_tags_ids)) {
                $post_tag->delete();
            }
        }

        $post->refresh();

        foreach ($post->posttags as $post_tag) {
            $old_tags_ids[]     =   $post_tag->tag_id;
        }

        foreach ($new_tags_ids as $tag_id) {
            if (!in_array($tag_id, $old_tags_ids)) {
                $model  =   new Posttag([
                    'tag_id'    =>  $tag_id,
                    'post_id'   =>  $post->id
                ]);
                $model->save();
            }
        }

        return true;
    }

    /**
     * Finds the Post model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Post the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findPost($id)
    {
        $model = Post::findOne($id);
        if ($model !== null && $model->status != Post::STATUS_DELETE && $model->user_id === Yii::$app->user->getId()) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('yii','Page not found.'));
        }
    }

    protected function findOrInsertTag($tag)
    {
        if (($model = Tag::findOne(['tag'=>$tag])) == NULL){
            $model      =   new Tag();
            $model->tag =   $tag;
            $model->save();
        }
        return $model;
    }
}
