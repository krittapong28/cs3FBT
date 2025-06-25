<?php

namespace App\Helper;

use DateTime;

class Helper
{
    public static function inputToParams($input, $except = ['page'])
    {
        $params = '';
        foreach ($input as $key => $value) {
            if (!in_array($key, $except)) {
                $params = $params . $key . '=' . $value . '&';
            }
        }
        return substr($params, 0, -1);
    }

    public static function validateDate($date, $format = 'Y-m-d H:i:s')
    {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }
}
