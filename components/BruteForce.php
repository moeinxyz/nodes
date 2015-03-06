<?php
namespace app\components;
use yii\web\Request;
use Yii;

class BruteForce extends Request{
    
    /**
     * 
     * @return boolean
     */
    public static function isBlocked($postfix,$times = 15)
    {
        $hash   =   Yii::$app->request->userIP.$postfix;
        $value  =   Yii::$app->redis->get($hash);
        if ($value == NULL || $value < $times)
            return false;
        return true;
    }
    
    
    public static function getTTL($postfix)
    {
        $hash   =   Yii::$app->request->userIP.$postfix;
        return Yii::$app->redis->ttl($hash);
    }

    /**
     * 
     * @param Integer $duration
     */
    public static function addInvalidQuery($postfix,$duration = 300)
    {
        $hash   =   Yii::$app->request->userIP.$postfix;
        Yii::$app->redis->incr($hash);
        Yii::$app->redis->expire($hash,$duration);
    }
    
    public static function clearInvalidQuery()
    {
        $ip     =   Yii::$app->request->userIP;
        Yii::$app->redis->del($ip);
    }
}