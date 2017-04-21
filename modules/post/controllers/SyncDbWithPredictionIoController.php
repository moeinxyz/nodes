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
use predictionio\EventClient;
use Yii;
use yii\console\Controller;

class SyncDbWithPredictionIoController extends Controller
{
    private $client;

    public function __construct($id, Module $module, array $config = [])
    {
        parent::__construct($id, $module, $config);
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
        $time_field = 'created_at';
        if ($type == 'user-read') {
            $model = Userread::class;
            $event = 'read';
        } else if ($type == 'guest-read') {
            $model = Guestread::class;
            $event = 'read';
        } else if ($type == 'post') {
            $model = Post::class;
            $event = 'write';
            $time_field = 'published_at';
        } else {
            $model = Userrecommend::class;
            $event = 'recommend';
        }

        $query = $model::find()
            ->select('*')
            ->where('predictionio_status=:status',[':status'=>Post::PREDICTION_STATUS_NEW])
            ->limit(50);

        if ($query->count() > 0) {
            $all = $query->all();
            foreach ($all as $item){
                if ($this->sendEvent($item->user_id, $item->post_id, $item->{$time_field}, $event)){
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