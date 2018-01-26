<?php
/********************************************************************************
 * Copyright: PhpStorm - PersonageFrame - RedisPool.php
 * Author:    WuHui
 * Date:      2017-08-28 09:05
 * Desc:      redis 链接池
 *******************************************************************************/

namespace Engine\DB;


class RedisPool
{
    /** @var PHPRedis[] redis连接池容器 */
    protected static $redis_pool = array();

    public static function getRedisObject(array $redis_conf = array(), $is_master = true)
    {
        $connect_md5_sign = md5(json_encode($redis_conf));

        if (empty(self::$redis_pool[$connect_md5_sign])) {
            $redis_object = new PHPRedis($redis_conf, $is_master);
            self::$redis_pool[$connect_md5_sign] = $redis_object;
        }

        return self::$redis_pool[$connect_md5_sign];
    }

    /**
     * @desc   获取redis池子
     * @return PHPRedis[]
     */
    public static function getRedisPool()
    {
        return self::$redis_pool;
    }

    /**
     * @desc   从链接池中销毁特定redis对象
     * @param  array $redis_conf
     * @return bool
     */
    public static function destroySinglePool(array $redis_conf = array())
    {
        $connect_md5_sign = md5(json_encode($redis_conf));

        if (!empty(self::$redis_pool[$connect_md5_sign])) {
            unset(self::$redis_pool[$connect_md5_sign]);
        }

        return true;
    }

    /**
     * @desc   销毁整个链接池
     * @return bool
     */
    public static function destroyAllPool()
    {
        foreach (self::$redis_pool as $connect_md5_sign => $redis_object) {
            unset(self::$redis_pool[$connect_md5_sign]);
        }

        return true;
    }
}