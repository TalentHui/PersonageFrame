<?php
/********************************************************************************
 * Copyright: PhpStorm - PersonageFrame - BaseController.php
 * Author:    WuHui
 * Date:      2017-08-25 23:44
 * Desc:      控制器基类
 *******************************************************************************/

namespace Engine\Base;


use Engine\Common\RequestFunction;
use Engine\Libary\Argv;
use Engine\Libary\Dispatcher;
use Engine\Libary\Get;
use Engine\Libary\Post;

abstract class BaseController
{
    /** @var string 工程的根目录 */
    protected $project_root_directory = '';

    /** @var Get*/
    protected $getParams;

    /** @var Post */
    protected $postParams;

    /** @var Argv */
    protected $argvParams;

    /** @var  Dispatcher */
    protected $dispatcher;

    /**
     * BaseController constructor.
     * @param string $project_root_directory 工程的根目录
     * @param Get $get_object
     * @param Post $post_object
     * @param Argv $argv_object
     * @param Dispatcher $dispatcher
     */
    public function __construct($project_root_directory = '', Get $get_object, Post $post_object, Argv $argv_object, Dispatcher $dispatcher)
    {
        $this->project_root_directory = $project_root_directory;
        $this->getParams = $get_object;
        $this->postParams = $post_object;
        $this->argvParams = $argv_object;
        $this->dispatcher = $dispatcher;
    }

    /**
     * @desc  调用不存在的方法时启用
     * @param $name
     * @param $arguments
     */
    final public function __call($name, $arguments)
    {
        if (RequestFunction::isCommandLine()) {

            echo '************************** Error * Message ***************************' . PHP_EOL;
            echo '* Sorry ! The method you load does not exist' . PHP_EOL;
            echo '**********************************************************************' . PHP_EOL;
            echo '* Controller : ' . $this->argvParams->getController() . PHP_EOL;
            echo '* Action     : ' . $this->argvParams->getAction() . PHP_EOL;
            echo '**********************************************************************' . PHP_EOL;
        } else {

           $this->dispatcher->loadTemplateView();
        }
    }

    /**
     * @desc  调用不存在的静态方法时启用
     * @param $name
     * @param $arguments
     */
    final public static function __callStatic($name, $arguments)
    {

    }
}