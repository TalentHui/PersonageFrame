<?php
/********************************************************************************
 * Copyright: PhpStorm - PersonageFrame - RedisHandler.php
 * Author:    WuHui
 * Date:      2017-08-28 15:03
 * Desc:      redis 句柄
 *******************************************************************************/

namespace Handler;


use Engine\DB\RedisPool;
use Example\Config\RedisConf;

class RedisHandler
{
    public static function LocationRedisHandler()
    {
        return RedisPool::getRedisObject(RedisConf::RedisLocationConfStr(), true);
    }
}