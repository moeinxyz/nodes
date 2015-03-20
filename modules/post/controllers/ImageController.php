<?php

namespace app\modules\post\controllers;
use Yii;
use yii\filters\AccessControl;
use yii\web\Response;
use app\modules\post\models\Image;
use yii\web\UploadedFile;
class ImageController extends \yii\web\Controller
{
    public function behaviors()
    {
        return [
            'access'    =>  [
                'class' =>  AccessControl::className(),
                'only'  =>  ['upload'],
                'rules' =>  [
                    [
                        'actions'   =>  ['upload'],
                        'roles'     =>  ['@'],
                        'allow'     =>  true
                    ]
                ],
                
            ]
        ];
    }
    
    public function actionUpload($id)
    {
        Yii::$app->response->format =   Response::FORMAT_JSON;
        $model                      =   new Image;
        $model->file    =   UploadedFile::getInstanceByName('file');
        $model->post_id =   base_convert($id, 36, 10);
        if ($model->save()){
            $file = Yii::getAlias("@uploaded/{$model->url}");
            $model->file->saveAs($file);
            $remoteFile =   Yii::getAlias("@ftp/p/{$model->url}");
            $content = file_get_contents($file);
//            Yii::$app->ftpFs->put($remoteFile, $content);
            unlink($file);
            return Yii::getAlias("@upBaseUrl/{$model->url}");
        } else {
            throw new \yii\web\NotFoundHttpException;
        }
    }
}
