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
        if ($post->published_at === NULL) {
            return time();
        }

        return strtotime($post->published_at);
    }
}