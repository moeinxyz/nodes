<?php
/**
 * Created by IntelliJ IDEA.
 * User: moein
 * Date: 4/18/17
 * Time: 11:17 PM
 */

namespace app\modules\post\helper;

use app\modules\post\models\Post;
use Yii;

class Helper {
    public static function getPostPublishTime(Post $post){
        $t1 =   strtotime($post->published_at);
        $t2 =   strtotime(Yii::$app->params['zeroTime']);
        $diff = abs($t1 - $t2);

        // To solve daylight in every server configuration!
        if ($diff <= 3600 * 24){
            return time();
        }

        return $t1;
    }
}