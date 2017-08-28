<?php
/********************************************************************************
 * Copyright: PhpStorm - PersonageFrame - RedisConf.php
 * Author:    WuHui
 * Date:      2017-08-28 09:15
 * Desc:      redis 配置 - 支持负载均衡，主库一个配置，从库可以有多个配置（随机选择一个配置）
 *            支持数组和字符串两种配置方式
 *            主库 master 用于写， 从库 slave 用于读
 *******************************************************************************/

namespace Config;


class RedisConf
{
    /**
     * @desc   使用php自带redis查询key类型，返回的是整形数值，可以通过这个关系表转换
     * @return array
     */
    public static function redisKeyTypeList()
    {
        return array(
            'other',
            'string',
            'set',
            'list',
            'zset',
            'hash'
        );
    }

    /**
     * @desc   本地redis配置，字符串类型
     * @return array
     */
    public static function RedisLocationConfStr()
    {
        return array(
            'master' => array(
                'tcp://127.0.0.1:6379?db=0;time_out=0;pwd='
            ),
            'slave' => array(
                'tcp://127.0.0.1:6379?db=0;time_out=0;pwd=',
                'tcp://127.0.0.1:6379?db=0;time_out=0;pwd='
            )
        );
    }

    /**
     * @desc   本地redis配置，数组类型
     * @return array
     */
    public static function RedisLocationConfArr()
    {
        return array(
            'master' => array(
                array(
                    'host' => '127.0.0.1',
                    'port' => '6379',
                    'db' => '0',
                    'time_out' => 0,
                    'pwd' => ''
                )
            ),
            'slave' => array(
                array(
                    'host' => '127.0.0.1',
                    'port' => '6379',
                    'db' => '0',
                    'time_out' => 0,
                    'pwd' => ''
                ),
                array(
                    'host' => '127.0.0.1',
                    'port' => '6379',
                    'db' => '0',
                    'time_out' => 0,
                    'pwd' => ''
                )
            )
        );
    }
}