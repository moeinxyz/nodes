<?php
/**
 * Created by IntelliJ IDEA.
 * User: moein
 * Date: 4/21/17
 * Time: 8:48 PM
 */

namespace app\modules\post\controllers;


use app\modules\post\models\Guestread;
use app\modules\post\models\Post;
use app\modules\post\models\Userread;
use app\modules\post\models\Userrecommend;
use DateTime;
use predictionio\EventClient;
use Yii;
use yii\console\Controller;

class SyncDbWithPredictionIoController extends Controller
{
    private $client;

    public function init()
    {
        parent::init(); // TODO: Change the autogenerated stub
        $this->client = new EventClient(
            Yii::$app->params['predictionIO']['access-key'],
            Yii::$app->params['predictionIO']['url']
        );
    }

    public function actionIndex($type = 'user-read'){
        $type = strtolower($type);
        /**
         * @var $model \yii\db\ActiveRecord
         */
        $postIdField = 'post_id';
        $userIdField = 'user_id';
        $timeField   = 'created_at';

        $condition = 'predictionio_status=:event_status';
        $params    =  [':event_status' => Post::PREDICTION_STATUS_NEW];
        if ($type == 'user-read') {
            $model          = Userread::class;
            $event          = 'read';
        } else if ($type == 'guest-read') {
            $model          = Guestread::class;
            $event          = 'read';
            $userIdField    = 'uuid';
        } else if ($type == 'post') {
            $model          = Post::class;
            $event          = 'write';
            $timeField      = 'published_at';
            $postIdField    = 'id';
            $condition     .= ' AND status=:status';
            $params[':status']= Post::STATUS_PUBLISH;
        } else {
            $model = Userrecommend::class;
            $event          = 'recommend';
        }

        $query = $model::find()
            ->select('*')
            ->where($condition,$params)
            ->limit(50);

        echo "Before Count"."\n";
        if ($query->count() > 0) {
            echo "After Count: ".$query->count()."\n";
            $all = $query->all();
            foreach ($all as $item){
                $time = (new DateTime($item->{$timeField}))->format(EventClient::DATE_TIME_FORMAT);

                if ($this->sendEvent($item->{$userIdField}, $item->{$postIdField}, $time, $event)){
                    $item->predictionio_status = Post::PREDICTION_STATUS_SENT;
                    $item->save();
                }
            }
        } else {
            sleep(60); // sleep for one minute
        }

    }

    private function sendEvent($userId, $postId, $time, $type) {
        $event = [
            'entityType'        => 'user',
            'entityId'          =>  $userId,
            'targetEntityType'  => 'item',
            'targetEntityId'    =>  $postId,
            'time'              =>  $time
        ];

        if ($type == 'write') {
            $event['event'] =   'buy';
        } else if ($type == 'recommend') {
            $event['event']      =   'rate';
            $event['properties'] = ['rating' => 6];
        } else {
            $event['event'] =   'rate';
            $event['properties'] = ['rating' => 3];
        }

        try{
            $this->client->createEvent($event);
            return true;
        } catch (\Exception $exception) {
            return false;
        }
    }
}