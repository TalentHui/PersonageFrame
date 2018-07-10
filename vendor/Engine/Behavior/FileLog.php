<?php
/********************************************************************************
 * Copyright: PhpStorm - PersonageFrame - FileLog.php
 * Author:    WuHui
 * Date:      2017-08-29 21:35
 * Desc:      日志类
 *******************************************************************************/

namespace Engine\Behavior;


use Engine\Common\RequestFunction;

class FileLog
{
    /** 日志文件名模板 第一个%s 日志前缀 第二个%s 日期 */
    const LOG_FILE_NAME_MODEL_DAILY = '%s_log_%s.logs';

    /**
     * @desc 错误信息列表
     * ERROR_FATAL    严重错误，导致系统崩溃无法使用
     * ERROR_ALERT    警戒性错误， 必须被立即修改的错误
     * ERROR_ERR      一般性错误
     * ERROR_WARNING  警告性错误， 需要发出警告的错误
     * ERROR_NOTICE   通知，程序可以运行但是还不够完美的错误
     * ERROR_DEBUG    调试，用于调试信息
     * ERROR_SQL      SQL错误
     */
    const ERROR_LIST = array(
        'ERROR_FATAL' => 'ERROR_FATAL',
        'ERROR_ALERT' => 'ERROR_ALERT',
        'ERROR_ERR' => 'ERROR_ERR',
        'ERROR_WARNING' => 'ERROR_WARNING',
        'ERROR_NOTICE' => 'ERROR_NOTICE',
        'ERROR_DEBUG' => 'ERROR_DEBUG',
        'ERROR_SQL' => 'ERROR_SQL'
    );

    /** 调试输出 */
    const OUTPUT = '__OUTPUT';

    /** @var bool 服务器系统是否为linux系统 */
    public static $server_system_is_linux = true;

    /** @var string 工程根目录 */
    public static $project_root_directory = '';

    /** @var string 保存日志的目录名称 */
    public static $save_log_directory_name = 'logs';

    /** @var string 日志前缀 */
    public static $log_file_flag = 'default';

    /** @var bool 日志输出状态 */
    public static $log_output_status = false;

    /** @var int buffer行数 */
    public static $log_buffer_length;

    /** @var bool 初始化状态 */
    public static $init_status = false;

    public static $current_log = array(
        'log_type' => '',
        'log_dt' => '',
        'content' => array(),
    );

    public static $log_buffer = [];

    /**
     * @desc  设置工程的根目录
     * @param $project_root_directory
     */
    public static function setProjectRootDirectory($project_root_directory)
    {
        self::$project_root_directory = rtrim($project_root_directory, "\\");
    }

    /**
     * @desc   获取工程的根目录
     * @return string
     */
    public static function getProjectRootDirectory()
    {
        return self::$project_root_directory;
    }

    /**
     * @desc  日志类初始化
     * @param string $log_flag 日志文件名前缀(建议使用__METHOD__做前缀，类似 TestController::getMethodName)
     * @param bool $output_log_status 日志输出状态，默认false  false 不输出 true 输出
     * @param int $buffer_length buff行数
     */
    public static function init($log_flag = '', $output_log_status = false, $buffer_length = 1)
    {
        /** @var array $get_server_system_type 获取服务器操作系统类型 */
        $get_server_system_type = RequestFunction::isLinuxSystemOfServer();

        if (!$get_server_system_type['status']) {
            self::$server_system_is_linux = false;
        }

        if ($log_flag) {
            self::$log_file_flag = $log_flag;
        }

        if ($output_log_status) {
            self::$log_output_status = $output_log_status;
        }

        self::$init_status = true;
        self::$log_buffer_length = $buffer_length;
    }

    /**
     * @desc   获取初始化状态
     * @return bool
     */
    public static function checkInit()
    {
        return self::$init_status;
    }

    /**
     * @desc   获取日志文件名
     * @return string
     */
    public static function getLogFileName()
    {
        $formatter_linux_time = date('Ymd', time());

        if (self::$server_system_is_linux) {
            return sprintf(self::LOG_FILE_NAME_MODEL_DAILY, self::$log_file_flag, $formatter_linux_time);
        }

        $process_flag_of_windows = str_replace(array('::', '\\', '/'), array('_', '.', '.'), self::$log_file_flag);
        return sprintf(self::LOG_FILE_NAME_MODEL_DAILY, $process_flag_of_windows, $formatter_linux_time);
    }

