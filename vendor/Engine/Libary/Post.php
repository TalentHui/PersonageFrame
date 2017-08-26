<?php
/**
 * Copyright: PhpStorm - PersonageFrame - Post.php
 * Author:    吴辉
 * Date:      2017-04-05 01:45
 * Desc:      获取 Post 参数
 */

namespace Engine\Libary;

final class Post
{
    /**
     * @var array
     */
    private $params = array();

    public function __construct()
    {

        $this->setParams($_POST);
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
     * @param array $params
     */
    private function setParams(array $params = array())
    {

        $this->params = $params;
    }
}