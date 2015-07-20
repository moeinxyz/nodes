<?php

namespace app\modules\post\controllers;

use Yii;
use app\components\Broker;
use yii\helpers\Json;
use yii\db\Query;
use app\modules\post\models\Post;
use app\modules\post\models\Userread;
use app\modules\post\models\Guestread;
use app\modules\post\models\Userrecommend;
use app\modules\post\models\Comment;
use app\modules\user\models\User;


class PostSuggestionForGuestWorkerController extends \yii\console\Controller{
    public function init() {
        parent::init();
        set_time_limit(0);
        register_shutdown_function(function (){
            Broker::close();
        });
    }
    
    public function actionIndex(){
        Broker::consumeMessage('PostSuggestionForGuest', function($message){
            $params     =   Json::decode($message->body);
            $post       =   Post::findOne($params['postId']);

            if ($post instanceof Post){
                $post->score            =   $this->postScore($post->id);
                $post->score_updated_at =   new \yii\db\Expression('NOW()');
                $post->save();
            }            
            Broker::sendAck($message);
        });
    }    
    
    private function postScore($postId)
    {
        $score      =   0;
        $post       =   Post::findOne($postId);
        $authorId   =   $post->user_id;
        $timestamp  =   $this->getLastUpdateTimestamp($post);
        
        $score      +=  $this->commentsScore($postId, $authorId, $timestamp);
        $score      +=  $this->getCoverScore($post);
        $score      +=  $this->guestVisitorScore($postId,$timestamp);
        $score      +=  $this->userVisitorScore($postId, $timestamp);
        $score      +=  $this->wordsCountScore($post->pure_text);
        $score      +=  $this->timeBasedScores($post);
        return round($score);
    }
    
    private function getLastUpdateTimestamp(Post $post)
    {
        $timestamp  =   strtotime($post->score_updated_at);
        if ($timestamp <= 0){
            $timestamp  = strtotime($post->published_at);
        }
        
        return $timestamp;
    }
    
    private function getCoverScore(Post $post)
    {
        if ($post->cover === Post::COVER_BYCOVER){
            return 10;
        }
        return 0;
    }

    private function commentsScore($postId,$authorId,$timestamp)
    {
        $query      =   new Query;
        $count      =   $query->from(Comment::tableName())
                            ->select('user_id')
                            ->where('post_id=:post_id',[':post_id'=>$postId])
                            ->andWhere('created_at > :timestamp',[':timestamp'=>$timestamp])
                            ->andWhere('user_id!=:author_id',[':author_id'=>$authorId])
                            ->distinct()
                            ->count();
        return $count * 10;
    }
    
    private function guestVisitorScore($postId,$timestamp)
    {
        $score      =   0;
        $visitors   =   [];
        $query      =   new Query;
        $rows       =   $query->from(Guestread::tableName())
                            ->select('uuid, ip')
                            ->where('post_id=:post_id',[':post_id'=>$postId])
                            ->andWhere('created_at > :timestamp',[':timestamp'=>$timestamp])
                            ->distinct()
                            ->all();
        foreach ($rows as $row)
        {
            if (isset($visitors[$row['ip']]) && $visitors[$row['ip']] < 3){
                $visitors[$row['ip']]++;
                $score++;
            } else if (!isset($visitors[$row['ip']])) {
                $visitors[$row['ip']] = 1;
                $score++;
            }
        }
        return $score;
    }
    
    private function userVisitorScore($postId,$timestamp)
    {
        $query      =   new Query;
        $count      =   $query->from(Userread::tableName())
                            ->select('user_id')
                            ->where('post_id=:post_id',[':post_id'=>$postId])
                            ->andWhere('created_at > :timestamp',[':timestamp'=>$timestamp])
                            ->distinct()
                            ->count();
        return $count * 2;
    }
    
    private function wordsCountScore($pureText)
    {
        /**
         * Function : [0,3200]  => -(x^2 - 3200x) / 51200
         * http://www.wolframalpha.com/input/?i=-%28x%5E2+-+3200x%29+%2F+51200
         * Function : [0,+INF]  =>  0
         */
        $count  =   count(preg_split('~[^\p{L}\p{N}\']+~u',$pureText));
        if ($count < 3200){
            return ((-1) * ($count * $count - 3200 * $count)) / 51200;
        }
        return 0;
    }
    
    private function timeBasedScores(Post $post)
    {
        $score  =   ($this->authorFollowersCount($post->user_id) * 2);
        $score  +=  ($this->postRecommendationsCount($post->id) * 7);
        $time   =   time()  - strtotime($post->published_at);
        return $this->leanerScoreMapper($score,$time);
    }
    
    private function leanerScoreMapper($score,$time)
    {
        // 604800 means one week 7 * 24 * 60 * 60
        if ($time > 604800)
        {
            return intval(0.001 * $score);
        }
        
        return (-1 / 604800) * $time + 1;
    }


    private function postRecommendationsCount($postId)
    {
        $query  =   new Query;
        $count  =   $query->from(Userrecommend::tableName())
                            ->select('user_id,post_id')
                            ->where('post_id=:post_id',[':post_id'=>$postId])
                            ->distinct()
                            ->count();
        return $count;
    }
    
    private function authorFollowersCount($userId)
    {
        $user   =   User::findOne($userId);
        return $user->followers_count;
    }    
    
}