<?php
/**
 * Copyright: PhpStorm - PersonageFrame - Get.php
 * Author:    吴辉
 * Date:      2017-04-05 01:45
 * Desc:      获取 Get 参数
 */

namespace Engine\Base;

use Engine\Common\RequestFunction;

class Get
{
    /**
     * @var array
     */
    private $params = array();

    /**
     * @var array
     */
    private $request_url_params = array();

    /**
     * @var string
     */
    private $controller = '';

    /**
     * @var string
     */
    private $action = '';

    /**
     * Get constructor.
     * @param array $application_config
     */
    public function __construct(array $application_config = array())
    {
        if (!RequestFunction::isCommandLine()) {

            $this->request_url_params = parse_url($_SERVER['REQUEST_URI']);

            $model_type = isset($application_config['url_model']) && empty($application_config['url_model']) && in_array($application_config['url_model'], array(1, 2, 3))
                ? intval($application_config['url_model'])
                : 3;

            if ($model_type === 1) {

                $this->urlModel1();
            } else if ($model_type === 2) {

                $this->urlModel2();
            } else {

                $this->urlModel3();
            }

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
     * @desc 普通模式的 参数 处理
     */
    private function urlModel1()
    {

        $this->setParams($_GET);
    }

    /**
     * @desc url模式的 参数 处理
     */
    private function urlModel2()
    {

        $this->doParamUrlPath();
    }

    /**
     * @desc 兼容模式的 参数 处理
     */
    private function urlModel3()
    {

        $this->doParamUrlPath();
        $this->doParamUrlQuery();
    }

    /**
     * @desc 处理 parse_url 中的 path 元素
     */
    private function doParamUrlPath()
    {

        $param_url_path = isset($this->request_url_params['path']) ? $this->request_url_params['path'] : '';
        $param_url_path = trim(str_replace('\\', '/', $param_url_path), '/');
        $param_url_path = ltrim($param_url_path, 'index.php/');
        $param_url = explode('/', $param_url_path);

        $param_url_path_array = array();
        $step = 1;

        do {

            if ($step === 1) {

                $value = array_shift($param_url);
                $this->setController(trim($value));

            } else if ($step === 2) {

                $value = array_shift($param_url);
                $this->setAction(trim($value));

            } else {

                $key = array_shift($param_url);
                $value = array_shift($param_url);

                $key = is_null($key) ? '' : $key;
                $value = is_null($value) ? '' : $value;

                $param_url_path_array[$key] = $value;

            }

            $step++;

            if (empty($param_url)) {

                break;

            }

        } while (true);

        $this->setParams($param_url_path_array);
    }

    /**
     * @desc 处理 parse_url 中的 query 元素
     */
    private function doParamUrlQuery()
    {

        $param_url_query = isset($this->request_url_params['query']) ? $this->request_url_params['query'] : '';

        if (empty($param_url_query_array)) {

            return;
        }

        $param_url_query_array = explode('&', $param_url_query);
        $package_url_query_array = array();

        foreach ($param_url_query_array as $value) {

            $value_array = explode('=', $value);

            $key = isset($value_array['0']) ? $value_array['0'] : '';
            $value = isset($value_array['1']) ? $value_array['1'] : '';

            $package_url_query_array[$key] = $value;
        }

        $this->setParams($package_url_query_array);

    }

    /**
     * @param $params
     */
    private function setParams($params)
    {

        $this->params = $params;
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