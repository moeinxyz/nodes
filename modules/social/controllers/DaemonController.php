<?php
namespace app\modules\social\controllers;

use Yii;
use app\modules\social\models\Social;
use app\modules\social\models\Socialcontent;
use app\modules\social\Module;
use app\modules\post\models\Post;
use app\modules\post\models\Userrecommend;
use Abraham\TwitterOAuth\TwitterOAuth;
/**
 * This Way Is So Dummy,Need Queue Arch
 */
class DaemonController extends \yii\console\Controller{
    
    public function init() {
        parent::init();
        set_time_limit(0);
    }

    public function actionIndex()
    {
        do
        {
            $timestamp      =   Social::find()->where('status=:status',[':status'=>  Social::STATUS_ACTIVE])->min('last_used');

            if ($timestamp === NULL){
                sleep(Module::CHECK_INTERVAL);
                continue;
            }

            $diff   =   time() - strtotime($timestamp);
            
            if ($diff < Module::CHECK_INTERVAL){
                sleep(Module::CHECK_INTERVAL + Module::ADDITIONAL_SLEEP_SECS - $diff);
                continue;;
            }   
            
            $socials = Social::find()->where('status=:status AND last_used >= :timestamp',[
                ':status'    =>  Social::STATUS_ACTIVE,
                ':timestamp' =>  $timestamp
            ])->limit(10)->all();
            foreach ($socials as $social){
                $this->dispatch($social);
            }
        } while(true);
    }
    
    private function dispatch(Social $social)
    {
        $posts = $this->getUnsharedPosts($social);
        if ($social->type === Social::TYPE_FACEBOOK){
            
        } else if ($social->type === Social::TYPE_TWITTER){
            $this->twitter($social, $posts);
        } else if ($social->type === Social::TYPE_LINKEDIN){
            $this->linkedin($social,$posts);
        }
    }
    
    
    private function getUnsharedPosts(Social $social){
        $recommends =   [];
        $posts      = Post::find()->where('user_id=:user_id AND status=:status AND published_at>=:published_at',[
            ':user_id'       =>  $social->user_id,
            ':published_at'  =>  $social->last_used,
            ':status'        =>  Post::STATUS_PUBLISH,
        ])->all();        
        if ($social->share === Social::SHARE_ALL){
            $recommends = Post::find()->join('LEFT JOIN', Userrecommend::tableName(),  Post::tableName().'.id = '.Userrecommend::tableName().'.post_id')
                ->where(Userrecommend::tableName().'.user_id=:user_id AND '.Userrecommend::tableName().'.created_at>=:created_at AND '.Post::tableName().'.status=:status',[
                    ':user_id'       =>  $social->user_id,
                    ':created_at'    =>  $social->last_used,
                    ':status'        =>  Post::STATUS_PUBLISH
                ])->all();
        }
        return array_merge($recommends,$posts);
    }

    private function linkedin(Social $linkedin,$posts)
    {
        $li         =   new \LinkedIn\LinkedIn([
            'api_key'       =>  Yii::$app->socialClientCollection->getClient('linkedin')->clientId,
            'api_secret'    =>  Yii::$app->socialClientCollection->getClient('linkedin')->clientSecret,
            'callback_url'  =>  Yii::$app->params['linkedinCallbackUrl']
        ]);
        $token  =   unserialize($linkedin->token);
        $li->setAccessToken($token->getToken());
        
        foreach ($posts as $post){
            $content    =    [
                'title'         =>  $post->title,
                'submitted-url' =>  Yii::$app->urlManager->createAbsoluteUrl([
                            "{$post->user->getUsername()}/{$post->url}",
                            'UTM_SOURCE'    =>  'linkedin','UTM_CAMPAIGN'  =>  'auto-'.md5($linkedin->user_id)]),
            ];
            if ($post->cover === Post::COVER_BYCOVER){
                $content['submitted-image-url'] =   Post::getCoverUrl($post->id);
            }
            $params =   [
                'content'       =>  $content,
                'visibility'    =>  [
                    'code'  =>  'anyone'
                ]
            ];
            
            $response = $li->post('people/~/shares', $params);
            $this->addSocialContent($linkedin, $post, $params,$response);
        }
        $linkedin->last_used    =   new \yii\db\Expression('NOW()');
        $linkedin->update();
    }
    
    private function twitter(Social $twitter,$posts)
    {
        $token  = unserialize($twitter->token);
        $connection = new TwitterOAuth( Yii::$app->socialClientCollection->getClient('twitter')->consumerKey, 
                                        Yii::$app->socialClientCollection->getClient('twitter')->consumerSecret,
                                        $token->getToken(),
                                        $token->getTokenSecret());
        foreach ($posts as $post){
            $url        =   Yii::$app->urlManager->createAbsoluteUrl(["{$post->user->getUsername()}/{$post->url}",
                            'UTM_SOURCE' =>'twitter','UTM_CAMPAIGN'  =>  'auto-'.md5($twitter->user_id)]);
            
            $params     =   ['status'=>$post->title.'  '.$url];
            
            if ($post->cover === Post::COVER_BYCOVER){
                $content = @file_get_contents(Post::getCoverUrl($post->id));
                if ($content !== FALSE){
                    $stream     =   imagecreatefromstring($content);
                    $fileName   =   Post::getCoverFileName($post->id);
                    $localFile  =   Yii::getAlias("@postcovers/{$fileName}-social.jpg");
                    imagejpeg($stream,$localFile,100);
                    imagedestroy($stream);
                    $media = $connection->upload('media/upload', array('media' => $localFile));
                    $params['media_ids']    =   implode(',',[$media->media_id_string]);
                    unlink($localFile);
                }
            }
            $response   =    $connection->post('statuses/update', $params);
            $this->addSocialContent($twitter, $post, $params,$response);
        }
        $twitter->last_used     =   new \yii\db\Expression('NOW()');
        $twitter->update();        
    }


    private function addSocialContent($social,$post,$request,$response = NULL)
    {
        $verbose            =   [
            'request'   =>  $request,
            'response'  =>  $response
        ];
        $model              =   new Socialcontent;
        $model->post_id     =   $post->id;
        $model->social_id   =   $social->id;
        $model->verbose     =   \yii\helpers\Json::encode($verbose);
        $model->save();
    }
}
