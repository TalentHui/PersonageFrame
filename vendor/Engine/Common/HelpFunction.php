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

    /**
     * @desc   获取目录下全部文件
     * @param  string $init_path 要读取的目录
     * @param  array $file_extension 需要读取的文件扩展名（如：array('jpg', 'jpeg', 'png')）
     * @return array
     */
    public static function optimized($init_path = '', $file_extension = array())
    {
        $find_picture_file_list = array();

        if (!is_dir($init_path)) {
            return $find_picture_file_list;
        }

        $get_file_list = scandir($init_path, SCANDIR_SORT_NONE);

        foreach ($get_file_list as $file_name) {
            if (in_array($file_name, array('.', '..'))) {
                continue;
            }

            $package_file_path = $init_path . DIRECTORY_SEPARATOR . $file_name;

            if (is_file($package_file_path)) {
                // 判断后缀
                $current_file_name_extension = substr($file_name, strrpos($file_name, '.') + 1);

                if ($file_extension && !in_array($current_file_name_extension, $file_extension)) {
                    continue;
                }

                $find_picture_file_list[] = str_replace('\\', '/', $package_file_path);
            } else {

                $find_picture_file_list_children = self::optimized($package_file_path, $file_extension);

                if ($find_picture_file_list_children) {
                    $find_picture_file_list = array_merge($find_picture_file_list, $find_picture_file_list_children);
                }
            }
        }

        return $find_picture_file_list;
    }

    /**
     * @desc  自定义输出
     * @param $content
     */
    public static function EchoSelf($content)
    {
        $is_linux = preg_match_all('/win/', strtolower(PHP_OS)) ? false : true;
        $is_command = ((defined('PHP_SAPI') && PHP_SAPI === 'cli') || php_sapi_name() === 'cli') ? true : false;

        if (!$is_linux && $is_command) {
            $content = iconv('UTF-8', 'GBK', $content);
        }

        echo $content . PHP_EOL;
    }

    /**
     * @desc  输出进度条
     * @param int $current_index 当前执行到的元素下标
     * @param int $count_total_index 元素总数
     * @param string $extra 额外描述
     */
    public static function ProgressBar($current_index, $count_total_index, $extra = '')
    {
        $rate = $current_index / $count_total_index;

        /**
         * printf 第一个参数内部元素描述：
         * %-50s       %s 输出字符串，%-50s 打印50个做对其，不足的用 ' ' 填充
         * %d          输出十进制整数
         * %%          第一个%是转义字符,最终输出 %
         * \r          \r是回车，使光标到行首
         * \n          \n是换行，使光标下移一格
         */
        if ($rate >= 1) {
            printf("PROGRESS: [%-50s] @ %d%% FINISH {$extra}\r", str_repeat('.', $rate * 50), $rate * 100);
            echo PHP_EOL;
        } else {
            printf("PROGRESS: [%-50s] @ %d%% ING{$extra}\r", str_repeat('.', $rate * 50), $rate * 100);
        }
    }

    /**
     * @desc   命令行 - 输入
     * @return bool|string
     */
    public static function AcceptCommandInput()
    {
        return fgets(STDIN);
    }

    /**
     * @desc   命令行 - 输出
     * @param  string $output
     */
    public static function AcceptCommandOutput($output = '')
    {
        fwrite(STDOUT, $output . PHP_EOL);
    }

    /**
     * @desc   通过解析UA头部信息，获取系统类型、系统版本、手机型号
     * @param  string $ua_string
     * @return array
     * `````````````````````````````````````````````````````````````````````````````````````````````````````````````````````
     * array (
     *      's' => '系统类型',     // android || ios
     *      's_v' => '系统版本',   // 6.0.0
     *      'p_t' => '手机型号',   // iphone || OP ...
     * )
     * `````````````````````````````````````````````````````````````````````````````````````````````````````````````````````
     */
    public static function parseUaString($ua_string = '')
    {
        $phone_info = array('s' => '', 's_v' => '', 'p_t' => '');

        // android
        if (preg_match_all("/.*Android ([\d.]+); ([\w-_ ]+) Build\/(([\w-._ ]+))/", $ua_string, $match_list)) {
            $phone_info['s'] = 'android';
            $phone_info['s_v'] = trim($match_list['1']['0']);
            $phone_info['p_t'] = trim($match_list['3']['0']);
        }

        // phone
        if (preg_match_all('/\(iPhone; CPU iPhone OS ([\d_]+) ([\w ]+)\)/', $ua_string, $match_list)) {
            $phone_info['s'] = 'ios';
            $phone_info['s_v'] = trim(str_replace('_', '.', $match_list['1']['0']));
            $phone_info['p_t'] = 'iphone';
        }

        return $phone_info;
    }

    /**
     * @desc   获取请求的URL
     * @return string
     */
    public static function getRequestUrl()
    {
        $http_type = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) ? 'https://' : 'http://';
        return $http_type . $_SERVER['SERVER_NAME'] . ':' . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
    }
}