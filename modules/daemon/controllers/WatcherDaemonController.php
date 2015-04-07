<?php

namespace app\modules\daemon\controllers;
/**
 * This command run WatcherDaemon.This daemon check another daemons and run, if it need.
 */
class WatcherDaemonController extends \vyants\daemon\controllers\WatcherDaemonController
{
    /**
     * @return array
     */
    protected function defineJobs()
    {
        sleep($this->sleep);
        //TODO: modify list, or get it from config, it does not matter
        $daemons = [
            ['className' => 'SocialDaemonController', 'enabled' => true]
        ];
        return $daemons;        
        $x = \app\modules\social\models\Social::find()->min('created_at');
        $t1 = time();
        sleep(15);
        var_dump(time() - $t1);
//        var_dump(strtotime($x));
        
        die;
        $model      =   \app\modules\social\models\Social::findOne(6);
        $token      =   unserialize($model->token);
        var_dump(\Yii::$app->socialClientCollection->getClient('linkedin')->getReturnUrl());die;
        var_dump($token);die;
        $li         =   new \LinkedIn\LinkedIn([
            'api_key'       => '78wy3unfyzmzd3', 
            'api_secret'    => 'JUCwxoA39IygIk4L',
            'callback_url'  =>  'http://nodes.ir/medium/web/social/auth?authclient=linkedin'
        ]);
        $li->setAccessToken($token->getAccessToken()->getToken());
        $post = array(
            'comment' => 'Test social Share',
            'content' => array(
            'title' => 'Test Title',
            'description' => 'test description', //Maxlen(255)
            'submitted_url' => 'http://rankedbyreview.co.nz'
            ),
            'visibility' => array(
            'code' => 'anyone'
            ));
        
            $post = $li->post('people/~/shares', $post);
            var_dump($post);die;
//        $linkedIn   =   new \Happyr\LinkedIn\LinkedIn('78wy3unfyzmzd3', 'JUCwxoA39IygIk4L');
//        $linkedIn->setAccessToken($token->getAccessToken()->getToken());
//        $linkedIn->setRequest(new \Happyr\LinkedIn\Http\CurlRequest());
//        $result     =  $linkedIn->api('/v1/people/~/shares?format=json',[],'POST',[
//            'comment'       =>  'This is Linkedin API Testing',
//            'visibility'    =>  [
//                'code'  =>  'anyone'
//            ]            
//        ]);
//        var_dump($result);die;
//        $linkedin->setCurlOptions([
//            'CURLOPT_PROXY' =>  ''
//        ]);
        
        $linkedin->api('https://api.linkedin.com/v1/people/~/shares?format=json','POST',  [

            ]);
//        ,
//            [
//                'Content-type'  =>  'application/json',
//                'x-li-format'   =>  'json'
//            ]
//            );
        var_dump($linkedin);die;
        
        sleep($this->sleep);
        //TODO: modify list, or get it from config, it does not matter
        $daemons = [
            ['className' => 'SocialDaemonController', 'enabled' => true]
        ];
        return $daemons;
    }
}