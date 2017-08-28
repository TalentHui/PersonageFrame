<?php
/********************************************************************************
 * Copyright: PhpStorm - PersonageFrame - Engine.php
 * Author:    吴辉
 * Date:      2017-08-25 22:17
 * Desc:      项目引擎文件
 *******************************************************************************/

namespace Engine\Libary;

use Engine\Base\EmptyController;
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

    public function __construct($project_root_directory = '')
    {
        if (is_dir($project_root_directory)) {
            $this->project_root_directory = $project_root_directory;
        }

        /** 设置时区 */
        if (empty(SystemConf::SystemDefaultConf()['SystemDefaultConf'])) {
            date_default_timezone_set('Asia/Shanghai');
        } else {
            date_default_timezone_set(SystemConf::SystemDefaultConf()['SystemDefaultConf']);
        }
    }

    /**
     * @desc 文件的主方法
     */
    public function run()
    {
        /** step-1: 注册异常处理 */
        $this->registerExceptionCatch();

        /** step-2: 实例参数对象 */
        $this->instantiationParamModel();

        /** step-3: 加载模板对象 */
        $this->dispatcher = new Dispatcher($this->project_root_directory);

        /** step-4: 加载-控制器-方法 */
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
        $controller_file_path =  rtrim($this->project_root_directory, '\\/') . DIRECTORY_SEPARATOR . trim(SystemConf::SystemDefaultConf()['default_controller_path'], '.\\/') . DIRECTORY_SEPARATOR . $controller . '.php';

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
        /** 设置捕获方法，是个静态方法，并且不能是个空方法，否则会报错 */
        set_exception_handler(array('Engine\Libary\Engine', 'defineExceptionCatch'));
    }

    /**
     * @desc 异常函数 - 定义异常捕获函数
     * @param Exception $exception
     */
    public static function defineExceptionCatch(Exception $exception)
    {
        var_dump($exception);
    }
}