<?php
namespace app\components\Helper;

use Yii;

class Time {

    const MINUTE_IN_SECONDS = 60;
    const HOUR_IN_SECONDS = 3600;
    const DAY_IN_SECONDS = 86400;
    const WEEK_IN_SECONDS = 604800;
    const YEAR_IN_SECONDS = 220752000;

    /**
     * Determines the difference between two timestamps.
     *
     * The difference is returned in a human readable format such as "1 hour",
     * "5 mins", "2 days".
     *
     *
     * @param int $from Unix timestamp from which the difference begins.
     * @param int $to Optional. Unix timestamp to end the time difference. Default becomes time() if not set.
     * @return string Human readable time difference.
     */
    public static function humanDiffTime($from, $to = null) {
        if (is_null($to))
            $to = time();

        $diff = (int) abs($to - $from);
        if ($diff < self::HOUR_IN_SECONDS) {
            $mins = round($diff / self::MINUTE_IN_SECONDS);
            if ($mins <= 1)
                $mins = 1;
            /* translators: min=minute */
            $since = Yii::t('app', 'time.mins.before',['mins'=>$mins]);            
        } elseif ($diff < self::DAY_IN_SECONDS && $diff >= self::HOUR_IN_SECONDS) {
            $hours = round($diff / self::HOUR_IN_SECONDS);
            if ($hours <= 1)
                $hours = 1;
            $since = Yii::t('app', 'time.hours.before',['hours'=>$hours]);            
        } elseif ($diff < self::WEEK_IN_SECONDS && $diff >= self::DAY_IN_SECONDS) {
            $days = round($diff / self::DAY_IN_SECONDS);
            if ($days <= 1)
                $days = 1;
            $since = Yii::t('app', 'time.days.before',['days'=>$days]);            
        } elseif ($diff < 30 * self::DAY_IN_SECONDS && $diff >= self::WEEK_IN_SECONDS) {
            $weeks = round($diff / self::WEEK_IN_SECONDS);
            if ($weeks <= 1)
                $weeks = 1;
            $since = Yii::t('app', 'time.weeks.before',['weeks'=>$weeks]);
        } elseif ($diff < self::YEAR_IN_SECONDS && $diff >= 30 * self::DAY_IN_SECONDS) {
            $months = round($diff / ( 30 * self::DAY_IN_SECONDS ));
            if ($months <= 1)
                $months = 1;
            $since = Yii::t('app', 'time.months.before',['months'=>$months]);
        } elseif ($diff >= self::YEAR_IN_SECONDS) {
            $years = round($diff / self::YEAR_IN_SECONDS);
            if ($years <= 1)
                $years = 1;
            $since = Yii::t('app', 'time.years.before',['years'=>$years]);
        }
        return $since;
    }
}
