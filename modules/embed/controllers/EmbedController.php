<?php

namespace app\modules\embed\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Response;
use Embedly\Embedly;


use app\modules\embed\models\Embed;



class EmbedController extends \yii\web\Controller
{
    
    public function behaviors()
    {
        return [
            'access'    =>  [
                'class' =>  AccessControl::className(),
                'only'  =>  ['embed'],
                'rules' =>  [
                    [
                        'actions'       =>  ['embed'],
                        'roles'         =>  ['@'],                        
                        'matchCallback' =>  function($rule,$action){
                            Yii::$app->response->format =   Response::FORMAT_JSON;
                            $referrer                   =   Yii::$app->request->getReferrer();
                            $serverName                 =   Yii::$app->request->getServerName();
                            $parse                      =   parse_url($referrer);
                            if (array_key_exists('host', $parse) && $parse['host'] == $serverName){
                                return TRUE;
                            }
                            return FALSE;
                        },
                        'allow'         =>  TRUE
                    ]
                ],                                
            ]
        ];
    }
    
    public function actionEmbed($url,$type = 'video')
    {
        $url    = strrev($url);
        if ($type === 'video'){
            $type = Embed::TYPE_OEMBED;
        } else {
            $type = Embed::TYPE_EXTRACT;
        }
        
        $model  = $this->loadModel($url, $type);
        if ($model === NULL || strtotime($model->updated_at) < (time() - Yii::$app->controller->module->timeLimit)){
            $response   = $this->getEmbedResponse($url,  $type);
            if ($model != NULL){
                $model->response    =   $response;
                $model->frequnecy++;
                $model->save();
            } else {
                $model          =   new Embed;
                $model->type    =   $type;
                $model->url     =   $url;
                $model->response=   $response;
                $model->save();
            }
            return $response;
        } else {
            $model->updateCounters(['frequency'=>1]);
            return $model->response;
        }                
    }

    private function isAparatDotComUrl($url)
    {
        
    }


    private function simulateAparatEmbedly($url)
    {
        
    }


    private function getEmbedResponse($url,$type = Embed::TYPE_OEMBED)
    {
        $keys       =   Yii::$app->params['embedlyKeys'];
        $key        =   $keys[array_rand($keys)];
        $api        =   new Embedly([
                            'key'       =>  $key,
                            'user_agent'=>  Yii::$app->request->userAgent
                        ]);
        if ($type === Embed::TYPE_OEMBED){
            return $api->oembed($url);
        }
        return $api->extract($url);
    }

    /**
     * 
     * @param string $url
     * @param string $type
     * @return Embed|NULL
     */
    private function loadModel($url,$type = Embed::TYPE_OEMBED)
    {
        $hash   =   md5($url);
        $embeds =   Embed::find()->where('hash=:hash AND type=:type',[':hash'=>$hash,':type'=> $type]);
        
        foreach ($embeds->each() as $embed){
            if ($embed->url === $url){
                return $embed;
            }
        }
        return NULL;
    }
}