    /**
     * 记录行日志,下次记录日志会启用新行
     * @param string $log_type 日志行标志
     * @param mixed $contents 日志内容
     */
    public static function logs($log_type, $contents)
    {
        self::$current_log['log_type'] = $log_type;
        self::$current_log['log_dt'] = date('Y/m/d H:i:s', time());

        if (!empty($contents)) {
            self::append($contents);
        }

        self::$log_buffer[] = self::$current_log;
        self::$current_log = [];

        if (count(self::$log_buffer) >= self::$log_buffer_length) {
            self::flushLog();
        }
    }

    /**
     * 在当前行追加日志
     * @param mixed $log_info 日志内容
     */
    public static function append($log_info)
    {
        if (is_string($log_info)) {
            self::$current_log['content'][] = $log_info;
        } else if (is_array($log_info)) {

            foreach ($log_info as $key_name => $key_value) {

                if (is_null($key_value)) {
                    $key_value = 'null';
                }

                if (!is_numeric($key_value) && !is_string($key_value)) {
                    $key_value = json_encode($key_value);
                }

                self::$current_log['content'][] = $key_name . ' ' . $key_value;
            }
        }
    }

    public static function flushLog()
    {
        $write_content = '';

        foreach (self::$log_buffer as $log_info) {

            if (empty($log_info['content'])) {

                $log_info['content'] = array();
                continue;
            }

            $write_content .= $log_info['log_dt'] . ' ' . $log_info['log_type'] . ' ' . implode($log_info['content'], ' ') . ' ' . self::infoString() . PHP_EOL;
        }

        self::$log_buffer = [];
        $file_name = self::$project_root_directory . DIRECTORY_SEPARATOR . self::$save_log_directory_name . DIRECTORY_SEPARATOR . self::getLogFileName();
        file_put_contents($file_name, $write_content, FILE_APPEND);
    }

    /**
     * @desc   获取请求的信息
     * @return string
     */
    public static function infoString()
    {
        $get = '-';
        if (!empty($_GET)) {
            $get = json_encode($_GET);
        }

        $uri = '-';
        if (!empty($_SERVER['REQUEST_URI'])) {
            $uri = $_SERVER['REQUEST_URI'];
        }

        $user_agent = '-';
        if (!empty($_SERVER['HTTP_USER_AGENT'])) {
            $user_agent = $_SERVER['HTTP_USER_AGENT'];
        }

        $x_for_ip = '-';
        if (!empty($_SERVER["HTTP_X_FORWARDED_FOR"])) {
            $x_for_ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
        }

        $client_ip = '-';
        if (!empty($_SERVER["HTTP_CLIENT_IP"])) {
            $x_for_ip = $_SERVER["HTTP_CLIENT_IP"];
        }

        $ip = '-';
        if (!empty($_SERVER["REMOTE_ADDR"])) {
            $ip = $_SERVER["REMOTE_ADDR"];
        }

        return $get . ' ' . $uri . ' ' . $user_agent . ' ' . $x_for_ip . ' ' . $client_ip . ' ' . $ip;
    }

    /**
     * OUTPUT级别日志记录
     * @param string|array $contents 日志内容
     */
    public static function logOutput($contents)
    {
        self::logs(self::OUTPUT, $contents);
    }

    /**
     * FATAL级别日志记录
     * @param string|array $contents 日志内容
     */
    public static function logFatal($contents)
    {
        self::logs(self::ERROR_LIST['ERROR_FATAL'], $contents);
    }

    /**
     * AlERT级别日志记录
     * @param string|array $contents 日志内容
     */
    public static function logAlert($contents)
    {
        self::logs(self::ERROR_LIST['ERROR_ALERT'], $contents);
    }

    /**
     * ERR级别日志记录
     * @param string|array $contents 日志内容
     */
    public static function logErr($contents)
    {
        self::logs(self::ERROR_LIST['ERROR_ERR'], $contents);
    }

    /**
     * WARNING级别日志记录
     * @param string|array $contents 日志内容
     */
    public static function logWarning($contents)
    {
        self::logs(self::ERROR_LIST['ERROR_WARNING'], $contents);
    }

    /**
     * NOTICE级别日志记录
     * @param string|array $contents 日志内容
     */
    public static function logNotice($contents)
    {
        self::logs(self::ERROR_LIST['ERROR_NOTICE'], $contents);
    }

    /**
     * DEBUG级别日志记录
     * @param string|array $contents 日志内容
     */
    public static function logDebug($contents)
    {
        self::logs(self::ERROR_LIST['ERROR_DEBUG'], $contents);
    }

    /**
     * SQL级别日志记录
     * @param string|array $contents 日志内容
     */
    public static function logSql($contents)
    {
        self::logs(self::ERROR_LIST['ERROR_SQL'], $contents);
    }
}