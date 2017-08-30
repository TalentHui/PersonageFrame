<?php
/********************************************************************************
 * Copyright: PhpStorm - PersonageFrame - Engine.php
 * Author:    吴辉
 * Date:      2017-08-25 22:17
 * Desc:      项目引擎文件
 *******************************************************************************/

namespace Engine\Libary;

use Engine\Base\EmptyController;
use Engine\Behavior\FileLog;
use Engine\Common\RequestFunction;
use Engine\Conf\SystemConf;
use Exception;

final class Engine
{
    /** @var  Get */
    protected $getObject;

    /** @var  Post */
    protected $postObject;

    /** @var  Argv */
    protected $argvObject;

    /** @var  Dispatcher */
    protected $dispatcher;

    /** @var string 工程根目录路径 */
    protected $project_root_directory = '../';

    /** @var string 控制器名 */
    protected $controller = '';

    /** @var string 方法名 */
    protected $action = '';

    public function __construct($project_root_directory)
    {
        /** step-1: 设置项目根目录 */
        if (is_dir($project_root_directory)) {
            $this->project_root_directory = $project_root_directory;
        }

        /** step-2: 设置时区 */
        if (empty(SystemConf::SystemDefaultConf()['SystemDefaultConf'])) {
            date_default_timezone_set('Asia/Shanghai');
        } else {
            date_default_timezone_set(SystemConf::SystemDefaultConf()['SystemDefaultConf']);
        }

        /** step-3: 设置错误提示 */
        if (SystemConf::SystemDefaultConf()['open_error_flush']) {
            error_reporting(E_ALL);
        } else {
            error_reporting(0);
        }

        /** step-4: 注册异常处理 */
        $this->registerExceptionCatch();

        /** step-5: 初始化日志所在工程的根目录 */
        FileLog::setProjectRootDirectory($project_root_directory);
    }

    /**
     * @desc 文件的主方法
     */
    public function run()
    {
        FileLog::init(__METHOD__);

        /** step-1: 实例参数对象 */
        $this->instantiationParamModel();

        /** step-1: 加载模板对象 */
        $this->dispatcher = new Dispatcher($this->project_root_directory);

        /** step-1: 加载-控制器-方法 */
        $this->load_controller_action();
        $this->run_controller_action();
    }

    /**
     * @desc 参数对象 - 实例化参数对象
     */
    protected function instantiationParamModel()
    {
        $this->getObject = new Get();
        $this->postObject = new Post();
        $this->argvObject = new Argv();
    }

    /**
     * @desc 类加载 - 获取控制器 和 方法
     */
    protected function load_controller_action()
    {
        $param_object = RequestFunction::isCommandLine() ? $this->argvObject : $this->getObject;

        $this->controller = $param_object->getController();
        $this->action = $param_object->getAction();
    }

    /**
     * @desc 类加载 - 拼接控制器路径
     */
    protected function run_controller_action()
    {
        $controller = $this->controller ? $this->controller : SystemConf::SystemDefaultConf()['default_controller'];
        $controller_file_path = rtrim($this->project_root_directory, '\\/') . DIRECTORY_SEPARATOR . trim(SystemConf::SystemDefaultConf()['default_controller_path'], '.\\/') . DIRECTORY_SEPARATOR . $controller . '.php';

        if (file_exists($controller_file_path)) {

            $current_load_class = new $controller($this->project_root_directory, $this->getObject, $this->postObject, $this->argvObject, $this->dispatcher);
            $action = $this->action ? $this->action : SystemConf::SystemDefaultConf()['default_action'];
            $current_load_class->$action();
        } else {

            $empty_controller_class = new EmptyController($this->project_root_directory, $this->getObject, $this->postObject, $this->argvObject, $this->dispatcher);
            $action = SystemConf::SystemDefaultConf()['default_action'];
            $empty_controller_class->$action();
        }
    }

    /**
     * @desc 异常函数 - 注册异常捕获函数
     */
    protected function registerExceptionCatch()
    {
        /** 设置用户自定义的错误处理程序，是个静态方法，并且不能是个空方法，否则会报错。好处：脚本会在此异常处理程序被调用后停止执行 */
        set_exception_handler(array('Engine\Libary\Engine', 'defineExceptionCatch'));

        /** 设置用户自定义的错误处理程序，然后触发错误（通过 trigger_error()） - php版本小于7时 */
        if (version_compare(PHP_VERSION, '7.0.0') < 0) {
            set_error_handler(array('Engine\Libary\Engine', 'myErrorHandler'));
        }

        /** 好处: catch the fatal errors */
        register_shutdown_function(array('Engine\Libary\Engine', 'defineShutdownCatch'));
    }

    /**
     * @desc 异常函数 - 定义异常捕获函数 - php7.0
     * @param Exception $exception
     */
    public static function defineExceptionCatch(Exception $exception)
    {
        $message = $exception->getMessage();
        $file = $exception->getFile();
        $line = $exception->getLine();
        $trace = $exception->getTraceAsString();

        $error_info = array(
            'message' => $message,
            'line' => $line,
            'file' => $file,
            'trace' => $trace
        );

        Filelog::logFatal($error_info);

        /** 将buffer中日志刷入文件 */
        fileLog::flushLog();
    }

    /**
     * @desc 异常函数 - 定义异常捕获函数 - php7.0
     * @param $errno
     * @param $errstr
     * @param $errfile
     * @param $errline
     */
    public static function myErrorHandler($errno, $errstr, $errfile, $errline)
    {
        $error_info = array(
            'errfile' => $errfile,
            'errline' => $errline,
            'errno' => $errno,
            'errstr' => $errstr
        );

        Filelog::logFatal($error_info);

        /** 将buffer中日志刷入文件 */
        fileLog::flushLog();
    }

    /**
     * @desc Catch Fatal Errors in PHP
     */
    public static function defineShutdownCatch()
    {
        $last_error = error_get_last();
        Filelog::logFatal($last_error);

        /** 将Fatal Errors刷入文件 */
        fileLog::flushLog();
    }
}