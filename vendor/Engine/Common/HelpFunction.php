<?php
/********************************************************************************
 * Copyright: PhpStorm - PersonageFrame - HelpFunction.php
 * Author:    WuHui
 * Date:      2018-06-27 16:13
 * Desc:      功能描述
 *******************************************************************************/

namespace Engine\Common;


class HelpFunction
{
    /**
     * @desc  输出进度条
     * @param int $current_index     当前执行到的元素下标
     * @param int $count_total_index 元素总数
     */
    public static function ProgressBar($current_index, $count_total_index)
    {
        $rate = $current_index/$count_total_index;

        /**
         * printf 第一个参数内部元素描述：
         * %-50s       %s 输出字符串，%-50s 打印50个做对其，不足的用 ' ' 填充
         * %d          输出十进制整数
         * %%          第一个%是转义字符,最终输出 %
         * \r          \r是回车，使光标到行首
         * \n          \n是换行，使光标下移一格
         */
        if ($rate >= 1) {
            printf("PROGRESS: [%-50s] @ %d%% FINISH\r", str_repeat('.', $rate*50), $rate*100);
            echo PHP_EOL;
        } else {
            printf("PROGRESS: [%-50s] @ %d%% ING\r", str_repeat('.', $rate*50), $rate*100);
        }
    }

    /**
     * @desc   获取当前使用内存 - 将字节大小转换为人类可视大小
     * @param  int $memory 字节大小
     * @return string
     */
    public static function GetCurrentScriptMemory($memory = 0)
    {
        $memory = empty($memory) ? memory_get_usage() : $memory;
        $unit = array('b', 'kb', 'mb', 'gb', 'tb', 'pb');
        $location_size = @round($memory / pow(1024, ($i = floor(log($memory, 1024)))), 2);
        $location_unit = strtoupper($unit[empty($i) ? 0 : $i]);
        return "{$location_size}({$location_unit})";
    }
}