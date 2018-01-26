<?php
/********************************************************************************
 * Copyright: PhpStorm - PersonageFrame - PHPRedis.php
 * Author:    WuHui
 * Date:      2017-08-28 08:53
 * Desc:      redis类，封装这个类的原因是因为php自带的redis比使用其他完全封装好的类效率高
 *******************************************************************************/

namespace Engine\DB;

class PHPRedis
{
    /** @var bool 当前操作的数据类型，默认为true【true: master，false: slave】 */
    protected $is_master = true;

    /** @var array redis链接配置 */
    protected $connect_conf = array();

    /** @var  \Redis */
    protected $redis_object;

    protected $error_msg = '';

    /**
     * PHPRedis constructor.
     * @param  array $redis_conf redis配置
     * @param  bool $is_master 默认true true：master，false：slave
     * @throws \Exception
     */
    public function __construct(array $redis_conf = array(), $is_master = true)
    {
        /** 检查是否支持redis扩展 */
        if (!extension_loaded('redis')) {
            throw new \Exception('server does not support redis extension');
        }

        $this->is_master = $is_master;
        $this->configConvert($redis_conf);
        $this->instanceRedisObject();
    }

    /**
     * @desc  当前操作的redis信息
     */
    public function currentOperationRedisInfo()
    {
        return array(
            'is_master' => $this->is_master,
            'connect_conf' => $this->connect_conf
        );
    }

    /**
     * @desc  redis 配置转换
     * @param array $redis_config redis配置
     */
    protected function configConvert(array $redis_config = array())
    {
        /** step-1: 选择连接配置 */
        if ($this->is_master) {
            $select_redis_conf = $redis_config['master']['0'];
        } else {
            $load_redis_slave_conf_list = $redis_config['slave'];
            shuffle($load_redis_slave_conf_list);
            $select_redis_conf = array_pop($load_redis_slave_conf_list);
        }

        /** step-2: 组装配置，如果是字符串配置需要转换为数组类型 */
        if (is_string($select_redis_conf)) {
            $select_redis_conf = str_replace('tcp://', '', $select_redis_conf);
            $conf = explode('?', $select_redis_conf);
            $host_and_port_arr = explode(':', $conf['0']);
            $other_conf = explode(';', $conf['1']);

            $select_redis_conf = array(
                'host' => empty($host_and_port_arr['0']) ? '' : $host_and_port_arr['0'],
                'port' => empty($host_and_port_arr['1']) ? '6379' : $host_and_port_arr['1'],
                'db' => empty($other_conf['db']) ? 0 : $other_conf['db'],
                'time_out' => empty($other_conf['time_out']) ? 0 : $other_conf['time_out'],
                'pwd' => empty($other_conf['pwd']) ? 0 : $other_conf['pwd']
            );
        }

        $this->connect_conf = array(
            'host' => empty($select_redis_conf['host']) ? '127.0.0.1' : $select_redis_conf['host'],
            'port' => empty($select_redis_conf['port']) ? '6379' : $select_redis_conf['port'],
            'db' => empty($select_redis_conf['db']) ? '0' : $select_redis_conf['db'],
            'time_out' => empty($select_redis_conf['time_out']) ? '0' : $select_redis_conf['time_out'],
            'pwd' => empty($select_redis_conf['pwd']) ? '' : $select_redis_conf['pwd'],
        );
    }

    /**
     * @desc   初始化redis对象
     * @throws \Exception
     */
    protected function instanceRedisObject()
    {
        $redis = new \Redis();

        try {
            $redis->connect($this->connect_conf['host'], $this->connect_conf['port'], $this->connect_conf['time_out']);

            if (!empty($this->connect_conf['pwd'])) {
                $redis->auth($this->connect_conf['pwd']);
            }

            $redis->select($this->connect_conf['db']);
        } catch (\Exception $e) {

            throw new \Exception($e->getMessage());
        }

        $this->redis_object = $redis;
    }

    public function getRedisObject()
    {
        return $this->redis_object;
    }
}