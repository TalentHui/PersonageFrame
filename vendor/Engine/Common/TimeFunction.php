<?php
/********************************************************************************
 * Copyright: PhpStorm - PersonageFrame - TimeFunction.php
 * Author:    WuHui
 * Date:      2017-08-25 23:50
 * Desc:      时间
 *******************************************************************************/

namespace Engine\Common;


class TimeFunction
{
    /**
     * @desc  获取某个时间所在周某一天的零点时间戳
     * @explore dailyLinuxTimeOfWeek(0, time() - 7*86400)  获取上周周日的时间戳
     * @param int $week 0-6 周天到周六
     * @param int $time 获取时间所在周 默认为 0, 使用当前时间
     * @return false|int
     */
    public static function dailyLinuxTimeOfWeek($week = 1, $time = 0)
    {
        $date = $_SERVER['REQUEST_TIME'] + 86400;

        if (!empty($time)) {

            $date = $time;
        }

        $int_to_week = array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday ');

        $week_linux_time  = ($week != date('w', $date))
            ? strtotime('last ' . $int_to_week[$week], $date)
            : strtotime($int_to_week[$week], $date);

        return strtotime(date('Y-m-d 00:00:00', $week_linux_time));
    }
}