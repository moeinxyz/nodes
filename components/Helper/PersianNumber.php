<?php
namespace app\components\Helper;
class PersianNumber{
    public static function convertNumberToPersian($string)
    {
        $persian = array('۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹');
        $num = range(0, 9);
        return str_replace($num, $persian, $string);
    }
}