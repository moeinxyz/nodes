<?php

namespace app\modules\post\controllers;
use Yii;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
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
                        'verbs'     =>  ['POST'],
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
            $file = Yii::getAlias("@runtimeTemp/{$model->url}");
            @$model->file->saveAs($file);
            $remoteFile =   Yii::getAlias("@ftpImages/{$model->url}");
            $this->resizeImage($file);
            $content    =   file_get_contents($file);
            Yii::$app->ftpFs->put($remoteFile, $content);
            unlink($file);
            return Yii::getAlias("@upBaseUrl/{$model->url}");
        } else {
            throw new NotFoundHttpException;
        }
    }
    
    private function resizeImage($file)
    {
        $image = \yii\imagine\Image::getImagine()->open($file);
        if ($image->getSize()->getWidth() > 700)
        {
            $height = (700 * $image->getSize()->getHeight()) / $image->getSize()->getWidth();
            $image->resize(new \Imagine\Image\Box(700, $height))
                    ->save($file);
        }
    }
}
