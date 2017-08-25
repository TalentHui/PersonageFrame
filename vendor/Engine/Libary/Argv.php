<?php
/**
 * Copyright: PhpStorm - PersonageFrame - Argv.php
 * Author:    吴辉
 * Date:      2017-04-05 01:46
 * Desc:      获取命令行参数
 */

namespace Engine\Libary;

use Engine\Common\RequestFunction;

class Argv
{
    /**
     * @var array
     */
    private $params = array();

    /**
     * @var string
     */
    private $controller = '';

    /**
     * @var string
     */
    private $action = '';

    /**
     * Argv constructor.
     */
    public function __construct()
    {

        if (RequestFunction::isCommandLine()) {

            $this->setParams($_SERVER['argv']);
        }

    }

    /**
     * @param $key
     * @return bool|mixed
     */
    public function getItem($key)
    {

        return isset($this->params[$key]) ? $this->params[$key] : false;
    }

    /**
     * @return array
     */
    public function getParams()
    {

        return $this->params;
    }

    /**
     * @param $argv
     */
    private function setParams($argv)
    {

        foreach ($argv as $key => $value) {

            if (intval($key) === 1) {

                $this->setController(trim($value));
            } else if (intval($key) === 2) {

                $this->setAction(trim($value));
            } else if (intval($key) >= 3) {

                $split_params = explode('=', $value);
                $this->params[$split_params['0']] = $split_params['1'];
            } else {

                continue;
            }
        }
    }

    /**
     * @desc  设置控制器的名称
     * @param $controller
     */
    private function setController($controller)
    {

        $this->controller = $controller;
    }

    /**
     * @desc   获取控制器的名称
     * @return string
     */
    public function getController()
    {

        return $this->controller;
    }

    /**
     * @desc  设置方法的名称
     * @param $action
     */
    private function setAction($action)
    {

        $this->action = $action;
    }

    /**
     * @desc   获取方法的名称
     * @return string
     */
    public function getAction()
    {

        return $this->action;
    }
}